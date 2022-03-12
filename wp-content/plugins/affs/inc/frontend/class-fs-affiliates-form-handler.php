<?php

/**
 *  Handles forms
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}
if ( ! class_exists( 'FS_Affiliates_Form_Handler' ) ) {

    /**
     * Class
     */
    class FS_Affiliates_Form_Handler {

        /**
         * Error messages.
         */
        private static $errors = array() ;

        /**
         * Update messages.
         */
        private static $messages = array() ;

        /**
         * Plugin slug.
         */
        private static $plugin_slug = 'fs_affiliates' ;

        /**
         * Class Initialization.
         */
        public static function init() {
            add_action( 'wp_loaded' , array( __CLASS__ , 'validate_login_form' ) ) ;
            add_action( 'wp_loaded' , array( __CLASS__ , 'validate_reset_password_form' ) ) ;
            add_action( 'wp_loaded' , array( __CLASS__ , 'redirect_reset_password_link' ) ) ;
            add_action( 'wp_loaded' , array( __CLASS__ , 'validate_register_form' ) ) ;
            add_action( 'wp_loaded' , array( __CLASS__ , 'validate_opt_in_form' ) ) ;
            add_action( 'init' , array( __CLASS__ , 'coupon_restriction' ) ) ;
        }

        /**
         * Add a message.
         */
        public static function add_message( $text ) {
            self::$messages[] = $text ;
        }

        /**
         * Add an error.
         */
        public static function add_error( $text ) {
            self::$errors[] = $text ;
        }

        /**
         * Output messages + errors.
         */
        public static function show_messages() {
            if ( count( self::$errors ) > 0 ) {
                foreach ( self::$errors as $error ) {
                    self::show_error( $error ) ;
                }
            } elseif ( count( self::$messages ) > 0 ) {
                foreach ( self::$messages as $message ) {
                    self::show_message( $message ) ;
                }
            }

            self::$errors   = array() ;
            self::$messages = array() ;
        }

        /**
         * Output a message.
         */
        public static function show_message( $message ) {
            echo '<div class="fs_affiliates_message"><p><strong><i class="fa fa-check"></i>' . $message . '</strong></p></div>' ;
        }

        /**
         * Output a error.
         */
        public static function show_error( $error ) {
            echo '<div class="fs_affiliates_error"><p><strong><i class="fa fa-exclamation-triangle"></i>' . $error . '</strong></p></div>' ;
        }

        /**
         * Signon by user name and password
         */
        public static function signon( $login_creds ) {

            // On multisite, ensure user exists on current site, if not add them before allowing login.
            if ( is_multisite() ) {
                $user_data = get_user_by( is_email( $creds[ 'user_login' ] ) ? 'email' : 'login' , $creds[ 'user_login' ] ) ;

                if ( $user_data && ! is_user_member_of_blog( $user_data->ID , get_current_blog_id() ) ) {
                    add_user_to_blog( get_current_blog_id() , $user_data->ID , 'customer' ) ;
                }
            }

            // Perform the login
            $user = wp_signon( $login_creds , is_ssl() ) ;

            if ( is_wp_error( $user ) ) {
                do_action( 'fp_affiliates_failed_login' ) ;
                throw new Exception( $user->get_error_message() ) ;
            }

            return $user ;
        }

        /**
         * redirect reset password link
         */
        public static function redirect_reset_password_link() {
            if ( isset( $_GET[ 'fs_affiliates_reset_key' ] ) && ( isset( $_GET[ 'id' ] ) ) ) {

                $user       = get_user_by( 'id' , absint( $_GET[ 'id' ] ) ) ;
                $user_login = $user ? $user->user_login : '' ;

                $value = sprintf( '%s:%s' , wp_unslash( $user_login ) , wp_unslash( $_GET[ 'fs_affiliates_reset_key' ] ) ) ;

                FS_Affiliates_Shortcodes::set_reset_password_cookie( $value ) ;

                $lost_password_page = get_permalink( fs_affiliates_get_page_id( 'lost_password' ) ) ;

                wp_safe_redirect( add_query_arg( 'fs-affiliates-show-reset-form' , 'true' , $lost_password_page ) ) ;
                exit ;
            }
        }

        /**
         * Validate lost password Form
         */
        public static function validate_lost_password_form() {
            if ( ! isset( $_POST[ 'fs_affiliates_email' ] ) )
                return ;

            try {

                $email = $_POST[ 'fs_affiliates_email' ] ;

                $nonce_value = isset( $_POST[ 'fs-affiliates-lost-password-nonce' ] ) ? $_POST[ 'fs-affiliates-lost-password-nonce' ] : null ;
                if ( ! wp_verify_nonce( $nonce_value , 'fs-affiliates-lost-password' ) )
                    throw new Exception( __( "Invalid Request" , FS_AFFILIATES_LOCALE ) ) ;

                if ( empty( $email ) ) {
                    throw new Exception( __( "Please enter a valid username or email address" , FS_AFFILIATES_LOCALE ) ) ;
                }

                $user_data = get_user_by( 'login' , $email ) ;

                if ( ! $user_data && is_email( $email ) ) {
                    $user_data = get_user_by( 'email' , $email ) ;
                }

                if ( ! $user_data ) {
                    throw new Exception( __( "Invalid username or email address" , FS_AFFILIATES_LOCALE ) ) ;
                }

                if ( is_multisite() && ! is_user_member_of_blog( $user_data->ID , get_current_blog_id() ) ) {
                    throw new Exception( __( "Invalid username or email address" , FS_AFFILIATES_LOCALE ) ) ;
                }

                // Get password reset key
                $key = get_password_reset_key( $user_data ) ;

                do_action( 'fs_affiliates_reset_password_notification' , $user_data , $key ) ;
            } catch ( Exception $ex ) {

                unset( $_POST[ 'fs_affiliates_email' ] ) ;

                self::add_error( $ex->getMessage() ) ;
            }
        }

        /**
         * Validate reset password form
         */
        public static function validate_reset_password_form() {
            $nonce_value = isset( $_POST[ 'fs-affiliates-reset-password-nonce' ] ) ? $_POST[ 'fs-affiliates-reset-password-nonce' ] : null ;
            if ( ! wp_verify_nonce( $nonce_value , 'fs-affiliates-reset-password' ) )
                return ;

            try {

                $user = FS_Affiliates_Shortcodes::check_password_reset_key( $_POST[ 'fs_affiliates_reset_key' ] , $_POST[ 'fs_affiliates_reset_login' ] ) ;

                if ( $user instanceof WP_User ) {
                    if ( empty( $_POST[ 'fs_affiliates_password' ] ) ) {
                        throw new Exception( __( "Please enter a valid password" , FS_AFFILIATES_LOCALE ) ) ;
                    }

                    if ( empty( $_POST[ 'fs_affiliates_confirm_password' ] ) ) {
                        throw new Exception( __( "Please repeat the password" , FS_AFFILIATES_LOCALE ) ) ;
                    }

                    if ( $posted_fields[ 'password_1' ] !== $posted_fields[ 'password_2' ] ) {
                        throw new Exception( __( "Passwords do not match" , FS_AFFILIATES_LOCALE ) ) ;
                    }

                    FS_Affiliates_Shortcodes::reset_password( $user , $_POST[ 'fs_affiliates_password' ] ) ;
                    wp_redirect( add_query_arg( 'fs_affiliates_reset_password_success' , 'true' , get_permalink( fs_affiliates_get_page_id( 'login' ) ) ) ) ;
                    exit ;
                }
            } catch ( Exception $ex ) {
                self::add_error( $ex->getMessage() ) ;
            }
        }

        /**
         * Validate Login Form
         */
        public static function validate_login_form() {
            $nonce_value = isset( $_POST[ 'fs-affiliates-login-nonce' ] ) ? $_POST[ 'fs-affiliates-login-nonce' ] : null ;
            if ( empty( $_POST[ 'fs-affiliates-action' ] ) || ! wp_verify_nonce( $nonce_value , 'fs-affiliates-login' ) )
                return ;

            try {
                if ( ! isset( $_POST[ 'user_name' ] ) || $_POST[ 'user_name' ] == '' ) {
                    throw new Exception( __( "Invalid username or email address" , FS_AFFILIATES_LOCALE ) ) ;
                }

                self::validate_google_captcha() ;

                $login_creds = array(
                    'user_login'    => trim( $_POST[ 'user_name' ] ) ,
                    'user_password' => $_POST[ 'password' ] ,
                    'remember'      => isset( $_POST[ 'rememberme' ] )
                        ) ;

                self::signon( $login_creds ) ;

                wp_redirect( get_permalink( fs_affiliates_get_page_id( 'dashboard' ) ) ) ;
                exit ;
            } catch ( Exception $ex ) {
                self::add_error( $ex->getMessage() ) ;
            }
        }

        /**
         * Validate required fields
         */
        public static function validate_required_field( $meta_data , $type = 'default' ) {
            $fields = fs_affiliates_get_form_fields() ;

            foreach ( $meta_data as $meta_key => $meta_name ) {
                if ( ! array_key_exists( $meta_key , $fields ) )
                    continue ;

                extract( $fields[ $meta_key ] ) ;

                if ( $field_required != 'mandatory' )
                    continue ;

                if ( $meta_key == 'file_upload' )
                    $meta_data[ $meta_key ] = get_transient( $meta_data[ 'uploaded_key' ] ) ;

                if ( empty( $meta_data[ $meta_key ] ) )
                    if ( $type == 'checkout' ) {
                        throw new Exception( sprintf( __( '<strong>%s</strong> is a required field.' , FS_AFFILIATES_LOCALE ) , $field_name ) ) ;
                    } else {
                        throw new Exception( sprintf( __( '<strong>ERROR :</strong> %s Required' , FS_AFFILIATES_LOCALE ) , $field_name ) ) ;
                    }
            }
        }

        /**
         * Validate Register Form
         */
        public static function validate_register_form() {
            $nonce_value = isset( $_POST[ 'fs-affiliates-register-nonce' ] ) ? $_POST[ 'fs-affiliates-register-nonce' ] : null ;
            if ( empty( $_POST[ 'fs-affiliates-action' ] ) || ! wp_verify_nonce( $nonce_value , 'fs-affiliates-register' ) )
                return ;

            try {

                $meta_data = $_POST[ 'affiliate' ] ; //get data from post

                self::validate_required_field( $meta_data ) ;

                $new_account       = false ;
                $user_id           = false ;
                $account_type      = fs_affiliates_get_account_creation_type() ;
                $required_approval = get_option( 'fs_affiliates_admin_approval_required' ) ;

                if ( $account_type == 'new_account' || ($account_type == 'user_decide' && $meta_data[ 'user_selection_type' ] == 'new') ) {
                    $new_account = true ;
                }

                if ( $new_account ) {

                    if ( ! filter_var( $meta_data[ 'email' ] , FILTER_VALIDATE_EMAIL ) ) {
                        throw new Exception( __( 'Please enter a valid email' , FS_AFFILIATES_LOCALE ) ) ;
                    }

                    if ( isset( $meta_data[ 'repeated_password' ] ) ) {
                        if ( $meta_data[ 'password' ] != $meta_data[ 'repeated_password' ] ) {
                            throw new Exception( __( 'Passwords do not match' , FS_AFFILIATES_LOCALE ) ) ;
                        }
                    }

                    $user_data = array(
                        'user_login' => $meta_data[ 'user_name' ] ,
                        'user_pass'  => $meta_data[ 'password' ] ,
                        'user_email' => $meta_data[ 'email' ] ,
                        'first_name' => isset( $meta_data[ 'first_name' ] ) ? $meta_data[ 'first_name' ] : '' ,
                        'last_name'  => isset( $meta_data[ 'last_name' ] ) ? $meta_data[ 'last_name' ] : '' ,
                        'role'       => get_option( 'fs_affiliates_user_role_type' , 'subscriber' ) ,
                        'user_url'   => isset( $meta_data[ 'website' ] ) ? $meta_data[ 'website' ] : ''
                            ) ;

                    $user_name = $meta_data[ 'user_name' ] ;
                } else {

                    if ( ! isset( $meta_data[ 'user_id' ] ) ) {
                        throw new Exception( __( 'Cannot modify userid' , FS_AFFILIATES_LOCALE ) ) ;
                    }

                    $user_id = $meta_data[ 'user_id' ] ;
                    $user    = get_user_by( 'id' , $meta_data[ 'user_id' ] ) ;
                    if ( ! $user ) {
                        throw new Exception( __( 'User not exists' , FS_AFFILIATES_LOCALE ) ) ;
                    }

                    $user_name                = $user->user_login ;
                    $meta_data[ 'email' ]     = $user->user_email ;
                    $meta_data[ 'user_role' ] = $user->role ;
                    $meta_data[ 'website' ]   = isset( $meta_data[ 'website' ] ) ? $meta_data[ 'website' ] : $user->user_url ;
                    $meta_data[ 'user_id' ]   = $user_id ;
                }

                if ( ! isset( $_POST[ 'iagree' ] ) ) {
                    throw new Exception( __( 'Please accept the Terms of Service' , FS_AFFILIATES_LOCALE ) ) ;
                }

                self::validate_google_captcha() ;

                $error = apply_filters( 'fs_affiliates_registration_errors' , '' , $meta_data ) ;

                if ( $error )
                    throw new Exception( $error ) ;

                if ( $new_account && ! $user_id ) {
                    $user_id = fs_affiliates_insert_user( $user_data ) ;
                    if ( ! $user_id ) {
                        throw new Exception( __( 'Something happened please try again' , FS_AFFILIATES_LOCALE ) ) ;
                    }
                }

                $meta_data[ 'date' ]    = time() ;
                $meta_data[ 'user_id' ] = $user_id ;
                $meta_data[ 'status' ]  = ($required_approval == 'yes') ? 'fs_pending_approval' : 'fs_active' ;
                $meta_data[ 'status' ]  = apply_filters( 'fs_affiliate_status_while_submit_application' , $meta_data[ 'status' ] , $meta_data ) ;

                $parent_affiliate_id = fs_affiliates_get_id_from_cookie( 'fsaffiliateid' ) ;
                $parent_affiliate_id = ($parent_affiliate_id) ? $parent_affiliate_id : fs_affiliates_get_default_parent_affiliate() ;
                $post_args           = array(
                    'post_status' => $meta_data[ 'status' ] ,
                    'post_author' => $user_id ,
                    'post_parent' => $parent_affiliate_id ,
                    'post_title'  => $user_name ,
                        ) ;

                $meta_data[ 'uploaded_files' ]     = isset( $meta_data[ 'uploaded_key' ] ) ? get_transient( $meta_data[ 'uploaded_key' ] ) : '' ;
                $meta_data[ 'signup_visit_id' ]    = fs_affiliates_get_id_from_cookie( 'fsvisitid' ) ;
                $meta_data[ 'signup_campaign_id' ] = fs_affiliates_get_id_from_cookie( 'fscampaign' , '' ) ;

                $affiliate_id = fs_affiliates_create_new_affiliate( $meta_data , $post_args ) ;

                // Update Payment Data from admin
                if ( '2' == get_option( 'fs_affiliates_payment_method_selection_type' , '1' ) ) {
                    fs_update_affiliate_payment_data( $affiliate_id , get_option( 'fs_affiliates_admin_payment_method' , 'direct' ) , 'new' ) ;
                }

                do_action( 'fs_affiliates_frontend_register_form_submitted' , $affiliate_id ) ;

                if ( isset( $_POST[ 'iagree_mail_subscribe' ] ) ) {
                    $email = $meta_data[ 'email' ] ;
                    fs_affiliates_access_mail_api( $email , $meta_data , 'register' ) ;
                }

                if ( $new_account ) {
                    $login_creds = array(
                        'user_login'    => trim( $meta_data[ 'user_name' ] ) ,
                        'user_password' => $meta_data[ 'password' ] ,
                        'remember'      => false
                            ) ;

                    self::signon( $login_creds ) ;
                }

                $redirect = get_permalink( fs_affiliates_get_page_id( 'dashboard' ) ) ;
                $redirect = add_query_arg( 'fs_status' , get_post_status( $affiliate_id ) , $redirect ) ;
                wp_redirect( $redirect ) ;
                exit ;
            } catch ( Exception $ex ) {
                self::add_error( $ex->getMessage() ) ;
            }
        }

        /**
         * Validate Email opt in Form
         */
        public static function validate_opt_in_form() {

            $nonce_value = isset( $_POST[ 'fs-affiliates-opt-in-nonce' ] ) ? $_POST[ 'fs-affiliates-opt-in-nonce' ] : null ;
            if ( empty( $_POST[ 'fs-affiliates-action' ] ) || ! wp_verify_nonce( $nonce_value , 'fs-affiliates-opt-in' ) )
                return ;

            try {
                $meta_data = $_POST[ 'optinform' ] ;

                self::validate_required_field( $meta_data ) ;

                if ( ! filter_var( $meta_data[ 'email' ] , FILTER_VALIDATE_EMAIL ) ) {
                    throw new Exception( __( 'Please enter a valid email' , FS_AFFILIATES_LOCALE ) ) ;
                }

                $status = fs_affiliates_access_mail_api( $meta_data[ 'email' ] , $meta_data ) ;
                if ( $status == 'mail_added' ) {
                    $message = __( 'You have successfully subscripted to our newsletter' , FS_AFFILIATES_LOCALE ) ;
                } else {
                    $message = __( 'You need to verify your email before we can add your email to our newsletter subscription list' , FS_AFFILIATES_LOCALE ) ;
                }
                self::add_message( __( "$message" , FS_AFFILIATES_LOCALE ) ) ;
            } catch ( Exception $ex ) {
                self::add_error( $ex->getMessage() ) ;
            }
        }

        /**
         * Validate Google reCaptcha
         */
        public static function validate_google_captcha() {
            if ( ! isset( $_POST[ 'g-recaptcha-response' ] ) )
                return ;

            $gcaptcha_site_key = get_option( 'fs_affiliates_recaptcha_site_key' , '' ) ;
            if ( $gcaptcha_site_key == '' )
                return ;

            if ( isset( $_POST[ 'g-recaptcha-response' ] ) && $_POST[ 'g-recaptcha-response' ] == '' )
                throw new Exception( __( 'Please provide a google captacha' , FS_AFFILIATES_LOCALE ) ) ;

            $response = fs_affiliates_verify_captcha() ;

            if ( $response[ 'success' ] == false )
                throw new Exception( 'Google Re-Captcha Error: ' . $response[ 'error-codes' ][ 0 ] ) ;
        }

        /**
         * Restrict Coupon
         */
        public static function coupon_restriction() {
            $restriction_option = get_option( 'fs_affiliates_wc_coupon_restrict' , 'no' ) ;

            if ( 'yes' == $restriction_option ) {
                $user_id = get_current_user_id() ;

                if ( empty( $user_id ) ) {
                    return false ;
                }

                $affiliate_id = fs_get_affiliate_id_for_user( $user_id ) ;

                if ( ! $affiliate_id || empty( $affiliate_id ) ) {
                    return false ;
                }

                add_filter( 'woocommerce_coupon_is_valid' , function ($bool) {
                    return false ;
                } ) ;

                add_filter( 'woocommerce_coupon_error' , function () {
                    return get_option( 'fs_affiliates_wc_coupon_restrict_msg' , esc_html__( 'You are restricted to use this coupon' , FS_AFFILIATES_LOCALE ) ) ;
                } ) ;
            }
        }

    }

    FS_Affiliates_Form_Handler::init() ;
}

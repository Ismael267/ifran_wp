<?php
/**
 * Shortcodes
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}
if ( ! class_exists( 'FS_Affiliates_Shortcodes' ) ) {

    /**
     * Class.
     */
    class FS_Affiliates_Shortcodes {

        /**
         * Plugin slug.
         */
        private static $plugin_slug = 'fs_affiliates' ;

        /**
         * Already included CSS.
         */
        private static $already_included_css = false ;

        /**
         * Class Initialization.
         */
        public static function init() {

            $shortcodes = array(
                'fs_affiliates_login' ,
                'fs_affiliates_application_status' ,
                'fs_affiliates_dashboard' ,
                'fs_affiliates_register' ,
                'fs_affiliates_lost_password' ,
                'fs_affiliates_mlm_tree' ,
                'fs_affiliate_basic_settings' ,
                'fs_affiliate_account_management_settings' ,
                'fs_affiliate_payment_management_settings' ,
                'fs_affiliate_creatives' ,
                'fs_affiliate_referrals' ,
                'fs_affiliate_visits' ,
                'fs_affiliate_payouts' ,
                'fs_affiliate_campaigns' ,
                'fs_affiliate_overview' ,
                'fs_affiliate_link_generator' ,
                'fs_affiliate_name' ,
                'fs_affiliate_username' ,
                'fs_affiliate_id' ,
                'fs_affiliate_link' ,
                'fs_affiliate_email' ,
                'fs_affiliate_referral_code' ,
                'fs_affiliate_logout' ,
                'fs_affiliate_campaigns_overview' ,
                'fs_affiliate_commission_rate' ,
                'fs_affiliate_paid_commission_rate' ,
                'fs_affiliate_unpaid_commission_rate' ,
                'fs_affiliate_overall_commission_rate' ,
                'fs_affiliate_refer_a_friend' ,
                'fs_affiliate_wallet' ,
                'fs_affiliate_pushover_notifications' ,
                'fs_affiliate_leaderboard' ,
                'fs_affiliates_opt_in_form' ,
                'fs_affiliate_wc_coupon_linking' ,
                'fs_affiliate_payment_method_alert' ,
                    ) ;

            foreach ( $shortcodes as $shortcode_name ) {

                add_shortcode( $shortcode_name , array( __CLASS__ , 'process_shortcode' ) ) ;
            }
        }

        /**
         * Process Shortcode
         */
        public static function process_shortcode( $atts , $content , $tag ) {

            $shortcode_name = str_replace( 'fs_affiliates_' , '' , $tag ) ;
            $shortcode_name = str_replace( 'fs_affiliate_' , '' , $shortcode_name ) ;
            $function       = 'shortcode_' . $shortcode_name ;

            switch ( $shortcode_name ) {
                case 'application_status':
                case 'dashboard':
                case 'login':
                case 'register':
                case 'lost_password':
                case 'mlm_tree':
                case 'id':
                case 'name':
                case 'email':
                case 'referral_code':
                case 'link':
                case 'username':
                case 'opt_in_form':
                case 'logout':
                case 'paid_commission_rate':
                case 'unpaid_commission_rate':
                case 'overall_commission_rate':
                case 'payment_method_alert':
                    ob_start() ;
                    self::$function( $atts ) ; // output for shortcode
                    $content = ob_get_contents() ;
                    ob_end_clean() ;
                    break ;

                case 'overview':
                case 'basic_settings':
                case 'account_management_settings':
                case 'payment_management_settings':
                case 'link_generator':
                case 'creatives':
                case 'referrals':
                case 'visits':
                case 'payouts':
                case 'campaigns':
                case 'campaigns_overview':
                case 'commission_rate':
                case 'refer_a_friend':
                case 'wallet':
                case 'leaderboard':
                case 'pushover_notifications':
                case 'wc_coupon_linking':
                    $user_id      = get_current_user_id() ;
                    ob_start() ;
                    ?>
                    <div class="fs_affiliates_frontend_dashboard fs_affiliates_frontend_shortcodes">
                        <div class="fs_affiliates_menu_content">
                            <?php
                            if ( ! is_user_logged_in() || ! ($affiliate_id = fs_affiliates_is_user_having_affiliate()) ) {
                                echo __( 'You need to be an Affiliate on this site to view the contents of this page' , FS_AFFILIATES_LOCALE ) ;
                            } else {
                                self::$function( $affiliate_id , $user_id , $atts ) ;
                            }
                            ?>
                        </div>
                    </div>
                    <?php
                    $content = ob_get_contents() ;
                    ob_end_clean() ;
                    break ;
            }

            return $content ;
        }

        /**
         * custom css
         */
        public static function custom_css() {

            if ( self::$already_included_css )
                return ;

            /* Include css */
            include_once(FS_AFFILIATES_PLUGIN_PATH . '/assets/css/frontend/form.php') ;
            /* Include css */
            include_once(FS_AFFILIATES_PLUGIN_PATH . '/assets/css/frontend/dashboard-color-customization.php') ;

            wp_enqueue_script( 'fs_affiliates_file_upload' ) ;
            ?>
            <style type="text/css">
            <?php
            echo get_option( 'fs_affiliates_frontend_custom_css' ) ;
            ?>
            </style>
            <?php
        }

        /**
         * Output Shortcode Dashboard.
         */
        public static function shortcode_dashboard( $atts ) {
            self::custom_css() ;
            $message        = '' ;
            $user_logged_in = is_user_logged_in() ;

            if ( isset( $_GET[ 'fs_status' ] ) ) {

                switch ( $_GET[ 'fs_status' ] ) {
                    case 'fs_hold':
                        $message = __( "Affiliate application submitted successfully. An email with a verification link has been sent to your email id. Please verify your email by clicking the link provided in the email. Your application will not be processed if you don't verify your email." , FS_AFFILIATES_LOCALE ) ;
                        break ;
                    case 'fs_pending_approval':
                        $message = __( "Affiliate application submitted successfully. Site admin will review your application and will get back to you shortly via email. You can also check your application status by login into your account using the credentials which you have provided in your application." , FS_AFFILIATES_LOCALE ) ;
                        break ;
                    case 'fs_active':
                        $message = __( "Affiliate account created successfully. You can manage your affiliate account using the dashboard below." , FS_AFFILIATES_LOCALE ) ;
                        break ;
                }

                if ( ! empty( $message ) )
                    FS_Affiliates_Form_Handler::show_message( $message ) ;

                if ( ! $user_logged_in )
                    return ;
            }

            $registration_method = get_option( 'fs_affiliates_registration_method' ) ;
            if ( ! $user_logged_in && $registration_method == 'basic' ) {
                self::registration_form( $atts ) ;
                self::login_form( $atts ) ;
            } elseif ( ! ($affiliate_id = fs_affiliates_is_user_having_affiliate()) && $registration_method == 'basic' ) {
                self::registration_form( $atts ) ;
            } else {
                self::dashboard( $affiliate_id , $registration_method ) ;
            }
        }

        /**
         * Output shortcode to lost password
         */
        public static function shortcode_lost_password( $atts ) {
            if ( is_user_logged_in() )
                return ;

            self::custom_css() ;

            FS_Affiliates_Form_Handler::validate_lost_password_form() ;

            if ( isset( $_POST[ 'fs_affiliates_email' ] ) ) {
                FS_Affiliates_Form_Handler::show_messages() ;
                ?><p><?php _e( 'A password reset email has been sent to the email address which your provided, but may take several minutes to show up in your inbox. Please wait at least 10 minutes before attempting another reset.' , FS_AFFILIATES_LOCALE ) ; ?></p><?php
                return ;
            } elseif ( isset( $_GET[ 'fs-affiliates-show-reset-form' ] ) ) {
                if ( isset( $_COOKIE[ 'fs-affiliates-resetpass-' . COOKIEHASH ] ) && 0 < strpos( $_COOKIE[ 'fs-affiliates-resetpass-' . COOKIEHASH ] , ':' ) ) {
                    list( $reset_pass_login , $reset_pass_key ) = explode( ':' , wp_unslash( $_COOKIE[ 'fs-affiliates-resetpass-' . COOKIEHASH ] ) , 2 ) ;
                    $user = self::check_password_reset_key( $reset_pass_key , $reset_pass_login ) ;
                    if ( is_object( $user ) ) {
                        /* Include reset password form page */
                        include_once ( 'views/reset-password-form.php' ) ;

                        return ;
                    }
                }
            }

            /* Include lost password form page */
            include_once ( 'views/lost-password-form.php' ) ;
        }

        /**
         * Handles resetting the user's password.
         */
        public static function reset_password( $user , $new_pass ) {

            wp_set_password( $new_pass , $user->ID ) ;
            self::set_reset_password_cookie() ;

            do_action( 'fs_affiliates_password_changed_notification' , $user ) ;
        }

        public static function check_password_reset_key( $key , $login ) {
            // Check for the password reset key.
            // Get user data or an error message in case of invalid or expired key.
            $user = check_password_reset_key( $key , $login ) ;
            if ( is_wp_error( $user ) ) {
                FS_Affiliates_Form_Handler::add_error( __( 'The link to reset the password is invalid. Please submit the new request.' , FS_AFFILIATES_LOCALE ) ) ;
                return false ;
            }

            return $user ;
        }

        /**
         * Set reset password cookie
         */
        public static function set_reset_password_cookie( $value = '' ) {
            $rp_cookie = 'fs-affiliates-resetpass-' . COOKIEHASH ;
            $rp_path   = isset( $_SERVER[ 'REQUEST_URI' ] ) ? current( explode( '?' , wp_unslash( $_SERVER[ 'REQUEST_URI' ] ) ) ) : '' ;

            if ( $value ) {
                setcookie( $rp_cookie , $value , 0 , $rp_path , COOKIE_DOMAIN , is_ssl() , true ) ;
            } else {
                setcookie( $rp_cookie , ' ' , time() - YEAR_IN_SECONDS , $rp_path , COOKIE_DOMAIN , is_ssl() , true ) ;
            }
        }

        /**
         * Output shortcode Login form.
         */
        public static function shortcode_login( $atts ) {
            $registration_method = get_option( 'fs_affiliates_registration_method' ) ;
            if ( $registration_method != 'advanced' || is_user_logged_in() ) {
                wp_redirect( get_permalink( fs_affiliates_get_page_id( 'dashboard' ) ) ) ;
                exit ;
            }

            self::login_form( $atts ) ;
        }

        /**
         * Output Shortcode Register form.
         */
        public static function shortcode_register( $atts ) {
            $registration_method = get_option( 'fs_affiliates_registration_method' ) ;

            if ( $registration_method != 'advanced' ) {
                wp_redirect( get_permalink( fs_affiliates_get_page_id( 'dashboard' ) ) ) ;
                exit ;
            }

            if ( is_user_logged_in() && $affiliate_id = fs_affiliates_is_user_having_affiliate() ) {
                wp_redirect( get_permalink( fs_affiliates_get_page_id( 'dashboard' ) ) ) ;
                exit ;
            }

            self::registration_form( $atts ) ;
        }

        /**
         * Output Login form.
         */
        public static function login_form( $atts ) {

            self::custom_css() ;

            if ( ! empty( $_GET[ 'fs_affiliates_reset_password_success' ] ) ) {
                FS_Affiliates_Form_Handler::add_message( __( 'Your password has been reset successfully.' , FS_AFFILIATES_LOCALE ) ) ;
            }


            do_action( 'fs_affiliates_before_login_form' ) ;
            ?>
            <form class="fs_affiliates_forms" id="fs_affiliates_login_form" action="" method="post">
                <?php
                // Display Error or Messages
                FS_Affiliates_Form_Handler::show_messages() ;

                $gcaptcha_site_key      = get_option( 'fs_affiliates_recaptcha_site_key' ) ;
                $google_captcha_enabled = get_option( 'fs_affiliates_recaptcha_login_page' ) == 'yes' ;

                /* Include Login page */
                if ( apply_filters( 'fs_affiliates_block_unsuccessful_login' , true ) ) {
                    include_once('views/login.php') ;
                } else {
                    $Duration = get_option( 'fs_affiliates_fraud_protection_min_duration' ) ;
                    $Msg      = ($Duration[ 'unit' ] == 'minutes') ? $Duration[ 'number' ] . ' minutes' : $Duration[ 'number' ] . ' hours' ;
                    _e( 'Login attempt count exceeds. Please login after ' . $Msg , FS_AFFILIATES_LOCALE ) ;
                }
                ?>
            </form>
            <?php
            do_action( 'fs_affiliates_after_login_form' ) ;
        }

        /**
         * Output Register form.
         */
        public static function registration_form( $atts ) {
            try {
                $allow_user  = get_option( 'fs_affiliates_allow_users_to_register' ) ;
                $allow_guest = get_option( 'fs_affiliates_allow_guest_to_register' ) ;

                if ( $allow_user == 'no' && is_user_logged_in() )
                    throw new Exception( get_option( 'fs_affiliates_user_restriction_msg' , esc_html__( 'Logged in user cannot register as an affiliate.' , FS_AFFILIATES_LOCALE ) ) ) ;

                if ( $allow_guest == 'no' && ! is_user_logged_in() ) {
                    $link    = '<a href="' . get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) . '">' . esc_html__( 'Signup' , FS_AFFILIATES_LOCALE ) . '</a>' ;
                    $message = str_replace( array( '[woo_signup]' ) , $link , get_option( 'fs_affiliates_guest_restriction_msg' , esc_html__( 'Guest user cannot register as an affiliate.' , FS_AFFILIATES_LOCALE ) ) ) ;
                    throw new Exception( $message ) ;
                }


                self::custom_css() ;

                // Display Error or Messages
                FS_Affiliates_Form_Handler::show_messages() ;

                $account_type           = fs_affiliates_get_account_creation_type() ;
                $fields                 = fs_affiliates_get_form_fields() ;
                do_action( 'fs_affiliates_before_register_form' ) ;
                ?>
                <form class="fs_affiliates_forms" id="fs_affiliates_register_form" action="" method="post">
                    <?php
                    $gcaptcha_site_key      = get_option( 'fs_affiliates_recaptcha_site_key' ) ;
                    $google_captcha_enabled = get_option( 'fs_affiliates_recaptcha_registration_page' ) == 'yes' ;

                    /* Include Register page */
                    include_once('views/register.php') ;
                    ?>
                </form>
                <?php
                do_action( 'fs_affiliates_after_register_form' ) ;
            } catch ( Exception $ex ) {
                echo $ex->getMessage() ;
            }
        }

        /**
         * Output Shortcode affiliate application status.
         */
        public static function shortcode_application_status( $atts ) {
            if ( ! is_user_logged_in() ) {
                return '' ;
            }

            $affiliate_id = fs_affiliates_is_user_having_affiliate() ;
            if ( ! $affiliate_id ) {
                return '' ;
            }

            $notice_message = self::validate_dashboard( $affiliate_id ) ;
            if ( $notice_message ) {
                echo $notice_message ;
            }
        }

        /**
         * Validate dashboard.
         */
        public static function validate_dashboard( $affiliate_id ) {
            $notice_message = false ;
            $status         = get_post_status( $affiliate_id ) ;

            switch ( $status ) {
                case 'fs_pending_approval':
                    $notice_message = __( 'Your Affiliate application is currently pending approval. You will be notified shortly.' , FS_AFFILIATES_LOCALE ) ;
                    $notice_message = '<div class="fs_affiliates_pending_approval_notice">' . $notice_message . '</div>' ;
                    break ;
                case 'fs_rejected':
                    $notice_message = __( 'Your Affiliate account has been rejected. Please contact admin for more info.' , FS_AFFILIATES_LOCALE ) ;
                    $notice_message = '<div class="fs_affiliates_rejected_notice">' . $notice_message . '</div>' ;
                    break ;
                case 'fs_suspended':
                    if ( FS_Affiliates_Module_Instances::get_module_by_id( 'affiliate_fee' )->is_enabled() && FS_Affiliates_Module_Instances::get_module_by_id( 'affiliate_fee' )->affiliate_to_pay_fee_as( $affiliate_id , 'recurring' ) ) {
                        $subscription_id = get_post_meta( $affiliate_id , '_aff_fee_subscription_id' , true ) ;
                        $product_id      = get_post_meta( $affiliate_id , '_aff_fee_product' , true ) ;

                        switch ( get_post_meta( $subscription_id , 'sumo_get_status' , true ) ) {
                            case 'Pause':
                                $notice_message   = str_replace( '{click_here}' , '<a style="color:#FBFF00;" href="' . wc_get_endpoint_url( 'view-subscription' , $subscription_id , wc_get_page_permalink( 'myaccount' ) ) . '">' . __( 'click here' , FS_AFFILIATES_LOCALE ) . '</a>' , FS_Affiliates_Module_Instances::get_module_by_id( 'affiliate_fee' )->aff_fee_product_paused_msg ) ;
                                $notice_message   = '<div class="fs_affiliates_suspended_notice">' . $notice_message . '</div>' ;
                                break ;
                            case 'Cancelled':
                                $notice_message   = str_replace( '{product_url)' , '<a style="color:#000FFF;" href="' . esc_url_raw( add_query_arg( array( 'fs_status' => 'fs_pending_payment' , 'fs_aff_id' => $affiliate_id , 'fs_nonce' => wp_create_nonce( '_fs_affiliates' ) ) , get_permalink( $product_id ) ) ) . '"> ' . get_the_title( $product_id ) . '</a>' , FS_Affiliates_Module_Instances::get_module_by_id( 'affiliate_fee' )->aff_fee_product_cancelled_msg ) ;
                                $notice_message   = '<div class="fs_affiliates_rejected_notice">' . $notice_message . '</div>' ;
                                break ;
                            case 'Expired':
                                $notice_message   = str_replace( '{product_url)' , '<a style="color:#000FFF;" href="' . esc_url_raw( add_query_arg( array( 'fs_status' => 'fs_pending_payment' , 'fs_aff_id' => $affiliate_id , 'fs_nonce' => wp_create_nonce( '_fs_affiliates' ) ) , get_permalink( $product_id ) ) ) . '"> ' . get_the_title( $product_id ) . '</a>' , FS_Affiliates_Module_Instances::get_module_by_id( 'affiliate_fee' )->aff_fee_product_expired_msg ) ;
                                $notice_message   = '<div class="fs_affiliates_rejected_notice">' . $notice_message . '</div>' ;
                                break ;
                            default :
                                $renewal_order_id = absint( get_post_meta( $subscription_id , 'sumo_get_renewal_id' , true ) ) ;

                                if ( $renewal_order_id && ($renewal_order = wc_get_order( $renewal_order_id )) ) {
                                    if ( $renewal_order->has_status( 'pending' ) ) {
                                        $notice_message = str_replace( '{click_here}' , '<a style="color:#FBFF00;" href="' . esc_url( $renewal_order->get_checkout_payment_url() ) . '">' . __( 'click here' , FS_AFFILIATES_LOCALE ) . '</a>' , FS_Affiliates_Module_Instances::get_module_by_id( 'affiliate_fee' )->aff_fee_product_suspended_msg ) ;
                                        $notice_message = '<div class="fs_affiliates_suspended_notice">' . $notice_message . '</div>' ;
                                    }
                                }
                                break ;
                        }
                    } else {
                        $notice_message = __( 'Your Affiliate account has been suspended. Please contact admin for more info.' , FS_AFFILIATES_LOCALE ) ;
                        $notice_message = '<div class="fs_affiliates_suspended_notice">' . $notice_message . '</div>' ;
                    }
                    break ;
                case 'fs_hold':
                    $notice_message  = __( 'Your email id is not yet verified. Please verify your email so that admin can process your application.' , FS_AFFILIATES_LOCALE ) ;
                    $notice_message  = '<div class="fs_affiliates_hold_notice">' . $notice_message . '</div>' ;
                    break ;
                case 'fs_pending_payment':
                    $subscription_id = get_post_meta( $affiliate_id , '_aff_fee_subscription_id' , true ) ;

                    if ( FS_Affiliates_Module_Instances::get_module_by_id( 'affiliate_fee' )->is_enabled() ) {
                        switch ( get_post_meta( $subscription_id , 'sumo_get_status' , true ) ) {
                            case 'Pending':
                                $notice_message = FS_Affiliates_Module_Instances::get_module_by_id( 'affiliate_fee' )->aff_fee_product_pending_msg ;
                                $notice_message = '<div class="fs_affiliates_pending_approval_notice">' . $notice_message . '</div>' ;
                                break ;
                            default :
                                $product_id     = get_post_meta( $affiliate_id , '_aff_fee_product' , true ) ;

                                if ( ! empty( $product_id ) ) {
                                    $notice_message = str_replace( '{product_url)' , '<a style="color:#000FFF;" href="' . esc_url_raw( add_query_arg( array( 'fs_status' => 'fs_pending_payment' , 'fs_aff_id' => $affiliate_id , 'fs_nonce' => wp_create_nonce( '_fs_affiliates' ) ) , get_permalink( $product_id ) ) ) . '"> ' . get_the_title( $product_id ) . '</a>' , FS_Affiliates_Module_Instances::get_module_by_id( 'affiliate_fee' )->aff_fee_product_purchase_msg ) ;
                                    $notice_message = '<div class="fs_affiliates_pending_approval_notice">' . $notice_message . '</div>' ;
                                }
                                break ;
                        }
                    }
                    break ;
            }

            return $notice_message ;
        }

        /**
         * Output dashboard form.
         */
        public static function dashboard( $affiliate_id , $registration_method ) {

            try {
                if ( ( ! is_user_logged_in() && $registration_method == 'advanced') || ! $affiliate_id ) {
                    $url            = get_permalink( fs_affiliates_get_page_id( 'register' ) ) ;
                    $login_url      = get_permalink( fs_affiliates_get_page_id( 'login' ) ) ;
                    $link           = "<a href='" . $url . "'>" . __( 'Affiliate Registration Form' , FS_AFFILIATES_LOCALE ) . "</a>" ;
                    $reg_message    = sprintf( __( 'Do you want to become an affiliate? Submit an application by clicking the %s' , FS_AFFILIATES_LOCALE ) , $link ) ;
                    $notice_message = '<div class="fs_affiliates_register_notice">' . $reg_message . '</div>' ;

                    if ( ! is_user_logged_in() ) {
                        $login_link     = "<a href='" . esc_url( $login_url ) . "'>" . esc_html__( 'Click here' , FS_AFFILIATES_LOCALE ) . "</a>" ;
                        $login_message  = sprintf( esc_html__( 'Already an Affiliate? %s to login' , FS_AFFILIATES_LOCALE ) , $login_link ) ;
                        $notice_message .= '<div class="fs_affiliates_register_notice">' . $login_message . '</div>' ;
                    }

                    throw new Exception( $notice_message ) ;
                }

                $notice_message = self::validate_dashboard( $affiliate_id ) ;
                if ( $notice_message ) {
                    throw new Exception( $notice_message ) ;
                }

                self::custom_css() ;

                $subscription_id     = get_post_meta( $affiliate_id , '_aff_fee_subscription_id' , true ) ;
                $subscription_status = get_post_meta( $subscription_id , 'sumo_get_status' , true ) ;

                if ( FS_Affiliates_Module_Instances::get_module_by_id( 'affiliate_fee' )->is_enabled() && in_array( $subscription_status , array( 'Overdue' , 'Active' ) ) ) {
                    $renewal_order_id = absint( get_post_meta( $subscription_id , 'sumo_get_renewal_id' , true ) ) ;
                    $message          = 'Overdue' === $subscription_status ? FS_Affiliates_Module_Instances::get_module_by_id( 'affiliate_fee' )->aff_fee_product_overdue_msg : FS_Affiliates_Module_Instances::get_module_by_id( 'affiliate_fee' )->aff_fee_product_renew_msg ;

                    if ( $renewal_order_id && ($renewal_order = wc_get_order( $renewal_order_id )) ) {
                        if ( $renewal_order->has_status( 'pending' ) ) {
                            $notice_message = str_replace( '{click_here}' , '<a style="color:#FBFF00;" href="' . esc_url( $renewal_order->get_checkout_payment_url() ) . '">' . __( 'click here' , FS_AFFILIATES_LOCALE ) . '</a>' , $message ) ;
                            echo '<div class="fs_affiliates_hold_notice">' . $notice_message . '</div>' ;
                        }
                    }
                }

                if ( apply_filters( 'fs_affiliates_render_dashboard' , true , $affiliate_id ) ) {
                    FS_Affiliates_Dashboard::output() ;
                }
            } catch ( Exception $ex ) {
                echo $ex->getMessage() ;
            }
        }

        /**
         * Output shortcode to opt in form
         */
        public static function shortcode_opt_in_form( $atts ) {
            try {

                self::custom_css() ;

                // Display Error or Messages
                FS_Affiliates_Form_Handler::show_messages() ;

                $fields = fs_affiliates_get_opt_in_form_fields() ;
                do_action( 'fs_affiliates_before_opt_in_form' ) ;
                ?>
                <form class="fs_affiliates_forms" id="fs_affiliates_register_form" action="" method="post">
                    <?php
                    /* Include Opt in page */
                    include_once('views/email-opt-in-form.php') ;
                    ?>
                </form>
                <?php
                do_action( 'fs_affiliates_after_opt_in_form' ) ;
            } catch ( Exception $ex ) {
                echo $ex->getMessage() ;
            }
        }

        /**
         * Output MLM Tree.
         */
        public static function shortcode_mlm_tree( $atts ) {
            try {
                if ( ! is_user_logged_in() ) {
                    throw new Exception( __( 'The Affiliates who are involved in the MLM can see the MLM Tree.' , FS_AFFILIATES_LOCALE ) ) ;
                }

                $AffiliateId = fs_affiliates_is_user_having_affiliate() ;
                if ( ! $AffiliateId ) {
                    throw new Exception( __( 'The Affiliates who are involved in the MLM can see the MLM Tree.' , FS_AFFILIATES_LOCALE ) ) ;
                }

                self::custom_css() ;

                fs_graph_for_mlm( $AffiliateId ) ;
            } catch ( Exception $ex ) {
                echo $ex->getMessage() ;
            }
        }

        /**
         * Output Affiliate Basic Settings
         */
        public static function shortcode_basic_settings( $affiliate_id , $user_id , $atts ) {

            self::custom_css() ;

            FS_Affiliates_Dashboard::basic_details( $user_id , $affiliate_id ) ;
        }

        /**
         * Output Affiliate Account Management Settings
         */
        public static function shortcode_account_management_settings( $affiliate_id , $user_id , $atts ) {

            self::custom_css() ;

            FS_Affiliates_Dashboard::account_management( $user_id , $affiliate_id ) ;
        }

        /**
         * Output Affiliate Payment Management Settings
         */
        public static function shortcode_payment_management_settings( $affiliate_id , $user_id , $atts ) {

            self::custom_css() ;

            FS_Affiliates_Dashboard::payment_management( $user_id , $affiliate_id ) ;
        }

        /**
         * Output Affiliate Creatives
         */
        public static function shortcode_creatives( $affiliate_id , $user_id , $atts ) {

            self::custom_css() ;

            FS_Affiliates_Dashboard::creatives( $user_id , $affiliate_id ) ;
        }

        /**
         * Output Affiliate Referrals
         */
        public static function shortcode_referrals( $affiliate_id , $user_id , $atts ) {

            self::custom_css() ;


            FS_Affiliates_Dashboard::referrals( $user_id , $affiliate_id ) ;
        }

        /**
         * Output Affiliate visits
         */
        public static function shortcode_visits( $affiliate_id , $user_id , $atts ) {

            self::custom_css() ;

            FS_Affiliates_Dashboard::visits( $user_id , $affiliate_id ) ;
        }

        /**
         * Output Affiliate Payouts
         */
        public static function shortcode_payouts( $affiliate_id , $user_id , $atts ) {

            self::custom_css() ;

            FS_Affiliates_Dashboard::payouts( $user_id , $affiliate_id ) ;
        }

        /**
         * Output Affiliate Campaigns
         */
        public static function shortcode_campaigns( $affiliate_id , $user_id , $atts ) {

            self::custom_css() ;

            FS_Affiliates_Dashboard::campaigns( $user_id , $affiliate_id ) ;
        }

        /**
         * Output Affiliate Overview
         */
        public static function shortcode_overview( $affiliate_id , $user_id , $atts ) {

            self::custom_css() ;

            FS_Affiliates_Dashboard::overview( $user_id , $affiliate_id ) ;
        }

        /**
         * Output Affiliate Link Generator
         */
        public static function shortcode_link_generator( $affiliate_id , $user_id , $atts ) {
            self::custom_css() ;

            FS_Affiliates_Dashboard::affiliate_link( $user_id , $affiliate_id ) ;
        }

        /**
         * Output Affiliate Campaign Overview
         */
        public static function shortcode_campaigns_overview( $affiliate_id , $user_id , $atts ) {

            self::custom_css() ;

            FS_Affiliates_Dashboard::affiliate_campaign_table( $user_id , $affiliate_id ) ;
        }

        /**
         * Output Affiliate Commission Rate
         */
        public static function shortcode_commission_rate( $affiliate_id , $user_id , $atts ) {

            self::custom_css() ;

            FS_Affiliates_Dashboard::affiliate_commission_table( $user_id , $affiliate_id , false , $atts ) ;
        }

        /**
         * Output Affiliate Paid Commission Rate
         */
        public static function shortcode_paid_commission_rate( $atts ) {

            $affiliate_id = fs_affiliates_is_user_having_affiliate() ;

            if ( ! is_user_logged_in() || ! $affiliate_id ) {
                echo fs_affiliates_price( 0 ) ;
                return ;
            }

            $affiliate_object = new FS_Affiliates_Data( $affiliate_id ) ;

            echo $affiliate_object->get_paid_commission() ;
        }

        /**
         * Output Affiliate Unpaid Commission Rate
         */
        public static function shortcode_unpaid_commission_rate( $atts ) {

            $affiliate_id = fs_affiliates_is_user_having_affiliate() ;

            if ( ! is_user_logged_in() || ! $affiliate_id ) {
                echo fs_affiliates_price( 0 ) ;
                return ;
            }

            $affiliate_object = new FS_Affiliates_Data( $affiliate_id ) ;

            echo $affiliate_object->get_unpaid_commission() ;
        }

        /**
         * Output Affiliate Overall Commission Rate
         */
        public static function shortcode_overall_commission_rate( $atts ) {

            $affiliate_id = fs_affiliates_is_user_having_affiliate() ;

            if ( ! is_user_logged_in() || ! $affiliate_id ) {
                echo fs_affiliates_price( 0 ) ;
                return ;
            }

            $affiliate_object = new FS_Affiliates_Data( $affiliate_id ) ;

            echo $affiliate_object->get_overall_commission() ;
        }

        /**
         * Output Affiliate Refer a Friend
         */
        public static function shortcode_refer_a_friend( $affiliate_id , $user_id , $atts ) {
            self::custom_css() ;

            do_action( 'fs_affiliates_dashboard_content_referafriend' , $user_id , $affiliate_id ) ;
        }

        /**
         * Output Affiliate Wallet
         */
        public static function shortcode_wallet( $affiliate_id , $user_id , $atts ) {

            self::custom_css() ;

            do_action( 'fs_affiliates_dashboard_content_wallet' , $user_id , $affiliate_id ) ;
        }

        /**
         * Output Affiliate WC Coupon Linking
         */
        public static function shortcode_wc_coupon_linking( $affiliate_id , $user_id ) {

            self::custom_css() ;

            do_action( 'fs_affiliates_dashboard_content_wc_coupon_linking' , $user_id , $affiliate_id ) ;
        }

        /**
         * Output Affiliate Pushover Notifications
         */
        public static function shortcode_pushover_notifications( $affiliate_id , $user_id , $atts ) {

            self::custom_css() ;

            do_action( 'fs_affiliates_dashboard_content_pushover_notifications' , $user_id , $affiliate_id ) ;
        }

        /**
         * Output Affiliate Leaderboard
         */
        public static function shortcode_leaderboard( $affiliate_id , $user_id , $atts ) {

            self::custom_css() ;

            do_action( 'fs_affiliates_dashboard_content_leaderboard' , $user_id , $affiliate_id ) ;
        }

        /**
         * Output Affiliate Name
         */
        public static function shortcode_name( $atts ) {

            if ( ! is_user_logged_in() )
                return ;

            $affiliate_id = fs_affiliates_is_user_having_affiliate() ;

            if ( ! $affiliate_id )
                return ;

            $affiliate_object = new FS_Affiliates_Data( $affiliate_id ) ;

            echo $affiliate_object->first_name . ' ' . $affiliate_object->last_name ;
        }

        /**
         * Output Affiliate Username
         */
        public static function shortcode_username( $atts ) {

            if ( ! is_user_logged_in() )
                return ;

            $affiliate_id = fs_affiliates_is_user_having_affiliate() ;

            if ( ! $affiliate_id )
                return ;

            $affiliate_object = new FS_Affiliates_Data( $affiliate_id ) ;

            echo $affiliate_object->user_name ;
        }

        /**
         * Output Affiliate Link
         */
        public static function shortcode_link( $atts ) {

            if ( ! is_user_logged_in() )
                return ;

            $affiliate_id = fs_affiliates_is_user_having_affiliate() ;

            if ( ! $affiliate_id )
                return ;

            $AffiliateURL             = site_url() ;
            $ReferralIdentifier       = fs_get_referral_identifier() ;
            $ReferralIdFormat         = get_option( 'fs_affiliates_referral_id_format' ) ;
            $AffiliateData            = new FS_Affiliates_Data( $affiliate_id ) ;
            $Identifier               = $ReferralIdFormat == 'name' ? $AffiliateData->user_name : $affiliate_id ;
            $Identifier               = apply_filters( 'fs_affiliates_slug_for_affiliate' , $Identifier , $AffiliateData ) ;
            $formatted_affiliate_link = add_query_arg( $ReferralIdentifier , $Identifier , $AffiliateURL ) ;

            echo apply_filters( 'fs_affiliates_link_generator' , $formatted_affiliate_link , $AffiliateURL , $ReferralIdentifier , $Identifier , false , true ) ;
        }

        /**
         * Output Affiliate ID
         */
        public static function shortcode_id( $atts ) {

            if ( ! is_user_logged_in() )
                return ;

            $affiliate_id = fs_affiliates_is_user_having_affiliate() ;

            if ( ! $affiliate_id )
                return ;

            echo $affiliate_id ;
        }

        /**
         * Output Affiliate Email
         */
        public static function shortcode_email( $atts ) {

            if ( ! is_user_logged_in() )
                return ;

            $affiliate_id = fs_affiliates_is_user_having_affiliate() ;

            if ( ! $affiliate_id )
                return ;

            $affiliate_object = new FS_Affiliates_Data( $affiliate_id ) ;

            echo $affiliate_object->email ;
        }

        /**
         * Output Affiliate referral code.
         */
        public static function shortcode_referral_code( $atts ) {
            if ( ! is_user_logged_in() )
                return '' ;

            $affiliate_id = fs_affiliates_is_user_having_affiliate() ;
            if ( ! $affiliate_id )
                return '' ;

            echo apply_filters( 'fs_affiliates_referral_code' , '' , $affiliate_id ) ;
        }

        /**
         * Output Logout
         */
        public static function shortcode_logout( $atts ) {

            if ( ! is_user_logged_in() )
                return ;

            $affiliate_id = fs_affiliates_is_user_having_affiliate() ;

            if ( ! $affiliate_id )
                return ;

            $url = wp_logout_url( get_permalink( fs_affiliates_get_page_id( 'login' ) ) ) ;

            echo '<a href="' . $url . '">' . get_option( 'fs_affiliates_dashboard_customization_logout_label' , 'Logout' ) . '</a>' ;
        }

        /**
         * Output payment method alert.
         */
        public static function shortcode_payment_method_alert( $atts ) {

            if ( ! is_user_logged_in() )
                return ;

            $affiliate_id = fs_affiliates_is_user_having_affiliate() ;

            if ( ! $affiliate_id )
                return ;

            $user_id = get_current_user_id() ;

            display_before_dashboard_content( 'shortcode' , $user_id , $affiliate_id ) ;
        }

    }

    FS_Affiliates_Shortcodes::init() ;
}

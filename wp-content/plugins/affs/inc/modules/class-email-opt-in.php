<?php

/**
 * Paypal payouts
 */
if ( !defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( !class_exists( 'FS_Affiliates_email_opt_in' ) ) {

    /**
     * Class
     */
    class FS_Affiliates_email_opt_in extends FS_Affiliates_Modules {
        /*
         * Data
         */

        protected $data = array (
            'enabled'                          => 'no' ,
            'email_service'                    => '' ,
            'api_key'                          => '' ,
            'display_newsletter_subs_settings' => '' ,
            'list_id'                          => '' ,
            'frontend_label'                   => '' ,
            'award_commision_form_filling'     => '' ,
            'enable_double_opt_in'             => '' ,
            'affs_commision'                   => '' ,
            'affs_subject'                     => '' ,
            'affs_message'                     => '' ,
                ) ;

        /**
         * Class Constructor
         */
        public function __construct() {

            $this->id    = 'affs_email_opt_in' ;
            $this->title = __( 'Email Opt-In' , FS_AFFILIATES_LOCALE ) ;

            parent::__construct() ;
        }

        /*
         * Get settings link
         */

        public function settings_link() {
            return add_query_arg( array ( 'page' => 'fs_affiliates' , 'tab' => 'modules' , 'section' => $this->id ) , admin_url( 'admin.php' ) ) ;
        }

        /*
         * Get settings options array
         */

        public function settings_options_array() {

            global $current_sub_section ;

            switch ( $current_sub_section ) {
                case 'edit':
                    return array (
                        array (
                            'id'      => 'fs_affiliates_opt_in_form_fields' ,
                            'default' => fs_affiliates_get_default_form_fields() ,
                            'type'    => 'output_opt_in_form'
                        ) ,
                            ) ;
                    break ;
                default :
                    return $this->opt_in_settings_options_array() ;
                    break ;
            }
        }

        /*
         * Admin Actions
         */

        public function admin_action() {
            add_action( $this->plugin_slug . '_admin_field_output_opt_in_form' , array ( $this , 'display_optin_form_edit_page' ) ) ;
        }

        /**
         * Frontend Actions
         */
        public function frontend_action() {
            add_action( 'wp_loaded' , array ( $this , 'process_opt_in_email_link' ) ) ;

            add_action( 'wp_head' , array ( $this , 'opt_in_email_display_notices' ) ) ;
        }

        /*
         * Get optin settings options array
         */

        public function opt_in_settings_options_array() {

            $mail_providers = array (
                'active_campaign' => __( 'ActiveCampaign' , FS_AFFILIATES_LOCALE ) ,
                'mailchimp'       => __( 'MailChimp' , FS_AFFILIATES_LOCALE ) ,
                    ) ;

            return array (
                array (
                    'type'  => 'title' ,
                    'title' => __( 'Authentication Settings' , FS_AFFILIATES_LOCALE ) ,
                    'id'    => 'affs_email_opt_in_authentication_settings' ,
                ) ,
                array (
                    'title'   => __( 'Email Service' , FS_AFFILIATES_LOCALE ) ,
                    'desc'    => __( 'Select a email service to capture the email ids' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->plugin_slug . '_' . $this->id . '_email_service' ,
                    'type'    => 'select' ,
                    'class'   => 'fs_affiliates_allowed_affiliates_method' ,
                    'default' => 'mailchimp' ,
                    'options' => $mail_providers ,
                ) ,
                array (
                    'title'   => __( 'URL' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->plugin_slug . '_' . $this->id . '_url' ,
                    'type'    => 'text' ,
                    'default' => '' ,
                ) ,
                array (
                    'title'   => __( 'API Key' , FS_AFFILIATES_LOCALE ) ,
                    'desc'    => __( 'Please login to the site to get the API key' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->plugin_slug . '_' . $this->id . '_api_key' ,
                    'type'    => 'text' ,
                    'default' => '' ,
                ) ,
                array (
                    'title'   => __( 'List ID' , FS_AFFILIATES_LOCALE ) ,
                    'desc'    => __( 'Please login to the site to get the List ID' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->plugin_slug . '_' . $this->id . '_list_id' ,
                    'type'    => 'text' ,
                    'default' => '' ,
                ) ,
                array (
                    'type' => 'sectionend' ,
                    'id'   => 'affs_email_opt_in_authentication_settings' ,
                ) ,
                array (
                    'type'  => 'title' ,
                    'title' => __( 'Newsletter Subscription Settings – Affiliate Registration Page' , FS_AFFILIATES_LOCALE ) ,
                    'id'    => 'affs_newsletter_subs_settings' ,
                ) ,
                array (
                    'title'   => 'Display Newsletter Subscription Checkbox' ,
                    'id'      => $this->plugin_slug . '_' . $this->id . '_display_newsletter_subs_settings' ,
                    'type'    => 'checkbox' ,
                    'default' => 'no' ,
                    'desc'    => __( 'When enabled, an email subscription checkbox will be visible on the Affiliate Registration Page' , FS_AFFILIATES_LOCALE ) ,
                ) ,
                array (
                    'title'   => __( 'Newsletter Subscription Notice' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->plugin_slug . '_' . $this->id . '_frontend_label' ,
                    'type'    => 'text' ,
                    'default' => 'Subscribe to our Newsletter' ,
                ) ,
                array (
                    'type' => 'sectionend' ,
                    'id'   => 'affs_newsletter_subs_settings' ,
                ) ,
                array (
                    'type'  => 'title' ,
                    'title' => __( 'Newsletter Subscription – Referral Commission Settings' , FS_AFFILIATES_LOCALE ) ,
                    'desc'  => sprintf( __( '<b>%s</b> - Use this shortcode to display the Email Opt-In(Newsletter Subscription) Form' , FS_AFFILIATES_LOCALE ) , '[fs_affiliates_opt_in_form] ' ) ,
                    'id'    => 'affs_newsletter_referral_subs_settings' ,
                ) ,
                array (
                    'title'   => __( 'Award Commission to Affiliate when an user subscribes to newsletter through Affiliate link' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->plugin_slug . '_' . $this->id . '_award_commision_form_filling' ,
                    'type'    => 'checkbox' ,
                    'default' => 'no' ,
                    'desc'    => __( 'When enabled, an affiliate will receive commission if they referals through email subscription' , FS_AFFILIATES_LOCALE ) ,
                ) ,
                array (
                    'title'   => __( 'Affiliate Commission' , FS_AFFILIATES_LOCALE ) ,
                    'desc'    => __( 'Commission to be awarded for Email Subscription Referral action' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->plugin_slug . '_' . $this->id . '_affs_commision' ,
                    'type'    => 'price' ,
                    'default' => '' ,
                ) ,
                array (
                    'type' => 'sectionend' ,
                    'id'   => 'affs_newsletter_referral_subs_settings' ,
                ) ,
                array (
                    'type'  => 'title' ,
                    'title' => __( 'Double Opt-In Settings' , FS_AFFILIATES_LOCALE ) ,
                    'id'    => 'affs_double_optin_mail_settings' ,
                ) ,
                array (
                    'title'   => __( 'Enable Double Opt-In for Newsletter Subscription' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->plugin_slug . '_' . $this->id . '_enable_double_opt_in' ,
                    'type'    => 'checkbox' ,
                    'default' => 'no' ,
                    'desc'    => __( 'When enabled, users email id will be newsletter list only after the user has verified their Subscription' , FS_AFFILIATES_LOCALE ) ,
                ) ,
                array (
                    'title'   => __( 'Email Subject' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->plugin_slug . '_' . $this->id . '_affs_subject' ,
                    'type'    => 'text' ,
                    'default' => 'Verify your Newsletter Subscription' ,
                ) ,
                array (
                    'title'   => __( 'Email Message' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->plugin_slug . '_' . $this->id . '_affs_message' ,
                    'type'    => 'wpeditor' ,
                    'default' => 'Hi,

Please Confirm your Newsletter Subscription on {site_link} using {verification_link}

Thanks.' ,
                ) ,
                array (
                    'type' => 'sectionend' ,
                    'id'   => 'affs_double_optin_mail_settings' ,
                ) ,
                    ) ;
        }

        /**
         * Output the edit frontend form settings
         */
        public function display_optin_form_edit_page() {
            if ( !isset( $_GET[ 'key' ] ) )
                return ;

            $field_key = $_GET[ 'key' ] ;
            $fields    = fs_affiliates_get_opt_in_form_fields() ;

            if ( !isset( $fields[ $field_key ] ) )
                return ;

            $field = $fields[ $field_key ] ;

            extract( $field ) ;

            $hide_place_fields      = array ( 'country' ) ;
            $disabled_status_fields = array ( 'email' , 'user_name' , 'password' ) ;
            $disabled_type_fields   = array ( 'email' , 'user_name' , 'password' , 'repeated_password' ) ;


            $disabled_status  = in_array( $field_key , $disabled_status_fields ) ? 'disabled="disabled"' : '' ;
            $disabled_type    = in_array( $field_key , $disabled_type_fields ) ? 'disabled="disabled"' : '' ;
            $hide_placeholder = in_array( $field_key , $hide_place_fields ) ? true : false ;

            include_once( FS_AFFILIATES_PLUGIN_PATH . '/inc/admin/menu/views/email-opt-inform-fields-edit.php' ) ;
        }

        /*
         * Get settings options array
         */

        public function process_opt_in_email_link() {

            if ( !isset( $_GET[ 'fs_opt_in_nonce' ] ) || !isset( $_GET[ 'opt_in_action' ] ) || !$_GET[ 'opt_in_action' ] ) {
                return ;
            }

            try {
                $email = base64_decode( $_GET[ 'fs_opt_in_nonce' ] ) ;

                $user = get_user_by( 'email' , $email ) ;

                $optin_action = $_GET[ 'opt_in_action' ] ;
                if ( !$user )
                    throw new Exception( 'mismatch_data' ) ;

                $email             = base64_decode( $_GET[ 'fs_opt_in_nonce' ] ) ;
                $affiliate_id      = fs_affiliates_is_user_having_affiliate( $user->ID ) ;
                $affiliates_object = new FS_Affiliates_Data( $affiliate_id ) ;
                $first_name        = isset( $affiliates_object->first_name ) ? $affiliates_object->first_name : '' ;
                $last_name         = isset( $affiliates_object->last_name ) ? $affiliates_object->last_name : '' ;

                $meta_data = array (
                    'first_name' => $first_name ,
                    'last_name'  => $last_name ,
                        ) ;
                process_adding_list_in_mail( $email , $meta_data , $optin_action ) ;
                //success banner
                $redirect  = add_query_arg( array ( 'fs_optin_verification_success' => 'true' ) , site_url() ) ;
            } catch ( Exception $ex ) {
                //fails banner
                $redirect = add_query_arg( array ( 'fs_optin_verification_failure' => $ex->getMessage() ) , site_url() ) ;
            }
            wp_safe_redirect( $redirect ) ;
            exit() ;
        }

        public function opt_in_email_display_notices() {

            if ( isset( $_GET[ 'fs_optin_verification_success' ] ) && $_GET[ 'fs_optin_verification_success' ] == 'true' ) {
                $message = __( 'Newsletter subscription verified successfully' , FS_AFFILIATES_LOCALE ) ;

                echo '<p class="fs_affiliates_success_notices">' . $message . '</p>' ;
            }

            if ( isset( $_GET[ 'fs_optin_verification_failure' ] ) && $_GET[ 'fs_optin_verification_failure' ] ) {
                $message = __( 'Something went wrong' , FS_AFFILIATES_LOCALE ) ;
                echo '<p class="fs_affiliates_failure_notices">' . $message . '</p>' ;
            }
        }

        /**
         * Output the frontend form settings
         */
        public function extra_fields() {

            global $current_section , $current_sub_section ;

            if ( $current_section == 'affs_email_opt_in' && $current_sub_section == 'edit' ) {
                return ;
            }

            if ( !class_exists( 'FS_Affiliates_Email_Opt_In_Post_Table' ) ) {
                require_once( FS_AFFILIATES_PLUGIN_PATH . '/inc/admin/menu/wp-list-table/class-fs-affiliates-email-opt-in-fields-table.php' ) ;
            }

            echo '<div class="' . $this->plugin_slug . '_table_wrap">' ;
            echo '<h2 class="wp-heading-inline">' . __( 'Opt-In Form Fields' , FS_AFFILIATES_LOCALE ) . '</h2>' ;

            $post_table = new FS_Affiliates_Email_Opt_In_Post_Table() ;
            $post_table->prepare_items() ;
            $post_table->display() ;
            echo '</div>' ;
        }

        public function output_buttons() {
            global $current_section , $current_sub_section ;

            if ( $current_section == 'affs_email_opt_in' && $current_sub_section == 'edit' ) {
                return ;
            }

            FS_Affiliates_Settings::output_buttons() ;
        }

        /**
         * Save subsection settings.
         */
        public function before_save() {
            global $current_sub_section ;

            if ( $current_sub_section == '' || empty( $_POST[ 'edit_opt_in_fields' ] ) )
                return ;

            check_admin_referer( $this->plugin_slug . '_edit_opt_in_form_fields' , '_' . $this->plugin_slug . '_nonce' ) ;

            $fields = fs_affiliates_get_opt_in_form_fields() ;

            $form_field_data = $_POST[ 'form_field' ] ;

            if ( empty( $form_field_data[ 'field_key' ] ) || $form_field_data[ 'field_key' ] != $_REQUEST[ 'key' ] ) {
                throw new Exception( __( 'Cannot change the form field key' , FS_AFFILIATES_LOCALE ) ) ;
            }

            $field = $fields[ $form_field_data[ 'field_key' ] ] ;

            foreach ( $form_field_data as $field_key => $field_data ) {

                if ( !array_key_exists( $field_key , $field ) )
                    continue ;

                $field [ $field_key ] = $field_data ;
            }

            $fields[ $form_field_data[ 'field_key' ] ] = $field ;

            update_option( 'fs_affiliates_opt_in_form_fields' , $fields ) ;

            FS_Affiliates_Settings::add_message( __( 'Form field updated successfully.' , FS_AFFILIATES_LOCALE ) ) ;
        }

    }

}
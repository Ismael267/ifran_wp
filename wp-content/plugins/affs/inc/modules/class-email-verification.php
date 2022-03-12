<?php

/**
 * Email Verification
 */
if ( !defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( !class_exists( 'FS_Affiliates_Email_Verification' ) ) {

    /**
     * Class FS_Affiliates_Email_Verification
     */
    class FS_Affiliates_Email_Verification extends FS_Affiliates_Modules {
        /*
         * Data
         */

        protected $data = array(
            'enabled' => 'no',
            'link_validity' => array('number' => 5, 'unit' => 'minutes'),
            'success_redirect_page' => '',
            'failure_redirect_page' => '',
            'email_subject' => '',
            'email_message' => '',
        );

        /**
         * Class Constructor
         */
        public function __construct() {
            $this->id    = 'email_verification' ;
            $this->title = __( 'Affiliate Email Verification' , FS_AFFILIATES_LOCALE ) ;

            parent::__construct() ;
        }

        /*
         * Get settings link
         */

        public function settings_link() {
            return add_query_arg( array ( 'page' => 'fs_affiliates' , 'tab' => 'modules' , 'section' => $this->id ) , admin_url( 'admin.php' ) ) ;
        }

        /*
         * Default Subject
         */

        public function get_default_subject() {

            return __( '{site_name} - Verify Your Affiliate Account' , FS_AFFILIATES_LOCALE ) ;
        }

        /*
         * Default Message
         */

        public function get_default_message() {

            return __( 'Hi,

                        You Recently Signed Up for an Affiliate Account on {site_name}. You need to verify your Email Id before you can log into your Affiliate                             Account.
                        Click here to verify your Email {verification_link}.


                      Affiliate Details

                       Affiliate Name: {affiliate_name}
                       Affiliate Email: {affiliate_email}

                       Thanks.' , FS_AFFILIATES_LOCALE ) ;
        }

        /*
         * Get settings options array
         */

        public function settings_options_array() {
            return array(
                array(
                    'type' => 'title',
                    'title' => __('Affiliate Email Verification', FS_AFFILIATES_LOCALE),
                    'id' => 'email_verification_options',
                ),
                array(
                    'title' => __('Verification Link Validity', FS_AFFILIATES_LOCALE),
                    'id' => $this->plugin_slug . '_' . $this->id . '_link_validity',
                    'type' => 'relative_date_selector',
                    'periods' => array('minutes' => __('minute(s)', FS_AFFILIATES_LOCALE),
                        'hours' => __('Hour(s)', FS_AFFILIATES_LOCALE),
                        'days' => __('Day(s)', FS_AFFILIATES_LOCALE),
                    ),
                    'placeholder' => __('N/A', FS_AFFILIATES_LOCALE),
                    'default' => array(
                        'number' => 5,
                        'unit' => 'minutes',
                    ),
                ),
                array(
                    'title' => __('Success Redirect Page', FS_AFFILIATES_LOCALE),
                    'desc' => __('The page which the user should be redirected once the email verification is completed successfully.', FS_AFFILIATES_LOCALE),
                    'id' => $this->plugin_slug . '_' . $this->id . '_success_redirect_page',
                    'type' => 'text',
                    'default' => get_permalink(fs_affiliates_get_page_id('dashboard')),
                ),
                array(
                    'title' => __('Failure Redirect Page', FS_AFFILIATES_LOCALE),
                    'desc' => __('The page which the user should be redirected if the link is clicked after expiry.', FS_AFFILIATES_LOCALE),
                    'id' => $this->plugin_slug . '_' . $this->id . '_failure_redirect_page',
                    'type' => 'text',
                    'default' => site_url(),
                ),
                array(
                    'type' => 'sectionend',
                    'id' => 'email_verification_options',
                ),
                array(
                    'type' => 'title',
                    'title' => __('Email Settings', FS_AFFILIATES_LOCALE),
                    'id' => 'email_setting_options',
                ),
                array(
                    'title' => __('Email Subject', FS_AFFILIATES_LOCALE),
                    'desc' => __('Email Subject for Email Verification', FS_AFFILIATES_LOCALE),
                    'id' => $this->plugin_slug . '_' . $this->id . '_email_subject',
                    'type' => 'text',
                    'default' => $this->get_default_subject(),
                ),
                array(
                    'title' => __('Email Message', FS_AFFILIATES_LOCALE),
                    'desc' => __('Email Message for Email Verification', FS_AFFILIATES_LOCALE),
                    'id' => $this->plugin_slug . '_' . $this->id . '_email_message',
                    'type' => 'wpeditor',
                    'default' => $this->get_default_message(),
                ),
                array(
                    'type' => 'sectionend',
                    'id' => 'email_setting_options',
                ),
            );
        }

        /**
         * Save
         */
        public function save() {
            try {
                if ($_POST[$this->get_field_key('link_validity')]) {
                    $validity_time = $_POST[$this->get_field_key('link_validity')];

                    if (fs_affiliates_check_is_array($validity_time) && isset($validity_time['number']) && empty($validity_time['number'])) {
                        throw new Exception(esc_html__('Please enter the value in Verification Link Validity field', FS_AFFILIATES_LOCALE));
                    }

                    if ($validity_time != $this->get_option('link_validity')) {
                        $this->cron_job_setting(true);
                    }
                }
            } catch (Exception $ex) {
                FS_Affiliates_Settings::add_error($ex->getMessage());
            }
        }

        /*
         * Actions
         */

        public function actions() {
            add_action( 'init' , array ( $this , 'cron_job_setting' ) ) ;
            add_filter( 'cron_schedules' , array ( $this , 'add_custom_schedule' ) , 10 , 1 ) ;
            add_action( 'fs_affiliate_cron_job' , array ( $this , 'cron_job_action' ) ) ;
        }

        /**
         * Frontend Actions
         */
        public function frontend_action() {
            add_action( 'wp_loaded' , array ( $this , 'process_email_link' ) ) ;
            add_action( 'wp_head' , array ( $this , 'display_notices' ) ) ;
            add_action( 'fs_affiliates_frontend_register_form_submitted' , array ( $this , 'send_verification_email' ) , 10 , 1 ) ;
            add_filter( 'fs_affiliate_status_while_submit_application' , array ( $this , 'change_affiliate_status_as_on_hold' ) , 10 , 2 ) ;
        }

        /**
         * Add custom schedule time for "fs_affiliate_cron_job" event
         */
        public function add_custom_schedule( $schedules ) {
            $interval               = $this->schedule_time() ;
            $schedules[ 'zhourly' ] = array (
                'interval' => $interval ,
                'display'  => 'Z Hourly'
                    ) ;
            return $schedules ;
        }

        /*
         * set cron event
         */

        public static function cron_job_setting( $clear_event = false ) {
            if ( $clear_event )
                wp_clear_scheduled_hook( 'fs_affiliate_cron_job' ) ;

            if ( wp_next_scheduled( 'fs_affiliate_cron_job' ) == false )
                wp_schedule_event( time() , 'zhourly' , 'fs_affiliate_cron_job' ) ;
        }

        /*
         * Delete expired affiliate post when status has been on hold
         */

        public function cron_job_action() {
            $time   = $this->link_validity ;
            $number = (isset( $time[ 'number' ] ) && $time[ 'number' ]) ? ( float ) $time[ 'number' ] : 1 ;
            $unit   = (isset( $time[ 'unit' ] ) && $time[ 'unit' ]) ? $time[ 'unit' ] : 'hours' ;
            $args   = array ( 'post_type'   => 'fs-affiliates' ,
                'numberposts' => -1 ,
                'post_status' => array ( 'fs_hold' ) ,
                'fields'      => 'ids' ,
                'date_query'  => array (
                    array (
                        'before' => $number . ' ' . $unit . ' ago'
                    )
                )
                    ) ;
            $posts  = get_posts( $args ) ;

            foreach ( $posts as $each_affiliate ) {
                fs_affiliates_delete_affiliate( $each_affiliate ) ;
            }
        }

        /*
         * Send email to affiliate for verification
         */

        public function send_verification_email( $affiliate_id ) {
            $sitename          = get_bloginfo() ;
            $affiliates_object = new FS_Affiliates_Data( $affiliate_id ) ;

            $user = get_user_by( 'ID' , $affiliates_object->user_id ) ;

            if ( !$user )
                return ;

            $hash = wp_generate_password( 32 , false , false ) ;

            $verification_link = add_query_arg( array ( 'fs_email' => $user->user_email , 'fs_nonce' => $hash ) , site_url() ) ;
            $verification_link = '<a href="' . $verification_link . '">' . $verification_link . '</a>' ;

            $shortcode_array = array ( '{site_name}' , '{verification_link}' , '{affiliate_name}' , '{affiliate_email}' ) ;
            $replace_array   = array ( $sitename , $verification_link , $affiliates_object->user_name , $affiliates_object->email ) ;

            $subject = str_replace( $shortcode_array , $replace_array , $this->email_subject ) ;
            $message = str_replace( $shortcode_array , $replace_array , $this->email_message ) ;

            $message = wpautop( $message ) ;

            $notifications = new FS_Affiliates_Notifications() ;
            $send          = $notifications->send_email( $affiliates_object->email , $subject , $message ) ;

            if ( $send ) {
                $affiliates_object->update_meta( 'hash' , $hash ) ;
                $affiliates_object->update_meta( 'link_validity' , $this->link_validity() ) ;
            }
        }

        /*
         * change status as on hold for verification affiliate
         */

        public function change_affiliate_status_as_on_hold( $status , $data ) {

            return 'fs_hold' ;
        }

        /*
         * link validity
         */

        public function link_validity() {

            return $this->schedule_time() + time() ;
        }

        /*
         * schedule time
         */

        public function schedule_time() {
            $time   = $this->link_validity ;
            $number = isset( $time[ 'number' ] ) && $time[ 'number' ] ? ( float ) $time[ 'number' ] : 1 ;
            $unit   = isset( $time[ 'unit' ] ) && $time[ 'unit' ] ? $time[ 'unit' ] : 'hours' ;
            if ( $unit == 'days' ) {
                $timestamp = $number * 24 * 60 * 60 ;
            } else if ( $unit == 'hours' ) {
                $timestamp = $number * 60 * 60 ;
            } else {
                $timestamp = $number * 60 ;
            }

            return $timestamp ;
        }

        /*
         * Process email link
         */

        public function process_email_link() {
            if ( !isset( $_GET[ 'fs_email' ] ) || !$_GET[ 'fs_email' ] || !isset( $_GET[ 'fs_nonce' ] ) || !$_GET[ 'fs_nonce' ] )
                return ;

            try {
                $user = get_user_by( 'email' , $_GET[ 'fs_email' ] ) ;
                if ( !$user )
                    throw new Exception( 'mismatch_data' ) ;

                $affiliate_id      = fs_affiliates_is_user_having_affiliate( $user->ID ) ;
                $affiliates_object = new FS_Affiliates_Data( $affiliate_id ) ;

                if ( empty( $affiliates_object->link_validity ) || empty( $affiliates_object->hash ) )
                    throw new Exception( 'mismatch_data' ) ;

                if ( $_GET[ 'fs_nonce' ] != $affiliates_object->hash )
                    throw new Exception( 'mismatch_data' ) ;

                if ( time() > $affiliates_object->link_validity )
                    throw new Exception( 'validity_expired' ) ;

                $required_approval = get_option( 'fs_affiliates_admin_approval_required' ) ;
                $status            = ($required_approval == 'yes') ? 'fs_pending_approval' : apply_filters( 'fs_email_verified_affiliate_status' , 'fs_active' , $affiliates_object ) ;

                fs_affiliates_update_affiliate( $affiliate_id , array () , array ( 'post_status' => $status ) ) ;

                $redirect = add_query_arg( array ( 'fs_email_verification_success' => 'true' ) , $this->success_redirect_page ) ;
            } catch ( Exception $ex ) {

                $redirect = add_query_arg( array ( 'fs_email_verification_failure' => $ex->getMessage() ) , $this->failure_redirect_page ) ;
            }

            //redirect page
            wp_safe_redirect( $redirect ) ;
            exit() ;
        }

        /*
         * Display notices
         */

        public function display_notices() {

            if ( isset( $_GET[ 'fs_email_verification_success' ] ) && $_GET[ 'fs_email_verification_success' ] == 'true' ) {
                $message = __( 'Your email has been verified successfully' , FS_AFFILIATES_LOCALE ) ;

                echo '<p class="fs_affiliates_success_notices">' . $message . '</p>' ;
            }

            if ( isset( $_GET[ 'fs_email_verification_failure' ] ) && $_GET[ 'fs_email_verification_failure' ] ) {

                $message = '' ;
                switch ( $_GET[ 'fs_email_verification_failure' ] ) {
                    case 'mismatch_data':
                        $message = __( 'This link is not valid' , FS_AFFILIATES_LOCALE ) ;
                        break ;
                    case 'validity_expired':
                        $message = __( 'This link has expired' , FS_AFFILIATES_LOCALE ) ;
                        break ;
                }

                if ( $message )
                    echo '<p class="fs_affiliates_failure_notices">' . $message . '</p>' ;
            }
        }

    }

}

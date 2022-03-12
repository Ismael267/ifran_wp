<?php

/**
 * Affiliate Payment Success
 */
if ( !defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( !class_exists( 'FS_Affiliates_Payment_Success' ) ) {

    /**
     * Class FS_Affiliates_Payment_Success
     */
    class FS_Affiliates_Payment_Success extends FS_Affiliates_Notifications {

        /**
         * Class Constructor
         */
        public function __construct() {
            $this->id    = 'affiliate_payment_success' ;
            $this->title = __( 'Affiliate - Affiliate Payment Success' , FS_AFFILIATES_LOCALE ) ;

            // Triggers for this email.
            add_action( 'fs_affiliates_payment_success_for_affiliate' , array ( $this , 'trigger' ) , 10 , 2 ) ;

            parent::__construct() ;
        }

        /*
         * Default Subject
         */

        public function get_default_subject() {

            return __( '{site_name} - Affiliate Payment Update' , FS_AFFILIATES_LOCALE ) ;
        }

        /*
         * Default Message
         */

        public function get_default_message() {

            return __( 'Hi,

                        Your Payment for your Affiliate Account{affiliate_name} on {site_name} is Successfully Processed.
                     
                       Thanks.' , FS_AFFILIATES_LOCALE ) ;
        }

        /*
         * Default SMS Message
         */

        public function get_sms_default_message() {

            return __( 'Your Payment for your Affiliate Account{affiliate_name} on {site_name} is Successfully Processed.' , FS_AFFILIATES_LOCALE ) ;
        }

        /*
         * Get settings link
         */

        public function settings_link() {
            return add_query_arg( array ( 'page' => 'fs_affiliates' , 'tab' => 'notifications' , 'section' => $this->id ) , admin_url( 'admin.php' ) ) ;
        }

        /**
         * Trigger the sending of this email.
         */
        public function trigger( $affiliate_id , $payout_id , $affiliate = false ) {
            if ( $affiliate_id && !is_a( $affiliate , 'FS_Affiliates_Data' ) ) {
                $affiliate = new FS_Affiliates_Data( $affiliate_id ) ;
            }

            if ( is_a( $affiliate , 'FS_Affiliates_Data' ) ) {
                $this->payout_id                          = $payout_id ;
                $this->recipient                          = $affiliate->email ;
                $this->sms_recipient                      = $affiliate->phone_number ;
                $this->placeholders[ '{affiliate_name}' ] = $affiliate->user_name ;
            }

            if ( $this->is_email_enabled() && $this->get_recipient() ) {
                $this->send_email( $this->get_recipient() , $this->get_subject() , $this->get_message() , $this->get_headers() , $this->get_attachments() ) ;
            }

            if ( $this->is_sms_enabled() && $this->get_sms_recipient() ) {

                $this->send_sms( $this->get_sms_recipient() , $this->get_sms_message() ) ;
            }
        }

        /*
         * Get settings options array
         */

        public function settings_options_array() {
            $settings = array () ;

            $settings[] = array (
                'type'  => 'title' ,
                'title' => __( 'Email Settings' , FS_AFFILIATES_LOCALE ) ,
                'id'    => 'affiliate_payment_success_options' ,
                    ) ;
            $settings[] = array (
                'title'   => __( 'Send Affiliate Payment Success Email to Affiliate' , FS_AFFILIATES_LOCALE ) ,
                'id'      => $this->plugin_slug . '_' . $this->id . '_email_enabled' ,
                'type'    => 'checkbox' ,
                'default' => '' ,
                    ) ;
            $settings[] = array (
                'title'   => __( 'Email Subject' , FS_AFFILIATES_LOCALE ) ,
                'id'      => $this->plugin_slug . '_' . $this->id . '_subject' ,
                'type'    => 'text' ,
                'default' => $this->get_default_subject() ,
                    ) ;
            $settings[] = array (
                'title'   => __( 'Email Message' , FS_AFFILIATES_LOCALE ) ,
                'id'      => $this->plugin_slug . '_' . $this->id . '_message' ,
                'type'    => 'wpeditor' ,
                'default' => $this->get_default_message() ,
                    ) ;
            $settings[] = array (
                'title'   => __( 'Email Attachments' , FS_AFFILIATES_LOCALE ) ,
                'id'      => $this->plugin_slug . '_' . $this->id . '_email_attachments' ,
                'type'    => 'file_upload' ,
                'default' => array () ,
                    ) ;
            $settings[] = array (
                'type' => 'sectionend' ,
                'id'   => 'affiliate_payment_success_options' ,
                    ) ;

            if ( $this->sms_module_enabled() ) {

                $settings[] = array (
                    'type'  => 'title' ,
                    'title' => __( 'SMS Settings' , FS_AFFILIATES_LOCALE ) ,
                    'id'    => 'affiliate_payment_success_sms_options' ,
                        ) ;
                $settings[] = array (
                    'title'   => __( 'Send Affiliate Payment Success SMS to Affiliate' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->plugin_slug . '_' . $this->id . '_sms_enabled' ,
                    'type'    => 'checkbox' ,
                    'default' => '' ,
                        ) ;
                $settings[] = array (
                    'title'   => __( 'SMS Message' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->plugin_slug . '_' . $this->id . '_sms_message' ,
                    'type'    => 'wpeditor' ,
                    'default' => $this->get_sms_default_message() ,
                        ) ;
                $settings[] = array (
                    'type' => 'sectionend' ,
                    'id'   => 'affiliate_payment_success_sms_options' ,
                        ) ;
            }

            return $settings ;
        }

    }

}
    
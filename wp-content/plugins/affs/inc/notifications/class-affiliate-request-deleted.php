<?php

/**
 * Affiliate Request Deleted
 */
if ( !defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( !class_exists( 'FS_Affiliates_Request_Deleted' ) ) {

    /**
     * Class FS_Affiliates_Request_Deleted
     */
    class FS_Affiliates_Request_Deleted extends FS_Affiliates_Notifications {

        /**
         * Class Constructor
         */
        public function __construct() {
            $this->id    = 'affiliate_request_deleted' ;
            $this->title = __( 'Affiliate - Affiliate Request Deleted' , FS_AFFILIATES_LOCALE ) ;

            // Triggers for this email.
            add_action( 'fs_affiliates_status_changed_fs_pending_approval_to_deleted' , array ( $this , 'trigger' ) , 10 , 2 ) ;

            parent::__construct() ;
        }

        /*
         * Default Subject
         */

        public function get_default_subject() {

            return __( '{site_name} - Affiliate Request Deleted' , FS_AFFILIATES_LOCALE ) ;
        }

        /*
         * Default Message
         */

        public function get_default_message() {

            return __( 'Hi,

                        Unfortunately your Affiliate Application {affiliate_ref_number} has been Rejected on {site_name}.
                       
                       Thanks.' , FS_AFFILIATES_LOCALE ) ;
        }

        /*
         * Default SMS Message
         */

        public function get_sms_default_message() {

            return __( 'Unfortunately your Affiliate Application {affiliate_ref_number} has been Rejected on {site_name}.' , FS_AFFILIATES_LOCALE ) ;
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
        public function trigger( $affiliate_id , $affiliate = false ) {
            if ( $affiliate_id && !is_a( $affiliate , 'FS_Affiliates_Data' ) ) {
                $affiliate = new FS_Affiliates_Data( $affiliate_id ) ;
            }

            if ( !is_a( $affiliate , 'FS_Affiliates_Data' ) )
                return ;

            if ( $affiliate->get_status() != 'fs_pending_approval' )
                return ;

            $this->recipient                                = $affiliate->email ;
            $this->sms_recipient                            = $affiliate->phone_number ;
            $this->placeholders[ '{affiliate_ref_number}' ] = $affiliate->get_id() ;


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
                'id'    => 'affiliate_request_deleted_options' ,
                    ) ;
            $settings[] = array (
                'title'   => __( 'Send Affiliate Request Deleted Email to Affiliate' , FS_AFFILIATES_LOCALE ) ,
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
                'id'   => 'affiliate_request_deleted_options' ,
                    ) ;

            if ( $this->sms_module_enabled() ) {

                $settings[] = array (
                    'type'  => 'title' ,
                    'title' => __( 'SMS Settings' , FS_AFFILIATES_LOCALE ) ,
                    'id'    => 'affiliate_request_deleted_sms_options' ,
                        ) ;
                $settings[] = array (
                    'title'   => __( 'Send Affiliate Request Deleted SMS to Affiliate' , FS_AFFILIATES_LOCALE ) ,
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
                    'id'   => 'affiliate_request_deleted_sms_options' ,
                        ) ;
            }

            return $settings ;
        }

    }

}
    
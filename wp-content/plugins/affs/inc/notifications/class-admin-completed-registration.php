<?php

/**
 * Admin Completed Affiliate Registration
 */
if ( !defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( !class_exists( 'FS_Affiliates_Admin_Completed_Registration' ) ) {

    /**
     * Class FS_Affiliates_Admin_Completed_Registration
     */
    class FS_Affiliates_Admin_Completed_Registration extends FS_Affiliates_Notifications {

        /**
         * Class Constructor
         */
        public function __construct() {
            $this->id    = 'admin_completed_registration' ;
            $this->title = __( 'Admin - Affiliate Registration Completed' , FS_AFFILIATES_LOCALE ) ;

            // Triggers for this email.
            add_action( 'fs_affiliates_status_changed_new_to_fs_active' , array ( $this , 'trigger' ) , 10 , 2 ) ;
            add_action( 'fs_affiliates_status_changed_fs_hold_to_fs_active' , array ( $this , 'trigger' ) , 10 , 2 ) ;
            add_action( 'fs_affiliates_status_changed_fs_pending_approval_to_fs_active' , array ( $this , 'trigger' ) , 10 , 2 ) ;

            parent::__construct() ;
        }

        /*
         * Default Subject
         */

        public function get_default_subject() {

            return __( '{site_name} - New Affiliate Registration' , FS_AFFILIATES_LOCALE ) ;
        }

        /*
         * Default Message
         */

        public function get_default_message() {

            return __( 'Hi,

                        A New Affiliate has been Registered on {site_name}
               
                       Affiliate Details

                       Affiliate Name: {affiliate_name}                    
                       Affiliate Email: {affiliate_email}  
                      
                       Thanks.' , FS_AFFILIATES_LOCALE
                    ) ;
        }

        /*
         * Default SMS Message
         */

        public function get_sms_default_message() {

            return __( 'New Affiliate has been Registered on {site_name}
               
                       Affiliate Details

                       Affiliate Name: {affiliate_name}' , FS_AFFILIATES_LOCALE
                    ) ;
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

            if ( is_a( $affiliate , 'FS_Affiliates_Data' ) ) {
                $sms_module_object                         = FS_Affiliates_Module_Instances::get_module_by_id( 'sms' ) ;
                $this->recipient                           = $this->get_admin_emails() ;
                $this->sms_recipient                       = $sms_module_object->admin_number ;
                $this->placeholders[ '{affiliate_name}' ]  = $affiliate->user_name ;
                $this->placeholders[ '{affiliate_email}' ] = $affiliate->email ;
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
                'id'    => 'admin_completed_registration_options' ,
                    ) ;
            $settings[] = array (
                'title'   => __( 'Send Affiliate Registration Completed Email to Admin' , FS_AFFILIATES_LOCALE ) ,
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
                'type' => 'sectionend' ,
                'id'   => 'admin_completed_registration_options' ,
                    ) ;

            if ( $this->sms_module_enabled() ) {

                $settings[] = array (
                    'type'  => 'title' ,
                    'title' => __( 'SMS Settings' , FS_AFFILIATES_LOCALE ) ,
                    'id'    => 'admin_completed_registration_sms_options' ,
                        ) ;
                $settings[] = array (
                    'title'   => __( 'Send Affiliate Registration Completed SMS to Admin' , FS_AFFILIATES_LOCALE ) ,
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
                    'id'   => 'admin_completed_registration_sms_options' ,
                        ) ;
            }

            return $settings ;
        }

    }

}
    
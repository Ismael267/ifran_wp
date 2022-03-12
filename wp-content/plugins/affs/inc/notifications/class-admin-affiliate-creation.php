<?php

/**
 * Admin Completed Affiliate Registration
 */
if ( ! defined ( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( ! class_exists ( 'FS_Affiliates_Account_Creation_by_Admin' ) ) {

    /**
     * Class FS_Affiliates_Account_Creation_by_Admin
     */
    class FS_Affiliates_Account_Creation_by_Admin extends FS_Affiliates_Notifications {

        /**
         * Class Constructor
         */
        public function __construct() {
            $this->id    = 'account_creation_by_admin' ;
            $this->title = __ ( 'Affiliate - Account Creation by Admin' , FS_AFFILIATES_LOCALE ) ;

            // Triggers for this email.
            add_action ( 'fs_affiliates_admin_to_affiliate_notification' , array ( $this , 'trigger' ) , 10 , 2 ) ;

            parent::__construct () ;
        }

        /*
         * Default Subject
         */

        public function get_default_subject() {

            return __ ( '{site_name} - New Affiliate Account Created' , FS_AFFILIATES_LOCALE ) ;
        }

        /*
         * Default Message
         */

        public function get_default_message() {

            return __ ( 'Hi,

                        A New Affiliate Account has been created for you on  {site_name}. Following are the Affiliate Details,

                        Affiliate Name: {affiliate_name}
                        Affiliate Email: {affiliate_email}
                        Affiliate Password: {affiliate_password}

                        You can access your affiliate account using the following link {site_link}

                       Thanks.' , FS_AFFILIATES_LOCALE
                    ) ;
        }

        /*
         * Get settings link
         */

        public function settings_link() {
            return add_query_arg ( array ( 'page' => 'fs_affiliates' , 'tab' => 'notifications' , 'section' => $this->id ) , admin_url ( 'admin.php' ) ) ;
        }

        /**
         * Trigger the sending of this email.
         */
        public function trigger( $affiliate_id , $meta_data , $affiliate = false ) {

          if ( $affiliate_id && ! is_a( $affiliate , 'FS_Affiliates_Data' ) ) {
              $affiliate = new FS_Affiliates_Data( $affiliate_id ) ;
          }

          if ( is_a( $affiliate , 'FS_Affiliates_Data' ) ) {
              $this->recipient = $affiliate->email ;

              $this->placeholders[ '{affiliate_email}' ]  = $affiliate->email ;
              $this->placeholders[ '{affiliate_name}' ]   = $affiliate->user_name ;
              $this->placeholders[ '{affiliate_password}' ] = isset($meta_data['password']) ? $meta_data['password'] : '-' ;
              $this->placeholders[ '{site_link}' ]          = get_permalink ( fs_affiliates_get_page_id ( 'login' ) ) ;
          }

          if ( $this->is_email_enabled() && $this->get_recipient() ) {
              $this->send_email( $this->get_recipient() , $this->get_subject() , $this->get_message() , $this->get_headers() , $this->get_attachments() ) ;
          }

        }

        /*
         * Get settings options array
         */

        public function settings_options_array() {
            $settings = array () ;

            $settings[] = array (
                'type'  => 'title' ,
                'title' => __ ( 'Email Settings' , FS_AFFILIATES_LOCALE ) ,
                'id'    => 'admin_created_affiliate_registration_options' ,
                    ) ;
            $settings[] = array (
                'title'   => __ ( 'Email Subject' , FS_AFFILIATES_LOCALE ) ,
                'id'      => $this->plugin_slug . '_' . $this->id . '_subject' ,
                'type'    => 'text' ,
                'default' => $this->get_default_subject () ,
                    ) ;
            $settings[] = array (
                'title'   => __ ( 'Email Message' , FS_AFFILIATES_LOCALE ) ,
                'id'      => $this->plugin_slug . '_' . $this->id . '_message' ,
                'type'    => 'wpeditor' ,
                'default' => $this->get_default_message () ,
                    ) ;
            $settings[] = array (
                'type' => 'sectionend' ,
                'id'   => 'admin_created_affiliate_registration_options' ,
                    ) ;

            return $settings ;
        }

    }

}

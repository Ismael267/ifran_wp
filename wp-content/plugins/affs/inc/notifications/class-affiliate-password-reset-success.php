<?php

/**
 * Affiliate Password Reset Success
 */
if ( !defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( !class_exists( 'FS_Affiliates_Password_Reset_Success' ) ) {

    /**
     * Class FS_Affiliates_Password_Reset_Success
     */
    class FS_Affiliates_Password_Reset_Success extends FS_Affiliates_Notifications {

        /**
         * Class Constructor
         */
        public function __construct() {
            $this->id    = 'affiliate_password_reset_success' ;
            $this->title = __( 'Affiliate - Affiliate Password Reset Success' , FS_AFFILIATES_LOCALE ) ;

            // Triggers for this email.
            add_action( 'fs_affiliates_password_changed_notification' , array ( $this , 'trigger' ) , 10 , 1 ) ;

            parent::__construct() ;
        }

        /*
         * Default Subject
         */

        public function get_default_subject() {

            return __( '{site_name} - Password Reset Success' , FS_AFFILIATES_LOCALE ) ;
        }

        /*
         * Default Message
         */

        public function get_default_message() {

            return __( "Hi,

                        Your Request to reset the Password for your Affiliate Account{user_name} on {site_name} has been successfully processed.

                       Thanks." , FS_AFFILIATES_LOCALE ) ;
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
        public function trigger( $user ) {

            if ( is_a( $user , 'WP_User' ) ) {
                $this->recipient                     = $user->user_email ;
                $this->placeholders[ '{user_name}' ] = $user->user_name ;
            }

            if ( $this->is_enabled() && $this->get_recipient() ) {
                $this->send_email( $this->get_recipient() , $this->get_subject() , $this->get_message() , $this->get_headers() , $this->get_attachments() ) ;
            }
        }

        /*
         * Get settings options array
         */

        public function settings_options_array() {
            return array (
                array (
                    'type'  => 'title' ,
                    'title' => __( 'Affiliate - Affiliate Password Reset Success' , FS_AFFILIATES_LOCALE ) ,
                    'id'    => 'affiliate_password_reset_success_options' ,
                ) ,
                array (
                    'title'   => __( 'Email Subject' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->plugin_slug . '_' . $this->id . '_subject' ,
                    'type'    => 'text' ,
                    'default' => $this->get_default_subject() ,
                ) ,
                array (
                    'title'   => __( 'Email Message' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->plugin_slug . '_' . $this->id . '_message' ,
                    'type'    => 'wpeditor' ,
                    'default' => $this->get_default_message() ,
                ) ,
                array (
                    'type' => 'sectionend' ,
                    'id'   => 'affiliate_password_reset_success_options' ,
                ) ,
                    ) ;
        }

    }

}
    
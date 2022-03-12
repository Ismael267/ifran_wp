<?php

/**
 * Affiliate Password Reset
 */
if ( !defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( !class_exists( 'FS_Affiliates_Password_Reset' ) ) {

    /**
     * Class FS_Affiliates_Password_Reset
     */
    class FS_Affiliates_Password_Reset extends FS_Affiliates_Notifications {

        /**
         * Class Constructor
         */
        public function __construct() {
            $this->id    = 'affiliate_password_reset' ;
            $this->title = __( 'Affiliate - Affiliate Password Reset' , FS_AFFILIATES_LOCALE ) ;

            // Triggers for this email.
            add_action( 'fs_affiliates_reset_password_notification' , array ( $this , 'trigger' ) , 10 , 2 ) ;

            parent::__construct() ;
        }

        /*
         * Default Subject
         */

        public function get_default_subject() {

            return __( '{site_name} - Password Reset Request' , FS_AFFILIATES_LOCALE ) ;
        }

        /*
         * Default Message
         */

        public function get_default_message() {

            return __( "Hi,
                        Someone has requested to reset the password for the Affiliate Account{user_name} on {site_name}.
                        To Reset the Password, use the following {password_reset}. 
                        If you didn't send the password reset request, you can ignore this email.
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
        public function trigger( $user_data , $key ) {

            if ( is_a( $user_data , 'WP_User' ) ) {
                $this->recipient                          = $user_data->user_email ;
                $this->placeholders[ '{user_name}' ]      = $user_data->user_login ;
                $this->placeholders[ '{password_reset}' ] = $this->get_reset_password_link( $user_data , $key ) ;
            }

            if ( $this->is_enabled() && $this->get_recipient() ) {
                $this->send_email( $this->get_recipient() , $this->get_subject() , $this->get_message() , $this->get_headers() , $this->get_attachments() ) ;
            }
        }

        /*
         * Get reset password link
         */

        protected function get_reset_password_link( $user_data , $key ) {
            $lost_password_page = get_permalink( fs_affiliates_get_page_id( 'lost_password' ) ) ;

            $href = add_query_arg( array ( 'fs_affiliates_reset_key' => $key , 'id' => $user_data->ID ) , $lost_password_page ) ;

            return '<a href="' . $href . '">' . __( 'Click here to reset' , FS_AFFILIATES_LOCALE ) . '</a>' ;
        }

        /*
         * Get settings options array
         */

        public function settings_options_array() {
            $settings = array () ;

            $settings[] = array (
                'type'  => 'title' ,
                'title' => __( 'Affiliate - Affiliate Password Reset' , FS_AFFILIATES_LOCALE ) ,
                'id'    => 'affiliate_password_reset_options' ,
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
                'css'     => 'width:500px' ,
                'default' => $this->get_default_message() ,
                    ) ;
            $settings[] = array (
                'type' => 'sectionend' ,
                'id'   => 'affiliate_password_reset_options' ,
                    ) ;

            return $settings ;
        }

    }

}
    
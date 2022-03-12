<?php

/**
 * Payout Request Submitted Notification for Affiliate
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'FS_Affiliates_Payout_Request_Submitted_Notification' ) ) {

    /**
     * Class FS_Affiliates_Payout_Request_Submitted_Notification
     */
    class FS_Affiliates_Payout_Request_Submitted_Notification extends FS_Affiliates_Notifications {

        /**
         * Class Constructor
         */
        public function __construct() {
            $this->id    = 'affiliate_payout_request_submitted' ;
            $this->title = __( 'Affiliate - Affiliate Payout Request Submitted' , FS_AFFILIATES_LOCALE ) ;

            // Triggers for this email.
            add_action( 'fs_affiliates_status_to_fs_submitted' , array( $this , 'trigger' ) , 10 , 1 ) ;

            parent::__construct() ;
        }

        /*
         * Default Subject
         */

        public function get_default_subject() {

            return __( 'Payout Request Submitted Notification' , FS_AFFILIATES_LOCALE ) ;
        }

        /*
         * Default Message
         */

        public function get_default_message() {

            return __( 'Hi,

                        A New Payout Request on {site_name} is submitted successfully
               
                       Payout Request Details

                       Total Unpaid Commission: {unpaid_commission}                     
                       Status: {status}  
                      
                       Thanks.' , FS_AFFILIATES_LOCALE ) ;
        }

        /*
         * Get settings link
         */

        public function settings_link() {
            return add_query_arg( array( 'page' => 'fs_affiliates' , 'tab' => 'notifications' , 'section' => $this->id ) , admin_url( 'admin.php' ) ) ;
        }

        /**
         * Trigger the sending of this email.
         */
        public function trigger( $post_id ) {
            $postobj      = get_post( $post_id ) ;
            $affiliate_id = $postobj->post_author ;
            $affiliate    = new FS_Affiliates_Data( $affiliate_id ) ;

            $this->recipient                             = $affiliate->email ;
            $this->placeholders[ '{unpaid_commission}' ] = fs_affiliates_price( get_post_meta( $post_id , 'fs_affiliates_unpaid_commission' , true ) ) ;
            $this->placeholders[ '{status}' ]            = 'Submitted' ;
            if ( $this->is_enabled() && $this->get_recipient() )
                $this->send_email( $this->get_recipient() , $this->get_subject() , $this->get_message() , $this->get_headers() , $this->get_attachments() ) ;
        }

        /*
         * Get settings options array
         */

        public function settings_options_array() {
            $settings = array() ;

            $settings[] = array(
                'type'  => 'title' ,
                'title' => __( 'Email Settings' , FS_AFFILIATES_LOCALE ) ,
                'id'    => 'admin_payout_request_submitted_options' ,
                    ) ;
            $settings[] = array(
                'title'   => __( 'Email Subject' , FS_AFFILIATES_LOCALE ) ,
                'id'      => $this->plugin_slug . '_' . $this->id . '_subject' ,
                'type'    => 'text' ,
                'default' => $this->get_default_subject() ,
                    ) ;
            $settings[] = array(
                'title'   => __( 'Email Message' , FS_AFFILIATES_LOCALE ) ,
                'id'      => $this->plugin_slug . '_' . $this->id . '_message' ,
                'type'    => 'wpeditor' ,
                'default' => $this->get_default_message() ,
                    ) ;
            $settings[] = array(
                'type' => 'sectionend' ,
                'id'   => 'admin_payout_request_submitted_options' ,
                    ) ;

            return $settings ;
        }

    }

}
    
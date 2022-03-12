<?php

/**
 * Periodic Reports Notification
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'FS_Affiliates_Periodic_Report_Notification' ) ) {

    /**
     * Class FS_Affiliates_Periodic_Report_Notification
     */
    class FS_Affiliates_Periodic_Report_Notification extends FS_Affiliates_Notifications {

        /**
         * Class Constructor
         */
        public function __construct() {
            $this->id    = 'affiliate_periodic_report' ;
            $this->title = __( 'Affiliate - Affiliate Periodic Reports' , FS_AFFILIATES_LOCALE ) ;

            // Triggers for this email.
            add_action( 'fs_affiliates_periodic_reports_for_affiliate' , array( $this , 'trigger' ) , 10 , 3 ) ;

            parent::__construct() ;
        }

        /*
         * Default Subject
         */

        public function get_default_subject() {

            return __( '{site_name} - Affiliate Periodic Reports' , FS_AFFILIATES_LOCALE ) ;
        }

        /*
         * Default Message
         */

        public function get_default_message() {

            return __( 'Hi,

                        Here is your Report on your Affiliate Account for the period of {duration}

                        Visits: {total_visits}
                        Paid Referrals: {paid_referrals}
                        Unpaid Referrals: {unpaid_referrals}
                        Paid Commission: {paid_commission}
                        Unpaid Commission: {unpaid_commission}

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
        public function trigger( $affiliate_id , $extra_data , $affiliate = false ) {
            if ( $affiliate_id && ! is_a( $affiliate , 'FS_Affiliates_Data' ) ) {
                $affiliate = new FS_Affiliates_Data( $affiliate_id ) ;
            }

            if ( is_a( $affiliate , 'FS_Affiliates_Data' ) ) {
                $this->recipient = $affiliate->email ;

                $this->placeholders[ '{total_visits}' ]      = $extra_data[ 'visit_count' ] ;
                $this->placeholders[ '{paid_referrals}' ]    = $extra_data[ 'referral_paid_count' ] ;
                $this->placeholders[ '{unpaid_referrals}' ]  = $extra_data[ 'referral_unpaid_count' ] ;
                $this->placeholders[ '{paid_commission}' ]   = $extra_data[ 'referral_paid_commisssion' ] ;
                $this->placeholders[ '{unpaid_commission}' ] = $extra_data[ 'referral_unpaid_commisssion' ] ;
                $this->placeholders[ '{duration}' ]          = $extra_data[ 'duration' ] ;
            }

            if ( $this->is_enabled() && $this->get_recipient() ) {
                $this->send_email( $this->get_recipient() , $this->get_subject() , $this->get_message() , $this->get_headers() , $this->get_attachments() ) ;
            }
        }

        /*
         * Get settings options array
         */

        public function settings_options_array() {
            $settings = array() ;

            $settings[] = array(
                'type'  => 'title' ,
                'title' => __( 'Email Settings' , FS_AFFILIATES_LOCALE ) ,
                'id'    => 'affiliate_periodic_reports_options' ,
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
                'title'   => __( 'Email Attachments' , FS_AFFILIATES_LOCALE ) ,
                'id'      => $this->plugin_slug . '_' . $this->id . '_email_attachments' ,
                'type'    => 'file_upload' ,
                'default' => array() ,
                    ) ;
            $settings[] = array(
                'type' => 'sectionend' ,
                'id'   => 'affiliate_periodic_reports_options' ,
                    ) ;

            return $settings ;
        }

    }

}

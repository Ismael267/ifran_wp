<?php

/**
 * Periodic Reports
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'FS_Affiliates_Periodic_Reports_Module' ) ) {

    /**
     * Class FS_Affiliates_Periodic_Reports_Module
     */
    class FS_Affiliates_Periodic_Reports_Module extends FS_Affiliates_Modules {
        /*
         * Data
         */

        protected $data = array(
            'enabled'                   => 'no' ,
            'allowed_affiliates_method' => '' ,
            'selected_affiliates'       => '' ,
            'email_frequency'           => '' ,
                ) ;

        /**
         * Class Constructor
         */
        public function __construct() {
            $this->id    = 'periodic_reports' ;
            $this->title = __( 'Periodic Reports' , FS_AFFILIATES_LOCALE ) ;

            parent::__construct() ;
        }

        /*
         * Get settings link
         */

        public function settings_link() {
            return add_query_arg( array( 'page' => 'fs_affiliates' , 'tab' => 'modules' , 'section' => $this->id ) , admin_url( 'admin.php' ) ) ;
        }

        /*
         * Get settings options array
         */

        public function settings_options_array() {

            return array(
                array(
                    'type'  => 'title' ,
                    'title' => __( 'Periodic Reports' , FS_AFFILIATES_LOCALE ) ,
                    'id'    => 'periodic_reports_display' ,
                ) ,
                array(
                    'title'   => __( 'Affiliate Selection' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->get_field_key( 'allowed_affiliates_method' ) ,
                    'class'   => 'fs_affiliates_allowed_affiliates_method' ,
                    'type'    => 'select' ,
                    'desc'    => __( 'The affiliates selected here will be receiving periodic reports about their affiliate activity via email.' , FS_AFFILIATES_LOCALE ) ,
                    'default' => '1' ,
                    'options' => array(
                        '1' => __( 'All Affiliates' , FS_AFFILIATES_LOCALE ) ,
                        '2' => __( 'Selected Affiliates' , FS_AFFILIATES_LOCALE ) , ) ,
                ) ,
                array(
                    'title'     => __( 'Select Affiliates' , FS_AFFILIATES_LOCALE ) ,
                    'id'        => $this->get_field_key( 'selected_affiliates' ) ,
                    'type'      => 'ajaxmultiselect' ,
                    'class'     => 'fs_affiliates_selected_affiliate' ,
                    'list_type' => 'affiliates' ,
                    'action'    => 'fs_affiliates_search' ,
                    'default'   => array() ,
                ) ,
                array(
                    'title'   => __( 'Email Frequency' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->get_field_key( 'email_frequency' ) ,
                    'type'    => 'select' ,
                    'default' => '1' ,
                    'options' => array(
                        '1' => __( 'Daily' , FS_AFFILIATES_LOCALE ) ,
                        '2' => __( 'Weekly' , FS_AFFILIATES_LOCALE ) ,
                        '3' => __( 'Monthly' , FS_AFFILIATES_LOCALE ) ,
                    ) ,
                ) ,
                array(
                    'type' => 'sectionend' ,
                    'id'   => 'periodic_reports_display' ,
                ) ,
                    ) ;
        }

        /*
         * Save
         */

        public function save() {
            if ( $_POST[ $this->get_field_key( 'email_frequency' ) ] != $this->get_option( 'email_frequency' ) ) {
                $this->custom_cron_event( true ) ;
            }
        }

        /*
         * Actions
         */

        public function actions() {
            add_action( 'init' , array( $this , 'custom_cron_event' ) ) ;
            add_action( 'fs_affiliates_periodic_reports' , array( $this , 'send_email_for_affiliate' ) ) ;
            add_filter( 'cron_schedules' , array( $this , 'custom_cron_schedule' ) ) ;
        }

        /*
         * Cron Schedule Function
         */

        public function custom_cron_schedule( $schedules ) {
            $interval                                                    = $this->schedule_time() ;
            $schedules[ 'fs_affiliates_periodic_reports_cron_interval' ] = array(
                'interval' => $interval[ 'sec_count' ] ,
                'display'  => $interval[ 'label' ] ,
                    ) ;

            return $schedules ;
        }

        /*
         * Cron Trigger Function
         */

        public function custom_cron_event( $clear_event = false ) {
            if ( $clear_event )
                wp_clear_scheduled_hook( 'fs_affiliates_periodic_reports' ) ;

            if ( ! wp_next_scheduled( 'fs_affiliates_periodic_reports' ) ) {
                wp_schedule_event( time() , 'fs_affiliates_periodic_reports_cron_interval' , 'fs_affiliates_periodic_reports' ) ;
            }
        }

        /*
         * schedule time
         */

        public function schedule_time() {
            $time = array() ;

            if ( $this->email_frequency == "2" ) {
                $time[ 'sec_count' ] = '604800' ;
                $time[ 'label' ]     = __( 'Weekly' , FS_AFFILIATES_LOCALE ) ;
            } elseif ( $this->email_frequency == "3" ) {
                $time[ 'sec_count' ] = '2628000' ;
                $time[ 'label' ]     = __( 'Monthly' , FS_AFFILIATES_LOCALE ) ;
            } else {
                $time[ 'sec_count' ] = '86400' ;
                $time[ 'label' ]     = __( 'Daily' , FS_AFFILIATES_LOCALE ) ;
            }

            return $time ;
        }

        /**
         *  Get Affiliates id
         */
        public function prepare_affiliates() {

            if ( $this->allowed_affiliates_method == "2" ) {
                $affiliate_ids = fs_affiliates_check_is_array( $this->selected_affiliates ) ? $this->selected_affiliates : array() ;
            } else {
                $affiliate_ids = fs_affiliates_get_active_affiliates() ;
            }

            return $affiliate_ids ;
        }

        /*
         * prepare mail contents for periodic reports
         */

        public function send_email_for_affiliate() {
            $affiliate_ids = $this->prepare_affiliates() ;

            foreach ( $affiliate_ids as $affiliate_id ) {
                $affiliate_object = new FS_Affiliates_Data( $affiliate_id ) ;
                $current_time     = current_time( 'timestamp' ) ;
                if ( $this->email_frequency == "2" ) {
                    $start_date                  = strtotime( '-6 days' , strtotime( 'midnight' , $current_time ) ) ;
                    $end_date                    = strtotime( 'midnight' , $current_time ) ;
                    $duration                    = fs_affiliates_local_datetime( $start_date , true , false ) . ' - ' . fs_affiliates_local_datetime( $end_date - 1 , true , false ) ;
                    $referral_paid_count         = $this->periodic_referrals_counts( $affiliate_id , 'fs_paid' , '- 7 days' ) ;
                    $referral_unpaid_count       = $this->periodic_referrals_counts( $affiliate_id , 'fs_unpaid' , '- 7 days' ) ;
                    $referral_unpaid_commisssion = $this->get_referrals_commission( $affiliate_id , 'fs_unpaid' , $start_date , $end_date ) ;
                    $referral_paid_commisssion   = $this->get_referrals_commission( $affiliate_id , 'fs_paid' , $start_date , $end_date ) ;
                    $visit_count                 = $this->periodic_visits_counts( $affiliate_id , '- 7 days' ) ;
                } elseif ( $this->email_frequency == "3" ) {
                    $start_date                  = strtotime( '-30 days' , strtotime( 'midnight' , $current_time ) ) ;
                    $end_date                    = strtotime( 'midnight' , $current_time ) ;
                    $duration                    = fs_affiliates_local_datetime( $start_date , true , false ) . ' - ' . fs_affiliates_local_datetime( $end_date - 1 , true , false ) ;
                    $referral_paid_count         = $this->periodic_referrals_counts( $affiliate_id , 'fs_paid' , '- 30 days' ) ;
                    $referral_unpaid_count       = $this->periodic_referrals_counts( $affiliate_id , 'fs_unpaid' , '- 30 days' ) ;
                    $referral_unpaid_commisssion = $this->get_referrals_commission( $affiliate_id , 'fs_unpaid' , $start_date , $end_date ) ;
                    $referral_paid_commisssion   = $this->get_referrals_commission( $affiliate_id , 'fs_paid' , $start_date , $end_date ) ;
                    $visit_count                 = $this->periodic_visits_counts( $affiliate_id , '- 30 days' ) ;
                } else {
                    $start_date                  = strtotime( '-1 days' , strtotime( 'midnight' , $current_time ) ) ;
                    $end_date                    = strtotime( 'midnight' , current_time( 'timestamp' ) ) ;
                    $duration                    = fs_affiliates_local_datetime( $start_date , true , false ) ;
                    $referral_paid_count         = $this->periodic_referrals_counts( $affiliate_id , 'fs_paid' , '- 1 days' ) ;
                    $referral_unpaid_count       = $this->periodic_referrals_counts( $affiliate_id , 'fs_unpaid' , '- 1 days' ) ;
                    $referral_unpaid_commisssion = $this->get_referrals_commission( $affiliate_id , 'fs_unpaid' , $start_date , $end_date ) ;
                    $referral_paid_commisssion   = $this->get_referrals_commission( $affiliate_id , 'fs_paid' , $start_date , $end_date ) ;
                    $visit_count                 = $this->periodic_visits_counts( $affiliate_id , '- 1 days' ) ;
                }

                $referral_paid_count         = sprintf( __( '%s Referrals' , FS_AFFILIATES_LOCALE ) , $referral_paid_count ) ;
                $referral_unpaid_count       = sprintf( __( '%s Referrals' , FS_AFFILIATES_LOCALE ) , $referral_unpaid_count ) ;
                $referral_unpaid_commisssion = fs_affiliates_price( $referral_unpaid_commisssion ) ;
                $referral_paid_commisssion   = fs_affiliates_price( $referral_paid_commisssion ) ;
                $visit_count                 = sprintf( __( '%s Visits' , FS_AFFILIATES_LOCALE ) , $visit_count ) ;

                $extra_data = compact( 'duration' , 'referral_paid_count' , 'referral_unpaid_count' , 'visit_count' , 'referral_unpaid_commisssion' , 'referral_paid_commisssion' ) ;

                do_action( 'fs_affiliates_periodic_reports_for_affiliate' , $affiliate_id , $extra_data , $affiliate_object ) ;
            }
        }

        /*
         * Get paid and unpaid count function for periodic
         */

        public function periodic_referrals_counts( $affiliate_id , $status = false , $duration = false ) {
            if ( ! $status )
                $status = array( 'fs_unpaid' , 'fs_paid' , 'fs_pending' ) ;

            $args = array(
                'numberposts' => -1 ,
                'post_type'   => 'fs-referrals' ,
                'post_status' => $status ,
                'fields'      => 'ids' ,
                'author'      => $affiliate_id ,
                'date_query'  => array(
                    'column'    => 'post_date' ,
                    'after'     => $duration ,
                    'inclusive' => true ,
                )
                    ) ;

            $posts = get_posts( $args ) ;

            return count( $posts ) ;
        }

        /*
         * Get visits count function for periodic
         */

        public function periodic_visits_counts( $affiliate_id , $duration = false ) {
            $args = array(
                'numberposts' => -1 ,
                'post_type'   => 'fs-visits' ,
                'post_status' => array( 'fs_converted' , 'fs_notconverted' ) ,
                'fields'      => 'ids' ,
                'author'      => $affiliate_id ,
                'date_query'  => array(
                    'column'    => 'post_date' ,
                    'after'     => $duration ,
                    'inclusive' => true ,
                )
                    ) ;

            $posts = get_posts( $args ) ;

            return count( $posts ) ;
        }

        /*
         * Get referrals commission
         */

        public function get_referrals_commission( $affiliate_id , $status , $start_date , $end_date ) {
            global $wpdb ;

            $query = $wpdb->prepare( "SELECT sum(meta_value) FROM $wpdb->posts as p "
                    . "INNER JOIN $wpdb->postmeta as meta ON p.ID=meta.post_id "
                    . "WHERE p.post_type='fs-referrals' AND p.post_status=%s "
                    . "AND p.post_author=%s AND meta.meta_key='amount' AND "
                    . "p.post_date >= %s AND p.post_date < %s" , $status , $affiliate_id , date( 'Y-m-d H:i:s' , $start_date ) , date( 'Y-m-d H:i:s' , $end_date ) ) ;

            $amount = $wpdb->get_var( $query ) ;

            return isset( $amount ) ? $amount : 0 ;
        }

    }

}

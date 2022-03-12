<?php

/**
 * Notifications Instances Class
 */
if ( !defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( !class_exists( 'FS_Affiliates_Notification_Instances' ) ) {

    /**
     * Class FS_Affiliates_Notification_Instances
     */
    class FS_Affiliates_Notification_Instances {
        /*
         * Notifications
         */

        private static $notifications = array () ;

        /*
         * Get Notifications
         */

        public static function get_notifications() {
            if ( !self::$notifications ) {
                self::load_notifications() ;
            }

            return self::$notifications ;
        }

        /*
         * Load all Notifications
         */

        public static function load_notifications() {

            if ( !class_exists( 'FS_Affiliates_Notifications' ) )
                include FS_AFFILIATES_PLUGIN_PATH . '/inc/abstracts/class-fs-affiliates-notifications.php' ;

            $default_notification_classes = array(
                'admin-submitted-registration'       => 'FS_Affiliates_Admin_Submitted_Registration' ,
                'admin-completed-registration'       => 'FS_Affiliates_Admin_Completed_Registration' ,
                'admin-profile-updated'              => 'FS_Affiliates_Admin_Profile_Updated' ,
                'admin-new-referral-request'         => 'FS_Affiliates_Admin_New_Referral_Request' ,
                'admin-new-referral'                 => 'FS_Affiliates_Admin_New_Referral' ,
                'affiliate-request-submitted'        => 'FS_Affiliates_Request_Submitted' ,
                'affiliate-request-approved'         => 'FS_Affiliates_Request_Approved' ,
                'affiliate-request-rejected'         => 'FS_Affiliates_Request_Rejected' ,
                'affiliate-request-deleted'          => 'FS_Affiliates_Request_Deleted' ,
                'affiliate-profile-updated'          => 'FS_Affiliates_Profile_Updated' ,
                'affiliate-password-reset'           => 'FS_Affiliates_Password_Reset' ,
                'affiliate-password-reset-success'   => 'FS_Affiliates_Password_Reset_Success' ,
                'affiliate-new-referral'             => 'FS_Affiliates_New_Referral' ,
                'affiliate-payment-success'          => 'FS_Affiliates_Payment_Success' ,
                'affiliate-payment-failed'           => 'FS_Affiliates_Payment_Failed' ,
                'affiliate-account-deleted'          => 'FS_Affiliates_Account_Deleted' ,
                'refer-friend'                       => 'FS_Affiliates_Refer_friend' ,
                'affiliate-periodic-report'          => 'FS_Affiliates_Periodic_Report_Notification' ,
                'affiliate-payout-request'           => 'FS_Affiliates_Payout_Request_Notification' ,
                'affiliate-payout-request-submitted' => 'FS_Affiliates_Payout_Request_Submitted_Notification' ,
                'affiliate-payout-request-progress'  => 'FS_Affiliates_Payout_Request_Progress_Notification' ,
                'affiliate-payout-request-closed'    => 'FS_Affiliates_Payout_Request_Closed_Notification' ,
                'admin-affiliate-creation'           => 'FS_Affiliates_Account_Creation_by_Admin' ,
                    ) ;

            foreach ( $default_notification_classes as $file_name => $notification_class ) {

                // include file
                include 'class-' . $file_name . '.php' ;

                //add notification
                self::add_notification( new $notification_class ) ;
            }
        }

        /**
         * Add a Module
         */
        public static function add_notification( $notification ) {

            self::$notifications[ $notification->get_id() ] = $notification ;

            return new self() ;
        }

        /**
         * Get notification by id
         */
        public static function get_notification_by_id( $notification_id ) {
            $notifications = self::get_notifications() ;

            return isset( $notifications[ $notification_id ] ) ? $notifications[ $notification_id ] : false ;
        }

    }

}
    
<?php

/*
 * Pushover notifications handler
 */
if ( !defined( 'ABSPATH' ) )
    exit ; // Exit if accessed directly.

if ( !class_exists( 'FS_Affiliates_Pushover_Handler' ) ) {

    /**
     * FS_Affiliates_Pushover_Handler Class.
     */
    class FS_Affiliates_Pushover_Handler {
        /*
         * send pushover notifications
         */

        public static function send_notifications( $data ) {
            if ( !class_exists( 'Pushover' ) )
                require_once FS_AFFILIATES_PLUGIN_PATH . '/inc/lib/Pushover/Pushover.php' ;

            $push = new Pushover() ;
            $push->setToken( $data[ 'token' ] ) ;
            $push->setUser( $data[ 'user' ] ) ;

            $push->setTitle( $data[ 'title' ] ) ;
            $push->setMessage( $data[ 'message' ] ) ;
            $push->setUrl( '' ) ;
            $push->setUrlTitle( '' ) ;

            $push->setDevice( $data[ 'device' ] ) ;
            $push->setPriority( 2 ) ;
            $push->setRetry( 60 ) ;
            $push->setExpire( 3600 ) ;
            $push->setTimestamp( time() ) ;
            $push->setDebug( false ) ;
            $push->setSound( $data[ 'sound' ] ) ;

            return $push->send() ;
        }

        /*
         * send test pushover notifications
         */

        public static function send_test_pushover_notifications() {
            $data     = array () ;
            $pushover = FS_Affiliates_Module_Instances::get_module_by_id( 'pushover_notifications' ) ;

            $data[ 'title' ]   = 'Pushover Test Notification' ;
            $data[ 'message' ] = 'This is a sample Pushover Notification' ;
            $data[ 'sound' ]   = $pushover->sound_notification ;
            $data[ 'token' ]   = $pushover->api_key ;
            $data[ 'user' ]    = $pushover->admin_user_key ;
            $data[ 'device' ]  = $pushover->device_name ;

            return self::send_notifications( $data ) ;
        }

        /*
         * send referral pushover notifications
         */

        public static function send_referral_pushover_notifications( $referral_id , $affiliate_id ) {
            $data     = array () ;
            $pushover = FS_Affiliates_Module_Instances::get_module_by_id( 'pushover_notifications' ) ;

            $data[ 'title' ]   = stripslashes( $pushover->new_referral_subject ) ;
            $data[ 'message' ] = stripslashes( $pushover->new_referral_message ) ;
            $data[ 'sound' ]   = $pushover->sound_notification ;
            $data[ 'token' ]   = $pushover->api_key ;

            if ( in_array( 'referral' , $pushover->admin_notifications ) ) {
                $data[ 'user' ]   = $pushover->admin_user_key ;
                $data[ 'device' ] = $pushover->device_name ;
                self::send_notifications( $data ) ;
            }

            if ( $pushover->allow_affiliates == 'yes' && in_array( 'referral' , $pushover->affiliate_notifications ) ) {
                $affiliate = new FS_Affiliates_Data( $affiliate_id ) ;
                if ( $affiliate->referral_pushover == 'yes' ) {
                    $data[ 'user' ]   = $affiliate->pushover_key ;
                    $data[ 'device' ] = $affiliate->device_name ;
                    self::send_notifications( $data ) ;
                }
            }
        }

        /*
         * send visit pushover notifications
         */

        public static function send_visit_pushover_notifications( $visit_id , $affiliate_id ) {
            $data     = array () ;
            $pushover = FS_Affiliates_Module_Instances::get_module_by_id( 'pushover_notifications' ) ;

            $data[ 'title' ]   = stripslashes( $pushover->new_visit_subject ) ;
            $data[ 'message' ] = stripslashes( $pushover->new_visit_message ) ;
            $data[ 'sound' ]   = $pushover->sound_notification ;
            $data[ 'token' ]   = $pushover->api_key ;

            if ( in_array( 'visit' , $pushover->admin_notifications ) ) {
                $data[ 'user' ]   = $pushover->admin_user_key ;
                $data[ 'device' ] = $pushover->device_name ;
                self::send_notifications( $data ) ;
            }

            if ( $pushover->allow_affiliates == 'yes' && in_array( 'referral' , $pushover->affiliate_notifications ) ) {
                $affiliate = new FS_Affiliates_Data( $affiliate_id ) ;
                if ( $affiliate->visit_pushover == 'yes' ) {
                    $data[ 'user' ]   = $affiliate->pushover_key ;
                    $data[ 'device' ] = $affiliate->device_name ;
                    self::send_notifications( $data ) ;
                }
            }
        }

        /*
         * send payout pushover notifications
         */

        public static function send_payout_pushover_notifications( $payout_id , $affiliate_id ) {
            $data     = array () ;
            $pushover = FS_Affiliates_Module_Instances::get_module_by_id( 'pushover_notifications' ) ;
            
            $data[ 'title' ]   = stripslashes( $pushover->new_payout_subject ) ;
            $data[ 'message' ] = stripslashes( $pushover->new_payout_message ) ;
            $data[ 'sound' ]   = $pushover->sound_notification ;
            $data[ 'token' ]   = $pushover->api_key ;

            if ( in_array( 'payout' , $pushover->admin_notifications ) ) {
                $data[ 'user' ]   = $pushover->admin_user_key ;
                $data[ 'device' ] = $pushover->device_name ;
                self::send_notifications( $data ) ;
            }

            if ( $pushover->allow_affiliates == 'yes' && in_array( 'referral' , $pushover->affiliate_notifications ) ) {
                $affiliate = new FS_Affiliates_Data( $affiliate_id ) ;
                if ( $affiliate->payout_pushover == 'yes' ) {
                    $data[ 'user' ]   = $affiliate->pushover_key ;
                    $data[ 'device' ] = $affiliate->device_name ;
                    self::send_notifications( $data ) ;
                }
            }
        }

    }

}
    
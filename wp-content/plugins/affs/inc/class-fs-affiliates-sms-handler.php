<?php

/*
 *  SMS Handler
 */
if ( !defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( !class_exists( 'FS_Affiliates_SMS_Handler' ) ) {

    /**
     * FS_Affiliates_SMS_Handler Class.
     */
    class FS_Affiliates_SMS_Handler {
        /*
         * Send SMS
         */

        public static function send_sms( $to , $message ) {
            $sms_module_object = FS_Affiliates_Module_Instances::get_module_by_id( 'sms' ) ;

            switch ( $sms_module_object->api_method ) {
                case '2':
                    self::nexmo_sms( $to , $message , $sms_module_object ) ;
                    break ;
                default :
                    self::twilio_sms( $to , $message , $sms_module_object ) ;
                    break ;
            }
        }

        /*
         * Send SMS via twilio
         */

        public static function twilio_sms( $to , $message , $sms_module_object ) {
            if ( !class_exists( 'Services_Twilio' ) ) {
                include_once FS_AFFILIATES_PLUGIN_PATH . '/inc/lib/SMS/Twilio.php' ;
            }

            $client = new Services_Twilio( $sms_module_object->twilio_account_sid , $sms_module_object->twilio_account_auth_token ) ;

            $response = $client->account->messages->sendMessage( $sms_module_object->from_number , $to , $message ) ;
        }

        /*
         * Send SMS via Nexmo
         */

        public static function nexmo_sms( $to , $message , $sms_module_object ) {
            if ( !class_exists( 'NexmoMessage' ) ) {
                include_once FS_AFFILIATES_PLUGIN_PATH . '/inc/lib/SMS/NexmoMessage.php' ;
            }

            $NexmoObj      = new NexmoMessage( $sms_module_object->nexmo_key , $sms_module_object->nexmo_secret ) ;
            $message_count = strlen( $message ) ;
            for ( $i = 0 ; $i <= $message_count ; ) {
                $sub_str  = substr( $message , $i , 160 ) ;
                $Response = $NexmoObj->sendText( $to , $sms_module_object->from_number , $sub_str ) ;
                $i        += 160 ;
            }
        }

    }

}
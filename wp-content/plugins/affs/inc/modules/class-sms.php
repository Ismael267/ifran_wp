<?php

/**
 * SMS
 */
if ( !defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( !class_exists( 'FS_Affiliates_SMS_Module' ) ) {

    /**
     * Class FS_Affiliates_SMS_Module
     */
    class FS_Affiliates_SMS_Module extends FS_Affiliates_Modules {
        /*
         * Data
         */

        protected $data = array (
            'enabled'                   => 'no' ,
            'api_method'                => '1' ,
            'twilio_account_sid'        => '' ,
            'twilio_account_auth_token' => '' ,
            'from_number'               => '' ,
            'admin_number'              => '' ,
            'nexmo_key'                 => '' ,
            'nexmo_secret'              => ''
                ) ;

        /**
         * Class Constructor
         */
        public function __construct() {
            $this->id    = 'sms' ;
            $this->title = __( 'SMS' , FS_AFFILIATES_LOCALE ) ;

            parent::__construct() ;
        }

        /*
         * Get settings link
         */

        public function settings_link() {
            return add_query_arg( array ( 'page' => 'fs_affiliates' , 'tab' => 'modules' , 'section' => $this->id ) , admin_url( 'admin.php' ) ) ;
        }

        /*
         * Get settings options array
         */

        public function settings_options_array() {
            return array (
                array (
                    'type'  => 'title' ,
                    'title' => __( 'SMS Settings' , FS_AFFILIATES_LOCALE ) ,
                    'id'    => 'sms_options' ,
                ) ,
                array (
                    'title'   => __( 'From Number' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->plugin_slug . '_' . $this->id . '_from_number' ,
                    'desc'    => __( 'The Number from which the SMS should be Sent.' , FS_AFFILIATES_LOCALE ) ,
                    'type'    => 'text' ,
                    'default' => '' ,
                ) ,
                array (
                    'title'   => __( 'Admin Mobile Number' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->plugin_slug . '_' . $this->id . '_admin_number' ,
                    'desc'    => __( 'The Mobile Number should be entered with Country Code.' , FS_AFFILIATES_LOCALE ) ,
                    'type'    => 'text' ,
                    'default' => '' ,
                ) ,
                array (
                    'title'   => __( 'SMS API' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->plugin_slug . '_' . $this->id . '_api_method' ,
                    'type'    => 'select' ,
                    'class'   => 'fs_affiliates_sms_module_api_method' ,
                    'default' => '1' ,
                    'options' => array (
                        '1' => __( 'Twilio' , FS_AFFILIATES_LOCALE ) ,
                        '2' => __( 'Nexmo' , FS_AFFILIATES_LOCALE ) ,
                    ) ,
                ) ,
                array (
                    'title'   => __( 'Twilio Account SID' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->plugin_slug . '_' . $this->id . '_twilio_account_sid' ,
                    'type'    => 'text' ,
                    'class'   => 'fs_affiliates_twilio_account_method' ,
                    'default' => '' ,
                ) ,
                array (
                    'title'   => __( 'Twilio Account Auth Token' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->plugin_slug . '_' . $this->id . '_twilio_account_auth_token' ,
                    'type'    => 'text' ,
                    'class'   => 'fs_affiliates_twilio_account_method' ,
                    'default' => '' ,
                ) ,
                array (
                    'title'   => __( 'Nexmo Key' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->plugin_slug . '_' . $this->id . '_nexmo_key' ,
                    'type'    => 'text' ,
                    'class'   => 'fs_affiliates_nexmo_account_method' ,
                    'default' => '' ,
                ) ,
                array (
                    'title'   => __( 'Nexmo Secret' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->plugin_slug . '_' . $this->id . '_nexmo_secret' ,
                    'type'    => 'text' ,
                    'class'   => 'fs_affiliates_nexmo_account_method' ,
                    'default' => '' ,
                ) ,
                array (
                    'type' => 'sectionend' ,
                    'id'   => 'sms_options' ,
                ) ,
                    ) ;
        }

        /*
         * Action
         */

        public function actions() {
            add_filter( 'fs_affiliates_sms_module_enable' , array ( $this , 'sms_module_enable' ) , 10 , 1 ) ;
        }

        /*
         * Check SMS Module enabled
         */

        public function sms_module_enable( $bool ) {
            return true ;
        }

    }

}
    
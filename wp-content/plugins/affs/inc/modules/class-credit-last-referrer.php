<?php

/**
 * Credit Last Referrer
 */
if ( !defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( !class_exists( 'FS_Affiliates_Credit_Last_Referrer' ) ) {

    /**
     * Class FS_Affiliates_Credit_Last_Referrer
     */
    class FS_Affiliates_Credit_Last_Referrer extends FS_Affiliates_Modules {

        /**
         * Class Constructor
         */
        public function __construct() {
            $this->id    = 'credit_last_referrer' ;
            $this->title = __( 'Credit Last Referrer' , FS_AFFILIATES_LOCALE ) ;

            parent::__construct() ;
        }

        /*
         * Action
         */

        public function actions() {
            add_filter( 'fs_affiliates_check_if_last_referral' , array ( $this , 'check_if_last_referral_enabled' ) , 10 , 1 ) ;
        }

        /*
         * Check If Last referral
         */

        public function check_if_last_referral_enabled( $bool ) {
            return true ;
        }

    }

}
    
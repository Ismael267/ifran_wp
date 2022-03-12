<?php

/**
 * Commission for Own Links
 */
if ( !defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( !class_exists( 'FS_Affiliates_Commission_Links' ) ) {

    /**
     * Class FS_Affiliates_Commission_Links
     */
    class FS_Affiliates_Commission_Links extends FS_Affiliates_Modules {

        /**
         * Class Constructor
         */
        public function __construct() {
            $this->id    = 'commission_links' ;
            $this->title = __( 'Commission for Own Links' , FS_AFFILIATES_LOCALE ) ;

            parent::__construct() ;
        }

        /*
         * Action
         */

        public function actions() {
            add_filter( 'fs_affiliates_is_restricted_own_commission' , array ( $this , 'is_restricted_own_commission' ) , 10 , 1 ) ;
        }

        /*
         * Check If Product is allowed to award own commission
         */

        public function is_restricted_own_commission( $bool ) {
            return true ;
        }

    }

}
    
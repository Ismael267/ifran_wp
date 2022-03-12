<?php

/**
 * Affiliate Level Product Commission
 */
if ( !defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( !class_exists( 'FS_Affiliates_Level_Product_Commission' ) ) {

    /**
     * Class FS_Affiliates_Level_Product_Commission
     */
    class FS_Affiliates_Level_Product_Commission extends FS_Affiliates_Modules {

        /**
         * Class Constructor
         */
        public function __construct() {
            $this->id    = 'affiliate_level_product_commission' ;
            $this->title = __( 'Affiliate Level Product Commission' , FS_AFFILIATES_LOCALE ) ;

            parent::__construct() ;
        }

        /*
         * Plugin enabled
         */

        public function is_plugin_enabled() {
            $woocommerce = FS_Affiliates_Integration_Instances::get_integration_by_id( 'woocommerce' ) ;

            if ( $woocommerce->is_enabled() )
                return true ;

            return false ;
        }

        /*
         * Action
         */

        public function actions() {
            add_filter( 'fs_affiliates_is_affiliate_level_product_commission' , array ( $this , 'is_affiliate_level_product_commission_enabled' ) , 10 , 1 ) ;
        }

        /*
         * Check If Affiliate Level Product Commission is Enabled
         */

        public function is_affiliate_level_product_commission_enabled( $bool ) {
            return true ;
        }

    }

}

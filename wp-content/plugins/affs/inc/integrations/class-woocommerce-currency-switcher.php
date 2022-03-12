<?php

/**
 * WooCommerce Currency Switcher.
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'FS_Woocommerce_Currency_Switcher' ) ) {

    /**
     * Class FS_Woocommerce_Currency_Switcher
     */
    class FS_Woocommerce_Currency_Switcher extends FS_Affiliates_Integrations {

        /**
         * Class Constructor
         */
        public function __construct() {
            $this->id    = 'woocommerce_currency_switcher' ;
            $this->title = __( 'WooCommerce Currency Switcher' , FS_AFFILIATES_LOCALE ) ;

            parent::__construct() ;
        }

        /*
         * is enabled
         */

        public function is_enabled() {

            return $this->is_plugin_enabled() && 'yes' === $this->enabled ;
        }

        /*
         * Actions
         */

        public function actions() {
            add_filter( 'fs_affiliates_product_regular_price' , array( $this , 'alter_product_regular_price' ) , 10 , 3 ) ;
        }

        /*
         * Plugin enabled
         */

        public function is_plugin_enabled() {
            $woocommerce = FS_Affiliates_Integration_Instances::get_integration_by_id( 'woocommerce' ) ;
            if ( class_exists( 'WOOCS' ) && $woocommerce->is_enabled() ) {
                return true ;
            }

            return false ;
        }

        /**
         * May be alter the product regular price based on currency.
         * 
         * @return Float
         */
        public function alter_product_regular_price( $price , $item , $order ) {
            global $WOOCS ;
            if ( is_object( $WOOCS ) && $WOOCS->is_multiple_allowed ) {
                $currrent = $WOOCS->current_currency ;
                if ( $currrent != $WOOCS->default_currency ) {
                    $currencies = $WOOCS->get_currencies() ;
                    $rate       = $currencies[ $currrent ][ 'rate' ] ;
                    $price      = $price / $rate ;
                }
            }

            return $price ;
        }

    }

}
    

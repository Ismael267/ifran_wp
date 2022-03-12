<?php

/**
 * SUMO Payment Plans
 */
if ( !defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( !class_exists( 'FS_Affiliates_SUMO_Payment_Plans' ) ) {

    /**
     * Class FS_Affiliates_SUMO_Payment_Plans
     */
    class FS_Affiliates_SUMO_Payment_Plans extends FS_Affiliates_Integrations {

        /**
         * Class Constructor
         */
        public function __construct() {
            $this->id    = 'sumo_payment_plans' ;
            $this->title = __( 'SUMO Payment Plans' , FS_AFFILIATES_LOCALE ) ;

            parent::__construct() ;
        }

        /*
         * is enabled
         */

        public function is_enabled() {
            return $this->is_plugin_enabled() && 'yes' === $this->enabled ;
        }

        /*
         * Plugin enabled
         */

        public function is_plugin_enabled() {
            return class_exists( 'SUMOPaymentPlans' ) ;
        }

        /*
         * Actions
         */

        public function actions() {
            add_filter( 'fs_affiliates_create_referral_by_parent' , array ( $this , 'create_referral_for_final_payment' ) , 10 , 2 ) ;
        }

        /*
         * Frontend Actions
         */

        public function frontend_action() {
            add_filter( 'fs_affiliate_regular_price_for_purchase' , array ( $this , 'set_commission_price' ) , 10 , 3 ) ;
        }

        public function create_referral_for_payment( $bool , $order_id ) {
            if ( SUMO_PP_Cart_Manager::cart_has_payment_items() ) {
                return true ;
            }
            return _sumo_pp_is_payment_order( $order_id ) ;
        }

        public function set_commission_price( $price , $order_id , $order_item ) {
            $order = _sumo_pp_get_order( $order_id ) ;

            if ( !is_admin() && isset( WC()->cart->cart_contents ) && $order && !empty( $order_item[ 'product_id' ] ) && $order->is_parent() ) {
                $item_id = $order_item[ 'product_id' ] ;

                if ( is_numeric( $order_item[ 'variation_id' ] ) && $order_item[ 'variation_id' ] ) {
                    $item_id = $order_item[ 'variation_id' ] ;
                }
                foreach ( WC()->cart->cart_contents as $cart_item_key => $cart_item ) {
                    if ( !empty( $cart_item[ 'sumopaymentplans' ][ 'product_id' ] ) && $cart_item[ 'sumopaymentplans' ][ 'product_id' ] == $item_id ) {
                        $price = $cart_item[ 'sumopaymentplans' ][ 'payment_product_props' ][ 'product_price' ] ;
                    }
                }
            }

            return $price ;
        }

    }

}
    
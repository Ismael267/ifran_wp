<?php

/**
 * Recover Abandoned Cart
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'FS_Affiliates_RAC' ) ) {

    /**
     * Class FS_Affiliates_RAC
     */
    class FS_Affiliates_RAC extends FS_Affiliates_Integrations {

        /**
         * Class Constructor
         */
        public function __construct() {
            $this->id    = 'recover_abandoned_cart' ;
            $this->title = __( 'Recover Abandoned Cart' , FS_AFFILIATES_LOCALE ) ;

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
            $woocommerce = FS_Affiliates_Integration_Instances::get_integration_by_id( 'woocommerce' ) ;

            return class_exists( 'RecoverAbandonCart' ) && $woocommerce->is_enabled() ;
        }

        /*
         * Both Front End and Back End Action
         */

        public function actions() {

            add_filter( 'fs_affiliates_order_visit_id' , array( $this , 'get_visit_id_from_recover_link' ) , 2 , 3 ) ;
            add_filter( 'fs_affiliates_order_campaign_id' , array( $this , 'get_campaign_id_from_recover_link' ) , 2 , 3 ) ;
            add_filter( 'fs_affiliates_order_affiliate_id' , array( $this , 'get_affiliate_id_from_recover_link' ) , 2 , 3 ) ;
            add_filter( 'fs_affiliates_order_commission' , array( $this , 'get_commission_value_from_recover_link' ) , 2 , 4 ) ;
            add_filter( 'fs_affiliates_display_checkout_affiliate' , array( $this , 'check_cart_object_cookies' ) , 10 , 1 ) ;
            add_filter( 'fs_affiliates_display_checkout_referral_code' , array( $this , 'check_cart_object_cookies' ) , 10 , 1 ) ;
        }

        /*
         * Check cart object affiliate cookies set or not
         */

        public function check_cart_object_cookies( $bool ) {

            if ( $this->get_cart_object_cookies() )
                return false ;

            return $bool ;
        }

        /*
         * Get visit id from recovered link
         */

        public function get_visit_id_from_recover_link( $visit_id , $order_id , $order ) {
            $saved_cookies = $this->get_cart_object_cookies() ;

            if ( ! $saved_cookies )
                return $visit_id ;

            if ( empty( $saved_cookies[ 'visit_id' ] ) )
                return $visit_id ;

            return $saved_cookies[ 'visit_id' ] ;
        }

        /*
         * Get campaign id from recovered link
         */

        public function get_campaign_id_from_recover_link( $campaign_id , $order_id , $order ) {
            $saved_cookies = $this->get_cart_object_cookies() ;

            if ( ! $saved_cookies )
                return $campaign_id ;

            if ( empty( $saved_cookies[ 'campaign_id' ] ) )
                return $campaign_id ;

            return $saved_cookies[ 'campaign_id' ] ;
        }

        /*
         * Get affiliate id from recovered link
         */

        public function get_affiliate_id_from_recover_link( $affiliate_id , $order_id , $order ) {
            $saved_cookies = $this->get_cart_object_cookies() ;

            if ( ! $saved_cookies )
                return $affiliate_id ;

            $product_link = FS_Affiliates_Module_Instances::get_module_by_id( 'product_based_affiliate_link' ) ;
            if ( ! empty( $saved_cookies[ 'product_id' ] ) && $product_link->is_enabled() ) {
                $UnserializedArray    = unserialize( $saved_cookies[ 'product_id' ] ) ;
                $product_affiliate_id = isset( $UnserializedArray[ 'affiliateid' ] ) ? $UnserializedArray[ 'affiliateid' ] : 0 ;
                if ( ! empty( $product_affiliate_id ) )
                    return $product_affiliate_id ;
            }

            if ( empty( $saved_cookies[ 'affiliate_id' ] ) )
                return $affiliate_id ;

            return $saved_cookies[ 'affiliate_id' ] ;
        }

        /*
         * Get Commission value from recovered link
         */

        public function get_commission_value_from_recover_link( $commission_value , $order_id , $order , $affiliate_id ) {
            if ( ! empty( $commission_value ) )
                return $commission_value ;

            $saved_cookies = $this->get_cart_object_cookies() ;

            if ( ! $saved_cookies )
                return $commission_value ;

            $product_link = FS_Affiliates_Module_Instances::get_module_by_id( 'product_based_affiliate_link' ) ;
            if ( ! empty( $saved_cookies[ 'product_id' ] ) && $product_link->is_enabled() ) {
                $UnserializedArray = unserialize( $saved_cookies[ 'product_id' ] ) ;
                $affiliate_id      = isset( $UnserializedArray[ 'affiliateid' ] ) ? $UnserializedArray[ 'affiliateid' ] : 0 ;
                $product_id        = isset( $UnserializedArray[ 'productid' ] ) ? $UnserializedArray[ 'productid' ] : 0 ;
                if ( ! empty( $affiliate_id ) && ! empty( $product_id ) )
                    return $product_link->award_commission_for_product_based_affiliate( $order_id , $affiliate_id , $product_id ) ;
            }

            if ( empty( $saved_cookies[ 'affiliate_id' ] ) )
                return $commission_value ;

            return FS_Affiliates_WC_Commission::award_commission_for_product_purchase( $order_id , $saved_cookies[ 'affiliate_id' ] ) ;
        }

        /*
         * Get cart object cookies
         */

        public function get_cart_object_cookies() {
            if ( ! isset( $_COOKIE[ 'rac_cart_id' ] ) )
                return false ;

            $cart_obj = fp_rac_create_cart_list_obj( $_COOKIE[ 'rac_cart_id' ] ) ;

            if ( $cart_obj->cart_status == 'rac-cart-recovered' || ! empty( $cart_obj->placed_order ) )
                return false ;

            $saved_cookies = $cart_obj->sumo_affiliates_pro ;

            if ( ! fs_affiliates_check_is_array( $saved_cookies ) )
                return false ;

            return $saved_cookies ;
        }

    }

}
    
<?php

/**
 * SUMO Pre-Orders
 */
if ( !defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( !class_exists( 'FS_Affiliates_SUMO_Preorders' ) ) {

    /**
     * Class FS_Affiliates_SUMO_Preorders
     */
    class FS_Affiliates_SUMO_Preorders extends FS_Affiliates_Integrations {
        /*
         * Data
         */

        protected $data = array (
            'enabled'            => 'no' ,
            'awarded_commission' => '1' ,
                ) ;

        /**
         * Class Constructor
         */
        public function __construct() {
            $this->id    = 'sumo_preorders' ;
            $this->title = __( 'SUMO Pre-Orders' , FS_AFFILIATES_LOCALE ) ;

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
            return class_exists( 'SUMOPreOrders' ) ;
        }

        /*
         * Get settings link
         */

        public function settings_link() {
            return add_query_arg( array ( 'page' => 'fs_affiliates' , 'tab' => 'integration' , 'section' => $this->id ) , admin_url( 'admin.php' ) ) ;
        }

        /*
         * Get settings options array
         */

        public function settings_options_array() {
            return array (
                array (
                    'type'  => 'title' ,
                    'title' => __( 'SUMO Pre-Orders' , FS_AFFILIATES_LOCALE ) ,
                    'id'    => 'sumo_preorders_options' ,
                ) ,
                array (
                    'title'   => __( 'Affiliate Commission will be awarded for' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->plugin_slug . '_' . $this->id . '_awarded_commission' ,
                    'type'    => 'multiselect' ,
                    'class'   => 'fs_affiliates_select2' ,
                    'default' => array ( '1' , '2' ) ,
                    'options' => array (
                        '1' => __( 'Pay On Release' , FS_AFFILIATES_LOCALE ) ,
                        '2' => __( 'Pay In Front' , FS_AFFILIATES_LOCALE ) ,
                    ) ,
                ) ,
                array (
                    'type' => 'sectionend' ,
                    'id'   => 'sumo_preorders_options' ,
                ) ,
                    ) ;
        }

        /*
         * Admin Actions
         */

        public function admin_action() {
            
        }

        /*
         * Frontend Actions
         */

        public function frontend_action() {
            
        }

        /*
         * Actions
         */

        public function actions() {
            add_filter( 'fs_affiliates_create_new_referral' , array ( $this , 'reward_commission_based_on_preorders_type' ) , 10 , 3 ) ;
        }

        public function reward_commission_based_on_preorders_type( $bool , $OrderId , $AffiliateId ) {

            if (
                    !is_admin() &&
                    is_callable( array ( 'SUMO_WCPO_Cart_Manager' , 'cart_contains_preorder' ) ) &&
                    is_callable( array ( 'SUMO_WCPO_Cart_Manager' , 'get_preorder_products_from_cart' ) ) &&
                    SUMO_WCPO_Cart_Manager::cart_contains_preorder()
            ) {
                $preorder_products = SUMO_WCPO_Cart_Manager::get_preorder_products_from_cart() ;

                foreach ( $preorder_products as $product_id => $props ) {
                    if ( !empty( $props[ 'preorder_method' ] ) ) {
                        if ( in_array( '1' , $this->awarded_commission ) && in_array( '2' , $this->awarded_commission ) ) {
                            if ( 'pay-later' === $props[ 'preorder_method' ] ) {
                                add_post_meta( $OrderId , "{$this->plugin_slug }_is_pay_on_release" , 'yes' ) ;
                                return false ;
                            } else if ( 'pay-infront' === $props[ 'preorder_method' ] ) {
                                return true ;
                            }
                        }
                        if ( in_array( '1' , $this->awarded_commission ) ) {
                            if ( 'pay-later' === $props[ 'preorder_method' ] ) {
                                add_post_meta( $OrderId , "{$this->plugin_slug }_is_pay_on_release" , 'yes' ) ;
                                return false ;
                            } else if ( 'pay-infront' === $props[ 'preorder_method' ] ) {
                                return false ;
                            }
                        }
                        if ( in_array( '2' , $this->awarded_commission ) ) {
                            if ( 'pay-infront' === $props[ 'preorder_method' ] ) {
                                return true ;
                            } else if ( 'pay-later' === $props[ 'preorder_method' ] ) {
                                return false ;
                            }
                        }
                    }
                }
            } elseif (
                    'yes' === get_post_meta( $OrderId , 'is_sumo_wcpo_order' , true ) &&
                    'yes' === get_post_meta( $OrderId , "{$this->plugin_slug }_is_pay_on_release" , true ) &&
                    function_exists( '_sumo_wcpo' ) &&
                    is_callable( array ( _sumo_wcpo()->query , 'get' ) )
            ) {
                $preorders = _sumo_wcpo()->query->get( array (
                    'type'       => 'sumo_wcpo_preorders' ,
                    'status'     => array ( _sumo_wcpo()->prefix . 'pending' , _sumo_wcpo()->prefix . 'progress' ) ,
                    'meta_key'   => '_preordered_order_id' ,
                    'meta_value' => $OrderId ,
                        ) ) ;

                foreach ( $preorders as $preorder_id ) {
                    $preorder = _sumo_wcpo_get_preorder( $preorder_id ) ;

                    if ( 'pay-later' === $preorder->get_charge_type() ) {
                        return true ;
                    }
                }
            }
            return $bool ;
        }

    }

}
    
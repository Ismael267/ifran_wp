<?php

/**
 * Woocommerce Subscriptions
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'FS_Affiliates_WC_Subscriptions' ) ) {

    /**
     * Class FS_Affiliates_WC_Subscriptions
     */
    class FS_Affiliates_WC_Subscriptions extends FS_Affiliates_Integrations {
        /*
         * Data
         */

        protected $data = array (
            'enabled'                               => 'no' ,
            'awarded_commission'                    => '1' ,
            'awarded_commission_for_fixed_renewals' => '' ,
                ) ;

        /**
         * Class Constructor
         */
        public function __construct() {
            $this->id    = 'wc_subscriptions' ;
            $this->title = __( 'WooCommerce Subscriptions' , FS_AFFILIATES_LOCALE ) ;

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
            return class_exists( 'WC_Subscriptions' ) ;
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
            $return = array (
                array (
                    'type'  => 'title' ,
                    'title' => __( 'WC Subscriptions' , FS_AFFILIATES_LOCALE ) ,
                    'id'    => 'wc_subscriptions_options' ,
                ) ,
                array (
                    'title'   => __( 'Affiliate Commission will be awarded for' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->plugin_slug . '_' . $this->id . '_awarded_commission' ,
                    'type'    => 'select' ,
                    'default' => '1' ,
                    'options' => array (
                        '1' => __( 'Initial Order' , FS_AFFILIATES_LOCALE ) ,
                        '2' => __( 'Initial Order and All Renewals' , FS_AFFILIATES_LOCALE ) ,
                    ) ,
                ) ,
                array (
                    'title'             => __( 'Award Commission for Fixed Number of Renewals' , FS_AFFILIATES_LOCALE ) ,
                    'id'                => $this->plugin_slug . '_' . $this->id . '_awarded_commission_for_fixed_renewals' ,
                    'type'              => 'number' ,
                    'default'           => '' ,
                    'custom_attributes' => array (
                        'min' => '1'
                    ) ,
                ) ,
                    ) ;

            return array_merge( $return , array ( array (
                    'type' => 'sectionend' ,
                    'id'   => 'wc_subscriptions_options' ,
                ) ) ) ;
        }

        /*
         * Admin Actions
         */

        public function actions() {
            if ( '2' === $this->awarded_commission ) {
                add_filter( 'wcs_renewal_order_created' , array ( $this , 'reward_renewal_order_commission' ) , 10 , 2 ) ;
            }

            add_filter( 'wcs_renewal_order_meta_query' , array ( $this , 'prevent_reward_in_renewal_order' ) , 10 , 3 ) ;
        }

        public function can_award_commission_to_renewal( $subscription ) {
            if ( '1' === $this->awarded_commission ) {
                return false ;
            }

            if (
                    '2' === $this->awarded_commission &&
                    'shop_subscription' === $subscription->get_type()
            ) {
                if (
                        $this->awarded_commission_for_fixed_renewals > 0 &&
                        $subscription->get_completed_payment_count() > 1 && //Since it is considered the payment count even for parent order and so we are doing like this
                        ($subscription->get_completed_payment_count() - 1) >= $this->awarded_commission_for_fixed_renewals
                ) {
                    return false ;
                }
                return true ;
            }
            return false ;
        }

        public function prevent_reward_in_renewal_order( $meta_query , $to_order , $from_order ) {
            if ( ! $this->can_award_commission_to_renewal( $from_order ) ) {
                $meta_query .= " AND `meta_key` NOT IN ('fs_visit_in_order', 'fs_campaign_in_order', 'fs_affiliate_in_order', 'fs_commission_to_be_awarded_in_order') " ;
            }
            return $meta_query ;
        }

        public function reward_renewal_order_commission( $renewal_order , $subscription ) {

            if ( ! $this->can_award_commission_to_renewal( $subscription ) ) {
                return $renewal_order ;
            }

            if ( is_callable( array ( $renewal_order , 'get_id' ) ) ) {
                $renewal_order_id = $renewal_order->get_id() ;
            } else {
                $renewal_order_id = $renewal_order->id ;
            }

            $parent_order_id = 0 ;
            if ( is_callable( array ( $subscription , 'get_parent_id' ) ) ) {
                $parent_order_id = $subscription->get_parent_id() ;
            }

            $parent_affiliate  = get_post_meta( $parent_order_id , 'fs_affiliate_in_order' , true ) ;
            $parent_visit      = get_post_meta( $parent_order_id , 'fs_visit_in_order' , true ) ;
            $parent_campaign   = get_post_meta( $parent_order_id , 'fs_campaign_in_order' , true ) ;
            $parent_commission = get_post_meta( $parent_order_id , 'fs_commission_to_be_awarded_in_order' , true ) ;

            if ( $parent_order_id ) {
                update_post_meta( $renewal_order_id , 'fs_visit_in_order' , $parent_visit ) ;
                update_post_meta( $renewal_order_id , 'fs_campaign_in_order' , $parent_campaign ) ;
                update_post_meta( $renewal_order_id , 'fs_affiliate_in_order' , $parent_affiliate ) ;
                update_post_meta( $renewal_order_id , 'fs_commission_to_be_awarded_in_order' , $parent_commission ) ;
            }
            return $renewal_order ;
        }

    }

}
    
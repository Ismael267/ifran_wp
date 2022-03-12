<?php

/**
 * SUMO Subscriptions
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'FS_Affiliates_SUMO_Subscriptions' ) ) {

    /**
     * Class FS_Affiliates_SUMO_Subscriptions
     */
    class FS_Affiliates_SUMO_Subscriptions extends FS_Affiliates_Integrations {
        /*
         * Data
         */

        protected $data = array (
            'enabled'                               => 'no' ,
            'awarded_commission'                    => '1' ,
            'awarded_commission_for_fixed_renewals' => '' ,
            'charge_affiliate_fee_by_recurring'     => 'no' ,
                ) ;

        /**
         * Class Constructor
         */
        public function __construct() {
            $this->id    = 'sumo_subscriptions' ;
            $this->title = __( 'SUMO Subscriptions' , FS_AFFILIATES_LOCALE ) ;

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
            return class_exists( 'sumosubscriptions' ) ;
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
                    'title' => __( 'SUMO Subscriptions' , FS_AFFILIATES_LOCALE ) ,
                    'id'    => 'sumo_subscriptions_options' ,
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

            if ( FS_Affiliates_Module_Instances::get_module_by_id( 'affiliate_fee' )->is_enabled() ) {
                $return = array_merge( $return , array ( array (
                        'title'   => __( 'Enable Recurring Charging for Affiliate Fee' , FS_AFFILIATES_LOCALE ) ,
                        'id'      => $this->plugin_slug . '_' . $this->id . '_charge_affiliate_fee_by_recurring' ,
                        'type'    => 'checkbox' ,
                        'default' => 'no' ,
                    ) ) ) ;
            }

            return array_merge( $return , array ( array (
                    'type' => 'sectionend' ,
                    'id'   => 'sumo_subscriptions_options' ,
                ) ) ) ;
        }

        /*
         * Admin Actions 
         */

        public function actions() {
            if ( '2' === $this->awarded_commission ) {
                add_action( 'sumosubscriptions_renewal_order_is_created' , array ( $this , 'reward_renewal_order_commission' ) , 10 , 3 ) ;
            }
        }

        public function can_award_commission_to_renewal( $subscription_id ) {
            if ( '1' === $this->awarded_commission ) {
                return false ;
            }

            if ( '2' === $this->awarded_commission ) {
                $payment_completed_count = sumosubs_get_renewed_count( $subscription_id ) ;

                if (
                        $this->awarded_commission_for_fixed_renewals > 0 &&
                        $payment_completed_count >= $this->awarded_commission_for_fixed_renewals
                ) {
                    return false ;
                }
                return true ;
            }
            return false ;
        }

        public function reward_renewal_order_commission( $parent_order_id , $renewal_order_id , $subscription_id ) {
            if ( ! $this->can_award_commission_to_renewal( $subscription_id ) ) {
                return ;
            }
            
            $order_obj         = wc_get_order( $renewal_order_id ) ;
            $limit_for_referral = apply_filters( 'fs_affiliates_is_restricted_referral' , true , $order_obj->get_user_id() , $order_obj ) ;

            if ( ! $limit_for_referral )
                return ;
            
            $affiliate_id = get_post_meta( $parent_order_id , 'fs_affiliate_in_order' , true ) ;

            if ( empty( $affiliate_id ) )
                return ;

            if ( ! apply_filters( 'fs_affiliates_commission_from_same_ip' , true , $renewal_order_id , $affiliate_id ) )
                return ;
            
            if ( ! FS_Affiliates_WC_Commission::is_restricted_own_commission( $affiliate_id , $order_obj->get_user_id() ) )
                return ;    
            
            // Commission Calculation.
            $commission = FS_Affiliates_WC_Commission::award_commission_for_product_purchase( $renewal_order_id , $affiliate_id ) ;
            $parent_visit      = get_post_meta( $parent_order_id , 'fs_visit_in_order' , true ) ;
            $parent_campaign   = get_post_meta( $parent_order_id , 'fs_campaign_in_order' , true ) ;
            
            update_post_meta( $renewal_order_id , 'fs_visit_in_order' , $parent_visit ) ;
            update_post_meta( $renewal_order_id , 'fs_campaign_in_order' , $parent_campaign ) ;
            update_post_meta( $renewal_order_id , 'fs_affiliate_in_order' , $affiliate_id ) ;
            update_post_meta( $renewal_order_id , 'fs_commission_to_be_awarded_in_order' , $commission ) ;
        }

    }

}
    
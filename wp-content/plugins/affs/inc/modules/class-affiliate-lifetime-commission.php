<?php

/**
 * Affiliate Wallet
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'FS_Affiliates_Lifetime_Commission_Module' ) ) {

    /**
     * Class FS_Affiliates_Lifetime_Commission_Module
     */
    class FS_Affiliates_Lifetime_Commission_Module extends FS_Affiliates_Modules {
        /*
         * Data
         */

        protected $data = array(
            'enabled'                   => 'no' ,
            'allowed_affiliates_method' => '1' ,
            'selected_affiliates'       => array() ,
            'lifetime_commission_rate'  => '1' ,
            'lifetime_commission_value' => '' ,
                ) ;

        /**
         * Class Constructor
         */
        public function __construct() {
            $this->id    = 'lifetime_commission' ;
            $this->title = __( 'Lifetime Commission' , FS_AFFILIATES_LOCALE ) ;

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
         * Get settings link
         */

        public function settings_link() {
            return add_query_arg( array( 'page' => 'fs_affiliates' , 'tab' => 'modules' , 'section' => $this->id ) , admin_url( 'admin.php' ) ) ;
        }

        /*
         * Both Front End and Back End Action
         */

        public function actions() {
            add_filter( 'fs_affiliates_order_commission' , array( $this , 'get_lifetime_commission_value' ) , 999 , 4 ) ;
            add_filter( 'fs_affiliates_order_affiliate_id' , array( $this , 'affiliate_id_for_lifetime_commission' ) , 10 , 3 ) ;
            add_filter( 'fs_affiliate_check_if_lifetime_commission_enabled' , array( $this , 'check_if_lifetime_commission_enabled' ) , 999 , 1 ) ;
        }

        /*
         * Get settings options array
         */

        public function settings_options_array() {
            return array(
                array(
                    'type'  => 'title' ,
                    'title' => __( 'Lifetime Commission' , FS_AFFILIATES_LOCALE ) ,
                    'id'    => 'fs_affiliates_lifetime_commission' ,
                ) ,
                array(
                    'title'   => __( 'Lifetime Commission can be Earned by' , FS_AFFILIATES_LOCALE ) ,
                    'desc'    => __( 'The Affiliates selected here will be eligible for earning Lifetime Affiliate Commission' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->plugin_slug . '_' . $this->id . '_allowed_affiliates_method' ,
                    'type'    => 'select' ,
                    'class'   => 'fs_affiliates_allowed_affiliates_method' ,
                    'default' => '1' ,
                    'options' => array(
                        '1' => __( 'All Affiliates' , FS_AFFILIATES_LOCALE ) ,
                        '2' => __( 'Selected Affiliates' , FS_AFFILIATES_LOCALE ) ,
                    ) ,
                ) ,
                array(
                    'title'     => __( 'Selected Affiliates' , FS_AFFILIATES_LOCALE ) ,
                    'id'        => $this->plugin_slug . '_' . $this->id . '_selected_affiliates' ,
                    'type'      => 'ajaxmultiselect' ,
                    'class'     => 'fs_affiliates_selected_affiliate' ,
                    'list_type' => 'affiliates' ,
                    'action'    => 'fs_affiliates_search' ,
                    'default'   => array() ,
                ) ,
                array(
                    'title'   => __( 'Commission Rate Type' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->plugin_slug . '_' . $this->id . '_lifetime_commission_rate' ,
                    'type'    => 'select' ,
                    'class'   => 'fs_affiliates_lifetime_commission_rate' ,
                    'default' => '1' ,
                    'options' => array(
                        '1' => __( 'Exisiting Commission' , FS_AFFILIATES_LOCALE ) ,
                        '2' => __( 'Fixed Commission' , FS_AFFILIATES_LOCALE ) ,
                        '3' => __( 'Percentage Commission' , FS_AFFILIATES_LOCALE ) ,
                    ) ,
                ) ,
                array(
                    'title'   => __( 'Commission Rate' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->plugin_slug . '_' . $this->id . '_lifetime_commission_value' ,
                    'type'    => 'price' ,
                    'class'   => 'fs_affiliates_lifetime_commission_value' ,
                    'default' => '' ,
                ) ,
                array(
                    'type' => 'sectionend' ,
                    'id'   => 'fs_affiliates_lifetime_commission' ,
                ) ,
                    ) ;
        }

        public function check_if_lifetime_commission_enabled( $bool ) {
            return true ;
        }

        /*
         * Get eligible affiliates
         */

        public function check_if_valid_affiliate( $affilate_id ) {

            if ( $this->allowed_affiliates_method == '2' ) {
                $eligible_affiliates = $this->selected_affiliates ;

                if ( fs_affiliates_check_is_array( $eligible_affiliates ) && ! in_array( $affilate_id , $eligible_affiliates ) ) {
                    return false ;
                }
            }

            return true ;
        }

        /*
         * Get Commission Value Form Lifetime Commission Module
         */

        public function get_lifetime_commission_value( $CommissionValue , $OrderId , $OrderObj , $AffiliateId ) {
            if ( isset( $_COOKIE[ 'fsaffiliateid' ] ) || ! empty( $CommissionValue ) )
                return $CommissionValue ;

            $CommissionValue = FS_Affiliates_WC_Commission::award_commission_for_product_purchase( $OrderId , $AffiliateId ) ;

            if ( $this->lifetime_commission_rate == '2' ) {
                $CommissionValue = empty( $this->lifetime_commission_value ) ? $CommissionValue : $this->lifetime_commission_value ;
            } elseif ( $this->lifetime_commission_rate == '3' ) {
                $CommissionValue = empty( $this->lifetime_commission_value ) ? $CommissionValue : ($CommissionValue / 100) * $this->lifetime_commission_value ;
            }

            return $CommissionValue ;
        }

        public function affiliate_id_for_lifetime_commission( $affilate_id , $order_id , $order_obj ) {
            if ( ! empty( $affilate_id ) )
                return $affilate_id ;

            $billing_email        = $order_obj->get_billing_email() ;
            $lifetime_affilate_id = get_affiliate_id_for_lifetime_commission( $billing_email ) ;
            if ( ! $this->check_if_valid_affiliate( $lifetime_affilate_id ) )
                return $affilate_id ;

            return $lifetime_affilate_id ;
        }

    }

}

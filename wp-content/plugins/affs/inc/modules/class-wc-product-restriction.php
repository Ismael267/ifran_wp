<?php

/**
 * WooCommerce Product Restriction
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'FS_Affiliates_WC_Product_Restriction' ) ) {

    /**
     * Class FS_Affiliates_WC_Product_Restriction
     */
    class FS_Affiliates_WC_Product_Restriction extends FS_Affiliates_Modules {
        /*
         * Data
         */

        protected $data = array(
            'enabled'             => 'no' ,
            'product_selection'   => 'all_products' ,
            'selected_products'   => array() ,
            'selected_categories' => array() ,
                ) ;

        /**
         * Class Constructor
         */
        public function __construct() {
            $this->id    = 'wc_product_restriction' ;
            $this->title = __( 'WooCommerce Product Restriction' , FS_AFFILIATES_LOCALE ) ;

            parent::__construct() ;
        }

        /*
         * Get settings link
         */

        public function settings_link() {
            return add_query_arg( array( 'page' => 'fs_affiliates' , 'tab' => 'modules' , 'section' => $this->id ) , admin_url( 'admin.php' ) ) ;
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
         * Get settings options array
         */

        public function settings_options_array() {
            return array(
                array(
                    'type'  => 'title' ,
                    'title' => __( 'WooCommerce Product Restriction' , FS_AFFILIATES_LOCALE ) ,
                    'id'    => 'wc_product_restriction_options' ,
                ) ,
                array(
                    'title'   => __( 'Products which are Eligible for Affiliate Commission' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->plugin_slug . '_' . $this->id . '_product_selection' ,
                    'type'    => 'select' ,
                    'default' => 'all_products' ,
                    'options' => array(
                        'all_products'        => __( 'All Products' , FS_AFFILIATES_LOCALE ) ,
                        'selected_products'   => __( 'Selected Products' , FS_AFFILIATES_LOCALE ) ,
                        'selected_categories' => __( 'Selected Categories' , FS_AFFILIATES_LOCALE ) ,
                        'no_product'          => __( 'No Products' , FS_AFFILIATES_LOCALE ) ,
                    ) ,
                    'desc'    => __( 'This option controls the products which are eligible for generating affiliate commissions.' , FS_AFFILIATES_LOCALE ) ,
                ) ,
                array(
                    'title'     => __( 'Selected Products' , FS_AFFILIATES_LOCALE ) ,
                    'desc'      => __( 'Products allowed for earning affiliate commission' , FS_AFFILIATES_LOCALE ) ,
                    'id'        => $this->plugin_slug . '_' . $this->id . '_selected_products' ,
                    'type'      => 'ajaxmultiselect' ,
                    'class'     => 'wc-product-search fs_affiliates_product_restrictions' ,
                    'list_type' => 'products' ,
                    'action'    => 'fs_affiliates_products_search' ,
                    'default'   => array() ,
                ) ,
                array(
                    'title'   => __( 'Selected Categories' , FS_AFFILIATES_LOCALE ) ,
                    'desc'    => __( 'Categories allowed for earning affiliate commission' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->plugin_slug . '_' . $this->id . '_selected_categories' ,
                    'type'    => 'multiselect' ,
                    'class'   => 'fs_affiliates_category_restrictions fs_affiliates_select2' ,
                    'default' => '' ,
                    'options' => fp_affiliates_get_categories()
                ) ,
                array(
                    'type' => 'sectionend' ,
                    'id'   => 'wc_product_restriction_options' ,
                ) ,
                    ) ;
        }

        /*
         * Action
         */

        public function actions() {
            add_filter( 'fs_affiliates_is_restricted_product' , array( $this , 'is_restricted_product' ) , 10 , 3 ) ;
        }

        /*
         * Check If Product is allowed to award commission
         */

        public function is_restricted_product( $bool , $ProductId , $VariationId = '' ) {
            $ProductIdToCheck = (empty( $VariationId )) ? $ProductId : $VariationId ;
            if ( $this->product_selection == 'all_products' ) {
                return true ;
            } elseif ( $this->product_selection == 'selected_products' ) {
                if ( in_array( $ProductId , $this->selected_products ) || in_array( $ProductIdToCheck , $this->selected_products ))
                    return true ;
            } elseif ( $this->product_selection == 'selected_categories' ) {
                if ( ! fs_affiliates_check_is_array( $this->selected_categories ) )
                    return false ;

                $CategoryList = get_the_terms( $ProductId , 'product_cat' ) ;
                if ( ! fs_affiliates_check_is_array( $CategoryList ) )
                    return false ;

                foreach ( $this->selected_categories as $CategoryId ) {
                    foreach ( $CategoryList as $Terms ) {
                        if ( $CategoryId == $Terms->term_id )
                            return true ;
                    }
                }
            }
            return false ;
        }

    }

}

<?php

/**
 * Register Custom Post Types
 */
if ( !defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( !class_exists( 'FS_Affiliates_Register_Post_Type' ) ) {

    /**
     * FS_Affiliates_Register_Post_Type Class
     */
    class FS_Affiliates_Register_Post_Type {

        /**
         * Class initialization
         */
        public static function init() {
            add_action( 'init' , array ( __CLASS__ , 'register_post_types' ) ) ;
        }

        /**
         * Register custom post types
         */
        public static function register_post_types() {
            register_post_type( 'fs-affiliates' ) ;
            register_post_type( 'fs-visits' ) ;
            register_post_type( 'fs-referrals' ) ;
            register_post_type( 'fs-creatives' ) ;
            register_post_type( 'fs-payouts' ) ;
            register_post_type( 'fs-payouts-batch' ) ;
            register_post_type( 'fs-wallet-logs' ) ;
            register_post_type( 'fs-coupon-linking' ) ;
            register_post_type( 'fs-landingcommission' ) ;
            register_post_type( 'fs-payout-request' ) ;
        }

    }

    FS_Affiliates_Register_Post_Type::init() ;
}
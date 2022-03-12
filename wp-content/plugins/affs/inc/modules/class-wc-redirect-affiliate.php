<?php

/**
 * WooCommerce Redirect Affiliate to Dashboard
 */
if ( !defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( !class_exists( 'FS_Affiliates_WC_Redirect_Affiliate' ) ) {

    /**
     * Class FS_Affiliates_WC_Redirect_Affiliate
     */
    class FS_Affiliates_WC_Redirect_Affiliate extends FS_Affiliates_Modules {

        /**
         * Class Constructor
         */
        public function __construct() {
            $this->id    = 'wc_redirect_affiliate' ;
            $this->title = __( 'WooCommerce Redirect Affiliate to Dashboard ' , FS_AFFILIATES_LOCALE ) ;

            parent::__construct() ;
        }

        /*
         * Front End Action
         */

        public function frontend_action() {
            add_filter( 'woocommerce_login_redirect' , array ( $this , 'redirect_to_dashboard' ) , 10 , 2 ) ;
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

        public function redirect_to_dashboard( $redirect , $user ) {

            $user_id = isset( $user->ID ) ? $user->ID : '' ;

            if ( $user_id == '' ) {
                return $redirect ;
            }

            $affilate_id       = fs_get_affiliate_id_for_user( $user_id ) ;
            $dashboard_page_id = fs_affiliates_get_page_id( 'dashboard' ) ;

            if ( $dashboard_page_id != '' && (fs_affiliates_is_affiliate_active( $affilate_id )) ) {
                return get_permalink( $dashboard_page_id ) ;
            }

            return $redirect ;
        }

    }

}
    
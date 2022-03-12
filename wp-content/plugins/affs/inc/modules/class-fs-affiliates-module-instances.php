<?php

/**
 * Modules Instances Class
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'FS_Affiliates_Module_Instances' ) ) {

    /**
     * Class FS_Affiliates_Module_Instances
     */
    class FS_Affiliates_Module_Instances {
        /*
         * Modules
         */

        private static $modules = array() ;

        /*
         * Get Modules
         */

        public static function get_modules() {
            if ( ! self::$modules ) {
                self::load_modules() ;
            }

            return self::$modules ;
        }

        /*
         * Load all Modules
         */

        public static function load_modules() {

            if ( ! class_exists( 'FS_Affiliates_Modules' ) )
                include FS_AFFILIATES_PLUGIN_PATH . '/inc/abstracts/class-fs-affiliates-modules.php' ;

            $default_module_classes = array(
                'signup-commission'                  => 'FS_Affiliates_Signup_Commission' ,
                'affiliate-signup-bonus'             => 'FS_Affiliates_Signup_Bonus_Module' ,
                'email-opt-in'                       => 'FS_Affiliates_email_opt_in' ,
                'email-verification'                 => 'FS_Affiliates_Email_Verification' ,
                'slug-modification'                  => 'FS_Affiliates_Slug_Modification' ,
                'pretty-affiliate-links'             => 'FS_Affiliates_Pretty_Affiliate_Links' ,
                'refer-friend'                       => 'FS_Affiliates_Refer_Friend_Module' ,
                'qrcode'                             => 'FS_Affiliates_QR_Code' ,
                'social-share'                       => 'FS_Affiliates_Social_Share' ,
                'url-masking'                        => 'FS_Affiliates_url_masking' ,
                'sms'                                => 'FS_Affiliates_SMS_Module' ,
                'pushover-notifications'             => 'FS_Affiliates_Pushover_Notifications_module' ,
                'affiliate-landing-pages'            => 'FS_Affiliates_landing_pages' ,
                'landing-commissions'                => 'FS_Affiliates_Landing_Commissions' ,
                'additional-dashboard-tabs'          => 'FS_Affiliates_Additional_Dashboard_Tabs' ,
                'multi-level-marketing'              => 'FS_Affiliates_Multi_Level_Marketing' ,
                'commission-links'                   => 'FS_Affiliates_Commission_Links' ,
                'credit-last-referrer'               => 'FS_Affiliates_Credit_Last_Referrer' ,
                'referral-commission-threshold'      => 'FS_Affiliates_Referral_Commission_Threshold' ,
                'fraud-protection'                   => 'FS_Affiliates_Fraud_Protection' ,
                'paypal-payouts'                     => 'FS_Affiliates_Paypal_Payouts_Module' ,
                'payout-request'                     => 'FS_Affiliates_Payout_Request' ,
                'payout-statements'                  => 'FS_Affiliates_Payout_Statements' ,
                'periodic-reports'                   => 'FS_Affiliates_Periodic_Reports_Module' ,
                'leaderboard'                        => 'FS_Affiliates_Leaderboard' ,
                'export'                             => 'FS_Affiliates_Export' ,
                'affiliate-signup-restriction'       => 'FS_Affiliates_Signup_Restriction' ,
                'affiliate-fee'                      => 'FS_Affiliates_Fee' ,
                'wc-account-management'              => 'FS_Affiliates_WC_Account_Management' ,
                'wc-redirect-affiliate'              => 'FS_Affiliates_WC_Redirect_Affiliate' ,
                'product-based-affiliate-link'       => 'FS_Product_Based_Affiliate_Link_Module' ,
                'referral-code'                      => 'FS_Affiliates_Referral_Code' ,
                'affiliate-level-product-commission' => 'FS_Affiliates_Level_Product_Commission' ,
                'wc-coupon-linking'                  => 'FS_Affiliates_WC_Coupon_Linking' ,
                'checkout-affiliate'                 => 'FS_Affiliates_Checkout' ,
                'referral-order-details'             => 'FS_Affiliates_Referral_Order_details' ,
                'affiliate-wallet'                   => 'FS_Affiliates_Wallet_Module' ,
                'affiliate-lifetime-commission'      => 'FS_Affiliates_Lifetime_Commission_Module' ,
                'wc-product-restriction'             => 'FS_Affiliates_WC_Product_Restriction' ,
                'wc-referral-restriction'            => 'FS_Affiliates_WC_Referral_Restriction' ,
                    ) ;

            foreach ( $default_module_classes as $file_name => $module_class ) {

                // include file
                include 'class-' . $file_name . '.php' ;
                //add module
                self::add_module( new $module_class ) ;
            }
        }

        /**
         * Add a Module
         */
        public static function add_module( $module ) {

            self::$modules[ $module->get_id() ] = $module ;

            return new self() ;
        }

        /**
         * Get module by id
         */
        public static function get_module_by_id( $module_id ) {
            $modules = self::get_modules() ;

            return isset( $modules[ $module_id ] ) ? $modules[ $module_id ] : false ;
        }

    }

}

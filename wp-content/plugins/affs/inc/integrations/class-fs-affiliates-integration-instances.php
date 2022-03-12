<?php

/**
 * Integration Instances Class
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'FS_Affiliates_Integration_Instances' ) ) {

    /**
     * Class FS_Affiliates_Integration_Instances
     */
    class FS_Affiliates_Integration_Instances {
        /*
         * Integrations
         */

        private static $integrations = array() ;

        /*
         * Get Modules
         */

        public static function get_integrations() {
            if ( ! self::$integrations ) {
                self::load_integrations() ;
            }

            return self::$integrations ;
        }

        /*
         * Load all Modules
         */

        public static function load_integrations() {

            if ( ! class_exists( 'FS_Affiliates_Integrations' ) )
                include FS_AFFILIATES_PLUGIN_PATH . '/inc/abstracts/class-fs-affiliates-integrations.php' ;

            $default_integration_classes = array(
                'contact-forms'                 => 'FS_Affiliates_Contact_Forms' ,
                'formidable-forms'              => 'FS_Affiliates_Formidable_Forms' ,
                'wp-forms'                      => 'FS_Affiliates_WP_Forms' ,
                'woocommerce'                   => 'FS_Affiliates_Woocommerce' ,
                'sumo-reward-points'            => 'FS_Affiliates_SUMO_Reward_Points' ,
                'sumo-subscriptions'            => 'FS_Affiliates_SUMO_Subscriptions' ,
                'sumo-payment-plans'            => 'FS_Affiliates_SUMO_Payment_Plans' ,
                'sumo-preorders'                => 'FS_Affiliates_SUMO_Preorders' ,
                'recover-abandoned-cart'        => 'FS_Affiliates_RAC' ,
                'wc-subscriptions'              => 'FS_Affiliates_WC_Subscriptions' ,
                'sumo-memberships'              => 'FS_Affiliates_SUMO_Memberships' ,
                'woocommerce-currency-switcher' => 'FS_Woocommerce_Currency_Switcher' ,
                    ) ;

            foreach ( $default_integration_classes as $file_name => $integration_class ) {

                // include file
                include 'class-' . $file_name . '.php' ;

                //add integration
                self::add_integration( new $integration_class ) ;
            }
        }

        /**
         * Add a Module
         */
        public static function add_integration( $integration ) {

            self::$integrations[ $integration->get_id() ] = $integration ;

            return new self() ;
        }

        /**
         * Get integration by id
         */
        public static function get_integration_by_id( $integration_id ) {
            $integrations = self::get_integrations() ;

            return isset( $integrations[ $integration_id ] ) ? $integrations[ $integration_id ] : false ;
        }

    }

}
    
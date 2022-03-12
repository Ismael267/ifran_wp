<?php

/**
 * Export
 */
if ( !defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( !class_exists( 'FS_Affiliates_Export' ) ) {

    /**
     * Class FS_Affiliates_Export
     */
    class FS_Affiliates_Export extends FS_Affiliates_Modules {

        /**
         * Class Constructor
         */
        public function __construct() {
            $this->id    = 'export' ;
            $this->title = __( 'Export' , FS_AFFILIATES_LOCALE ) ;

            parent::__construct() ;
        }

        /*
         * Get settings link
         */

        public function settings_link() {
            return add_query_arg( array ( 'page' => 'fs_affiliates' , 'tab' => 'modules' , 'section' => $this->id ) , admin_url( 'admin.php' ) ) ;
        }

        /*
         * Get settings options array
         */

        public function settings_options_array() {
            return array (
                array (
                    'type' => 'display_export' ,
                ) ,
                    ) ;
        }

        /*
         * Output Buttons
         */

        public function output_buttons() {
            
        }

        /*
         * Admin Action
         */

        public function admin_action() {
            add_action( $this->plugin_slug . '_admin_field_display_export' , array ( $this , 'display_export' ) ) ;
        }

        /*
         * Display Export
         */

        public function display_export() {
            $exports = array (
                'affiliates' ,
                'referrals' ,
                'visits' ,
                'payouts'
                    ) ;

            foreach ( $exports as $export ) {
                include_once 'views/export-' . $export . '.php' ;
            }
        }

    }

}
    
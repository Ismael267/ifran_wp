<?php

/**
 * Abstract Integrations Class
 */
if ( !defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( !class_exists( 'FS_Affiliates_Integrations' ) ) {

    /**
     * Class FS_Affiliates_Integrations
     */
    abstract class FS_Affiliates_Integrations {
        /*
         * ID
         */

        protected $id ;

        /*
         * Title
         */
        protected $title ;
        /*
         * Data
         */
        protected $data = array (
            'enabled' => 'no'
                ) ;

        /*
         * Plugin slug
         */
        protected $plugin_slug = 'fs_affiliates' ;

        /**
         * Class Constructor
         */
        public function __construct() {
            $this->prepare_options() ;
            $this->process_actions() ;
        }

        /*
         * Get id
         */

        public function get_id() {
            return $this->id ;
        }

        /*
         * Get title
         */

        public function get_title() {
            return $this->title ;
        }

        /*
         * Get image url
         */

        public function get_image_url() {
            return sprintf( '%s/assets/images/integration/%s.png' , FS_AFFILIATES_PLUGIN_URL , $this->id ) ;
        }

        /*
         * Actions
         */

        public function process_actions() {
            if ( !$this->is_enabled() )
                return ;

            $this->actions() ;

            if ( is_admin() )
                $this->admin_action() ;

            if ( !is_admin() || defined( 'DOING_AJAX' ) )
                $this->frontend_action() ;
        }

        /*
         * Admin Actions
         */

        public function admin_action() {
            
        }

        /*
         * Actions
         */

        public function actions() {
            
        }

        /*
         * Frontend Actions
         */

        public function frontend_action() {
            
        }

        /*
         * Plugin enabled
         */

        public function is_plugin_enabled() {
            return true ;
        }

        /*
         * Get settings link
         */

        public function settings_link() {
            return null ;
        }

        /*
         * Get settings options array
         */

        public function settings_options_array() {
            array () ;
        }

        /*
         * is enabled
         */

        public function is_enabled() {

            return 'yes' === $this->enabled ;
        }

        /*
         * Get data
         */

        public function get_data() {
            return $this->data ;
        }

        /*
         * Update Option
         */

        public function update_option( $key , $value ) {
            $field_key = $this->get_field_key( $key ) ;

            return update_option( $field_key , $value ) ;
        }

        /*
         * Prepare Options
         */

        public function prepare_options() {
            $default_data = $this->data ;

            foreach ( $default_data as $key => $value ) {

                $this->$key = $this->get_option( $key , $value ) ;
            }
        }

        /*
         * Get Option
         */

        public function get_option( $key , $value = false ) {
            $field_key = $this->get_field_key( $key ) ;

            return get_option( $field_key , $value ) ;
        }

        /*
         * Get field key
         */

        public function get_field_key( $key ) {
            return $this->plugin_slug . '_' . $this->id . '_' . $key ;
        }

        /*
         * Extra Fields
         */

        public function extra_fields() {
            
        }

    }

}

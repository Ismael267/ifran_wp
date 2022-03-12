<?php

/**
 * Abstract Modules Class
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'FS_Affiliates_Modules' ) ) {

    /**
     * FS_Affiliates_Modules Class
     */
    abstract class FS_Affiliates_Modules {
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
        protected $data = array( 'enabled' => 'no' ) ;

        /*
         * Plugin slug
         */
        protected $plugin_slug = 'fs_affiliates' ;

        /*
         * Options
         */
        protected $options = array() ;

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
            if ( ! $this->is_enabled() )
                return $this->get_inactive_image_url() ;

            return $this->get_active_image_url() ;
        }

        /*
         * Get active image url
         */

        public function get_active_image_url() {
            return sprintf( '%s/assets/images/module/%s.png' , FS_AFFILIATES_PLUGIN_URL , $this->id . '_active' ) ;
        }

        /*
         * Get inactive image url
         */

        public function get_inactive_image_url() {
            return sprintf( '%s/assets/images/module/%s.png' , FS_AFFILIATES_PLUGIN_URL , $this->id . '_inactive' ) ;
        }

        /*
         * Actions
         */

        public function process_actions() {
            if ( ! $this->is_enabled() )
                return ;

            $this->actions() ;

            if ( is_admin() )
                $this->admin_action() ;

            if ( ! is_admin() || defined( 'DOING_AJAX' ) )
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
         * Save
         */

        public function save() {
            
        }

        /*
         * After save
         */

        public function after_save() {
            
        }

        /*
         * Before save
         */

        public function before_save() {
            
        }

        /*
         * Get settings link
         */

        public function settings_link() {
            return null ;
        }

        /*
         * is enabled
         */

        public function is_enabled() {

            return $this->is_plugin_enabled() && 'yes' === $this->enabled ;
        }

        /*
         * is plugin enabled
         */

        public function is_plugin_enabled() {

            return true ;
        }

        /*
         * Get settings options array
         */

        public function settings_options_array() {
            array() ;
        }

        /*
         * Get data
         */

        public function get_data() {
            return $this->data ;
        }

        /**
         * Output the settings buttons.
         */
        public function output_buttons() {
            global $current_section ;

            if ( $current_section ) {
                FS_Affiliates_Settings::output_buttons() ;
            }
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

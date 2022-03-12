<?php

/**
 * Settings Page/Tab
 */
if ( !defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly
}

if ( !class_exists( 'FS_Affiliates_Settings_Page' ) ) {

    /**
     * FS_Affiliates_Settings_Page.
     */
    abstract class FS_Affiliates_Settings_Page {

        /**
         * Setting page id.
         */
        protected $id = '' ;

        /**
         * Setting page label.
         */
        protected $label = '' ;

        /**
         * Plugin slug.
         */
        protected $plugin_slug = 'fs_affiliates' ;

        /**
         * Constructor.
         */
        public function __construct() {
            add_filter( $this->plugin_slug . '_settings_tabs_array' , array ( $this , 'add_settings_page' ) , 20 ) ;
            add_action( $this->plugin_slug . '_sections_' . $this->id , array ( $this , 'output_sections' ) ) ;
            add_action( $this->plugin_slug . '_settings_' . $this->id , array ( $this , 'output' ) ) ;
            add_action( $this->plugin_slug . '_settings_buttons_' . $this->id , array ( $this , 'output_buttons' ) ) ;
            add_action( $this->plugin_slug . '_settings_save_' . $this->id , array ( $this , 'save' ) ) ;
            add_action( $this->plugin_slug . '_settings_reset_' . $this->id , array ( $this , 'reset' ) ) ;
            add_action( $this->plugin_slug . '_after_setting_buttons_' . $this->id , array ( $this , 'output_extra_fields' ) ) ;
        }

        /**
         * Get settings page ID.
         */
        public function get_id() {
            return $this->id ;
        }

        /**
         * Get settings page label.
         */
        public function get_label() {
            return $this->label ;
        }

        /**
         * Get plugin slug.
         */
        public function get_plugin_slug() {
            return $this->plugin_slug ;
        }

        /**
         * Add this page to settings.
         */
        public function add_settings_page( $pages ) {
            $pages[ $this->id ] = $this->label ;

            return $pages ;
        }

        /**
         * Get settings array.
         */
        public function get_settings() {
            return apply_filters( $this->plugin_slug . '_get_settings_' . $this->id , array () ) ;
        }

        /**
         * Get sections.
         */
        public function get_sections() {
            return apply_filters( $this->plugin_slug . '_get_sections_' . $this->id , array () ) ;
        }

        /**
         * Output sections.
         */
        public function output_sections() {
            global $current_section ;

            $sections = $this->get_sections() ;

            if ( empty( $sections ) || 1 === sizeof( $sections ) ) {
                return ;
            }

            echo '<ul class="subsubsub ' . $this->plugin_slug . '_sections ' . $this->plugin_slug . '_subtab">' ;

            $array_keys      = array_keys( $sections ) ;
            $current_section = ($current_section == '') ? current( $array_keys ) : $current_section ;

            foreach ( $sections as $id => $section ) {
                echo '<li>'
                . '<a href="' . admin_url( 'admin.php?page=' . $this->plugin_slug . '&tab=' . $this->id . '&section=' . sanitize_title( $id ) ) . '" '
                . 'class="' . ( $current_section == $id ? 'current' : '' ) . '"><i class="fa ' . $section[ 'code' ] . '"></i>' . $section[ 'label' ] . '</a></li>' ;
            }

            echo '</ul><br class="clear" />' ;
        }

        /**
         * Output the settings.
         */
        public function output() {
            global $current_section ;

            $settings = $this->get_settings( $current_section ) ;

            FS_Affiliates_Settings::output_fields( $settings ) ;

            do_action( $this->plugin_slug . '_' . $this->id . '_' . $current_section . '_display' ) ;
        }

        /**
         * Output the settings buttons.
         */
        public function output_buttons() {

            FS_Affiliates_Settings::output_buttons() ;
        }

        /**
         * Save settings.
         */
        public function save() {
            global $current_section ;

            if ( empty( $_POST[ 'save' ] ) )
                return ;

            $settings = $this->get_settings( $current_section ) ;
            FS_Affiliates_Settings::save_fields( $settings ) ;
            FS_Affiliates_Settings::add_message( __( 'Your settings have been saved.' , FS_AFFILIATES_LOCALE ) ) ;
        }

        /**
         * Reset settings.
         */
        public function reset() {
            global $current_section ;

            if ( empty( $_POST[ 'reset' ] ) )
                return ;

            $settings = $this->get_settings( $current_section ) ;
            FS_Affiliates_Settings::reset_fields( $settings ) ;
            FS_Affiliates_Settings::add_message( __( 'Your settings have been resetted.' , FS_AFFILIATES_LOCALE ) ) ;
        }

        /**
         * Output the extra fields
         */
        public function output_extra_fields() {
            
        }

    }

}

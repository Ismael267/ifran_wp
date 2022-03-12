<?php

/**
 * Integration Tab
 */
if ( !defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( class_exists( 'FS_Affiliates_Integration_Tab' ) ) {
    return new FS_Affiliates_Integration_Tab() ;
}

/**
 * Class
 */
class FS_Affiliates_Integration_Tab extends FS_Affiliates_Settings_Page {

    /**
     * Constructor.
     */
    public function __construct() {
        $this->id    = 'integration' ;
        $this->label = __( 'Integration' , FS_AFFILIATES_LOCALE ) ;

        add_action( $this->plugin_slug . '_admin_field_output_integration' , array ( $this , 'output_integration' ) ) ;

        parent::__construct() ;
    }

    /**
     * Get settings array
     */
    public function get_settings( $current_section = '' ) {
        return array (
            array ( 'type' => 'output_integration' )
                ) ;
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

    /**
     * Output the modules
     */
    public function output_integration() {
        global $current_section ;

        if ( $current_section ) {
            $integration_object = FS_Affiliates_Integration_Instances::get_integration_by_id( $current_section ) ;
            if ( is_object( $integration_object ) ) {
                FS_Affiliates_Settings::output_fields( $integration_object->settings_options_array() ) ;
            }
        } else {
            include_once( FS_AFFILIATES_PLUGIN_PATH . '/inc/integrations/views/layout.php' ) ;
        }
    }

    /**
     * Output the integrations
     */
    public function save() {
        global $current_section ;

        if ( empty( $_POST[ 'save' ] ) )
            return ;

        if ( !$current_section )
            return ;

        $integration_object = FS_Affiliates_Integration_Instances::get_integration_by_id( $current_section ) ;
        if ( is_object( $integration_object ) ) {
            FS_Affiliates_Settings::save_fields( $integration_object->settings_options_array() ) ;
        }

        FS_Affiliates_Settings::add_message( __( 'Your settings have been saved.' , FS_AFFILIATES_LOCALE ) ) ;
    }

    /**
     * Reset settings.
     */
    public function reset() {
        global $current_section ;

        if ( empty( $_POST[ 'reset' ] ) )
            return ;

        if ( !$current_section )
            return ;

        $integration_object = FS_Affiliates_Integration_Instances::get_integration_by_id( $current_section ) ;
        if ( is_object( $integration_object ) ) {
            FS_Affiliates_Settings::reset_fields( $integration_object->settings_options_array() ) ;
        }

        FS_Affiliates_Settings::add_message( __( 'Your settings have been reset.' , FS_AFFILIATES_LOCALE ) ) ;
    }

}

return new FS_Affiliates_Integration_Tab() ;

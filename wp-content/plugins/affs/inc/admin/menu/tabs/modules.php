<?php

/**
 * Modules Tab
 */
if ( !defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( class_exists( 'FS_Affiliates_Modules_Tab' ) ) {
    return new FS_Affiliates_Modules_Tab() ;
}

/**
 * FS_Affiliates_Modules_Tab.
 */
class FS_Affiliates_Modules_Tab extends FS_Affiliates_Settings_Page {

    /**
     * Constructor.
     */
    public function __construct() {
        $this->id    = 'modules' ;
        $this->label = __( 'Modules' , FS_AFFILIATES_LOCALE ) ;

        add_action( $this->plugin_slug . '_admin_field_output_modules' , array ( $this , 'output_modules' ) ) ;

        parent::__construct() ;
    }

    /**
     * Get settings array.
     */
    public function get_settings( $current_section = '' ) {

        return array (
            array ( 'type' => 'output_modules' )
                ) ;
    }

    /**
     * Output the settings buttons.
     */
    public function output_buttons() {
        global $current_section ;

        if ( $current_section ) {
            $module_object = FS_Affiliates_Module_Instances::get_module_by_id( $current_section ) ;
            $module_object->output_buttons() ;
        }
    }

    /**
     * Output the modules
     */
    public function output_modules() {
        global $current_section ;

        if ( $current_section ) {
            $module_object = FS_Affiliates_Module_Instances::get_module_by_id( $current_section ) ;
            if ( is_object( $module_object ) ) {
                FS_Affiliates_Settings::output_fields( $module_object->settings_options_array() ) ;
            }
        } else {
            include_once( FS_AFFILIATES_PLUGIN_PATH . '/inc/modules/views/layout.php' ) ;
        }
    }

    /**
     * Output the modules
     */
    public function save() {
        global $current_section ;

        if ( !$current_section )
            return ;

        $module_object = FS_Affiliates_Module_Instances::get_module_by_id( $current_section ) ;
        if ( !is_object( $module_object ) )
            return ;

        $module_object->before_save() ;

        if ( empty( $_POST[ 'save' ] ) )
            return ;

        $module_object->save() ;
        FS_Affiliates_Settings::save_fields( $module_object->settings_options_array() ) ;

        $module_object->after_save() ;

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

        $module_object = FS_Affiliates_Module_Instances::get_module_by_id( $current_section ) ;
        if ( is_object( $module_object ) ) {
            FS_Affiliates_Settings::reset_fields( $module_object->settings_options_array() ) ;
        }

        FS_Affiliates_Settings::add_message( __( 'Your settings have been reset.' , FS_AFFILIATES_LOCALE ) ) ;
    }

    /**
     * Output the extra fields
     */
    public function output_extra_fields() {
        global $current_section ;

        if ( !$current_section )
            return ;

        $module_object = FS_Affiliates_Module_Instances::get_module_by_id( $current_section ) ;

        $module_object->extra_fields() ;
    }

}

return new FS_Affiliates_Modules_Tab() ;

<?php

/**
 * Notifications Tab
 */
if ( !defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( class_exists( 'FS_Affiliates_Notifications_Tab' ) ) {
    return new FS_Affiliates_Notifications_Tab() ;
}

/**
 * FS_Affiliates_Notifications_Tab.
 */
class FS_Affiliates_Notifications_Tab extends FS_Affiliates_Settings_Page {

    /**
     * Constructor.
     */
    public function __construct() {
        $this->id    = 'notifications' ;
        $this->label = __( 'Notifications' , FS_AFFILIATES_LOCALE ) ;

        add_action( $this->plugin_slug . '_admin_field_output_notifications' , array ( $this , 'output_notifications' ) ) ;

        parent::__construct() ;
    }

    /**
     * Get settings array.
     */
    public function get_settings( $current_section = '' ) {

        return array (
            array ( 'type' => 'output_notifications' )
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
     * Output the notifications
     */
    public function output_notifications() {
        global $current_section ;

        if ( $current_section ) {
            $notification_object = FS_Affiliates_Notification_Instances::get_notification_by_id( $current_section ) ;
            if ( is_object( $notification_object ) ) {
                FS_Affiliates_Settings::output_fields( $notification_object->settings_options_array() ) ;
            }
        } else {
            include_once( FS_AFFILIATES_PLUGIN_PATH . '/inc/notifications/views/layout.php' ) ;
        }
    }

    /**
     * Output the notifications
     */
    public function save() {
        global $current_section ;

        if ( empty( $_POST[ 'save' ] ) )
            return ;

        if ( !$current_section )
            return ;

        $notification_object = FS_Affiliates_Notification_Instances::get_notification_by_id( $current_section ) ;
        if ( is_object( $notification_object ) ) {
            FS_Affiliates_Settings::save_fields( $notification_object->settings_options_array() ) ;
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

        $notification_object = FS_Affiliates_Notification_Instances::get_notification_by_id( $current_section ) ;
        if ( is_object( $notification_object ) ) {
            FS_Affiliates_Settings::reset_fields( $notification_object->settings_options_array() ) ;
        }

        FS_Affiliates_Settings::add_message( __( 'Your settings have been reset.' , FS_AFFILIATES_LOCALE ) ) ;
    }

}

return new FS_Affiliates_Notifications_Tab() ;

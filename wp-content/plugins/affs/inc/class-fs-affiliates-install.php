<?php

/**
 * Initialize the Plugin.
 */
if ( !defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( !class_exists( 'FS_Affiliates_Install' ) ) {

    /**
     * Class.
     */
    class FS_Affiliates_Install {
        /*
         * Plugin Slug
         */

        protected static $plugin_slug = 'fs_affiliates' ;

        /**
         *  Class initialization.
         */
        public static function init() {
            add_filter( "plugin_action_links_" . FS_AFFILIATES_PLUGIN_BASE_NAME , array ( __CLASS__ , 'settings_link' ) ) ;
        }

        /**
         * Install Affiliates System
         */
        public static function install() {

            FS_Affiliates_Pages::create_pages() ; //Create pages
            self::set_default_values() ; // default values
            self::welcome_screen_activation() ; //welcome page
        }

        /**
         * Welcome Page
         */
        public static function welcome_screen_activation() {
            set_transient( '_welcome_screen_activation_redirect_' . self::$plugin_slug , true , 30 ) ;
        }

        /**
         *  Settings link. 
         */
        public static function settings_link( $links ) {
            $setting_page_link = '<a href="admin.php?page=fs_affiliates">' . __( "Settings" , FS_AFFILIATES_LOCALE ) . '</a>' ;
            $welcome_page_link = '<a href="admin.php?page=fs-affiliates-welcome-page">' . __( "About" , FS_AFFILIATES_LOCALE ) . '</a>' ;

            array_unshift( $links , $welcome_page_link ) ;
            array_unshift( $links , $setting_page_link ) ;

            return $links ;
        }

        /**
         *  Set settings default values  
         */
        public static function set_default_values() {
            include_once(FS_AFFILIATES_PLUGIN_PATH . '/inc/admin/menu/class-fs-affiliates-settings.php') ;

            $settings = FS_Affiliates_Settings::get_settings_pages() ;

            foreach ( $settings as $setting ) {
                $sections = $setting->get_sections() ;
                if ( !fs_affiliates_check_is_array( $sections ) )
                    continue ;

                foreach ( $sections as $section_key => $section ) {
                    $settings_array = $setting->get_settings( $section_key ) ;
                    foreach ( $settings_array as $value ) {
                        if ( isset( $value[ 'default' ] ) && isset( $value[ 'id' ] ) ) {
                            if ( get_option( $value[ 'id' ] ) == false )
                                add_option( $value[ 'id' ] , $value[ 'default' ] ) ;
                        }
                    }
                }
            }

     	    $status               = fs_affiliates_get_paymethod_preference_status () ;
            $label                = fs_affiliates_get_paymethod_preference () ;
            $old_version_payments = get_option ( 'fs_affiliates_payment_preference' , array ( 0 => 'direct' , 1 => 'paypal' ) ) ;

            $status_update        = array () ;
            if ( get_option ( 'fs_affiliates_payment_preference_label' ) == false ) {
                add_option ( 'fs_affiliates_payment_preference_label' , $label ) ;
                foreach ( $status as $key => $status ) {
                    $status                = ( fs_affiliates_check_is_array($old_version_payments) && in_array( $key , $old_version_payments ) ) ?  'enable' : 'disable' ;
                    $status_update[ $key ] = $status ;
                }
                update_option ( 'fs_affiliates_payment_preference' , $status_update ) ;
            }




            $modules       = FS_Affiliates_Module_Instances::get_modules() ;
            $notifications = FS_Affiliates_Notification_Instances::get_notifications() ;
            $integrations  = FS_Affiliates_Integration_Instances::get_integrations() ;

            foreach ( array ( $modules , $notifications , $integrations ) as $objects ) {
                foreach ( $objects as $object ) {
                    $settings = $object->settings_options_array() ;

                    if ( !fs_affiliates_check_is_array( $settings ) )
                        continue ;

                    foreach ( $settings as $setting ) {
                        if ( isset( $setting[ 'default' ] ) && isset( $setting[ 'id' ] ) ) {
                            if ( get_option( $setting[ 'id' ] ) == false )
                                add_option( $setting[ 'id' ] , $setting[ 'default' ] ) ;
                        }
                    }
                }
            }
        }

    }

    FS_Affiliates_Install::init() ;
}

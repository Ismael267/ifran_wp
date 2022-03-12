<?php

/*
 * Welcome Page
 */
if ( ! class_exists( 'FP_Affiliates_Welcome_Page' ) ) {

    /**
     * FP_Affiliates_Welcome_Page Class.
     */
    class FP_Affiliates_Welcome_Page {
        /*
         * Page
         */

        private static $welcome_page = 'fs-affiliates-welcome-page' ;

        /*
         * Plugin slug
         */
        private static $plugin_slug = 'fs_affiliates' ;

        /**
         * FP_Affiliates_Welcome_Page Class initialization.
         */
        public static function init() {
            add_action( 'admin_init' , array( __CLASS__ , 'welcome_screen_do_activation_redirect' ) ) ;
            add_action( 'admin_menu' , array( __CLASS__ , '_welcome_screen_pages' ) ) ;
            add_action( 'admin_head' , array( __CLASS__ , 'remove_welcome_screen_menus' ) ) ;
        }

        /**
         * Redirect to Welcome Page 
         */
        public static function welcome_screen_do_activation_redirect() {
            if ( ! get_transient( '_welcome_screen_activation_redirect_' . self::$plugin_slug ) ) {
                return ;
            }

            delete_transient( '_welcome_screen_activation_redirect_' . self::$plugin_slug ) ;

            wp_safe_redirect( add_query_arg( array( 'page' => self::$welcome_page ) , admin_url( 'admin.php' ) ) ) ;
        }

        /**
         * Add custom dashboard page
         */
        public static function _welcome_screen_pages() {
            add_dashboard_page(
                    __( 'Welcome To Affiliate Pro' , FS_AFFILIATES_LOCALE ) , __( 'Welcome To Affiliate Pro' , FS_AFFILIATES_LOCALE ) , 'read' , self::$welcome_page , array( __CLASS__ , 'welcome_screen_content' )
            ) ;
        }

        /**
         * Display Welcome Page Content
         */
        public static function welcome_screen_content() {
            //Welcome Page Css
            wp_enqueue_style( 'fs_affiliates_welcome_css' , FS_AFFILIATES_PLUGIN_URL . '/assets/css/backend/welcome-page.css' , array() , FS_AFFILIATES_VERSION ) ;

            include_once( 'welcome-layout.php' ) ;
        }

        /**
         * Remove Welcome page Sub Menu from dashboard Menu.
         */
        public static function remove_welcome_screen_menus() {
            remove_submenu_page( 'index.php' , self::$welcome_page ) ;
        }

    }

    FP_Affiliates_Welcome_Page::init() ;
}



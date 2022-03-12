<?php

/**
 * Pages
 */
if ( !defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( !class_exists( 'FS_Affiliates_Pages' ) ) {

    /**
     * Class.
     */
    class FS_Affiliates_Pages {
        /*
         * Plugin Slug
         */

        protected static $plugin_slug = 'fs_affiliates' ;

        /**
         * Create pages
         */
        public static function create_pages() {
            $pages = apply_filters(
                    self::$plugin_slug . '_create_pages' , array (
                'register'      => array (
                    'name'    => _x( 'registration' , 'Page slug' , FS_AFFILIATES_LOCALE ) ,
                    'title'   => _x( 'Registration' , 'Page title' , FS_AFFILIATES_LOCALE ) ,
                    'content' => '[fs_affiliates_register]' ,
                    'option'  => self::$plugin_slug . '_register_page_id'
                ) ,
                'login'         => array (
                    'name'    => _x( 'login' , 'Page slug' , FS_AFFILIATES_LOCALE ) ,
                    'title'   => _x( 'Login' , 'Page title' , FS_AFFILIATES_LOCALE ) ,
                    'content' => '[fs_affiliates_login]' ,
                    'option'  => self::$plugin_slug . '_login_page_id'
                ) ,
                'dashboard'     => array (
                    'name'    => _x( 'dashboard' , 'Page slug' , FS_AFFILIATES_LOCALE ) ,
                    'title'   => _x( 'Dashboard' , 'Page title' , FS_AFFILIATES_LOCALE ) ,
                    'content' => '[fs_affiliates_dashboard]' ,
                    'option'  => self::$plugin_slug . '_dashboard_page_id'
                ) ,
                'terms'         => array (
                    'name'    => _x( 'terms' , 'Page slug' , FS_AFFILIATES_LOCALE ) ,
                    'title'   => _x( 'Terms of Use' , 'Page title' , FS_AFFILIATES_LOCALE ) ,
                    'content' => '[fs_affiliates_terms]' ,
                    'option'  => self::$plugin_slug . '_terms_page_id'
                ) ,
                'lost_password' => array (
                    'name'    => _x( 'lost-password' , 'Page slug' , FS_AFFILIATES_LOCALE ) ,
                    'title'   => _x( 'Lost Password' , 'Page title' , FS_AFFILIATES_LOCALE ) ,
                    'content' => '[fs_affiliates_lost_password]' ,
                    'option'  => self::$plugin_slug . '_lost_password_page_id'
                ) ,
                    )
                    ) ;

            foreach ( $pages as $page_args ) {
                self::create( $page_args ) ;
            }
        }

        /*
         * Creat page
         */

        public static function create( $page_args = array () ) {

            $defalut_page_args = array (
                'name'    => '' ,
                'title'   => '' ,
                'content' => '' ,
                'option'  => '' ,
                    ) ;

            $page_args = wp_parse_args( $page_args , $defalut_page_args ) ;

            extract( $page_args ) ;

            $option_value = get_option( $option ) ;

            if ( !empty( $option ) && $page_object = get_post( $option_value ) ) {
                if ( $page_object->post_type == 'page' ) {
                    if ( !in_array( $page_object->post_status , array ( 'pending' , 'trash' , 'future' , 'auto-draft' ) ) ) {
                        return $page_object->ID ;
                    }
                }
            }

            $page_data = array (
                'post_status'    => 'publish' ,
                'post_type'      => 'page' ,
                'post_author'    => 1 ,
                'post_name'      => esc_sql( $name ) ,
                'post_title'     => $title ,
                'post_content'   => $content ,
                'comment_status' => 'closed' ,
                    ) ;

            $page_id = wp_insert_post( $page_data ) ;

            if ( $option )
                update_option( $option , $page_id ) ;

            return $page_id ;
        }

        /*
         * Class Initialization
         */

        public static function init() {
            add_filter( 'display_post_states' , array ( __CLASS__ , 'post_states' ) , 10 , 2 ) ;
        }

        /*
         * Denotes the post states as such in the pages list table.
         */

        public static function post_states( $post_states , $post ) {

            if ( fs_affiliates_get_page_id( 'register' ) == $post->ID ) {
                $post_states[ self::$plugin_slug . '_register_page' ] = __( 'Affiliate Registration Page - Advanced' , FS_AFFILIATES_LOCALE ) ;
            } elseif ( fs_affiliates_get_page_id( 'login' ) == $post->ID ) {
                $post_states[ self::$plugin_slug . '_login_page' ] = __( 'Affiliate Login Page - Advanced' , FS_AFFILIATES_LOCALE ) ;
            } elseif ( fs_affiliates_get_page_id( 'dashboard' ) == $post->ID ) {
                $post_states[ self::$plugin_slug . '_dashboard_page' ] = __( 'Affiliate Dashboard Page' , FS_AFFILIATES_LOCALE ) ;
            } elseif ( fs_affiliates_get_page_id( 'lost_password' ) == $post->ID ) {
                $post_states[ self::$plugin_slug . '_lost_password_page' ] = __( 'Affiliate Lost Password Page' , FS_AFFILIATES_LOCALE ) ;
            } elseif ( fs_affiliates_get_page_id( 'terms' ) == $post->ID ) {
                $post_states[ self::$plugin_slug . '_terms_page' ] = __( 'Affiliate Term Of Use Page' , FS_AFFILIATES_LOCALE ) ;
            }

            return $post_states ;
        }

    }

    FS_Affiliates_Pages::init() ;
}
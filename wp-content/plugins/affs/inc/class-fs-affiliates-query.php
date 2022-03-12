<?php

/*
 * Query
 */
if ( !defined( 'ABSPATH' ) )
    exit ; // Exit if accessed directly.

if ( !class_exists( 'FS_Affiliates_Query' ) ) {

    /**
     * FS_Affiliates_Query Class.
     */
    class FS_Affiliates_Query {

        /**
         * Query vars to add to wp.
         */
        protected $query_vars = array () ;

        /**
         * Constructor for the query class
         */
        public function __construct() {

            if ( is_admin() )
                return ;

            add_action( 'init' , array ( $this , 'customEndpoint' ) ) ;
            add_action( 'wp_loaded' , array ( $this , 'flushRewriteRules' ) ) ;
            add_filter( 'query_vars' , array ( $this , 'customQueryVars' ) , 0 ) ;
        }

        /**
         * Get query vars
         */
        public function get_query_vars() {
            return apply_filters( 'fs_affiliates_query_vars' , $this->query_vars ) ;
        }

        /**
         * Rewrite Coworker Endpoint
         */
        public function customEndpoint() {

            $query_vars = $this->get_query_vars() ;

            foreach ( $query_vars as $key => $mask ) {
                add_rewrite_endpoint( $key , $mask ) ;
            }
        }

        /**
         * Add custom Query variable
         */
        public function customQueryVars( $vars ) {

            $query_vars = $this->get_query_vars() ;

            foreach ( $query_vars as $key => $mask ) {
                $vars[] = $key ;
            }

            return $vars ;
        }

        /**
         * Flush Rewrite Rules 
         */
        public function flushRewriteRules() {
            flush_rewrite_rules() ;
        }

    }

}
    
<?php

/**
 * Affiliates Wallet Post Table
 */
if ( !defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( !class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' ) ;
}

if ( !class_exists( 'FS_Affiliates_Wallet_Logs_Post_Table' ) ) {

    /**
     * FS_Affiliates_Wallet_Logs_Post_Table Class.
     * */
    class FS_Affiliates_Wallet_Logs_Post_Table extends WP_List_Table {

        /**
         * Total Count of Table
         * */
        private $total_items ;

        /**
         * Per page count
         * */
        private $perpage ;

        /**
         * Offset
         * */
        private $offset ;

        /**
         * Order BY
         * */
        private $orderby = 'ORDER BY ID DESC' ;

        /**
         * Post type
         * */
        private $post_type = 'fs-wallet-logs' ;

        /**
         * Base URL
         * */
        private $base_url ;

        /**
         * Current URL
         * */
        private $current_url ;

        /**
         * Prepare the table Data to display table based on pagination.
         * */
        public function prepare_items() {
            $this->base_url = add_query_arg( array ( 'page' => 'fs_affiliates' , 'tab' => 'modules' , 'section' => 'affiliate_wallet' ) , admin_url( 'admin.php' ) ) ;

            $this->prepare_current_url() ;
            $this->process_bulk_action() ;
            $this->get_perpage_count() ;
            $this->get_current_pagenum() ;
            $this->get_current_page_items() ;
            $this->prepare_pagination_args() ;
            //display header columns
            $this->prepare_column_headers() ;
        }

        /**
         * get per page count
         * */
        private function get_perpage_count() {

            $this->perpage = 20 ;
        }

        /**
         * Prepare pagination
         * */
        private function prepare_pagination_args() {

            $this->set_pagination_args( array (
                'total_items' => $this->total_items ,
                'per_page'    => $this->perpage
            ) ) ;
        }

        /**
         * get current page number
         * */
        private function get_current_pagenum() {
            $this->offset = 20 * ($this->get_pagenum() - 1) ;
        }

        /**
         * Prepare header columns
         * */
        private function prepare_column_headers() {
            $columns               = $this->get_columns() ;
            $hidden                = $this->get_hidden_columns() ;
            $sortable              = $this->get_sortable_columns() ;
            $this->_column_headers = array ( $columns , $hidden , $sortable ) ;
        }

        /**
         * Initialize the columns
         * */
        public function get_columns() {
            $columns = array (
                'affiliate_id'      => __( 'Affiliate Name' , FS_AFFILIATES_LOCALE ) ,
                'event'             => __( 'Event' , FS_AFFILIATES_LOCALE ) ,
                'earned_balance'    => __( 'Earned balance' , FS_AFFILIATES_LOCALE ) ,
                'used_balance'      => __( 'Used balance' , FS_AFFILIATES_LOCALE ) ,
                'available_balance' => __( 'Available Balance' , FS_AFFILIATES_LOCALE ) ,
                'date'              => __( 'Date' , FS_AFFILIATES_LOCALE ) ,
                    ) ;

            return $columns ;
        }

        /**
         * Initialize the hidden columns
         * */
        public function get_hidden_columns() {
            return array () ;
        }

        /**
         * get current url
         * */
        private function prepare_current_url() {
            //Build row actions
            if ( isset( $_GET[ 'status' ] ) )
                $args[ 'status' ] = $_GET[ 'status' ] ;

            $pagenum         = $this->get_pagenum() ;
            $args[ 'paged' ] = $pagenum ;
            $url             = add_query_arg( $args , $this->base_url ) ;

            $this->current_url = $url ;
        }

        /**
         * Prepare each column data
         * */
        protected function column_default( $item , $column_name ) {
            $WalletObject = new FS_Affiliates_Wallet( $item[ 'ID' ] ) ;
            switch ( $column_name ) {
                case 'affiliate_id':
                    return $WalletObject->affiliate_id ;
                    break ;
                case 'event':
                    return $WalletObject->event ;
                    break ;
                case 'earned_balance':
                    return $WalletObject->earned_balance ;
                    break ;
                case 'used_balance':
                    return $WalletObject->used_balance ;
                    break ;
                case 'available_balance':
                    return $WalletObject->available_balance ;
                    break ;
                case 'date':
                    $date = $WalletObject->date ;
                    return fs_affiliates_local_datetime( $date ) ;
                    break ;
            }
        }

        /**
         * Initialize the columns
         * */
        private function get_current_page_items() {
            global $wpdb ;

            $status            = isset( $_GET[ 'status' ] ) ? ' IN("' . $_GET[ 'status' ] . '")' : ' NOT IN("trash")' ;
            $where             = " where post_type='" . $this->post_type . "' and post_status" . $status ;
            $where             = apply_filters( $this->table_slug . '_query_where' , $where ) ;
            $limit             = apply_filters( $this->table_slug . '_query_limit' , $this->perpage ) ;
            $offset            = apply_filters( $this->table_slug . '_query_offset' , $this->offset ) ;
            $orderby           = apply_filters( $this->table_slug . '_query_orderby' , $this->orderby ) ;
            $prepare_query     = $wpdb->prepare( "SELECT ID FROM " . $wpdb->posts . " $where $orderby LIMIT %d,%d" , $offset , $limit ) ;
            $this->items       = $wpdb->get_results( $prepare_query , ARRAY_A ) ;
            $count_items       = $wpdb->get_results( "SELECT ID FROM " . $wpdb->posts . " $where $orderby" ) ;
            $this->total_items = count( $count_items ) ;
        }

    }

}
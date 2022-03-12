<?php

/**
 * Affiliates Wallet Post Table
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' ) ;
}

if ( ! class_exists( 'FS_Affiliates_Payout_Request_Logs' ) ) {

    /**
     * FS_Affiliates_Payout_Request_Logs Class.
     * */
    class FS_Affiliates_Payout_Request_Logs extends WP_List_Table {

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
        private $post_type = 'fs-payout-request' ;

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
            $this->base_url = add_query_arg( array( 'page' => 'fs_affiliates' , 'tab' => 'modules' , 'section' => 'payout_request' ) , admin_url( 'admin.php' ) ) ;

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

            $this->set_pagination_args( array(
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
            $this->_column_headers = array( $columns , $hidden , $sortable ) ;
        }

        /**
         * Initialize the columns
         * */
        public function get_columns() {
            $columns = array(
                'cb'                => '<input type="checkbox" />' ,
                'ID'                => __( 'ID' , FS_AFFILIATES_LOCALE ) ,
                'affiliate_name'    => __( 'Affiliate' , FS_AFFILIATES_LOCALE ) ,
                'unpaid_commission' => __( 'Total Unpaid Commission' , FS_AFFILIATES_LOCALE ) ,
                'status'            => __( 'Status' , FS_AFFILIATES_LOCALE ) ,
                'requested_date'    => __( 'Requested Date' , FS_AFFILIATES_LOCALE ) ,
                'notes'             => __( 'Notes' , FS_AFFILIATES_LOCALE ) ,
                'closed_date'       => __( 'Closed Date' , FS_AFFILIATES_LOCALE ) ,
                    ) ;

            return $columns ;
        }

        /**
         * Initialize the hidden columns
         * */
        public function get_hidden_columns() {
            return array() ;
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
         * Initialize the bulk actions
         * */
        protected function get_bulk_actions() {
            $action                   = array() ;
            $action[ 'fs_submitted' ] = __( 'Submitted' , FS_AFFILIATES_LOCALE ) ;
            $action[ 'fs_progress' ]  = __( 'In-Progress' , FS_AFFILIATES_LOCALE ) ;
            $action[ 'fs_closed' ]    = __( 'Closed' , FS_AFFILIATES_LOCALE ) ;
            $action[ 'delete' ]       = __( 'Delete' , FS_AFFILIATES_LOCALE ) ;

            return $action ;
        }

        /**
         * add row actions
         * */
        public function column_ID( $item ) {
            $actions = array() ;

            if ( get_post_status( $item[ 'ID' ] ) != 'fs_closed' ) {
                $actions[ 'edit' ]      = sprintf( '<a href="' . $this->base_url . '&subsection=%s&id=%s">' . __( 'Edit' , FS_AFFILIATES_LOCALE ) . '</a>' , 'fs_edit_request' , $item[ 'ID' ] ) ;
                $actions[ 'fs_closed' ] = sprintf( '<a href="' . $this->base_url . '&action=%s&id=%s">' . __( 'Mark as Closed' , FS_AFFILIATES_LOCALE ) . '</a>' , 'fs_closed' , $item[ 'ID' ] ) ;
            }
            $actions[ 'delete' ] = sprintf( '<a href="' . $this->current_url . '&action=%s&id=%s">' . __( 'Delete' , FS_AFFILIATES_LOCALE ) . '</a>' , 'delete' , $item[ 'ID' ] ) ;

            //Return the title contents
            return sprintf( '%1$s %2$s' ,
                    /* $1%s */ '#' . $item[ 'ID' ] ,
                    /* $3%s */ $this->row_actions( $actions )
                    ) ;
        }

        /**
         * Prepare cb column data
         * */
        protected function column_cb( $item ) {
            return sprintf(
                    '<input type="checkbox" name="id[]" value="%s" />' , $item[ 'ID' ]
                    ) ;
        }

        /**
         * bulk action functionality
         * */
        public function process_bulk_action() {

            $ids = isset( $_REQUEST[ 'id' ] ) ? $_REQUEST[ 'id' ] : array() ;
            $ids = ! is_array( $ids ) ? explode( ',' , $ids ) : $ids ;

            if ( ! fs_affiliates_check_is_array( $ids ) )
                return ;

            $action = $this->current_action() ;

            foreach ( $ids as $id ) {

                if ( ! current_user_can( 'edit_post' , $id ) )
                    wp_die( '<p class="fs_affiliates_warning_notice">' . __( 'Sorry, you are not allowed to edit this item.' , FS_AFFILIATES_LOCALE ) . '</p>' ) ;

                if ( 'delete' === $action ) {
                    wp_delete_post( $id , true ) ;
                } elseif ( 'fs_submitted' === $action ) {
                    if ( get_post_status( $id ) != 'fs_closed' && get_post_status( $id ) != 'fs_progress' ) {
                        $post = array(
                            'ID'          => $id ,
                            'post_status' => 'fs_submitted'
                                ) ;
                        wp_update_post( $post ) ;
                    }
                } elseif ( 'fs_progress' === $action ) {
                    if ( get_post_status( $id ) != 'fs_closed' ) {
                        $post = array(
                            'ID'          => $id ,
                            'post_status' => 'fs_progress'
                                ) ;
                        wp_update_post( $post ) ;
                    }
                } elseif ( 'fs_closed' === $action ) {
                    $post = array(
                        'ID'          => $id ,
                        'post_status' => 'fs_closed'
                            ) ;
                    wp_update_post( $post ) ;
                    update_post_meta( $id , 'fs_closed_date' , time() ) ;
                }
                do_action( 'fs_affiliates_status_to_' . $action , $id ) ;
            }

            wp_safe_redirect( $this->current_url ) ;
            exit() ;
        }

        /**
         * Prepare each column data
         * */
        protected function column_default( $item , $column_name ) {
            $payoutrequest     = get_post( $item[ 'ID' ] ) ;
            $affiliate_id      = $payoutrequest->post_author ;
            $affiliates_object = new FS_Affiliates_Data( $affiliate_id ) ;
            switch ( $column_name ) {
                case 'affiliate_name':
                    return $affiliates_object->user_name ;
                    break ;
                case 'unpaid_commission':
                    return fs_affiliates_price( get_post_meta( $item[ 'ID' ] , 'fs_affiliates_unpaid_commission' , true ) ) ;
                    break ;
                case 'status':
                    if ( get_post_status( $item[ 'ID' ] ) == 'fs_submitted' ) {
                        return __( 'Submitted' , FS_AFFILIATES_LOCALE ) ;
                    } elseif ( get_post_status( $item[ 'ID' ] ) == 'fs_progress' ) {
                        return __( 'In-Progess' , FS_AFFILIATES_LOCALE ) ;
                    } else {
                        return __( 'Closed' , FS_AFFILIATES_LOCALE ) ;
                    }
                    return get_post_status( $item[ 'ID' ] ) ;
                    break ;
                case 'requested_date':
                    return $payoutrequest->post_date ;
                    break ;
                case 'notes':
                    return empty( $payoutrequest->post_content ) ? '-' : $payoutrequest->post_content ;
                    break ;
                case 'closed_date':
                    $ClosedDate = get_post_meta( $item[ 'ID' ] , 'fs_closed_date' , true ) ;
                    return empty( $ClosedDate ) ? '-' : date( 'Y-m-d h:i:s' , $ClosedDate ) ;
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
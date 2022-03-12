<?php

/**
 * Affiliates Payouts Post Table
 */
if ( !defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( !class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' ) ;
}

if ( !class_exists( 'FS_Affiliates_Payouts_Batch_Post_Table' ) ) {

    /**
     * FS_Affiliates_Payouts_Batch_Post_Table Class.
     * */
    class FS_Affiliates_Payouts_Batch_Post_Table extends WP_List_Table {

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
        private $post_type = 'fs-payouts-batch' ;

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
            $this->base_url = add_query_arg( array ( 'page' => 'fs_affiliates' , 'tab' => 'payouts' ) , admin_url( 'admin.php' ) ) ;

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
                'cb'           => '<input type="checkbox" />' , //Render a checkbox instead of text
                'batch_id'     => __( 'PayPal Payouts Batch ID' , FS_AFFILIATES_LOCALE ) ,
                'status'       => __( 'Status' , FS_AFFILIATES_LOCALE ) ,
                'check_status' => __( 'Check Payout Status' , FS_AFFILIATES_LOCALE ) ,
                'date'         => __( 'Date' , FS_AFFILIATES_LOCALE ) ,
                    ) ;

            return $columns ;
        }

        /**
         * Initialize the sortable columns
         * */
        public function get_sortable_columns() {
            return array (
                'batch_id' => array ( 'batch_id' , false ) ,
                'status'   => array ( 'status' , false ) ,
                'date'     => array ( 'date' , false ) ,
                    ) ;
        }

        /**
         * Initialize the hidden columns
         * */
        public function get_hidden_columns() {
            return array () ;
        }

        /**
         * Initialize the bulk actions
         * */
        protected function get_bulk_actions() {
            $action = array () ;
            if ( isset( $_GET[ 'status' ] ) && $_GET[ 'status' ] == 'trash' ) {
                $action[ 'restore' ] = __( 'Restore' , FS_AFFILIATES_LOCALE ) ;
                $action[ 'delete' ]  = __( 'Delete' , FS_AFFILIATES_LOCALE ) ;
            } else {
                $action[ 'trash' ] = __( 'Move to Trash' , FS_AFFILIATES_LOCALE ) ;
            }

            return $action ;
        }

        /**
         * Display the list of views available on this table.
         * */
        public function get_views() {
            $args        = array () ;
            $status_link = array () ;

            $status_link_array = array (
                ''        => __( 'All' , FS_AFFILIATES_LOCALE ) ,
                'fs_paid' => __( 'Paid' , FS_AFFILIATES_LOCALE ) ,
                'trash'   => __( 'Trash' , FS_AFFILIATES_LOCALE ) ,
                    ) ;

            foreach ( $status_link_array as $status_name => $status_label ) {
                $status_count = $this->get_total_item_for_status( $status_name ) ;

                if ( !$status_count )
                    continue ;

                if ( $status_name )
                    $args[ 'status' ] = $status_name ;

                $label                       = $status_label . ' (' . $status_count . ')' ;
                $class                       = (isset( $_GET[ 'status' ] ) && $_GET[ 'status' ] == $status_name ) ? 'current' : '' ;
                $class                       = (!isset( $_GET[ 'status' ] ) && '' == $status_name ) ? 'current' : $class ;
                $status_link[ $status_name ] = $this->get_edit_link( $args , $label , $class ) ;
            }

            return $status_link ;
        }

        /**
         * Edit link for status 
         * */
        private function get_edit_link( $args , $label , $class = '' ) {
            $url        = add_query_arg( $args , $this->base_url ) ;
            $class_html = '' ;
            if ( !empty( $class ) ) {
                $class_html = sprintf(
                        ' class="%s"' , esc_attr( $class )
                        ) ;
            }

            return sprintf(
                    '<a href="%s"%s>%s</a>' , esc_url( $url ) , $class_html , $label
                    ) ;
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
         * add row actions
         * */
        public function column_ID( $item ) {
            $actions = array () ;
            if ( isset( $_GET[ 'status' ] ) && $_GET[ 'status' ] == 'trash' ) {
                $actions = array (
                    'delete'  => sprintf( '<a href="' . $this->current_url . '&action=%s&id=%s">' . __( 'Delete' , FS_AFFILIATES_LOCALE ) . '</a>' , 'delete' , $item[ 'ID' ] ) ,
                    'restore' => sprintf( '<a href="' . $this->current_url . '&action=%s&id=%s">' . __( 'Restore' , FS_AFFILIATES_LOCALE ) . '</a>' , 'restore' , $item[ 'ID' ] ) ,
                        ) ;
            } else {
                $actions[ 'edit' ]   = sprintf( '<a href="' . $this->base_url . '&section=%s&id=%s">' . __( 'Edit' , FS_AFFILIATES_LOCALE ) . '</a>' , 'edit' , $item[ 'ID' ] ) ;
                $actions [ 'trash' ] = sprintf( '<a href="' . $this->current_url . '&action=%s&id=%s">' . __( 'Trash' , FS_AFFILIATES_LOCALE ) . '</a>' , 'trash' , $item[ 'ID' ] ) ;
            }

            //Return the title contents
            return sprintf( '%1$s %2$s' ,
                    /* $1%s */ $item[ 'ID' ] ,
                    /* $3%s */ $this->row_actions( $actions )
                    ) ;
        }

        /**
         * bulk action functionality
         * */
        public function process_bulk_action() {

            $ids = isset( $_REQUEST[ 'id' ] ) ? $_REQUEST[ 'id' ] : array () ;
            $ids = !is_array( $ids ) ? explode( ',' , $ids ) : $ids ;

            if ( !fs_affiliates_check_is_array( $ids ) )
                return ;

            $action = $this->current_action() ;

            foreach ( $ids as $id ) {

                if ( !current_user_can( 'edit_post' , $id ) )
                    wp_die( '<p class="fs_affiliates_warning_notice">' . __( 'Sorry, you are not allowed to edit this item.' , FS_AFFILIATES_LOCALE ) . '</p>' ) ;

                if ( 'delete' === $action ) {
                    wp_delete_post( $id , true ) ;
                } elseif ( 'trash' === $action ) {
                    wp_trash_post( $id ) ;
                } elseif ( 'restore' === $action ) {
                    wp_untrash_post( $id ) ;
                }
            }

            wp_safe_redirect( $this->current_url ) ;
            exit() ;
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
         * Prepare each column data
         * */
        protected function column_default( $item , $column_name ) {
            $batch_header = get_post_meta( $item[ 'ID' ] , '_payout_batch_header' , true ) ;

            switch ( $column_name ) {
                case 'batch_id':
                    return !empty( $batch_header[ 'payout_batch_id' ] ) ? $batch_header[ 'payout_batch_id' ] : '--' ;
                    break ;
                case 'status':
                    return fs_affiliates_get_status_display( get_post_status( $item[ 'ID' ] ) ) ;
                    break ;
                case 'check_status':
                    printf( __( '<a href=%s>%s</a>' ) , add_query_arg( array ( 'action' => 'check_payout_status' , 'sender_batch_id' => $item[ 'ID' ] ) , $this->current_url ) , __( 'Check Payout Status' , FS_AFFILIATES_LOCALE ) ) ;
                    break ;
                case 'date':
                    return !empty( $batch_header[ 'time_completed' ] ) ? $batch_header[ 'time_completed' ] : '--' ;
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

        /**
         * get total item from status
         * */
        private function get_total_item_for_status( $status = '' ) {
            global $wpdb ;
            $where  = "WHERE post_type='" . $this->post_type . "' and post_status" ;
            $status = ($status == '') ? "NOT IN('trash')" : "IN('" . $status . "')" ;
            $data   = $wpdb->get_results( "SELECT ID FROM " . $wpdb->posts . " $where $status" , ARRAY_A ) ;

            return count( $data ) ;
        }

    }

}
<?php

/**
 * Affiliates Post Table
 */
if ( !defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( !class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' ) ;
}

if ( !class_exists( 'FS_Affiliates_Post_Table' ) ) {

    /**
     * FS_Affiliates_Post_Table Class.
     * */
    class FS_Affiliates_Post_Table extends WP_List_Table {

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
        private $post_type = 'fs-affiliates' ;

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
            $this->base_url = add_query_arg( array ( 'page' => 'fs_affiliates' , 'tab' => 'affiliates' ) , admin_url( 'admin.php' ) ) ;

            add_filter( $this->table_slug . '_query_where' , array ( $this , 'custom_search' ) , 10 , 1 ) ;

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
                'ID'                => __( 'Affiliate ID' , FS_AFFILIATES_LOCALE ) ,
                'user_name'         => __( 'Username' , FS_AFFILIATES_LOCALE ) ,
                'first_name'        => __( 'Name' , FS_AFFILIATES_LOCALE ) ,
                'email'             => __( 'Email ID' , FS_AFFILIATES_LOCALE ) ,
                'referrals'         => __( 'Referrals' , FS_AFFILIATES_LOCALE ) ,
                'visits'            => __( 'Visits' , FS_AFFILIATES_LOCALE ) ,
                'paid_commission'   => __( 'Paid Commission' , FS_AFFILIATES_LOCALE ) ,
                'unpaid_commission' => __( 'Unpaid Commission' , FS_AFFILIATES_LOCALE ) ,
                'status'            => __( 'Status' , FS_AFFILIATES_LOCALE )
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
         * Initialize the bulk actions
         * */
        protected function get_bulk_actions() {
            $action = array () ;

            return $action ;
        }

        /**
         * Display the list of views available on this table.
         * */
        public function get_views() {
            $args        = array () ;
            $status_link = array () ;

            $status_link_array = array (
                ''                    => __( 'All' , FS_AFFILIATES_LOCALE ) ,
                'fs_active'           => __( 'Active' , FS_AFFILIATES_LOCALE ) ,
                'fs_pending_approval' => __( 'Pending Approval' , FS_AFFILIATES_LOCALE ) ,
                'fs_suspended'        => __( 'Suspended' , FS_AFFILIATES_LOCALE ) ,
                'fs_rejected'         => __( 'Rejected' , FS_AFFILIATES_LOCALE ) ,
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

            $actions[ 'edit' ]    = sprintf( '<a href="' . $this->base_url . '&section=%s&id=%s">' . __( 'Edit' , FS_AFFILIATES_LOCALE ) . '</a>' , 'edit' , $item->get_id() ) ;
            $actions [ 'delete' ] = sprintf( '<a class="fs_affiliates_delete" data-type="affiliate" href="' . $this->current_url . '&action=%s&id=%s">' . __( 'Delete' , FS_AFFILIATES_LOCALE ) . '</a>' , 'delete' , $item->get_id() ) ;

            //Return the title contents
            return sprintf( '%1$s %2$s' ,
                    /* $1%s */ '#' . $item->get_id() ,
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
                    fs_affiliates_delete_affiliate( $id ) ;
                } elseif ( 'fs_active' === $action ) {
                    wp_update_post( array ( 'ID' => $id , 'post_status' => $action ) ) ;

                    do_action( 'fs_affiliates_status_changed' , $id ) ;
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
                    '<input type="checkbox" name="id[]" value="%s" />' , $item->get_id()
                    ) ;
        }

        /**
         * Prepare each column data
         * */
        protected function column_default( $item , $column_name ) {

            switch ( $column_name ) {
                case 'email':
                    return $item->email ;
                    break ;
                case 'first_name':
                    return $item->first_name . ' ' . $item->last_name ;
                    break ;
                case 'visits':
                    return $item->get_visits_count() ;
                    break ;
                case 'referrals':
                    return $item->get_referrals_count() ;
                    break ;
                case 'paid_commission':
                    return $item->get_paid_commission() ;
                    break ;
                case 'unpaid_commission':
                    return $item->get_unpaid_commission() ;
                    break ;
                case 'status':
                    return fs_affiliates_get_status_display( $item->get_status() ) ;
                    break ;
                case'user_name':
                    return $item->user_name ;
                    break ;
            }
        }

        /**
         * Initialize the columns
         * */
        private function get_current_page_items() {
            global $wpdb ;

            $status  = isset( $_GET[ 'status' ] ) ? ' IN("' . $_GET[ 'status' ] . '")' : ' NOT IN("trash")' ;
            $where   = " where post_type='" . $this->post_type . "' and post_status" . $status ;
            $where   = apply_filters( $this->table_slug . '_query_where' , $where ) ;
            $limit   = apply_filters( $this->table_slug . '_query_limit' , $this->perpage ) ;
            $offset  = apply_filters( $this->table_slug . '_query_offset' , $this->offset ) ;
            $orderby = apply_filters( $this->table_slug . '_query_orderby' , $this->orderby ) ;

            $count_items       = $wpdb->get_results( "SELECT ID FROM " . $wpdb->posts . " $where $orderby" ) ;
            $this->total_items = count( $count_items ) ;

            $prepare_query = $wpdb->prepare( "SELECT ID FROM " . $wpdb->posts . " $where $orderby LIMIT %d,%d" , $offset , $limit ) ;
            $items         = $wpdb->get_results( $prepare_query , ARRAY_A ) ;

            $this->prepare_item_object( $items ) ;
        }

        /**
         * Prepare item Object
         * */
        private function prepare_item_object( $items ) {
            $prepare_items = array () ;
            if ( fs_affiliates_check_is_array( $items ) ) {
                foreach ( $items as $item ) {
                    $prepare_items[] = new FS_Affiliates_Data( $item[ 'ID' ] ) ;
                }
            }

            $this->items = $prepare_items ;
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

        /**
         * Search Functionality
         * */
        public function custom_search( $where ) {
            global $wpdb ;
            if ( isset( $_REQUEST[ 's' ] ) ) {
                $search_ids = array () ;
                $terms      = explode( ',' , $_REQUEST[ 's' ] ) ;

                foreach ( $terms as $term ) {
                    $term          = $wpdb->esc_like( $term ) ;
                    $meta_array    = array (
                        'first_name' ,
                        'last_name' ,
                        'email' ,
                            ) ;
                    $implode_array = implode( "','" , $meta_array ) ;
                    if ( isset( $_GET[ 'post_status' ] ) && $_GET[ 'post_status' ] != 'all' ) {
                        $post_status = $_GET[ 'post_status' ] ;
                    } else {
                        $post_status_array = array ( 'fs_active' , 'fs_inactive' , 'fs_rejected' , 'fs_suspended' , 'fs_pending_approval' ) ;
                        $post_status       = implode( "','" , $post_status_array ) ;
                    }

                    $search_ids = $wpdb->get_col( $wpdb->prepare(
                                    "SELECT DISTINCT ID FROM {$wpdb->posts} as p "
                                    . "INNER JOIN {$wpdb->postmeta} as pm ON p.ID = pm.post_id "
                                    . "WHERE p.post_type=%s AND p.post_status IN ('$post_status') AND (("
                                    . "pm.meta_key IN ('$implode_array') "
                                    . "AND pm.meta_value LIKE %s) OR (p.ID LIKE %s) OR (p.post_title LIKE %s))" , $this->post_type , '%' . $term . '%' , '%' . $term . '%' , '%' . $term . '%' )
                            ) ;
                }

                $search_ids = array_filter( array_unique( array_map( 'absint' , $search_ids ) ) ) ;

                $search_ids = fs_affiliates_check_is_array( $search_ids ) ? $search_ids : array ( 0 ) ;

                $where .= " AND ({$wpdb->posts}.ID IN (" . implode( ',' , $search_ids ) . "))" ;
            }

            return $where ;
        }

    }

}
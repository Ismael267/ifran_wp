<?php

/**
 * Affiliates Visits Post Table
 */
if ( !defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( !class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' ) ;
}

if ( !class_exists( 'FS_Affiliates_Visits_Post_Table' ) ) {

    /**
     * FS_Affiliates_Visits_Post_Table Class.
     * */
    class FS_Affiliates_Visits_Post_Table extends WP_List_Table {

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
        private $post_type = 'fs-visits' ;

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
            $this->base_url = add_query_arg( array ( 'page' => 'fs_affiliates' , 'tab' => 'visits' ) , admin_url( 'admin.php' ) ) ;

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
                'cb'           => '<input type="checkbox" />' , //Render a checkbox instead of text
                'ID'           => __( 'Visit ID' , FS_AFFILIATES_LOCALE ) ,
                'landing_page' => __( 'URL' , FS_AFFILIATES_LOCALE ) ,
                'campaign'     => __( 'Campaign' , FS_AFFILIATES_LOCALE ) ,
                'referral_url' => __( 'Referring URL' , FS_AFFILIATES_LOCALE ) ,
                'ip_address'   => __( 'IP Address' , FS_AFFILIATES_LOCALE ) ,
                'affiliate'    => __( 'Affiliate Username/ID' , FS_AFFILIATES_LOCALE ) ,
                'status'       => __( 'Converted' , FS_AFFILIATES_LOCALE ) ,
                'referral_id'  => __( 'Referral ID' , FS_AFFILIATES_LOCALE ) ,
                'date'         => __( 'Date' , FS_AFFILIATES_LOCALE ) ,
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
                ''                => __( 'All' , FS_AFFILIATES_LOCALE ) ,
                'fs_converted'    => __( 'Converted' , FS_AFFILIATES_LOCALE ) ,
                'fs_notconverted' => __( 'Not Converted' , FS_AFFILIATES_LOCALE ) ,
                'trash'           => __( 'Trash' , FS_AFFILIATES_LOCALE ) ,
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
                    'delete'  => sprintf( '<a href="' . $this->current_url . '&action=%s&id=%s">' . __( 'Delete' , FS_AFFILIATES_LOCALE ) . '</a>' , 'delete' , $item->get_id() ) ,
                    'restore' => sprintf( '<a href="' . $this->current_url . '&action=%s&id=%s">' . __( 'Restore' , FS_AFFILIATES_LOCALE ) . '</a>' , 'restore' , $item->get_id() ) ,
                        ) ;
            } else {
                $actions [ 'trash' ] = sprintf( '<a href="' . $this->current_url . '&action=%s&id=%s">' . __( 'Trash' , FS_AFFILIATES_LOCALE ) . '</a>' , 'trash' , $item->get_id() ) ;
            }


            //Return the title contents
            return sprintf( '%1$s %2$s' ,
                    /* $1%s */ $item->get_id() ,
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
                    '<input type="checkbox" name="id[]" value="%s" />' , $item->get_id()
                    ) ;
        }

        /**
         * Prepare each column data
         * */
        protected function column_default( $item , $column_name ) {

            switch ( $column_name ) {
                case 'landing_page':
                    return $item->landing_page ;
                    break ;
                case 'referral_url':
                    return $item->referral_url ;
                    break ;
                case 'affiliate':
                    return get_the_title( $item->affiliate ) ;
                    break ;
                case 'ip_address':
                    return $item->ip_address ;
                    break ;
                case 'referral_id':
                    return $item->referral_id ;
                    break ;
                case 'campaign':
                    return $item->campaign ;
                    break ;
                case 'status':
                    return fs_affiliates_get_status_display( $item->get_status() ) ;
                    break ;
                case 'date':
                    return fs_affiliates_local_datetime( $item->date ) ;
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
                    $prepare_items[] = new FS_Affiliates_Visits( $item[ 'ID' ] ) ;
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
                        'landing_page' ,
                        'referral_url' ,
                        'ip_address' ,
                        'referral_id' ,
                            ) ;
                    $implode_array = implode( "','" , $meta_array ) ;
                    if ( isset( $_GET[ 'post_status' ] ) && $_GET[ 'post_status' ] != 'all' ) {
                        $post_status = $_GET[ 'post_status' ] ;
                    } else {
                        $post_status_array = array ( 'fs_notconverted' , 'fs_converted' ) ;
                        $post_status       = implode( "','" , $post_status_array ) ;
                    }

                    $user_displayname_search = $wpdb->get_col(
                            $wpdb->prepare( "SELECT DISTINCT p1.ID FROM $wpdb->posts as p1 
                                 INNER JOIN $wpdb->posts as p2 ON p1.post_author=p2.ID 
                                 WHERE p1.post_type=%s AND p1.post_status IN ('$post_status') "
                                    . "AND p2.post_type='fs-affiliates'"
                                    . "AND p2.post_title LIKE %s" , $this->post_type , '%' . $term . '%' )
                            ) ;


                    $search_ids = $wpdb->get_col( $wpdb->prepare(
                                    "SELECT DISTINCT ID FROM {$wpdb->posts} as p "
                                    . "INNER JOIN {$wpdb->postmeta} as pm ON p.ID = pm.post_id "
                                    . "WHERE p.post_type=%s AND p.post_status IN ('$post_status') AND (("
                                    . "pm.meta_key IN ('$implode_array') "
                                    . "AND pm.meta_value LIKE %s) OR (p.ID LIKE %s))" , $this->post_type , '%' . $term . '%' , '%' . $term . '%' )
                            ) ;
                }

                $search_ids = array_merge( $search_ids , $user_displayname_search ) ;

                $search_ids = array_filter( array_unique( array_map( 'absint' , $search_ids ) ) ) ;

                $search_ids = fs_affiliates_check_is_array( $search_ids ) ? $search_ids : array ( 0 ) ;

                $where .= " AND ({$wpdb->posts}.ID IN (" . implode( ',' , $search_ids ) . "))" ;
            }

            return $where ;
        }

    }

}
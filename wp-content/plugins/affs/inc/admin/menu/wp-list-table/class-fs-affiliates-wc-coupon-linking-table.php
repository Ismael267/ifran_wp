<?php

/**
 * WC Coupon Linking Post Table
 */
if ( !defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( !class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' ) ;
}

if ( !class_exists( 'FS_Affiliates_WC_Coupon_Linking_Post_Table' ) ) {

    /**
     * FS_Affiliates_WC_Coupon_Linking_Post_Table Class.
     * */
    class FS_Affiliates_WC_Coupon_Linking_Post_Table extends WP_List_Table {

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
        private $post_type = 'fs-coupon-linking' ;

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
            $this->base_url = add_query_arg( array ( 'page' => 'fs_affiliates' , 'tab' => 'modules' , 'section' => 'wc_coupon_linking' ) , admin_url( 'admin.php' ) ) ;

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
                'coupon_code'  => __( 'Coupon Code' , FS_AFFILIATES_LOCALE ) ,
                'coupon_value' => __( 'Coupon Value' , FS_AFFILIATES_LOCALE ) ,
                'affiliate_id' => __( 'Linked Affiliate' , FS_AFFILIATES_LOCALE ) ,
                'action'       => __( 'Action' , FS_AFFILIATES_LOCALE ) ,
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
         * Initialize the bulk actions
         * */
        protected function get_bulk_actions() {
            $action                = array () ;
            $action[ 'fs_link' ]   = __( 'Link' , FS_AFFILIATES_LOCALE ) ;
            $action[ 'fs_unlink' ] = __( 'Unlink' , FS_AFFILIATES_LOCALE ) ;
            $action[ 'delete' ]    = __( 'Delete' , FS_AFFILIATES_LOCALE ) ;

            return $action ;
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

                $CouponLinkedObj = new FS_Linked_Affiliates_Data( $id ) ;
                if ( 'delete' === $action ) {
                    wp_delete_post( $id , true ) ;
                } elseif ( 'fs_link' === $action ) {
                    $CouponLinkedObj->update_status( 'fs_link' ) ;
                } elseif ( 'fs_unlink' === $action ) {
                    $CouponLinkedObj->update_status( 'fs_unlink' ) ;
                }
            }

            wp_safe_redirect( $this->current_url ) ;
            exit() ;
        }

        /**
         * Prepare each column data
         * */
        protected function column_default( $item , $column_name ) {
            $CouponId   = get_post_meta( $item->get_id() , 'coupon_data' , true ) ;
            $CouponCode = !empty( $CouponId ) ? get_the_title( $CouponId ) : '' ;
            $DiscountType = get_post_meta( $CouponId , 'discount_type' , true );
            $coupon_value = ($DiscountType != 'percent') ? fs_affiliates_price( get_post_meta( $CouponId , 'coupon_amount' , true ) ) : get_post_meta( $CouponId , 'coupon_amount' , true ) . ' %' ;

            switch ( $column_name ) {
                case 'coupon_code':
                    return $CouponCode ;
                    break ;
                case 'coupon_value':
                    return $coupon_value;
                    break ;
                case 'affiliate_id':
                    $AffiliateId  = $item->post_author ;
                    $AffiliateObj = new FS_Affiliates_Data( $AffiliateId ) ;
                    return $AffiliateObj->user_name ;
                    break ;
                case 'action':
                    $actions      = array () ;
                    if ( $item->get_status() == 'fs_link' ) {
                        $actions[ 'fs_unlink' ] = fs_affiliates_get_action_display( 'fs_unlink' , $item->get_id() , $this->current_url ) ;
                    } else {
                        $actions[ 'fs_link' ] = fs_affiliates_get_action_display( 'fs_link' , $item->get_id() , $this->current_url ) ;
                    }
                    $actions[ 'edit' ]   = sprintf( '<a href="' . $this->base_url . '&subsection=%s&id=%s">' . __( 'Edit' , FS_AFFILIATES_LOCALE ) . '</a>' , 'edit_linking' , $item->get_id() ) ;
                    $actions[ 'delete' ] = sprintf( '<a style="color:red !important;" href="' . $this->base_url . '&action=%s&id=%s">' . __( 'Delete Permanantly' , FS_AFFILIATES_LOCALE ) . '</a>' , 'delete' , $item->get_id() ) ;
                    foreach ( $actions as $key => $action ) {
                        echo $action . ' | ' ;
                    }
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

            $this->prepare_item_object( $this->items ) ;
        }

        /**
         * Prepare item Object
         * */
        private function prepare_item_object( $items ) {
            $prepare_items = array () ;
            if ( fs_affiliates_check_is_array( $items ) ) {
                foreach ( $items as $item ) {
                    $prepare_items[] = new FS_Linked_Affiliates_Data( $item[ 'ID' ] ) ;
                }
            }

            $this->items = $prepare_items ;
        }

    }

}

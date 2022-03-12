<?php

/**
 * Form Fields Post Table
 */
if ( !defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( !class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' ) ;
}

if ( !class_exists( 'FS_Affiliates_Form_Fields_Post_Table' ) ) {

    /**
     * FS_Affiliates_Form_Fields_Post_Table Class.
     * */
    class FS_Affiliates_Form_Fields_Post_Table extends WP_List_Table {

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
            $this->base_url = add_query_arg( array ( 'page' => 'fs_affiliates' , 'tab' => 'settings' , 'section' => 'frontend_form' ) , admin_url( 'admin.php' ) ) ;

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
                'cb'             => '<input type="checkbox" />' , //Render a checkbox instead of text
                'field_name'     => __( 'Field Name' , FS_AFFILIATES_LOCALE ) ,
                'field_status'   => __( 'Field Status' , FS_AFFILIATES_LOCALE ) ,
                'field_required' => __( 'Field Type' , FS_AFFILIATES_LOCALE ) ,
                'sort'           => __( 'Sort' , FS_AFFILIATES_LOCALE ) ,
                    ) ;

            return $columns ;
        }

        /**
         * Initialize the sortable columns
         * */
        public function get_sortable_columns() {
            return array () ;
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

            $action[ 'enabled' ]  = __( 'Enable' , FS_AFFILIATES_LOCALE ) ;
            $action[ 'disabled' ] = __( 'Disable' , FS_AFFILIATES_LOCALE ) ;

            return $action ;
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
        public function column_field_name( $item ) {
            $actions             = array () ;
            $action_status       = ( $item[ 'field_status' ] == 'enabled' ) ? 'disabled' : 'enabled' ;
            $action_status_label = ( $item[ 'field_status' ] == 'enabled' ) ? __( 'Disable' , FS_AFFILIATES_LOCALE ) : __( 'Enable' , FS_AFFILIATES_LOCALE ) ;
            $actions[ 'edit' ]   = sprintf( '<a href="' . $this->base_url . '&subsection=%s&id=%s">' . __( 'Edit' , FS_AFFILIATES_LOCALE ) . '</a>' , 'edit' , $item[ 'field_key' ] ) ;

            $disabled_fileds      = array ( 'email' , 'user_name' , 'password' ) ;
            if ( !in_array( $item[ 'field_key' ] , $disabled_fileds ) )
                $actions [ 'status' ] = sprintf( '<a href="' . $this->current_url . '&action=%s&id=%s">' . $action_status_label . '</a>' , $action_status , $item[ 'field_key' ] ) ;

            //Return the title contents
            return sprintf( '%1$s %2$s' ,
                    /* $1%s */ $item[ 'field_name' ] ,
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

            $action          = $this->current_action() ;
            $fields          = fs_affiliates_get_form_fields() ;
            $disabled_fileds = array ( 'email' , 'user_name' , 'password' ) ;

            foreach ( $ids as $id ) {

                if ( !isset( $fields[ $id ] ) )
                    continue ;

                if ( in_array( $id , $disabled_fileds ) )
                    continue ;

                $field = $fields[ $id ] ;
                if ( 'disabled' === $action ) {
                    $field[ 'field_status' ] = 'disabled' ;
                } elseif ( 'enabled' === $action ) {
                    $field[ 'field_status' ] = 'enabled' ;
                }

                $fields[ $id ] = $field ;
            }

            update_option( 'fs_affiliates_frontend_form_fields' , $fields ) ;

            wp_safe_redirect( $this->current_url ) ;
            exit() ;
        }

        /**
         * Prepare cb column data
         * */
        protected function column_cb( $item ) {
            $disabled_fileds = array ( 'email' , 'user_name' , 'password' ) ;

            $disabled = (in_array( $item[ 'field_key' ] , $disabled_fileds )) ? "disabled='disabled'" : '' ;

            return sprintf(
                    '<input type="checkbox" class="fs_affiliates_sortable" name="id[]" value="%s" ' . $disabled . ' />' , $item[ 'field_key' ]
                    ) ;
        }

        /**
         * Prepare each column data
         * */
        protected function column_default( $item , $column_name ) {

            switch ( $column_name ) {
                case 'field_status':
                    return ucfirst( $item[ 'field_status' ] ) ;
                    break ;
                case 'field_required':
                    return ucfirst( $item[ 'field_required' ] ) ;
                    break ;
                case 'sort':
                    return '<div class="fs_affiliates_fields_sort_handle"><i class="fa fa-bars" ></i></div>' ;
                    break ;
            }
        }

        /**
         * Initialize the columns
         * */
        private function get_current_page_items() {
            $fields            = fs_affiliates_get_form_fields() ;
            $this->total_items = count( $fields ) ;
            $this->items       = $fields ;
        }

    }

}
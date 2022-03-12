<?php

/**
 * Register Custom Post Status.
 */
if ( !defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( !class_exists( 'FS_Affiliates_Register_Post_Status' ) ) {

    /**
     * FS_Affiliates_Register_Post_Status Class.
     */
    class FS_Affiliates_Register_Post_Status {

        /**
         * Class initialization.
         */
        public static function init() {
            add_action( 'init' , array ( __CLASS__ , 'register_custom_post_status' ) ) ;
        }

        public static function register_custom_post_status() {
            $array = array(
                'fs_hold'             => array( 'FS_Affiliates_Register_Post_Status' , 'fs_hold_post_status_args' ) ,
                'fs_paid'             => array( 'FS_Affiliates_Register_Post_Status' , 'fs_paid_post_status_args' ) ,
                'fs_submitted'        => array( 'FS_Affiliates_Register_Post_Status' , 'fs_submitted_post_status_args' ) ,
                'fs_progress'         => array( 'FS_Affiliates_Register_Post_Status' , 'fs_progress_post_status_args' ) ,
                'fs_closed'           => array( 'FS_Affiliates_Register_Post_Status' , 'fs_closed_post_status_args' ) ,
                'fs_active'           => array( 'FS_Affiliates_Register_Post_Status' , 'fs_active_post_status_args' ) ,
                'fs_inactive'         => array( 'FS_Affiliates_Register_Post_Status' , 'fs_inactive_post_status_args' ) ,
                'fs_unpaid'           => array( 'FS_Affiliates_Register_Post_Status' , 'fs_unpaid_post_status_args' ) ,
                'fs_notconverted'     => array( 'FS_Affiliates_Register_Post_Status' , 'fs_notconverted_post_status_args' ) ,
                'fs_converted'        => array( 'FS_Affiliates_Register_Post_Status' , 'fs_converted_post_status_args' ) ,
                'fs_rejected'         => array( 'FS_Affiliates_Register_Post_Status' , 'fs_rejected_post_status_args' ) ,
                'fs_suspended'        => array( 'FS_Affiliates_Register_Post_Status' , 'fs_suspended_post_status_args' ) ,
                'fs_pending_approval' => array( 'FS_Affiliates_Register_Post_Status' , 'fs_pending_approval_post_type_args' ) ,
                'fs_link'             => array( 'FS_Affiliates_Register_Post_Status' , 'fs_link_post_type_args' ) ,
                'fs_unlink'           => array( 'FS_Affiliates_Register_Post_Status' , 'fs_unlink_post_type_args' ) ,
                'fs_in_progress'      => array( 'FS_Affiliates_Register_Post_Status' , 'fs_in_progress_post_type_args' ) ,
                'fs_pending_payment'  => array( 'FS_Affiliates_Register_Post_Status' , 'fs_pending_payment_post_type_args' ) ,
                'fs_cancelled'        => array( 'FS_Affiliates_Register_Post_Status' , 'fs_cancelled_post_type_args' ) ,
                    ) ;

            $array = apply_filters( 'fpgdpr_add_custom_post_status' , $array ) ;

            foreach ( $array as $post_name => $args_function ) {
                $args = call_user_func_array( $args_function , array () ) ;
                register_post_status( $post_name , $args ) ;
            }

            $paypal_payout_statuses = array (
                'fs_acknowledged' => __( 'Acknowledged' , FS_AFFILIATES_LOCALE ) ,
                'fs_denied'       => __( 'Denied' , FS_AFFILIATES_LOCALE ) ,
                'fs_pending'      => __( 'Pending' , FS_AFFILIATES_LOCALE ) ,
                'fs_processing'   => __( 'Processing' , FS_AFFILIATES_LOCALE ) ,
                'fs_success'      => __( 'Success' , FS_AFFILIATES_LOCALE ) ,
                'fs_new'          => __( 'New' , FS_AFFILIATES_LOCALE ) ,
                'fs_cancelled'    => __( 'Cancelled' , FS_AFFILIATES_LOCALE )
                    ) ;

            foreach ( $paypal_payout_statuses as $payout_status => $payout_status_display_name ) {
                register_post_status( $payout_status , array (
                    'label'                     => $payout_status_display_name ,
                    'public'                    => true ,
                    'exclude_from_search'       => false ,
                    'show_in_admin_status_list' => true ,
                    'show_in_admin_all_list'    => true ,
                    'label_count'               => _n_noop( $payout_status_display_name . ' <span class="count">(%s)</span>' , $payout_status_display_name . ' <span class="count">(%s)</span>' ) ,
                ) ) ;
            }
        }
        
        public static function fs_pending_payment_post_type_args() {
            $args = apply_filters( 'fs_pending_payment_post_type_args' , array (
                'label'                     => _x( 'Pending Payment' , FS_AFFILIATES_LOCALE ) ,
                'public'                    => true ,
                'exclude_from_search'       => false ,
                'show_in_admin_all_list'    => true ,
                'show_in_admin_status_list' => true ,
                'label_count'               => _n_noop( 'Pending Payment <span class="count">(%s)</span>' , 'Pending Payment <span class="count">(%s)</span>' ) ,
                    )
                    ) ;
            return $args ;
        }
        
        public static function fs_cancelled_post_type_args() {
            $args = apply_filters( 'fs_cancelled_post_type_args' , array (
                'label'                     => _x( 'Cancelled' , FS_AFFILIATES_LOCALE ) ,
                'public'                    => true ,
                'exclude_from_search'       => false ,
                'show_in_admin_all_list'    => true ,
                'show_in_admin_status_list' => true ,
                'label_count'               => _n_noop( 'Cancelled <span class="count">(%s)</span>' , 'Cancelled <span class="count">(%s)</span>' ) ,
                    )
                    ) ;
            return $args ;
        }

        public static function fs_paid_post_status_args() {
            $args = apply_filters( 'fs_paid_post_status_args' , array (
                'label'                     => _x( 'Paid' , FS_AFFILIATES_LOCALE ) ,
                'public'                    => true ,
                'exclude_from_search'       => false ,
                'show_in_admin_all_list'    => true ,
                'show_in_admin_status_list' => true ,
                'label_count'               => _n_noop( 'Paid <span class="count">(%s)</span>' , 'Paid <span class="count">(%s)</span>' ) ,
                    )
                    ) ;
            return $args ;
        }

        public static function fs_submitted_post_status_args() {
            $args = apply_filters( 'fs_submitted_post_status_args' , array(
                'label'                     => _x( 'Submitted' , FS_AFFILIATES_LOCALE ) ,
                'public'                    => true ,
                'exclude_from_search'       => false ,
                'show_in_admin_all_list'    => true ,
                'show_in_admin_status_list' => true ,
                'label_count'               => _n_noop( 'Submitted <span class="count">(%s)</span>' , 'Submitted <span class="count">(%s)</span>' ) ,
                    )
                    ) ;
            return $args ;
        }

        public static function fs_progress_post_status_args() {
            $args = apply_filters( 'fs_progress_post_status_args' , array(
                'label'                     => _x( 'In-Progress' , FS_AFFILIATES_LOCALE ) ,
                'public'                    => true ,
                'exclude_from_search'       => false ,
                'show_in_admin_all_list'    => true ,
                'show_in_admin_status_list' => true ,
                'label_count'               => _n_noop( 'In-Progress <span class="count">(%s)</span>' , 'In-Progress <span class="count">(%s)</span>' ) ,
                    )
                    ) ;
            return $args ;
        }

        public static function fs_closed_post_status_args() {
            $args = apply_filters( 'fs_closed_post_status_args' , array(
                'label'                     => _x( 'Closed' , FS_AFFILIATES_LOCALE ) ,
                'public'                    => true ,
                'exclude_from_search'       => false ,
                'show_in_admin_all_list'    => true ,
                'show_in_admin_status_list' => true ,
                'label_count'               => _n_noop( 'Closed <span class="count">(%s)</span>' , 'Closed <span class="count">(%s)</span>' ) ,
                    )
                    ) ;
            return $args ;
        }

        public static function fs_hold_post_status_args() {
            $args = apply_filters( 'fs_hold_post_status_args' , array (
                'label'                     => _x( 'On-Hold' , FS_AFFILIATES_LOCALE ) ,
                'public'                    => true ,
                'exclude_from_search'       => false ,
                'show_in_admin_all_list'    => true ,
                'show_in_admin_status_list' => true ,
                'label_count'               => _n_noop( 'On-Hold <span class="count">(%s)</span>' , 'On-Hold <span class="count">(%s)</span>' ) ,
                    )
                    ) ;
            return $args ;
        }

        public static function fs_active_post_status_args() {
            $args = apply_filters( 'fs_active_post_status_args' , array (
                'label'                     => _x( 'Active' , FS_AFFILIATES_LOCALE ) ,
                'public'                    => true ,
                'exclude_from_search'       => false ,
                'show_in_admin_all_list'    => true ,
                'show_in_admin_status_list' => true ,
                'label_count'               => _n_noop( 'Active <span class="count">(%s)</span>' , 'Active <span class="count">(%s)</span>' ) ,
                    )
                    ) ;
            return $args ;
        }

        public static function fs_inactive_post_status_args() {
            $args = apply_filters( 'fs_inactive_post_status_args' , array (
                'label'                     => _x( 'Inactive' , FS_AFFILIATES_LOCALE ) ,
                'public'                    => true ,
                'exclude_from_search'       => false ,
                'show_in_admin_all_list'    => true ,
                'show_in_admin_status_list' => true ,
                'label_count'               => _n_noop( 'Inactive <span class="count">(%s)</span>' , 'Inactive <span class="count">(%s)</span>' ) ,
                    )
                    ) ;
            return $args ;
        }

        public static function fs_unpaid_post_status_args() {
            $args = apply_filters( 'fs_unpaid_post_status_args' , array (
                'label'                     => _x( 'Unpaid' , FS_AFFILIATES_LOCALE ) ,
                'public'                    => true ,
                'exclude_from_search'       => false ,
                'show_in_admin_all_list'    => true ,
                'show_in_admin_status_list' => true ,
                'label_count'               => _n_noop( 'Unpaid <span class="count">(%s)</span>' , 'Unpaid <span class="count">(%s)</span>' ) ,
                    )
                    ) ;
            return $args ;
        }

        public static function fs_rejected_post_status_args() {
            $args = apply_filters( 'fs_rejected_post_status_args' , array (
                'label'                     => _x( 'Rejected' , FS_AFFILIATES_LOCALE ) ,
                'public'                    => false ,
                'exclude_from_search'       => false ,
                'show_in_admin_all_list'    => true ,
                'show_in_admin_status_list' => true ,
                'label_count'               => _n_noop( 'Rejected <span class="count">(%s)</span>' , 'Rejected <span class="count">(%s)</span>' ) ,
                    )
                    ) ;
            return $args ;
        }

        public static function fs_suspended_post_status_args() {
            $args = apply_filters( 'fs_suspended_post_status_args' , array (
                'label'                     => _x( 'Suspended' , FS_AFFILIATES_LOCALE ) ,
                'public'                    => false ,
                'exclude_from_search'       => false ,
                'show_in_admin_all_list'    => true ,
                'show_in_admin_status_list' => true ,
                'label_count'               => _n_noop( 'Suspended <span class="count">(%s)</span>' , 'Suspended <span class="count">(%s)</span>' ) ,
                    )
                    ) ;
            return $args ;
        }

        public static function fs_pending_approval_post_type_args() {
            $args = apply_filters( 'fs_pending_approval_post_type_args' , array (
                'label'                     => _x( 'Pending Approval' , FS_AFFILIATES_LOCALE ) ,
                'public'                    => true ,
                'exclude_from_search'       => false ,
                'show_in_admin_all_list'    => true ,
                'show_in_admin_status_list' => true ,
                'label_count'               => _n_noop( 'Pending Approval <span class="count">(%s)</span>' , 'Pending Approval <span class="count">(%s)</span>' ) ,
                    )
                    ) ;
            return $args ;
        }

        public static function fs_link_post_type_args() {
            $args = apply_filters( 'fs_link_post_type_args' , array (
                'label'                     => _x( 'Link' , FS_AFFILIATES_LOCALE ) ,
                'public'                    => true ,
                'exclude_from_search'       => false ,
                'show_in_admin_all_list'    => true ,
                'show_in_admin_status_list' => true ,
                'label_count'               => _n_noop( 'Link <span class="count">(%s)</span>' , 'Link <span class="count">(%s)</span>' ) ,
                    )
                    ) ;
            return $args ;
        }

        public static function fs_unlink_post_type_args() {
            $args = apply_filters( 'fs_unlink_post_type_args' , array (
                'label'                     => _x( 'Unlink' , FS_AFFILIATES_LOCALE ) ,
                'public'                    => true ,
                'exclude_from_search'       => false ,
                'show_in_admin_all_list'    => true ,
                'show_in_admin_status_list' => true ,
                'label_count'               => _n_noop( 'Unlink <span class="count">(%s)</span>' , 'Unlink <span class="count">(%s)</span>' ) ,
                    )
                    ) ;
            return $args ;
        }

        public static function fs_notconverted_post_status_args() {
            $args = apply_filters( 'fs_notconverted_approval_post_type_args' , array (
                'label'                     => _x( 'Not Converted' , FS_AFFILIATES_LOCALE ) ,
                'public'                    => true ,
                'exclude_from_search'       => false ,
                'show_in_admin_all_list'    => true ,
                'show_in_admin_status_list' => true ,
                'label_count'               => _n_noop( 'Not Converted <span class="count">(%s)</span>' , 'Not Converted <span class="count">(%s)</span>' ) ,
                    )
                    ) ;
            return $args ;
        }

        public static function fs_converted_post_status_args() {
            $args = apply_filters( 'fs_converted_approval_post_type_args' , array (
                'label'                     => _x( 'Converted' , FS_AFFILIATES_LOCALE ) ,
                'public'                    => true ,
                'exclude_from_search'       => false ,
                'show_in_admin_all_list'    => true ,
                'show_in_admin_status_list' => true ,
                'label_count'               => _n_noop( 'Converted <span class="count">(%s)</span>' , 'Converted <span class="count">(%s)</span>' ) ,
                    )
                    ) ;
            return $args ;
        }

        public static function fs_in_progress_post_type_args() {
            $args = apply_filters( 'fs_in_progress_post_type_args' , array (
                'label'                     => _x( 'In Progress' , FS_AFFILIATES_LOCALE ) ,
                'public'                    => true ,
                'exclude_from_search'       => false ,
                'show_in_admin_all_list'    => true ,
                'show_in_admin_status_list' => true ,
                'label_count'               => _n_noop( 'In Progress <span class="count">(%s)</span>' , 'In Progress <span class="count">(%s)</span>' ) ,
                    ) ) ;
            return $args ;
        }

    }

    FS_Affiliates_Register_Post_Status::init() ;
}
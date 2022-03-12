<?php
/*
 * Admin Ajax
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}
if ( ! class_exists( 'FS_Affiliates_Admin_Ajax' ) ) {

    /**
     * FS_Affiliates_Admin_Ajax Class
     */
    class FS_Affiliates_Admin_Ajax {

        /**
         * FS_Affiliates_Admin_Ajax Class initialization
         */
        public static function init() {

            add_action( 'wp_ajax_fs_affiliates_add_mlm_rule', array( __CLASS__, 'add_mlm_rule' ) ) ;
            add_action( 'wp_ajax_fs_affiliates_add_dashboard_tab_rule', array( __CLASS__, 'add_dashboard_tab_rule' ) ) ;
            add_action( 'wp_ajax_fsunpaidcommission', array( __CLASS__, 'fp_request_unpaid_commission' ) ) ;
            add_action( 'wp_ajax_fs_user_search', array( __CLASS__, 'user_search' ) ) ;
            add_action( 'wp_ajax_fs_coupon_search', array( __CLASS__, 'coupon_search' ) ) ;
            add_action( 'wp_ajax_fs_affiliates_search', array( __CLASS__, 'affiliates_search' ) ) ;
            add_action( 'wp_ajax_fs_affiliates_products_search', array( __CLASS__, 'products_search' ) ) ;
            add_action( 'wp_ajax_fs_affiliates_sort_fields', array( __CLASS__, 'sort_form_fields' ) ) ;
            add_action( 'wp_ajax_fs_affiliates_sort_dashboard_tabs', array( __CLASS__, 'sort_dashboard_tabs' ) ) ;
            add_action( 'wp_ajax_fs_affiliates_toggle_modules', array( __CLASS__, 'toggle_modules' ) ) ;
            add_action( 'wp_ajax_fs_affiliates_toggle_integrations', array( __CLASS__, 'toggle_integrations' ) ) ;
            add_action( 'wp_ajax_fs_affiliates_toggle_notifications', array( __CLASS__, 'toggle_notifications' ) ) ;
            add_action( 'wp_ajax_fs_affiliates_username_validation', array( __CLASS__, 'username_validation' ) ) ;
            add_action( 'wp_ajax_fs_referral_rejected_reason', array( __CLASS__, 'referral_rejected_reason' ) ) ;
            add_action( 'wp_ajax_fs_affiliates_useremail_validation', array( __CLASS__, 'useremail_validation' ) ) ;
            add_action( 'wp_ajax_fs_affiliates_file_upload', array( __CLASS__, 'file_upload' ) ) ;
            add_action( 'wp_ajax_nopriv_fs_affiliates_file_upload', array( __CLASS__, 'file_upload' ) ) ;
            add_action( 'wp_ajax_fs_affiliates_remove_uploaded_file', array( __CLASS__, 'remove_uploaded_file' ) ) ;
            add_action( 'wp_ajax_nopriv_fs_affiliates_remove_uploaded_file', array( __CLASS__, 'remove_uploaded_file' ) ) ;
            add_action( 'wp_ajax_nopriv_fs_affiliates_username_validation', array( __CLASS__, 'username_validation' ) ) ;
            add_action( 'wp_ajax_nopriv_fs_affiliates_useremail_validation', array( __CLASS__, 'useremail_validation' ) ) ;
            add_action( 'wp_ajax_fs_affiliates_generate_affiliate_url', array( __CLASS__, 'generate_affiliate_url' ) ) ;
            add_action( 'wp_ajax_nopriv_fs_affiliates_generate_affiliate_url', array( __CLASS__, 'generate_affiliate_url' ) ) ;
            add_action( 'wp_ajax_fs_affiliates_pay_method_change', array( __CLASS__, 'fs_affiliates_pay_method_change' ) ) ;
            add_action( 'wp_ajax_fs_affiliates_referafriend_mails', array( __CLASS__, 'fs_affiliates_referafriend_mails' ) ) ;
            add_action( 'wp_ajax_fs_affiliates_export_payouts_data', array( __CLASS__, 'export_payouts_data' ) ) ;
            add_action( 'wp_ajax_fs_affiliates_process_payouts_data_export', array( __CLASS__, 'process_payouts_data_export' ) ) ;
            add_action( 'wp_ajax_fs_affiliates_toggle_settings_color_mode', array( __CLASS__, 'change_settings_color_mode' ) ) ;
            add_action( 'wp_ajax_fs_affiliates_toggle_module_settings_color_mode', array( __CLASS__, 'change_module_settings_color_mode' ) ) ;
            add_action( 'wp_ajax_fs_affiliates_test_pushover_notification', array( __CLASS__, 'test_pushover_notification' ) ) ;
            add_action( 'wp_ajax_fs_add_rule_for_product_rate', array( __CLASS__, 'fs_add_rule_for_product_rate' ) ) ;
            add_action( 'wp_ajax_fs_affiliates_aff_fee_products_search', array( __CLASS__, 'aff_fee_products_search' ) ) ;
            add_action( 'wp_ajax_fs_affiliates_change_user_as_affiliate', array( __CLASS__, 'change_user_as_affiliate' ) ) ;
            add_action( 'wp_ajax_fs_affiliates_get_bulk_update_affiliate_ids', array( __CLASS__, 'get_bulk_update_affiliate_ids' ) ) ;
        }

        /**
         * Get non affiliate user ids
         */
        public static function get_bulk_update_affiliate_ids() {
            check_ajax_referer( 'fs-bulk-update-nonce', 'fs_security' ) ;

            try {
                if ( ! isset( $_REQUEST ) || ! isset( $_REQUEST[ 'user_type' ] ) )
                    throw new exception( __( 'Invalid Request', FS_AFFILIATES_LOCALE ) ) ;


                if ( $_REQUEST[ 'user_type' ] == '2' ) {
                    $user_ids = $_REQUEST[ 'selected_users' ] ;
                } else {
                    $args = array(
                        'meta_key'     => 'fs_affiliates_enabled',
                        'meta_compare' => 'NOT EXISTS',
                        'fields'       => 'ids',
                            ) ;

                    $user_ids = get_users( $args ) ;
                }

                if ( ! fs_affiliates_check_is_array( $user_ids ) )
                    throw new Exception( __( 'No valid user(s) found', FS_AFFILIATES_LOCALE ) ) ;

                wp_send_json_success( array( 'user_ids' => $user_ids ) ) ;
            } catch ( Exception $e ) {
                wp_send_json_error( array( 'error' => $e->getMessage() ) ) ;
            }
        }

        /**
         * Change all user as Affiliate
         */
        public static function change_user_as_affiliate() {
            check_ajax_referer( 'fs-bulk-update-nonce', 'fs_security' ) ;

            try {
                if ( ! isset( $_REQUEST ) || ! isset( $_REQUEST[ 'user_ids' ] ) )
                    throw new exception( __( 'Invalid Request', FS_AFFILIATES_LOCALE ) ) ;

                if ( ! fs_affiliates_check_is_array( $_REQUEST[ 'user_ids' ] ) )
                    throw new Exception( __( 'Bulk update completed successfully', FS_AFFILIATES_LOCALE ) ) ;

                foreach ( $_REQUEST[ 'user_ids' ] as $user_id ) {

                    fs_affiliates_change_user_as_affiliate( $user_id ) ;
                }

                wp_send_json_success() ;
            } catch ( Exception $e ) {
                wp_send_json_error( array( 'error' => $e->getMessage() ) ) ;
            }
        }

        /**
         * Change Settings Color Mode
         */
        public static function change_settings_color_mode() {
            check_ajax_referer( 'fs-settings-color-mode-nonce', 'fs_security' ) ;

            try {
                if ( ! isset( $_REQUEST ) || ! isset( $_REQUEST[ 'color_mode' ] ) )
                    throw new exception( __( 'Invalid Request', FS_AFFILIATES_LOCALE ) ) ;

                update_option( 'fs_affiliates_settings_color_mode', $_REQUEST[ 'color_mode' ] ) ;

                wp_send_json_success( array( 'content' => 'success' ) ) ;
            } catch ( Exception $ex ) {
                wp_die() ;
            }
        }

        /**
         * Change Module Settings Color Mode
         */
        public static function change_module_settings_color_mode() {
            check_ajax_referer( 'fs-settings-color-mode-nonce', 'fs_security' ) ;

            try {
                if ( ! isset( $_REQUEST ) || ! isset( $_REQUEST[ 'color_mode' ] ) )
                    throw new exception( __( 'Invalid Request', FS_AFFILIATES_LOCALE ) ) ;

                update_option( 'fs_affiliates_module_settings_color_mode', $_REQUEST[ 'color_mode' ] ) ;

                wp_send_json_success( array( 'content' => 'success' ) ) ;
            } catch ( Exception $ex ) {
                wp_die() ;
            }
        }

        /**
         * Upload a File via Ajax
         */
        public static function file_upload() {

            try {
                if ( ! isset( $_POST ) )
                    throw new exception( __( 'Invalid Request', FPCF_LOCALE ) ) ;

                $key = $_POST[ 'key' ] ;

                $get_transient = get_transient( $key ) ;
                $file_uploader = new FS_Affiliates_File_Uploader( $key ) ;
                $value         = $file_uploader->upload_files() ;

                $merge_data = fs_affiliates_check_is_array( $get_transient ) ? array_merge( $get_transient, $value ) : $value ;

                set_transient( $key, array_filter( $merge_data ), 3600 ) ;

                wp_send_json_success() ;
            } catch ( Exception $e ) {
                wp_send_json_error( array( 'error' => $e->getMessage() ) ) ;
            }
        }

        /**
         * Remove a File via Ajax
         */
        public static function remove_uploaded_file() {

            try {
                if ( ! isset( $_POST ) || ! isset( $_POST[ 'file_name' ] ) )
                    throw new exception( __( 'Invalid Request', FPCF_LOCALE ) ) ;

                $key      = $_POST[ 'key' ] ;
                $filename = $_POST[ 'file_name' ] ;

                $get_transient = get_transient( $key ) ;
                if ( fs_affiliates_check_is_array( $get_transient ) && array_key_exists( $filename, $get_transient ) ) {
                    unset( $get_transient[ $filename ] ) ;
                }

                set_transient( $key, array_filter( $get_transient ), 3600 ) ;

                wp_send_json_success() ;
            } catch ( Exception $e ) {
                wp_send_json_error( array( 'error' => $e->getMessage() ) ) ;
            }
        }

        /**
         * User search
         */
        public static function user_search() {
            check_ajax_referer( 'fs-search-nonce', 'fs_security' ) ;

            try {
                if ( ! isset( $_REQUEST ) || ! isset( $_REQUEST[ 'term' ] ) )
                    throw new exception( __( 'Invalid Request', FS_AFFILIATES_LOCALE ) ) ;

                $listofusers = array() ;
                $term        = $_REQUEST[ 'term' ] ;

                if ( empty( $term ) )
                    throw new exception( __( 'Invalid Request', FS_AFFILIATES_LOCALE ) ) ;

                $number = (strlen( $term ) > 3) ? '' : '20' ;

                $args           = array(
                    'meta_key'     => 'fs_affiliates_enabled',
                    'meta_compare' => 'NOT EXISTS',
                    'search'       => '*' . esc_attr( $term ) . '*',
                    'number'       => $number,
                    'fields'       => 'all',
                        ) ;
                $search_results = get_users( $args ) ;

                if ( fs_affiliates_check_is_array( $search_results ) ) {
                    foreach ( $search_results as $user ) {
                        if ( ! is_object( $user ) )
                            continue ;

                        $listofusers[ $user->ID ] = esc_html( esc_html( $user->display_name ) . '(#' . absint( $user->ID ) . ' &ndash; ' . esc_html( $user->user_email ) . ')' ) ;
                    }
                }

                wp_send_json( $listofusers ) ;
            } catch ( Exception $ex ) {
                wp_send_json_error( array( 'error' => $e->getMessage() ) ) ;
            }
        }

        public static function fs_affiliates_pay_method_change() {
            check_ajax_referer( 'affiliate-payment-nonce', 'fs_security' ) ;

            try {
                if ( ! isset( $_POST ) || ! isset( $_POST[ 'fs_affiliates_payment_method' ] ) )
                    throw new exception( __( 'Invalid Request', FS_AFFILIATES_LOCALE ) ) ;

                $affilate_id  = isset( $_POST[ 'fs_affiliate_current_id' ] ) ? $_POST[ 'fs_affiliate_current_id' ] : '' ;
                $pay_method   = isset( $_POST[ 'fs_affiliates_payment_method' ] ) ? $_POST[ 'fs_affiliates_payment_method' ] : '' ;
                $paypal_email = isset( $_POST[ 'fs_affiliates_paypal_email' ] ) ? $_POST[ 'fs_affiliates_paypal_email' ] : '' ;
                $paypal_bank  = isset( $_POST[ 'fs_affiliates_bank_details' ] ) ? $_POST[ 'fs_affiliates_bank_details' ] : '' ;

                $payment_datas = array(
                    'fs_affiliates_current_id'     => $affilate_id,
                    'fs_affiliates_payment_method' => $pay_method,
                    'fs_affiliates_paypal_email'   => $paypal_email,
                    'fs_affiliates_bank_details'   => $paypal_bank,
                        ) ;

                update_post_meta( $affilate_id, 'fs_affiliates_user_payment_datas', $payment_datas ) ;

                update_post_meta( $affilate_id, 'payment_email', $paypal_email ) ;

                wp_send_json_success( array( 'content' => 'success' ) ) ;
            } catch ( Exception $e ) {
                wp_send_json_error( array( 'error' => $e->getMessage() ) ) ;
            }
        }

        /**
         * User search
         */
        public static function affiliates_search() {
            check_ajax_referer( 'fs-search-nonce', 'fs_security' ) ;

            try {
                if ( ! isset( $_REQUEST ) || ! isset( $_REQUEST[ 'term' ] ) )
                    throw new exception( __( 'Invalid Request', FS_AFFILIATES_LOCALE ) ) ;

                $listofaffiliates = array() ;
                $term             = $_REQUEST[ 'term' ] ;

                if ( empty( $term ) )
                    throw new exception( __( 'Invalid Request', FS_AFFILIATES_LOCALE ) ) ;

                global $wpdb ;
                $like  = '%' . $wpdb->esc_like( $term ) . '%' ;
                $query = $wpdb->prepare( "SELECT DISTINCT posts.ID as id FROM {$wpdb->posts} as posts
			INNER JOIN {$wpdb->postmeta} postmeta ON posts.ID = postmeta.post_id
			WHERE posts.post_type='fs-affiliates' AND posts.post_status IN('fs_active')
                        AND ( ( postmeta.meta_key IN('first_name','last_name','email')
                        AND postmeta.meta_value LIKE %s )  OR (posts.ID LIKE %s) OR (posts.post_title LIKE %s))", $like, $like, $like ) ;

                $search_results = $wpdb->get_col( $query ) ;
                $search_results = array_filter( $search_results ) ;

                if ( fs_affiliates_check_is_array( $search_results ) ) {
                    foreach ( $search_results as $_id ) {
                        $affiliates               = new FS_Affiliates_Data( $_id ) ;
                        $listofaffiliates[ $_id ] = esc_html( esc_html( $affiliates->first_name . ' ' . $affiliates->last_name ) . '(#' . absint( $affiliates->get_id() ) . ' &ndash; ' . esc_html( $affiliates->email ) . ')' ) ;
                    }
                }

                wp_send_json( $listofaffiliates ) ;
            } catch ( Exception $ex ) {
                wp_die() ;
            }
        }

        /**
         * Product search
         */
        public static function products_search() {
            check_ajax_referer( 'fs-search-nonce', 'fs_security' ) ;

            try {
                if ( ! isset( $_REQUEST ) || ! isset( $_REQUEST[ 'term' ] ) )
                    throw new exception( __( 'Invalid Request', FS_AFFILIATES_LOCALE ) ) ;

                $listofaffiliates = array() ;
                $term             = $_REQUEST[ 'term' ] ;

                if ( empty( $term ) )
                    throw new exception( __( 'Invalid Request', FS_AFFILIATES_LOCALE ) ) ;

                $data_store = WC_Data_Store::load( 'product' ) ;
                $ids        = $data_store->search_products( $term, '', true ) ;

                $product_objects = array_filter( array_map( 'wc_get_product', $ids ), 'wc_products_array_filter_readable' ) ;
                $products        = array() ;

                foreach ( $product_objects as $product_object ) {
                    $products[ $product_object->get_id() ] = rawurldecode( $product_object->get_formatted_name() ) ;
                }

                wp_send_json( $products ) ;
            } catch ( Exception $ex ) {
                wp_die() ;
            }
        }

        /**
         * Product search
         */
        public static function aff_fee_products_search() {
            check_ajax_referer( 'fs-search-nonce', 'fs_security' ) ;

            try {
                if ( ! isset( $_GET ) || ! isset( $_GET[ 'term' ] ) )
                    throw new exception( __( 'Invalid Request', FS_AFFILIATES_LOCALE ) ) ;

                global $wpdb ;
                $term = ( string ) wc_clean( stripslashes( $_GET[ 'term' ] ) ) ;

                if ( FS_Affiliates_Module_Instances::get_module_by_id( 'affiliate_fee' )->charge_affiliate_fee_by_recurring() ) {
                    $product_posts = $wpdb->get_results( $wpdb->prepare( "
                                                        SELECT DISTINCT p.ID, p.post_title FROM {$wpdb->posts} p
                                                        INNER JOIN {$wpdb->postmeta} AS m1 ON ( p.ID = m1.post_id )
                                                        INNER JOIN {$wpdb->postmeta} AS m2 ON ( p.ID = m2.post_id )
                                                        INNER JOIN {$wpdb->postmeta} AS m3 ON ( p.ID = m3.post_id )
                                                        LEFT JOIN {$wpdb->postmeta} AS m4 ON (p.ID = m4.post_id AND m4.meta_key = 'sumo_susbcription_status' )
                                                        WHERE ((p.post_title LIKE '%s') OR (p.post_excerpt LIKE '%s') OR (p.post_content LIKE '%s'))
                                                        AND p.post_type IN ('product') AND p.post_status LIKE 'publish'
                                                        AND ((m1.meta_key = 'sumo_susbcription_status' AND m1.meta_value LIKE '1'
                                                        AND m2.meta_key = 'sumo_susbcription_trial_enable_disable' AND m2.meta_value NOT IN ('1','3')
                                                        AND m3.meta_key = 'sumo_recurring_period_value' AND m3.meta_value LIKE '0')
                                                        OR (m4.post_id IS NULL OR ( m3.meta_key = 'sumo_susbcription_status' AND m3.meta_value NOT LIKE '1' )))"
                                    , "%{$term}%", "%{$term}%", "%{$term}%" ) ) ;

                    $invalid_variable_posts = $wpdb->get_col( $wpdb->prepare( "
                                                        SELECT DISTINCT p.post_parent FROM {$wpdb->posts} p
                                                        LEFT JOIN {$wpdb->postmeta} AS m1 ON (p.ID = m1.post_id AND m1.meta_key = 'sumo_susbcription_status' )
                                                        INNER JOIN {$wpdb->postmeta} AS m2 ON ( p.ID = m2.post_id )
                                                        INNER JOIN {$wpdb->postmeta} AS m3 ON ( p.ID = m3.post_id )
                                                        WHERE ((p.post_title LIKE '%s') OR (p.post_excerpt LIKE '%s') OR (p.post_content LIKE '%s'))
                                                        AND p.post_type IN ('product_variation') AND p.post_status LIKE 'publish'
                                                        AND m1.meta_key = 'sumo_susbcription_status' AND m1.meta_value LIKE '1'
                                                        AND ((m2.meta_key = 'sumo_susbcription_trial_enable_disable' AND m2.meta_value IN ('1','3'))
                                                        OR (m3.meta_key = 'sumo_recurring_period_value' AND m3.meta_value NOT LIKE '0'))"
                                    , "%{$term}%", "%{$term}%", "%{$term}%" ) ) ;
                } else {
                    $product_posts = $wpdb->get_results( $wpdb->prepare( "
                                                        SELECT DISTINCT p.ID, p.post_title FROM {$wpdb->posts} p
                                                        LEFT JOIN {$wpdb->postmeta} AS m1 ON (p.ID = m1.post_id AND m1.meta_key = 'sumo_susbcription_status' )
                                                        INNER JOIN {$wpdb->postmeta} AS m2 ON ( p.ID = m2.post_id )
                                                        WHERE ((p.post_title LIKE '%s') OR (p.post_excerpt LIKE '%s') OR (p.post_content LIKE '%s'))
                                                        AND (m1.post_id IS NULL OR ( m2.meta_key = 'sumo_susbcription_status' AND m2.meta_value NOT LIKE '1' ))
                                                        AND p.post_type IN ('product') AND p.post_status LIKE 'publish' ORDER BY post_title"
                                    , "%{$term}%", "%{$term}%", "%{$term}%" ) ) ;
                }

                $products = array() ;
                if ( $product_posts ) {
                    foreach ( $product_posts as $post ) {
                        if ( ! empty( $invalid_variable_posts ) && in_array( $post->ID, $invalid_variable_posts ) ) {
                            continue ;
                        }

                        $products[ $post->ID ] = $post->post_title ;
                    }
                }

                wp_send_json( $products ) ;
            } catch ( Exception $ex ) {
                wp_die() ;
            }
        }

        /**
         * Coupon search
         */
        public static function coupon_search() {
            check_ajax_referer( 'fs-search-nonce', 'fs_security' ) ;

            try {
                if ( ! isset( $_REQUEST ) || ! isset( $_REQUEST[ 'term' ] ) )
                    throw new exception( __( 'Invalid Request', FS_AFFILIATES_LOCALE ) ) ;

                $listofcoupons = array() ;
                $term          = $_REQUEST[ 'term' ] ;

                if ( empty( $term ) )
                    throw new exception( __( 'Invalid Request', FS_AFFILIATES_LOCALE ) ) ;

                global $wpdb ;
                $like  = '%' . $wpdb->esc_like( $term ) . '%' ;
                $query = $wpdb->prepare( "SELECT DISTINCT ID as id, post_title as name FROM {$wpdb->posts}
			WHERE post_type='shop_coupon' AND post_status IN('publish')
                        AND (post_title LIKE %s)", $like ) ;

                $search_results = $wpdb->get_results( $query, ARRAY_A ) ;
                $search_results = array_filter( $search_results ) ;

                if ( fs_affiliates_check_is_array( $search_results ) ) {
                    foreach ( $search_results as $result ) {
                        $CheckIfCouponLinked              = is_linked_coupon( $result[ 'id' ] ) ;
                        if ( empty( $CheckIfCouponLinked ) )
                            $listofcoupons[ $result[ 'id' ] ] = esc_html( esc_html( $result[ 'name' ] ) ) ;
                    }
                }

                wp_send_json( $listofcoupons ) ;
            } catch ( Exception $ex ) {
                wp_die() ;
            }
        }

        /**
         * Sort form fields
         */
        public static function sort_form_fields() {
            check_ajax_referer( 'fs-field-sort-nonce', 'fs_security' ) ;

            try {

                if ( ! isset( $_POST ) || ! isset( $_POST[ 'sort_order' ] ) || ! fs_affiliates_check_is_array( $_POST[ 'sort_order' ] ) )
                    throw new exception( __( 'Invalid Request', FS_AFFILIATES_LOCALE ) ) ;

                $fields = fs_affiliates_get_form_fields() ;

                $fields = array_filter( array_merge( array_flip( $_POST[ 'sort_order' ] ), $fields ) ) ;


                update_option( 'fs_affiliates_frontend_form_fields', $fields ) ;

                wp_send_json_success() ;
            } catch ( Exception $e ) {
                wp_send_json_error( array( 'error' => $e->getMessage() ) ) ;
            }
        }

        /**
         * Sort Dashboard Tabs
         */
        public static function sort_dashboard_tabs() {
            check_ajax_referer( 'fs-field-sort-nonce', 'fs_security' ) ;

            try {

                if ( ! isset( $_POST ) || ! isset( $_POST[ 'sort_order' ] ) || ! fs_affiliates_check_is_array( $_POST[ 'sort_order' ] ) )
                    throw new exception( __( 'Invalid Request', FS_AFFILIATES_LOCALE ) ) ;

                $additional_dashboard_tabs = FS_Affiliates_Module_Instances::get_module_by_id( 'additional_dashboard_tabs' ) ;

                $saving_rules = array_filter( array_merge( array_flip( $_POST[ 'sort_order' ] ), $additional_dashboard_tabs->get_rules() ) ) ;

                $additional_dashboard_tabs->update_option( 'rules', $saving_rules ) ;

                wp_send_json_success() ;
            } catch ( Exception $e ) {
                wp_send_json_error( array( 'error' => $e->getMessage() ) ) ;
            }
        }

        /**
         * Toggle Modules
         */
        public static function toggle_modules() {
            check_ajax_referer( 'fs-module-nonce', 'fs_security' ) ;

            try {
                if ( ! isset( $_REQUEST ) || ! isset( $_REQUEST[ 'module_name' ] ) )
                    throw new exception( __( 'Invalid Request', FS_AFFILIATES_LOCALE ) ) ;

                $module_object = FS_Affiliates_Module_Instances::get_module_by_id( $_REQUEST[ 'module_name' ] ) ;
                if ( is_object( $module_object ) ) {
                    $value = ($_REQUEST[ 'enabled' ] == 'true') ? 'yes' : 'no' ;
                    $module_object->update_option( 'enabled', $value ) ;
                }

                wp_send_json_success() ;
            } catch ( Exception $ex ) {
                wp_send_json_success( array( 'error' => $ex->getMessage() ) ) ;
            }
        }

        /**
         * Toggle integrations
         */
        public static function toggle_integrations() {
            check_ajax_referer( 'fs-integration-nonce', 'fs_security' ) ;

            try {
                if ( ! isset( $_REQUEST ) || ! isset( $_REQUEST[ 'integration_name' ] ) )
                    throw new exception( __( 'Invalid Request', FS_AFFILIATES_LOCALE ) ) ;

                $integration_object = FS_Affiliates_Integration_Instances::get_integration_by_id( $_REQUEST[ 'integration_name' ] ) ;

                if ( is_object( $integration_object ) ) {
                    $value = ($_REQUEST[ 'enabled' ] == 'true') ? 'yes' : 'no' ;
                    $integration_object->update_option( 'enabled', $value ) ;
                }

                wp_send_json_success() ;
            } catch ( Exception $ex ) {
                wp_send_json_success( array( 'error' => $ex->getMessage() ) ) ;
            }
        }

        /**
         * Toggle notifications
         */
        public static function toggle_notifications() {
            check_ajax_referer( 'fs-notification-nonce', 'fs_security' ) ;

            try {
                if ( ! isset( $_REQUEST ) || ! isset( $_REQUEST[ 'notification_name' ] ) )
                    throw new exception( __( 'Invalid Request', FS_AFFILIATES_LOCALE ) ) ;

                $notification_object = FS_Affiliates_Notification_Instances::get_notification_by_id( $_REQUEST[ 'notification_name' ] ) ;

                if ( is_object( $notification_object ) ) {
                    $value = ($_REQUEST[ 'enabled' ] == 'true') ? 'yes' : 'no' ;
                    $notification_object->update_option( 'enabled', $value ) ;
                }

                wp_send_json_success() ;
            } catch ( Exception $ex ) {
                wp_send_json_success( array( 'error' => $ex->getMessage() ) ) ;
            }
        }

        /**
         * User name validation
         */
        public static function username_validation() {
            check_ajax_referer( 'fs-username-nonce', 'fs_security' ) ;

            try {
                if ( ! isset( $_REQUEST ) || ! isset( $_REQUEST[ 'name' ] ) )
                    throw new exception( __( 'Invalid Request', FS_AFFILIATES_LOCALE ) ) ;

                $content = '' ;
                $user_id = username_exists( $_REQUEST[ 'name' ] ) ;
                if ( $user_id ) {
                    $user    = get_user_by( 'id', $user_id ) ;
                    $content = '<span class="fs_affiliates_warning">' . sprintf( __( 'Username %s already exists, please enter a different username', FS_AFFILIATES_LOCALE ), $user->user_name ) . '</span>' ;
                }

                wp_send_json_success( array( 'content' => $content ) ) ;
            } catch ( Exception $ex ) {
                wp_send_json_success( array( 'error' => $ex->getMessage() ) ) ;
            }
        }

        /**
         * User name validation
         */
        public static function referral_rejected_reason() {
            check_ajax_referer( 'fs-referral-rejected-nonce', 'fs_security' ) ;
            try {
                if ( ! isset( $_REQUEST ) || ! isset( $_REQUEST[ 'reason' ] ) || ! isset( $_REQUEST[ 'referral_id' ] ) || empty( $_REQUEST[ 'referral_id' ] ) )
                    throw new exception( __( 'Invalid Request', FS_AFFILIATES_LOCALE ) ) ;

                update_post_meta( $_REQUEST[ 'referral_id' ], 'rejected_reason', $_REQUEST[ 'reason' ] ) ;

                wp_send_json_success() ;
            } catch ( Exception $ex ) {
                wp_send_json_success( array( 'error' => $ex->getMessage() ) ) ;
            }
        }

        /**
         * User email validation
         */
        public static function useremail_validation() {
            check_ajax_referer( 'fs-useremail-nonce', 'fs_security' ) ;

            try {
                if ( ! isset( $_REQUEST ) || ! isset( $_REQUEST[ 'email' ] ) )
                    throw new exception( __( 'Invalid Request', FS_AFFILIATES_LOCALE ) ) ;

                $content = '' ;
                $user    = get_user_by( 'email', $_REQUEST[ 'email' ] ) ;
                if ( $user ) {
                    $user    = get_user_by( 'id', $user_id ) ;
                    $content = '<span class="fs_affiliates_warning">' . sprintf( __( 'Email ID %s already exists, please enter a different email id', FS_AFFILIATES_LOCALE ), $user->user_email ) . '</span>' ;
                }

                wp_send_json_success( array( 'content' => $content ) ) ;
            } catch ( Exception $ex ) {
                wp_send_json_success( array( 'error' => $ex->getMessage() ) ) ;
            }
        }

        /**
         * Generate Affiliate Url
         */
        public static function generate_affiliate_url() {
            check_ajax_referer( 'fs-url-nonce', 'fs_security' ) ;

            try {
                if ( ! isset( $_POST ) )
                    throw new exception( __( 'Invalid Request', FS_AFFILIATES_LOCALE ) ) ;

                if ( isset( $_POST[ 'linkgeneratortype' ] ) && $_POST[ 'linkgeneratortype' ] == '2' ) {
                    if ( ! isset( $_POST[ 'product' ] ) || (isset( $_POST[ 'product' ] ) && $_POST[ 'product' ] == '') )
                        throw new exception( __( 'Please select a product', FS_AFFILIATES_LOCALE ) ) ;

                    if ( ! apply_filters( 'fs_affiliates_is_restricted_product', true, $_POST[ 'product' ] ) )
                        throw new exception( __( 'This product is restricted to generate the affiliate link', FS_AFFILIATES_LOCALE ) ) ;

                    $AffiliateURL = rtrim( get_permalink( $_POST[ 'product' ] ), '/' ) ;
                    $campaign     = isset( $_POST[ 'campaign_for_product' ] ) ? $_POST[ 'campaign_for_product' ] : false ;
                } else {
                    if ( ! isset( $_POST[ 'url' ] ) || (isset( $_POST[ 'url' ] ) && $_POST[ 'url' ] == '') )
                        throw new exception( __( 'Please enter a valid URL', FS_AFFILIATES_LOCALE ) ) ;

                    $AffiliateURL = $_POST[ 'url' ] ;
                    $campaign     = isset( $_POST[ 'campaign_for_affiliate' ] ) ? $_POST[ 'campaign_for_affiliate' ] : false ;
                }

                do_action( 'fs_affiliates_before_generate_affiliate_url', $AffiliateURL ) ;

                $ReferralIdentifier       = fs_get_referral_identifier() ;
                $ReferralIdFormat         = get_option( 'fs_affiliates_referral_id_format' ) ;
                $UserId                   = get_current_user_id() ;
                $AffiliateId              = fs_get_affiliate_id_for_user( $UserId ) ;
                $AffiliateData            = new FS_Affiliates_Data( $AffiliateId ) ;
                $AffiliateName            = $AffiliateData->user_name ;
                $Identifier               = $ReferralIdFormat == 'name' ? $AffiliateName : $AffiliateId ;
                $Identifier               = apply_filters( 'fs_affiliates_slug_for_affiliate', $Identifier, $AffiliateData ) ;
                $formatted_affiliate_link = add_query_arg( $ReferralIdentifier, $Identifier, $AffiliateURL ) ;

                if ( $campaign )
                    $formatted_affiliate_link = add_query_arg( 'campaign', $campaign, $formatted_affiliate_link ) ;

                $button_type = (isset( $_POST[ 'button_type' ] ) && 'static' == $_POST[ 'button_type' ]) ? true : false ;

                $formatted_affiliate_link = apply_filters( 'fs_affiliates_link_generator', $formatted_affiliate_link, $AffiliateURL, $ReferralIdentifier, $Identifier, $campaign ) ;

                $UrlToDisplay = '<p>' . sprintf( '<b>' . esc_html__( "Copy the Link: ", FS_AFFILIATES_LOCALE ) . '</b>' . "%s", $formatted_affiliate_link ) . ' ' . fs_display_copy_affiliate_link_image( $formatted_affiliate_link, $button_type ) . '</p>' ;

                $UrlToDisplay = apply_filters( 'fs_affiliates_generate_affiliate_link', $UrlToDisplay, $formatted_affiliate_link ) ;

                wp_send_json_success( array( 'content' => $UrlToDisplay ) ) ;
            } catch ( Exception $ex ) {
                wp_send_json_error( array( 'error' => $ex->getMessage() ) ) ;
            }
        }

        /*
         * Add a rule for MLM
         */

        public static function add_mlm_rule() {
            check_ajax_referer( 'fs-mlm-nonce', 'fs_security' ) ;

            try {
                if ( ! isset( $_POST ) || ! isset( $_POST[ 'count' ] ) )
                    throw new exception( __( 'Invalid Request', FS_AFFILIATES_LOCALE ) ) ;

                $key     = $_POST[ 'count' ] ;
                $name    = 'fs_affiliates_multi_level_marketing_rules[' . $key . ']' ;
                ob_start() ;
                ?>
                <tr>
                    <td>
                        <input type="hidden" id="fs_affiliates_mlm_rule_id" value="<?php echo $key ; ?>"/>
                        <span><?php echo sprintf( __( 'Level %s', FS_AFFILIATES_LOCALE ), $key ) ; ?></span>
                    </td>
                    <td>
                        <select name="<?php echo $name ; ?>[commission_type]" class='fs_affiliates_commission_type'>
                            <option value="percentage_commission"><?php echo esc_html__( 'Percentage Commission', FS_AFFILIATES_LOCALE ) ; ?></option>
                            <option value="fixed_commission" ><?php echo esc_html__( 'Fixed Commission', FS_AFFILIATES_LOCALE ) ; ?></option>
                        </select>   
                    </td>
                    <td>
                        <input type="text" class ='fs_affiliates_input_price' name="<?php echo $name ; ?>[commission_value]"/>
                    </td>
                    <td>
                        <p class="fs_affiliates_remove_mlm_rule"> <img src="<?php echo FS_AFFILIATES_PLUGIN_URL . '/assets/images/x-mark-3-24.png' ; ?>"></img></p>
                    </td>
                </tr>
                <?php
                $content = ob_get_clean() ;
                wp_send_json_success( array( 'content' => $content ) ) ;
            } catch ( Exception $e ) {
                wp_send_json_error( array( 'error' => $e->getMessage() ) ) ;
            }
        }

        /*
         * Add a rule for Additional Dashboard Tabs
         */

        public static function add_dashboard_tab_rule() {
            check_ajax_referer( 'fs-dashboard-tab-nonce', 'fs_security' ) ;

            try {
                if ( ! isset( $_POST ) )
                    throw new exception( __( 'Invalid Request', FS_AFFILIATES_LOCALE ) ) ;

                $name         = 'fs_affiliates_additional_dashboard_tabs_rules[' . $_POST[ 'count' ] . ']' ;
                $font_awesome = fs_affiliates_get_font_awesome_codes() ;
                ob_start() ;
                ?>
                <tr>
                    <td>
                        <div class = "fs_affiliates_tabs_sort_handle">
                            <h3><?php _e( 'Custom Tab', FS_AFFILIATES_LOCALE ) ; ?>
                                <i class="fa fa-bars" ></i>
                            </h3>
                        </div>
                        <div class="fs_affiliates_cell_one">
                            <p>
                                <input type="hidden" id="fs_affiliates_dashboard_tab_rule_id" value="<?php echo $_POST[ 'count' ] ; ?>"/>
                                <label><?php _e( 'Custom Tab Tile', FS_AFFILIATES_LOCALE ) ; ?>:&nbsp;</label>
                                <input type="text" required="required" name="<?php echo $name . '[tile]' ?>" value=""/>
                            </p>
                            <div class="fs_affiliates_custom_drop_down">
                                <label class="fs_affiliates_icon"><?php _e( 'Custom Tab Tile', FS_AFFILIATES_LOCALE ) ; ?>:&nbsp;</label>
                                <input type="hidden" class="fs_affiliates_selected_icon_code" name="<?php echo $name . '[code]' ; ?>" value=""/>
                                <div class="fs_affiliates_selected_icon"><i class="fa fa-cog"></i></div>
                                <div class="fs_affiliates_popup_icons" style="display:none;">
                                    <ul>
                                        <?php foreach ( $font_awesome as $base_class => $code ): ?>
                                            <li class="fs_affiliates_popup_icon" data-class="<?php echo $base_class ; ?>"><i class="fa <?php echo $base_class ; ?>"></i></li>
                                        <?php endforeach ; ?>
                                    </ul>
                                </div>
                            </div>
                            <p>
                                <label><?php _e( 'Custom Tab Content', FS_AFFILIATES_LOCALE ) ; ?>:&nbsp;</label>
                                <?php wp_editor( '', 'fs_affiliates_additional_dashboard_tabs_rules-' . $_POST[ 'count' ], array( 'textarea_name' => $name . '[content]', 'media_buttons' => false ) ) ; ?>
                            </p>
                            <p>
                                <label><?php _e( 'Hide Tab In Frontend Dashboard', FS_AFFILIATES_LOCALE ) ; ?>:&nbsp;</label>
                                <label class="switch">
                                    <input type="checkbox" name="<?php echo $name . '[hide]' ?>" value="yes"/>
                                    <span class="slider round"></span>
                                </label>
                            </p>
                            <input type="button" class="fs_affiliates_remove_dashboard_tab_rule" value="<?php _e( 'Remove', FS_AFFILIATES_LOCALE ) ; ?>"/>
                        </div>
                    </td>
                </tr>
                <?php
                $content = \_WP_Editors::enqueue_scripts() ;
                $content .= \_WP_Editors::editor_js() ;
                $content .= ob_get_clean() ;

                wp_send_json_success( array( 'content' => $content ) ) ;
            } catch ( Exception $e ) {
                wp_send_json_error( array( 'error' => $e->getMessage() ) ) ;
            }
        }

        public static function fs_affiliates_referafriend_mails() {
            check_ajax_referer( 'affiliate-payment-nonce', 'fs_security' ) ;

            $affilate_id = isset( $_POST[ 'hidden_id' ] ) ? $_POST[ 'hidden_id' ] : '' ;


            if ( ! $affilate_id ) {
                echo 0 ;
            }

            $i = do_action( 'fs_affiliates_send_refer_a_friend_mail', $affilate_id ) ;

            echo $i ;

            exit( 0 ) ;
        }

        /**
         * send test pushover notification
         */
        public static function test_pushover_notification() {
            check_ajax_referer( 'fs-test-pushover-nonce', 'fs_security' ) ;

            try {
                if ( ! isset( $_REQUEST ) )
                    throw new exception( __( 'Invalid Request', FS_AFFILIATES_LOCALE ) ) ;

                FS_Affiliates_Pushover_Handler::send_test_pushover_notifications() ;

                wp_send_json_success( array( 'content' => 'success' ) ) ;
            } catch ( Exception $ex ) {
                wp_die() ;
            }
        }

        /**
         * Payouts Data Exporter
         */
        public static function export_payouts_data() {

            check_ajax_referer( 'fs-generate-payout-nonce', 'security' ) ;

            include_once( FS_AFFILIATES_PLUGIN_PATH . '/inc/admin/menu/exporter/class-fs-affiliates-payouts-data-exporter.php' ) ;

            global $wpdb ;
            $json_args  = $payoutData = array() ;

            parse_str( $_POST[ 'payoutData' ], $payoutData ) ;

            $selected_affiliates = implode( ', ', $payoutData[ 'referral' ][ 'selected_affiliate' ] ) ;

            if ( ! empty( $payoutData[ 'referral' ][ 'payout_method' ] ) ) {
                $affiliates = "SELECT DISTINCT ID FROM {$wpdb->posts} posts "
                        . "INNER JOIN {$wpdb->postmeta} postmeta ON posts.ID = postmeta.post_id "
                        . "WHERE posts.post_type=%s AND posts.post_status =%s" ;

                if ( ! empty( $selected_affiliates ) ) {
                    if ( $payoutData[ 'referral' ][ 'affiliate_select_type' ] == 'include' ) {
                        $affiliates .= " AND posts.ID IN($selected_affiliates)" ;
                    }
                    if ( $payoutData[ 'referral' ][ 'affiliate_select_type' ] == 'exclude' ) {
                        $affiliates .= " AND posts.ID NOT IN($selected_affiliates)" ;
                    }
                }

                $affiliates = $wpdb->prepare( $affiliates, 'fs-affiliates', 'fs_active' ) ;
                $affiliates = array_filter( $wpdb->get_col( $affiliates ) ) ;

                if ( ! fs_affiliates_check_is_array( $affiliates ) )
                    return ;

                $affiliates = implode( ', ', $affiliates ) ;
                $referrals  = "SELECT DISTINCT ID FROM {$wpdb->posts} posts "
                        . "INNER JOIN {$wpdb->postmeta} postmeta ON posts.ID = postmeta.post_id "
                        . "WHERE posts.post_type=%s AND posts.post_status=%s AND posts.post_author IN($affiliates)" ;

                if ( is_numeric( $payoutData[ 'referral' ][ 'min_threshold' ] ) ) {
                    $referrals .= "AND (postmeta.meta_key='amount' AND postmeta.meta_value >= {$payoutData[ 'referral' ][ 'min_threshold' ]})" ;
                }
                if ( ! empty( $payoutData[ 'referral' ][ 'from_date' ] ) ) {
                    $from_date = date( 'Y-m-d 00:00:00', strtotime( $payoutData[ 'referral' ][ 'from_date' ] ) ) ;
                    $referrals .= "AND posts.post_date >='{$from_date}'" ;
                }
                if ( ! empty( $payoutData[ 'referral' ][ 'to_date' ] ) ) {
                    $to_date   = date( 'Y-m-d 23:59:59', strtotime( $payoutData[ 'referral' ][ 'to_date' ] ) ) ;
                    $referrals .= "AND posts.post_date <='{$to_date}'" ;
                }
                $referrals = $wpdb->prepare( $referrals, 'fs-referrals', 'fs_unpaid' ) ;
                $referrals = array_filter( $wpdb->get_col( $referrals ) ) ;

                if ( sizeof( $referrals ) <= 10 ) {
                    $json_args[ 'export' ]         = 'done' ;
                    $json_args[ 'generated_data' ] = FS_Affiliates_Payouts_Data_Exporter::generate_data( $referrals, $payoutData[ 'referral' ][ 'payout_method' ] ) ;
                    $json_args[ 'redirect_url' ]   = FS_Affiliates_Payouts_Data_Exporter::get_download_url( $json_args[ 'generated_data' ] ) ;
                } else {
                    $json_args[ 'export' ]    = 'processing' ;
                    $json_args[ 'referrals' ] = $referrals ;
                }
            }

            wp_send_json( wp_parse_args( $json_args, array(
                'export'         => '',
                'generated_data' => array(),
                'referrals'      => array(),
                'redirect_url'   => FS_Affiliates_Payouts_Data_Exporter::get_download_url(),
            ) ) ) ;
        }

        /**
         * Chunk and Export payouts data
         */
        public static function process_payouts_data_export() {

            check_ajax_referer( 'fs-generate-payout-nonce', 'security' ) ;

            include_once( FS_AFFILIATES_PLUGIN_PATH . '/inc/admin/menu/exporter/class-fs-affiliates-payouts-data-exporter.php' ) ;

            $generated_data          = $json_args               = $payoutData              = array() ;
            $previous_generated_data = is_array( $_POST[ 'generated_data' ] ) ? $_POST[ 'generated_data' ] : array() ;

            parse_str( $_POST[ 'payoutData' ], $payoutData ) ;

            if ( ! empty( $_POST[ 'chunkedData' ] ) ) {
                $generated_data = FS_Affiliates_Payouts_Data_Exporter::generate_data( ( array ) $_POST[ 'chunkedData' ], $payoutData[ 'referral' ][ 'payout_method' ], $previous_generated_data ) ;
            }

            $json_args[ 'generated_data' ] = array_filter(  ! empty( $generated_data ) ? ($previous_generated_data + $generated_data) : $previous_generated_data ) ;

            if ( sizeof( $_POST[ 'originalData' ] ) === absint( $_POST[ 'step' ] ) ) {
                $json_args[ 'export' ]       = 'done' ;
                $json_args[ 'redirect_url' ] = FS_Affiliates_Payouts_Data_Exporter::get_download_url( $json_args[ 'generated_data' ] ) ;
            }

            wp_send_json( wp_parse_args( $json_args, array(
                'export'         => 'processing',
                'generated_data' => array(),
                'referrals'      => array(),
                'redirect_url'   => FS_Affiliates_Payouts_Data_Exporter::get_download_url(),
            ) ) ) ;
        }

        public static function fs_add_rule_for_product_rate() {
            check_ajax_referer( 'fs-product-rate-nonce', 'fs_security' ) ;

            if ( ! isset( $_POST ) || ! isset( $_POST[ 'count' ] ) )
                throw new exception( __( 'Invalid Request', FS_AFFILIATES_LOCALE ) ) ;

            try {
                ob_start() ;
                $key                    = $_POST[ 'count' ] ;
                ?>
                <tr>
                <input type="hidden" id="fs_product_rate_rule_id" value="<?php echo $key ; ?>"/>
                <td>
                    <?php
                    $product_selection_args = array(
                        'id'          => 'product_ids',
                        'name'        => 'affiliate[wc_product_rates][' . $key . '][products]',
                        'list_type'   => 'products',
                        'class'       => 'wc-product-search',
                        'action'      => 'fs_affiliates_products_search',
                        'placeholder' => __( 'Search a Product', FS_AFFILIATES_LOCALE ),
                        'multiple'    => true,
                        'selected'    => true,
                        'options'     => array(),
                            ) ;
                    fs_affiliates_select2_html( $product_selection_args ) ;
                    ?>
                </td>
                <td>
                    <select name='affiliate[wc_product_rates][<?php echo $key ; ?>][commission_type]' class='fs_affiliates_commission_type'>
                        <?php
                        $commission_options     = array(
                            'percentage' => __( 'Percentage Commission', FS_AFFILIATES_LOCALE ),
                            'fixed'      => __( 'Fixed Commission', FS_AFFILIATES_LOCALE ),
                                ) ;
                        foreach ( $commission_options as $type => $name ) {
                            ?>
                            <option value="<?php echo $type ; ?>"><?php echo $name ; ?></option>
                        <?php } ?>
                    </select>
                </td>
                <td>
                    <input type="text" name='affiliate[wc_product_rates][<?php echo $key ; ?>][commission_value]' class='fs_affiliates_commission_value' value=''/>
                </td>
                <td class="column-columnname num" scope="col">
                    <input class='button-primary fs_affiliates_remove_dashboard_tab_rule fs_remove_product_rates' type="button" value="<?php _e( 'Remove', FS_AFFILIATES_LOCALE ) ; ?>"
                </td>
                </tr>
                <?php
                $content = ob_get_clean() ;
                wp_send_json_success( array( 'content' => $content ) ) ;
            } catch ( Exception $e ) {
                wp_send_json_error( array( 'error' => $e->getMessage() ) ) ;
            }
        }

        /*
         * Request Unpaid Commission
         */

        public static function fp_request_unpaid_commission() {
            check_ajax_referer( 'unpaid-commission', 'fs_security' ) ;

            try {
                if ( ! isset( $_POST ) || ! isset( $_POST[ 'affiliateid' ] ) )
                    throw new exception( __( 'Invalid Request', FS_AFFILIATES_LOCALE ) ) ;

                $unpaid_amount = fs_affiliates_get_referrals_commission( $_POST[ 'affiliateid' ], 'unpaid' ) ;
                if ( $unpaid_amount < get_option( 'fs_affiliates_payout_request_payout_threshold' ) )
                    throw new exception( __( get_option( 'fs_affiliates_payout_request_errmsg_for_threshold' ), FS_AFFILIATES_LOCALE ) ) ;

                $args           = array( 'post_type' => 'fs-payout-request', 'post_status' => array( 'fs_submitted', 'fs_progress' ), 'numberposts' => -1, 'author' => $_POST[ 'affiliateid' ], 'fields' => 'ids' ) ;
                $payout_request = get_posts( $args ) ;
                if ( count( $payout_request ) >= 1 )
                    throw new exception( __( get_option( 'fs_affiliates_payout_request_errmsg_for_multiple_request' ), FS_AFFILIATES_LOCALE ) ) ;

                $postargs = array(
                    'post_author' => $_POST[ 'affiliateid' ],
                    'post_status' => 'fs_submitted',
                        ) ;

                $meta_data[ 'fs_affiliates_unpaid_commission' ] = $unpaid_amount ;

                $PostId = fs_affiliates_create_new_payout_request( $meta_data, $postargs ) ;

                do_action( 'fs_affiliate_send_payout_request_notification_to_admin', $_POST[ 'affiliateid' ] ) ;
                do_action( 'fs_affiliates_status_to_fs_submitted', $PostId ) ;
                wp_send_json_success( array( 'content' => get_option( 'fs_affiliates_payout_request_success_msg' ) ) ) ;
            } catch ( Exception $e ) {
                wp_send_json_error( array( 'error' => $e->getMessage() ) ) ;
            }
        }

    }

    FS_Affiliates_Admin_Ajax::init() ;
}

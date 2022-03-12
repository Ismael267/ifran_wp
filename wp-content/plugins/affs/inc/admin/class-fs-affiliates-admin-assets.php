<?php

/**
 * Admin Assets
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}
if ( ! class_exists( 'FS_Affiliates_Admin_Assets' ) ) {

    /**
     * Class.
     */
    class FS_Affiliates_Admin_Assets {

        /**
         * Class Initialization.
         */
        public static function init() {

            add_action( 'admin_enqueue_scripts' , array( __CLASS__ , 'external_js_files' ) ) ;
            add_action( 'admin_enqueue_scripts' , array( __CLASS__ , 'external_css_files' ) ) ;
        }

        /**
         * Enqueue external css files
         */
        public static function external_css_files() {
            if ( ! apply_filters( 'fs_affiliates_allow_admin_css_files' , true ) ) {
                return ;
            }

            $suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min' ;

            wp_enqueue_style( 'fs_affiliates-font-awesome' , FS_AFFILIATES_PLUGIN_URL . '/assets/css/font-awesome.min.css' , array() , FS_AFFILIATES_VERSION ) ;

            $newscreenids = get_current_screen() ;

            $screen_ids = array(
                'toplevel_page_fs_affiliates' ,
                'product_cat' ,
                'product' ,
                'shop_order'
                    ) ;

            $screenid = str_replace( 'edit-' , '' , $newscreenids->id ) ;

            if ( ! in_array( $screenid , $screen_ids ) )
                return ;

            include_once(FS_AFFILIATES_PLUGIN_PATH . '/assets/css/backend/main-tab-design.php') ;
            include_once(FS_AFFILIATES_PLUGIN_PATH . '/assets/css/backend/module-grid.php') ;
            include_once(FS_AFFILIATES_PLUGIN_PATH . '/assets/css/backend/sub-menu-design.php') ;
            include_once(FS_AFFILIATES_PLUGIN_PATH . '/assets/css/backend/post-table-design.php') ;
            include_once(FS_AFFILIATES_PLUGIN_PATH . '/assets/css/backend/overview-table-design.php') ;

            wp_enqueue_style( 'fs_affiliates-jquery-ui' , FS_AFFILIATES_PLUGIN_URL . '/assets/css/jquery-ui.css' , array() , FS_AFFILIATES_VERSION ) ;
            wp_enqueue_style( 'fs_affiliates-integreation-grid' , FS_AFFILIATES_PLUGIN_URL . '/assets/css/backend/integreation-grid.css' , array() , FS_AFFILIATES_VERSION ) ;
            wp_enqueue_style( 'fs_affiliates-additionaltab-table-design' , FS_AFFILIATES_PLUGIN_URL . '/assets/css/backend/additionaltab-table-design.css' , array() , FS_AFFILIATES_VERSION ) ;
            wp_enqueue_style( 'fs_affiliates-notification-grid' , FS_AFFILIATES_PLUGIN_URL . '/assets/css/backend/notification-grid.css' , array() , FS_AFFILIATES_VERSION ) ;
        }

        /**
         * Enqueue external js files
         */
        public static function external_js_files() {
            $suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min' ;

            $newscreenids = get_current_screen() ;
            $screen_ids   = array(
                'toplevel_page_fs_affiliates' ,
                'product_cat' ,
                'product' ,
                'shop_order'
                    ) ;

            $screenid = str_replace( 'edit-' , '' , $newscreenids->id ) ;

            $enqueue_array = array(
                'fs_affiliates-admin'   => array(
                    'callable' => array( 'FS_Affiliates_Admin_Assets' , 'admin' ) ,
                    'restrict' => in_array( $screenid , $screen_ids ) ,
                ) ,
                'fs_affiliates-select2' => array(
                    'callable' => array( 'FS_Affiliates_Admin_Assets' , 'select2' ) ,
                    'restrict' => in_array( $screenid , $screen_ids ) ,
                ) ,
                'fs_affiliates-report'  => array(
                    'callable' => array( 'FS_Affiliates_Admin_Assets' , 'report' ) ,
                    'restrict' => isset( $_GET[ 'tab' ] ) && $_GET[ 'tab' ] == 'reports' ,
                ) ,
                    ) ;

            $enqueue_array = apply_filters( 'fs_affiliates_admin_assets_array' , $enqueue_array ) ;

            if ( ! fs_affiliates_check_is_array( $enqueue_array ) )
                return ;

            foreach ( $enqueue_array as $key => $enqueue ) {
                if ( ! fs_affiliates_check_is_array( $enqueue ) )
                    continue ;

                if ( $enqueue[ 'restrict' ] )
                    call_user_func_array( $enqueue[ 'callable' ] , array( $suffix ) ) ;
            }
        }

        /**
         * Enqueue Admin end required JS files
         */
        public static function admin( $suffix ) {
            //media
            wp_enqueue_media() ;

            //Google Re-Captcha
            wp_register_script( 'fs-affiliates-recaptcha' , 'https://www.google.com/recaptcha/api.js' , array( 'jquery' ) ) ;

            wp_enqueue_script( 'fs_affiliates-jquery-ui' , FS_AFFILIATES_PLUGIN_URL . '/assets/js/jquery-ui.js' , array( 'jquery' ) , FS_AFFILIATES_VERSION ) ;
            wp_register_script( 'jquery.tiptip' , FS_AFFILIATES_PLUGIN_URL . '/assets/js/tipTip/jquery.tipTip' . $suffix . '.js' , array( 'jquery' ) , '1.3.0' ) ;
            wp_register_script( 'fs_blockUI' , FS_AFFILIATES_PLUGIN_URL . '/assets/js/blockUI/jquery.blockUI.js' , array( 'jquery' ) , '2.70.0' ) ;
            wp_enqueue_script( 'fs_affiliates-admin' , FS_AFFILIATES_PLUGIN_URL . '/assets/js/admin/admin.js' , array( 'jquery' , 'jquery.tiptip' ) , FS_AFFILIATES_VERSION ) ;

            wp_localize_script(
                    'fs_affiliates-admin' , 'fs_affiliates_admin_params' , array(
                /* translators: %s: price decimal separator */
                'non_decimal_error' => sprintf( __( 'Please enter in monetary decimal (%s) format without thousand separators and currency symbols.' , FS_AFFILIATES_LOCALE ) , fs_affiliates_get_decimal_separator() ) ,
                'mon_decimal_point' => fs_affiliates_get_decimal_separator() ,
                    )
            ) ;

            wp_enqueue_script( 'fs_affiliates-settings' , FS_AFFILIATES_PLUGIN_URL . '/assets/js/admin/settings.js' , array( 'jquery' , 'fs_blockUI' , 'jquery-ui-sortable' , 'iris' ) , FS_AFFILIATES_VERSION ) ;
            wp_localize_script(
                    'fs_affiliates-settings' , 'fs_affiliates_settings_params' , array(
                'field_sort_nonce'                  => wp_create_nonce( 'fs-field-sort-nonce' ) ,
                'module_nonce'                      => wp_create_nonce( 'fs-module-nonce' ) ,
                'bulk_update_nonce'                 => wp_create_nonce( 'fs-bulk-update-nonce' ) ,
                'settings_color_mode_nonce'         => wp_create_nonce( 'fs-settings-color-mode-nonce' ) ,
                'integration_nonce'                 => wp_create_nonce( 'fs-integration-nonce' ) ,
                'notification_nonce'                => wp_create_nonce( 'fs-notification-nonce' ) ,
                'bulk_update_confirm_message'       => __( 'Are you sure you want to register the selected users as affiliate(s)?' , FS_AFFILIATES_LOCALE ) ,
                'affiliate_delete_message'          => __( 'Are you sure you want to delete this affiliate?' , FS_AFFILIATES_LOCALE ) ,
                'referral_delete_message'           => __( 'Are you sure you want to delete this referral?' , FS_AFFILIATES_LOCALE ) ,
                'landing_commission_delete_message' => __( 'Are you sure you want to delete this Landing Commission?' , FS_AFFILIATES_LOCALE ) ,
                'basic_dashboard_label'             => esc_html__( 'Affiliate Signup/Dashboard Page' , FS_AFFILIATES_LOCALE ) ,
                'basic_dashboard_description'       => esc_html__( 'Page to display the Basic Mode Signup/Affiliate Dashboard' , FS_AFFILIATES_LOCALE ) ,
                'advance_dashboard_label'           => esc_html__( 'Affiliate Dashboard Page' , FS_AFFILIATES_LOCALE ) ,
                'advance_dashboard_description'     => esc_html__( 'Page to display the Advanced Mode Dashboard Page' , FS_AFFILIATES_LOCALE )
                    )
            ) ;

            wp_enqueue_script( 'fs_affiliates' , FS_AFFILIATES_PLUGIN_URL . '/assets/js/admin/affiliates.js' , array( 'jquery' , 'fs_blockUI' , 'jquery-ui-datepicker' ) , FS_AFFILIATES_VERSION ) ;
            wp_localize_script(
                    'fs_affiliates' , 'fs_affiliates_params' , array(
                'username_validation_msg'      => __( 'Username should not be empty' , FS_AFFILIATES_LOCALE ) ,
                'useremail_validation_msg'     => __( 'Email should not be empty' , FS_AFFILIATES_LOCALE ) ,
                'referral_reject_reason_label' => esc_html__( 'Would you like to give reason?' , FS_AFFILIATES_LOCALE ) ,
                'username_nonce'               => wp_create_nonce( 'fs-username-nonce' ) ,
                'useremail_nonce'              => wp_create_nonce( 'fs-useremail-nonce' ) ,
                'product_rate'                 => wp_create_nonce( 'fs-product-rate-nonce' ) ,
                'referral_rejected_nonce'      => wp_create_nonce( 'fs-referral-rejected-nonce' ) ,
                    )
            ) ;

            wp_enqueue_script( 'fs_affiliates_modules' , FS_AFFILIATES_PLUGIN_URL . '/assets/js/admin/modules.js' , array( 'jquery' , 'fs_blockUI' ) , FS_AFFILIATES_VERSION ) ;
            wp_localize_script(
                    'fs_affiliates_modules' , 'fs_affiliates_modules_params' , array(
                'mlm_nonce'                            => wp_create_nonce( 'fs-mlm-nonce' ) ,
                'dashboard_tab_nonce'                  => wp_create_nonce( 'fs-dashboard-tab-nonce' ) ,
                'test_pushover_nonce'                  => wp_create_nonce( 'fs-test-pushover-nonce' ) ,
                'ranking_nonce'                        => wp_create_nonce( 'fs-ranking-nonce' ) ,
                'default_error_msg'                    => __( 'Cannot delete this rule' , FS_AFFILIATES_LOCALE ) ,
                'pushover_success_msg'                 => __( 'Pushover notification send successfully' , FS_AFFILIATES_LOCALE ) ,
                'pushover_alert_msg'                   => __( 'Are you sure want to send Pushover notification to admin' , FS_AFFILIATES_LOCALE ) ,
                'export_selected_affiliates_error_msg' => __( 'Cannot empty this selected Affiliates' , FS_AFFILIATES_LOCALE ) ,
                'is_woo_product_restriction_module'    => (isset( $_REQUEST[ 'section' ] ) && 'wc_product_restriction' == wp_unslash( $_REQUEST[ 'section' ] )) ? true : false ,
                'product_select_msg'                   => esc_html__( 'Please select atleast one product' , FS_AFFILIATES_LOCALE ) ,
                'category_select_msg'                  => esc_html__( 'Please select atleast one category' , FS_AFFILIATES_LOCALE )
                    )
            ) ;

            $paypal_payouts = FS_Affiliates_Module_Instances::get_module_by_id( 'paypal_payouts' ) ;

            wp_enqueue_script( 'fs_affiliates_creatives' , FS_AFFILIATES_PLUGIN_URL . '/assets/js/admin/creatives.js' , array( 'jquery' , 'fs_blockUI' ) , FS_AFFILIATES_VERSION ) ;

            wp_enqueue_script( 'fs_affiliates_generate_payout' , FS_AFFILIATES_PLUGIN_URL . '/assets/js/admin/generate-payout.js' , array( 'jquery' , 'fs_blockUI' ) , FS_AFFILIATES_VERSION ) ;
            wp_localize_script( 'fs_affiliates_generate_payout' , 'fs_affiliates_generate_payout_params' , array(
                'generate_payout_nonce'      => wp_create_nonce( 'fs-generate-payout-nonce' ) ,
                'is_paypal_payouts_enabled'  => ( int ) $paypal_payouts->is_enabled() ,
                'payment_select_error_msg'   => esc_html__( 'Select Payout Method' , FS_AFFILIATES_LOCALE ) ,
                'affiliate_select_error_msg' => esc_html__( 'Select Affiliate(s)' , FS_AFFILIATES_LOCALE ) ,
                    )
            ) ;

            wp_enqueue_script( 'fs_affiliates_file_upload' , FS_AFFILIATES_PLUGIN_URL . '/assets/js/jquery.fileupload.js' , array( 'jquery' , 'jquery-ui-widget' ) , FS_AFFILIATES_VERSION ) ;

            wp_enqueue_script( 'fs_affiliates_integrations' , FS_AFFILIATES_PLUGIN_URL . '/assets/js/admin/integrations.js' , array( 'jquery' ) , FS_AFFILIATES_VERSION ) ;
        }

        /**
         * Enqueue select2 scripts and css
         */
        public static function select2( $suffix ) {
            wp_enqueue_style( 'select2-css' , FS_AFFILIATES_PLUGIN_URL . '/assets/css/select2/select2' . $suffix . '.css' , array() , '4.0.5' ) ;

            wp_register_script( 'select2' , FS_AFFILIATES_PLUGIN_URL . '/assets/js/select2/select2.full' . $suffix . '.js' , array( 'jquery' ) , '4.0.5' ) ;
            wp_enqueue_script( 'fs-enhanced-select2' , FS_AFFILIATES_PLUGIN_URL . '/assets/js/fs-enhanced-select.js' , array( 'jquery' , 'select2' ) , FS_AFFILIATES_VERSION ) ;
            wp_localize_script(
                    'fs-enhanced-select2' , 'fs_enhanced_select_params' , array(
                'i18n_no_matches'           => esc_html__( 'No matches found' , FS_AFFILIATES_LOCALE ) ,
                'i18n_input_too_short_1'    => esc_html__( 'Please enter 1 or more characters' , FS_AFFILIATES_LOCALE ) ,
                'i18n_input_too_short_n'    => esc_html__( 'Please enter %qty% or more characters' , FS_AFFILIATES_LOCALE ) ,
                'i18n_input_too_long_1'     => esc_html__( 'Please delete 1 character' , FS_AFFILIATES_LOCALE ) ,
                'i18n_input_too_long_n'     => esc_html__( 'Please delete %qty% characters' , FS_AFFILIATES_LOCALE ) ,
                'i18n_selection_too_long_1' => esc_html__( 'You can only select 1 item' , FS_AFFILIATES_LOCALE ) ,
                'i18n_selection_too_long_n' => esc_html__( 'You can only select %qty% items' , FS_AFFILIATES_LOCALE ) ,
                'i18n_load_more'            => esc_html__( 'Loading more results&hellip;' , FS_AFFILIATES_LOCALE ) ,
                'i18n_searching'            => esc_html__( 'Searching&hellip;' , FS_AFFILIATES_LOCALE ) ,
                'ajax_url'                  => admin_url( 'admin-ajax.php' ) ,
                'search_nonce'              => wp_create_nonce( 'fs-search-nonce' ) ,
                    )
            ) ;
        }

        /**
         * Enqueue report scripts
         */
        public static function report() {
            wp_enqueue_script( 'common' ) ;
            wp_enqueue_script( 'wp-lists' ) ;
            wp_enqueue_script( 'postbox' ) ;
            wp_enqueue_script( 'fs-moment-js' , FS_AFFILIATES_PLUGIN_URL . '/assets/js/admin/moment.js' , '' , FS_AFFILIATES_VERSION ) ;
            wp_enqueue_script( 'fs-Chart-js' , FS_AFFILIATES_PLUGIN_URL . '/assets/js/admin/Chart.js' , '' , FS_AFFILIATES_VERSION ) ;
        }

    }

    FS_Affiliates_Admin_Assets::init() ;
}

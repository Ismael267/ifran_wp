<?php

/**
 * Frontend Assets
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}
if ( ! class_exists( 'FS_Affiliates_Fronend_Assets' ) ) {

    /**
     * Class.
     */
    class FS_Affiliates_Fronend_Assets {

        /**
         * Class Initialization.
         */
        public static function init() {

            add_action( 'wp_enqueue_scripts' , array( __CLASS__ , 'external_js_files' ) ) ;
            add_action( 'wp_enqueue_scripts' , array( __CLASS__ , 'external_css_files' ) ) ;
        }

        /**
         * Enqueue external css files
         */
        public static function external_css_files() {

            wp_enqueue_style( 'fs_affiliates-font-awesome' , FS_AFFILIATES_PLUGIN_URL . '/assets/css/font-awesome.min.css' , array() , FS_AFFILIATES_VERSION ) ;
            wp_enqueue_style( 'fs_affiliates-status' , FS_AFFILIATES_PLUGIN_URL . '/assets/css/frontend/frontend-status-button-design.css' , array() , FS_AFFILIATES_VERSION ) ;
            wp_enqueue_style( 'fs_affiliates-dashboard' , FS_AFFILIATES_PLUGIN_URL . '/assets/css/frontend/dashboard.css' , array() , FS_AFFILIATES_VERSION ) ;
            wp_enqueue_style( 'fs_affiliates-mobile-responsive' , FS_AFFILIATES_PLUGIN_URL . '/assets/css/frontend/table-mobile-responsive.css' , array() , FS_AFFILIATES_VERSION ) ;
        }

        /**
         * Enqueue external js files
         */
        public static function external_js_files() {
            $suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min' ;

            wp_register_script( 'fs_blockUI' , FS_AFFILIATES_PLUGIN_URL . '/assets/js/blockUI/jquery.blockUI.js' , array( 'jquery' ) , '2.70.0' ) ;
            self::select2( $suffix ) ;

            wp_enqueue_script( 'fs-affiliates-form' , FS_AFFILIATES_PLUGIN_URL . '/assets/js/frontend/form.js' , array( 'jquery' , 'fs_blockUI' ) , FS_AFFILIATES_VERSION ) ;
            wp_localize_script(
                    'fs-affiliates-form' , 'fs_affiliates_form_params' , array(
                'ajax_url'                 => admin_url( 'admin-ajax.php' ) ,
                'username_validation_msg'  => __( 'Username should not be empty' , FS_AFFILIATES_LOCALE ) ,
                'useremail_validation_msg' => __( 'Email should not be empty' , FS_AFFILIATES_LOCALE ) ,
                'username_nonce'           => wp_create_nonce( 'fs-username-nonce' ) ,
                'useremail_nonce'          => wp_create_nonce( 'fs-useremail-nonce' ) ,
                'url_nonce'                => wp_create_nonce( 'fs-url-nonce' ) ,
                'fbshare'                  => get_option( 'fs_affiliates_socialshare_fbshare' ) ,
                'tweet'                    => get_option( 'fs_affiliates_socialshare_tweet' ) ,
                'gplusshare'               => get_option( 'fs_affiliates_socialshare_gplus_share' ) ,
                'vkshare'                  => get_option( 'fs_affiliates_socialshare_vkshare' ) ,
                'fbappid'                  => get_option( 'fs_affiliates_socialshare_fbappid' ) ,
                'redirecturl'              => get_permalink() ,
                'referral_url_type'        => get_option( 'fs_affiliates_referral_link_type' , '1' ) ,
                'static_referral_url'      => get_option( 'fs_affiliates_static_referral_url' , site_url() ) ,
                'empty_campain_msg'        => esc_html__( ' Please select the Campaign' , FS_AFFILIATES_LOCALE ) ,
                'is_checkout_page'         => ( function_exists( 'is_checkout' ) && is_checkout() ) ? is_checkout() : false
                    )
            ) ;

            $LocalizeScript = array(
                'fbappid'     => get_option( 'fs_affiliates_socialshare_fbappid' ) ,
                'fbshare'     => get_option( 'fs_affiliates_socialshare_fbshare' ) ,
                'tweet'       => get_option( 'fs_affiliates_socialshare_tweet' ) ,
                'gplusshare'  => get_option( 'fs_affiliates_socialshare_gplus_share' ) ,
                'vkshare'     => get_option( 'fs_affiliates_socialshare_vkshare' ) ,
                'redirecturl' => get_permalink() ,
                'success_msg' => __( 'Sucessfully Posted' , FS_AFFILIATES_LOCALE ) ,
                'cancel_msg'  => __( 'Cancel' , FS_AFFILIATES_LOCALE ) ,
                    ) ;
            wp_enqueue_script( 'fs_social_actions' , FS_AFFILIATES_PLUGIN_URL . '/assets/js/frontend/fpsocialactions.js' , array( 'jquery' ) , FS_AFFILIATES_VERSION ) ;
            wp_localize_script( 'fs_social_actions' , 'fs_social_action_params' , $LocalizeScript ) ;

            if ( get_option( 'fs_affiliates_socialshare_vkshare' ) == 'yes' )
                wp_enqueue_script( 'fp_vkshare_button' , 'https://vkontakte.ru/js/api/share.js?5' ) ;

            wp_enqueue_script( 'fs-affiliates-dashboard' , FS_AFFILIATES_PLUGIN_URL . '/assets/js/frontend/dashboard.js' , array( 'jquery' ) , FS_AFFILIATES_VERSION ) ;
            wp_localize_script( 'fs-affiliates-dashboard' , 'fs_affiliates_dashboard_params' , array(
                'ajax_url'               => admin_url( 'admin-ajax.php' ) ,
                'pay_save_nonce'         => wp_create_nonce( 'affiliate-payment-nonce' ) ,
                'unpaid_commission'      => wp_create_nonce( 'unpaid-commission' ) ,
                'request_submit_confirm' => esc_html__( 'Are you sure, you want to submit the request?' , FS_AFFILIATES_LOCALE )
                    )
            ) ;

            wp_register_script( 'fs_affiliates_file_upload' , FS_AFFILIATES_PLUGIN_URL . '/assets/js/jquery.fileupload.js' , array( 'jquery' , 'jquery-ui-widget' ) , FS_AFFILIATES_VERSION ) ;

            wp_register_script( 'fs-affiliates-recaptcha' , 'https://www.google.com/recaptcha/api.js' , array( 'jquery' ) ) ;

            wp_register_script( 'fs-affiliates-checkout' , FS_AFFILIATES_PLUGIN_URL . '/assets/js/frontend/checkout.js' , array( 'jquery' ) ) ;
        }

        /**
         * Enqueue select2 scripts and css
         */
        public static function select2( $suffix ) {
            wp_enqueue_style( 'select2-css' , FS_AFFILIATES_PLUGIN_URL . '/assets/css/select2/select2' . $suffix . '.css' , '4.0.5' ) ;

            wp_register_script( 'select2' , FS_AFFILIATES_PLUGIN_URL . '/assets/js/select2/select2.full' . $suffix . '.js' , array( 'jquery' ) , '4.0.5' ) ;
            wp_enqueue_script( 'fs-enhanced-select2' , FS_AFFILIATES_PLUGIN_URL . '/assets/js/fs-enhanced-select.js' , array( 'jquery' , 'select2' ) , FS_AFFILIATES_VERSION ) ;
            wp_localize_script(
                    'fs-enhanced-select2' , 'fs_enhanced_select_params' , array(
                'ajax_url'     => admin_url( 'admin-ajax.php' ) ,
                'search_nonce' => wp_create_nonce( 'fs-search-nonce' ) ,
                    )
            ) ;
        }

    }

    FS_Affiliates_Fronend_Assets::init() ;
}

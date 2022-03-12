<?php

/*
 * Plugin Name: SUMO Affiliates Pro
 * Description: SUMO Affiliates Pro is a Comprehensive WordPress Affiliates Plugin using which you can run an affiliate system on your existing WordPress Site. You can award affiliate commissions for actions such as Affiliate signup, Form Submission, Product Purchases, etc.
 * Version: 8.0
 * Author: Fantastic Plugins
 * Author URI: http://fantasticplugins.com/
 * WC requires at least: 3.0.0
 * WC tested up to: 6.2
 */

/*
  Copyright 2014 SUMO Affiliates Pro. All Rights Reserved.
  This Software should not be used or changed without the permission
  of Fantastic Plugins.
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!class_exists('FS_Affiliates')) {

    /**
     * Main FS_Affiliates Class.
     * */
    final class FS_Affiliates {

        /**
         * Version
         * */
        private $version = '8.0';

        /**
         * Modules
         * */
        protected $modules;

        /**
         * Integrations
         * */
        protected $integrations;

        /**
         * Notifications
         * */
        protected $notifications;

        /**
         * Query
         * */
        protected $query;

        /**
         * The single instance of the class.
         * */
        protected static $_instance = null;

        /**
         * Load FS_Affiliates Class in Single Instance
         */
        public static function instance() {
            if (is_null(self::$_instance)) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        /* Cloning has been forbidden */

        public function __clone() {
            _doing_it_wrong(__FUNCTION__, 'You are not allowed to perform this action!!!', $this->version);
        }

        /**
         * Unserialize the class data has been forbidden
         * */
        public function __wakeup() {
            _doing_it_wrong(__FUNCTION__, 'You are not allowed to perform this action!!!', $this->version);
        }

        /**
         * Constructor
         * */
        public function __construct() {

            /* Include once will help to avoid fatal error by load the files when you call init hook */
            include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

            $this->header_already_sent_problem();
            $this->define_constants();
            $this->include_files();
            $this->init_hooks();
        }

        /**
         * Function to prevent header error that says you have already sent the header.
         */
        private function header_already_sent_problem() {
            ob_start();
        }

        /**
         * Initialize the translate files.
         * */
        private function translate_file() {
            if (function_exists('determine_locale')) {
                $locale = determine_locale();
            } else {
                // @todo Remove when start supporting WP 5.0 or later.
                $locale = is_admin() ? get_user_locale() : get_locale();
            }
            
            $locale = apply_filters('plugin_locale', $locale, FS_AFFILIATES_LOCALE);

            unload_textdomain(FS_AFFILIATES_LOCALE);

            load_textdomain(FS_AFFILIATES_LOCALE, WP_LANG_DIR . '/affs/affs-' . $locale . '.mo');

            load_plugin_textdomain(FS_AFFILIATES_LOCALE, false, dirname(plugin_basename(__FILE__)) . '/languages');
        }

        /**
         * Prepare the constants value array.
         * */
        private function define_constants() {

            $protocol = 'http://';

            if (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1) || isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
                $protocol = 'https://';
            }

            $constant_array = array(
                'FS_AFFILIATES_VERSION' => $this->version,
                'FS_AFFILIATES_PLUGIN_FILE' => __FILE__,
                'FS_AFFILIATES_LOCALE' => 'affs',
                'FS_AFFILIATES_FOLDER_NAME' => 'affs',
                'FS_AFFILIATES_PROTOCOL' => $protocol,
                'FS_AFFILIATES_ADMIN_URL' => admin_url('admin.php'),
                'FS_AFFILIATES_ADMIN_AJAX_URL' => admin_url('admin-ajax.php'),
                'FS_AFFILIATES_PLUGIN_BASE_NAME' => plugin_basename(__FILE__),
                'FS_AFFILIATES_PLUGIN_PATH' => untrailingslashit(plugin_dir_path(__FILE__)),
                'FS_AFFILIATES_PLUGIN_URL' => untrailingslashit(plugins_url('/', __FILE__)),
            );

            $constant_array = apply_filters('fs_affiliates_define_constants', $constant_array);

            if (is_array($constant_array) && !empty($constant_array)) {
                foreach ($constant_array as $name => $value) {
                    $this->define_constant($name, $value);
                }
            }
        }

        /**
         * Define the Constants value.
         * */
        private function define_constant($name, $value) {
            if (!defined($name)) {
                define($name, $value);
            }
        }

        /**
         * Include required files
         * */
        private function include_files() {

            //function
            include_once('inc/fs-affiliates-common-functions.php');

            include_once('inc/integrations/class-fs-affiliates-integration-instances.php');
            include_once('inc/modules/class-fs-affiliates-module-instances.php');
            include_once('inc/notifications/class-fs-affiliates-notification-instances.php');

            //class
            include_once('inc/class-fs-affiliates-query.php');
            include_once('inc/class-fs-affiliates-sms-handler.php');
            include_once('inc/class-fs-affiliates-pushover-handler.php');
            include_once('inc/class-fs-affiliates-install.php');
            include_once('inc/class-fs-affiliates-file-uploader.php');
            include_once('inc/privacy/class-fs-affiliates-privacy.php');
            include_once('inc/welcome/class-fs-affiliates-welcome-page.php');
            include_once('inc/class-fs-affiliates-register-post-status.php');
            include_once('inc/class-fs-affiliates-register-post-type.php');
            include_once('inc/abstracts/class-fs-affiliates-post.php');

            include_once('inc/class-fs-affiliates.php');
            include_once('inc/class-fs-affiliates-wallet.php');
            include_once('inc/class-fs-affiliates-url_masking.php');
            include_once('inc/class-fs-affiliates-payouts.php');
            include_once('inc/class-fs-affiliates-payout-request.php');
            include_once('inc/class-fs-affiliates-referrals.php');
            include_once('inc/class-fs-affiliates-visits.php');
            include_once('inc/class-fs-affiliates-landing-commission.php');
            include_once('inc/class-fs-affiliates-download-handler.php');
            include_once('inc/class-fs-affiliates-creatives.php');
            include_once('inc/class-fs-wc-coupon-linking.php');

            if (is_admin())
                $this->include_admin_files();

            if (!is_admin() || defined('DOING_AJAX'))
                $this->include_frontend_files();

            $this->query = new FS_Affiliates_Query();
        }

        /**
         * Include admin files
         * */
        private function include_admin_files() {
            include_once('inc/class-fs-affiliates-pages.php');
            include_once('inc/class-fs-affiliates-data-export-handler.php');
            include_once('inc/admin/class-fs-affiliates-user-profile.php');
            include_once('inc/admin/class-fs-affiliates-admin-assets.php');
            include_once('inc/admin/class-fs-affiliates-admin-ajax.php');
            include_once('inc/admin/menu/class-fs-affiliates-menu-management.php');
        }

        /**
         * Include frontend files
         * */
        private function include_frontend_files() {
            include_once('inc/frontend/class-fs-affiliates-frontend-assets.php');
            include_once('inc/frontend/class-fs-affiliates-shortcodes.php');
            include_once('inc/frontend/class-fs-affiliates-dashboard.php');
            include_once('inc/frontend/class-fs-affiliates-cookie-handler.php');
            include_once('inc/frontend/class-fs-affiliates-form-handler.php');
        }

        /**
         * Define the hooks
         * */
        private function init_hooks() {

            // Init the plugin.
            add_action('init', array($this, 'init'));

            add_action('plugins_loaded', array($this, 'plugins_loaded'));

            register_activation_hook(__FILE__, array('FS_Affiliates_Install', 'install'));
        }

        /**
         * Init.
         * */
        public function init() {
            $this->translate_file();
        }

        /**
         * Plugins Loaded
         * */
        public function plugins_loaded() {
            $this->notifications = FS_Affiliates_Notification_Instances::get_notifications();
            $this->integrations = FS_Affiliates_Integration_Instances::get_integrations();
            $this->modules = FS_Affiliates_Module_Instances::get_modules();
        }

        /**
         * Modules instances
         * */
        public function modules() {
            return $this->modules;
        }

        /**
         * Integrations instances
         * */
        public function integrations() {
            return $this->integrations;
        }

        /**
         * Notifications instances
         * */
        public function notifications() {
            return $this->notifications;
        }

    }

}

if (!function_exists('FS_AFFILIATES')) {

    function FS_AFFILIATES() {
        if (class_exists('FS_Affiliates'))
            return FS_Affiliates::instance();

        return false;
    }

}

//initialize the plugin.
FS_AFFILIATES();

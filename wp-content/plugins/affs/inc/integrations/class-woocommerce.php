<?php

/**
 * WooCommerce
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!class_exists('FS_Affiliates_Woocommerce')) {

    /**
     * Class FS_Affiliates_Woocommerce
     */
    class FS_Affiliates_Woocommerce extends FS_Affiliates_Integrations {

        /**
         * Class Constructor
         */
        public function __construct() {
            $this->id = 'woocommerce';
            $this->title = __('WooCommerce', FS_AFFILIATES_LOCALE);

            parent::__construct();
        }

        /*
         * is enabled
         */

        public function is_enabled() {

            return ($this->is_plugin_enabled() && 'yes' === $this->enabled);
        }

        /*
         * Admin Actions
         */

        public function admin_action() {
            include_once(FS_AFFILIATES_PLUGIN_PATH . '/inc/woocommerce/class-fs-affiliates-wc-category-level-settings.php');
            include_once(FS_AFFILIATES_PLUGIN_PATH . '/inc/woocommerce/class-fs-affiliates-wc-product-level-settings.php');

            //Category Level 
            add_action('product_cat_add_form_fields', array('FS_Affiliates_WC_Category_Level_Settings', 'category_level_settings_in_add_form'));
            add_action('product_cat_edit_form_fields', array('FS_Affiliates_WC_Category_Level_Settings', 'category_level_settings_in_edit_form'), 10, 2);
            add_action('created_term', array('FS_Affiliates_WC_Category_Level_Settings', 'save_category_level_settings'), 10, 3);
            add_action('edit_term', array('FS_Affiliates_WC_Category_Level_Settings', 'save_category_level_settings'), 10, 3);

            //Product Level
            add_action('woocommerce_product_options_general_product_data', array('FS_Affiliates_WC_Product_Level_Settings', 'product_level_settings_for_simple_product'), 1);
            add_action('woocommerce_product_after_variable_attributes', array('FS_Affiliates_WC_Product_Level_Settings', 'product_level_settings_for_variable_product'), 10, 3);
            add_action('woocommerce_save_product_variation', array('FS_Affiliates_WC_Product_Level_Settings', 'save_variant_level_settings'), 10, 2);
            add_action('woocommerce_process_product_meta', array('FS_Affiliates_WC_Product_Level_Settings', 'save_product_level_settings'), 10, 2);

            //Order column
            add_action('restrict_manage_posts', array($this , 'affiliate_post_filter'));
            add_action('posts_where', array($this , 'affiliate_filter_display'), 10, 2);
            add_filter('manage_edit-shop_order_columns', array($this, 'custom_shop_order_column'), 20);
            add_action('manage_shop_order_posts_custom_column', array($this, 'custom_orders_list_column_content'), 20, 2);
        }

        /*
         * Plugin enabled
         */

        public function is_plugin_enabled() {
            return fs_affiliates_check_if_woocommerce_is_active();
        }

        public function affiliate_post_filter($post_type) {
            if ('shop_order' != $post_type) {
                return;
            }

            $parent_selection_args = array(
                'name' => 'affs_search_affiliate',
                'list_type' => 'affiliates',
                'action' => 'fs_affiliates_search',
                'placeholder' => esc_html__('Filter by affiliate', FS_AFFILIATES_LOCALE),
                'multiple' => false,
                'selected' => true,
                'options' => array(),
                    );
            
            fs_affiliates_select2_html($parent_selection_args);
        }
        
        public function affiliate_filter_display($where, $wp_query){
            global $wpdb;
            
            if(!is_object($wpdb))
                return $where;
            
            if (isset($wp_query->query['post_type']) && $wp_query->query['post_type'] != 'shop_order')
                return $where;

            if (isset($_REQUEST['filter_action']) && $_REQUEST['post_type'] == 'shop_order') {
                $search_affiliate = isset($_REQUEST['affs_search_affiliate']) ? $_REQUEST['affs_search_affiliate'] : '';
                $search_affiliate = ( is_array ($search_affiliate) && !empty($search_affiliate)) ? $search_affiliate[0] : '';
                
                if ( !empty($search_affiliate) ){
                    $where .= " AND ID IN (SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = 'fs_affiliate_in_order' AND meta_value = $search_affiliate )";
                }

            }
            
            return $where;
            
        }

        /*
         * Actions
         */

        public function actions() {

            include_once(FS_AFFILIATES_PLUGIN_PATH . '/inc/woocommerce/class-fs-affiliates-wc-commission.php');

            $OrderStatusToAward = get_option('fs_affiliates_order_status_to_approved', array('processing', 'completed'));
            if (fs_affiliates_check_is_array($OrderStatusToAward)) {
                foreach ($OrderStatusToAward as $Status) {
                    add_action('woocommerce_order_status_' . $Status, array('FS_Affiliates_WC_Commission', 'insert_referrals_post'), 1);
                }
            }

            $OrderStatusToRejectUnpaidReferrals = get_option('fs_affiliates_order_status_to_reject_unpaid_referral', array('cancelled', 'failed', 'refunded'));

            if (fs_affiliates_check_is_array($OrderStatusToRejectUnpaidReferrals)) {
                foreach ($OrderStatusToRejectUnpaidReferrals as $Status) {
                    add_action('woocommerce_order_status_' . $Status, array('FS_Affiliates_WC_Commission', 'reject_unpaid_referral_upon_refund'), 1);
                }
            }

            add_action('woocommerce_checkout_update_order_meta', array('FS_Affiliates_WC_Commission', 'save_affiliate_commission_for_order'), 10, 1);
        }

        /*
         * custom shop order column
         */

        public function custom_shop_order_column($columns) {
            $reordered_columns = array();

            foreach ($columns as $key => $column) {
                $reordered_columns[$key] = $column;
                
                if ($key == 'order_status') {
                    $reordered_columns['fs_affiliates'] = __('Affiliate Username', FS_AFFILIATES_LOCALE);
                }
            }

            return $reordered_columns;
        }

        /*
         * custom shop order column content
         */

        public function custom_orders_list_column_content($column, $post_id) {
            if ($column != 'fs_affiliates')
                return;

            $affiliate_id = get_post_meta($post_id, 'fs_affiliate_in_order', true);

            if (!$affiliate_id) {
                echo '-';
                return;
            }

            echo ($affiliate_name = get_the_title($affiliate_id)) ? $affiliate_name : __('Affiliate Deleted', FS_AFFILIATES_LOCALE);
        }

    }

}
    

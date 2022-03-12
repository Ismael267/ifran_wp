<?php

/**
 * Signup Commission
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!class_exists('FS_Affiliates_Signup_Commission')) {

    /**
     * Class FS_Affiliates_Signup_Commission
     */
    class FS_Affiliates_Signup_Commission extends FS_Affiliates_Modules {
        /*
         * Data
         */

        protected $data = array(
            'enabled' => 'no',
            'affiliate_signup' => 'no',
            'woocommerce_signup' => 'no',
            'aff_commission_value' => '',
            'wc_commission_value' => '',
            'referral_status' => 'pending',
            'wc_commission_after_first_purchase' => 'no'
        );

        /**
         * Class Constructor
         */
        public function __construct() {
            $this->id = 'signup_commission';
            $this->title = __('Signup Commission', FS_AFFILIATES_LOCALE);

            parent::__construct();
        }

        /*
         * Get settings link
         */

        public function settings_link() {
            return add_query_arg(array('page' => 'fs_affiliates', 'tab' => 'modules', 'section' => $this->id), admin_url('admin.php'));
        }

        /*
         * Get settings options array
         */

        public function settings_options_array() {

            $settings_array = array();
            $woocommerce = FS_Affiliates_Integration_Instances::get_integration_by_id('woocommerce');
            $settings_array[] = array(
                'type' => 'title',
                'title' => __('Signup Commission', FS_AFFILIATES_LOCALE),
                'id' => 'signup_commission_options',
            );
            $settings_array[] = array(
                'title' => __('Award Account Signup Commission for', FS_AFFILIATES_LOCALE),
                'id' => $this->plugin_slug . '_' . $this->id . '_affiliate_signup',
                'type' => 'checkbox',
                'default' => 'no',
                'desc' => __('Affiliate Signup', FS_AFFILIATES_LOCALE),
            );
            if ($woocommerce->is_enabled()) {
                $settings_array[] = array(
                    'title' => '',
                    'id' => $this->plugin_slug . '_' . $this->id . '_woocommerce_signup',
                    'type' => 'checkbox',
                    'default' => 'no',
                    'desc' => __('WooCommerce Signup', FS_AFFILIATES_LOCALE),
                );
            }
            $settings_array[] = array(
                'title' => __('Commission Amount for Affiliate Registration', FS_AFFILIATES_LOCALE),
                'desc' => __('The affiliate commission amount which should be awarded when a user registers as an affiliate.', FS_AFFILIATES_LOCALE),
                'id' => $this->plugin_slug . '_' . $this->id . '_aff_commission_value',
                'type' => 'price',
                'default' => '',
            );
            if ($woocommerce->is_enabled()) {
                $settings_array[] = array(
                    'title' => __('Commission Amount for WooCommerce Account Signup', FS_AFFILIATES_LOCALE),
                    'desc' => __('The affiliate commission amount which should be awarded when a user creates an account through WooCommerce Registration Form.', FS_AFFILIATES_LOCALE),
                    'id' => $this->plugin_slug . '_' . $this->id . '_wc_commission_value',
                    'type' => 'price',
                    'default' => '',
                    'class' => 'fs_affiliates_wc_signup_fields'
                );
                $settings_array[] = array(
                    'title' => esc_html__('Award Signup Commission after the First Purchase', FS_AFFILIATES_LOCALE),
                    'id' => $this->get_field_key('wc_commission_after_first_purchase'),
                    'type' => 'checkbox',
                    'default' => 'no',
                    'class' => 'fs_affiliates_wc_signup_fields'
                );
            }
            $settings_array[] = array(
                'title' => __('Referral Status', FS_AFFILIATES_LOCALE),
                'desc' => __('When set to <b>"Pending"</b>, the commission amount has to be approved before before Paying the Affiliate. If set to <b>"Unpaid"</b>, the commission amount can paid directly.', FS_AFFILIATES_LOCALE),
                'id' => $this->plugin_slug . '_' . $this->id . '_referral_status',
                'type' => 'select',
                'default' => 'pending',
                'options' => array(
                    'pending' => __('Pending', FS_AFFILIATES_LOCALE),
                    'unpaid' => __('Unpaid', FS_AFFILIATES_LOCALE),
                ),
            );
            $settings_array[] = array(
                'type' => 'sectionend',
                'id' => 'signup_commission_options',
            );

            return $settings_array;
        }

        /**
         * Frontend Actions
         */
        public function frontend_action() {
            if ($this->woocommerce_signup == 'yes') {
                add_action('woocommerce_created_customer', array($this, 'woocommerce_created_customer'), 10, 1);
            }
        }

        /**
         * Actions
         */
        public function actions() {
            add_action('fs_affiliates_after_referral_creation' , array($this , 'update_commission_after_purchase'));

            if ($this->affiliate_signup == 'yes') {
                add_action('fs_affiliates_status_changed', array($this, 'fs_affiliates_status_changed'), 10, 1);
            }
        }

        /*
         *  Award Commission for WooCommerce Signup
         */

        public function woocommerce_created_customer($customer_id) {
            $affiliate_id = fs_affiliates_get_id_from_cookie('fsaffiliateid');

            if (empty($affiliate_id))
                return;
            $status = $this->referral_status == 'pending' ? 'fs_pending' : 'fs_unpaid';
            $ReferralData = array();
            $ReferralData['type'] = 'opt-in';
            $ReferralData['amount'] = $this->wc_commission_value;
            $ReferralData['description'] = get_option('fs_affiliates_referral_desc_woocommerce_signup_label', 'Affiliate Signup');
            $ReferralData['reference'] = $customer_id;
            $ReferralData['date'] = time();
            $ReferralData['status'] = $status;
            $ReferralData['visit_id'] = fs_affiliates_get_id_from_cookie('fsvisitid');
            $ReferralData['campaign'] = fs_affiliates_get_id_from_cookie('fscampaign', '');

            if ('yes' == $this->wc_commission_after_first_purchase) {
                $ReferralData['affiliate_id'] = $affiliate_id;
                $ReferralData['status'] = $status;
                update_user_meta($customer_id, 'fs_affiliate_signup_commission_args', $ReferralData);
            } else {
                fs_affiliates_create_new_referral($ReferralData, array('post_status' => $status, 'post_author' => $affiliate_id));
            }
        }

        /*
         *  Award Commission for Affiliate Signup
         */

        public function fs_affiliates_status_changed($affiliate_id) {
            $affiliates_object = new FS_Affiliates_Data($affiliate_id);

            if ($affiliates_object->get_status() != 'fs_active' || $affiliates_object->commission_provided == 'yes')
                return;

            if (!$affiliates_object->parent)
                return;

            $parent = $affiliates_object->parent;
            $referral_status = $this->referral_status == 'pending' ? 'fs_pending' : 'fs_unpaid';

            $ReferralData = array();
            $ReferralData['type'] = 'opt-in';
            $ReferralData['amount'] = $this->aff_commission_value;
            $ReferralData['description'] = 'Affiliate Signup';
            $ReferralData['reference'] = $affiliate_id;
            $ReferralData['date'] = time();
            $ReferralData['status'] = $referral_status;
            $ReferralData['visit_id'] = $affiliates_object->signup_visit_id;
            $ReferralData['campaign'] = $affiliates_object->signup_campaign_id;

            fs_affiliates_create_new_referral($ReferralData, array('post_status' => $referral_status, 'post_author' => $parent));

            $affiliates_object->update_meta('commission_provided', 'yes');
        }

        /**
         *  Update sign-up commission after the purchase
         */
        public function update_commission_after_purchase($order_id) {
            if (empty($order_id)) {
                return;
            }

            $order_obj = wc_get_order($order_id);

            if (!is_object($order_obj)) {
                return;
            }

            $user_id = $order_obj->get_user_id();

            if (empty($user_id)) {
                return;
            }

            $referral_datas = get_user_meta($user_id, 'fs_affiliate_signup_commission_args', true);

            if (!($referral_datas) || !fs_affiliates_check_is_array($referral_datas)) {
                return;
            }

            delete_user_meta($user_id, 'fs_affiliate_signup_commission_args');

            fs_affiliates_create_new_referral($referral_datas, array('post_status' => $referral_datas['status'], 'post_author' => $referral_datas['affiliate_id']));
        }

    }

}

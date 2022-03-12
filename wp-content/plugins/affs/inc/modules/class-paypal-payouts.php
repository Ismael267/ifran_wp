<?php

/**
 * Paypal payouts
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!class_exists('FS_Affiliates_Paypal_Payouts_Module')) {

    /**
     * Class
     */
    class FS_Affiliates_Paypal_Payouts_Module extends FS_Affiliates_Modules {
        /*
         * Data
         */

        protected $data = array(
            'enabled'            => 'no',
            'mode'               => 'no',
            'sandbox_client_id'  => '',
            'sandbox_client_key' => '',
            'live_client_id'     => '',
            'live_client_key'    => '',
        );

        /**
         * Class Constructor
         */
        public function __construct() {
            $this->id    = 'paypal_payouts';
            $this->title = __('Paypal Payouts', FS_AFFILIATES_LOCALE);

            add_action($this->plugin_slug . '_admin_field_referral_paypal_generate_payouts', array($this, 'generate_payouts'));
            add_action($this->plugin_slug . '_admin_field_referral_pay', array($this, 'process_payouts'), 10, 3);

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
            return array(
                array(
                    'type'  => 'title',
                    'title' => __('PayPal Payouts', FS_AFFILIATES_LOCALE),
                    'id'    => 'paypal_payouts_options',
                ),
                array(
                    'title'   => __('Enable Sandbox Mode', FS_AFFILIATES_LOCALE),
                    'id'      => $this->plugin_slug . '_' . $this->id . '_mode',
                    'type'    => 'checkbox',
                    'default' => 'no',
                    'desc'    => '',
                ),
                array(
                    'title'   => __('Sandbox Application Client ID', FS_AFFILIATES_LOCALE),
                    'id'      => $this->plugin_slug . '_' . $this->id . '_sandbox_client_id',
                    'type'    => 'text',
                    'class'   => 'fs_affiliates_sandbox_mode',
                    'default' => '',
                ),
                array(
                    'title'   => __('Sandbox Application Client Secret Key', FS_AFFILIATES_LOCALE),
                    'id'      => $this->plugin_slug . '_' . $this->id . '_sandbox_client_key',
                    'type'    => 'text',
                    'class'   => 'fs_affiliates_sandbox_mode',
                    'default' => '',
                ),
                array(
                    'title'   => __('Live Application Client ID', FS_AFFILIATES_LOCALE),
                    'id'      => $this->plugin_slug . '_' . $this->id . '_live_client_id',
                    'type'    => 'text',
                    'class'   => 'fs_affiliates_live_mode',
                    'default' => '',
                ),
                array(
                    'title'   => __('Live Application Client Secret Key', FS_AFFILIATES_LOCALE),
                    'id'      => $this->plugin_slug . '_' . $this->id . '_' . '_live_client_key',
                    'type'    => 'text',
                    'class'   => 'fs_affiliates_live_mode',
                    'default' => '',
                ),
                array(
                    'type' => 'sectionend',
                    'id'   => 'paypal_payouts_options',
                ),
            );
        }

        /*
         * Admin Actions
         */

        public function admin_action() {
            add_filter($this->plugin_slug . '_list_of_action_for_referral', array($this, 'list_of_action_for_paypal_payouts'), 10, 1);
            add_action($this->plugin_slug . '_settings_paypal_payouts_options_after', array($this, 'display_payout_logs'));
            add_filter($this->plugin_slug . '_admin_field_referral_actions', array($this, 'render_pay_via_paypal_link'), 10, 3);
            add_action('admin_init', array($this, 'check_payout_status'), 10, 2);
        }

        /*
         * display payout logs
         */

        public function display_payout_logs() {
            if (!class_exists('FS_Affiliates_Payouts_Batch_Post_Table')) {
                require_once( FS_AFFILIATES_PLUGIN_PATH . '/inc/admin/menu/wp-list-table/class-fs-affiliates-payouts-batch.php' );
            }

            echo '<div class="' . $this->plugin_slug . '_table_wrap">';
            echo '<h2 class="wp-heading-inline">' . __('Payouts Log', FS_AFFILIATES_LOCALE) . '</h2>';
            if (isset($_REQUEST['s']) && strlen($_REQUEST['s'])) {
                /* translators: %s: search keywords */
                printf(' <span class="subtitle">' . __('Search results for &#8220;%s&#8221;') . '</span>', $_REQUEST['s']);
            }

            $post_table = new FS_Affiliates_Payouts_Batch_Post_Table();
            $post_table->prepare_items();
            $post_table->views();
            $post_table->search_box(__('Search Payouts Log', FS_AFFILIATES_LOCALE), $this->plugin_slug . '_search');
            $post_table->display();
            echo '</div>';
        }

        public function list_of_action_for_paypal_payouts($action) {
            $action['paypal'] = __('Pay via Paypal', FS_AFFILIATES_LOCALE);

            return $action;
        }

        public function render_pay_via_paypal_link($actions, $referral_id, $current_url) {
            if ('fs_unpaid' === get_post_status($referral_id)) {
                $affiliate_id = get_post($referral_id)->post_author;
                $payment_data = get_post_meta($affiliate_id, 'fs_affiliates_user_payment_datas', true);

                if (!empty($payment_data['fs_affiliates_payment_method']) && 'paypal' === $payment_data['fs_affiliates_payment_method']) {
                    $actions['paypal'] = fs_affiliates_get_action_display('paypal', $referral_id, $current_url);
                }
            }
            return $actions;
        }

        public function generate_payouts($args) {
            global $wpdb;
            $selected_affiliates = implode(', ', $args['selected_affiliate']);
            // affiliate Selection
            $affiliates = "SELECT DISTINCT ID FROM {$wpdb->posts} posts "
                    . "INNER JOIN {$wpdb->postmeta} postmeta ON posts.ID = postmeta.post_id "
                    . "WHERE posts.post_type=%s AND posts.post_status =%s "
                    . "AND postmeta.meta_key = %s AND postmeta.meta_value != ''";

            if ($args['affiliate_select_type'] == 'include') {
                $affiliates .= " AND posts.ID IN($selected_affiliates)";
            }
            if ($args['affiliate_select_type'] == 'exclude') {
                $affiliates .= " AND posts.ID NOT IN($selected_affiliates)";
            }

            $affiliates = $wpdb->prepare($affiliates, 'fs-affiliates', 'fs_active', 'payment_email');
            $affiliates = array_filter($wpdb->get_col($affiliates));

            if (!fs_affiliates_check_is_array($affiliates))
                return;

            $affiliates = implode(', ', $affiliates);
            // referral Selection
            $referrals  = "SELECT DISTINCT ID FROM {$wpdb->posts} posts "
                    . "INNER JOIN {$wpdb->postmeta} postmeta ON posts.ID = postmeta.post_id "
                    . "WHERE posts.post_type=%s AND posts.post_status=%s AND posts.post_author IN($affiliates)";

            if (!empty($args['from_date'])) {
                $referrals .= " AND posts.post_date >='{$args['from_date']}'";
            }
            if (!empty($args['to_date'])) {
                $referrals .= " AND posts.post_date <='{$args['to_date']}'";
            }

            if ($this->is_enabled()) {
                $referrals = $wpdb->prepare($referrals, 'fs-referrals', 'fs_unpaid');
            } else {
                $referrals = $wpdb->prepare($referrals, 'fs-referrals', $args['current_referral_status']);
            }
            $referrals = array_filter($wpdb->get_col($referrals));

            do_action($this->plugin_slug . '_admin_field_referral_pay', $referrals, 'paypal', $args);
        }

        public function process_payouts($referrals, $payout_method, $args = array()) {

            if ('paypal' !== $payout_method) {
                return;
            }

            try {
                if (empty($referrals) || !is_array($referrals)) {
                    throw new Exception(__("Couldn't find valid referrals", FS_AFFILIATES_LOCALE));
                }

                $payout_receivers        = array();
                $payout_receivers_amount = array();
                $payout_data             = array();
                $paypal_referrals        = array();

                foreach ($referrals as $referral_id) {
                    $affiliate_id   = get_post($referral_id)->post_author;
                    $receiver_email = get_post_meta($affiliate_id, 'payment_email', true);
                    $payment_data   = get_post_meta($affiliate_id, 'fs_affiliates_user_payment_datas', true);

                    if (!empty($receiver_email) && !empty($payment_data['fs_affiliates_payment_method']) && 'paypal' === $payment_data['fs_affiliates_payment_method']) {
                        if (!isset($payout_receivers_amount[$affiliate_id])) {
                            $payout_receivers_amount[$affiliate_id] = 0;
                        }
                        $Referral = new FS_Affiliates_Referrals($referral_id);

                        $paypal_referrals[$affiliate_id][]      = $referral_id;
                        $payout_receivers_amount[$affiliate_id] += floatval($Referral->amount);
                        $payout_receivers[$affiliate_id]        = $payout_receivers_amount[$affiliate_id];
                        $payout_data[$affiliate_id]             = array(
                            'payment_mode'   => 'PayPal',
                            'generated_by'   => get_current_user_id(),
                            'commission'     => $payout_receivers[$affiliate_id],
                            'referral_count' => count($paypal_referrals[$affiliate_id]),
                            'referral_ids'   => $paypal_referrals,
                        );
                    }
                }

                if (!empty($args['min_threshold']) && is_numeric($args['min_threshold'])) {
                    foreach ($payout_receivers as $aff_id => $receiver_amount) {
                        if ($receiver_amount < floatval($args['min_threshold'])) {
                            unset($payout_receivers[$aff_id], $payout_data[$aff_id], $paypal_referrals[$aff_id]);
                        }
                    }
                }

                if (empty($payout_receivers)) {
                    throw new Exception(__("Couldn't find valid receivers for payout.", FS_AFFILIATES_LOCALE));
                }

                $payout_batch_id = null;
                if ($this->is_enabled()) {
                    include_once FS_AFFILIATES_PLUGIN_PATH . '/inc/lib/PayPal/autoload.php';

                    $payout_batch_id = wp_insert_post(array(
                        'post_type'   => 'fs-payouts-batch',
                        'post_status' => 'fs_pending',
                        'post_author' => 1,
                        'post_title'  => __('Payouts Log', FS_AFFILIATES_LOCALE),
                            ), true);

                    if (is_wp_error($payout_batch_id)) {
                        throw new Exception($payout_batch_id->get_error_message());
                    }

                    $Payout = new FS_Affiliates_PayPal_Payouts(array(
                        'sender_batch_id' => $payout_batch_id,
                        'receivers'       => $payout_receivers,
                        'currency'        => get_option('fs_affiliates_currency', 'USD'),
                    ));

                    $BatchPayout = $Payout->createBatchPayout();

                    if (!isset($BatchPayout->batch_header->payout_batch_id)) {
                        throw new Exception($BatchPayout);
                    }

                    $processed_payout = $Payout->processPayout($BatchPayout->batch_header->payout_batch_id);

                    if (!isset($processed_payout->batch_header->payout_batch_id)) {
                        throw new Exception($processed_payout);
                    }
                }

                $referral_status = isset($args['referral_status_to_update']) ? $args['referral_status_to_update'] : 'fs_paid';

                if ($this->is_enabled() || 'fs_paid' === $referral_status) {
                    fs_insert_payout_data($payout_data);
                }

                foreach ($paypal_referrals as $aff_id => $referrals) {
                    if (!empty($referrals)) {
                        foreach ($referrals as $referral_id) {
                            $Referral = new FS_Affiliates_Referrals($referral_id);

                            if ($this->is_enabled()) {
                                $Referral->update_status('fs_paid');
                            } else {
                                $Referral->update_status($referral_status);
                            }
                        }
                    }
                }

                foreach ($payout_data as $affiliate_id => $data) {
                    do_action('fs_affiliates_paypal_payout_status_for_affiliate', $affiliate_id, $payout_batch_id);
                }

                FS_Affiliates_Settings::add_message(__('PayPal Payouts processed successfully', FS_AFFILIATES_LOCALE));
            } catch (Exception $ex) {
                FS_Affiliates_Settings::add_error($ex->getMessage());
            }
        }

        public function check_payout_status() {

            if (empty($_GET['action']) || empty($_GET['sender_batch_id']) || 'check_payout_status' !== $_GET['action']) {
                return;
            }

            $batch_header = get_post_meta($_GET['sender_batch_id'], '_payout_batch_header', true);

            if (!empty($batch_header['payout_batch_id'])) {
                include_once FS_AFFILIATES_PLUGIN_PATH . '/inc/lib/PayPal/autoload.php';
                $Payout = new FS_Affiliates_PayPal_Payouts(array(
                    'sender_batch_id' => $_GET['sender_batch_id'],
                ));

                $payoutBatchStatus = $Payout->getPayoutBatchStatus($batch_header['payout_batch_id']);

                if (isset($payoutBatchStatus->batch_header->payout_batch_id)) {
                    $Payout->processPayout($payoutBatchStatus->batch_header->payout_batch_id);
                    FS_Affiliates_Settings::add_message(__('Payout status updated via PayPal.', FS_AFFILIATES_LOCALE));
                } else {
                    FS_Affiliates_Settings::add_error($payoutBatchStatus);
                }
            } else {
                FS_Affiliates_Settings::add_error(__('Something went wrong while retrieving the Payout status from PayPal.', FS_AFFILIATES_LOCALE));
            }
            wp_safe_redirect(esc_url_raw(add_query_arg(array('page' => 'fs_affiliates', 'tab' => 'modules', 'section' => 'paypal_payouts'), admin_url('admin.php'))));
            exit();
        }

    }

}
    
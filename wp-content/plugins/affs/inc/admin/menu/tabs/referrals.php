<?php

/**
 * Referrals Tab
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (class_exists('FS_Affiliates_Referrals_Tab')) {
    return new FS_Affiliates_Referrals_Tab();
}

/**
 * FS_Affiliates_Referrals_Tab.
 */
class FS_Affiliates_Referrals_Tab extends FS_Affiliates_Settings_Page {

    /**
     * Constructor.
     */
    public function __construct() {
        $this->id = 'referrals';
        $this->label = __('Referrals', FS_AFFILIATES_LOCALE);

        add_action($this->plugin_slug . '_admin_field_output_referrals', array($this, 'output_referrals'));
        add_action($this->plugin_slug . '_admin_field_referral_direct_generate_payouts', array($this, 'direct_bacs_generate_payouts'));
        add_action($this->plugin_slug . '_admin_field_referral_pay', array($this, 'process_direct_bacs_payouts'), 10, 3);
        add_action($this->plugin_slug . '_new_referral', array($this, 'paid_referrals_to_affiliates'), 8, 2);

        include_once( FS_AFFILIATES_PLUGIN_PATH . '/inc/admin/menu/exporter/class-fs-affiliates-payouts-data-exporter.php' );
        parent::__construct();
    }

    /**
     * Paid referrals to affiliates
     */
    public function paid_referrals_to_affiliates($referral_id, $affiliate_id) {
        $referral = new FS_Affiliates_Referrals($referral_id);
        $affiliate = new FS_Affiliates_Data($affiliate_id);

        if ('fs_paid' == $referral->get_status()) {
            $payment_data = get_post_meta($affiliate_id, 'fs_affiliates_user_payment_datas', true);
            $payment_method = isset($payment_data['fs_affiliates_payment_method']) ? $payment_data['fs_affiliates_payment_method'] : false;

            do_action($this->plugin_slug . '_admin_field_referral_pay', array($referral_id), $payment_method);
        }
    }

    /**
     * Get settings array.
     */
    public function get_settings($current_section = '') {

        return array(
            array('type' => 'output_referrals')
                );
    }

    /**
     * Output the settings buttons.
     */
    public function output_buttons() {
        
    }

    /**
     * Output the affiliates referrals
     */
    public function output_referrals() {
        global $current_section;

        switch ($current_section) {
            case 'new' :
                $this->display_new_page();
                break;
            case 'edit' :
                $this->display_edit_page();
                break;
            case 'generate_payout' :
                $this->display_generate_payout_page();
                break;
            default:
                $this->display_table();
                break;
        }
    }

    /**
     * Output the affiliates referrals table
     */
    public function display_table() {
        if (!class_exists('FS_Affiliates_Referrals_Post_Table')) {
            require_once( FS_AFFILIATES_PLUGIN_PATH . '/inc/admin/menu/wp-list-table/class-fs-affiliates-referrals-table.php' );
        }

        $new_section_url = add_query_arg(array('page' => 'fs_affiliates', 'tab' => 'referrals', 'section' => 'new'), FS_AFFILIATES_ADMIN_URL);
        $pay_url = add_query_arg(array('page' => 'fs_affiliates', 'tab' => 'referrals', 'section' => 'generate_payout'), FS_AFFILIATES_ADMIN_URL);
        echo '<div class="' . $this->plugin_slug . '_table_wrap">';
        echo '<h2 class="wp-heading-inline">' . __('Referrals', FS_AFFILIATES_LOCALE) . '</h2>';
        echo '<a class="page-title-action ' . $this->plugin_slug . '_add_btn" href="' . $new_section_url . '">' . __('Add New Referral', FS_AFFILIATES_LOCALE) . '</a>';
        echo '<a class="page-title-action ' . $this->plugin_slug . '_ref_payout_btn" href="' . $pay_url . '">' . __('Generate Payout', FS_AFFILIATES_LOCALE) . '</a>';

        if (isset($_REQUEST['s']) && strlen($_REQUEST['s'])) {
            /* translators: %s: search keywords */
            printf(' <span class="subtitle">' . __('Search results for &#8220;%s&#8221;') . '</span>', $_REQUEST['s']);
        }

        $post_table = new FS_Affiliates_Referrals_Post_Table();
        $post_table->prepare_items();
        $post_table->views();
        $post_table->search_box(__('Search Referrals', FS_AFFILIATES_LOCALE), $this->plugin_slug . '_search');
        $post_table->display();
        echo '</div>';
    }

    /**
     * Output the new affiliate page
     */
    public function display_new_page() {

        include_once( FS_AFFILIATES_PLUGIN_PATH . '/inc/admin/menu/views/referrals-new.php' );
    }

    public function display_generate_payout_page() {

        include_once( FS_AFFILIATES_PLUGIN_PATH . '/inc/admin/menu/views/generate-payout.php' );
    }

    /**
     * Output the edit referral page
     */
    public function display_edit_page() {
        if (!isset($_GET['id']))
            return;

        $referral_id = $_GET['id'];
        $referral_object = new FS_Affiliates_Referrals($referral_id);

        include_once( FS_AFFILIATES_PLUGIN_PATH . '/inc/admin/menu/views/referrals-edit.php' );
    }

    /**
     * Save settings.
     */
    public function save() {
        global $current_section;

        if (!empty($_POST['register_new_referrals'])) {
            $this->create_new_referrals();
        } elseif (!empty($_POST['edit_referrals'])) {
            $this->update_referrals();
        } elseif (!empty($_POST['generate_payouts'])) {
            $this->generate_payouts();
        } elseif(isset($_POST['fs_referral_item_per_page_input'])) {
            $item_per_page = !empty( $_REQUEST[ 'fs_referral_item_per_page_input' ] ) ? wp_unslash($_REQUEST[ 'fs_referral_item_per_page_input' ]) : 20 ;
            update_option('fs_referral_item_per_page_input' , $item_per_page);
        }
    }

    /*
     * Create a new referral
     */

    public function create_new_referrals() {
        check_admin_referer($this->plugin_slug . '_register_new_referrals', '_' . $this->plugin_slug . '_nonce');

        try {
            $meta_data = $_POST['referral'];

            if (!isset($meta_data['affiliate_id']) || !$affiliate_id = (reset($meta_data['affiliate_id']))) {
                throw new Exception(__('Please select an Affiliate', FS_AFFILIATES_LOCALE));
            }

            if ($meta_data['type'] == '') {
                throw new Exception(__('Referral Type cannot be empty', FS_AFFILIATES_LOCALE));
            }

            if ($meta_data['amount'] == '') {
                throw new Exception(__('Referral Amount cannot be empty', FS_AFFILIATES_LOCALE));
            }

            if ($meta_data['reference'] == '') {
                throw new Exception(__('Reference cannot be empty', FS_AFFILIATES_LOCALE));
            }

            if ($meta_data['description'] == '') {
                throw new Exception(__('Description cannot be empty', FS_AFFILIATES_LOCALE));
            }

            $meta_data['date'] = time();
            $meta_data['amount'] = fs_affiliates_format_decimal($meta_data['amount'], true);
            $post_args = array('post_status' => $meta_data['status'], 'post_author' => $affiliate_id);

            $post_args['skip_mlm_check'] = true;

            fs_affiliates_create_new_referral($meta_data, $post_args);

            FS_Affiliates_Settings::add_message(__('New Referral has been created', FS_AFFILIATES_LOCALE));

            unset($_POST['referral']);
        } catch (Exception $ex) {
            FS_Affiliates_Settings::add_error($ex->getMessage());
        }
    }

    /*
     * Create a new affiliates
     */

    public function update_referrals() {
        check_admin_referer($this->plugin_slug . '_edit_referrals', '_' . $this->plugin_slug . '_nonce');

        try {
            $meta_data = $_POST['referral'];

            if (empty($meta_data['id']) || $meta_data['id'] != $_REQUEST['id']) {
                throw new Exception(__('Cannot modify Affiliate Id', FS_AFFILIATES_LOCALE));
            }

            if (!isset($meta_data['affiliate_id']) || !$affiliate_id = (reset($meta_data['affiliate_id']))) {
                throw new Exception(__('Please select an Affiliate', FS_AFFILIATES_LOCALE));
            }

            if ($meta_data['type'] == '') {
                throw new Exception(__('Referral Type cannot be empty', FS_AFFILIATES_LOCALE));
            }

            if ($meta_data['amount'] == '') {
                throw new Exception(__('Referral Amount cannot be empty', FS_AFFILIATES_LOCALE));
            }

            if ($meta_data['reference'] == '') {
                throw new Exception(__('Reference cannot be empty', FS_AFFILIATES_LOCALE));
            }

            if ($meta_data['description'] == '') {
                throw new Exception(__('Description cannot be empty', FS_AFFILIATES_LOCALE));
            }

            if (get_post_status($meta_data['id']) == 'fs_paid')
                return;

            $meta_data['amount'] = fs_affiliates_format_decimal($meta_data['amount'], true);

            $post_args = array('post_status' => $meta_data['status'], 'post_author' => $affiliate_id);

            fs_affiliates_update_referral($meta_data['id'], $meta_data, $post_args);

            do_action('fs_affiliates_status_changed_new_to_' . get_post_status($meta_data['id']), $meta_data['id']);

            unset($_POST['affiliate']);

            FS_Affiliates_Settings::add_message(__('Referral has been updated successfully', FS_AFFILIATES_LOCALE));
        } catch (Exception $ex) {
            FS_Affiliates_Settings::add_error($ex->getMessage());
        }
    }

    public function generate_payouts() {
        check_admin_referer($this->plugin_slug . '_process_payouts', '_' . $this->plugin_slug . '_nonce');

        $payout_method = !empty($_POST['referral']['payout_method']) ? $_POST['referral']['payout_method'] : '';
        $affiliate_select_type = !empty($_POST['referral']['affiliate_select_type']) ? $_POST['referral']['affiliate_select_type'] : 'all';
        $selected_affiliate = !empty($_POST['referral']['selected_affiliate']) ? $_POST['referral']['selected_affiliate'] : array();
        $from_date = !empty($_POST['referral']['from_date']) ? date('Y-m-d 00:00:00', strtotime($_POST['referral']['from_date'])) : '';
        $to_date = !empty($_POST['referral']['to_date']) ? date('Y-m-d 23:59:59', strtotime($_POST['referral']['to_date'])) : '';
        $min_threshold = isset($_POST['referral']['min_threshold']) && is_numeric($_POST['referral']['min_threshold']) ? floatval($_POST['referral']['min_threshold']) : '';
        $current_referral_status = !empty($_POST['referral']['referral_status']) ? $_POST['referral']['referral_status'] : 'fs_unpaid';

        try {
            if (empty($payout_method)) {
                throw new Exception(esc_html__('Select Payment Method', FS_AFFILIATES_LOCALE));
            }

            if (($affiliate_select_type == 'include' || $affiliate_select_type == 'exclude') && (!fs_affiliates_check_is_array($selected_affiliate))) {
                throw new Exception(esc_html__('Select Affiliate(s)', FS_AFFILIATES_LOCALE));
            }

            if (!empty($_POST['referral']['referral_status'])) {
                if ($_POST['referral']['referral_status'] == 'fs_unpaid' && !isset($_POST['referral']['paid_status'])) {
                    $referral_status_to_update = 'fs_in_progress';
                } else {
                    $referral_status_to_update = 'fs_paid';
                }
            }

            do_action("{$this->plugin_slug}_admin_field_referral_{$payout_method}_generate_payouts", array(
                'affiliate_select_type' => $affiliate_select_type,
                'selected_affiliate' => $selected_affiliate,
                'from_date' => $from_date,
                'to_date' => $to_date,
                'min_threshold' => $min_threshold,
                'current_referral_status' => $current_referral_status,
                'referral_status_to_update' => $referral_status_to_update,
            ));
        } catch (Exception $ex) {
            FS_Affiliates_Settings::add_error($ex->getMessage());
        }
    }

    public function direct_bacs_generate_payouts($args) {
        global $wpdb;
        $selected_affiliates = implode(', ', $args['selected_affiliate']);
        // affiliate selection
        $affiliates = "SELECT DISTINCT ID FROM {$wpdb->posts} posts "
                . "INNER JOIN {$wpdb->postmeta} postmeta ON posts.ID = postmeta.post_id "
                . "WHERE posts.post_type=%s AND posts.post_status =%s";

        if ($args['affiliate_select_type'] == 'include') {
            $affiliates .= " AND posts.ID IN($selected_affiliates)";
        }
        if ($args['affiliate_select_type'] == 'exclude') {
            $affiliates .= " AND posts.ID NOT IN($selected_affiliates)";
        }

        $affiliates = $wpdb->prepare($affiliates, 'fs-affiliates', 'fs_active');
        $affiliates = array_filter($wpdb->get_col($affiliates));

        if (!fs_affiliates_check_is_array($affiliates))
            return;

        $affiliates = implode(', ', $affiliates);
        // referrals selection
        $referrals = "SELECT DISTINCT ID FROM {$wpdb->posts} posts "
                . "INNER JOIN {$wpdb->postmeta} postmeta ON posts.ID = postmeta.post_id "
                . "WHERE posts.post_type=%s AND posts.post_status=%s AND posts.post_author IN($affiliates)";

        if (!empty($args['from_date'])) {
            $referrals .= " AND posts.post_date >='{$args['from_date']}'";
        }
        if (!empty($args['to_date'])) {
            $referrals .= " AND posts.post_date <='{$args['to_date']}'";
        }

        $referrals = $wpdb->prepare($referrals, 'fs-referrals', $args['current_referral_status']);
        $referrals = array_filter($wpdb->get_col($referrals));

        do_action($this->plugin_slug . '_admin_field_referral_pay', $referrals, 'direct', $args);
    }

    public function process_direct_bacs_payouts($referrals, $payout_method, $args = array()) {

        if ('direct' !== $payout_method)
            return;

        try {
            if (empty($referrals) || !is_array($referrals))
                throw new Exception(__("Couldn't find valid referrals", FS_AFFILIATES_LOCALE));

            $payout_receivers = array();
            $payout_data = array();
            $bacs_referrals = array();
            $current_user_id = get_current_user_id();

            foreach ($referrals as $referral_id) {
                $Referral = new FS_Affiliates_Referrals($referral_id);

                if (!in_array($Referral->get_status(), array('fs_unpaid', 'fs_in_progress', 'fs_paid')))
                    continue;

                $affiliate_id = $Referral->affiliate;
                $payment_data = get_post_meta($affiliate_id, 'fs_affiliates_user_payment_datas', true);

                $method = isset($payment_data['fs_affiliates_payment_method']) ? $payment_data['fs_affiliates_payment_method'] : false;

                if ($method && $method != 'direct')
                    continue;

                if (!isset($payout_receivers[$affiliate_id])) {
                    $payout_receivers[$affiliate_id] = 0;
                }

                $bacs_referrals[$affiliate_id][] = $referral_id;
                $payout_receivers[$affiliate_id] += floatval($Referral->amount);
                $payout_data[$affiliate_id] = array(
                    'payment_mode' => 'BACS',
                    'generated_by' => $current_user_id,
                    'commission' => $payout_receivers[$affiliate_id],
                    'referral_count' => count($bacs_referrals[$affiliate_id]),
                    'referral_ids' => $bacs_referrals,
                        );
            }

            if (!empty($args['min_threshold']) && is_numeric($args['min_threshold'])) {
                foreach ($payout_receivers as $aff_id => $receiver_amount) {
                    if ($receiver_amount < floatval($args['min_threshold'])) {
                        unset($payout_receivers[$aff_id], $payout_data[$aff_id], $bacs_referrals[$aff_id]);
                    }
                }
            }

            if (empty($payout_receivers)) {
                throw new Exception(__("Couldn't find valid receivers for payout", FS_AFFILIATES_LOCALE));
            }

            $referral_status = isset($args['referral_status_to_update']) ? $args['referral_status_to_update'] : 'fs_paid';

            if ('fs_paid' === $referral_status)
                fs_insert_payout_data($payout_data);

            foreach ($bacs_referrals as $affiliate_id => $referrals) {
                if (!empty($referrals)) {
                    foreach ($referrals as $referral_id) {
                        $Referral = new FS_Affiliates_Referrals($referral_id);
                        $Referral->update_status($referral_status);
                    }
                }

                do_action('fs_affiliates_direct_payout_status_for_affiliate', $affiliate_id);
            }


            FS_Affiliates_Settings::add_message(__('BACS Payouts processed successfully', FS_AFFILIATES_LOCALE));
        } catch (Exception $ex) {
            FS_Affiliates_Settings::add_error($ex->getMessage());
        }
    }

}

return new FS_Affiliates_Referrals_Tab();

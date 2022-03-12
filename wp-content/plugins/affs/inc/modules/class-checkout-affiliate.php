<?php
/**
 * Checkout Affiliate
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!class_exists('FS_Affiliates_Checkout')) {

    /**
     * Class FS_Affiliates_Checkout
     */
    class FS_Affiliates_Checkout extends FS_Affiliates_Modules {
        /*
         * Data
         */

        protected $data = array(
            'enabled' => 'no',
            'force_users' => '',
            'affs_selection' => '',
            'selection_title' => '',
            'selection_value1' => '',
            'selection_value2' => '',
            'label' => '',
            'display_style' => '1',
            'allowed_affiliates_method' => '1',
            'selected_affiliates' => array()
                );

        /**
         * Class Constructor
         */
        public function __construct() {
            $this->id = 'checkout_affiliate';
            $this->title = __('Checkout Affiliate', FS_AFFILIATES_LOCALE);

            parent::__construct();
        }

        /*
         * Plugin enabled
         */

        public function is_plugin_enabled() {
            $woocommerce = FS_Affiliates_Integration_Instances::get_integration_by_id('woocommerce');

            if ($woocommerce->is_enabled())
                return true;

            return false;
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

            $selected = ($this->force_users == 'yes') ? 2 : 1;

            return array(
                array(
                    'type' => 'title',
                    'title' => __('Checkout Affiliate Settings', FS_AFFILIATES_LOCALE),
                    'id' => 'checkout_affiliate_options',
                ),
                array(
                    'title' => __('Affiliates to be Displayed in Listbox', FS_AFFILIATES_LOCALE),
                    'desc' => __('This option controls the list of affiliates which should be displayed in the listbox', FS_AFFILIATES_LOCALE),
                    'id' => $this->plugin_slug . '_' . $this->id . '_allowed_affiliates_method',
                    'type' => 'select',
                    'class' => 'fs_affiliates_allowed_affiliates_method',
                    'default' => '1',
                    'options' => array(
                        '1' => __('All Affiliates', FS_AFFILIATES_LOCALE),
                        '2' => __('Selected Affiliates', FS_AFFILIATES_LOCALE),
                    ),
                ),
                array(
                    'title' => __('Selected Affiliates', FS_AFFILIATES_LOCALE),
                    'id' => $this->plugin_slug . '_' . $this->id . '_selected_affiliates',
                    'type' => 'ajaxmultiselect',
                    'class' => 'fs_affiliates_selected_affiliate',
                    'list_type' => 'affiliates',
                    'action' => 'fs_affiliates_search',
                    'default' => array(),
                ),
                array(
                    'title' => __('Affiliate Name Display Style', FS_AFFILIATES_LOCALE),
                    'desc' => __('This option controls the Affiliate Name display style on the checkout page', FS_AFFILIATES_LOCALE),
                    'id' => $this->plugin_slug . '_' . $this->id . '_display_style',
                    'type' => 'select',
                    'default' => '1',
                    'options' => array(
                        '1' => __('User Name', FS_AFFILIATES_LOCALE),
                        '2' => __('Display Name', FS_AFFILIATES_LOCALE),
                        '3' => __('Display Nickname', FS_AFFILIATES_LOCALE),
                    ),
                ),
                array(
                    'title' => __('Affiliate Selection', FS_AFFILIATES_LOCALE),
                    'desc' => __('This option controls how the affiliate selection option can be used to display at checkout', FS_AFFILIATES_LOCALE),
                    'id' => $this->plugin_slug . '_' . $this->id . '_affs_selection',
                    'type' => 'select',
                    'default' => $selected,
                    'options' => array(
                        '1' => __('Optional', FS_AFFILIATES_LOCALE),
                        '2' => __('Mandatory', FS_AFFILIATES_LOCALE),
                        '3' => __('Based on User Selection', FS_AFFILIATES_LOCALE),
                    ),
                ),
                array(
                    'title' => __('Affiliate Selection Title', FS_AFFILIATES_LOCALE),
                    'desc' => __('This label will be used for displaying the affiliate selection field on the checkout page', FS_AFFILIATES_LOCALE),
                    'id' => $this->plugin_slug . '_' . $this->id . '_selection_title',
                    'class' => 'fs_affiliates_user_selection_fields',
                    'type' => 'text',
                    'default' => 'Affiliate Selection',
                ),
                array(
                    'title' => __('Affiliate Selection Value1', FS_AFFILIATES_LOCALE),
                    'desc' => __('This label will be used for displaying the affiliate selection on the checkout page', FS_AFFILIATES_LOCALE),
                    'id' => $this->plugin_slug . '_' . $this->id . '_selection_value1',
                    'class' => 'fs_affiliates_user_selection_fields',
                    'type' => 'text',
                    'default' => 'I want to select an Affiliate',
                ),
                array(
                    'title' => __('Affiliate Selection Value2', FS_AFFILIATES_LOCALE),
                    'desc' => __('This label will be used for displaying the affiliate selection on the checkout page', FS_AFFILIATES_LOCALE),
                    'id' => $this->plugin_slug . '_' . $this->id . '_selection_value2',
                    'class' => 'fs_affiliates_user_selection_fields',
                    'type' => 'text',
                    'default' => "I don't want to select the Affiliate",
                ),
                array(
                    'title' => __('Affiliate Selection Label', FS_AFFILIATES_LOCALE),
                    'desc' => __('This label will be used for displaying the affiliate selection field on the checkout page', FS_AFFILIATES_LOCALE),
                    'id' => $this->plugin_slug . '_' . $this->id . '_label',
                    'type' => 'text',
                    'default' => 'Select Affiliate',
                ),
                array(
                    'type' => 'sectionend',
                    'id' => 'checkout_affiliate_options',
                ),
                    );
        }

        /**
         * Frontend Actions
         */
        public function frontend_action() {
            add_action('woocommerce_checkout_after_customer_details', array($this, 'woocommerce_checkout_after_customer_details'));

            add_action('woocommerce_checkout_process', array($this, 'woocommerce_checkout_process_validation'));

            add_action('woocommerce_checkout_update_order_meta', array($this, 'woocommerce_checkout_update_order_meta'), 10, 2);
        }

        public function woocommerce_checkout_after_customer_details() {
            $cookie_affiliate_id = fs_affiliates_get_id_from_cookie('fsaffiliateid');

            if (!empty($cookie_affiliate_id) || !apply_filters('fs_affiliates_display_checkout_affiliate', true))
                return;

            $radio_default = 1;

            wp_localize_script(
                    'fs-affiliates-checkout', 'fs_affiliates_checkout_params', array(
                'affs_selection' => $this->affs_selection,
                'radio_default' => $radio_default,
                    )
            );

            wp_enqueue_script('fs-affiliates-checkout', FS_AFFILIATES_PLUGIN_URL . '/assets/js/frontend/checkout.js', array('jquery'), FS_AFFILIATES_VERSION);

            $affiliates = fs_affiliates_get_active_affiliates();

            if ($this->allowed_affiliates_method == '2') {
                $affiliates = array_filter(array_unique(array_intersect($this->selected_affiliates, $affiliates)));
            }

            if (!fs_affiliates_check_is_array($affiliates))
                return;

            $affiliate_options = array('' => __('None', FS_AFFILIATES_LOCALE));
            $current_affiliate = fs_affiliates_is_user_having_affiliate();

            foreach ($affiliates as $affiliate_id) {
                if ($current_affiliate == $affiliate_id && !apply_filters('fs_affiliates_is_restricted_own_commission', false))
                    continue;

                $affiliate = get_post($affiliate_id);
                $user_id = $affiliate->post_author;
                $user = get_user_by('id', $user_id);

                if ($this->display_style == '3') {
                    $affiliate_options[$affiliate_id] = $user->nickname;
                } else if ($this->display_style == '2') {
                    $affiliate_options[$affiliate_id] = $user->display_name;
                } else {
                    $affiliate_options[$affiliate_id] = $affiliate->post_title;
                }
            }

            if ($this->affs_selection == 3) {
                ?>
                <span class="woocommerce-input-wrapper">
                    <label><?php echo $this->selection_title; ?></label>
                    <br><input type="radio" class="input-radio " value="1" name="affiliate_referrer_radio" <?php if ($radio_default == 1) { ?> checked="checked" <?php } ?> id="affiliate_referrer_radio_1">
                    <label for="affiliate_referrer_radio_1" class="radio "> <?php echo $this->selection_value1; ?> </label>
                    <br><input type="radio" class="input-radio " value="2" name="affiliate_referrer_radio" id="affiliate_referrer_radio_2" <?php if ($radio_default == 2) { ?> checked="checked" <?php } ?> >
                    <label for="affiliate_referrer_radio_2" class="radio "> <?php echo $this->selection_value2; ?> </label>
                </span>
            <?php } ?>

            <p class="form-row affiliate_referrer_fields" id="affiliate_referrer_fields">
                <label><?php echo $this->label;
                
                    if ($this->affs_selection != 1) {
                        ?>
                        <abbr class="required" title="required">*</abbr>
                    <?php } ?>
                        
                </label> 

                <span class="woocommerce-input-wrapper">
                    <select name="affiliate_referrer" id="affiliate_referrer" class="input-text affiliate_referrer">
                        <?php
                        foreach ($affiliate_options as $affs_id => $each_options) {
                            ?>
                            <option value="<?php echo $affs_id; ?>"><?php echo $each_options; ?></option>
                        <?php } ?>
                    </select>
                </span>
            </p>
            <?php
        }

        public function woocommerce_checkout_process_validation() {
            $notice_content = sprintf(esc_html__('%1$s Select Affiliate %2$s is a required field', FS_AFFILIATES_LOCALE), '<b>', '</b>');

            if (isset($_POST['affiliate_referrer']) && ( '' == $_POST['affiliate_referrer'] && ( $this->affs_selection == 2 ) )) {
                wc_add_notice(wp_kses_post($notice_content), 'error');
            }

            if (isset($_POST['affiliate_referrer_radio']) && isset($_POST['affiliate_referrer'])) {

                if (( 1 == $_POST['affiliate_referrer_radio'] ) && ( '' == $_POST['affiliate_referrer'] )) {
                    wc_add_notice(wp_kses_post($notice_content), 'error');
                }
            }
        }

        public function woocommerce_checkout_update_order_meta($order_id, $data) {

            if (isset($_POST['affiliate_referrer']) && !empty($_POST['affiliate_referrer'])) {

                update_post_meta($order_id, 'fs_affiliate_in_order', $_POST['affiliate_referrer']);

                $commission = FS_Affiliates_WC_Commission::award_commission_for_product_purchase($order_id, $_POST['affiliate_referrer']);
                update_post_meta($order_id, 'fs_commission_to_be_awarded_in_order', $commission);
            }
        }

    }

}
    
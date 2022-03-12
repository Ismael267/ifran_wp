<?php
/* New Landing commission Page */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

$commission_value = isset($_POST['landing_commission']['commission_value']) ? $_POST['landing_commission']['commission_value'] : 0;
$coupon_id = isset($_POST['landing_commission']['referral_status']) ? $_POST['landing_commission']['referral_status'] : '';
$status = isset($_POST['landing_commission']['status']) ? $_POST['landing_commission']['status'] : '';
$cookie_validity_number = isset($_POST['landing_commission']['cookie_validity']['number']) ? $_POST['landing_commission']['commission_value']['number'] : 1;
$cookie_validity_unit = isset($_POST['landing_commission']['cookie_validity']['unit']) ? $_POST['landing_commission']['commission_value']['unit'] : 'days';
$usage_type = isset($_POST['landing_commission']['usage_type']) ? $_POST['landing_commission']['usage_type'] : '1';
$validity_count = isset($_POST['landing_commission']['validity_count']) ? $_POST['landing_commission']['validity_count'] : '';

?>
<div class="<?php echo $this->plugin_slug; ?>_affiliates_new">
    <h2><?php _e('New Landing Commission', FS_AFFILIATES_LOCALE); ?></h2>
    <table class="form-table">
        <tbody>
            <tr>
                <th scope='row'>
                    <label><?php _e('Commission Value', FS_AFFILIATES_LOCALE); ?></label>
                </th>
                <td>
                    <input type="text" name='landing_commission[commission_value]' class ='fs_affiliates_input_price' value="<?php echo $commission_value; ?>"/>
                </td>
            </tr>
            <tr>
                <th scope='row'>
                    <label><?php _e('Landing Commission Status', FS_AFFILIATES_LOCALE); ?></label>
                </th>
                <td>
                    <select name='landing_commission[status]'>
                        <?php
                        $selected_status = array(
                            'fs_active' => __('Active', FS_AFFILIATES_LOCALE),
                            'fs_inactive' => __('Inactive', FS_AFFILIATES_LOCALE),
                        );
                        foreach ($selected_status as $type => $name) {
                            ?>
                            <option value="<?php echo $type; ?>" <?php selected($status, $type); ?>><?php echo $name; ?></option>
                        <?php } ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th scope='row'>
                    <label><?php _e('Referral Status', FS_AFFILIATES_LOCALE); ?></label>
                </th>
                <td>
                    <select name='landing_commission[referral_status]'>
                        <?php
                        $referral_status = array(
                            'fs_pending' => __('Pending', FS_AFFILIATES_LOCALE),
                            'fs_unpaid' => __('Unpaid', FS_AFFILIATES_LOCALE),
                        );
                        foreach ($referral_status as $type => $name) {
                            ?>
                            <option value="<?php echo $type; ?>" <?php selected($status, $type); ?>><?php echo $name; ?></option>
                        <?php } ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th scope='row'>
                    <label><?php _e('Cookie Validity', FS_AFFILIATES_LOCALE); ?></label>
                </th>
                <td>
                    <input type="number" min="0" name='landing_commission[cookie_validity][number]' value="<?php echo $cookie_validity_number; ?>"/>
                    <select name='landing_commission[cookie_validity][unit]'>
                        <?php
                        $default_periods = array(
                            'days' => __('Day(s)', FS_AFFILIATES_LOCALE),
                            'weeks' => __('Week(s)', FS_AFFILIATES_LOCALE),
                            'months' => __('Month(s)', FS_AFFILIATES_LOCALE),
                            'years' => __('Year(s)', FS_AFFILIATES_LOCALE),
                        );
                        foreach ($default_periods as $value => $label) {
                            ?>
                            <option value="<?php echo $value; ?>" <?php selected($cookie_validity_unit, $value); ?>><?php echo $label; ?></option>
                        <?php } ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th scope='row'>
                    <label><?php esc_html_e('Usage Type', FS_AFFILIATES_LOCALE); ?></label>
                </th>
                <td>
                    <select name='landing_commission[usage_type]' class='fs_affs_lc_usage_type'>
                        <?php
                        $default_options = array(
                            '1' => esc_html__('Unlimited', FS_AFFILIATES_LOCALE),
                            '2' => esc_html__('Limited', FS_AFFILIATES_LOCALE),
                        );
                        
                        foreach ($default_options as $value => $label) { ?>
                            <option value="<?php echo esc_attr($value); ?>" <?php selected($usage_type, $value); ?>><?php echo esc_attr($label); ?></option>
                        <?php } ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th scope='row'>
                    <label><?php esc_html_e('Usage Count', FS_AFFILIATES_LOCALE); ?></label>
                </th>
                <td>
                    <input type="number" min="0" class='fs_affs_lc_validity_count' name='landing_commission[validity_count]' value="<?php echo esc_attr($validity_count); ?>"/>
                </td>
            </tr>
        </tbody>
    </table>
    <p class="submit">
        <input name='<?php echo $this->plugin_slug; ?>_save' class='button-primary <?php echo $this->plugin_slug; ?>_save_btn' type='submit' value="<?php _e('Create Landing Commission', FS_AFFILIATES_LOCALE); ?>" />
        <input type="hidden" name="new_landing_commission" value="add-new"/>
        <?php
        wp_nonce_field($this->plugin_slug . '_new_landing_commission', '_' . $this->plugin_slug . '_nonce', false, true);
        ?>
    </p>
</div>

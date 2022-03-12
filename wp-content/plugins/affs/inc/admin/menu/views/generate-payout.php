<?php
/* New Affiliates Referrals Page */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
?>
<div class="<?php echo $this->plugin_slug; ?>_affiliates_new">
    <h2><?php _e('Generate Payout', FS_AFFILIATES_LOCALE); ?></h2>
    <table class="form-table">
        <tbody>
            <tr>
                <th scope='row'>
                    <label><?php _e('Payout Method', FS_AFFILIATES_LOCALE); ?></label>
                </th>
                <td>
                    <select name='referral[payout_method]'>
                        <?php
                        $methods = apply_filters($this->plugin_slug . '_admin_field_payout_methods', array(''       => __('Select a Payout Method', FS_AFFILIATES_LOCALE),
                            'direct' => __('Bank Transfer', FS_AFFILIATES_LOCALE),
                            'paypal' => __('Paypal', FS_AFFILIATES_LOCALE)
                                ));
                        foreach ($methods as $id => $method):
                            ?>
                            <option value="<?php echo $id; ?>"><?php echo $method; ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th scope='row'>
                    <label><?php esc_html_e('Affiliate(s) Selection', FS_AFFILIATES_LOCALE); ?></label>
                </th>
                <td>
                    <select name='referral[affiliate_select_type]'>
                        <?php
                        $options = array('all'     => esc_html__('All Affiliates', FS_AFFILIATES_LOCALE),
                            'include' => esc_html__('Include Affiliate(s)', FS_AFFILIATES_LOCALE),
                            'exclude' => esc_html__('Exclude Affiliate(s)', FS_AFFILIATES_LOCALE));
                        foreach ($options as $id => $option):
                            ?>
                            <option value="<?php echo $id; ?>"><?php echo $option; ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th scope='row'>
                    <label><?php esc_html_e('Select Affiliate(s)', FS_AFFILIATES_LOCALE); ?></label>
                </th>
                <td>
                    <?php
                    $selection_args = array(
                        'id'          => 'user_id',
                        'name'        => 'referral[selected_affiliate]',
                        'list_type'   => 'affiliates',
                        'action'      => 'fs_affiliates_search',
                        'placeholder' => esc_html__('Search a Affiliate', FS_AFFILIATES_LOCALE),
                        'multiple'    => true,
                        'selected'    => true,
                        'options'     => array(),
                            );
                    fs_affiliates_select2_html($selection_args);
                    ?>
                </td>
            </tr>
            <tr>
                <th scope='row'>
                    <label><?php _e('From Date', FS_AFFILIATES_LOCALE); ?></label>
                </th>
                <td>
                    <input type="text" name='referral[from_date]' class="fs_affilaites_datepicker" value=''/>
                </td>
            </tr>
            <tr>
                <th scope='row'>
                    <label><?php _e('To Date', FS_AFFILIATES_LOCALE); ?></label>
                </th>
                <td>
                    <input type="text" name='referral[to_date]' class="fs_affilaites_datepicker" value=''/>
                </td>
            </tr>
            <tr>
                <th scope='row'>
                    <label><?php _e('Minimum Threshold', FS_AFFILIATES_LOCALE); ?></label>
                </th>
                <td>
                    <input type="number" min="0" step="0.001" name='referral[min_threshold]' value=''/>
                </td>
            </tr>
            <tr style="display: none;">
                <th scope='row'>
                    <label><?php _e('Referral Status', FS_AFFILIATES_LOCALE); ?></label>
                </th>
                <td>
                    <select name='referral[referral_status]'>
                        <?php
                        $referral_status = array('fs_unpaid' => __('Unpaid', FS_AFFILIATES_LOCALE), 'fs_in_progress' => __('In-Progress', FS_AFFILIATES_LOCALE));
                        foreach ($referral_status as $status => $status_label):
                            ?>
                            <option value="<?php echo $status; ?>"><?php echo $status_label; ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th scope='row'>
                    <label><?php _e('Mark as paid', FS_AFFILIATES_LOCALE); ?></label>
                </th>
                <td>
                    <input type="checkbox" name='referral[paid_status]' value='yes'/>
                </td>
            </tr>
        </tbody>
    </table>
    <p class="submit">
        <input type="hidden" id="exported_data" value=""/>
        <input type="button" class="fs_affiliates-exporter-button fs_affiliates_add_btn" style="margin-top:0px !important; margin-right: 15px;" value="<?php esc_attr_e('GENERATE CSV', FS_AFFILIATES_LOCALE); ?>">
        <input name='<?php echo $this->plugin_slug; ?>_save' class='button-primary <?php echo $this->plugin_slug; ?>_save_btn' type='submit' value="<?php _e('GENERATE PAYOUT', FS_AFFILIATES_LOCALE); ?>" />
        <input type="hidden" name="generate_payouts" value="1"/>
        <?php
        wp_nonce_field($this->plugin_slug . '_process_payouts', '_' . $this->plugin_slug . '_nonce', false, true);
        ?>
    </p>
</div>

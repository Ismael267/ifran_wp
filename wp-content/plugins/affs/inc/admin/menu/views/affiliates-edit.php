<?php
/* Edit Affiliates Page */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if('2' == get_option('fs_affiliates_payment_method_selection_type', '1')) {
    $payment_preference = fs_affiliates_paymethod_preference();
} else {
    $payment_preference = fs_affiliates_get_available_payment_method($affiliates_object->get_id());
}

$default_paymethod = fs_affiliates_get_default_gateway($payment_preference);
$payment_datas = get_post_meta($affiliates_object->get_id(), 'fs_affiliates_user_payment_datas', true);
$saved_pay_method = isset($payment_datas['fs_affiliates_payment_method']) ? $payment_datas['fs_affiliates_payment_method'] : '';
$pay_method = ( empty($saved_pay_method) && !empty($default_paymethod) ) ? $default_paymethod : $saved_pay_method;
$paypal_email = isset($payment_datas['fs_affiliates_paypal_email']) ? $payment_datas['fs_affiliates_paypal_email'] : '';
$pay_bank = isset($payment_datas['fs_affiliates_bank_details']) ? $payment_datas['fs_affiliates_bank_details'] : '';
?>
<div class="<?php echo $this->plugin_slug; ?>_affiliates_edit">
    <h2><?php _e('Edit Affiliate', FS_AFFILIATES_LOCALE); ?></h2>
    <table class="form-table fs_affiliates_block">
        <tbody>
            <tr>
                <th scope='row'>
                    <label><?php _e('First Name', FS_AFFILIATES_LOCALE); ?></label>
                </th>
                <td>
                    <input type="text" name='affiliate[first_name]' value='<?php echo $affiliates_object->first_name; ?>'/>
                </td>
            </tr>
            <tr>
                <th scope='row'>
                    <label><?php _e('Last Name', FS_AFFILIATES_LOCALE); ?></label>
                </th>
                <td>
                    <input type="text" name='affiliate[last_name]' value='<?php echo $affiliates_object->last_name; ?>'/>
                </td>
            </tr>
            <tr>
                <th scope='row'>
                    <label><?php _e('Username', FS_AFFILIATES_LOCALE); ?></label>
                </th>
                <td>
                    <input type="hidden" name="affiliate[id]" value='<?php echo $affiliates_object->get_id(); ?>'/>
                    <input type="text" name='affiliate[user_name]' value='<?php echo $affiliates_object->user_name; ?>'/>
                </td>
            </tr>
            <tr>
                <th scope='row'>
                    <label><?php _e('Affiliate ID', FS_AFFILIATES_LOCALE); ?></label>
                </th>
                <td>
                    <input type="text" disabled="disabled" value='<?php echo $affiliates_object->get_id(); ?>'/>
                </td>
            </tr>
            <tr>
                <th scope='row'>
                    <label><?php _e('Registration Date', FS_AFFILIATES_LOCALE); ?></label>
                </th>
                <td>
                    <input type="text" disabled="disabled" value='<?php echo fs_affiliates_local_datetime($affiliates_object->date); ?>'/>
                </td>
            </tr>
            <tr>
                <th scope='row'>
                    <label><?php _e('Parent Affiliate', FS_AFFILIATES_LOCALE); ?></label>
                </th>
                <td>
                    <?php
                    $parent_selection_args = array(
                        'id' => 'user_id',
                        'name' => 'affiliate[parent]',
                        'list_type' => 'affiliates',
                        'action' => 'fs_affiliates_search',
                        'placeholder' => __('Search a Affiliate', FS_AFFILIATES_LOCALE),
                        'multiple' => false,
                        'selected' => true,
                        'options' => ($affiliates_object->parent) ? (array) $affiliates_object->parent : array(),
                    );
                    fs_affiliates_select2_html($parent_selection_args);
                    ?>
                </td>
            </tr>
            <tr>
                <th scope='row'>
                    <label><?php _e('Email', FS_AFFILIATES_LOCALE); ?></label>
                </th>
                <td>
                    <input type="email" name='affiliate[email]' value='<?php echo $affiliates_object->email; ?>'/>
                </td>
            </tr>
            <tr>
                <th scope='row'>
                    <label><?php _e('Phone Number', FS_AFFILIATES_LOCALE); ?></label>
                </th>
                <td>
                    <input type="text" name='affiliate[phone_number]' value='<?php echo $affiliates_object->phone_number; ?>'/>
                </td>
            </tr>
            <tr>
                <th scope='row'>
                    <label><?php _e('Website', FS_AFFILIATES_LOCALE); ?></label>
                </th>
                <td>
                    <input type="url" name='affiliate[website]' value='<?php echo $affiliates_object->website; ?>'/>
                </td>
            </tr>
            <tr>
                <th scope='row'>
                    <label><?php _e('Promotion Methods', FS_AFFILIATES_LOCALE); ?></label>
                </th>
                <td>
                    <textarea name='affiliate[promotion]'><?php echo $affiliates_object->promotion; ?></textarea>
                </td>
            </tr>
            <?php if (fs_affiliates_check_is_array($payment_preference)) { 
                       $read_only_select = ('2' == get_option('fs_affiliates_payment_method_selection_type', '1')) ? '' : 'disabled="disabled"';
                ?>
                <tr>
                    <th scope='row'>
                        <label><?php esc_html_e('Payment Method', FS_AFFILIATES_LOCALE); ?></label>
                    </th>
                    <td>
                        <select name="affiliate[payment_method]" <?php esc_html_e($read_only_select); ?>>
                            <?php
                            foreach ($payment_preference as $paykey => $status) {
                                $payment_label = fs_affiliates_get_paymethod_preference($paykey);

                                if (( $paykey == 'wallet' && !fs_affiliates_is_wallet_eligible($affiliates_object->get_id()) ) || ( $status == 'disable' && '1' == get_option('fs_affiliates_payment_method_selection_type', '1'))) {
                                    continue;
                                }
                                
                                if (empty($saved_pay_method)) { ?>
                                    <option><?php esc_html_e('Not Yet Selected', FS_AFFILIATES_LOCALE); ?></option>
                                <?php break; } ?>

                                <option value="<?php echo $paykey ?>"  <?php if ($pay_method == $paykey) { ?> selected="selected" <?php } ?> ><?php _e($payment_label, FS_AFFILIATES_LOCALE); ?></option>
                            <?php } ?>
                        </select>
                    </td>
                </tr>

                <?php
                if (!empty($saved_pay_method)) {
                    if ('paypal' == $pay_method) {
                        ?>
                        <tr>
                            <th scope='row'>
                                <label><?php esc_html_e('PayPal ID', FS_AFFILIATES_LOCALE); ?></label>
                            </th>
                            <td>
                                <input type="text" name='affiliate[payment_email]' disabled="disabled" value='<?php echo $affiliates_object->payment_email; ?>'/>
                            </td>
                        </tr>
        <?php } ?>
        <?php if ('direct' == $pay_method) { ?>
                        <tr>
                            <th scope='row'>
                                <label><?php esc_html_e('Bank Details', FS_AFFILIATES_LOCALE); ?></label>
                            </th>
                            <td>
                                <textarea disabled="disabled"><?php echo esc_attr($pay_bank); ?></textarea>
                            </td>
                        </tr>
        <?php }
    }
}
?>

            <tr>
                <th scope='row'>
                    <label><?php _e('Affiliate Status', FS_AFFILIATES_LOCALE); ?></label>
                </th>
                <td>
                    <select name='affiliate[status]'>
                        <?php
                        $status_options = apply_filters('fs_get_affiliates_status_to_display_in_edit_page', array(
                            'fs_active' => __('Active', FS_AFFILIATES_LOCALE),
                            'fs_hold' => __('On-Hold', FS_AFFILIATES_LOCALE),
                            'fs_pending_approval' => __('Pending Approval', FS_AFFILIATES_LOCALE),
                            'fs_suspended' => __('Suspended', FS_AFFILIATES_LOCALE),
                            'fs_rejected' => __('Rejected', FS_AFFILIATES_LOCALE),
                                ), $affiliates_object);
                        foreach ($status_options as $status_type => $status_name) {
                            ?>
                            <option value="<?php echo $status_type; ?>" <?php selected($affiliates_object->get_status(), $status_type); ?>><?php echo $status_name; ?></option>
<?php } ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th scope='row'>
                    <label><?php _e('Commission Type', FS_AFFILIATES_LOCALE); ?></label>
                </th>
                <td>
                    <select name='affiliate[commission_type]' class='fs_affiliates_commission_type'>
                        <?php
                        $commission_options = array(
                            'percentage' => __('Percentage Based Commission', FS_AFFILIATES_LOCALE),
                            'fixed' => __('Fixed Price Commission', FS_AFFILIATES_LOCALE),
                        );
                        foreach ($commission_options as $type => $name) {
                            ?>
                            <option value="<?php echo $type; ?>" <?php selected($affiliates_object->commission_type, $type); ?>><?php echo $name; ?></option>
<?php } ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th scope='row'>
                    <label><?php _e('Commission Value', FS_AFFILIATES_LOCALE); ?></label>
                </th>
                <td>
                    <input type="text" name='affiliate[commission_value]' class='fs_affiliates_commission_value fs_affiliates_input_price' value='<?php echo fs_affiliates_format_decimal($affiliates_object->commission_value); ?>'/>
                </td>
            </tr>
            <tr>
                <th scope='row'>
                    <label><?php _e('Documents', FS_AFFILIATES_LOCALE); ?></label>
                </th>
                <td colspan="2">
                    <table class="fs_affiliates_fileupload_table">
                        <thead>
                            <tr>
                                <td><?php _e('File Name', FS_AFFILIATES_LOCALE); ?></td>
                                <td><?php _e('Download', FS_AFFILIATES_LOCALE); ?></td>
                                <td><?php _e('Delete', FS_AFFILIATES_LOCALE); ?></td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Prepare email address hash.
                            $email_hash = function_exists('hash') ? hash('sha256', $affiliates_object->email) : sha1($affiliates_object->email);
                            $url = add_query_arg(array('email' => $affiliates_object->email, 'fs_nonce' => $email_hash), site_url());
                            set_transient('fs_affiliates_file_upload_' . $affiliates_object->get_id(), array_filter((array) $affiliates_object->uploaded_files), 3600);
                            if (fs_affiliates_check_is_array($affiliates_object->uploaded_files)) {
                                foreach ($affiliates_object->uploaded_files as $file_name => $file_url) {
                                    ?> <tr>
                                        <td><b><?php echo $file_name; ?> </b></td>
                                        <td style="text-align:center">
                                            <input type="hidden" class="fs_affiliates_download_file" value="<?php echo $file_name; ?>"/>
                                            <a class="fs_affiliates_download_btns" href="<?php echo add_query_arg(array('download_file' => $file_name), $url); ?>"><?php _e('Download', FS_AFFILIATES_LOCALE); ?><i class="fa fa-download" aria-hidden="true"></i></a>
                                        </td>
                                        <td style="text-align:center">
                                            <span class="fs_affiliates_delete_table_uploaded_file" style="color:red;cursor:pointer;">[x]
                                                <input type="hidden" class="fs_affiliates_uploaded_file_key" value="fs_affiliates_file_upload_<?php echo $affiliates_object->get_id(); ?>"/>
                                                <input type="hidden" class="fs_affiliates_remove_file" value="<?php echo $file_name; ?>"/>
                                            </span>
                                        </td>
                                    </tr><?php
                        }
                    }
                            ?>
                        </tbody>
                    </table>
                    <div class="fs_affiliates_file_uploader">
                        <div class="fs_affiliates_display_file_names">
                            <input type="hidden" class="fs_affiliates_uploaded_file_key" value="fs_affiliates_file_upload_<?php echo $affiliates_object->get_id(); ?>"/>
                        </div>

                        <input type="file"
                               name="fs_affiliates_file_upload_<?php echo $affiliates_object->get_id(); ?>"
                               class="fs_affiliates_file_upload"/>
                    </div>
                </td>
            </tr>
<?php
$CheckIfModuleEnabled = apply_filters('fs_affiliates_is_affiliate_level_product_commission', false);
if ($CheckIfModuleEnabled) {
    ?>
                <tr>
                    <th><?php _e('WooCommerce Product Rate(s)', FS_AFFILIATES_LOCALE); ?></th>
                </tr>
                <tr>
                    <th scope='row'>
                        <label><?php _e('Rule Priority', FS_AFFILIATES_LOCALE); ?></label>
                    </th>
                    <td>
                        <select name='affiliate[rule_priority]' class='fs_affiliates_rule_priority'>
                            <?php
                            $rule_priority = array(
                                '1' => __('First Matched Rule', FS_AFFILIATES_LOCALE),
                                '2' => __('Last Matched Rule', FS_AFFILIATES_LOCALE),
                            );
                            foreach ($rule_priority as $value => $name) {
                                ?>
                                <option value="<?php echo $value; ?>" <?php selected($affiliates_object->rule_priority, $value); ?>><?php echo $name; ?></option>
    <?php } ?>
                        </select>
                    </td>
                </tr>
                <tr class="fs_append_rule_for_product_rate">
                    <th scope='row'><?php _e('Product', FS_AFFILIATES_LOCALE); ?></th>
                    <th scope='row'><?php _e('Commission Type', FS_AFFILIATES_LOCALE); ?></th>
                    <th scope='row'><?php _e('Commission Rate', FS_AFFILIATES_LOCALE); ?></th>
                    <th scope='row'><?php _e('Remove', FS_AFFILIATES_LOCALE); ?></th>
                </tr>
    <?php
    $wcratesdata = get_post_meta($affiliate_id, 'wc_product_rates', true);
    if (fs_affiliates_check_is_array($wcratesdata))
        include_once( FS_AFFILIATES_PLUGIN_PATH . '/inc/admin/menu/views/affiliates-wc-product-rate-table.php' );
    ?>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>
                        <input class='button-primary <?php echo $this->plugin_slug; ?>_save_btn' id="fs_add_product_rates" type="button" value="<?php _e('Add', FS_AFFILIATES_LOCALE); ?>">
                    </td>
                </tr>
        <?php } ?>
        </tbody>
    </table>
    <p class="submit">
        <input name='<?php echo $this->plugin_slug; ?>_save' class='button-primary <?php echo $this->plugin_slug; ?>_save_btn' type='submit' value="<?php _e('Update Affiliate', FS_AFFILIATES_LOCALE); ?>" />
        <input type="hidden" name="edit_affiliates" value="add-edit"/>
    <?php
    wp_nonce_field($this->plugin_slug . '_edit_affiliates', '_' . $this->plugin_slug . '_nonce', false, true);
    ?>
    </p>
<?php
fs_graph_for_mlm($affiliate_id);
?>
</div>

<?php
/* New Affiliates Page */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
$user_selection = isset($_POST['affiliate']['user_selection']) ? $_POST['affiliate']['user_selection'] : '';
$user_id = isset($_POST['affiliate']['user_id']) ? $_POST['affiliate']['user_id'] : array();
$parent = isset($_POST['affiliate']['parent']) ? $_POST['affiliate']['parent'] : array();
$first_name = isset($_POST['affiliate']['first_name']) ? $_POST['affiliate']['first_name'] : '';
$last_name = isset($_POST['affiliate']['last_name']) ? $_POST['affiliate']['last_name'] : '';
$user_name = isset($_POST['affiliate']['user_name']) ? $_POST['affiliate']['user_name'] : '';
$phone_number = isset($_POST['affiliate']['phone_number']) ? $_POST['affiliate']['phone_number'] : '';
$email = isset($_POST['affiliate']['email']) ? $_POST['affiliate']['email'] : '';
$payment_email = isset($_POST['affiliate']['payment_email']) ? $_POST['affiliate']['payment_email'] : '';
$website = isset($_POST['affiliate']['website']) ? $_POST['affiliate']['website'] : '';
$promotion = isset($_POST['affiliate']['promotion']) ? $_POST['affiliate']['promotion'] : '';
$user_role = isset($_POST['affiliate']['user_role']) ? $_POST['affiliate']['user_role'] : 'subscriber';
$country = isset($_POST['affiliate']['country']) ? $_POST['affiliate']['country'] : '';
$status = isset($_POST['affiliate']['status']) ? $_POST['affiliate']['status'] : '';
$commission_type = isset($_POST['affiliate']['commission_type']) ? $_POST['affiliate']['commission_type'] : '';
$commission_value = isset($_POST['affiliate']['commission_value']) ? $_POST['affiliate']['commission_value'] : '';
$email_notification = isset($_POST['affiliate']['email_notification']) ? $_POST['affiliate']['email_notification'] : '0';
$rulepriority = isset($_POST['affiliate']['rule_priority']) ? $_POST['affiliate']['rule_priority'] : '';
?>
<div class="<?php echo $this->plugin_slug; ?>_affiliates_new">
    <h2><?php _e('New Affiliate', FS_AFFILIATES_LOCALE); ?></h2>
    <table class="form-table">
        <tbody>
            <tr>
                <th scope='row'>
                    <label><?php _e('User Selection', FS_AFFILIATES_LOCALE); ?></label>
                </th>
                <td>
                    <select name='affiliate[user_selection]' class="user_selection_type">
                        <?php
                        $user_selections = array(
                            'new' => __('New User', FS_AFFILIATES_LOCALE),
                            'existing' => __('Existing User', FS_AFFILIATES_LOCALE),
                                );
                        foreach ($user_selections as $user_selection_type => $user_selection_name) {
                            ?>
                            <option value="<?php echo $user_selection_type; ?>" <?php selected($user_selection, $user_selection_type); ?> ><?php echo $user_selection_name; ?></option>
                        <?php } ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th scope='row'>
                    <label><?php _e('Select User', FS_AFFILIATES_LOCALE); ?></label>
                </th>
                <td>
                    <?php
                    $user_selection_args = array(
                        'id' => 'user_id',
                        'name' => 'affiliate[user_id]',
                        'class' => 'existing_user_selection',
                        'list_type' => 'customers',
                        'action' => 'fs_user_search',
                        'placeholder' => __('Search a User', FS_AFFILIATES_LOCALE),
                        'multiple' => false,
                        'selected' => true,
                        'options' => $user_id
                            );
                    fs_affiliates_select2_html($user_selection_args);
                    ?>
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
                        'list_type' => 'customers',
                        'action' => 'fs_affiliates_search',
                        'placeholder' => __('Search a Affiliate', FS_AFFILIATES_LOCALE),
                        'multiple' => false,
                        'selected' => true,
                        'options' => (array) $parent
                            );
                    fs_affiliates_select2_html($parent_selection_args);
                    ?>
                </td>
            </tr>
            <tr>
                <th scope='row'>
                    <label><?php _e('First Name', FS_AFFILIATES_LOCALE); ?></label>
                </th>
                <td>
                    <input type="text" name='affiliate[first_name]' class="new_user_selection" value='<?php echo $first_name; ?>'/>
                </td>
            </tr>
            <tr>
                <th scope='row'>
                    <label><?php _e('Last Name', FS_AFFILIATES_LOCALE); ?></label>
                </th>
                <td>
                    <input type="text" name='affiliate[last_name]' class="new_user_selection" value='<?php echo $last_name; ?>'/>
                </td>
            </tr>
            <tr>
                <th scope='row'>
                    <label><?php _e('Username', FS_AFFILIATES_LOCALE); ?></label>
                </th>
                <td>
                    <input type="text" name='affiliate[user_name]' class="new_user_selection fs_affiliates_user_name" value='<?php echo $user_name; ?>'/>
                </td>
            </tr>
            <tr>
                <th scope='row'>
                    <label><?php _e('Email', FS_AFFILIATES_LOCALE); ?></label>
                </th>
                <td>
                    <input type="email" name='affiliate[email]' class="new_user_selection fs_affiliates_user_email" value='<?php echo $email; ?>'/>
                </td>
            </tr>
            <tr>
                <th scope='row'>
                    <label><?php _e('Phone Number', FS_AFFILIATES_LOCALE); ?></label>
                </th>
                <td>
                    <input type="text" name='affiliate[phone_number]' value='<?php echo $phone_number; ?>'/>
                </td>
            </tr>
            <tr>
                <th scope='row'>
                    <label><?php _e('Password', FS_AFFILIATES_LOCALE); ?></label>
                </th>
                <td>
                    <input type="text" name='affiliate[password]' class="new_user_selection" value=''/>
                </td>
            </tr>
            <tr>
                <th scope='row'>
                    <label><?php _e('Repeat Password', FS_AFFILIATES_LOCALE); ?></label>
                </th>
                <td>
                    <input type="text" name='affiliate[repeated_password]' class="new_user_selection" value=''/>
                </td>
            </tr>
            <tr>
                <th scope='row'>
                    <label><?php _e('Website', FS_AFFILIATES_LOCALE); ?></label>
                </th>
                <td>
                    <input type="url" name='affiliate[website]' class="new_user_selection" value='<?php echo $website; ?>'/>
                </td>
            </tr>
            <tr>
                <th scope='row'>
                    <label><?php _e('Promotion Methods', FS_AFFILIATES_LOCALE); ?></label>
                </th>
                <td>
                    <textarea name='affiliate[promotion]'><?php echo $promotion; ?></textarea>
                </td>
            </tr>
            <?php if ('2' == get_option('fs_affiliates_payment_method_selection_type', '1')) { ?>
                <tr>
                    <th scope='row'>
                        <label><?php esc_html_e('Payment Method', FS_AFFILIATES_LOCALE); ?></label>
                    </th>
                    <td>
                        <select name="affiliate[payment_method]">
                            <?php
                            $payment_preference = fs_affiliates_get_available_payment_method();

                            foreach ($payment_preference as $paykey => $status) {
                                $payment_label = fs_affiliates_get_paymethod_preference($paykey);
                                ?> <option value="<?php echo $paykey ?>"><?php esc_html_e($payment_label, FS_AFFILIATES_LOCALE); ?></option> <?php
                            }
                            ?>
                        </select>
                    </td>
                </tr>
            <?php } ?>
            <tr>
                <th scope='row'>
                    <label><?php _e('WordPress User Role', FS_AFFILIATES_LOCALE); ?></label>
                </th>
                <td>
                    <select name='affiliate[user_role]' class="new_user_selection">
                        <?php
                        $user_roles = fs_affiliates_get_user_roles();
                        if (fs_affiliates_check_is_array($user_roles)) {
                            foreach ($user_roles as $role_key => $role_name) {
                                if ($role_key == 'administrator')
                                    continue;
                                ?>
                                <option value="<?php echo $role_key; ?>" <?php if ($role_key == $user_role) { ?> selected="" <?php } ?>> <?php echo $role_name; ?></option>
                                <?php
                            }
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th scope='row'>
                    <label><?php _e('Affiliate Status', FS_AFFILIATES_LOCALE); ?></label>
                </th>
                <td>
                    <select name='affiliate[status]'>
                        <?php
                        $status_options = array(
                            'fs_active' => __('Active', FS_AFFILIATES_LOCALE),
                            'fs_hold' => __('On-Hold', FS_AFFILIATES_LOCALE),
                            'fs_pending_approval' => __('Pending Approval', FS_AFFILIATES_LOCALE),
                            'fs_suspended' => __('Suspended', FS_AFFILIATES_LOCALE),
                            'fs_rejected' => __('Rejected', FS_AFFILIATES_LOCALE),
                                );
                        foreach ($status_options as $status_type => $status_name) {
                            ?>
                            <option value="<?php echo $status_type; ?>" <?php selected($status, $status_type); ?> ><?php echo $status_name; ?></option>
                        <?php } ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th scope='row'>
                    <label><?php _e('Country', FS_AFFILIATES_LOCALE); ?></label>
                </th>
                <td>
                    <select name='affiliate[country]'>
                        <?php
                        $countries = get_fs_affiliates_countries();
                        foreach ($countries as $code => $name) {
                            ?>
                            <option value="<?php echo $code; ?>" <?php selected($country, $code); ?> ><?php echo $name; ?></option>
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
                            <option value="<?php echo $type; ?>" <?php selected($commission_type, $type); ?>><?php echo $name; ?></option>
                        <?php } ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th scope='row'>
                    <label><?php _e('Commission Value', FS_AFFILIATES_LOCALE); ?></label>
                </th>
                <td>
                    <input type="text" name='affiliate[commission_value]' class='fs_affiliates_commission_value fs_affiliates_input_price' value='<?php echo fs_affiliates_format_decimal($commission_value); ?>' />
                </td>
            </tr>

            <?php
            $notification_obj = FS_Affiliates_Notification_Instances::get_notification_by_id('account_creation_by_admin');
            $readonly_attr = '';
            $guide_link = '';
            if (!$notification_obj->is_enabled()) {
                ?><style>
                input[type="checkbox"][readonly] { pointer-events: none; }
            </style><?php
            $readonly_attr = 'readonly';
            $guide_link = __('Turn on the notification settings[' . '<b>' . 'SUMO Affiliates Pro -> Notifications -> Affiliate - Account Creation by Admin' . '</b>' . '] to send the email to affiliate', FS_AFFILIATES_LOCALE);
        }
        ?>
        <tr>
            <th scope='row'>
                <label><?php _e('Affiliate Registration Email Notification', FS_AFFILIATES_LOCALE); ?></label>
            </th>
            <td>
                <input type="checkbox" name='affiliate[email_notification]' value='1' <?php echo $readonly_attr;
        checked($email_notification, '1');
        ?>/>&nbsp;&nbsp;
<?php echo $guide_link; ?>
            </td>

        </tr>
        <tr>
            <th scope='row'>
                <label><?php _e('Documents', FS_AFFILIATES_LOCALE); ?></label>
            </th>
            <td>
                <div class="fs_affiliates_file_uploader">
                    <div class="fs_affiliates_display_file_names">
                        <input type="hidden" name="affiliate[uploaded_key]" class="fs_affiliates_uploaded_file_key" value="fs_affiliates_file_upload_<?php echo time(); ?>"/>
                    </div>

                    <input type="file"
                           name="fs_affiliates_file_upload_<?php echo time(); ?>"
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
                            <option value="<?php echo $value; ?>" <?php selected($rulepriority, $value); ?>><?php echo $name; ?></option>
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
            $wcratesdata = '';
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
        <input name='<?php echo $this->plugin_slug; ?>_save' class='button-primary <?php echo $this->plugin_slug; ?>_save_btn' type='submit' value="<?php _e('Register', FS_AFFILIATES_LOCALE); ?>" />
        <input type="hidden" name="register_new_affiliates" value="add-new"/>
        <?php
        wp_nonce_field($this->plugin_slug . '_register_new_affiliates', '_' . $this->plugin_slug . '_nonce', false, true);
        ?>
    </p>
</div>

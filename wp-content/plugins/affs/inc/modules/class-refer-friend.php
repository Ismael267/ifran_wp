<?php
/**
 * Refer a Friend
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!class_exists('FS_Affiliates_Refer_Friend_Module')) {

    /**
     * Class FS_Affiliates_Refer_Friend_Module
     */
    class FS_Affiliates_Refer_Friend_Module extends FS_Affiliates_Modules {
        /*
         * Data
         */

        protected $data = array(
            'enabled' => 'no',
            'custom_css' => '',
            'prefilled_subject' => '',
            'prefilled_message' => 'My Referral Link - [fs_affiliate_link]',
            'email_label' => 'Email ID',
            'email_message_label' => 'Email Message',
            'subject_label' => 'Email Subject',
            'email_placeholder' => 'Email ID',
            'subject_placeholder' => 'Email Subject',
            'email_message_placeholder' => 'Email Message',
            'email_subject_field_error' => 'Please enter a subject',
            'email_field_error' => 'Please enter a Valid Email ID',
            'email_message_field_error' => 'Please enter a message',
            'email_sent_success' => 'Email Sent Successfully',
            'email_sent_fails' => 'Email Sending Failed',
            'allowed_affiliates_method' => '1',
            'selected_affiliates' => array() ,
            'referral_url' => '',
            'subject_edit_type' => '1',
            'message_edit_type' => '1'
                );

        /**
         * Class Constructor
         */
        public function __construct() {
            $this->id = 'refer_friend';
            $this->title = __('Refer a Friend', FS_AFFILIATES_LOCALE);

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
                    'type' => 'title',
                    'title' => __('Form Display Settings', FS_AFFILIATES_LOCALE),
                    'id' => 'refer_friend_display_options',
                ),
                array(
                    'title' => __('Refer a Friend Form will be Available for', FS_AFFILIATES_LOCALE),
                    'desc' => __('This option controls the list of affiliates who will be able to access the Refer a Friend Form.', FS_AFFILIATES_LOCALE),
                    'id' => $this->get_field_key('allowed_affiliates_method'),
                    'type' => 'select',
                    'class' => 'fs_affiliates_allowed_affiliates_method',
                    'default' => '1',
                    'options' => array(
                        '1' => __('All Affiliates', FS_AFFILIATES_LOCALE),
                        '2' => __('Selected Affiliates', FS_AFFILIATES_LOCALE),
                    ),
                ),
                array(
                    'title' => __('Affiliate Selection', FS_AFFILIATES_LOCALE),
                    'id' => $this->get_field_key('selected_affiliates'),
                    'type' => 'ajaxmultiselect',
                    'class' => 'fs_affiliates_selected_affiliate',
                    'list_type' => 'affiliates',
                    'action' => 'fs_affiliates_search',
                    'default' => array(),
                ),
                array(
                    'title' => __('Subject Type', FS_AFFILIATES_LOCALE),
                    'id' => $this->get_field_key('subject_edit_type'),
                    'type' => 'select',
                    'default' => '1',
                    'options' => array(
                        '1' => __('Editable', FS_AFFILIATES_LOCALE),
                        '2' => __('Non-Editable', FS_AFFILIATES_LOCALE),
                    ),
                ),
                array(
                    'title' => __("Prefilled Subject for Refer a Friend ", FS_AFFILIATES_LOCALE),
                    'id' => $this->get_field_key('prefilled_subject'),
                    'type' => 'text',
                    'default' => "",
                ),
                array(
                    'title' => esc_html__('Default Affiliate Link', FS_AFFILIATES_LOCALE),
                    'id' => $this->get_field_key('referral_url'),
                    'type' => 'text',
                    'default' => site_url(),
                    'desc' => esc_html__('Affiliate Link will be generated based on the page which you are configuring in this field', FS_AFFILIATES_LOCALE),
                ),
                array(
                    'title' => __('Message Type', FS_AFFILIATES_LOCALE),
                    'id' => $this->get_field_key('message_edit_type'),
                    'type' => 'select',
                    'default' => '1',
                    'options' => array(
                        '1' => __('Editable', FS_AFFILIATES_LOCALE),
                        '2' => __('Non-Editable', FS_AFFILIATES_LOCALE),
                    ),
                ),
                array(
                    'title' => __("Prefilled Message for Refer a Friend ", FS_AFFILIATES_LOCALE),
                    'id' => $this->get_field_key('prefilled_message'),
                    'type' => 'textarea',
                    'default' => "My Referral Link - [fs_affiliate_link]",
                ),
                array(
                    'type' => 'sectionend',
                    'id' => 'refer_friend_display_options',
                ),
                array(
                    'type' => 'title',
                    'title' => __('Label Customization', FS_AFFILIATES_LOCALE),
                    'id' => 'refer_friend_label_customization_options',
                ),
                array(
                    'title' => __("Email ID Label", FS_AFFILIATES_LOCALE),
                    'desc' => __('Label for Email ID Field', FS_AFFILIATES_LOCALE),
                    'id' => $this->get_field_key('email_label'),
                    'type' => 'text',
                    'default' => "Email ID",
                ),
                array(
                    'title' => __("Email Subject Label", FS_AFFILIATES_LOCALE),
                    'desc' => __('Label for Email Subject Field', FS_AFFILIATES_LOCALE),
                    'id' => $this->get_field_key('subject_label'),
                    'type' => 'text',
                    'default' => "Email Subject",
                ),
                array(
                    'title' => __('Email Message Label', FS_AFFILIATES_LOCALE),
                    'desc' => __('Label for Email Message Field', FS_AFFILIATES_LOCALE),
                    'id' => $this->get_field_key('email_message_label'),
                    'type' => 'text',
                    'default' => 'Email Message',
                ),
                array(
                    'type' => 'sectionend',
                    'id' => 'refer_friend_label_customization_options',
                ),
                array(
                    'type' => 'title',
                    'title' => __('Placeholder Customization', FS_AFFILIATES_LOCALE),
                    'id' => 'refer_friend_placeholder_customization_options',
                ),
                array(
                    'title' => __("Email ID Placeholder", FS_AFFILIATES_LOCALE),
                    'desc' => __('Placeholder text for Email ID Field', FS_AFFILIATES_LOCALE),
                    'id' => $this->get_field_key('email_placeholder'),
                    'type' => 'text',
                    'default' => "Email ID",
                ),
                array(
                    'title' => __("Email Subject Placeholder", FS_AFFILIATES_LOCALE),
                    'desc' => __('Placeholder text for Email Subject Field', FS_AFFILIATES_LOCALE),
                    'id' => $this->get_field_key('subject_placeholder'),
                    'type' => 'text',
                    'default' => "Email Subject",
                ),
                array(
                    'title' => __('Email Message Placeholder', FS_AFFILIATES_LOCALE),
                    'desc' => __('Placeholder text for Email Message Field', FS_AFFILIATES_LOCALE),
                    'id' => $this->get_field_key('email_message_placeholder'),
                    'type' => 'text',
                    'default' => 'Email Message',
                ),
                array(
                    'type' => 'sectionend',
                    'id' => 'refer_friend_placeholder_customization_options',
                ),
                array(
                    'type' => 'title',
                    'title' => __('Message Settings', FS_AFFILIATES_LOCALE),
                    'id' => __('refer_friend_error_message_options', FS_AFFILIATES_LOCALE),
                ),
                array(
                    'title' => __("Email Field Empty Error", FS_AFFILIATES_LOCALE),
                    'desc' => __('Error Message to display when the email field is left empty', FS_AFFILIATES_LOCALE),
                    'id' => $this->get_field_key('email_field_error'),
                    'type' => 'text',
                    'default' => 'Please enter a Valid Email ID',
                ),
                array(
                    'title' => __('Email Subject Empty Error', FS_AFFILIATES_LOCALE),
                    'desc' => __('Error Message to display when the Email Subject field is left empty', FS_AFFILIATES_LOCALE),
                    'id' => $this->get_field_key('email_subject_field_error'),
                    'type' => 'text',
                    'default' => 'Please enter a subject',
                ),
                array(
                    'title' => __('Email Message Empty Error', FS_AFFILIATES_LOCALE),
                    'desc' => __('Error Message to display when the Email Message field is left empty', FS_AFFILIATES_LOCALE),
                    'id' => $this->get_field_key('email_message_field_error'),
                    'type' => 'text',
                    'default' => 'Please enter a message',
                ),
                array(
                    'title' => __('Email Sent Message', FS_AFFILIATES_LOCALE),
                    'id' => $this->get_field_key('email_sent_success'),
                    'type' => 'text',
                    'default' => 'Email Sent Successfully',
                ),
                array(
                    'title' => __('Email Sending Failed Message', FS_AFFILIATES_LOCALE),
                    'id' => $this->get_field_key('email_sent_fails'),
                    'type' => 'text',
                    'default' => 'Email Sending Failed',
                ),
                array(
                    'type' => 'sectionend',
                    'id' => 'refer_friend_error_message_options',
                ),
                    );
        }

        /*
         * Frontend Action
         */

        public function frontend_action() {
            add_action('fs_affiliates_dashboard_content_referafriend', array($this, 'display_dashboard_content'), 10, 2);
            add_filter('fs_affiliates_frontend_dashboard_affiliate_tools_submenus', array($this, 'refer_a_friend_menu'), 11, 3);
        }

        /*
         * Custom Dashboard Menu
         */

        public function refer_a_friend_menu($menus, $user_id, $affiliate_id) {

            if ($this->allowed_affiliates_method == 2 && (!in_array($affiliate_id, $this->selected_affiliates) )) {
                return $menus;
            }

            $menus['referafriend'] = get_option('fs_affiliates_dashboard_customization_friend_form_label');

            return $menus;
        }

        /*
         * Display Dashboard Content
         */

        public function display_dashboard_content($user_id, $affiliate_id) {
            $refer_mail_content = $this->prefilled_message;
            $link_object = new FS_Affiliates_Refer_friend ();
            $refer_mail_content = str_replace('[fs_affiliate_link]', $link_object->affiliate_link(), $refer_mail_content);
            $subject_readonly = ( '2' == $this->subject_edit_type ) ? 'readonly="readonly"' : '';
            $message_readonly = ( '2' == $this->message_edit_type ) ? 'readonly="readonly"' : '';
            ?>
            <div class="fs_affiliates_form" read>
                <h2><?php _e('Refer a Friend Form', FS_AFFILIATES_LOCALE) ?></h2>
                <div class="fs_affiliates_refer_mail_success fs_affiliates_msg_success" style="display:none;" >
                    <i class="fa fa-check"></i><?php echo esc_html($this->email_sent_success); ?>
                </div>
                <div class="fs_affiliates_refer_mail_fails fs_affiliates_msg_fails" style="display:none;" >
                    <i class="fa fa-exclamation-triangle"></i><?php echo esc_html($this->email_sent_fails); ?>
                </div>
                <form method="post" class="fs_affiliates_form affiliate-form affiliate-form-register register">
                    <p class="affiliate-form-row affiliate-form-row--wide form-row form-row-wide">
                        <label for="fs_affiliates_refer_mails"><?php echo esc_html($this->email_label); ?>&nbsp;<span class="required">*</span></label>
                        <textarea placeholder="<?php echo esc_html($this->email_placeholder); ?>" id="fs_affiliates_refer_mails" name="fs_affiliates_refer_mails" class="affiliate-Input affiliate-Input--text input-text"></textarea>
                    <div class="fs_affiliates_refer_mail_validate fs_affiliates_refer_friend_validation_error" style="display:none;" ><?php echo esc_html($this->email_field_error); ?></div>
                    </p>
                    <p class="affiliate-form-row affiliate-form-row--wide form-row form-row-wide">
                        <label for="fs_affiliates_refer_mail_subject"><?php echo esc_html($this->subject_label); ?>&nbsp;<span class="required">*</span></label>
                        <input type="text"  placeholder="<?php echo esc_html($this->subject_placeholder); ?>"  value="<?php echo esc_html($this->prefilled_subject); ?>" class="affiliate-Input affiliate-Input--text input-text" name="fs_affiliates_refer_mail_subject" id="fs_affiliates_refer_mail_subject" <?php echo esc_attr($subject_readonly);?> />
                    <div class="fs_affiliates_refer_subject_validate fs_affiliates_refer_friend_validation_error" style="display:none;" ><?php echo esc_html($this->email_message_field_error); ?></div>
                    </p>
                    <p class="affiliate-form-row affiliate-form-row--wide form-row form-row-wide">
                        <label for="fs_affiliates_refer_mail_content"><?php echo esc_html($this->email_message_label); ?>&nbsp;<span class="required">*</span></label>
                        <textarea placeholder="<?php echo esc_html($this->email_message_placeholder); ?>" id="fs_affiliates_refer_mail_content" name="fs_affiliates_refer_mail_content" class="affiliate-Input affiliate-Input--text input-text" <?php echo esc_attr($message_readonly);?> ><?php echo $refer_mail_content; ?></textarea>
                    <div class="fs_affiliates_refer_content_validate fs_affiliates_refer_friend_validation_error" style="display:none;" ><?php echo esc_html($this->email_subject_field_error); ?></div>
                    </p>
                    <p class="affiliate-FormRow form-row">
            <?php wp_nonce_field('affiliate-update', 'affiliate-update-nonce'); ?>
                        <input type="hidden" id="fs_affiliates_hidden_id" value="<?php echo $affiliate_id; ?>" >
                        <button type="button" id="fs_affiliates_form_send_mail" class="fs_affiliates_form_save affiliate-Button button" name="aff_update" value="aff_update_details"><?php esc_html_e('SEND EMAIL', FS_AFFILIATES_LOCALE); ?></button>
                    </p>
                </form>
            </div>
            <?php
        }

    }

}

<?php
/**
 * Admin Settings Class
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!class_exists('FS_Affiliates_Settings')) {

    /**
     * FS_Affiliates_Settings Class
     */
    class FS_Affiliates_Settings {

        /**
         * Setting pages.
         */
        private static $settings = array();

        /**
         * Error messages.
         */
        private static $errors = array();

        /**
         * Plugin slug.
         */
        private static $plugin_slug = 'fs_affiliates';

        /**
         * Update messages.
         */
        private static $messages = array();

        /**
         * Include the settings page classes.
         */
        public static function get_settings_pages() {
            if (!empty(self::$settings))
                return self::$settings;

            include_once FS_AFFILIATES_PLUGIN_PATH . '/inc/abstracts/class-fs-affiliates-settings-page.php';

            $settings = array();
            $tabs = self::settings_page_tabs();

            foreach ($tabs as $tab_name) {
                $settings[] = include 'tabs/' . $tab_name . '.php';
            }

            self::$settings = apply_filters(self::$plugin_slug . '_get_settings_pages', $settings);

            return self::$settings;
        }

        /**
         * Settings page tabs
         */
        public static function settings_page_tabs() {

            return array(
                'overview',
                'affiliates',
                'referrals',
                'visits',
                'creatives',
                'payouts',
                'reports',
                'integration',
                'modules',
                'notifications',
                'settings',
                'shortcodes',
                'help'
                    );
        }

        /**
         * Add a message.
         */
        public static function add_message($text) {
            self::$messages[] = $text;
        }

        /**
         * Add an error.
         */
        public static function add_error($text) {
            self::$errors[] = $text;
        }

        /**
         * Output messages + errors.
         */
        public static function show_messages() {
            if (count(self::$errors) > 0) {
                foreach (self::$errors as $error) {
                    echo '<div id="message" class="error inline"><p><strong><i class="fa fa-exclamation-triangle"></i>' . esc_html($error) . '</strong></p></div>';
                }
            } elseif (count(self::$messages) > 0) {
                foreach (self::$messages as $message) {
                    echo '<div id="message " class="updated inline ' . self::$plugin_slug . '_save_msg"><p><strong><i class="fa fa-check"></i>' . esc_html($message) . '</strong></p></div>';
                }
            }
        }

        /**
         * Handles the display of the settings page in admin.
         */
        public static function output() {
            global $current_section, $current_tab;

            do_action(self::$plugin_slug . '_settings_start');

            $tabs = fs_affiliates_get_allowed_setting_tabs();

            $color_mode = get_option('fs_affiliates_settings_color_mode', 1);

            /* Include admin html settings */
            include_once('views/html-settings.php');
        }

        /**
         * Handles the display of the settings page buttons in page.
         */
        public static function output_buttons($reset = true) {

            /* Include admin html settings buttons */
            include_once('views/html-settings-buttons.php');
        }

        /**
         * Output admin fields.
         */
        public static function output_fields($options) {
            if (isset($_GET['subsection']) && ($_GET['subsection'] == 'fs_edit_request'))
                return;

            foreach ($options as $value) {
                if (!isset($value['type'])) {
                    continue;
                }

                $value['id'] = isset($value['id']) ? $value['id'] : '';
                $value['css'] = isset($value['css']) ? $value['css'] : '';
                $value['desc'] = isset($value['desc']) ? $value['desc'] : '';
                $value['title'] = isset($value['title']) ? $value['title'] : '';
                $value['class'] = isset($value['class']) ? $value['class'] : '';
                $value['default'] = isset($value['default']) ? $value['default'] : '';
                $value['name'] = isset($value['name']) ? $value['name'] : $value['id'];
                $value['placeholder'] = isset($value['placeholder']) ? $value['placeholder'] : '';
                $value['custom_attributes'] = isset($value['custom_attributes']) ? $value['custom_attributes'] : '';

                // Custom attribute handling.
                $custom_attributes = self:: get_custom_attributes($value);

                // Description handling.
                $description = self::get_field_description($value);

                // Switch based on type.
                switch ($value['type']) {

                    // Section Titles.
                    case 'title':
                        if (!empty($value['title'])) {
                            echo '<h2>' . esc_html($value['title']) . '</h2>';
                        }
                        if (!empty($value['desc'])) {
                            echo wp_kses_post(wpautop(wptexturize($value['desc'])));
                        }
                        echo '<table class="form-table">' . "\n\n";
                        break;

                    // Section Ends.
                    case 'sectionend':
                        echo '</table>';
                        if (!empty($value['id'])) {
                            do_action(self::$plugin_slug . '_settings_' . sanitize_title($value['id']) . '_after');
                        }
                        break;

                    // Standard text inputs and subtypes like 'number'.
                    case 'text':
                    case 'password':
                    case 'datetime':
                    case 'date':
                    case 'month':
                    case 'time':
                    case 'week':
                    case 'number':
                    case 'email':
                    case 'url':
                    case 'tel':
                        $option_value = get_option($value['id'], $value['default']);
                        ?><tr valign="top">
                            <th scope="row">
                                <label for="<?php echo esc_attr($value['id']); ?>"><?php echo esc_html($value['title']); ?></label>
                            </th>
                            <td>
                                <input
                                    name="<?php echo esc_attr($value['name']); ?>"
                                    id="<?php echo esc_attr($value['id']); ?>"
                                    type="<?php echo esc_attr($value['type']); ?>"
                                    style="<?php echo esc_attr($value['css']); ?>"
                                    value="<?php echo esc_attr($option_value); ?>"
                                    class="<?php echo esc_attr($value['class']); ?>"
                                    placeholder="<?php echo esc_attr($value['placeholder']); ?>"
                                    <?php echo implode(' ', $custom_attributes); ?>
                                    /> <?php echo $description; ?>
                            </td>
                        </tr>
                        <?php
                        break;

                    case 'price':
                        $option_value = get_option($value['id'], $value['default']);
                        ?><tr valign="top">
                            <th scope="row">
                                <label for="<?php echo esc_attr($value['id']); ?>"><?php echo esc_html($value['title']); ?></label>
                            </th>
                            <td>
                                <input
                                    name="<?php echo esc_attr($value['name']); ?>"
                                    id="<?php echo esc_attr($value['id']); ?>"
                                    type="text"
                                    style="<?php echo esc_attr($value['css']); ?>"
                                    value="<?php echo esc_attr(fs_affiliates_format_decimal($option_value)); ?>"
                                    class="fs_affiliates_input_price <?php echo esc_attr($value['class']); ?>"
                                    placeholder="<?php echo esc_attr($value['placeholder']); ?>"
                                    <?php echo implode(' ', $custom_attributes); ?>
                                    /> <?php echo $description; ?>
                            </td>
                        </tr>
                        <?php
                        break;

                    //file upload
                    case 'file_upload':
                        $option_value = get_option($value['id'], $value['default']);
                        ?>
                        <tr valign="top">
                            <th scope="row">
                                <label for="<?php echo esc_attr($value['id']); ?>"><?php echo esc_html($value['title']); ?></label>
                            </th>
                            <td>
                                <?php set_transient($value['id'], array_filter((array) $option_value), 3600); ?> 
                                <div class='<?php echo esc_attr($value['class']); ?>'>
                                    <div class="fs_affiliates_display_file_names">
                                        <input type="hidden" class="fs_affiliates_uploaded_file_key" value="<?php echo $value['id']; ?>"/>
                                        <?php
                                        if (fs_affiliates_check_is_array($option_value)) {
                                            foreach ($option_value as $file_name => $file_url) {
                                                ?>
                                                <p class="fs_affiliates_uploaded_file_name">
                                                    <b>
                                                        <?php echo $file_name; ?>
                                                    </b> <span class="fs_affiliates_delete_uploaded_file" style="color:red;cursor:pointer;">&nbsp;[x]<input type="hidden" class="fs_affiliates_remove_file" value="<?php echo $file_name; ?>"/></span>
                                                </p>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </div>

                                    <input type="file" 
                                           name="<?php echo esc_attr($value['name']); ?>"
                                           id="<?php echo esc_attr($value['id']); ?>" 
                                           class="fs_affiliates_file_upload <?php echo esc_attr($value['class']); ?>"
                                           placeholder="<?php echo esc_attr($value['placeholder']); ?> 
                                           <?php echo implode(' ', $custom_attributes); ?>"/>
                                           <?php echo $description; ?>
                                </div>
                            </td>
                        </tr>
                        <?php
                        break;

                    //color Picker
                    case 'colorpicker':
                        $option_value = get_option($value['id'], $value['default']);
                        ?><tr valign="top">
                            <th scope="row">
                                <label for="<?php echo esc_attr($value['id']); ?>"><?php echo esc_html($value['title']); ?></label>
                            </th>
                            <td>
                                <input
                                    name="<?php echo esc_attr($value['name']); ?>"
                                    id="<?php echo esc_attr($value['id']); ?>"
                                    type="text"
                                    style="background: <?php echo $option_value; ?>;<?php echo esc_attr($value['css']); ?>"
                                    value="<?php echo esc_attr($option_value); ?>"
                                    class="fs_affilaites_colorpicker <?php echo esc_attr($value['class']); ?>"
                                    placeholder="<?php echo esc_attr($value['placeholder']); ?>"
                                    <?php echo implode(' ', $custom_attributes); ?>
                                    /> <?php echo $description; ?>
                            </td>
                        </tr>
                        <?php
                        break;

                    // Textarea.
                    case 'textarea':
                        $option_value = get_option($value['id'], $value['default']);
                        ?>
                        <tr valign="top">
                            <th scope="row">
                                <label for="<?php echo esc_attr($value['id']); ?>"><?php echo esc_html($value['title']); ?></label>
                            </th>
                            <td>
                                <textarea
                                    name="<?php echo esc_attr($value['name']); ?>"
                                    id="<?php echo esc_attr($value['id']); ?>"
                                    style="<?php echo esc_attr($value['css']); ?>"
                                    class="<?php echo esc_attr($value['class']); ?>"
                                    placeholder="<?php echo esc_attr($value['placeholder']); ?>"
                                    <?php echo implode(' ', $custom_attributes); ?>
                                    ><?php echo esc_textarea($option_value); ?></textarea>
                                    <?php echo $description; ?>
                            </td>
                        </tr>
                        <?php
                        break;

                    // Select boxes.
                    case 'select':
                    case 'multiselect':
                        $option_value = get_option($value['id'], $value['default']);
                        ?>
                        <tr valign="top">
                            <th scope="row">
                                <label for="<?php echo esc_attr($value['id']); ?>"><?php echo esc_html($value['title']); ?></label>
                            </th>
                            <td>
                                <select
                                    name="<?php echo esc_attr($value['name']); ?><?php echo ( 'multiselect' === $value['type'] ) ? '[]' : ''; ?>"
                                    id="<?php echo esc_attr($value['id']); ?>"
                                    style="<?php echo esc_attr($value['css']); ?>"
                                    class="<?php echo esc_attr($value['class']); ?>"
                                    <?php echo implode(' ', $custom_attributes); ?>
                                    <?php echo 'multiselect' === $value['type'] ? 'multiple="multiple"' : ''; ?>
                                    >
                                        <?php
                                        if (fs_affiliates_check_is_array($value['options'])) {
                                            foreach ($value['options'] as $key => $val) {
                                                ?>
                                            <option value="<?php echo esc_attr($key); ?>"
                                            <?php
                                            if (is_array($option_value)) {
                                                selected(in_array((string) $key, $option_value, true), true);
                                            } else {
                                                selected($option_value, (string) $key);
                                            }
                                            ?>
                                                    >
                                                <?php echo esc_html($val); ?></option>
                                            <?php
                                        }
                                    }
                                    ?>
                                </select> <?php echo $description; ?>
                            </td>
                        </tr>
                        <?php
                        break;

                    case 'ajaxsingleselect':
                        $option_value = get_option($value['id'], $value['default']);
                        ?>
                        <tr valign="top">
                            <th scope="row">
                                <label for="<?php echo esc_attr($value['id']); ?>"><?php echo esc_html($value['title']); ?></label>
                            </th>
                            <td>
                                <?php
                                $value['options'] = $option_value;
                                $value['multiple'] = false;
                                fs_affiliates_select2_html($value);
                                ?>
                            </select> <?php echo $description; ?>
                        </td>
                        </tr>
                        <?php
                        break;
                    case 'ajaxmultiselect':
                        $option_value = get_option($value['id'], $value['default']);
                        ?>
                        <tr valign="top">
                            <th scope="row">
                                <label for="<?php echo esc_attr($value['id']); ?>"><?php echo esc_html($value['title']); ?></label>
                            </th>
                            <td>
                                <?php
                                $value['options'] = $option_value;
                                fs_affiliates_select2_html($value);
                                ?>
                            </select> <?php echo $description; ?>
                        </td>
                        </tr>
                        <?php
                        break;
                    case 'wpeditor':
                        $option_value = get_option($value['id'], $value['default']);
                        ?>
                        <tr valign="top">
                            <th scope="row">
                                <label for="<?php echo esc_attr($value['id']); ?>"><?php echo esc_html($value['title']); ?></label>
                            </th>
                            <td>
                                <?php
                                wp_editor($option_value, $value['id'], array('media_buttons' => false, 'editor_class' => esc_attr($value['class'])));
                                ?>
                            </td>
                        </tr>
                        <?php
                        break;

                    // Radio inputs.
                    case 'radio':
                        $option_value = get_option($value['id'], $value['default']);
                        ?>
                        <tr valign="top">
                            <th scope="row">
                                <label for="<?php echo esc_attr($value['id']); ?>"><?php echo esc_html($value['title']); ?></label>
                            </th>
                            <td>
                                <fieldset>
                                    <ul>
                                        <?php
                                        foreach ($value['options'] as $key => $val) {
                                            ?>
                                            <li>
                                                <label><input
                                                        name="<?php echo esc_attr($value['name']); ?>"
                                                        value="<?php echo esc_attr($key); ?>"
                                                        type="radio"
                                                        style="<?php echo esc_attr($value['css']); ?>"
                                                        class="<?php echo esc_attr($value['class']); ?>"
                                                        <?php echo implode(' ', $custom_attributes); // WPCS: XSS ok.     ?>
                                                        <?php checked($key, $option_value); ?>
                                                        /> <?php echo esc_html($val); ?></label>
                                            </li>
                                            <?php
                                        }
                                        echo $description;
                                        ?>
                                    </ul>
                                </fieldset>
                            </td>
                        </tr>
                        <?php
                        break;

                    // Checkbox input.
                    case 'checkbox':
                        $option_value = get_option($value['id'], $value['default']);
                        ?>
                        <tr valign="top">
                            <th scope="row"><?php echo esc_html($value['title']); ?></th>
                            <td>
                                <fieldset>
                                    <label for="<?php echo esc_attr($value['id']); ?>">
                                        <input
                                            name="<?php echo esc_attr($value['name']); ?>"
                                            id="<?php echo esc_attr($value['id']); ?>"
                                            type="checkbox"
                                            class="<?php echo esc_attr(isset($value['class']) ? $value['class'] : '' ); ?>"
                                            value="1"
                                            <?php checked($option_value, 'yes'); ?>
                                            <?php echo implode(' ', $custom_attributes); ?>
                                            /> <?php echo strip_tags($description); ?>
                                    </label> 
                                </fieldset>
                            </td>
                        </tr>
                        <?php
                        break;
                    // Days/months/years selector.
                    case 'relative_date_selector':

                        $default_periods = array(
                            'days' => __('Day(s)', FS_AFFILIATES_LOCALE),
                            'weeks' => __('Week(s)', FS_AFFILIATES_LOCALE),
                            'months' => __('Month(s)', FS_AFFILIATES_LOCALE),
                            'years' => __('Year(s)', FS_AFFILIATES_LOCALE),
                                );

                        $periods = isset($value['periods']) ? $value['periods'] : $default_periods;
                        $option_value = get_option($value['id'], $value['default']);
                        ?>
                        <tr valign="top">
                            <th scope="row">
                                <label for="<?php echo esc_attr($value['id']); ?>"><?php echo esc_html($value['title']); ?></label>
                            </th>
                            <td>
                                <input
                                    name="<?php echo esc_attr($value['name']); ?>[number]"
                                    id="<?php echo esc_attr($value['id']); ?>"
                                    type="number"
                                    style= "width: 80px;"
                                    value="<?php echo esc_attr($option_value['number']); ?>"
                                    class="<?php echo esc_attr($value['class']); ?>"
                                    placeholder="<?php echo esc_attr($value['placeholder']); ?>"
                                    step="1"
                                    min="1"
                                    <?php echo implode(' ', $custom_attributes); // WPCS: XSS ok.     ?>
                                    />&nbsp;
                                <select name="<?php echo esc_attr($value['name']); ?>[unit]" style="width: auto;">
                                    <?php
                                    foreach ($periods as $value => $label) {
                                        echo '<option value="' . esc_attr($value) . '"' . selected($option_value['unit'], $value, false) . '>' . esc_html($label) . '</option>';
                                    }
                                    ?>
                                </select> <?php echo $description; ?>
                            </td>
                        </tr>
                        <?php
                        break;
                    // Default: run an action.
                    default:
                        do_action(self::$plugin_slug . '_admin_field_' . $value['type'], $value);
                        break;
                }
            }
        }

        /**
         * Get the Custom attributes.
         */
        public static function get_custom_attributes($value) {
            $custom_attributes = array();

            if (!empty($value['custom_attributes']) && is_array($value['custom_attributes'])) {
                foreach ($value['custom_attributes'] as $attribute => $attribute_value) {
                    $custom_attributes[] = esc_attr($attribute) . '="' . esc_attr($attribute_value) . '"';
                }
            }

            return $custom_attributes;
        }

        /**
         * Get the formatted description.
         */
        public static function get_field_description($value) {
            $description = '';

            if (isset($value['desc']) && !empty($value['desc'])) {
                $description = '<p style="margin-top:0">' . wp_kses_post($value['desc']) . '</p>';
            }

            return $description;
        }

        /**
         * Save admin fields.
         */
        public static function save_fields($options, $data = null) {
            if (is_null($data))
                $data = $_POST;

            if (empty($data))
                return false;

            if (!is_array($options))
                return false;

            // Loop options and get values to save.
            foreach ($options as $option) {
                if (!isset($option['id']) || !isset($option['type'])) {
                    continue;
                }

                if (in_array($option['type'], array('title', 'sectionend'))) {
                    continue;
                }

                $raw_value = isset($data[$option['id']]) ? wp_unslash($data[$option['id']]) : null;

                // Format the value based on option type.
                switch ($option['type']) {
                    case 'checkbox':
                        $value = '1' === $raw_value || 'yes' === $raw_value ? 'yes' : 'no';
                        break;
                    case 'price':
                        $value = fs_affiliates_format_decimal($raw_value, true);
                        break;
                    case 'textarea':
                        $value = wp_kses_post(trim($raw_value));
                        break;
                    case 'file_upload':
                        $value = get_transient($option['id']);

                        delete_transient($option['id']);
                        break;
                    case 'multiselect':
                        $value = array_filter((array) $raw_value);
                        break;
                    case 'ajaxmultiselect':
                        $value = array_filter((array) $raw_value);
                        break;
                    case 'select':
                        $allowed_values = empty($option['options']) ? array() : array_map('strval', array_keys($option['options']));
                        if (empty($option['default']) && empty($allowed_values)) {
                            $value = null;
                            break;
                        }
                        $default = ( empty($option['default']) ? $allowed_values[0] : $option['default'] );
                        $value = in_array($raw_value, $allowed_values, true) ? $raw_value : $default;
                        break;
                    default:
                        $value = $raw_value;
                        break;
                }

                if (is_null($value))
                    continue;

                //save the option value
                update_option($option['id'], $value);
            }

            return true;
        }

        /**
         * Reset admin fields.
         */
        public static function reset_fields($options) {
            if (!is_array($options))
                return false;

            // Loop options and get values to reset.
            foreach ($options as $option) {
                if (!isset($option['id']) || !isset($option['type']) || !isset($option['default'])) {
                    continue;
                }

                update_option($option['id'], $option['default']);
            }
            return true;
        }

    }

}

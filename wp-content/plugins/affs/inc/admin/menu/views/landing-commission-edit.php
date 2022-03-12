<?php
/* Edit Affiliates Page */

if ( !defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

$cookie_validity = $post_object->cookie_validity ;
?>
<div class="<?php echo $this->plugin_slug ; ?>_affiliates_new">
    <h2><?php _e( 'Edit Landing Commission' , FS_AFFILIATES_LOCALE ) ; ?></h2>
    <input type="hidden" name="landing_commission[id]" value='<?php echo $post_object->get_id() ; ?>'/>
    <table class="form-table">
        <tbody>
            <tr>
                <th scope='row'>
                    <label><?php _e( 'Commission Value' , FS_AFFILIATES_LOCALE ) ; ?></label>
                </th>
                <td>
                    <input type="text" name='landing_commission[commission_value]' class ='fs_affiliates_input_price' value="<?php echo $post_object->commission_value ; ?>"/>
                </td>
            </tr>
            <tr>
                <th scope='row'>
                    <label><?php _e( 'Landing Commission Status' , FS_AFFILIATES_LOCALE ) ; ?></label>
                </th>
                <td>
                    <select name='landing_commission[status]'>
                        <?php
                        $selected_status = array (
                            'fs_active'   => __( 'Active' , FS_AFFILIATES_LOCALE ) ,
                            'fs_inactive' => __( 'Inactive' , FS_AFFILIATES_LOCALE ) ,
                                ) ;
                        foreach ( $selected_status as $type => $name ) {
                            ?>
                            <option value="<?php echo $type ; ?>" <?php selected( $post_object->get_status() , $type ) ; ?>><?php echo $name ; ?></option>
                        <?php } ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th scope='row'>
                    <label><?php _e( 'Referral Status' , FS_AFFILIATES_LOCALE ) ; ?></label>
                </th>
                <td>
                    <select name='landing_commission[referral_status]'>
                        <?php
                        $referral_status = array (
                            'fs_pending' => __( 'Pending' , FS_AFFILIATES_LOCALE ) ,
                            'fs_unpaid'  => __( 'Unpaid' , FS_AFFILIATES_LOCALE ) ,
                                ) ;
                        foreach ( $referral_status as $type => $name ) {
                            ?>
                            <option value="<?php echo $type ; ?>" <?php selected( $post_object->referral_status , $type ) ; ?>><?php echo $name ; ?></option>
                        <?php } ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th scope='row'>
                    <label><?php _e( 'Cookie Validity' , FS_AFFILIATES_LOCALE ) ; ?></label>
                </th>
                <td>
                    <input type="number" min="0" name='landing_commission[cookie_validity][number]' value="<?php echo $cookie_validity[ 'number' ] ; ?>"/>
                    <select name='landing_commission[cookie_validity][unit]'>
                        <?php
                        $default_periods = array (
                            'days'   => __( 'Day(s)' , FS_AFFILIATES_LOCALE ) ,
                            'weeks'  => __( 'Week(s)' , FS_AFFILIATES_LOCALE ) ,
                            'months' => __( 'Month(s)' , FS_AFFILIATES_LOCALE ) ,
                            'years'  => __( 'Year(s)' , FS_AFFILIATES_LOCALE ) ,
                                ) ;
                        foreach ( $default_periods as $value => $label ) {
                            ?>
                            <option value="<?php echo $value ; ?>" <?php selected( $cookie_validity[ 'unit' ] , $value ) ; ?>><?php echo $label ; ?></option>
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
                            <option value="<?php echo esc_attr($value); ?>" <?php selected($post_object->usage_type, $value); ?>><?php echo esc_attr($label); ?></option>
                        <?php } ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th scope='row'>
                    <label><?php esc_html_e('Usage Count', FS_AFFILIATES_LOCALE); ?></label>
                </th>
                <td>
                    <input type="number" min="0" class='fs_affs_lc_validity_count' name='landing_commission[validity_count]' value="<?php echo esc_attr($post_object->validity_count); ?>"/>
                </td>
            </tr>
        </tbody>
    </table>
    <p class="submit">
        <input name='<?php echo $this->plugin_slug ; ?>_save' class='button-primary <?php echo $this->plugin_slug ; ?>_save_btn' type='submit' value="<?php _e( 'Update Landing Commission' , FS_AFFILIATES_LOCALE ) ; ?>" />
        <input type="hidden" name="edit_landing_commission" value="add-new"/>
        <?php
        wp_nonce_field( $this->plugin_slug . '_edit_landing_commission' , '_' . $this->plugin_slug . '_nonce' , false , true ) ;
        ?>
    </p>
</div>
<?php

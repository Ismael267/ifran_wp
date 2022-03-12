<?php
/* Edit Affiliates Creatives Page */

if ( !defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}
?>
<div class="<?php echo $this->plugin_slug ; ?>_form_fields_edit">
    <h2><?php _e( 'Edit Registration Form Field' , FS_AFFILIATES_LOCALE ) ; ?></h2>
    <table class="form-table">
        <tbody>
            <tr>
                <th scope='row'>
                    <label><?php _e( 'Label' , FS_AFFILIATES_LOCALE ) ; ?></label>
                </th>
                <td>
                    <input type="hidden" name='form_field[field_key]' value='<?php echo $field_key ; ?>'/>
                    <input type="text" name='form_field[field_name]' value='<?php echo $field_name ; ?>'/>
                </td>
            </tr>
            <tr>
                <th scope='row'>
                    <label><?php _e( 'Field Status' , FS_AFFILIATES_LOCALE ) ; ?></label>
                </th>
                <td>
                    <select name='form_field[field_status]' <?php echo $disabled_status ; ?>>
                        <?php
                        $status_options = array (
                            'enabled'  => __( 'Enabled' , FS_AFFILIATES_LOCALE ) ,
                            'disabled' => __( 'Disabled' , FS_AFFILIATES_LOCALE ) ,
                                ) ;
                        foreach ( $status_options as $status_type => $status_name ) {
                            ?>
                            <option value="<?php echo $status_type ; ?>" <?php selected( $field_status , $status_type ) ; ?>><?php echo $status_name ; ?></option>
                        <?php } ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th scope='row'>
                    <label><?php _e( 'Field Type' , FS_AFFILIATES_LOCALE ) ; ?></label>
                </th>
                <td>
                    <select name='form_field[field_required]' <?php echo $disabled_type ; ?>>
                        <?php
                        $type_options = array (
                            'optional'  => __( 'Optional' , FS_AFFILIATES_LOCALE ) ,
                            'mandatory' => __( 'Mandatory' , FS_AFFILIATES_LOCALE ) ,
                                ) ;
                        foreach ( $type_options as $type => $type_name ) {
                            ?>
                            <option value="<?php echo $type ; ?>" <?php selected( $field_required , $type ) ; ?>><?php echo $type_name ; ?></option>
                        <?php } ?>
                    </select>
                </td>
            </tr>
            <?php if ( !$hide_placeholder ) { ?>
                <tr>
                    <th scope='row'>
                        <label><?php _e( 'Placeholder Text' , FS_AFFILIATES_LOCALE ) ; ?></label>
                    </th>
                    <td>
                        <input type="text" name='form_field[field_placeholder]' value='<?php echo $field_placeholder ; ?>'/>
                    </td>
                </tr>
            <?php } ?>
            <tr>
                <th scope='row'>
                    <label><?php _e( 'Field Description' , FS_AFFILIATES_LOCALE ) ; ?></label>
                </th>
                <td>
                    <input type="text" name='form_field[field_description]' value='<?php echo $field_description ; ?>'/>
                </td>
            </tr>
        </tbody>
    </table>
    <p class="submit">
        <input class='button-primary <?php echo $this->plugin_slug ; ?>_save_btn' type='submit' value="<?php _e( 'Update Registration Form Field' , FS_AFFILIATES_LOCALE ) ; ?>" />
        <input type="hidden" name="edit_form_fields" value="edit"/>
        <?php
        wp_nonce_field( $this->plugin_slug . '_edit_form_fields' , '_' . $this->plugin_slug . '_nonce' , false , true ) ;
        ?>
    </p>
</div>

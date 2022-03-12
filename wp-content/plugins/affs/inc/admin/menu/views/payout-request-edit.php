<?php
/* Edit Affiliates Page */

if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}
?>
<div class="<?php echo $this->plugin_slug ; ?>_payout_request_edit">
    <h2><?php _e( 'Edit Payout Request' , FS_AFFILIATES_LOCALE ) ; ?></h2>
    <table class="form-table fs_affiliates_payout_request">
        <tbody>
            <tr>
                <th scope='row'>
                    <label><?php _e( 'Affiliate' , FS_AFFILIATES_LOCALE ) ; ?></label>
                </th>
                <td>
                    <input type="text" disabled="disabled" name='payout_request[user_name]' value='<?php echo $affiliates_object->user_name ; ?>'/>
                </td>
            </tr>
            <tr>
                <th scope='row'>
                    <label><?php _e( 'Total Unpaid Commission' , FS_AFFILIATES_LOCALE ) ; ?></label>
                </th>
                <td>
                    <input type="text" disabled="disabled" name='payout_request[unpaid_commission]' value='<?php echo get_post_meta( $affiliate_id , 'unpaid_earnings' , true ) ; ?>'/>
                </td>
            </tr>
            <tr>
                <th scope='row'>
                    <label><?php _e( 'Status' , FS_AFFILIATES_LOCALE ) ; ?></label>
                </th>
                <td>
                    <select name='payout_request[status]'>
                        <?php if ( get_post_status( $_GET[ 'id' ] ) != 'fs_progress' ) { ?>
                            <option value="fs_submitted" <?php if ( get_post_status( $_GET[ 'id' ] ) == 'fs_submitted' ) { ?>selected="selected"<?php } ?>><?php _e( 'Submitted' , FS_AFFILIATES_LOCALE ) ; ?></option>
                        <?php } ?>
                        <?php if ( get_post_status( $_GET[ 'id' ] ) != 'fs_closed' ) { ?>
                            <option value="fs_progress" <?php if ( get_post_status( $_GET[ 'id' ] ) == 'fs_progress' ) { ?>selected="selected"<?php } ?>><?php _e( 'In-Progress' , FS_AFFILIATES_LOCALE ) ; ?></option>
                        <?php } ?>
                        <option value="fs_closed" <?php if ( get_post_status( $_GET[ 'id' ] ) == 'fs_closed' ) { ?>selected="selected"<?php } ?>><?php _e( 'Closed' , FS_AFFILIATES_LOCALE ) ; ?></option>
                    </select>
                </td>
            </tr>
            <tr>
                <th scope='row'>
                    <label><?php _e( 'Requested Date' , FS_AFFILIATES_LOCALE ) ; ?></label>
                </th>
                <td>
                    <input type="text" disabled="disabled" value='<?php echo $payoutrequest->post_date ; ?>'/>
                </td>
            </tr>
            <tr>
                <th scope='row'>
                    <label><?php _e( 'Notes' , FS_AFFILIATES_LOCALE ) ; ?></label>
                </th>
                <td>
                    <textarea name='payout_request[notes]'><?php echo $payoutrequest->post_content ; ?></textarea>
                </td>
            </tr>
        </tbody>
    </table>
    <p class="submit">
        <input name='<?php echo $this->plugin_slug ; ?>_save' class='button-primary <?php echo $this->plugin_slug ; ?>_save_btn' type='submit' value="<?php _e( 'Update Payout Request' , FS_AFFILIATES_LOCALE ) ; ?>" />
        <input type="hidden" name="edit_payout_request" value="add-edit"/>
        <?php
        wp_nonce_field( $this->plugin_slug . '_edit_payout_request' , '_' . $this->plugin_slug . '_nonce' , false , true ) ;
        ?>
    </p>
</div>

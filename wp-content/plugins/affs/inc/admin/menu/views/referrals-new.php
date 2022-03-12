<?php
/* New Affiliates Referrals Page */

if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}
$affiliate_id        = isset( $_POST[ 'referral' ][ 'affiliate_id' ] ) ? $_POST[ 'referral' ][ 'affiliate_id' ] : array() ;
$type                = isset( $_POST[ 'referral' ][ 'type' ] ) ? $_POST[ 'referral' ][ 'type' ] : '' ;
$amount              = isset( $_POST[ 'referral' ][ 'amount' ] ) ? $_POST[ 'referral' ][ 'amount' ] : '' ;
$reference           = isset( $_POST[ 'referral' ][ 'reference' ] ) ? $_POST[ 'referral' ][ 'reference' ] : '' ;
$description         = isset( $_POST[ 'referral' ][ 'description' ] ) ? $_POST[ 'referral' ][ 'description' ] : '' ;
$status              = isset( $_POST[ 'referral' ][ 'status' ] ) ? $_POST[ 'referral' ][ 'status' ] : '' ;
?>
<div class="<?php echo $this->plugin_slug ; ?>_affiliates_new">
    <h2><?php _e( 'New Referrals' , FS_AFFILIATES_LOCALE ) ; ?></h2>
    <table class="form-table">
        <tbody>
            <tr>
                <th scope='row'>
                    <label><?php _e( 'Select Affiliate' , FS_AFFILIATES_LOCALE ) ; ?></label>
                </th>
                <td>
                    <?php
                    $user_selection_args = array(
                        'id'          => 'affiliate_id' ,
                        'name'        => 'referral[affiliate_id]' ,
                        'list_type'   => 'affiliates' ,
                        'action'      => 'fs_affiliates_search' ,
                        'placeholder' => __( 'Search a Affiliates' , FS_AFFILIATES_LOCALE ) ,
                        'multiple'    => false ,
                        'selected'    => true ,
                        'options'     => $affiliate_id
                            ) ;
                    fs_affiliates_select2_html( $user_selection_args ) ;
                    ?>
                </td>
            </tr>
            <tr>
                <th scope='row'>
                    <label><?php _e( 'Referral Type' , FS_AFFILIATES_LOCALE ) ; ?></label>
                </th>
                <td>
                    <select name='referral[type]'>
                        <?php
                        $referral_types      = array(
                            ''       => __( 'Select Referral Type' , FS_AFFILIATES_LOCALE ) ,
                            'sale'   => __( 'Sale' , FS_AFFILIATES_LOCALE ) ,
                            'opt-in' => __( 'Opt-In' , FS_AFFILIATES_LOCALE ) ,
                            'lead'   => __( 'Lead' , FS_AFFILIATES_LOCALE ) ,
                                ) ;
                        foreach ( $referral_types as $referral_type => $referral_type_name ) {
                            ?>
                            <option value="<?php echo $referral_type ; ?>" <?php selected( $type , $referral_type ) ?>><?php echo $referral_type_name ; ?></option>
                        <?php } ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th scope='row'>
                    <label><?php _e( 'Amount' , FS_AFFILIATES_LOCALE ) ; ?></label>
                </th>
                <td>
                    <input type="text" name='referral[amount]' class="fs_affiliates_input_price" value='<?php echo fs_affiliates_format_decimal( $amount ) ; ?>'/>
                </td>
            </tr>
            <tr>
                <th scope='row'>
                    <label><?php _e( 'Reference' , FS_AFFILIATES_LOCALE ) ; ?></label>
                </th>
                <td>
                    <input type="text" name='referral[reference]' value='<?php echo $reference ; ?>'/>
                </td>
            </tr>
            <tr>
                <th scope='row'>
                    <label><?php _e( 'Description' , FS_AFFILIATES_LOCALE ) ; ?></label>
                </th>
                <td>
                    <input type="text" name='referral[description]' value='<?php echo $description ; ?>'/>
                </td>
            </tr>
            <tr>
                <th scope='row'>
                    <label><?php _e( 'Referral Status' , FS_AFFILIATES_LOCALE ) ; ?></label>
                </th>
                <td>
                    <select class="fs_referral_status" name='referral[status]'>
                        <?php
                        $status_options = array(
                            'fs_pending'  => __( 'Pending' , FS_AFFILIATES_LOCALE ) ,
                            'fs_unpaid'   => __( 'Unpaid' , FS_AFFILIATES_LOCALE ) ,
                            'fs_paid'     => __( 'Paid' , FS_AFFILIATES_LOCALE ) ,
                            'fs_rejected' => __( 'Rejected' , FS_AFFILIATES_LOCALE ) ,
                                ) ;
                        foreach ( $status_options as $status_type => $status_name ) {
                            ?>
                            <option value="<?php echo $status_type ; ?>" <?php selected( $status , $status_type ) ?>><?php echo $status_name ; ?></option>
                        <?php } ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th scope='row'>
                    <label><?php esc_html_e( 'Reason' , FS_AFFILIATES_LOCALE ) ; ?></label>
                </th>
                <td>
                    <input type="text" name='referral[rejected_reason]' class='fs_referral_rejected_reason' />
                </td>
            </tr>
        </tbody>
    </table>
    <p class="submit">
        <input name='<?php echo $this->plugin_slug ; ?>_save' class='button-primary <?php echo $this->plugin_slug ; ?>_save_btn' type='submit' value="<?php _e( 'Register Referral' , FS_AFFILIATES_LOCALE ) ; ?>" />
        <input type="hidden" name="register_new_referrals" value="add-new"/>
        <?php
        wp_nonce_field( $this->plugin_slug . '_register_new_referrals' , '_' . $this->plugin_slug . '_nonce' , false , true ) ;
        ?>
    </p>
</div>

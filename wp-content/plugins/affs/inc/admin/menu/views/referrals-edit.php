<?php
/* Edit Affiliates Referrals Page */

if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

$disabled            = ($referral_object->get_status() == 'fs_paid') ? 'disabled="disabled"' : '' ;
?>
<div class="<?php echo $this->plugin_slug ; ?>_affiliates_edit">
    <h2><?php _e( 'Edit Referral' , FS_AFFILIATES_LOCALE ) ; ?></h2>
    <table class="form-table">
        <tbody>
            <tr>
                <th scope='row'>
                    <label><?php _e( 'Referral ID' , FS_AFFILIATES_LOCALE ) ; ?></label>
                </th>
                <td>
                    <input type="hidden" name='referral[id]' value='<?php echo $referral_object->get_id() ; ?>'/>
                    <input type="text" disabled="disabled" value='<?php echo $referral_object->get_id() ; ?>'/>
                </td>
            </tr>
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
                        'options'     => array( $referral_object->affiliate )
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
                            <option value="<?php echo $referral_type ; ?>" <?php selected( $referral_object->type , $referral_type ) ; ?>><?php echo $referral_type_name ; ?></option>
                        <?php } ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th scope='row'>
                    <label><?php _e( 'Amount' , FS_AFFILIATES_LOCALE ) ; ?></label>
                </th>
                <td>
                    <input type="text" name='referral[amount]' class="fs_affiliates_input_price" value='<?php echo fs_affiliates_format_decimal( $referral_object->amount ) ; ?>'/>
                </td>
            </tr>
            <tr>
                <th scope='row'>
                    <label><?php _e( 'Registration Date' , FS_AFFILIATES_LOCALE ) ; ?></label>
                </th>
                <td>
                    <input type="text" disabled="disabled" value='<?php echo fs_affiliates_local_datetime( $referral_object->date ) ; ?>'/>
                </td>
            </tr>
            <tr>
                <th scope='row'>
                    <label><?php _e( 'Reference' , FS_AFFILIATES_LOCALE ) ; ?></label>
                </th>
                <td>
                    <input type="text" name='referral[reference]' value='<?php echo $referral_object->reference ; ?>'/>
                </td>
            </tr>
            <tr>
                <th scope='row'>
                    <label><?php _e( 'Description' , FS_AFFILIATES_LOCALE ) ; ?></label>
                </th>
                <td>
                    <input type="text" name='referral[description]' value='<?php echo $referral_object->description ; ?>'/>
                </td>
            </tr>
            <tr>
                <th scope='row'>
                    <label><?php _e( 'Referral Status' , FS_AFFILIATES_LOCALE ) ; ?></label>
                </th>
                <td>
                    <select class="fs_referral_status" name='referral[status]' <?php echo $disabled ; ?>>
                        <?php
                        $status_options = array(
                            'fs_pending'  => __( 'Pending' , FS_AFFILIATES_LOCALE ) ,
                            'fs_unpaid'   => __( 'Unpaid' , FS_AFFILIATES_LOCALE ) ,
                            'fs_paid'     => __( 'Paid' , FS_AFFILIATES_LOCALE ) ,
                            'fs_rejected' => __( 'Rejected' , FS_AFFILIATES_LOCALE ) ,
                                ) ;

                        $payment_datas = get_post_meta( $referral_object->affiliate , 'fs_affiliates_user_payment_datas' , true ) ;
                        $disp_notice   = true ;

                        if ( isset( $payment_datas ) && ! empty( $payment_datas ) ) {
                            $pay_method   = isset( $payment_datas[ 'fs_affiliates_payment_method' ] ) ? $payment_datas[ 'fs_affiliates_payment_method' ] : '' ;
                            $pay_bank     = isset( $payment_datas[ 'fs_affiliates_bank_details' ] ) ? $payment_datas[ 'fs_affiliates_bank_details' ] : '' ;
                            $paypal_email = isset( $payment_datas[ 'fs_affiliates_paypal_email' ] ) ? $payment_datas[ 'fs_affiliates_paypal_email' ] : '' ;

                            if ( $pay_method == 'direct' && ! empty( $pay_bank ) || $pay_method == 'paypal' && ! empty( $paypal_email ) )
                                $disp_notice = false ;
                            
                            $disp_notice = ($disp_notice) ? in_array( $pay_method , array('reward_points' , 'wallet') ) ? false : $disp_notice : $disp_notice;
                        }
                        
                        
                        if ( $disp_notice && $referral_object->get_status() != 'fs_paid' )
                            unset( $status_options[ 'fs_paid' ] ) ;

                        foreach ( $status_options as $status_type => $status_name ) {
                            ?>
                            <option value="<?php echo $status_type ; ?>" <?php selected( $referral_object->get_status() , $status_type ) ; ?>><?php echo $status_name ; ?></option>
                        <?php } ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th scope='row'>
                    <label><?php esc_html_e( 'Reason' , FS_AFFILIATES_LOCALE ) ; ?></label>
                </th>
                <td>
                    <input type="text" name='referral[rejected_reason]' class='fs_referral_rejected_reason' value='<?php echo $referral_object->rejected_reason ; ?>'/>
                </td>
            </tr>
            <?php
            if ( $disp_notice && $referral_object->get_status() != 'fs_paid' ) { ?>
            <span class="fs_affiliates_msg_fails_post"><i class="fa fa-exclamation-triangle"></i><?php echo sprintf( __( 'Since the affiliate didn\'t select their payment method, "Paid" option is hidden in Affiliate Status' , FS_AFFILIATES_LOCALE ) ) ; ?></span>
            <?php } ?>
        </tbody>
    </table>
    <p class="submit">
        <input name='<?php echo $this->plugin_slug ; ?>_save' class='button-primary <?php echo $this->plugin_slug ; ?>_save_btn' type='submit' value="<?php _e( 'Update Referral' , FS_AFFILIATES_LOCALE ) ; ?>" />
        <input type="hidden" name="edit_referrals" value="add-edit"/>
<?php
wp_nonce_field( $this->plugin_slug . '_edit_referrals' , '_' . $this->plugin_slug . '_nonce' , false , true ) ;
?>
    </p>
</div>

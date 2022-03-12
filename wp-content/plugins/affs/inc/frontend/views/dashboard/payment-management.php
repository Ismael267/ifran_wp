<?php
/**
 * Profile - Payemnt Mangement
 */
if( ! defined( 'ABSPATH' ) )
    exit ; // Exit if accessed directly.


$payment_preference = fs_affiliates_paymethod_preference();
$payment_change_notice = get_post_meta($affilate_id, 'fs_affiliates_payment_notice_disp_type', true);

if(in_array($payment_change_notice, array('new' , 'exist'))){
    update_post_meta($affilate_id, 'fs_affiliates_payment_notice_disp_type', 'viewed');
}

//$payment_datas      = get_post_meta ( $affilate_id , 'fs_affiliates_user_payment_datas' , true ) ;
?><div class="fs_affiliates_form">
    <h2><?php _e( 'Payment Management' , FS_AFFILIATES_LOCALE ) ?></h2>
    <div id="fs_affiliates_pay_msg_wraper" style="display:none;"></div>
    <form method="post" class="fs_affiliates_form affiliate-form affiliate-form-register register">
        <p class="affiliate-form-row affiliate-form-row--wide form-row form-row-wide">
            <?php
            if( ! fs_affiliates_check_is_array( $payment_preference ) )
                return ;
            
            $read_only_select = ('2' != get_option('fs_affiliates_payment_method_selection_type', '1')) ? '' : 'disabled="disabled"';
            ?>
            <label for="fs_affiliates_payment_info"><?php esc_html_e( 'Payment Method' , FS_AFFILIATES_LOCALE ) ; ?>&nbsp;</label>
            <select name="fs_affiliates_payment_method" id="fs_affiliates_payment_method" <?php echo $read_only_select; ?>>
                <?php
                foreach( $payment_preference as $paykey => $status ) {

                    $payment_label = fs_affiliates_get_paymethod_preference( $paykey ) ;

                    if( ( $paykey == 'wallet' && ! fs_affiliates_is_wallet_eligible( $affilate_id ) ) || ( $status == 'disable' && '1' == get_option('fs_affiliates_payment_method_selection_type' , '1')) ) {
                        continue ;
                    }
                    
                    if( $pay_method != $paykey && '2' == get_option('fs_affiliates_payment_method_selection_type' , '1')) {
                        continue;
                    }
                    ?>
                    <option value="<?php echo $paykey ?>"  <?php if( $pay_method == $paykey ) { ?> selected="selected" <?php } ?> ><?php _e( $payment_label , FS_AFFILIATES_LOCALE ) ; ?></option>
                    <?php
                }
                ?>
            </select>
        </p>
        <p class="affiliate-form-row affiliate-form-row--wide form-row form-row-wide affiliate-paypal-pay affiliate-pay">
            <label for="fs_affiliates_paypal_email"><?php esc_html_e( 'Paypal Email' , FS_AFFILIATES_LOCALE ) ; ?>&nbsp;</label>
            <input type="email" value="<?php echo $paypal_email ; ?>" placeholder="<?php esc_html_e( 'Enter your PayPal Email ID' , FS_AFFILIATES_LOCALE ) ; ?>" class="affiliate-Input affiliate-Input--text input-text" name="fs_affiliates_paypal_email" id="fs_affiliates_paypal_email"  />
        </p>
        <p class="affiliate-form-row affiliate-form-row--wide form-row form-row-wide affiliate-direct-pay affiliate-pay">
            <label for="fs_affiliates_bank_details"><?php esc_html_e( 'Bank Details' , FS_AFFILIATES_LOCALE ) ; ?>&nbsp;</label>
            <input type="textarea" value="<?php echo $pay_bank ; ?>" placeholder="<?php esc_html_e( 'Enter your Bank Details' , FS_AFFILIATES_LOCALE ) ; ?>"  class="affiliate-Input affiliate-Input--text input-text" name="fs_affiliates_bank_details" id="fs_affiliates_bank_details"  />
        </p>
        <?php if('1' == get_option('fs_affiliates_payment_method_selection_type', '1') || ('2' == get_option('fs_affiliates_payment_method_selection_type', '1') && !in_array($pay_method , array('wallet' , 'reward_points')) ) ){ ?>
        <p class="affiliate-FormRow form-row affiliate-paypal-pay">
            <input type="hidden" id="fs_affiliates_current_id" value="<?php echo $affilate_id ; ?>" >
            <button type="submit" id="fs_affiliates_form_save" class="fs_affiliates_form_save affiliate-Button button" name="fs_affiliates_payment" value="fs_affiliates_update_payment"><?php esc_html_e( 'Save Changes' , FS_AFFILIATES_LOCALE ) ; ?></button>
        </p>
        <?php } ?>

    </form>
</div>
<?php

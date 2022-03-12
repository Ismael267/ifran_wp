<?php
/**
 * Profile - Account Management
 */
if ( ! defined ( 'ABSPATH' ) )
    exit ; // Exit if accessed directly.
$affiliate_data    = new FS_Affiliates_Data ( $AffiliateId ) ;
$validation_fields = get_option('fs_affiliates_payout_statements_validation_fields');

?><div class="fs_affiliates_form">
    <h2><?php _e ( 'Billing Address' , FS_AFFILIATES_LOCALE ) ?></h2>
    <form method="post" class="fs_affiliates_form affiliate-form affiliate-form-register register">
        <?php
        foreach ( $payout_fields as $each_id => $each_label ) {
            $required = '' ;
            if ( in_array ( $each_id , $validation_fields ) ) {
                $required = '<abbr style="color:red;">*</abbr>' ;
            }
            ?>
            <p class="affiliate-form-row affiliate-form-row--wide form-row form-row-wide">
                <label for="<?php echo $each_id ; ?>"><?php esc_html_e ( $each_label , FS_AFFILIATES_LOCALE ) ; ?>&nbsp; <?php echo $required ; ?></label>
                <input type="text" class="affiliate-Input affiliate-Input--text input-text" value="<?php echo $affiliate_data->$each_id ; ?>" name="<?php echo $each_id ; ?>" id="<?php echo $each_id ; ?>"  />
            </p>
        <?php } ?>
        <p class="affiliate-FormRow form-row">
            <?php wp_nonce_field ( 'affiliate-payout-particulars' , 'affiliate-payout-particulars-nonce' ) ; ?>
            <button type="submit" class="fs_affiliates_form_save affiliate-Button button" name="aff_set_payout_particulars" value="aff_update_password"><?php esc_html_e ( 'Save Changes' , FS_AFFILIATES_LOCALE ) ; ?></button>
        </p>
    </form>
</div>
<?php

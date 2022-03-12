<?php
/**
 * Lost Password Form
 */
if ( !defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}
FS_Affiliates_Form_Handler::show_messages() ;
?>
<form method="POST">
    <div class='fs_affiliates_reset_password fs_affiliates_log_form'>
        <div class='fs-affiliates-form-row'>
            <input type="hidden" name="fs_affiliates_reset_key" value="<?php echo esc_attr( $reset_pass_key ) ; ?>" />
            <input type="hidden" name="fs_affiliates_reset_login" value="<?php echo esc_attr( $reset_pass_login ) ; ?>" />
            <span class='fs_affiliates_lostpwd_info'><?php _e( 'Enter a new password' , FS_AFFILIATES_LOCALE ) ; ?></span>
        </div>
        <div class='fs-affiliates-form-row'>
            <label><?php _e( 'New password' , FS_AFFILIATES_LOCALE ) ; ?></label>
            <input type="password" name="fs_affiliates_password" value="" placeholder="<?php esc_html_e( 'New password' , FS_AFFILIATES_LOCALE ) ; ?>" />
            <span class="border"></span>
        </div>
        <div class='fs-affiliates-form-row'>
            <label><?php _e( 'Confirm password' , FS_AFFILIATES_LOCALE ) ; ?></label>
            <input type="password" name="fs_affiliates_confirm_password" value="" placeholder="<?php esc_html_e( 'Confirm password' , FS_AFFILIATES_LOCALE ) ; ?>" />
            <span class="border"></span>
        </div>
        <div class='fs-affiliates-form-row'>
            <input type="hidden" name="fs-affiliates-reset-password-nonce" value="<?php echo wp_create_nonce( 'fs-affiliates-reset-password' ) ; ?>" />
            <input type='submit' class='fs_affiliates_reset_password_button fs-affiliates-button' value="<?php _e( 'Submit' , FS_AFFILIATES_LOCALE ) ; ?>"/>
        </div>
    </div>
</form>
<?php

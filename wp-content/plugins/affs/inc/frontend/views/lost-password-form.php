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
    <div class='fs_affiliates_lost_password fs_affiliates_log_form'>
        <div class='fs-affiliates-form-row'>
            <span class='fs_affiliates_lostpwd_info'><?php _e( 'Lost your password? Please enter your username or email address. You will receive a link to create a new password via email.' , FS_AFFILIATES_LOCALE ) ; ?></span>
        </div>
        <div class='fs-affiliates-form-row'>
            <label><?php _e( 'Username/Email' , FS_AFFILIATES_LOCALE ) ; ?></label>
            <input type="text" name="fs_affiliates_email" value="" class='fs_affiliates_user_email_id' placeholder="<?php esc_html_e( 'Username/Email' , FS_AFFILIATES_LOCALE ) ; ?>" />
            <span class="border"></span>
        </div>
        <div class='fs-affiliates-form-row'>
            <input type="hidden" name="fs-affiliates-lost-password-nonce" value="<?php echo wp_create_nonce( 'fs-affiliates-lost-password' ) ; ?>" />
            <input type='submit' class='fs_affiliates_lost_password_button fs-affiliates-button ' value="<?php _e( 'Submit' , FS_AFFILIATES_LOCALE ) ; ?>"/>
        </div> 
    </div>
</form>
<?php

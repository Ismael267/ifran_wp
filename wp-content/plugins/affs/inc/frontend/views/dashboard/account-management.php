<?php
/**
 * Profile - Account Management
 */
if ( !defined( 'ABSPATH' ) )
    exit ; // Exit if accessed directly.
?><div class="fs_affiliates_form">
    <h2><?php _e( 'Account Management' , FS_AFFILIATES_LOCALE ) ?></h2>
    <form method="post" class="fs_affiliates_form affiliate-form affiliate-form-register register">
        <p class="affiliate-form-row affiliate-form-row--wide form-row form-row-wide">
            <label for="old_password"><?php esc_html_e( 'Old Password' , FS_AFFILIATES_LOCALE ) ; ?>&nbsp;</label>
            <input type="password" class="affiliate-Input affiliate-Input--text input-text" name="aff_old_password" id="aff_old_password"  />
        </p> 
        <p class="affiliate-form-row affiliate-form-row--wide form-row form-row-wide">
            <label for="new_password"><?php esc_html_e( 'New Password' , FS_AFFILIATES_LOCALE ) ; ?>&nbsp;</label>
            <input type="password" class="affiliate-Input affiliate-Input--text input-text" name="aff_new_password" id="aff_new_password"  />
        </p>
        <p class="affiliate-form-row affiliate-form-row--wide form-row form-row-wide">
            <label for="confirm_password"><?php esc_html_e( 'Repeat Password' , FS_AFFILIATES_LOCALE ) ; ?>&nbsp;</label>
            <input type="password" class="affiliate-Input affiliate-Input--text input-text" name="aff_repeat_password" id="aff_repeat_password"  />
        </p>
        <p class="affiliate-FormRow form-row">
            <?php wp_nonce_field( 'affiliate-password' , 'affiliate-password-nonce' ) ; ?>
            <button type="submit" class="fs_affiliates_form_save affiliate-Button button" name="aff_set_pswd" value="aff_update_password"><?php esc_html_e( 'Save Changes' , FS_AFFILIATES_LOCALE ) ; ?></button>
        </p>
    </form>
</div>
<?php

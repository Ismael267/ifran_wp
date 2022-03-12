<?php
/**
 * Login Template
 */
if ( !defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

do_action( 'affwp_login_fields_before' ) ;
?>
<div class="fs_affiliates_log_form">
    <div class="fs_affiliates_login_form_header">
        <h3><?php esc_html_e( 'Login Form' , FS_AFFILIATES_LOCALE ) ; ?></h3>
    </div>
    <div class="fs-affiliates-form-row">
        <label for="user_name"><?php esc_html_e( 'Username/Email address' , FS_AFFILIATES_LOCALE ) ; ?>&nbsp;<span class="required">*</span></label>
        <input type="text" class="required" name="user_name" value="" placeholder="<?php esc_html_e( 'Username/Email address' , FS_AFFILIATES_LOCALE ) ; ?>"/>
        <span class="border"></span>
    </div>
    <div class="fs-affiliates-form-row">
        <label for="password"><?php esc_html_e( 'Password' , FS_AFFILIATES_LOCALE ) ; ?>&nbsp;<span class="required">*</span></label>
        <input class="required" type="password" name="password" value="" placeholder="<?php esc_html_e( 'Password' , FS_AFFILIATES_LOCALE ) ; ?>"/>
        <span class="border"></span>
    </div>
    <?php
    $gcaptcha_site_key      = get_option( 'fs_affiliates_recaptcha_site_key' ) ;
    $google_captcha_enabled = get_option( 'fs_affiliates_recaptcha_login_page' ) == 'yes' ;
    if ( $gcaptcha_site_key != '' && $google_captcha_enabled ) {
        wp_enqueue_script( 'fs-affiliates-recaptcha' ) ;
        ?>
        <div class="fs-affiliates-form-row">
            <div class="g-recaptcha" data-sitekey="<?php echo $gcaptcha_site_key ; ?>"></div>&nbsp;
        </div>
    <?php } ?>
    <div class="fs-affiliates-form-row">
        <div class="fs_login_form_foot_top">
            <input name="rememberme" type="checkbox" value="1" /> 
            <span><?php esc_html_e( 'Remember me' , FS_AFFILIATES_LOCALE ) ; ?></span>
            <a hre="#"><?php esc_html_e( 'Forgot Password' , FS_AFFILIATES_LOCALE ) ; ?></a>
        </div>
        <div class="fs_login_form_foot_bottom">
            <input type="button" class="fs-affiliates-button button" name="login" value="<?php esc_attr_e( 'Log in' , FS_AFFILIATES_LOCALE ) ; ?>" />
            <a hre="#"><?php esc_html_e( 'Signup' , FS_AFFILIATES_LOCALE ) ; ?></a>
        </div>
    </div>
</div>    
<?php
do_action( 'affwp_login_fields_after' ) ;

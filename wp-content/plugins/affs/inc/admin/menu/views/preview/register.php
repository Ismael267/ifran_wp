<?php
/**
 * Register Template
 */
if ( !defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}
?>
<div class="fs_affiliates_register_form">
    <div class="fs_affiliates_login_form_header">
        <h3><?php esc_html_e( 'Registration Form' , FS_AFFILIATES_LOCALE ) ; ?></h3>
    </div>
    <?php
    do_action( 'fs_affiliates_before_register_fields' ) ;

    if ( $account_type == 'user_decide' ):
        ?>
        <p class="fs-affiliates-form-row">
            <label for="user_selection_type"><?php echo __( 'Account Creation Preference' , FS_AFFILIATES_LOCALE ) ; ?></label>
            <select name='affiliate[user_selection_type]' class="user_selection_type">
                <option value='same'><?php echo __( 'Same Account' , FS_AFFILIATES_LOCALE ) ; ?></option>
                <option value='new'><?php echo __( 'New Account' , FS_AFFILIATES_LOCALE ) ; ?></option>
            </select>
        </p>
        <?php
    endif ;

    foreach ( $fields as $field ):
        extract( $field ) ;

        if ( $field_status != 'enabled' )
            continue ;

        switch ( $field_key ) {
            case 'first_name':
                if ( $account_type != 'existing_account' ):
                    ?>
                    <p class="fs-affiliates-form-row">
                        <label for="<?php echo $field_key ; ?>"><?php echo $field_name ; ?>
                            <?php if ( $field_required == 'mandatory' ) { ?> <span class="required">*</span><?php } ?>
                        </label>
                        <input type="text" class="fs_affiliates_separate_account" name="affiliate[first_name]" placeholder="<?php echo $field_placeholder ; ?>" />
                        <?php if ( !empty( $field_description ) ) { ?><span style="width:100%;float: left"><?php echo $field_description ; ?></span><?php } ?>
                    </p>

                    <?php
                endif ;
                break ;
            case 'last_name':
                if ( $account_type != 'existing_account' ):
                    ?>
                    <p class="fs-affiliates-form-row">
                        <label for="<?php echo $field_key ; ?>"><?php echo $field_name ; ?>
                            <?php if ( $field_required == 'mandatory' ) { ?> <span class="required">*</span><?php } ?>
                        </label>
                        <input type="text" class="fs_affiliates_separate_account" name="affiliate[last_name]" placeholder="<?php echo $field_placeholder ; ?>" />
                        <?php if ( !empty( $field_description ) ) { ?><span style="width:100%;float: left"><?php echo $field_description ; ?></span><?php } ?>
                    </p>

                    <?php
                endif ;
                break ;
            case 'user_name':
                if ( $account_type != 'existing_account' ):
                    ?>
                    <p class="fs-affiliates-form-row">
                        <label for="<?php echo $field_key ; ?>"><?php echo $field_name ; ?>
                            <?php if ( $field_required == 'mandatory' ) { ?> <span class="required">*</span><?php } ?>
                        </label>
                        <input type="text" class="fs_affiliates_separate_account" name="affiliate[user_name]" placeholder="<?php echo $field_placeholder ; ?>" />
                        <?php if ( !empty( $field_description ) ) { ?><span style="width:100%;float: left"><?php echo $field_description ; ?></span><?php } ?>
                    </p>

                    <?php
                endif ;
                break ;
            case 'email':
                if ( $account_type != 'existing_account' ):
                    ?>
                    <p class="fs-affiliates-form-row">
                        <label for="<?php echo $field_key ; ?>"><?php echo $field_name ; ?>
                            <?php if ( $field_required == 'mandatory' ) { ?> <span class="required">*</span><?php } ?>
                        </label>
                        <input type="email" class="fs_affiliates_separate_account" name="affiliate[email]" placeholder="<?php echo $field_placeholder ; ?>"/>
                        <?php if ( !empty( $field_description ) ) { ?><span style="width:100%;float: left"><?php echo $field_description ; ?></span><?php } ?>
                    </p>

                    <?php
                endif ;
                break ;
            case 'phonenumber':
                ?>
                <p class="fs-affiliates-form-row">
                    <label for="<?php echo $field_key ; ?>"><?php echo $field_name ; ?>
                        <?php if ( $field_required == 'mandatory' ) { ?> <span class="required">*</span><?php } ?>
                    </label>
                    <input type="text" class="fs_affiliates_separate_account" name="affiliate[phone_number]" placeholder="<?php echo $field_placeholder ; ?>"/>
                    <?php if ( !empty( $field_description ) ) { ?><span style="width:100%;float: left"><?php echo $field_description ; ?></span><?php } ?>
                </p>

                <?php
                break ;
            case 'payment_email':
                ?>
                <p class="fs-affiliates-form-row">
                    <label for="<?php echo $field_key ; ?>"><?php echo $field_name ; ?>
                        <?php if ( $field_required == 'mandatory' ) { ?> <span class="required">*</span><?php } ?>
                    </label>
                    <input type="text" name="affiliate[payment_email]" placeholder="<?php echo $field_placeholder ; ?>"/>
                    <?php if ( !empty( $field_description ) ) { ?><span style="width:100%;float: left"><?php echo $field_description ; ?></span><?php } ?>
                </p>

                <?php
                break ;
            case 'country':
                if ( $account_type != 'existing_account' ):
                    ?>
                    <p class="fs-affiliates-form-row">
                        <label for="<?php echo $field_key ; ?>"><?php echo $field_name ; ?>
                            <?php if ( $field_required == 'mandatory' ) { ?> <span class="required">*</span><?php } ?>
                        </label>
                        <select name="affiliate[country]" class="fs_affiliates_separate_account fs_affilaites_select2">
                            <?php
                            $countries = get_fs_affiliates_countries() ;
                            if ( fs_affiliates_check_is_array( $countries ) ) {
                                foreach ( $countries as $country_code => $country_name ) {
                                    ?> <option value="<?php echo $country_code ; ?>"><?php echo $country_name ; ?></option><?php
                                }
                            }
                            ?>
                        </select>
                        <?php if ( !empty( $field_description ) ) { ?><span style="width:100%;float: left"><?php echo $field_description ; ?></span><?php } ?>
                    </p>
                    <?php
                endif ;
                break ;
            case 'website':
                ?>
                <p class="fs-affiliates-form-row">
                    <label for="<?php echo $field_key ; ?>"><?php echo $field_name ; ?>
                        <?php if ( $field_required == 'mandatory' ) { ?> <span class="required">*</span><?php } ?>
                    </label>
                    <input type="url" name="affiliate[website]" placeholder="<?php echo $field_placeholder ; ?>" />
                    <?php if ( !empty( $field_description ) ) { ?><span style="width:100%;float: left"><?php echo $field_description ; ?></span><?php } ?>
                </p>
                <?php
                break ;
            case 'promotion':
                ?>
                <p class="fs-affiliates-form-row">
                    <label for="<?php echo $field_key ; ?>"><?php echo $field_name ; ?>
                        <?php if ( $field_required == 'mandatory' ) { ?> <span class="required">*</span><?php } ?>
                    </label>
                    <textarea name="affiliate[promotion]" cols="30" rows="5" placeholder="<?php echo $field_placeholder ; ?>"></textarea>
                    <?php if ( !empty( $field_description ) ) { ?><span style="width:100%;float: left"><?php echo $field_description ; ?></span><?php } ?>
                </p>

                <?php
                break ;
            case 'password':
                if ( $account_type != 'existing_account' ):
                    ?>
                    <p class="fs-affiliates-form-row">
                        <label for="<?php echo $field_key ; ?>"><?php echo $field_name ; ?>
                            <?php if ( $field_required == 'mandatory' ) { ?> <span class="required">*</span><?php } ?>
                        </label>
                        <input class="fs_affiliates_separate_account" type="password" name="affiliate[password]" placeholder="<?php echo $field_placeholder ; ?>"/>
                        <?php if ( !empty( $field_description ) ) { ?><span style="width:100%;float: left"><?php echo $field_description ; ?></span><?php } ?>
                    </p>

                    <?php
                endif ;
                break ;
            case 'repeated_password':
                if ( $account_type != 'existing_account' ):
                    ?>
                    <p class="fs-affiliates-form-row">
                        <label for="<?php echo $field_key ; ?>"><?php echo $field_name ; ?>
                            <?php if ( $field_required == 'mandatory' ) { ?> <span class="required">*</span><?php } ?>
                        </label>
                        <input class="fs_affiliates_separate_account" type="password" name="affiliate[repeated_password]" placeholder="<?php echo $field_placeholder ; ?>"/>
                        <?php if ( !empty( $field_description ) ) { ?><span style="width:100%;float: left"><?php echo $field_description ; ?></span><?php } ?>
                    </p>
                    <?php
                endif ;
                break ;
            case 'file_upload':
                ?>
                <p class="fs-affiliates-form-row">
                    <label for="<?php echo $field_key ; ?>"><?php echo $field_name ; ?>
                        <?php if ( $field_required == 'mandatory' ) { ?> <span class="required">*</span><?php } ?>
                    </label>
                </p>
                <div class="fs_affiliates_file_uploader">
                    <div class="fs_affiliates_display_file_names">
                        <input type="hidden" name="affiliate[uploaded_key]" class="fs_affiliates_uploaded_file_key" value="fs_affiliates_file_upload_<?php echo time() ; ?>"/>                            
                    </div>

                    <input type="hidden" name="affiliate[file_upload]" value=""/>
                    <input type="file" 
                           name="fs_affiliates_file_upload_<?php echo time() ; ?>"
                           class="fs_affiliates_file_upload"/>
                </div>
                <?php
                break ;
        }
    endforeach ;

    do_action( 'fs_affiliates_after_register_fields' ) ;
    ?>
    <p class="fs-affiliates-form-row">
        <span class="required">*</span>&nbsp;<input name="iagree" type="checkbox" value="1" />&nbsp;
        <span><?php esc_html_e( 'I agree to the Terms of Service' , FS_AFFILIATES_LOCALE ) ; ?></span>
    </p>
    <?php
    $gcaptcha_site_key      = get_option( 'fs_affiliates_recaptcha_site_key' ) ;
    $google_captcha_enabled = get_option( 'fs_affiliates_recaptcha_registration_page' ) == 'yes' ;
    if ( $gcaptcha_site_key != '' && $google_captcha_enabled ) {
        wp_enqueue_script( 'fs-affiliates-recaptcha' ) ;
        ?>
        <div class="fs-affiliates-form-row">
            <div class="g-recaptcha" data-sitekey="<?php echo $gcaptcha_site_key ; ?>"></div>&nbsp;
        </div>
    <?php } ?>
    <p class="fs-affiliates-form-row">
        <input type="hidden" name="fs-affiliates-register-nonce" value="<?php echo wp_create_nonce( 'fs-affiliates-register' ) ; ?>" />
        <input type="hidden" name="fs-affiliates-action" value="register" />
        <input type="button" class="fs-affiliates-button button" name="register" value="<?php esc_attr_e( 'Register' , FS_AFFILIATES_LOCALE ) ; ?>" />        
    </p>
</div>
<?php



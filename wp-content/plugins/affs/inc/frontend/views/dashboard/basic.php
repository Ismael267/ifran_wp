<?php
/**
 * Profile - Basic Details
 */
if ( ! defined ( 'ABSPATH' ) )
    exit ; // Exit if accessed directly.

$selected_fields = fs_affiliates_get_form_fields () ;

?><div class="fs_affiliates_form">
    <h2><?php _e ( 'Basic Details' , FS_AFFILIATES_LOCALE ) ?></h2>
    <form method="post" class="fs_affiliates_form affiliate-form affiliate-form-register register">
        <?php  if ( fs_affiliates_get_form_fields_status ('first_name') ) { ?>
        <p class="affiliate-form-row affiliate-form-row--wide form-row form-row-wide">
            <label for="aff_firstname"><?php esc_html_e ( 'First Name' , FS_AFFILIATES_LOCALE ) ; ?>&nbsp;<span class="required">*</span></label>
            <input type="text" class="affiliate-Input affiliate-Input--text input-text" name="aff_firstname" id="aff_firstname"  value="<?php echo $AffiliateObj->first_name ; ?>" />
        </p>
        <?php } 
        if ( fs_affiliates_get_form_fields_status ('last_name') ) { ?>
        <p class="affiliate-form-row affiliate-form-row--wide form-row form-row-wide">
            <label for="aff_lastname"><?php esc_html_e ( 'Last Name' , FS_AFFILIATES_LOCALE ) ; ?>&nbsp;<span class="required">*</span></label>
            <input type="text" class="affiliate-Input affiliate-Input--text input-text" name="aff_lastname" id="aff_lastname"  value="<?php echo $AffiliateObj->last_name ; ?>" />
        </p>
        <?php } 
         if ( fs_affiliates_get_form_fields_status ('email') ) { ?>
        <p class="affiliate-form-row affiliate-form-row--wide form-row form-row-wide">
            <label for="aff_email"><?php esc_html_e ( 'Email' , FS_AFFILIATES_LOCALE ) ; ?>&nbsp;<span class="required">*</span></label>
            <input type="email" class="affiliate-Input affiliate-Input--text input-text" name="aff_email" id="aff_email"  value="<?php echo $AffiliateObj->email ; ?>" />
        </p>
         <?php } 
         if ( fs_affiliates_get_form_fields_status ('phonenumber') ) { ?>
        <p class="affiliate-form-row affiliate-form-row--wide form-row form-row-wide">
            <label for="aff_email"><?php esc_html_e ( 'Phone Number' , FS_AFFILIATES_LOCALE ) ; ?>
                <input type="text" class="affiliate-Input affiliate-Input--text input-text" name="aff_phonenumber" id="aff_phonenumber"  value="<?php echo $AffiliateObj->phone_number ; ?>" />
        </p>
         <?php } ?>
        <?php if ( apply_filters ( 'fs_affiliate_check_if_slug_modification_enabled' , false , $AffiliateId ) ) { ?>
            <p class="affiliate-form-row affiliate-form-row--wide form-row form-row-wide">
                <label class="fs_affiliates_label" for="aff_change_slug"><?php esc_html_e ( 'Modify Affiliate Slug' , FS_AFFILIATES_LOCALE ) ; ?>&nbsp;</label>
                <input type="checkbox" <?php if ( $AffiliateObj->modify_slug == 'yes' ) { ?>checked="checked"<?php } ?>class="affiliate-Input fs_affiliates_checkbox" name="aff_change_slug" id="aff_change_slug"  value="yes" />
            </p>
            <p class="affiliate-form-row affiliate-form-row--wide form-row form-row-wide">
                <label for="aff_new_slug"><?php esc_html_e ( 'New Affiliate Slug' , FS_AFFILIATES_LOCALE ) ; ?>&nbsp;</label>
                <input type="text" class="affiliate-Input affiliate-Input--text input-text" name="aff_new_slug" id="aff_new_slug"  value="<?php echo $AffiliateObj->slug ; ?>" />
            </p>
        <?php
        }
        if ( fs_affiliates_get_form_fields_status ('file_upload') ) {
            ?>
            <p class="affiliate-form-row affiliate-form-row--wide form-row form-row-wide">
                <label for="fs_affiliates_file_upload"><?php esc_html_e ( 'Documents' , FS_AFFILIATES_LOCALE ) ; ?>
            </p>
            <table class="fs_affiliates_fileupload_frontend_table">
                <tbody>
                    <tr>
                        <th><?php _e ( 'File Name' , FS_AFFILIATES_LOCALE ) ; ?></th>
                        <th><?php _e ( 'Download' , FS_AFFILIATES_LOCALE ) ; ?></th>
                        <th><?php _e ( 'Delete' , FS_AFFILIATES_LOCALE ) ; ?></th>
                    </tr>

                    <?php
                    // Prepare email address hash.
                    $email_hash = function_exists ( 'hash' ) ? hash ( 'sha256' , $AffiliateObj->email ) : sha1 ( $AffiliateObj->email ) ;
                    $url        = add_query_arg ( array ( 'email' => $AffiliateObj->email , 'fs_nonce' => $email_hash ) , site_url () ) ;
                    set_transient ( 'fs_affiliates_file_upload_' . $AffiliateId , array_filter ( ( array ) $AffiliateObj->uploaded_files ) , 3600 ) ;
                    if ( fs_affiliates_check_is_array ( $AffiliateObj->uploaded_files ) ) {
                        foreach ( $AffiliateObj->uploaded_files as $file_name => $file_url ) {
                            ?> <tr>
                                <td data-title="<?php esc_html_e( 'File Name' , FS_AFFILIATES_LOCALE ) ?>" ><b><?php echo $file_name ; ?> </b></td>
                                <td data-title="<?php esc_html_e( 'Download' , FS_AFFILIATES_LOCALE ) ?>" style="text-align:center">
                                    <input type="hidden" class="fs_affiliates_download_file" value="<?php echo $file_name ; ?>"/>
                                    <a class="fs_affiliates_download_btn"href="<?php echo add_query_arg ( array ( 'download_file' => $file_name ) , $url ) ; ?>"><?php _e ( 'Download' , FS_AFFILIATES_LOCALE ) ; ?><i class="fa fa-download" aria-hidden="true"></i>
                                    </a>
                                </td>
                                <td data-title="<?php esc_html_e( 'Delete' , FS_AFFILIATES_LOCALE ) ?>" style="text-align:center">
                                    <span class="fs_affiliates_delete_table_uploaded_file" style="color:red;cursor:pointer;">[x]
                                        <input type="hidden" class="fs_affiliates_uploaded_file_key" value="fs_affiliates_file_upload_<?php echo $AffiliateId ; ?>"/>
                                        <input type="hidden" class="fs_affiliates_remove_file" value="<?php echo $file_name ; ?>"/>
                                    </span>
                                </td>
                            </tr><?php
                        }
                    }
                    ?>
                </tbody>
            </table>
            <div class="fs_affiliates_file_uploader">
                <div class="fs_affiliates_display_file_names">
                    <input type="hidden" class="fs_affiliates_uploaded_file_key" value="fs_affiliates_file_upload_<?php echo $AffiliateId ; ?>"/>                            
                </div>

                <input type="file" 
                       name="fs_affiliates_file_upload_<?php echo $AffiliateId ; ?>"
                       class="fs_affiliates_file_upload"/>
            </div>
            <?php } ?>
        <p class="affiliate-FormRow form-row">
<?php wp_nonce_field ( 'affiliate-update' , 'affiliate-update-nonce' ) ; ?>
            <button type="submit" class="fs_affiliates_form_save affiliate-Button button" name="aff_update" value="aff_update_details"><?php esc_html_e ( 'Save Changes' , FS_AFFILIATES_LOCALE ) ; ?></button>
        </p>
    </form>
</div>
<?php

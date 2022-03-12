<?php
/**
 * Login Template
 */
if ( ! defined ( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}
$iagree = isset ( $_POST[ 'iagree' ] ) ? true : false ;
?>
<div class="fs_affiliates_register_form">
    <div class="fs_affiliates_login_form_header">
        <h3><?php esc_html_e ( 'Subscribe Form' , FS_AFFILIATES_LOCALE ) ; ?></h3>
    </div>
    <?php
    do_action ( 'fs_affiliates_opt_in_fields_before' ) ;

    foreach ( $fields as $field ):
        extract ( $field ) ;

        $field_value = isset ( $_POST[ 'optinform' ][ $field_key ] ) ? $_POST[ 'optinform' ][ $field_key ] : '' ;
        if ( $field_status != 'enabled' )
            continue ;

        switch ( $field_key ) {

            case 'first_name':
                ?>
                <p class="fs-affiliates-form-row">
                    <label for="<?php echo $field_key ; ?>"><?php echo $field_name ; ?>
                        <?php if ( $field_required == 'mandatory' ) { ?> <span class="required">*</span><?php } ?>
                    </label>
                    <input type="text" class="fs_affiliates_separate_account" name="optinform[first_name]" placeholder="<?php echo $field_placeholder ; ?>" value="<?php echo $field_value ; ?>"/>
                    <?php if ( ! empty ( $field_description ) ) { ?><span style="width:100%;float: left"><?php echo $field_description ; ?></span><?php } ?>
                </p>

                <?php
                break ;
            case 'last_name':
                ?>
                <p class="fs-affiliates-form-row">
                    <label for="<?php echo $field_key ; ?>"><?php echo $field_name ; ?>
                        <?php if ( $field_required == 'mandatory' ) { ?> <span class="required">*</span><?php } ?>
                    </label>
                    <input type="text" class="fs_affiliates_separate_account" name="optinform[last_name]" placeholder="<?php echo $field_placeholder ; ?>" value="<?php echo $field_value ; ?>"/>
                    <?php if ( ! empty ( $field_description ) ) { ?><span style="width:100%;float: left"><?php echo $field_description ; ?></span><?php } ?>
                </p>

                <?php
                break ;
            case 'email':
                ?>
                <p class="fs-affiliates-form-row">
                    <label for="<?php echo $field_key ; ?>"><?php echo $field_name ; ?>
                        <?php if ( $field_required == 'mandatory' ) { ?> <span class="required">*</span><?php } ?>
                    </label>
                    <input type="text" class="fs_affiliates_separate_account" name="optinform[email]" placeholder="<?php echo $field_placeholder ; ?>" value="<?php echo $field_value ; ?>"/>
                    <?php if ( ! empty ( $field_description ) ) { ?><span style="width:100%;float: left"><?php echo $field_description ; ?></span><?php } ?>
                </p>

                <?php
                break ;
        }
    endforeach ;
    ?>

    <p class="fs-affiliates-form-row">
        <input type="hidden" name="fs-affiliates-opt-in-nonce" value="<?php echo wp_create_nonce ( 'fs-affiliates-opt-in' ) ; ?>" />
        <input type="hidden" name="fs-affiliates-action" value="register" />
        <input type="hidden" name="optinform[user_id]" value="<?php echo get_current_user_id () ; ?>" />
        <input type="submit" class="fs-affiliates-button button" name="register" value="<?php esc_attr_e ( 'Subscribe' , FS_AFFILIATES_LOCALE ) ; ?>" />        
    </p>
    <?php
    do_action ( 'fs_affiliates_opt_in_fields_after' ) ;
    ?>
</div>
<?php



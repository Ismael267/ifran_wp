<?php
/* Layout */

if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}
$modules           = FS_Affiliates::instance()->modules() ;
do_action( 'fs_affiliates_before_modules_title' ) ;
$woocommerce       = FS_Affiliates_Integration_Instances::get_integration_by_id( 'woocommerce' ) ;
$module_color_mode = get_option( 'fs_affiliates_module_settings_color_mode' , 1 ) ;

if ( ! fs_affiliates_check_if_woocommerce_is_active() || ! $woocommerce->is_enabled() ) {
    ?>
    <div class="fs_affiliates_module_notice">
        <p><i class="fa fa-exclamation-triangle"></i> <?php _e( 'Note: Please turn on WooCommerce Integration under Integration Tab to access the inactive modules.' , FS_AFFILIATES_LOCALE ) ; ?> </p>
    </div>
<?php } ?>
<h2 class="fs_affiliates_modules_title"><?php _e( 'SUMO Affiliates Pro Modules' , FS_AFFILIATES_LOCALE ) ; ?>
    <div class="fs_affiliates_modules_btn">
        <div class="fs_affiliates_modules_btn_content">
            <div class="fs_affiliates_modules_btn_switch_one">
                <input class="fs_affiliates_module_settings_color_mode" type="radio" name="dark_btn" value="1" id="three" <?php checked( $module_color_mode , '1' ) ; ?> >
                <label for="three"><?php _e( 'Native Color' , FS_AFFILIATES_LOCALE ) ; ?></label>
            </div>
            <div class="fs_affiliates_modules_btn_switch_two">
                <input class="fs_affiliates_module_settings_color_mode" type="radio" name="dark_btn" value="2" id="four" <?php checked( $module_color_mode , '2' ) ; ?>>
                <label for="four"><?php _e( 'Multi Color' , FS_AFFILIATES_LOCALE ) ; ?></label>
            </div>
        </div>
    </div>
</h2>
<?php
foreach ( $modules as $module ) {

    $module_grid_class = ($module->is_enabled()) ? 'fs_affiliates_' . $module->get_id() . '_active' : 'fs_affiliates_' . $module->get_id() . '_inactive' ;
    ?>
    <div class="fs_affiliates_modules_grid">
        <input class="fs_affiliates_module_name" type="hidden" value="<?php echo $module->get_id() ; ?>" />
        <div class="fs_affiliates_modules_grid_inner <?php echo $module_grid_class ; ?>">
            <div class="fs_affiliates_modules_grid_inner_top">
                <h3><?php esc_html_e( $module->get_title() ) ; ?></h3>
            </div>
            <div class="fs_affiliates_modules_grid_img">
                <?php if ( $module->get_image_url() ) { ?>
                    <img src="<?php echo $module->get_image_url() ; ?>"/>
                <?php } ?>
            </div>
            <div class="fs_affiliates_modules_grid_inner_bottom">
                <label class="fs_affiliates_switch_round">
                    <input class="fs_affiliates_modules_enabled" type="checkbox" value="true" <?php checked( $module->is_enabled() , true ) ?>>
                    <div class="fs_affiliates_slider_round"></div>
                </label>
                <?php
                if ( $module->settings_link() ) {
                    $display_style = ( ! $module->is_enabled()) ? 'style="display:none"' : '' ;
                    ?>
                    <a class="fs_affiliates_settings_link" <?php echo $display_style ; ?> href="<?php echo $module->settings_link() ; ?>"><?php _e( 'Settings' , FS_AFFILIATES_LOCALE ) ; ?></a>
                <?php } ?>
            </div>
            <?php if ( ! $module->is_plugin_enabled() ) { ?>
                <div class="mask"></div>
            <?php } ?>
        </div>
    </div>
    <?php
}

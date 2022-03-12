<?php
/* Layout */

if ( !defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}
$integrations = FS_Affiliates::instance()->integrations() ;
?>
<h2 class="fs_affiliates_integrations_title"><?php _e( 'SUMO Affiliates Pro Integration' , FS_AFFILIATES_LOCALE ) ; ?></h2>
<?php
foreach ( $integrations as $integration ) {
    $integration_grid_class = ($integration->is_enabled()) ? 'fs_affiliates_integration_active' : 'fs_affiliates_integration_inactive' ;
    ?>
    <div class="fs_affiliates_integrations_grid">
        <input class="fs_affiliates_integration_name" type="hidden" value="<?php echo $integration->get_id() ; ?>" />
        <div class="fs_affiliates_integrations_grid_inner <?php echo $integration_grid_class ; ?>">
            <div class="fs_affiliates_integrations_grid_inner_top">
                <h3><?php esc_html_e( $integration->get_title() ) ; ?></h3>
            </div>
            <div class="fs_affiliates_integrations_grid_img">
                <?php if ( $integration->get_image_url() ) { ?>
                    <img src="<?php echo $integration->get_image_url() ; ?>"/>
                <?php } ?>
            </div>
            <div class="fs_affiliates_integrations_grid_inner_bottom">
                <label class="fs_affiliates_switch_round">
                    <input class="fs_affiliates_integrations_enabled" type="checkbox" value="true" <?php checked( $integration->is_enabled() , true ) ?>>
                    <div class="fs_affiliates_slider_round"></div>
                </label>
                <?php
                if ( $integration->settings_link() ) {
                    $display_style = (!$integration->is_enabled()) ? 'style="display:none"' : '' ;
                    ?>
                    <a class="fs_affiliates_settings_link" <?php echo $display_style ; ?> href="<?php echo $integration->settings_link() ; ?>"><?php _e( 'Settings' , FS_AFFILIATES_LOCALE ) ; ?></a>
                <?php } ?>
            </div>
        </div>
        <?php if ( !$integration->is_plugin_enabled() ) { ?>
            <div class="mask"></div>
        <?php } ?>
    </div>
    <?php
}

<?php
/* Layout */

if ( !defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}
$notifications = FS_Affiliates::instance()->notifications() ;
?>
<h2 class="fs_affiliates_notifications_title"><?php _e( 'SUMO Affiliates Pro Notification' , FS_AFFILIATES_LOCALE ) ; ?></h2>
<?php
foreach ( $notifications as $notification ) {
    if ( !$notification->get_id() )
        continue ;

    $notification_grid_class = ($notification->is_enabled()) ? 'fs_affiliates_notification_active' : 'fs_affiliates_notification_inactive' ;
    ?>
    <div class="fs_affiliates_notifications_grid">
        <input class="fs_affiliates_notification_name" type="hidden" value="<?php echo $notification->get_id() ; ?>" />
        <div class="fs_affiliates_notifications_grid_inner <?php echo $notification_grid_class ; ?>">
            <div class="fs_affiliates_notifications_grid_inner_top">
                <h3><?php esc_html_e( $notification->get_title() ) ; ?></h3>
            </div>
            <div class="fs_affiliates_notifications_grid_inner_bottom">
                <label class="fs_affiliates_switch_round">
                    <input class="fs_affiliates_notifications_enabled" type="checkbox" value="true" <?php checked( $notification->is_enabled() , true ) ?>>
                    <div class="fs_affiliates_slider_round"></div>
                </label>
                <?php
                if ( $notification->settings_link() ) {
                    $display_style = (!$notification->is_enabled()) ? 'style="display:none"' : '' ;
                    ?>
                    <a class="fs_affiliates_settings_link" <?php echo $display_style ; ?> href="<?php echo $notification->settings_link() ; ?>"><?php _e( 'Settings' , FS_AFFILIATES_LOCALE ) ; ?></a>
                <?php } ?>
            </div>
        </div>
    </div>
    <?php
}

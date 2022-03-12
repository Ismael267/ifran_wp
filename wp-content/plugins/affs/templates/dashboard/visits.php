<?php
/**
 * This template is used for display dashboard visits.
 *
 * This template can be overridden by copying it to yourtheme/affs/dashboard/visits.php
 *
 * To maintain compatibility, affiliates pro for Woocommerce will update the template files and you have to copy the updated files to your theme
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

do_action('fs_affiliates_before_dashboard_visits_table');
?>
<div class="fs_affiliates_form">
    <h2><?php esc_html_e('Visits', FS_AFFILIATES_LOCALE) ?></h2>
    <table class="fs_affiliates_visits_frontend_table fs_affiliates_frontend_table">
        <tbody>
            <tr>
                <th class="fs_affiliates_sno fs_affiliates_visits_sno"><?php esc_html_e('S.No', FS_AFFILIATES_LOCALE) ?></th>
                <th><?php esc_html_e('URL', FS_AFFILIATES_LOCALE) ?></th>
                <th><?php esc_html_e('Status', FS_AFFILIATES_LOCALE) ?></th>
                <th><?php esc_html_e('Date', FS_AFFILIATES_LOCALE) ?></th>
            </tr>
            <?php
            $args = array('post_type' => 'fs-visits',
                'author' => $affiliate_id,
                'offset' => $offset,
                'numberposts' => $per_page,
                'fields' => 'ids',
                'post_status' => array('fs_notconverted', 'fs_converted')
            );
            $post = get_posts($args);
            $sno = $offset + 1;

            if (fs_affiliates_check_is_array($post)) {
                foreach ($post as $visit_id) {
                    $url = get_post_meta($visit_id, 'landing_page', true);
                    $date = get_post_meta($visit_id, 'date', true);
                    ?><tr>
                        <td data-title="<?php esc_html_e('S.No', FS_AFFILIATES_LOCALE) ?>" class="fs_affiliates_sno fs_affiliates_visits_sno"><?php echo $sno; ?></td>
                        <td data-title="<?php esc_html_e('URL', FS_AFFILIATES_LOCALE) ?>" ><?php echo $url; ?></td>
                        <td data-title="<?php esc_html_e('Status', FS_AFFILIATES_LOCALE) ?>" ><?php echo fs_affiliates_get_status_display(get_post_status($visit_id)); ?></td>
                        <td data-title="<?php esc_html_e('Date', FS_AFFILIATES_LOCALE) ?>" ><?php echo fs_affiliates_local_datetime($date); ?></td>
                    </tr><?php
                    $sno ++;
                }
            } else {
                ?>
                <tr>
                    <td colspan="4"><?php esc_html_e('No Records found', FS_AFFILIATES_LOCALE); ?></td>
                <tr>
                    <?php
                }
                ?>
        </tbody>
        <tfoot>
            <tr style="clear:both;">
                <td colspan="6" class="footable-visible">
                    <div class="pagination pagination-centered">
                        <?php
                        if ($page_count > 1) {
                            FS_Affiliates_Dashboard::fs_affiliates_set_pagination($current_page, $page_count);
                        }
                        ?>
                    </div>
                </td>
            </tr>
        </tfoot>
    </table>
</div>
<?php
do_action('fs_affiliates_after_dashboard_visits_table');

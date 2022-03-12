<?php
/**
 * This template is used for display dashboard payouts.
 *
 * This template can be overridden by copying it to yourtheme/affs/dashboard/payouts.php
 *
 * To maintain compatibility, affiliates pro for Woocommerce will update the template files and you have to copy the updated files to your theme
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

do_action('fs_affiliates_before_dashboard_payouts_table');
?>
<div class="fs_affiliates_form">
    <h2><?php esc_html_e('Payouts', FS_AFFILIATES_LOCALE) ?></h2>
    <table class="fs_affiliates_Payout_frontend_table fs_affiliates_frontend_table">
        <tbody>
            <tr>
                <th class="fs_affiliates_sno fs_affiliates_Payout_sno"><?php esc_html_e('S.No', FS_AFFILIATES_LOCALE) ?></th>
                <th><?php esc_html_e('Payout ID', FS_AFFILIATES_LOCALE) ?></th>
                <th><?php esc_html_e('Payment Mode', FS_AFFILIATES_LOCALE) ?></th>
                <th><?php esc_html_e('Paid Amount', FS_AFFILIATES_LOCALE) ?></th>
                <th><?php esc_html_e('Status', FS_AFFILIATES_LOCALE) ?></th>
                <th><?php esc_html_e('Date', FS_AFFILIATES_LOCALE) ?></th>
                <?php if (apply_filters('fs_affiliates_is_payout_statements_available', false)) { ?>
                    <th><?php esc_html_e('Payout Statements', FS_AFFILIATES_LOCALE) ?></th>
                <?php } ?>
            </tr>
            <?php
            $args = array('post_type' => 'fs-payouts',
                'author' => $affiliate_id,
                'offset' => $offset,
                'numberposts' => $per_page,
                'fields' => 'ids',
                'post_status' => array('fs_paid')
            );
            $post = get_posts($args);
            $sno = $offset + 1;
            if (fs_affiliates_check_is_array($post)) {
                foreach ($post as $payout_id) {
                    $payout_obj = new FS_Affiliates_Payouts($payout_id);
                    $preparre_dwnld_url = '<a href="' . esc_url_raw(add_query_arg(array('section' => 'payout_statements', 'payout_statement_id' => $payout_id), get_permalink())) . '">' . __('Download', FS_AFFILIATES_LOCALE) . '</a>';
                    ?><tr>
                        <td data-title="<?php esc_html_e('S.No', FS_AFFILIATES_LOCALE) ?>" class="fs_affiliates_sno fs_affiliates_Payout_sno"><?php echo $sno; ?></td>
                        <td data-title="<?php esc_html_e('Payout ID', FS_AFFILIATES_LOCALE) ?>" ><?php echo $payout_id; ?></td>
                        <td data-title="<?php esc_html_e('Payment Mode', FS_AFFILIATES_LOCALE) ?>" ><?php echo fs_affiliates_display_payment_method($payout_obj->payment_mode); ?></td>
                        <td data-title="<?php esc_html_e('Paid Amount', FS_AFFILIATES_LOCALE) ?>" ><?php echo fs_affiliates_price($payout_obj->paid_amount); ?></td>
                        <td data-title="<?php esc_html_e('Status', FS_AFFILIATES_LOCALE) ?>" ><?php echo fs_affiliates_get_status_display($payout_obj->get_status()); ?></td>
                        <td data-title="<?php esc_html_e('Date', FS_AFFILIATES_LOCALE) ?>" ><?php echo fs_affiliates_local_datetime($payout_obj->date); ?></td>
                        <?php
                        if (apply_filters('fs_affiliates_is_payout_statements_available', false)) {
                            $footable_colspan = 7;
                            ?>
                            <td data-title="<?php esc_html_e('Payout Statements', FS_AFFILIATES_LOCALE) ?>" style="text-align:center">
                                <?php
                                echo apply_filters('fs_affiliates_is_pay_slip_exists', false, $payout_id) ? $preparre_dwnld_url : '-'
                                ?>
                            </td>
                        <?php } ?>

                    </tr><?php
                    $sno ++;
                }
            } else {
                ?>
                <tr>
                    <td colspan="<?php echo $footable_colspan; ?>"><?php esc_html_e('No Records found', FS_AFFILIATES_LOCALE); ?></td>
                <tr>
                    <?php
                }
                ?>
        </tbody>
        <tfoot>
            <tr style="clear:both;">
                <td colspan="<?php echo $footable_colspan; ?>" class="footable-visible">
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
do_action('fs_affiliates_after_dashboard_payouts_table');

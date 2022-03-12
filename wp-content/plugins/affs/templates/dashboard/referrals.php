<?php
/**
 * This template is used for display dashboard referrals.
 *
 * This template can be overridden by copying it to yourtheme/affs/dashboard/referrals.php
 *
 * To maintain compatibility, affiliates pro for Woocommerce will update the template files and you have to copy the updated files to your theme
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

do_action('fs_affiliates_before_dashboard_referrals_table');
?>
<div class="fs_affiliates_form">
    <h2><?php _e('Referrals', FS_AFFILIATES_LOCALE) ?></h2>
    <?php if (!empty($unpaid_amount) && apply_filters('fs_affiliates_payout_request_enable', false)) { ?>
        <button style="margin-bottom: 15px !important;margin-left: 10px !important;" class="fs_request_unpaid_commission fs_affiliates_form_save" data-affiliateid="<?php echo $affiliate_id; ?>"><?php _e('Request Unpaid Commission', FS_AFFILIATES_LOCALE); ?></button>
    <?php } ?>
    <table class="fs_affiliates_referrals_frontend_table fs_affiliates_frontend_table">
        <tbody>
            <tr>
                <th class="fs_affiliates_sno fs_affiliates_referrals_sno"><?php esc_html_e('S.No', FS_AFFILIATES_LOCALE) ?></th>
                <th><?php esc_html_e('Reference', FS_AFFILIATES_LOCALE) ?></th>
                <th><?php esc_html_e('Description', FS_AFFILIATES_LOCALE) ?></th>
                <th><?php esc_html_e('Amount', FS_AFFILIATES_LOCALE) ?></th>
                <th><?php esc_html_e('Status', FS_AFFILIATES_LOCALE) ?></th>
                <th><?php esc_html_e('Date', FS_AFFILIATES_LOCALE) ?></th>
            </tr>
            <?php
            $args = array('post_type' => 'fs-referrals',
                'offset' => $offset,
                'numberposts' => $per_page,
                'fields' => 'ids',
                'post_status' => array('fs_paid', 'fs_unpaid', 'fs_rejected'),
                'author' => $affiliate_id,
            );

            $post = get_posts($args);

            $sno = $offset + 1;
            if (fs_affiliates_check_is_array($post)) {
                foreach ($post as $referral_id) {
                    $reference_id = get_post_meta($referral_id, 'reference', true);
                    $referral_name = '#' . $reference_id;

                    if (get_post_type($reference_id) == 'shop_order') {
                        $referral_name = apply_filters('fs_affiliates_order_link', $referral_name, $reference_id, $affiliate_id);
                    }

                    $description = get_post_meta($referral_id, 'description', true);
                    $amount = get_post_meta($referral_id, 'amount', true);
                    $status = get_post_status($referral_id);
                    $timestamp = get_post_meta($referral_id, 'date', true);
                    $reject_reason = get_post_meta($referral_id, 'rejected_reason', true);
                    $reason = '';

                    if ('fs_rejected' == $status && !empty($reject_reason)) {
                        $reason .= '</br>' . sprintf(esc_html__('Reason : %s', FS_AFFILIATES_LOCALE), $reject_reason);
                    }
                    ?>
                    <tr>
                        <td data-title ="<?php esc_html_e('S.No', FS_AFFILIATES_LOCALE) ?>" class="fs_affiliates_sno fs_affiliates_referrals_sno"><?php echo $sno; ?></td>
                        <td data-title ="<?php esc_html_e('Reference', FS_AFFILIATES_LOCALE) ?>" ><?php echo $referral_name; ?></td>
                        <td data-title ="<?php esc_html_e('Description', FS_AFFILIATES_LOCALE) ?>" ><?php echo $description; ?></td>
                        <td data-title ="<?php esc_html_e('Amount', FS_AFFILIATES_LOCALE) ?>" ><?php echo fs_affiliates_price($amount); ?></td>
                        <td data-title ="<?php esc_html_e('Status', FS_AFFILIATES_LOCALE) ?>" ><?php echo fs_affiliates_get_status_display($status) . $reason; ?></td>
                        <td data-title ="<?php esc_html_e('Date', FS_AFFILIATES_LOCALE) ?>" ><?php echo fs_affiliates_local_datetime($timestamp); ?></td>
                    </tr>
                    <?php
                    $sno ++;
                }
            } else {
                    ?>
                    <tr>
                        <td colspan="6"><?php esc_html_e('No Records found' , FS_AFFILIATES_LOCALE); ?></td>
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
do_action('fs_affiliates_after_dashboard_referrals_table');

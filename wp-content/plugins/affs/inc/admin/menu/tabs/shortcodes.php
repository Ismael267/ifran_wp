<?php
/**
 * Shortcodes Tab
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( class_exists( 'FS_Affiliates_Shortcodes_Tab' ) ) {
    return new FS_Affiliates_Shortcodes_Tab() ;
}

/**
 * FS_Affiliates_Shortcodes_Tab.
 */
class FS_Affiliates_Shortcodes_Tab extends FS_Affiliates_Settings_Page {

    /**
     * Constructor.
     */
    public function __construct() {
        $this->id    = 'shortcodes' ;
        $this->label = __( 'Shortcodes' , FS_AFFILIATES_LOCALE ) ;

        add_action( $this->plugin_slug . '_admin_field_output_shortcodes' , array( $this , 'output_shortcodes' ) ) ;

        parent::__construct() ;
    }

    /**
     * Get settings array.
     */
    public function get_settings( $current_section = '' ) {
        return array(
            array( 'type' => 'output_shortcodes' )
                ) ;
    }

    /**
     * Output the settings buttons.
     */
    public function output_buttons() {
        
    }

    /**
     * Output the affiliates shortcodes table
     */
    public function output_shortcodes() {
        $shortcodes_info = array(
            "[fs_affiliates_login]"                      => array( "where" => __( "Pages" , FS_AFFILIATES_LOCALE ) ,
                "usage" => __( "Displays the Affiliate Login Form" , FS_AFFILIATES_LOCALE )
            ) ,
            "[fs_affiliates_dashboard]"                  => array( "where" => __( "Pages" , FS_AFFILIATES_LOCALE ) ,
                "usage" => __( "Displays the Affiliate Dashboard" , FS_AFFILIATES_LOCALE )
            ) ,
            "[fs_affiliates_register]"                   => array( "where" => __( "Pages" , FS_AFFILIATES_LOCALE ) ,
                "usage" => __( "Displays the Affiliate  Registration Form" , FS_AFFILIATES_LOCALE )
            ) ,
            "[fs_affiliates_lost_password]"              => array( "where" => __( "Pages" , FS_AFFILIATES_LOCALE ) ,
                "usage" => __( "Displays the Affiliate Lost Password Form" , FS_AFFILIATES_LOCALE )
            ) ,
            "[fs_affiliates_mlm_tree]"                   => array( "where" => __( "Pages" , FS_AFFILIATES_LOCALE ) ,
                "usage" => __( "Displays the MLM Tree of the Affiliate" , FS_AFFILIATES_LOCALE )
            ) ,
            "[fs_affiliate_basic_settings]"              => array( "where" => __( "Pages" , FS_AFFILIATES_LOCALE ) ,
                "usage" => __( "Displays the Basic Profile Section of the Affiliate Dashboard" , FS_AFFILIATES_LOCALE )
            ) ,
            "[fs_affiliate_account_management_settings]" => array( "where" => __( "Pages" , FS_AFFILIATES_LOCALE ) ,
                "usage" => __( "Displays the Account Management Section of the Affiliate Dashboard" , FS_AFFILIATES_LOCALE )
            ) ,
            "[fs_affiliate_payment_management_settings]" => array( "where" => __( "Pages" , FS_AFFILIATES_LOCALE ) ,
                "usage" => __( "Displays the Payment Management Section of the Affiliate Dashboard" , FS_AFFILIATES_LOCALE )
            ) ,
            "[fs_affiliate_creatives]"                   => array( "where" => __( "Pages" , FS_AFFILIATES_LOCALE ) ,
                "usage" => __( "Displays the Creatives Section of the Affiliate Dashboard" , FS_AFFILIATES_LOCALE )
            ) ,
            "[fs_affiliate_referrals]"                   => array( "where" => __( "Pages" , FS_AFFILIATES_LOCALE ) ,
                "usage" => __( "Displays the Referrals Section of the Affiliate Dashboard" , FS_AFFILIATES_LOCALE )
            ) ,
            "[fs_affiliate_overview]"                    => array( "where" => __( "Pages" , FS_AFFILIATES_LOCALE ) ,
                "usage" => __( "Displays the Overview section of the Affiliate Dashboard" , FS_AFFILIATES_LOCALE )
            ) ,
            "[fs_affiliate_link_generator]"              => array( "where" => __( "Pages" , FS_AFFILIATES_LOCALE ) ,
                "usage" => __( "Displays the Affiliate Link Generator" , FS_AFFILIATES_LOCALE )
            ) ,
            "[fs_affiliate_visits]"                      => array( "where" => __( "Pages" , FS_AFFILIATES_LOCALE ) ,
                "usage" => __( "Displays the Visits Section of the Affiliate Dashboard" , FS_AFFILIATES_LOCALE )
            ) ,
            "[fs_affiliate_payouts]"                     => array( "where" => __( "Pages" , FS_AFFILIATES_LOCALE ) ,
                "usage" => __( "Displays the Payouts Section of the Affiliate Dashboard" , FS_AFFILIATES_LOCALE )
            ) ,
            "[fs_affiliate_campaigns]"                   => array( "where" => __( "Pages" , FS_AFFILIATES_LOCALE ) ,
                "usage" => __( "Displays the Campaigs Section of the Affiliate Dashboard" , FS_AFFILIATES_LOCALE )
            ) ,
            "[fs_affiliate_logout]"                      => array( "where" => __( "Pages" , FS_AFFILIATES_LOCALE ) ,
                "usage" => __( "Displays the Logout Link to the Affiliate" , FS_AFFILIATES_LOCALE )
            ) ,
            "[fs_affiliate_name]"                        => array( "where" => __( "Pages" , FS_AFFILIATES_LOCALE ) ,
                "usage" => __( "Displays the Affiliate Name" , FS_AFFILIATES_LOCALE )
            ) ,
            "[fs_affiliate_username]"                    => array( "where" => __( "Pages" , FS_AFFILIATES_LOCALE ) ,
                "usage" => __( "Displays the Affiliate Username" , FS_AFFILIATES_LOCALE )
            ) ,
            "[fs_affiliate_email]"                       => array( "where" => __( "Pages" , FS_AFFILIATES_LOCALE ) ,
                "usage" => __( "Displays the Affiliate Email" , FS_AFFILIATES_LOCALE )
            ) ,
            "[fs_affiliate_id]"                          => array( "where" => __( "Pages" , FS_AFFILIATES_LOCALE ) ,
                "usage" => __( "Displays the Affiliate ID" , FS_AFFILIATES_LOCALE )
            ) ,
            "[fs_affiliate_link]"                        => array( "where" => __( "Pages" , FS_AFFILIATES_LOCALE ) ,
                "usage" => __( "Displays the Affiliate Link" , FS_AFFILIATES_LOCALE )
            ) ,
            "[fs_affiliate_referral_code]"               => array( "where" => __( "Pages" , FS_AFFILIATES_LOCALE ) ,
                "usage" => __( "Displays the Affiliate Referral Code" , FS_AFFILIATES_LOCALE )
            ) ,
            "[fs_affiliate_campaigns_overview]"          => array( "where" => __( "Pages" , FS_AFFILIATES_LOCALE ) ,
                "usage" => __( "Displays the Affiliate Campaign Statistics" , FS_AFFILIATES_LOCALE )
            ) ,
            "[fs_affiliate_commission_rate]"             => array( "where" => __( "Pages" , FS_AFFILIATES_LOCALE ) ,
                "usage" => __( "Displays the Commission Rate for the Affiliate" , FS_AFFILIATES_LOCALE )
            ) ,
            "[fs_affiliate_paid_commission_rate]"        => array( "where" => __( "Pages" , FS_AFFILIATES_LOCALE ) ,
                "usage" => __( "Displays the Paid Commission Rate for the Affiliate" , FS_AFFILIATES_LOCALE )
            ) ,
            "[fs_affiliate_unpaid_commission_rate]"      => array( "where" => __( "Pages" , FS_AFFILIATES_LOCALE ) ,
                "usage" => __( "Displays the Unpaid Commission Rate for the Affiliate" , FS_AFFILIATES_LOCALE )
            ) ,
            "[fs_affiliate_overall_commission_rate]"     => array( "where" => __( "Pages" , FS_AFFILIATES_LOCALE ) ,
                "usage" => __( "Displays the Overall Commission Rate for the Affiliate" , FS_AFFILIATES_LOCALE )
            ) ,
            "[fs_affiliate_refer_a_friend]"              => array( "where" => __( "Pages" , FS_AFFILIATES_LOCALE ) ,
                "usage" => __( "Displays the Refer a Friend Form Section of the Affiliate Dashboard" , FS_AFFILIATES_LOCALE )
            ) ,
            "[fs_affiliate_wallet]"                      => array( "where" => __( "Pages" , FS_AFFILIATES_LOCALE ) ,
                "usage" => __( "Displays the Wallet Section of the Affiliate Dashboard" , FS_AFFILIATES_LOCALE )
            ) ,
            "[fs_affiliate_wallet_balance]"              => array( "where" => __( "Pages" , FS_AFFILIATES_LOCALE ) ,
                "usage" => __( "Displays the Wallet Balance of an Affiliate" , FS_AFFILIATES_LOCALE )
            ) ,
            "[fs_affiliate_leaderboard]"                 => array( "where" => __( "Pages" , FS_AFFILIATES_LOCALE ) ,
                "usage" => __( "Displays the Leaderboard Section of the Affiliate Dasboard" , FS_AFFILIATES_LOCALE )
            ) ,
            "[fs_affiliate_pushover_notifications]"      => array( "where" => __( "Pages" , FS_AFFILIATES_LOCALE ) ,
                "usage" => __( "Displays the Pushover Notification Section of the Affiliate Dashboard" , FS_AFFILIATES_LOCALE )
            ) ,
            "[fs_affiliates_opt_in_form]"                => array( "where" => __( "Pages" , FS_AFFILIATES_LOCALE ) ,
                "usage" => __( "Displays the Email Opt-In(Newsletter Subscription) Form" , FS_AFFILIATES_LOCALE )
            ) ,
            "[fs_affiliate_wc_coupon_linking]"           => array( "where" => __( "Pages" , FS_AFFILIATES_LOCALE ) ,
                "usage" => __( "Displays the Linked Coupons Section of the Affiliate Dashboard" , FS_AFFILIATES_LOCALE )
            ) ,
            "[fs_affiliates_application_status]"         => array( "where" => __( "Pages" , FS_AFFILIATES_LOCALE ) ,
                "usage" => __( "Displays the Affiliate's Application Status" , FS_AFFILIATES_LOCALE )
            ) ,
            "[fs_affiliate_payment_method_alert]"        => array( "where" => __( "Pages" , FS_AFFILIATES_LOCALE ) ,
                "usage" => __( "Displays the Payment Method Alert" , FS_AFFILIATES_LOCALE )
            ) ,
            "[fs_affiliate_billing_details]"             => array( "where" => __( "Pages" , FS_AFFILIATES_LOCALE ) ,
                "usage" => __( "Displays the Billing Details Section" , FS_AFFILIATES_LOCALE )
            ) ,
            "[fs_affiliate_billing_details_alert]"       => array( "where" => __( "Pages" , FS_AFFILIATES_LOCALE ) ,
                "usage" => __( "Displays the Billing Details Alert" , FS_AFFILIATES_LOCALE )
            ) ,
                ) ;
        ?>
        <div class="fs_affiliates_shortcodes_content">
            <table class="fs_affiliates_shortcodes_info">
                <thead>
                    <tr>
                        <th>
                            <?php _e( 'Shortcode' , FS_AFFILIATES_LOCALE ) ; ?>
                        </th>
                        <th>
                            <?php _e( 'Context where Shortcode is valid' , FS_AFFILIATES_LOCALE ) ; ?>
                        </th>
                        <th>
                            <?php _e( 'Purpose' , FS_AFFILIATES_LOCALE ) ; ?>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ( fs_affiliates_check_is_array( $shortcodes_info ) ) {
                        foreach ( $shortcodes_info as $shortcode => $s_info ) {
                            ?>
                            <tr>
                                <td>
                                    <?php echo $shortcode ; ?>
                                </td>
                                <td>
                                    <?php echo $s_info[ 'where' ] ; ?>
                                </td>
                                <td>
                                    <?php echo $s_info[ 'usage' ] ; ?>
                                </td>
                            </tr>
                            <?php
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <?php
    }

}

return new FS_Affiliates_Shortcodes_Tab() ;

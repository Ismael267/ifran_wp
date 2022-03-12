<?php
/**
 * Affiliate Wallet
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'FS_Affiliates_Wallet_Module' ) ) {

    /**
     * Class FS_Affiliates_Wallet_Module
     */
    class FS_Affiliates_Wallet_Module extends FS_Affiliates_Modules {
        /*
         * Data
         */

        protected $data = array(
            'enabled'                   => 'no' ,
            'allowed_affiliates_method' => '1' ,
            'selected_affiliates'       => array() ,
            'menu_label'                => '' ,
            'balance_label'             => '' ,
            'log_label'                 => '' ,
            'checkout_coupon_label'     => 'Wallet Redeemed Value' ,
        ) ;

        /**
         * Class Constructor
         */
        public function __construct() {
            $this->id    = 'affiliate_wallet' ;
            $this->title = __( 'Affiliate Wallet' , FS_AFFILIATES_LOCALE ) ;

            parent::__construct() ;
        }

        /*
         * Plugin enabled
         */

        public function is_plugin_enabled() {
            $woocommerce = FS_Affiliates_Integration_Instances::get_integration_by_id( 'woocommerce' ) ;

            if ( $woocommerce->is_enabled() )
                return true ;

            return false ;
        }

        /*
         * Get settings link
         */

        public function settings_link() {
            return add_query_arg( array( 'page' => 'fs_affiliates' , 'tab' => 'modules' , 'section' => $this->id ) , admin_url( 'admin.php' ) ) ;
        }

        /*
         * Front End Action
         */

        public function frontend_action() {
            add_filter( 'fs_affiliates_frontend_dashboard_menu' , array( $this , 'wallet_menu' ) , 11 , 3 ) ;
            add_action( 'fs_affiliates_dashboard_content_wallet' , array( $this , 'display_dashboard_content' ) , 10 , 2 ) ;
            add_filter( 'fs_affiliates_is_valid_affiliate' , array( $this , 'is_valid_affiliate' ) , 10 , 2 ) ;
            add_filter( 'woocommerce_coupon_message' , array( $this , 'fs_success_message_for_coupon' ) , 10 , 2 ) ;
            add_filter( 'woocommerce_add_message' , array( $this , 'fs_removal_message_for_coupon' ) , 10 , 1 ) ;
            add_action( 'woocommerce_removed_coupon' , array( $this , 'fs_display_message_in_checkout' ) , 10 , 1 ) ;
            add_action( 'woocommerce_before_checkout_form' , array( $this , 'button_to_redeem_wallet_balance' ) ) ;
            add_action( 'woocommerce_checkout_update_order_meta' , array( $this , 'fs_remove_coupon_after_place_order' ) , 10 , 2 ) ;
            add_action( 'wp_head' , array( $this , 'apply_wallet_balance' ) ) ;
            add_filter( 'woocommerce_cart_totals_coupon_label' , array( $this , 'alter_coupon_name_display' ) , 10 , 2 ) ;
        }

        /*
         * Both Front End and Back End Action
         */

        public function actions() {
            add_filter( 'fs_affiliates_custom_payment_preference_option' , array( $this , 'custom_payment_preference_option' ) , 10 , 1 ) ;

            add_filter( 'fs_affiliates_custom_payment_preference_status' , array( $this , 'custom_payment_preference_status' ) , 10 , 1 ) ;

            add_action( 'fs_affiliates_new_referral' , array( $this , 'automatic_wallet_payment' ) , 8 , 2 ) ;

            add_action( 'fs_affiliates_new_mlm_referral' , array( $this , 'automatic_wallet_payment' ) , 8 , 2 ) ;

            add_shortcode( 'fs_affiliate_wallet_balance' , array( $this , 'shortcode_wallet_balance' ) ) ;
        }

        /*
         * Admin Actions
         */

        public function admin_action() {
            add_filter( $this->plugin_slug . '_admin_field_referral_actions' , array( $this , 'render_pay_now_action_for_wallet' ) , 10 , 3 ) ;
            add_filter( $this->plugin_slug . '_list_of_action_for_referral' , array( $this , 'list_of_action_for_wallet' ) , 10 , 1 ) ;
            add_filter( $this->plugin_slug . '_admin_field_payout_methods' , array( $this , 'list_of_action_for_wallet' ) ) ;
            add_action( $this->plugin_slug . '_admin_field_referral_pay' , array( $this , 'pay_via_wallet' ) , 10 , 3 ) ;
            add_action( $this->plugin_slug . '_admin_field_referral_pay-via-wallet_generate_payouts' , array( $this , 'do_generate_payouts_for_wallet' ) ) ;
        }

        /*
         * Custom Payment Preference Option
         */

        public function wallet_menu( $menus , $user_id , $affiliate_id ) {
            if ( isset( $menus[ 'profile' ] ) ) {

                $profile = $menus[ 'profile' ] ;

                unset( $menus[ 'profile' ] ) ;

                $menus[ 'wallet' ] = array( 'label' => $this->menu_label , 'code' => 'fa-usd' ) ;

                $menus[ 'profile' ] = $profile ;
            } else {
                $menus[ 'wallet' ] = array( 'label' => $this->menu_label , 'code' => 'fa-usd' ) ;
            }

            return $menus ;
        }

        /*
         * Display Dashboard Content
         */

        public static function display_dashboard_content( $user_id , $AffiliateId ) {

            if ( ! fs_affiliates_is_wallet_eligible( $AffiliateId ) )
                return ;

            $AvailableBalance = get_post_meta( $AffiliateId , 'fs_affiliate_commission_amount_as_wallet' , true ) ;
            $args             = array( 'post_type' => 'fs-wallet-logs' , 'post_status' => 'publish' , 'numberposts' => -1 , 'author' => $AffiliateId , 'fields' => 'ids' ) ;
            $transaction_logs = get_posts( $args ) ;
            $count            = count( $transaction_logs ) ;
            $current_page     = isset( $_REQUEST[ 'page_no' ] ) && $_REQUEST[ 'page_no' ] ? ( int ) $_REQUEST[ 'page_no' ] : 1 ;
            $per_page         = get_option( 'fs_affiliates_wallet_per_page_count' , 5 ) ;
            $offset           = ($current_page - 1) * $per_page ;
            $page_count       = ceil( $count / $per_page ) ;
            $footable_colspan = 6 ;
            ?>
            <div class="fs_affiliates_form">
                <h2><?php _e( 'Wallet' , FS_AFFILIATES_LOCALE ) ; ?></h2>
                <div class="fs_affiliate_available_balance_in_wallet">
                    <label>
                        <?php _e( 'Wallet Balance : ' , FS_AFFILIATES_LOCALE ) ; ?>
                        <?php echo fs_affiliates_price( $AvailableBalance ) ; ?>
                    </label>
                </div>
                <div class="fs_affiliate_transaction_log_for_wallet">
                    <h2><?php _e( 'Transaction Log' , FS_AFFILIATES_LOCALE ) ; ?></h2>
                    <table class="fs_affiliate_transaction_log_table fs_affiliates_table fs_affiliates_frontend_table">
                        <th class="fs_affiliates_sno fs_affiliate_transaction_log_sno"><?php _e( 'S.no' , FS_AFFILIATES_LOCALE ) ; ?></th>
                        <th><?php _e( 'Event' , FS_AFFILIATES_LOCALE ) ; ?></th>
                        <th><?php _e( 'Earned Balance' , FS_AFFILIATES_LOCALE ) ; ?></th>
                        <th><?php _e( 'Used Balance' , FS_AFFILIATES_LOCALE ) ; ?></th>
                        <th><?php _e( 'Available Balance' , FS_AFFILIATES_LOCALE ) ; ?></th>
                        <th><?php _e( 'Date' , FS_AFFILIATES_LOCALE ) ; ?></th>
                        <tbody>
                            <?php
                            $args             = array( 'post_type' => 'fs-wallet-logs' , 'offset' => $offset , 'numberposts' => $per_page , 'post_status' => 'publish' , 'author' => $AffiliateId , 'fields' => 'ids' ) ;
                            $transaction_logs = get_posts( $args ) ;

                            if ( fs_affiliates_check_is_array( $transaction_logs ) ) {
                                $i = 1 ;
                                foreach ( $transaction_logs as $transactionid ) {
                                    $WalletObj = new FS_Affiliates_Wallet( $transactionid ) ;
                                    ?>
                                    <tr>
                                        <td class="fs_affiliates_sno fs_affiliate_transaction_log_sno" data-title="<?php esc_html_e( 'S.No' , FS_AFFILIATES_LOCALE ) ?>"><?php echo $i ; ?></td>
                                        <td data-title="<?php esc_html_e( 'Event' , FS_AFFILIATES_LOCALE ) ?>"><?php echo $WalletObj->event ; ?></td>
                                        <td data-title="<?php esc_html_e( 'Earned Balance' , FS_AFFILIATES_LOCALE ) ?>"><?php echo fs_affiliates_price( $WalletObj->earned_balance ) ; ?></td>
                                        <td data-title="<?php esc_html_e( 'Used Balance' , FS_AFFILIATES_LOCALE ) ?>"><?php echo fs_affiliates_price( $WalletObj->used_balance ) ; ?></td>
                                        <td data-title="<?php esc_html_e( 'Available Balance' , FS_AFFILIATES_LOCALE ) ?>"><?php echo fs_affiliates_price( $WalletObj->available_balance ) ; ?></td>
                                        <td data-title="<?php esc_html_e( 'Date' , FS_AFFILIATES_LOCALE ) ?>"><?php echo fs_affiliates_local_datetime( $WalletObj->date ) ; ?></td>
                                    </tr>
                                    <?php
                                    $i ++ ;
                                }
                            }
                            ?>
                        </tbody>
                        <tfoot>
                            <tr style="clear:both;">
                                <td colspan="<?php echo $footable_colspan ; ?>" class="footable-visible">
                                    <div class="pagination pagination-centered">
                                        <?php
                                        if ( $page_count > 1 ) {
                                            FS_Affiliates_Dashboard::fs_affiliates_set_pagination( $current_page , $page_count ) ;
                                        }
                                        ?>
                                    </div>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <?php
        }

        /*
         * Custom Payment Preference Option
         */

        public function custom_payment_preference_option( $options ) {
            $options[ 'wallet' ] = __( 'Wallet' , FS_AFFILIATES_LOCALE ) ;

            return $options ;
        }

        public function custom_payment_preference_status( $options ) {
            $options[ 'wallet' ] = 'enable' ;

            return $options ;
        }

        /**
         * Output Affiliate Wallet Balance
         */
        public static function shortcode_wallet_balance( $atts , $content , $tag ) {

            ob_start() ;

            $affiliate_id     = fs_affiliates_is_user_having_affiliate() ;
            $AvailableBalance = get_post_meta( $affiliate_id , 'fs_affiliate_commission_amount_as_wallet' , true ) ;

            echo fs_affiliates_price( $AvailableBalance ) ; // output for shortcode

            $content = ob_get_contents() ;
            ob_end_clean() ;

            return $content ;
        }

        /*
         * Get eligible affiliates
         */

        public function is_valid_affiliate( $bool , $affilate_id ) {

            if ( $this->allowed_affiliates_method == '2' ) {
                $eligible_affiliates = $this->selected_affiliates ;

                if ( fs_affiliates_check_is_array( $eligible_affiliates ) && ! in_array( $affilate_id , $eligible_affiliates ) ) {
                    return false ;
                }
            }

            return $bool ;
        }

        /*
         * Get settings options array
         */

        public function settings_options_array() {
            return array(
                array(
                    'type'  => 'title' ,
                    'title' => __( 'Affiliate Wallet' , FS_AFFILIATES_LOCALE ) ,
                    'id'    => 'fs_affiliates_wallet_options' ,
                ) ,
                array(
                    'title'   => __( 'Affiliate Wallet can be Used by' , FS_AFFILIATES_LOCALE ) ,
                    'desc'    => __( 'The Affiliates selected in this option can receive their affiliate commission in their wallet.' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->plugin_slug . '_' . $this->id . '_allowed_affiliates_method' ,
                    'type'    => 'select' ,
                    'class'   => 'fs_affiliates_allowed_affiliates_method' ,
                    'default' => '1' ,
                    'options' => array(
                        '1' => __( 'All Affiliates' , FS_AFFILIATES_LOCALE ) ,
                        '2' => __( 'Selected Affiliates' , FS_AFFILIATES_LOCALE ) ,
                    ) ,
                ) ,
                array(
                    'title'     => __( 'Selected Affiliates' , FS_AFFILIATES_LOCALE ) ,
                    'id'        => $this->plugin_slug . '_' . $this->id . '_selected_affiliates' ,
                    'type'      => 'ajaxmultiselect' ,
                    'class'     => 'fs_affiliates_selected_affiliate' ,
                    'list_type' => 'affiliates' ,
                    'action'    => 'fs_affiliates_search' ,
                    'default'   => array() ,
                ) ,
                array(
                    'title'   => esc_html__( 'Automatic Commission' , FS_AFFILIATES_LOCALE ) ,
                    'desc'    => esc_html__( 'By enabling this checkbox, the referral commission will be credited  to the affiliate wallet automatically for those who are all selected their payment method as "Wallet"' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->plugin_slug . '_' . $this->id . '_allowed_auto_pay' ,
                    'type'    => 'checkbox' ,
                    'default' => 'no' ,
                ) ,
                array(
                    'title'   => __( 'Affiliate Menu Label' , FS_AFFILIATES_LOCALE ) ,
                    'desc'    => __( 'This label will be used for displaying the Wallet section on the affiliate dashboard' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->plugin_slug . '_' . $this->id . '_menu_label' ,
                    'type'    => 'text' ,
                    'default' => 'Wallet' ,
                ) ,
                array(
                    'title'   => __( 'Wallet Balance Label' , FS_AFFILIATES_LOCALE ) ,
                    'desc'    => __( 'This label will be used for displaying the Wallet section on the affiliate dashboard' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->plugin_slug . '_' . $this->id . '_balance_label' ,
                    'type'    => 'text' ,
                    'default' => 'Wallet Balance' ,
                ) ,
                array(
                    'title'   => __( 'Transaction Log Label' , FS_AFFILIATES_LOCALE ) ,
                    'desc'    => __( 'This label will be used as the Transaction Log Label on the Affiliate Dashboard' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->plugin_slug . '_' . $this->id . '_log_label' ,
                    'type'    => 'text' ,
                    'default' => 'Transaction Log' ,
                ) ,
                array(
                    'title'   => esc_html__( 'Coupon Display Name' , FS_AFFILIATES_LOCALE ) ,
                    'desc'    => esc_html__( 'Enter the text to display in the Order Table' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->plugin_slug . '_' . $this->id . '_checkout_coupon_label' ,
                    'type'    => 'text' ,
                    'default' => 'Wallet Redeemed Value' ,
                ) ,
                array(
                    'type' => 'sectionend' ,
                    'id'   => 'fs_affiliates_wallet_options' ,
                ) ,
            ) ;
        }

        /*
         * Extra Fields
         */

        public function extra_fields() {
            if ( ! class_exists( 'FS_Affiliates_Wallet_Logs_Post_Table' ) ) {
                require_once( FS_AFFILIATES_PLUGIN_PATH . '/inc/admin/menu/wp-list-table/class-fs-affiliates-wallet-logs-table.php' ) ;
            }

            echo '<div class="' . $this->plugin_slug . '_table_wrap">' ;
            echo '<h2 class="wp-heading-inline">' . __( 'Wallet Transaction Logs' , FS_AFFILIATES_LOCALE ) . '</h2>' ;

            $post_table = new FS_Affiliates_Wallet_Logs_Post_Table() ;
            $post_table->prepare_items() ;
            $post_table->display() ;
            echo '</div>' ;
        }

        public function render_pay_now_action_for_wallet( $actions , $referral_id , $current_url ) {
            if ( 'fs_unpaid' != get_post_status( $referral_id ) )
                return $actions ;

            $ReferralObj  = new FS_Affiliates_Referrals( $referral_id ) ;
            $affiliate_id = $ReferralObj->affiliate ;
            $amount       = $ReferralObj->amount ;
            $payment_data = get_post_meta( $affiliate_id , 'fs_affiliates_user_payment_datas' , true ) ;

            if ( ! fs_affiliates_check_is_array( $payment_data ) )
                return $actions ;

            $PaymentMethod = $payment_data[ 'fs_affiliates_payment_method' ] ;
            if ( $PaymentMethod != 'wallet' )
                return $actions ;

            $actions[ 'pay-via-wallet' ] = fs_affiliates_get_action_display( 'pay-via-wallet' , $referral_id , $current_url ) ;
            return $actions ;
        }

        public function list_of_action_for_wallet( $action ) {
            $action[ 'pay-via-wallet' ] = __( 'Pay via Wallet' , FS_AFFILIATES_LOCALE ) ;
            return $action ;
        }

        public function pay_via_wallet( $ReferralIds , $action , $args = array() ) {
            if ( $action != 'pay-via-wallet' )
                return ;

            $PayoutData                    = array() ;
            $PayoutReferrals               = array() ;
            $TotalAffiliateCommission      = array() ;
            $ReferralIdsToAwardAmtAsWallet = array() ;
            foreach ( $ReferralIds as $Id ) {
                $ReferralObj = new FS_Affiliates_Referrals( $Id ) ;
                if ( 'fs_unpaid' === get_post_status( $Id ) ) {
                    $AffiliateId  = $ReferralObj->affiliate ;
                    $Commission   = $ReferralObj->amount ;
                    $payment_data = get_post_meta( $AffiliateId , 'fs_affiliates_user_payment_datas' , true ) ;

                    if ( fs_affiliates_check_is_array( $payment_data ) ) {
                        $PaymentMethod = $payment_data[ 'fs_affiliates_payment_method' ] ;
                        if ( $PaymentMethod == 'wallet' ) {
                            if ( ! isset( $TotalAffiliateCommission[ $AffiliateId ] ) ) {
                                $TotalAffiliateCommission[ $AffiliateId ] = 0 ;
                            }
                            $CommissionValue                               = isset( $PayoutData[ $AffiliateId ][ 'commission' ] ) ? $PayoutData[ $AffiliateId ][ 'commission' ] + $Commission : $Commission ;
                            $ReferralCount                                 = isset( $PayoutData[ $AffiliateId ][ 'referral_count' ] ) ? $PayoutData[ $AffiliateId ][ 'referral_count' ] + 1 : 1 ;
                            $PayoutReferrals[]                             = $Id ;
                            $TotalAffiliateCommission[ $AffiliateId ]        += floatval( $Commission ) ;
                            $ReferralIdsToAwardAmtAsWallet[ $AffiliateId ][] = $Id ;
                            $PayoutData[ $AffiliateId ]                      = array(
                                'payment_mode'   => 'Wallet' ,
                                'generated_by'   => get_current_user_id() ,
                                'commission'     => $CommissionValue ,
                                'referral_count' => $ReferralCount ,
                                'referral_ids'   => $PayoutReferrals ,
                            ) ;
                        }
                    }
                }
            }

            /* To Check Minimum Threshold and Unset if not statisfied */
            if ( isset( $args[ 'min_threshold' ] ) && ! empty( $args[ 'min_threshold' ] ) ) {
                if ( fs_affiliates_check_is_array( $TotalAffiliateCommission ) ) {
                    foreach ( $TotalAffiliateCommission as $AffsId => $AffiliateAmount ) {
                        if ( $AffiliateAmount < floatval( $args[ 'min_threshold' ] ) ) {
                            unset( $PayoutData[ $AffsId ] , $ReferralIdsToAwardPoints[ $AffsId ] ) ;
                        }
                    }
                }
            }

            /* To Insert Points and update the status after threshold value statisfied */
            if ( fs_affiliates_check_is_array( $ReferralIdsToAwardAmtAsWallet ) ) {
                foreach ( $ReferralIdsToAwardAmtAsWallet as $AffId => $ReferralId ) {
                    if ( fs_affiliates_check_is_array( $ReferralId ) ) {
                        foreach ( $ReferralId as $id ) {
                            self::wallet_balance_update( $id , $AffId ) ;
                        }
                    }
                }
            }

            fs_insert_payout_data( $PayoutData ) ;
            $Redirect = remove_query_arg( array( 'action' , 'id' , 'paged' ) ) ;
            wp_safe_redirect( $Redirect ) ;
            exit() ;
        }

        public function wallet_balance_update( $ReferralID , $AffiliateId , $UpdatePayout = false ) {
            $ReferralObj            = new FS_Affiliates_Referrals( $ReferralID ) ;
            $AffiliateObj           = new FS_Affiliates_Data( $AffiliateId ) ;
            $AvailableWalletBalance = get_post_meta( $AffiliateId , 'fs_affiliate_commission_amount_as_wallet' , true ) ;
            $TotalWalletBalance     = ( $AvailableWalletBalance == '' ) ? $ReferralObj->amount : (( float ) $AvailableWalletBalance + $ReferralObj->amount) ;

            update_post_meta( $AffiliateId , 'fs_affiliate_commission_amount_as_wallet' , $TotalWalletBalance ) ;

            $WalletData[ 'affiliate_id' ]      = $AffiliateObj->user_name ;
            $WalletData[ 'event' ]             = $ReferralObj->description ;
            $WalletData[ 'earned_balance' ]    = $ReferralObj->amount ;
            $WalletData[ 'used_balance' ]      = 0 ;
            $WalletData[ 'available_balance' ] = $TotalWalletBalance ;
            $WalletData[ 'date' ]              = time() ;

            fs_affiliates_create_new_transaction_log_for_wallet( $WalletData , array( 'post_status' => 'publish' , 'post_author' => $AffiliateId ) ) ;

            if ( $ReferralObj->get_status() == 'fs_unpaid' ) {
                $ReferralObj->update_status( 'fs_paid' ) ;
            }

            if ( $UpdatePayout ) {
                $PayoutData               = array() ;
                $PayoutData[ $AffiliateId ] = array(
                    'payment_mode'   => 'Wallet' ,
                    'generated_by'   => 'auto' ,
                    'commission'     => $ReferralObj->amount ,
                    'referral_count' => 1 ,
                    'referral_ids'   => $ReferralID ,
                ) ;

                fs_insert_payout_data( $PayoutData ) ;
            }
        }

        /*
         * Pay commission to affiliate if they had chosen wallet payment method.
         */

        public function automatic_wallet_payment( $ReferralID , $AffiliateId ) {
            $ReferralObj  = new FS_Affiliates_Referrals( $ReferralID ) ;
            $AffiliateObj = new FS_Affiliates_Data( $AffiliateId ) ;

            if ( ! in_array( $ReferralObj->get_status() , array( 'fs_unpaid' , 'fs_paid' ) ) ) {
                return ;
            }

            $PaymentData = get_post_meta( $AffiliateId , 'fs_affiliates_user_payment_datas' , true ) ;
            $Method      = isset( $PaymentData[ 'fs_affiliates_payment_method' ] ) ? $PaymentData[ 'fs_affiliates_payment_method' ] : false ;

            // wallet balance update
            if ( ($Method == 'wallet') && ( 'yes' == get_option( 'fs_affiliates_affiliate_wallet_allowed_auto_pay' , 'no' ) || 'fs_paid' == $ReferralObj->get_status() ) ) {
                $current_affiliate = fs_affiliates_is_user_having_affiliate() ;

                if ( $current_affiliate == $AffiliateId && ! apply_filters( 'fs_affiliates_is_restricted_own_commission' , false ) )
                    return ;

                self::wallet_balance_update( $ReferralID , $AffiliateId , true ) ;
            }
        }

        public function button_to_redeem_wallet_balance() {
            if ( ! is_user_logged_in() )
                return ;

            if ( ! is_checkout() )
                return ;

            $AffiliateId = fs_get_affiliate_id_for_user( get_current_user_id() ) ;
            if ( ! $AffiliateId )
                return ;

            $AvailableWalletBalance = get_post_meta( $AffiliateId , 'fs_affiliate_commission_amount_as_wallet' , true ) ;
            if ( empty( $AvailableWalletBalance ) )
                return ;

            $this->fs_display_available_balance_after_apply( $AvailableWalletBalance ) ;
            ?>
            <form method="post" class="fs_button_to_redeem woocommerce-info">
                <div class="fs_button_to_redeem">
                    <?php echo sprintf( __( 'Available Wallet Balance is %s. You can make use of it to get a discount. Redeem Wallet Balance.' , FS_AFFILIATES_LOCALE ) , fs_affiliates_price( $AvailableWalletBalance ) ) ; ?>
                    <input id="fs_wallet_available_balance" class="input-text" type="hidden"  value="<?php echo $AvailableWalletBalance ; ?>" name="fs_apply_wallet_balance">
                    <input class="fs_apply_wallet_balance_button" type="submit" value="<?php _e( 'Redeem It' , FS_AFFILIATES_LOCALE ) ; ?>" name="fs_apply_wallet_balance_button">
                </div>
            </form>
            <?php
        }

        public function apply_wallet_balance() {
            if ( ! isset( $_POST[ 'fs_apply_wallet_balance_button' ] ) )
                return ;

            if ( ! isset( $_POST[ 'fs_apply_wallet_balance' ] ) )
                return ;

            $AmountInWallet = $_POST[ 'fs_apply_wallet_balance' ] ;
            if ( $AmountInWallet == 0 || $AmountInWallet == '' )
                return ;

            $UserId     = get_current_user_id() ;
            $UserData   = get_user_by( 'id' , $UserId ) ;
            $CouponCode = $UserData->user_login ;
            $CouponName = 'fs_' . strtolower( $CouponCode ) ;
            $CouponObj  = array(
                'post_title'   => $CouponName ,
                'post_content' => '' ,
                'post_status'  => 'publish' ,
                'post_author'  => $UserId ,
                'post_type'    => 'shop_coupon' ,
            ) ;
            $CouponId   = wp_insert_post( $CouponObj ) ;
            update_user_meta( $UserId , 'fsredeemcouponids' , $CouponId ) ;
            update_post_meta( $CouponId , 'customer_email' , array( $UserData->user_email ) ) ;
            update_post_meta( $CouponId , 'discount_type' , 'fixed_cart' ) ;
            update_post_meta( $CouponId , 'coupon_amount' , $AmountInWallet ) ;
            update_post_meta( $CouponId , 'individual_use' , 'yes' ) ;
            update_post_meta( $CouponId , 'usage_limit' , '1' ) ;
            update_post_meta( $CouponId , 'usage_count' , 0 ) ;
            update_post_meta( $CouponId , 'free_shipping' , 'yes' ) ;
            update_post_meta( $CouponId , 'apply_before_tax' , 'yes' ) ;
            update_option( 'fs_coupon_name' , $CouponName ) ;
            if ( WC()->cart->has_discount( 'fs_' . strtolower( $CouponCode ) ) )
                return ;

            WC()->cart->add_discount( 'fs_' . strtolower( $CouponCode ) ) ;
        }

        /**
         * Alter Coupon name on order review in checkout page
         */
        public function alter_coupon_name_display( $label , $coupon_obj ) {

            if ( ! is_object( $coupon_obj ) ) {
                return $label ;
            }

            $user_id = get_current_user_id() ;

            if ( empty( $user_id ) ) {
                return $label ;
            }

            $user_data = get_user_by( 'id' , $user_id ) ;

            if ( ! is_object( $user_data ) ) {
                return $label ;
            }

            $coupon_code = $user_data->user_login ;
            $coupon_name = 'fs_' . strtolower( $coupon_code ) ;

            if ( $coupon_obj->get_code() == $coupon_name ) {
                return $this->checkout_coupon_label ;
            }

            return $label ;
        }

        public function fs_remove_coupon_after_place_order( $OrderId , $OrderData ) {
            $this->redeem_wallet_commission_for_product_purchase( $OrderId ) ;
            $Order      = new WC_Order( $OrderId ) ;
            $UserId     = $Order->get_user_id() ;
            $UserData   = get_user_by( 'id' , $UserId ) ;
            $UserName   = isset( $UserData->user_login ) ? ($UserData->user_login) : '' ;
            $CouponName = 'fs_' . strtolower( $UserName ) ;

            if ( fs_affiliates_check_is_array( $Order->get_coupon_codes() ) ) {
                foreach ( $Order->get_coupon_codes() as $OrderedCoupon ) {
                    if ( $CouponName == $OrderedCoupon ) {
                        $CouponId = get_user_meta( $UserId , 'fsredeemcouponids' , true ) ;
                        if ( $CouponId != '' )
                            wp_trash_post( $CouponId ) ;
                    }
                }
            }
        }

        /**
         * Redeem Commission from Wallet
         */
        public function redeem_wallet_commission_for_product_purchase( $OrderId ) {
            $OrderObj    = new WC_Order( $OrderId ) ;
            $UserId      = $OrderObj->get_user_id() ;
            $AffiliateId = fs_get_affiliate_id_for_user( $UserId ) ;

            if ( ! $AffiliateId )
                return ;

            $AvailableWalletBalance = get_post_meta( $AffiliateId , 'fs_affiliate_commission_amount_as_wallet' , true ) ;

            if ( $AvailableWalletBalance == '' )
                return ;

            $UserData   = get_user_by( 'id' , $UserId ) ;
            $UserName   = isset( $UserData->user_login ) ? ($UserData->user_login) : '' ;
            $CouponName = 'fs_' . strtolower( $UserName ) ;

            foreach ( $OrderObj->get_items( array( 'coupon' ) ) as $CouponCode => $Values ) {
                if ( $CouponName != $Values[ 'name' ] )
                    continue ;

                $DiscountAmount   = $Values[ 'discount_amount' ] ;
                $RemainingBalance = ( float ) $AvailableWalletBalance - $DiscountAmount ;
                update_post_meta( $AffiliateId , 'fs_affiliate_commission_amount_as_wallet' , $RemainingBalance ) ;
            }

            $AffiliateObj                    = new FS_Affiliates_Data( $AffiliateId ) ;
            $WalletData[ 'affiliate_id' ]      = $AffiliateObj->user_name ;
            $WalletData[ 'event' ]             = "Wallet Balance Redeemed" ;
            $WalletData[ 'earned_balance' ]    = 0 ;
            $WalletData[ 'used_balance' ]      = $DiscountAmount ;
            $WalletData[ 'available_balance' ] = $RemainingBalance ;
            $WalletData[ 'date' ]              = time() ;

            fs_affiliates_create_new_transaction_log_for_wallet( $WalletData , array( 'post_status' => 'publish' , 'post_author' => $AffiliateId ) ) ;
        }

        public function fs_success_message_for_coupon( $msg , $msg_code ) {
            foreach ( WC()->cart->applied_coupons as $CouponCode ) {
                if ( strpos( $CouponCode , 'fs_' ) !== false ) {
                    switch ( $msg_code ) {
                        case 200 :
                            if ( ! isset( $_POST[ 'fs_apply_wallet_balance_button' ] ) )
                                $msg = '' ;

                            $msg = __( 'Wallet amount applied successfully' , FS_AFFILIATES_LOCALE ) ;
                            break ;
                        default:
                            $msg = '' ;
                            break ;
                    }
                }
            }
            return $msg ;
        }

        public function fs_removal_message_for_coupon( $message ) {
            if ( ! is_user_logged_in() )
                return $message ;

            $CouponCode = get_option( 'fs_coupon_name' ) ;
            $UserId     = get_current_user_id() ;
            $UserData   = get_user_by( 'id' , $UserId ) ;
            $UserName   = $UserData->user_login ;
            $CouponName = 'fs_' . strtolower( "$UserName" ) ;
            $woo_msg    = __( 'Coupon has been removed.' , 'woocommerce' ) ;
            if ( $message == $woo_msg )
                if ( $CouponName == $CouponCode )
                    $message    = __( 'Applied Wallet Balance has been removed' , FS_AFFILIATES_LOCALE ) ;

            return $message ;
        }

        public function fs_display_available_balance_after_apply( $AvailableWalletBalance ) {
            if ( ! fs_affiliates_check_is_array( WC()->cart->get_applied_coupons() ) )
                return ;

            $UserId     = get_current_user_id() ;
            $UserData   = get_user_by( 'id' , $UserId ) ;
            $UserName   = $UserData->user_login ;
            $CouponName = 'fs_' . strtolower( "$UserName" ) ;

            if ( ! isset( WC()->cart->coupon_discount_amounts[ "$CouponName" ] ) )
                return ;

            $DiscountAmount = WC()->cart->coupon_discount_amounts[ "$CouponName" ] ;
            if ( $DiscountAmount == 0 )
                return ;

            $RemainingBalance = $AvailableWalletBalance - $DiscountAmount ;

            foreach ( WC()->cart->get_applied_coupons() as $AppliedCouponName ) {
                if ( strtolower( $AppliedCouponName ) != $CouponName )
                    continue ;
                ?>
                <div class="woocommerce-message">
                    <?php echo sprintf( __( '%s has been applied. Available Wallet Balance is %s' , FS_AFFILIATES_LOCALE ) , $DiscountAmount , $RemainingBalance ) ; ?>
                </div>
                <div class="fs_coupon">
                    <script type="text/javascript">
                        jQuery( document ).ready( function () {
                            jQuery( ".fs_button_to_redeem" ).hide() ;
                        } ) ;
                    </script>
                </div>
                <?php
            }
        }

        public function fs_display_message_in_checkout( $CouponCode ) {
            $Coupon = new WC_Coupon( $CouponCode ) ;
            if ( is_object( $Coupon ) && $Coupon->is_valid() ) {
                $AffiliateId = fs_get_affiliate_id_for_user( get_current_user_id() ) ;
                if ( ! $AffiliateId )
                    return ;

                $AvailableWalletBalance = get_post_meta( $AffiliateId , 'fs_affiliate_commission_amount_as_wallet' , true ) ;
                if ( $AvailableWalletBalance == '' )
                    return ;

                $this->fs_display_available_balance_after_apply( $AvailableWalletBalance ) ;
            }
        }

        public function do_generate_payouts_for_wallet( $args ) {
            global $wpdb ;
            $selected_affiliates = implode( ', ' , $args[ 'selected_affiliate' ] ) ;
            // affiliate Selection
            $affiliates          = "SELECT DISTINCT ID FROM {$wpdb->posts} posts "
                    . "INNER JOIN {$wpdb->postmeta} postmeta ON posts.ID = postmeta.post_id "
                    . "WHERE posts.post_type=%s AND posts.post_status =%s" ;

            if ( $args[ 'affiliate_select_type' ] == 'include' ) {
                $affiliates .= " AND posts.ID IN($selected_affiliates)" ;
            }
            if ( $args[ 'affiliate_select_type' ] == 'exclude' ) {
                $affiliates .= " AND posts.ID NOT IN($selected_affiliates)" ;
            }

            $affiliates = $wpdb->prepare( $affiliates , 'fs-affiliates' , 'fs_active' ) ;
            $affiliates = array_filter( $wpdb->get_col( $affiliates ) ) ;

            if ( ! fs_affiliates_check_is_array( $affiliates ) )
                return ;

            $affiliates = implode( ', ' , $affiliates ) ;
            // referral Selection
            $referrals  = "SELECT DISTINCT ID FROM {$wpdb->posts} posts "
                    . "INNER JOIN {$wpdb->postmeta} postmeta ON posts.ID = postmeta.post_id "
                    . "WHERE posts.post_type=%s AND posts.post_status=%s AND posts.post_author IN($affiliates)" ;

            if ( ! empty( $args[ 'from_date' ] ) ) {
                $referrals .= " AND posts.post_date >='{$args[ 'from_date' ]}'" ;
            }
            if ( ! empty( $args[ 'to_date' ] ) ) {
                $referrals .= " AND posts.post_date <='{$args[ 'to_date' ]}'" ;
            }

            $referrals = $wpdb->prepare( $referrals , 'fs-referrals' , 'fs_unpaid' ) ;
            $referrals = array_filter( $wpdb->get_col( $referrals ) ) ;

            do_action( $this->plugin_slug . '_admin_field_referral_pay' , $referrals , 'pay-via-wallet' , $args ) ;
        }

    }

}

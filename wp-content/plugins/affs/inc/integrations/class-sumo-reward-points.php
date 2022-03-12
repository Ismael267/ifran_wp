<?php

/**
 * SUMO Reward Points
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!class_exists('FS_Affiliates_SUMO_Reward_Points')) {

    /**
     * Class FS_Affiliates_SUMO_Reward_Points
     */
    class FS_Affiliates_SUMO_Reward_Points extends FS_Affiliates_Integrations {
        /*
         * Data
         */

        protected $data = array(
            'enabled'                   => 'no',
            'allowed_affiliates_method' => '1',
            'optin_form'                => 'no',
            'selected_affiliates'       => array(),
            'optin_label'               => '',
        );

        /**
         * Class Constructor
         */
        public function __construct() {
            $this->id    = 'sumo_reward_points';
            $this->title = __('SUMO Reward Points', FS_AFFILIATES_LOCALE);

            parent::__construct();
        }

        /*
         * is enabled
         */

        public function is_enabled() {
            return $this->is_plugin_enabled() && 'yes' === $this->enabled;
        }

        /*
         * Plugin enabled
         */

        public function is_plugin_enabled() {
            return fs_affiliates_check_if_reward_points_is_active();
        }

        /*
         * Get settings link
         */

        public function settings_link() {
            return add_query_arg(array('page' => 'fs_affiliates', 'tab' => 'integration', 'section' => $this->id), admin_url('admin.php'));
        }

        /*
         * Get settings options array
         */

        public function settings_options_array() {
            return array(
                array(
                    'type'  => 'title',
                    'title' => esc_html__('SUMO Reward Points', FS_AFFILIATES_LOCALE),
                    'id'    => 'sumo_reward_points_options',
                ),
                array(
                    'title'   => esc_html__('Reward Points Opt-In Label', FS_AFFILIATES_LOCALE),
                    'id'      => $this->plugin_slug . '_' . $this->id . '_optin_label',
                    'type'    => 'text',
                    'default' => '',
                ),
                array(
                    'title'   => esc_html__('Automatic Commission', FS_AFFILIATES_LOCALE),
                    'id'      => $this->plugin_slug . '_' . $this->id . '_allowed_to_auto_pay',
                    'type'    => 'checkbox',
                    'default' => 'no',
                    'desc'    => esc_html__('By enabling this checkbox, the referral commission to affiliates will be credited as reward points automatically for those who are all selected their payment method as "SUMO Reward Points"', FS_AFFILIATES_LOCALE)
                ),
                array(
                    'type' => 'sectionend',
                    'id'   => 'sumo_reward_points_options',
                ),
            );
        }

        /*
         * Admin Actions
         */

        public function admin_action() {
            add_filter($this->plugin_slug . '_admin_field_referral_actions', array($this, 'render_pay_now_action_for_reward_points'), 10, 3);
            add_filter($this->plugin_slug . '_list_of_action_for_referral', array($this, 'list_of_action_for_reward_points'), 10, 1);
            add_filter($this->plugin_slug . '_admin_field_payout_methods', array($this, 'list_of_action_for_reward_points'), 10, 1);
            add_action($this->plugin_slug . '_admin_field_referral_pay', array($this, 'pay_as_reward_points'), 10, 3);
            add_action($this->plugin_slug . '_admin_field_referral_pay-via-reward_points_generate_payouts', array($this, 'do_generate_payouts_for_reward_points'));
        }

        /*
         * Frontend Actions
         */

        public function frontend_action() {
            
        }

        /*
         * Both Front End and Back End Action
         */

        public function actions() {
            add_filter('fs_affiliates_custom_payment_preference_option', array($this, 'custom_payment_preference_option'), 10, 1);

            add_filter('fs_affiliates_custom_payment_preference_status', array($this, 'custom_payment_preference_status'), 10, 1);
            
            add_action( 'fs_affiliates_new_referral' , array( $this , 'automatic_reward_points' ) , 8 , 2 ) ;
            
            add_action( 'fs_affiliates_new_mlm_referral' , array( $this , 'automatic_reward_points' ) , 8 , 2 ) ;

        }

        /*
         * Custom Payment Preference Option
         */

        public function custom_payment_preference_option($options) {
            $options['reward_points'] = __('SUMO Reward Points', FS_AFFILIATES_LOCALE);

            return $options;
        }

        public function custom_payment_preference_status($options) {
            $options['reward_points'] = 'enable';

            return $options;
        }

        public function render_pay_now_action_for_reward_points($actions, $referral_id, $current_url) {
            if ('fs_unpaid' != get_post_status($referral_id))
                return $actions;

            $ReferralObj  = new FS_Affiliates_Referrals($referral_id);
            $affiliate_id = $ReferralObj->affiliate;
            $amount       = $ReferralObj->amount;
            $payment_data = get_post_meta($affiliate_id, 'fs_affiliates_user_payment_datas', true);

            if (!fs_affiliates_check_is_array($payment_data))
                return $actions;

            $PaymentMethod = $payment_data['fs_affiliates_payment_method'];
            if ($PaymentMethod != 'reward_points')
                return $actions;

            $actions['pay-via-reward_points'] = fs_affiliates_get_action_display('pay-via-reward_points', $referral_id, $current_url);
            return $actions;
        }

        public function list_of_action_for_reward_points($action) {
            $action['pay-via-reward_points'] = __('Pay via Reward Points', FS_AFFILIATES_LOCALE);

            return $action;
        }

        public function pay_as_reward_points($ReferralIds, $action, $args = array()) {
            if ($action != 'pay-via-reward_points')
                return;

            self::reward_points_pay_process($ReferralIds, $args);
            
            $Redirect = remove_query_arg(array('action', 'id', 'paged'));
            wp_safe_redirect($Redirect);
            exit();
        }

        public function do_generate_payouts_for_reward_points($args) {
            global $wpdb;
            $selected_affiliates = implode(', ', $args['selected_affiliate']);
            // affiliate Selection
            $affiliates = "SELECT DISTINCT ID FROM {$wpdb->posts} posts "
                    . "INNER JOIN {$wpdb->postmeta} postmeta ON posts.ID = postmeta.post_id "
                    . "WHERE posts.post_type=%s AND posts.post_status =%s";

            if ($args['affiliate_select_type'] == 'include') {
                $affiliates .= " AND posts.ID IN($selected_affiliates)";
            }
            if ($args['affiliate_select_type'] == 'exclude') {
                $affiliates .= " AND posts.ID NOT IN($selected_affiliates)";
            }

            $affiliates = $wpdb->prepare($affiliates, 'fs-affiliates', 'fs_active');
            $affiliates = array_filter($wpdb->get_col($affiliates));

            if (!fs_affiliates_check_is_array($affiliates))
                return;

            $affiliates = implode(', ', $affiliates);
            // referral Selection
            $referrals = "SELECT DISTINCT ID FROM {$wpdb->posts} posts "
                    . "INNER JOIN {$wpdb->postmeta} postmeta ON posts.ID = postmeta.post_id "
                    . "WHERE posts.post_type=%s AND posts.post_status=%s AND posts.post_author IN($affiliates)";

            if (!empty($args['from_date'])) {
                $referrals .= " AND posts.post_date >='{$args['from_date']}'";
            }
            if (!empty($args['to_date'])) {
                $referrals .= " AND posts.post_date <='{$args['to_date']}'";
            }
            $referrals = $wpdb->prepare($referrals, 'fs-referrals', 'fs_unpaid');
            $referrals = array_filter($wpdb->get_col($referrals));

            do_action($this->plugin_slug . '_admin_field_referral_pay', $referrals, 'pay-via-reward_points', $args);
        }
        
        /*
         * Pay process for reward points
         */

        public function reward_points_pay_process( $ReferralIds , $args = array()) {
            $PayoutData               = array();
            $PayoutReferrals          = array();
            $ReferralIdsToAwardPoints = array();
            $TotalAffiliateCommission = array();
            
            foreach ($ReferralIds as $Id) {
                $ReferralObj = new FS_Affiliates_Referrals($Id);
                
                if ( in_array(get_post_status($Id) , array('fs_unpaid' , 'fs_paid')) ) {
                    $AffiliateId  = $ReferralObj->affiliate;
                    $Commission   = $ReferralObj->amount;
                    $payment_data = get_post_meta($AffiliateId, 'fs_affiliates_user_payment_datas', true);
                    
                    if (fs_affiliates_check_is_array($payment_data)) {
                        $PaymentMethod = $payment_data['fs_affiliates_payment_method'];
                        
                        if ($PaymentMethod == 'reward_points') {
                            if (!isset($TotalAffiliateCommission[$AffiliateId])) {
                                $TotalAffiliateCommission[$AffiliateId] = 0;
                            }

                            $CommissionValue                          = isset($PayoutData[$AffiliateId]['commission']) ? $PayoutData[$AffiliateId]['commission'] + $Commission : $Commission;
                            $ReferralCount                            = isset($PayoutData[$AffiliateId]['referral_count']) ? $PayoutData[$AffiliateId]['referral_count'] + 1 : 1;
                            $PayoutReferrals[]                        = $Id;
                            $ReferralIdsToAwardPoints[$AffiliateId][] = $Id;
                            $TotalAffiliateCommission[$AffiliateId]   += floatval($Commission);
                            $PayoutData[$AffiliateId]                 = array(
                                'payment_mode'   => 'SUMO Reward Points',
                                'generated_by'   => get_current_user_id(),
                                'commission'     => $CommissionValue,
                                'referral_count' => $ReferralCount,
                                'referral_ids'   => $PayoutReferrals,
                            );
                        }
                        
                    }
                }
            }

            /* To Check Minimum Threshold and Unset if not statisfied */
            if (isset($args['min_threshold']) && !empty($args['min_threshold'])) {
                if (fs_affiliates_check_is_array($TotalAffiliateCommission)) {
                    foreach ($TotalAffiliateCommission as $AffsId => $AffiliateAmount) {
                        if ($AffiliateAmount < floatval($args['min_threshold'])) {
                            unset($PayoutData[$AffsId], $ReferralIdsToAwardPoints[$AffsId]);
                        }
                    }
                }
            }

            /* To Insert Points and update the status after threshold value statisfied */
            if (fs_affiliates_check_is_array($ReferralIdsToAwardPoints)) {
                foreach ($ReferralIdsToAwardPoints as $AffId => $ReferralId) {
                    if (fs_affiliates_check_is_array($ReferralId)) {
                        foreach ($ReferralId as $id) {
                            $ReferralObj       = new FS_Affiliates_Referrals($id);
                            $AffiliateObj      = new FS_Affiliates_Data($AffId);
                            $UserId            = $AffiliateObj->user_id;
                            $Commission        = $ReferralObj->amount;
                            $PointAmount       = method_exists('RSMemberFunction', 'redeem_points_percentage') ? RSMemberFunction::redeem_points_percentage($UserId) : RSMemberFunction::user_role_based_redeem_points($UserId);
                            $UpdatedValue      = $Commission / $PointAmount;
                            $UpdatedCommission = $UpdatedValue * wc_format_decimal(get_option('rs_redeem_point'));
                            $date              = function_exists('expiry_date_for_points') ? expiry_date_for_points() : rs_function_to_get_expiry_date_in_unixtimestamp();
                            if (!method_exists('RSPointExpiry', 'earning_conversion_settings') && !method_exists('RSPointExpiry', 'total_available_points_in_site')) {
                                $table_args = array(
                                    'user_id'        => $UserId,
                                    'pointstoinsert' => $UpdatedCommission,
                                    'checkpoints'    => 'RPFAC',
                                    'date'           => $date,
                                );
                                RSPointExpiry::insert_earning_points($table_args);
                                RSPointExpiry::record_the_points($table_args);
                            } else {
                                RSPointExpiry::insert_earning_points($UserId, $UpdatedCommission, 0, $date, 'RPFAC', '', '', '', $reasonindetail = '');
                                $equearnamt     = RSPointExpiry::earning_conversion_settings($UpdatedCommission);
                                $totalpoints    = RSPointExpiry::total_available_points_in_site($UserId);
                                RSPointExpiry::record_the_points($UserId, $UpdatedCommission, 0, $date, 'RPFAC', $equearnamt, 0, 0, '', '', '', $reasonindetail = '', $totalpoints, '', 0);
                            }
                            
                            if( 'fs_unpaid' == get_post_status($Id) ){
                                $ReferralObj->update_status('fs_paid');
                            }
                            
                        }
                    }
                }
            }

            /* To Insert Payouts, if statisfied */
            fs_insert_payout_data($PayoutData);
        }
        
        /*
         * Pay commission to affiliate if they had chosen SUMO Reward Points payment method.
         */

        public function automatic_reward_points( $ReferralID , $AffiliateId ) {
            $PaymentData = get_post_meta( $AffiliateId , 'fs_affiliates_user_payment_datas' , true ) ;
            $Method      = isset( $PaymentData[ 'fs_affiliates_payment_method' ] ) ? $PaymentData[ 'fs_affiliates_payment_method' ] : false ;

            // reward points update
            if ( ($Method == 'reward_points') && (get_option( 'fs_affiliates_sumo_reward_points_allowed_to_auto_pay' , 'no' ) == 'yes' || 'fs_paid' == get_post_status($ReferralID)) ) {
                $current_affiliate = fs_affiliates_is_user_having_affiliate() ;

                if ( $current_affiliate == $AffiliateId && ! apply_filters( 'fs_affiliates_is_restricted_own_commission' , false ) )
                    return ;
                
                self::reward_points_pay_process(array($ReferralID));
            }
        }

    }

}
    

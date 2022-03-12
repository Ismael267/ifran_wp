<?php

/**
 * Fraud Protection
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'FS_Affiliates_Fraud_Protection' ) ) {

    /**
     * Class FS_Affiliates_Fraud_Protection
     */
    class FS_Affiliates_Fraud_Protection extends FS_Affiliates_Modules {
        /*
         * Data
         */

        protected $data = array(
            'enabled'                               => 'no' ,
            'block_login'                           => 'no' ,
            'no_of_attempt'                         => '' ,
            'min_duration'                          => array() ,
            'threshold_duration'                    => array() ,
            'commission_for_new_user'               => 'no' ,
            'commission_for_same_ip'                => 'no' ,
            'landing_commission_for_same_ip'        => 'no' ,
            'landing_commission_threshold_duration' => array() ,
                ) ;

        /**
         * Class Constructor
         */
        public function __construct() {
            $this->id    = 'fraud_protection' ;
            $this->title = __( 'Fraud Protection' , FS_AFFILIATES_LOCALE ) ;

            parent::__construct() ;
        }

        /*
         * Get settings link
         */

        public function settings_link() {
            return add_query_arg( array( 'page' => 'fs_affiliates' , 'tab' => 'modules' , 'section' => $this->id ) , admin_url( 'admin.php' ) ) ;
        }

        /*
         * Get settings options array
         */

        public function settings_options_array() {
            $settings_fields[] = array(
                'type'  => 'title' ,
                'title' => __( 'Affiliate Login Restrictions' , FS_AFFILIATES_LOCALE ) ,
                'id'    => 'fp_login_restriction' ,
                    ) ;
            $settings_fields[] = array(
                'title'   => __( 'Block Affiliate Account after no. of unsuccessful login attempts' , FS_AFFILIATES_LOCALE ) ,
                'id'      => $this->plugin_slug . '_' . $this->id . '_block_login' ,
                'desc'    => __( 'By enabling this checkbox, you can block affiliate account login for few minutes.' , FS_AFFILIATES_LOCALE ) ,
                'type'    => 'checkbox' ,
                'default' => 'no' ,
                    ) ;
            $settings_fields[] = array(
                'title'             => __( 'Number of Attempts Allowed' , FS_AFFILIATES_LOCALE ) ,
                'id'                => $this->plugin_slug . '_' . $this->id . '_no_of_attempt' ,
                'desc'              => __( 'Specify the number of attempts can the user enter the wrong password.' , FS_AFFILIATES_LOCALE ) ,
                'type'              => 'number' ,
                'default'           => '' ,
                'custom_attributes' => array( 'min' => 0 )
                    ) ;
            $settings_fields[] = array(
                'title'            => __( 'Minimum Duration for Next Login' , FS_AFFILIATES_LOCALE ) ,
                'id'               => $this->plugin_slug . '_' . $this->id . '_min_duration' ,
                'desc'             => __( 'Specify the number of minutes/hours can the user enter the wrong password.' , FS_AFFILIATES_LOCALE ) ,
                'type'             => 'relative_date_selector' ,
                'periods'          => array( 'minutes' => __( 'Minute(s)' , FS_AFFILIATES_LOCALE ) ,
                    'hours'   => __( 'Hour(s)' , FS_AFFILIATES_LOCALE ) ,
                ) ,
                'default'          => array(
                    'number' => '1' ,
                    'unit'   => 'minutes' ,
                ) ,
                'custom_attribute' => array( 'min' => 0 )
                    ) ;
            $settings_fields[] = array(
                'type' => 'sectionend' ,
                'id'   => 'fp_login_restriction' ,
                    ) ;

            $woocommerce                = FS_Affiliates_Integration_Instances::get_integration_by_id( 'woocommerce' ) ;
            $woocommerce_enabled        = fs_affiliates_check_if_woocommerce_is_active() && $woocommerce->is_enabled() ;
            $landing_commission_enabled = FS_Affiliates_Module_Instances::get_module_by_id( 'landing_commissions' )->is_enabled() ;
            if ( $woocommerce_enabled || $landing_commission_enabled ) {
                $settings_fields[] = array(
                    'type'  => 'title' ,
                    'title' => __( 'Referral Restriction Settings' , FS_AFFILIATES_LOCALE ) ,
                    'id'    => 'fp_referral_restriction' ,
                        ) ;
                if ( $woocommerce_enabled ) {
                    $settings_fields[] = array(
                        'title'   => __( 'Stop Awarding Commission to Affiliate when multiple referrals come through the same IP within a specified time [Applicable only for Referral Product Purchase Action]' , FS_AFFILIATES_LOCALE ) ,
                        'id'      => $this->plugin_slug . '_' . $this->id . '_commission_for_same_ip' ,
                        'desc'    => __( 'By enabling this checkbox, you can stop awarding commission when multiple referrals come through same IP.' , FS_AFFILIATES_LOCALE ) ,
                        'type'    => 'checkbox' ,
                        'default' => 'no' ,
                            ) ;
                    $settings_fields[] = array(
                        'title'            => __( 'Time Duration for Threshold' , FS_AFFILIATES_LOCALE ) ,
                        'id'               => $this->plugin_slug . '_' . $this->id . '_threshold_duration' ,
                        'type'             => 'relative_date_selector' ,
                        'periods'          => array( 'minutes' => __( 'Minute(s)' , FS_AFFILIATES_LOCALE ) ,
                            'hours'   => __( 'Hour(s)' , FS_AFFILIATES_LOCALE ) ,
                            'days'    => __( 'Day(s)' , FS_AFFILIATES_LOCALE ) ,
                        ) ,
                        'default'          => array(
                            'number' => '1' ,
                            'unit'   => 'minutes' ,
                        ) ,
                        'custom_attribute' => array( 'min' => 0 )
                            ) ;
                }

                if ( $landing_commission_enabled ) {
                    $settings_fields[] = array(
                        'title'   => __( 'Stop Awarding Commission to Affiliate when multiple referrals come through the same IP within a specified time [Applicable only for Referral Landing Commission Action]' , FS_AFFILIATES_LOCALE ) ,
                        'id'      => $this->plugin_slug . '_' . $this->id . '_landing_commission_for_same_ip' ,
                        'desc'    => __( 'By enabling this checkbox, you can stop awarding commission when multiple referrals come through same IP.' , FS_AFFILIATES_LOCALE ) ,
                        'type'    => 'checkbox' ,
                        'default' => 'no' ,
                            ) ;
                    $settings_fields[] = array(
                        'title'            => __( 'Time Duration for Threshold' , FS_AFFILIATES_LOCALE ) ,
                        'id'               => $this->plugin_slug . '_' . $this->id . '_landing_commission_threshold_duration' ,
                        'type'             => 'relative_date_selector' ,
                        'periods'          => array( 'minutes' => __( 'Minute(s)' , FS_AFFILIATES_LOCALE ) ,
                            'hours'   => __( 'Hour(s)' , FS_AFFILIATES_LOCALE ) ,
                            'days'    => __( 'Day(s)' , FS_AFFILIATES_LOCALE ) ,
                        ) ,
                        'default'          => array(
                            'number' => '1' ,
                            'unit'   => 'minutes' ,
                        ) ,
                        'custom_attribute' => array( 'min' => 0 )
                            ) ;
                }

                $settings_fields[] = array(
                    'type' => 'sectionend' ,
                    'id'   => 'fp_referral_restrictions' ,
                        ) ;
            }

            return $settings_fields ;
        }

        /*
         * Action
         */

        public function actions() {
            add_filter( 'fs_affiliates_commission_from_same_ip' , array( $this , 'stop_commission_from_same_ip' ) , 20 , 3 ) ;
            add_filter( 'fs_affiliates_valid_landing_commission_referral' , array( $this , 'maybe_restrict_landing_commission_referral' ) , 20 , 3 ) ;
            add_filter( 'fs_affiliates_block_unsuccessful_login' , array( $this , 'block_unsuccessful_login' ) , 10 , 1 ) ;
            add_action( 'fp_affiliates_failed_login' , array( $this , 'set_cookie_for_unsuccessful_login' ) ) ;
        }

        public function stop_commission_from_same_ip( $BoolVal , $OrderId , $AffiliateId ) {
            if ( $this->commission_for_same_ip == 'no' )
                return $BoolVal ;

            $OrderObj = new WC_Order( $OrderId ) ;
            if ( ! is_object( $OrderObj ) )
                return $BoolVal ;

            $IPAddrs  = $OrderObj->get_customer_ip_address() ;
            $args     = array(
                'post_type'      => 'shop_order' ,
                'post_status'    => array( 'wc-processing,wc-completed' ) ,
                'fields'         => 'ids' ,
                'meta_query'     => array(
                    'relation' => 'AND' ,
                    array(
                        'key'     => '_customer_ip_address' ,
                        'value'   => $IPAddrs ,
                        'compare' => '=' ,
                    ) ,
                    array(
                        'key'     => 'fs_affiliate_in_order' ,
                        'value'   => $AffiliateId ,
                        'compare' => '=' ,
                    ) ,
                ) ,
                'posts_per_page' => '-1' ,
                'cache_results'  => false
                    ) ;
            $OrderIds = get_posts( $args ) ;
            if ( ! fs_affiliates_check_is_array( $OrderIds ) )
                return $BoolVal ;

            $PrevId       = current( $OrderIds ) ;
            $PrevOrderObj = new WC_Order( $PrevId ) ;
            if ( ! is_object( $PrevOrderObj ) )
                return $BoolVal ;

            $CurrentTimestamp  = $OrderObj->get_date_created()->getTimestamp() ;
            $PrevTimestamp     = $PrevOrderObj->get_date_created()->getTimestamp() ;
            $ThresholdDuration = $this->threshold_duration ;
            $Duration          = strtotime( '+' . $ThresholdDuration[ 'number' ] . '' . $ThresholdDuration[ 'unit' ] , $PrevTimestamp ) ;
            if ( $Duration > $CurrentTimestamp )
                return false ;

            return $BoolVal ;
        }

        /**
         * May be restrict the landing commission referrals.
         *
         * @return boolean
         * 
         */
        public function maybe_restrict_landing_commission_referral( $bool , $landing_commission_id , $affiliate_id ) {
            if ( $this->landing_commission_for_same_ip == 'no' )
                return $bool ;

            $args = array(
                'post_type'      => 'fs-referrals' ,
                'post_status'    => array( 'fs_paid' , 'fs_unpaid' , 'fs_pending' , 'fs_rejected' ) ,
                'author'         => $affiliate_id ,
                'fields'         => 'ids' ,
                'meta_query'     => array(
                    'relation' => 'AND' ,
                    array(
                        'key'     => 'ip_address' ,
                        'value'   => fs_affiliates_get_ip_address() ,
                        'compare' => '=' ,
                    ) ,
                    array(
                        'key'     => 'landing_commission_id' ,
                        'value'   => $landing_commission_id ,
                        'compare' => '=' ,
                    ) ,
                ) ,
                'posts_per_page' => '-1' ,
                'cache_results'  => false
                    ) ;

            $referral_ids = get_posts( $args ) ;
            if ( ! fs_affiliates_check_is_array( $referral_ids ) )
                return $bool ;

            $referral_id = current( $referral_ids ) ;
            $referral    = new FS_Affiliates_Referrals( $referral_id ) ;
            if ( ! is_object( $referral ) )
                return $bool ;

            $current_time  = current_time( 'timestamp' , true ) ;
            $referral_date = strtotime( $referral->get_post()->post_date_gmt ) ;
            $duration      = $this->landing_commission_threshold_duration ;
            $duration      = strtotime( '+' . $duration[ 'number' ] . '' . $duration[ 'unit' ] , $referral_date ) ;
            if ( $duration > $current_time )
                return false ;

            return $bool ;
        }

        public function set_cookie_for_unsuccessful_login() {
            $Duration       = $this->min_duration ;
            $PrevCount      = isset( $_COOKIE[ 'fsblocklogin' ] ) ? base64_decode( $_COOKIE[ 'fsblocklogin' ] ) : 0 ;
            $FailedCount    = $PrevCount + 1 ;
            $cookieValidity = ($Duration[ 'unit' ] == 'minutes') ? time() + ($Duration[ 'number' ] * 60) : time() + ($Duration[ 'number' ] * 3600) ;
            fs_affiliates_setcookie( 'fsblocklogin' , base64_encode( $FailedCount ) , $cookieValidity ) ;

            $_COOKIE[ 'fsblocklogin' ] = base64_encode( $FailedCount ) ;
        }

        public function block_unsuccessful_login() {
            if ( $this->block_login == 'no' )
                return true ;

            if ( empty( $this->no_of_attempt ) )
                return true ;

            if ( ! fs_affiliates_check_is_array( $this->min_duration ) )
                return true ;

            if ( ! isset( $_COOKIE[ 'fsblocklogin' ] ) )
                return true ;

            $FailedCount = base64_decode( $_COOKIE[ 'fsblocklogin' ] ) ;
            if ( $FailedCount > $this->no_of_attempt )
                return false ;

            return true ;
        }

    }

}
    
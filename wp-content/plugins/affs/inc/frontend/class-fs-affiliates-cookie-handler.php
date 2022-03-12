<?php

/**
 *  Handles Cookie
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
if (!class_exists('FS_Affiliates_Cookie_Handler')) {

    /**
     * Class
     */
    class FS_Affiliates_Cookie_Handler {

        /**
         * Plugin slug.
         */
        private static $plugin_slug = 'fs_affiliates';

        /**
         * Class Initialization.
         */
        public static function init() {
            add_action('wp_head', array(__CLASS__, 'set_cookie'));
            add_action('wp_head', array(__CLASS__, 'set_payment_default'));
            add_action('wp_login', array(__CLASS__, 'check_user_after_login'), 10, 2);
        }

        /**
         * set Payment Default
         */
        public static function set_payment_default() {
            $user_id = get_current_user_id();

            $affilate_id = fs_get_affiliate_id_for_user($user_id);
            //Getting Default Payment Gateway
            $payment_preference = get_option('fs_affiliates_payment_preference', array('direct' => 'enable', 'paypal' => 'enable', 'wallet' => 'enable'));

            $default_paymethod = fs_affiliates_get_default_gateway($payment_preference);

            //Settings Default Payment Gateway
            fs_affiliates_settings_default_gateway($affilate_id, $default_paymethod);
        }

        /**
         * set cookie
         */
        public static function set_cookie() {
            try {

                $referral_identifier = fs_get_referral_identifier();
                $cookieValidity = fs_affiliates_get_cookie_validity_value();

                $product = self::get_data_from_url('fsproduct');
                $campaign = self::get_data_from_url('campaign');
                $Affiliate = self::get_data_from_url($referral_identifier);

                $AffiliateID = fs_affiliates_get_affiliateid_from_name($Affiliate);
                $AffiliateID = apply_filters('fs_affiliates_check_is_affiliate', $AffiliateID);

                if (!$AffiliateID)
                    return;

                if (!fs_affiliates_is_affiliate_active($AffiliateID))
                    return;

                do_action('fs_affiliates_before_set_cookie', $AffiliateID, $product, $cookieValidity);

                $encoded_affiliateid = base64_encode($AffiliateID);

                if (isset($_COOKIE['fsaffiliateid']) && $_COOKIE['fsaffiliateid'] == $encoded_affiliateid)
                    throw new Exception('redirect');

                if (!apply_filters('fs_affiliates_check_if_last_referral', false)) {
                    if (isset($_COOKIE['fsaffiliateid']))
                        throw new Exception('redirect');
                }

                if (!self::check_affiliate_restriction()) {
                    return;
                }

                $visit_id = self::create_visit($AffiliateID, $campaign);

                fs_affiliates_setcookie('fsaffiliateid', $encoded_affiliateid, time() + $cookieValidity);
                fs_affiliates_setcookie('fscampaign', base64_encode($campaign), time() + $cookieValidity);
                fs_affiliates_setcookie('fsvisitid', base64_encode($visit_id), time() + $cookieValidity);

                throw new Exception('redirect');
            } catch (Exception $ex) {

                $redirect = self::get_redirect_url();

                if (!$redirect)
                    return;

                wp_safe_redirect($redirect);
                exit();
            }
        }

        /**
         * Get data from url
         */
        public static function get_data_from_url($referral_identifier) {

            if (isset($_GET[$referral_identifier]))
                return $_GET[$referral_identifier];

            $PathofAffiliate = !empty($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';

            if (false === strpos($PathofAffiliate, $referral_identifier . '/'))
                return false;

            $ExplodedUrl = explode('/', $PathofAffiliate);
            $KeyToCheck = array_search($referral_identifier, $ExplodedUrl);
            if (!$KeyToCheck)
                throw new Exception('redirect');

            $AffiliateID = isset($ExplodedUrl[$KeyToCheck + 1]) ? $ExplodedUrl[$KeyToCheck + 1] : false;
            if (!$AffiliateID)
                throw new Exception('redirect');

            return $AffiliateID;
        }

        /**
         * Create visit
         */
        public static function create_visit($affiliate_id, $campaign) {
            $meta_args = array();
            $disable_ip_logging = get_option('fs_affiliates_disable_ip_logging');
            $ip_address = ($disable_ip_logging == 'yes') ? '' : fs_affiliates_get_ip_address();
            $referral_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'Direct Visit';
            $landing_page_url = self::get_redirect_url();

            $meta_args['landing_page'] = (!$landing_page_url) ? get_permalink() : self::get_redirect_url();
            $meta_args['referral_url'] = $referral_url;
            $meta_args['campaign'] = $campaign;
            $meta_args['ip_address'] = fs_affiliates_get_ip_address();
            $meta_args['date'] = time();

            $post_args = array('post_status' => 'fs_notconverted', 'post_author' => $affiliate_id);

            return fs_affiliates_create_new_visit($meta_args, $post_args);
        }

        /**
         * Prepare Redirect URL
         */
        public static function get_redirect_url($redirect = false) {
            $referral_identifier = fs_get_referral_identifier();
            $current_url = FS_AFFILIATES_PROTOCOL . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

            if (isset($_GET[$referral_identifier])) {
                $redirect = remove_query_arg(array($referral_identifier, 'campaign', 'fsproduct'), $current_url);
            } else {
                $PathofAffiliate = !empty($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
                $explode_url = explode('/', $PathofAffiliate);
                $is_pretty_link = array_search($referral_identifier, $explode_url);

                if ($is_pretty_link) {
                    $product = self::get_data_from_url('fsproduct');
                    $campaign = self::get_data_from_url('campaign');
                    $Affiliate = self::get_data_from_url($referral_identifier);
                    $AffiliateID = fs_affiliates_get_affiliateid_from_name($Affiliate);
                    $AffiliateID = apply_filters('fs_affiliates_check_is_affiliate', $AffiliateID);
                    $current_url = str_replace('/' . $referral_identifier . '/' . $AffiliateID, '', $current_url);
                    $current_url = str_replace('/' . $referral_identifier . '/' . $Affiliate, '', $current_url);
                    $current_url = str_replace('/campaign/' . $campaign, '', $current_url);
                    $redirect = str_replace('/fsproduct/' . $product, '', $current_url);
                }
            }

            return $redirect;
        }

        /**
         * Affiliate Restriction for using another affiliate Link
         */
        public static function check_affiliate_restriction($user_id = false) {
            if ('yes' != get_option('fs_affiliates_link_restriction', 'no')) {
                return true;
            }

            $user_id = (!$user_id) ? get_current_user_id() : $user_id;

            if (empty($user_id)) {
                return true;
            }

            $affilate_id = fs_get_affiliate_id_for_user($user_id);

            if ($affilate_id) {
                $referral_identifier = fs_get_referral_identifier();
                $Affiliate = self::get_data_from_url($referral_identifier);
                $AffiliateID = fs_affiliates_get_affiliateid_from_name($Affiliate);
                $AffiliateID = apply_filters('fs_affiliates_check_is_affiliate', $AffiliateID);

                if ( $affilate_id == $AffiliateID && apply_filters( 'fs_affiliates_is_restricted_own_commission' , false ) )
                    return true;
                
                return false;
            }

            return true;
        }
        
        /**
         * Affiliate Restriction for using another affiliate Link
         */
        public static function check_user_after_login($user , $user_obj){
            if(!is_object($user_obj)){
                return;
            }

            if(!self::check_affiliate_restriction($user_obj->ID)){
                $visit_id = fs_affiliates_get_id_from_cookie( 'fsvisitid' );
                
                if($visit_id){
                    wp_delete_post( $visit_id , true ) ; //delete affiliate
                }
                
                fs_affiliates_setcookie('fsaffiliateid', '' , time() - 86400 ) ;
                fs_affiliates_setcookie('fscampaign', '' , time() - 86400 ) ;
                fs_affiliates_setcookie('fsvisitid', '' , time() - 86400 ) ;
            }
        }

    }

    FS_Affiliates_Cookie_Handler::init();
}

<?php

/*
 * Affiliates Data
 */
if ( ! defined ( 'ABSPATH' ) )
    exit ; // Exit if accessed directly.

if ( ! class_exists ( 'FS_Affiliates_Data' ) ) {

    /**
     * FS_Affiliates_Data Class.
     */
    class FS_Affiliates_Data extends FS_Affiliates_Post {

        /**
         * Post Type
         */
        protected $post_type = 'fs-affiliates' ;

        /**
         * Post Status
         */
        protected $post_status = 'fs_active' ;

        /**
         * Meta data keys
         */
        protected $meta_data_keys = array (
            'first_name'                     => '' ,
            'last_name'                      => '' ,
            'campaign'                       => '' ,
            'email'                          => '' ,
            'website'                        => '' ,
            'promotion'                      => '' ,
            'phone_number'                   => '' ,
            'payment_email'                  => '' ,
            'country'                        => '' ,
            'hash'                           => '' ,
            'link_validity'                  => '' ,
            'paid_earnings'                  => '' ,
            'unpaid_earnings'                => '' ,
            'commission_type'                => '' ,
            'referral_code'                  => '' ,
            'commission_value'               => '' ,
            'wc_product_rates'               => '' ,
            'rule_priority'                  => '' ,
            'date'                           => '' ,
            'commission_provided'            => '' ,
            'uploaded_files'                 => array () ,
            'modify_slug'                    => '' ,
            'pushover_key'                   => '' ,
            'device_name'                    => '' ,
            'visit_pushover'                 => 'no' ,
            'referral_pushover'              => 'no' ,
            'payout_pushover'                => 'no' ,
            'landing_pages'                  => '' ,
            'name_label_heading'             => '' ,
            'addr1_label'                    => '' ,
            'addr2_label'                    => '' ,
            'city_label'                     => '' ,
            'state_label'                    => '' ,
            'zip_code_label'                 => '' ,
            'tax_cred_label'                 => '' ,
            'payout_form_status_successfull' => '' ,
            'is_bonus_awarded'               => '' ,
            'signup_visit_id'                => '' ,
            'signup_campaign_id'             => '' ,
                ) ;

        /**
         * Prepare extra post data
         */
        public function load_extra_postdata() {

            $this->parent    = $this->post->post_parent ;
            $this->user_id   = $this->post->post_author ;
            $this->user_name = $this->post->post_title ;
            $this->slug      = $this->post->post_name ;
        }

        /**
         * parent exist
         */
        public function parent_exists() {
            if ( ! $this->parent )
                return false ;

            return get_post_status ( $this->parent ) ;
        }

        /**
         * visits count
         */
        public function get_visits_count() {
            return fs_affiliates_get_visits_count ( $this->id ) ;
        }

        /**
         * referrals count
         */
        public function get_referrals_count() {
            return fs_affiliates_get_referrals_count ( $this->id ) ;
        }

        /**
         * Unpaid Earnings amount
         */
        public function get_unpaid_commission() {
            return fs_affiliates_price ( fs_affiliates_get_referrals_commission ( $this->id ) ) ;
        }

        /**
         *  paid Earnings amount
         */
        public function get_paid_commission() {
            return fs_affiliates_price ( $this->paid_earnings ) ;
        }

        /**
         *  Overall Earnings amount
         */
        public function get_overall_commission() {
            $overall = ( float ) fs_affiliates_get_referrals_commission ( $this->id ) + ( float ) $this->paid_earnings ;

            return fs_affiliates_price ( $overall ) ;
        }

    }

}

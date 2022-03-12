<?php

/**
 * Pretty Affiliate Links
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'FS_Affiliates_Pretty_Affiliate_Links' ) ) {

    /**
     * Class FS_Affiliates_Pretty_Affiliate_Links
     */
    class FS_Affiliates_Pretty_Affiliate_Links extends FS_Affiliates_Modules {

        /**
         * Class Constructor
         */
        public function __construct() {
            $this->id    = 'pretty-affiliate-links' ;
            $this->title = __( 'Pretty Affiliate Links' , FS_AFFILIATES_LOCALE ) ;

            parent::__construct() ;
        }

        /*
         * Action
         */

        public function actions() {
            add_filter( 'fs_affiliates_pretty_referral_link' , array( $this , 'check_if_pretty_referral_enabled' ) , 10 , 1 ) ;
        }

        /*
         * Front End Action
         */

        public function frontend_action() {
            add_filter( 'fs_affiliates_query_vars' , array( $this , 'add_query_var' ) , 10 , 1 ) ;
            add_filter( 'fs_affiliates_link_generator' , array( $this , 'generate_affiliate_link_as_pretty_link' ) , 11 , 6 ) ;
        }

        /*
         * Check If Pretty Referral Link
         */

        public function check_if_pretty_referral_enabled( $bool ) {
            return true ;
        }

        /*
         * add custom query vars
         */

        public function add_query_var( $query_vars ) {

            $query_vars[ fs_get_referral_identifier() ] = EP_PERMALINK | EP_ROOT | EP_COMMENTS | EP_SEARCH | EP_PAGES | EP_ALL_ARCHIVES ;

            return $query_vars ;
        }

        public function generate_affiliate_link_as_pretty_link( $formatted_affiliate_link , $affiliate_link , $ReferralIdentifier , $Identifier , $campaign , $manual = false ) {
            $formatted_affiliate_link = $affiliate_link . '/' . $ReferralIdentifier . '/' . $Identifier ;

            if ( $manual )
                return $formatted_affiliate_link ;

            if ( $campaign )
                $formatted_affiliate_link = $formatted_affiliate_link . '/campaign/' . $campaign ;

            if ( isset( $_POST[ 'product' ] ) && $_POST[ 'product' ] )
                $formatted_affiliate_link = $formatted_affiliate_link . '/fsproduct/' . $_POST[ 'product' ] ;

            return $formatted_affiliate_link ;
        }

    }

}
    
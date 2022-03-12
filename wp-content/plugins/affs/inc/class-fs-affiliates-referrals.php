<?php

/*
 * Affiliates Referrals Data
 */
if ( ! defined( 'ABSPATH' ) )
    exit ; // Exit if accessed directly.

if ( ! class_exists( 'FS_Affiliates_Referrals' ) ) {

    /**
     * FS_Affiliates_Referrals Class.
     */
    class FS_Affiliates_Referrals extends FS_Affiliates_Post {

        /**
         * Post type
         */
        protected $post_type = 'fs-referrals' ;

        /**
         * Post Status
         */
        protected $post_status = 'fs_unpaid' ;

        /**
         * Meta data keys
         */
        protected $meta_data_keys = array(
            'reference'             => '' ,
            'description'           => '' ,
            'campaign'              => '' ,
            'visit_id'              => '' ,
            'amount'                => '' ,
            'type'                  => '' ,
            'date'                  => '' ,
            'rejected_reason'       => '' ,
            'ip_address'            => '' ,
            'landing_commission_id' => '' ,
                ) ;

        /**
         * Prepare extra post data
         */
        public function load_extra_postdata() {

            $this->affiliate = $this->post->post_author ;
        }

    }

}
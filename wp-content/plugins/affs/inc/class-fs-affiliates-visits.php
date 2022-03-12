<?php

/*
 * Affiliates Visits Data
 */
if ( !defined( 'ABSPATH' ) )
    exit ; // Exit if accessed directly.

if ( !class_exists( 'FS_Affiliates_Visits' ) ) {

    /**
     * FS_Affiliates_Visits Class.
     */
    class FS_Affiliates_Visits extends FS_Affiliates_Post {

        /**
         * Post type
         */
        protected $post_type = 'fs-visits' ;

        /**
         * Post status
         */
        protected $post_status = 'fs_notconverted' ;

        /**
         * Meta data keys
         */
        protected $meta_data_keys = array (
            'landing_page' => '' ,
            'referral_url' => '' ,
            'campaign'     => '' ,
            'ip_address'   => '' ,
            'referral_id'  => '' ,
            'date'         => '' ,
                ) ;

        /**
         * Prepare extra post data
         */
        public function load_extra_postdata() {

            $this->affiliate = $this->post->post_author ;
        }

    }

}
<?php

/*
 * Landing Commission
 */
if ( !defined( 'ABSPATH' ) )
    exit ; // Exit if accessed directly.

if ( !class_exists( 'FS_Affiliates_Landing_Commission' ) ) {

    /*
     * FS_Affiliates_Landing_Commission Class.
     */

    class FS_Affiliates_Landing_Commission extends FS_Affiliates_Post {

        /**
         * Post Type
         */
        protected $post_type = 'fs-landingcommission' ;

        /**
         * Post Status
         */
        protected $post_status = 'fs_active' ;

        /**
         * Meta data keys
         */
        protected $meta_data_keys = array (
            'commission_value' => '' ,
            'referral_status'  => '' ,
            'cookie_validity'  => '' ,
            'date'             => '' ,
            'usage_type'      => '1' ,
            'validity_count'    => '' ,
                ) ;

    }

}
    
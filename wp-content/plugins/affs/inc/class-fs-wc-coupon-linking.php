<?php

/*
 * FS_Linked_Affiliates_Data Data
 */
if ( ! defined( 'ABSPATH' ) )
    exit ; // Exit if accessed directly.

if ( ! class_exists( 'FS_Linked_Affiliates_Data' ) ) {

    /*
     * FS_Linked_Affiliates_Data Class.
     */

    class FS_Linked_Affiliates_Data extends FS_Affiliates_Post {

        /**
         * Post Type
         */
        protected $post_type = 'fs-coupon-linking' ;

        /**
         * Post Status
         */
        protected $post_status = 'fs_link' ;

        /**
         * Meta data keys
         */
        protected $meta_data_keys = array(
            'coupon_data'      => '' ,
            'commission_level' => '' ,
            'commission_type'  => '' ,
            'commission_value' => '' ,
                ) ;

        /**
         * Prepare extra post data
         */
        public function load_extra_postdata() {
            $this->post_author = $this->post->post_author ;
        }

    }

}
    
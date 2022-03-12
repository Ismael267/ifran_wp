<?php

/*
 * Affiliates Creatives Data
 */
if ( ! defined( 'ABSPATH' ) )
    exit ; // Exit if accessed directly.

if ( ! class_exists( 'FS_Affiliates_Creatives' ) ) {

    /**
     * FS_Affiliates_Creatives Class.
     */
    class FS_Affiliates_Creatives extends FS_Affiliates_Post {

        /**
         * Post type
         */
        protected $post_type = 'fs-creatives' ;

        /**
         * Post Status
         */
        protected $post_status = 'fs_active' ;

        /**
         * Meta data keys
         */
        protected $meta_data_keys = array(
            'url'                 => '' ,
            'image'               => '' ,
            'description'         => '' ,
            'alternative_text'    => '' ,
            'affiliate_selection' => '1' ,
            'exclude_affiliates'  => array() ,
            'include_affiliates'  => array()
                ) ;

        /**
         * Prepare extra post data
         */
        public function load_extra_postdata() {

            $this->name = $this->post->post_title ;
        }

    }

}
<?php

/*
 * Affiliates Data
 */
if ( !defined( 'ABSPATH' ) )
    exit ; // Exit if accessed directly.

if ( !class_exists( 'FS_URL_Masking_Data' ) ) {

    /**
     * FS_URL_Masking_Data Class.
     */
    class FS_URL_Masking_Data extends FS_Affiliates_Post {

        /**
         * Post Type
         */
        protected $post_type = 'fs-url-masking' ;

        /**
         * Post Status
         */
        //fs_rejected fs_suspended fs_pending_approval fs_approved
        protected $post_status = 'fs_pending_approval' ;

        /**
         * Meta data keys
         */
        protected $meta_data_keys = array (
            'affs_name'          => '' ,
            'affs_id'            => '' ,
            'url_masking_domain' => '' ,
            'domain_visit_count' => '' ,
            'date'               => '' ,
            'status'             => '' ,
                ) ;

    }

}
    
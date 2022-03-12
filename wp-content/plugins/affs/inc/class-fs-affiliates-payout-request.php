<?php

/*
 * Payout Request Data
 */
if ( ! defined( 'ABSPATH' ) )
    exit ; // Exit if accessed directly.

if ( ! class_exists( 'FS_Affiliates_Payout_Request_Data' ) ) {

    /**
     * FS_Affiliates_Payouts Class.
     */
    class FS_Affiliates_Payout_Request_Data extends FS_Affiliates_Post {

        /**
         * Post type
         */
        protected $post_type = 'fs-payout-request' ;

        /**
         * Post Status
         */
        protected $post_status = 'fs_submitted' ;

        /**
         * Meta data keys
         */
        protected $meta_data_keys = array(
            'fs_affiliates_unpaid_commission' => '' ,
                ) ;

    }

}
<?php

/**
 * Referral Commission Threshold
 */
if ( !defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( !class_exists( 'FS_Affiliates_Referral_Commission_Threshold' ) ) {

    /**
     * Class FS_Affiliates_Referral_Commission_Threshold
     */
    class FS_Affiliates_Referral_Commission_Threshold extends FS_Affiliates_Modules {
        /*
         * Data
         */

        protected $data = array (
            'enabled'                    => 'no' ,
            'commission_threshold_value' => '' ,
                ) ;

        /**
         * Class Constructor
         */
        public function __construct() {
            $this->id    = 'referral_commission_threshold' ;
            $this->title = __( 'Referral Commission Threshold' , FS_AFFILIATES_LOCALE ) ;

            parent::__construct() ;
        }

        /*
         * Get settings link
         */

        public function settings_link() {
            return add_query_arg( array ( 'page' => 'fs_affiliates' , 'tab' => 'modules' , 'section' => $this->id ) , admin_url( 'admin.php' ) ) ;
        }

        /*
         * Get settings options array
         */

        public function settings_options_array() {
            return array (
                array (
                    'type'  => 'title' ,
                    'title' => __( 'Referral Commission Threshold' , FS_AFFILIATES_LOCALE ) ,
                    'id'    => 'referral_commission_threshold_options' ,
                ) ,
                array (
                    'title'   => __( 'Referral Commission Threshold Value' , FS_AFFILIATES_LOCALE ) ,
                    'desc'    => __( 'This option controls the maximum commission that an affiliate can receive for one referral. If the referral amount exceeds this threshold, the referral will be kept on <b>"Pending"</b> status for admin review.' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->plugin_slug . '_' . $this->id . '_commission_threshold_value' ,
                    'type'    => 'price' ,
                    'default' => '' ,
                ) ,
                array (
                    'type' => 'sectionend' ,
                    'id'   => 'referral_commission_threshold_options' ,
                ) ,
                    ) ;
        }

        /*
         * Action
         */

        public function actions() {
            add_filter( 'fs_affiliates_referral_commission_threshold' , array ( $this , 'referral_commission_threshold' ) , 10 , 2 ) ;
        }

        /*
         * Action
         */

        public function referral_commission_threshold( $status , $amount ) {
            if ( !$this->commission_threshold_value )
                return $status ;

            if ( ( float ) $this->commission_threshold_value < ( float ) $amount )
                return 'fs_pending' ;

            return $status ;
        }

    }

}
    
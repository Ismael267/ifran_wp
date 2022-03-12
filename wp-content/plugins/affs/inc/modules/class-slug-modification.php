<?php

/**
 * Affiliate Slug Modification
 */
if ( !defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( !class_exists( 'FS_Affiliates_Slug_Modification' ) ) {

    /**
     * Class FS_Affiliates_Slug_Modification
     */
    class FS_Affiliates_Slug_Modification extends FS_Affiliates_Modules {
        /*
         * Data
         */

        protected $data = array (
            'enabled'                   => 'no' ,
            'allowed_affiliates_method' => '1' ,
            'selected_affiliates'       => array () ,
                ) ;

        /**
         * Class Constructor
         */
        public function __construct() {
            $this->id    = 'slug_modification' ;
            $this->title = __( 'Affiliate Slug Modification' , FS_AFFILIATES_LOCALE ) ;

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
                    'title' => __( 'Affiliate Slug Modification' , FS_AFFILIATES_LOCALE ) ,
                    'id'    => 'slug_modification_options' ,
                ) ,
                array (
                    'title'   => __( 'Allowed Affiliates' , FS_AFFILIATES_LOCALE ) ,
                    'desc'    => __( 'This option controls the list of affiliates who can customize their affiliate slug' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->plugin_slug . '_' . $this->id . '_allowed_affiliates_method' ,
                    'type'    => 'select' ,
                    'class'   => 'fs_affiliates_allowed_affiliates_method' ,
                    'default' => '1' ,
                    'options' => array (
                        '1' => __( 'All Affiliates' , FS_AFFILIATES_LOCALE ) ,
                        '2' => __( 'Selected Affiliates' , FS_AFFILIATES_LOCALE ) ,
                    ) ,
                ) ,
                array (
                    'title'     => __( 'Select Affiliates' , FS_AFFILIATES_LOCALE ) ,
                    'id'        => $this->plugin_slug . '_' . $this->id . '_selected_affiliates' ,
                    'type'      => 'ajaxmultiselect' ,
                    'class'     => 'fs_affiliates_selected_affiliate' ,
                    'list_type' => 'affiliates' ,
                    'action'    => 'fs_affiliates_search' ,
                    'default'   => array () ,
                ) ,
                array (
                    'type' => 'sectionend' ,
                    'id'   => 'slug_modification_options' ,
                ) ,
                    ) ;
        }

        /*
         * Action
         */

        public function actions() {
            add_filter( 'fs_affiliate_check_if_slug_modification_enabled' , array ( $this , 'check_if_slug_modification_enabled' ) , 10 , 2 ) ;
            add_filter( 'fs_affiliates_slug_for_affiliate' , array ( $this , 'get_modified_slug' ) , 10 , 2 ) ;
        }

        /*
         * Check If Slug Modification Enabled
         */

        public function check_if_slug_modification_enabled( $bool , $AffiliateId ) {
            if ( $this->allowed_affiliates_method == '1' ) {
                return true ;
            } else {
                if ( !fs_affiliates_check_is_array( $this->selected_affiliates ) )
                    return false ;

                if ( in_array( $AffiliateId , $this->selected_affiliates ) )
                    return true ;
            }
            return false ;
        }

        /*
         * Get Modified Slug
         */

        public function get_modified_slug( $Identifier , $AffiliateData ) {
            if ( $AffiliateData->modify_slug == 'yes' )
                return $AffiliateData->slug ;

            return $Identifier ;
        }

    }

}
    
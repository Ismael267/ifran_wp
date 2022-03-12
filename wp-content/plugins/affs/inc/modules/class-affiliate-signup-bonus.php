<?php

/**
 * Refer a Friend
 */
if ( ! defined ( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( ! class_exists ( 'FS_Affiliates_Signup_Bonus_Module' ) ) {

    /**
     * Class FS_Affiliates_Signup_Bonus_Module
     */
    class FS_Affiliates_Signup_Bonus_Module extends FS_Affiliates_Modules {
        /*
         * Data
         */

        protected $data = array (
            'enabled' => 'no' ,
            'value'   => '' ,
                ) ;

        /**
         * Class Constructor
         */
        public function __construct() {
            $this->id    = 'signup_bonus' ;
            $this->title = __ ( 'Affiliate Signup Bonus' , FS_AFFILIATES_LOCALE ) ;

            parent::__construct () ;
        }

        /*
         * Get settings link
         */

        public function settings_link() {
            return add_query_arg ( array ( 'page' => 'fs_affiliates' , 'tab' => 'modules' , 'section' => $this->id ) , admin_url ( 'admin.php' ) ) ;
        }

        /*
         * Get settings options array
         */

        public function settings_options_array() {
            return array (
                array (
                    'type'  => 'title' ,
                    'title' => __ ( 'Affiliate Signup Bonus Settings' , FS_AFFILIATES_LOCALE ) ,
                    'id'    => 'fs_affs_signup_bonus_section' ,
                ) ,
                array (
                    'title'   => __ ( 'Signup Bonus for Affiliates' , FS_AFFILIATES_LOCALE ) ,
                    'desc'    => __ ( 'The commission amount set in this field will be awarded to users when they successfully became an affiliate' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->plugin_slug . '_' . $this->id . '_value' ,
                    'type'    => 'price' ,
                    'default' => '' ,
                ) ,
                array (
                    'type' => 'sectionend' ,
                    'id'   => 'fs_affs_signup_bonus_section' ,
                ) ,
                    ) ;
        }

        /*
         * Actions
         */

        public function actions() {
            add_action ( 'fs_affiliates_status_changed_to_fs_active' , array ( $this , 'trigger' ) , 10 , 2 ) ;
            add_action ( 'fs_affiliates_status_changed_new_to_fs_active' , array ( $this , 'trigger' ) , 10 ) ;
        }

        /**
         * Trigger the sending of this email.
         */
        public function trigger( $affiliate_id , $affiliate = false ) {
            if ( $affiliate_id && ! is_a ( $affiliate , 'FS_Affiliates_Data' ) ) {
                $affiliate = new FS_Affiliates_Data ( $affiliate_id ) ;
            }

            if ( $affiliate->is_bonus_awarded == 'yes' ) {
                return ;
            }

            $time_now  = time () ;
            $meta_data = array (
                'reference'   => $affiliate_id ,
                'description' => get_option ( 'fs_affiliates_referral_desc_signup_bonus_label' , 'Sign Up Bonus' ) ,
                'campaign'    => ' - ' ,
                'amount'      => $this->value ,
                'type'        => 'Opt-in' ,
                'date'        => $time_now ,
                'is_mlm'      => 'no' ,
                    ) ;

            fs_affiliates_create_new_referral ( $meta_data , array ( 'post_author' => $affiliate_id ) ) ;
            $affiliate->update_meta ( 'is_bonus_awarded' , 'yes' ) ;
        }

    }

}
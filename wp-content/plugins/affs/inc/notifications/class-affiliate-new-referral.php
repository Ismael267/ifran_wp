<?php

/**
 * Affiliate New Referral
 */
if ( !defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( !class_exists( 'FS_Affiliates_New_Referral' ) ) {

    /**
     * Class FS_Affiliates_New_Referral
     */
    class FS_Affiliates_New_Referral extends FS_Affiliates_Notifications {

        /**
         * Class Constructor
         */
        public function __construct() {
            $this->id    = 'affiliate_new_referral' ;
            $this->title = __( 'Affiliate - Affiliate New Referral' , FS_AFFILIATES_LOCALE ) ;

            // Triggers for this email.
            add_action( 'fs_affiliates_refferal_status_changed_new_to_fs_unpaid' , array ( $this , 'trigger' ) , 10 , 2 ) ;
            add_action( 'fs_affiliates_refferal_status_changed_to_fs_unpaid' , array ( $this , 'trigger' ) , 10 , 2 ) ;

            parent::__construct() ;
        }

        /*
         * Default Subject
         */

        public function get_default_subject() {

            return __( '{site_name} - New Referral Earned' , FS_AFFILIATES_LOCALE ) ;
        }

        /*
         * Default Message
         */

        public function get_default_message() {

            return __( 'Hi,

                        You have earned a new Referral for your Affiliate Account{affiliate_name} on {site_name}. The Referral Details are as Follows

                       Referral Details
                        
                       Referral Description: {referral_description}         
                       Referral Referrence:  {referral_referrence} 
                       Referral Amount: {referral_amount}     
                       Referral Status:   {referral_status}                   

                       Thanks.' , FS_AFFILIATES_LOCALE ) ;
        }

        /*
         * Default SMS Message
         */

        public function get_sms_default_message() {

            return __( 'You have earned a new Referral for your Affiliate Account{affiliate_name} on {site_name}. The Referral Details are as Follows

                       Referral Details
                        
                       Referral Description: {referral_description}         
                       Referral Referrence:  {referral_referrence} 
                       Referral Amount: {referral_amount}     
                       Referral Status:   {referral_status}' , FS_AFFILIATES_LOCALE ) ;
        }

        /*
         * Get settings link
         */

        public function settings_link() {
            return add_query_arg( array ( 'page' => 'fs_affiliates' , 'tab' => 'notifications' , 'section' => $this->id ) , admin_url( 'admin.php' ) ) ;
        }

        /**
         * Trigger the sending of this email.
         */
        public function trigger( $referral_id , $referral = false ) {
            if ( $referral_id && !is_a( $referral , 'FS_Affiliates_Referrals' ) ) {
                $referral = new FS_Affiliates_Referrals( $referral_id ) ;
            }

            if ( is_a( $referral , 'FS_Affiliates_Referrals' ) ) {
                $affiliate                                      = new FS_Affiliates_Data( $referral->affiliate ) ;
                $this->recipient                                = $affiliate->email ;
                $this->sms_recipient                            = $affiliate->phone_number ;
                $this->placeholders[ '{affiliate_name}' ]       = $affiliate->user_name ;
                $this->placeholders[ '{referral_status}' ]      = fs_affiliates_get_status_display( $referral->get_status() ) ;
                $this->placeholders[ '{referral_referrence}' ]  = $referral->reference ;
                $this->placeholders[ '{referral_description}' ] = $referral->description ;
                $this->placeholders[ '{referral_amount}' ]      = $referral->amount ;
            }

            if ( $this->is_email_enabled() && $this->get_recipient() ) {
                $this->send_email( $this->get_recipient() , $this->get_subject() , $this->get_message() , $this->get_headers() , $this->get_attachments() ) ;
            }

            if ( $this->is_sms_enabled() && $this->get_sms_recipient() ) {

                $this->send_sms( $this->get_sms_recipient() , $this->get_sms_message() ) ;
            }
        }

        /*
         * Get settings options array
         */

        public function settings_options_array() {
            $settings = array () ;

            $settings[] = array (
                'type'  => 'title' ,
                'title' => __( 'Email Settings' , FS_AFFILIATES_LOCALE ) ,
                'id'    => 'affiliate_new_referral_options' ,
                    ) ;
            $settings[] = array (
                'title'   => __( 'Send Affiliate New Referral Email to Affiliate' , FS_AFFILIATES_LOCALE ) ,
                'id'      => $this->plugin_slug . '_' . $this->id . '_email_enabled' ,
                'type'    => 'checkbox' ,
                'default' => '' ,
                    ) ;
            $settings[] = array (
                'title'   => __( 'Email Subject' , FS_AFFILIATES_LOCALE ) ,
                'id'      => $this->plugin_slug . '_' . $this->id . '_subject' ,
                'type'    => 'text' ,
                'default' => $this->get_default_subject() ,
                    ) ;
            $settings[] = array (
                'title'   => __( 'Email Message' , FS_AFFILIATES_LOCALE ) ,
                'id'      => $this->plugin_slug . '_' . $this->id . '_message' ,
                'type'    => 'wpeditor' ,
                'default' => $this->get_default_message() ,
                    ) ;
            $settings[] = array (
                'type' => 'sectionend' ,
                'id'   => 'affiliate_new_referral_options' ,
                    ) ;

            if ( $this->sms_module_enabled() ) {

                $settings[] = array (
                    'type'  => 'title' ,
                    'title' => __( 'SMS Settings' , FS_AFFILIATES_LOCALE ) ,
                    'id'    => 'affiliate_new_referral_sms_options' ,
                        ) ;
                $settings[] = array (
                    'title'   => __( 'Send Affiliate New Referral SMS to Affiliate' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->plugin_slug . '_' . $this->id . '_sms_enabled' ,
                    'type'    => 'checkbox' ,
                    'default' => '' ,
                        ) ;
                $settings[] = array (
                    'title'   => __( 'SMS Message' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->plugin_slug . '_' . $this->id . '_sms_message' ,
                    'type'    => 'wpeditor' ,
                    'default' => $this->get_sms_default_message() ,
                        ) ;
                $settings[] = array (
                    'type' => 'sectionend' ,
                    'id'   => 'affiliate_new_referral_sms_options' ,
                        ) ;
            }

            return $settings ;
        }

    }

}
    
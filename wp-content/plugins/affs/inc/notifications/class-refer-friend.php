<?php

/**
 * Admin Completed Affiliate Registration
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'FS_Affiliates_Refer_friend' ) ) {

    /**
     * Class FS_Affiliates_Refer_friend
     */
    class FS_Affiliates_Refer_friend extends FS_Affiliates_Notifications {
        /*
         * From name
         */

        protected $from_name  = '' ;
        /*
         * From Email
         */
        protected $from_email = '' ;

        /**
         * Class Constructor
         */
        public function __construct() {

            // Triggers for this email.
            add_action( 'fs_affiliates_send_refer_a_friend_mail' , array( $this , 'trigger' ) , 10 , 1 ) ;

            parent::__construct() ;
        }

        /**
         * Get the from name
         */
        public function get_from_name() {

            return wp_specialchars_decode( esc_html( $this->from_name ) , ENT_QUOTES ) ;
        }

        /**
         * Get the from address
         */
        public function get_from_address() {

            return sanitize_email( $this->from_email ) ;
        }

        /**
         * Trigger the sending of this email.
         */
        public function trigger( $affiliate_id , $affiliate = false ) {

            if ( $affiliate_id && ! is_a( $affiliate , 'FS_Affiliates_Data' ) ) {
                $affilate_datas = new FS_Affiliates_Data( $affiliate_id ) ;
            }
            $i                  = 0 ;
            $allemails          = isset( $_POST[ 'refer_mails' ] ) ? $_POST[ 'refer_mails' ] : '' ;
            $refer_mail_subject = isset( $_POST[ 'refer_mail_subject' ] ) ? $_POST[ 'refer_mail_subject' ] : '' ;
            $refer_mail_content = isset( $_POST[ 'refer_mail_content' ] ) ? $_POST[ 'refer_mail_content' ] : '' ;
            $this->from_name    = ( isset( $affilate_datas->first_name ) || isset( $affilate_datas->last_name ) ) ? $affilate_datas->first_name . ' ' . $affilate_datas->last_name : $affilate_datas->user_name ;
            $this->from_email   = isset( $affilate_datas->email ) ? $affilate_datas->email : '' ;

            $exploded_mails = explode( "," , $allemails ) ;

            $refer_mail_content = wpautop ( stripslashes ( $refer_mail_content ) ) ;

            foreach ( $exploded_mails as $email ) {

                $this->recipient = $email ;

                if ( $this->get_recipient() ) {
                    $this->send_email( $this->get_recipient() , $refer_mail_subject , $refer_mail_content , $this->get_headers() , $this->get_attachments() ) ;
                    $i ++ ;
                }
            }

            echo $i ;
        }

        /*
         * Current affiliate link
         */

        public function affiliate_link() {
            $url                      = get_option('fs_affiliates_refer_friend_referral_url' , site_url() );
            $AffiliateURL             = empty($url) ? site_url() : $url;
            $ReferralIdentifier       = fs_get_referral_identifier() ;
            $ReferralIdFormat         = get_option( 'fs_affiliates_referral_id_format' ) ;
            $UserId                   = get_current_user_id() ;
            $AffiliateId              = fs_get_affiliate_id_for_user( $UserId ) ;
            $AffiliateData            = new FS_Affiliates_Data( $AffiliateId ) ;
            $Identifier               = $ReferralIdFormat == 'name' ? $AffiliateData->user_name : $AffiliateId ;
            $Identifier               = apply_filters( 'fs_affiliates_slug_for_affiliate' , $Identifier , $AffiliateData ) ;
            $formatted_affiliate_link = add_query_arg( $ReferralIdentifier , $Identifier , $AffiliateURL ) ;

            return apply_filters( 'fs_affiliates_link_generator' , $formatted_affiliate_link , $AffiliateURL , $ReferralIdentifier , $Identifier , false , true ) ;
        }

    }

}
    
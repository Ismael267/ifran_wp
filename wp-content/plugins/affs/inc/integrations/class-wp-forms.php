<?php

/**
 * WP Forms
 */
if ( !defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( !class_exists( 'FS_Affiliates_WP_Forms' ) ) {

    /**
     * Class FS_Affiliates_WP_Forms
     */
    class FS_Affiliates_WP_Forms extends FS_Affiliates_Integrations {

        /**
         * Class Constructor
         */
        public function __construct() {
            $this->id    = 'wp_forms' ;
            $this->title = __( 'WP Forms' , FS_AFFILIATES_LOCALE ) ;

            parent::__construct() ;
        }

        /*
         * is enabled
         */

        public function is_enabled() {

            return $this->is_plugin_enabled() && 'yes' === $this->enabled ;
        }

        /*
         * Admin Actions
         */

        public function admin_action() {
            add_action( 'wpforms_form_settings_general' , array ( $this , 'wp_forms_settings' ) , 1 ) ;
        }

        /*
         * Plugin enabled
         */

        public function is_plugin_enabled() {
            if ( class_exists( 'WPForms' ) ) {
                return true ;
            }
        }

        /*
         * Frontend Actions
         */

        public function frontend_action() {

            add_action( 'wpforms_process_complete' , array ( $this , 'wp_forms_confirmation' ) , 10 , 4 ) ;
        }

        public function wp_forms_confirmation( $fields , $entry , $form_data , $entry_id ) {

            $form_settings = isset( $form_data[ 'settings' ] ) ? $form_data[ 'settings' ] : '' ;

            if ( empty( $form_settings ) || !is_array( $form_settings ) ) {
                return ;
            }

            $form_id          = $form_data[ 'id' ] ;
            $time_now         = time() ;
            $format_reference = $form_id . ' - ' . $time_now ;

            $is_wp_referrals_enable = isset( $form_settings[ 'fs_affiliates_wp_referrals_enable' ] ) ? $form_settings[ 'fs_affiliates_wp_referrals_enable' ] : '' ;

            if ( $is_wp_referrals_enable != 1 || fs_affiliates_get_id_from_cookie( 'fsaffiliateid' ) == '0' ) {
                return ;
            }

            $fs_affiliates_wp_referral_type    = isset( $form_settings[ 'fs_affiliates_wp_referral_type' ] ) ? $form_settings[ 'fs_affiliates_wp_referral_type' ] : '' ;
            $fs_affiliates_wp_commission_value = isset( $form_settings[ 'fs_affiliates_wp_commission_value' ] ) ? $form_settings[ 'fs_affiliates_wp_commission_value' ] : '' ;
            $affiliate_id                      = fs_affiliates_get_id_from_cookie( 'fsaffiliateid' ) ;

            $meta_data = array (
                'reference'   => $format_reference ,
                'description' => get_option( 'fs_affiliates_referral_desc_wp_form_label','WP Forms' ) ,    
                'amount'      => $fs_affiliates_wp_commission_value ,
                'type'        => $fs_affiliates_wp_referral_type ,
                'date'        => time() ,
                'visit_id'    => fs_affiliates_get_id_from_cookie( 'fsvisitid' ) ,
                'campaign'    => fs_affiliates_get_id_from_cookie( 'fscampaign' , '' ) ,
                    ) ;

            fs_affiliates_create_new_referral( $meta_data , array ( 'post_author' => $affiliate_id ) ) ;
        }

        public function wp_forms_settings( $thisformdata ) {
            echo '<div class="wpforms-panel-content-section-title">' ;
            echo __( 'Sumo Affiliate Settings' , 'wpforms' ) ;
            echo '</div>' ;
            wpforms_panel_field(
                    'checkbox' , 'settings' , 'fs_affiliates_wp_referrals_enable' , $thisformdata->form_data , esc_html__( 'Enable Allow Referrals' , 'wpforms' )
            ) ;
            wpforms_panel_field(
                    'select' , 'settings' , 'fs_affiliates_wp_referral_type' , $thisformdata->form_data , esc_html__( 'Referral Type' , 'wpforms' ) , array (
                'default' => '1' ,
                'options' => array (
                    'sale'   => esc_html__( 'Sale' , 'wpforms' ) ,
                    'opt-in' => esc_html__( 'Opt-In' , 'wpforms' ) ,
                    'lead'   => esc_html__( 'Lead' , 'wpforms' ) ,
                ) ,
                'class'   => 'fs_affiliates_wp_referrals_content' ,
                    )
            ) ;
            wpforms_panel_field(
                    'text' , 'settings' , 'fs_affiliates_wp_commission_value' , $thisformdata->form_data , esc_html__( 'Commission Value' , 'wpforms' ) , array ( 'class' => 'fs_affiliates_wp_referrals_content' )
            ) ;
        }

    }

}
    
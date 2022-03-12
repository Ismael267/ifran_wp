<?php

/**
 * Landing Commissions
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'FS_Affiliates_Landing_Commissions' ) ) {

    /**
     * Class FS_Affiliates_Landing_Commissions
     */
    class FS_Affiliates_Landing_Commissions extends FS_Affiliates_Modules {

        /**
         * Class Constructor
         */
        public function __construct() {
            $this->id    = 'landing_commissions' ;
            $this->title = __( 'Landing Commission' , FS_AFFILIATES_LOCALE ) ;

            parent::__construct() ;
        }

        /*
         * Get settings link
         */

        public function settings_link() {
            return add_query_arg( array( 'page' => 'fs_affiliates' , 'tab' => 'modules' , 'section' => $this->id ) , admin_url( 'admin.php' ) ) ;
        }

        /**
         * Output the settings buttons.
         */
        public function output_buttons() {
            
        }

        /**
         * Get settings array.
         */
        public function settings_options_array() {

            return array(
                array(
                    'type' => 'output_landing_commissions'
                ) ,
                    ) ;
        }

        /**
         * Output the affiliates
         */
        public function output_landing_commissions() {
            global $current_sub_section ;

            switch ( $current_sub_section ) {
                case 'new' :
                    $this->display_new_page() ;
                    break ;
                case 'edit' :
                    $this->display_edit_page() ;
                    break ;
                default :
                    $this->display_table() ;
                    break ;
            }
        }

        /*
         * Admin Actions
         */

        public function admin_action() {
            add_action( $this->plugin_slug . '_admin_field_output_landing_commissions' , array( $this , 'output_landing_commissions' ) ) ;
        }

        /*
         * Frontend Actions
         */

        public function frontend_action() {
            add_shortcode( 'fs-landing-commission' , array( $this , 'process_shortcode' ) ) ;
        }

        /*
         * Process Shortcode
         */

        public function process_shortcode( $atts , $content , $tag ) {
            if ( ! isset( $atts[ 'id' ] ) )
                return $content ;

            $landing_commission_object = new FS_Affiliates_Landing_Commission( $atts[ 'id' ] ) ;

            if ( ! $landing_commission_object->exists() || ! $landing_commission_object->has_status( 'fs_active' ) )
                return $content ;

            $cookie_key   = 'fslandingcommission_' . $atts[ 'id' ] ;
            $affiliate_id = fs_affiliates_get_id_from_cookie( 'fsaffiliateid' ) ;

            if ( empty( $affiliate_id ) )
                return $content ;

            if ( fs_affiliates_get_id_from_cookie( $cookie_key ) )
                return $content ;

            if ( ! apply_filters( 'fs_affiliates_valid_landing_commission_referral' , true , $landing_commission_object->get_id() , $affiliate_id ) )
                return $content ;

            // Landing Commission Shortcode usage restriction
            if ( '2' == $landing_commission_object->usage_type ) {
                $usage_count = $landing_commission_object->validity_count ;
                $used_count  = empty( get_post_meta( $landing_commission_object->get_id() , 'fs_affs_lc_used_count' , true ) ) ? 0 : get_post_meta( $landing_commission_object->get_id() , 'fs_affs_lc_used_count' , true ) ;

                if ( $used_count < $usage_count ) {
                    update_post_meta( $landing_commission_object->get_id() , 'fs_affs_lc_used_count' , $used_count + 1 ) ;
                } else {
                    return $content ;
                }
            }

            $visit_id     = fs_affiliates_get_id_from_cookie( 'fsvisitid' ) ;
            $campaign_id  = fs_affiliates_get_id_from_cookie( 'fscampaign' , '' ) ;
            $current_time = time() ;

            $ReferralData                            = array() ;
            $ReferralData[ 'type' ]                  = 'lead' ;
            $ReferralData[ 'amount' ]                = fs_affiliates_format_decimal( $landing_commission_object->commission_value , true ) ;
            $ReferralData[ 'description' ]           = get_option( 'fs_affiliates_referral_desc_landing_commission_label' , 'Landing Commission' ) ;
            $ReferralData[ 'reference' ]             = get_the_ID() . '-' . $current_time ;
            $ReferralData[ 'date' ]                  = $current_time ;
            $ReferralData[ 'visit_id' ]              = ($visit_id) ? $visit_id : '' ;
            $ReferralData[ 'campaign' ]              = $campaign_id ;
            $ReferralData[ 'landing_commission_id' ] = $landing_commission_object->get_id() ;

            $post_array = array( 'post_status' => $landing_commission_object->referral_status , 'post_author' => $affiliate_id ) ;

            $referral_id = fs_affiliates_create_new_referral( $ReferralData , $post_array ) ;

            if ( ! $referral_id )
                return $content ;

            $validity = fs_affiliates_get_cookie_validity_value( $landing_commission_object->cookie_validity ) ;

            $cookie_value = base64_encode( $affiliate_id . '-' . $referral_id ) ;

            fs_affiliates_setcookie( $cookie_key , $cookie_value , time() + $validity ) ;

            return $content ;
        }

        /*
         * Display Table
         */

        public function display_table() {
            if ( ! class_exists( 'FS_Affiliates_Landing_Commissions_Post_Table' ) ) {
                require_once( FS_AFFILIATES_PLUGIN_PATH . '/inc/admin/menu/wp-list-table/class-fs-affiliates-landing-commissions-table.php' ) ;
            }

            $new_section_url = add_query_arg( array( 'page' => 'fs_affiliates' , 'tab' => 'modules' , 'section' => $this->id , 'subsection' => 'new' ) , admin_url( 'admin.php' ) ) ;
            echo '<div class="' . $this->plugin_slug . '_table_wrap">' ;
            echo '<h2 class="wp-heading-inline">' . __( 'Landing Commission' , FS_AFFILIATES_LOCALE ) . '</h2>' ;

            echo '<a class="page-title-action ' . $this->plugin_slug . '_add_btn" href="' . $new_section_url . '">' . __( 'Add New Commission' , FS_AFFILIATES_LOCALE ) . '</a>' ;
            if ( isset( $_REQUEST[ 's' ] ) && strlen( $_REQUEST[ 's' ] ) ) {
                /* translators: %s: search keywords */
                printf( ' <span class="subtitle">' . __( 'Search results for &#8220;%s&#8221;' ) . '</span>' , $_REQUEST[ 's' ] ) ;
            }

            $post_table = new FS_Affiliates_Landing_Commissions_Post_Table() ;
            $post_table->prepare_items() ;
            $post_table->views() ;
            $post_table->display() ;
            echo '</div>' ;
        }

        /**
         * Output the new affiliate page
         */
        public function display_new_page() {

            include_once( FS_AFFILIATES_PLUGIN_PATH . '/inc/admin/menu/views/landing-commission-new.php' ) ;
        }

        /**
         * Output the edit affiliate page
         */
        public function display_edit_page() {
            if ( ! isset( $_GET[ 'id' ] ) )
                return ;

            $post_id     = $_GET[ 'id' ] ;
            $post_object = new FS_Affiliates_Landing_Commission( $post_id ) ;

            include_once( FS_AFFILIATES_PLUGIN_PATH . '/inc/admin/menu/views/landing-commission-edit.php' ) ;
        }

        /**
         * Save settings.
         */
        public function before_save() {

            if ( ! empty( $_POST[ 'new_landing_commission' ] ) ) {
                $this->save_new_landing_commission() ;
            } elseif ( ! empty( $_POST[ 'edit_landing_commission' ] ) ) {
                $this->update_landing_commission() ;
            }
        }

        /*
         * Link a new Landing Commission
         */

        public function save_new_landing_commission() {
            global $current_sub_section ;
            if ( $current_sub_section == '' )
                return ;

            check_admin_referer( $this->plugin_slug . '_new_landing_commission' , '_' . $this->plugin_slug . '_nonce' ) ;

            try {
                $meta_data = $_POST[ 'landing_commission' ] ;

                if ( ! isset( $meta_data[ 'cookie_validity' ] [ 'number' ] ) )
                    throw new Exception( __( 'Cookie validity cannot be empty' , FS_AFFILIATES_LOCALE ) ) ;

                $post_args                       = array( 'post_status' => $meta_data[ 'status' ] ) ;
                $meta_data[ 'date' ]             = time() ;
                $meta_data[ 'commission_value' ] = fs_affiliates_format_decimal( $meta_data[ 'commission_value' ] , true ) ;

                fs_affiliates_create_new_landing_commission( $meta_data , $post_args ) ;

                unset( $_POST[ 'landing_commission' ] ) ;

                FS_Affiliates_Settings::add_message( __( 'Landing Commission has been created successfully.' , FS_AFFILIATES_LOCALE ) ) ;
            } catch ( Exception $ex ) {
                FS_Affiliates_Settings::add_error( $ex->getMessage() ) ;
            }
        }

        /*
         * Update a Landing Commission
         */

        public function update_landing_commission() {
            global $current_sub_section ;
            if ( $current_sub_section == '' )
                return ;

            check_admin_referer( $this->plugin_slug . '_edit_landing_commission' , '_' . $this->plugin_slug . '_nonce' ) ;

            try {
                $meta_data = $_POST[ 'landing_commission' ] ;
                if ( ! isset( $meta_data[ 'cookie_validity' ] [ 'number' ] ) )
                    throw new Exception( __( 'Cookie validity cannot be empty' , FS_AFFILIATES_LOCALE ) ) ;

                $post_args = array(
                    'post_status' => $meta_data[ 'status' ] ,
                        ) ;

                $meta_data[ 'commission_value' ] = fs_affiliates_format_decimal( $meta_data[ 'commission_value' ] , true ) ;

                //update landing Commission
                fs_affiliates_update_landing_commission( $meta_data[ 'id' ] , $meta_data , $post_args ) ;

                unset( $_POST[ 'landing_commission' ] ) ;

                FS_Affiliates_Settings::add_message( __( 'Landing Commission has been updated successfully.' , FS_AFFILIATES_LOCALE ) ) ;
            } catch ( Exception $ex ) {
                FS_Affiliates_Settings::add_error( $ex->getMessage() ) ;
            }
        }

    }

}   

<?php
/**
 * Contact Forms 7
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'FS_Affiliates_Contact_Forms' ) ) {

    /**
     * Class FS_Affiliates_Contact_Forms
     */
    class FS_Affiliates_Contact_Forms extends FS_Affiliates_Integrations {

        /**
         * Class Constructor
         */
        public function __construct() {
            $this->id    = 'contact_forms' ;
            $this->title = __( 'Contact Form 7' , FS_AFFILIATES_LOCALE ) ;

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
            add_action( 'wpcf7_save_contact_form' , array( $this , 'save_contact_form' ) , 1 , 3 ) ;
            add_filter( 'wpcf7_editor_panels' , array( $this , 'adding_affiliate_panel' ) , 1 ) ;
        }

        public function actions() {
            add_filter( 'wpcf7_contact_form_properties' , array( $this , 'contact_form_properties' ) , 10 , 1 ) ;
        }

        /*
         * Plugin enabled
         */

        public function is_plugin_enabled() {
            if ( class_exists( 'WPCF7_ContactForm' ) ) {
                return true ;
            }
        }

        /*
         * Frontend Actions
         */

        public function frontend_action() {
            add_action( 'wpcf7_submit' , array( $this , 'form_after_success' ) , 2 , 2 ) ;
        }

        public function contact_form_properties( $properties ) {

            $properties = wp_parse_args( $properties , array(
                'form'                => '' ,
                'mail'                => array() ,
                'mail_2'              => array() ,
                'messages'            => array() ,
                'additional_settings' => '' ,
                'affiliate_settings'  => '' ,
                    ) ) ;

            return $properties ;
        }

        public function adding_affiliate_panel( $panels ) {
            $panels[ 'sumo-affiliate-settings-panel' ] = array(
                'title'    => __( 'Affiliate Settings' , 'contact-form-7' ) ,
                'callback' => array( $this , 'cf_forms_settings' ) ,
                    ) ;
            return $panels ;
        }

        public function cf_forms_settings( $post ) {
            $args = array() ;
            $args = wp_parse_args( $args , array(
                'id'    => 'wpcf7-affiliate-settings' ,
                'name'  => 'affiliate_settings' ,
                'title' => __( 'Affiliate Settings' , 'contact-form-7' ) ,
                    ) ) ;

            $id = esc_attr( $args[ 'id' ] ) ;

            $affiliate_props_settings = wp_parse_args( $post->prop( $args[ 'name' ] ) , array(
                'fs_affiliates_cf_referrals_enable'  => false ,
                'fs_affiliates_cf_referral_type'     => '' ,
                'fs_affiliates_cf_commission_value'  => '' ,
                'fs_affiliates_cf_username_form_key' => ''
                    ) ) ;
            ?>
            <h2><?php echo esc_html( __( 'Affiliate Settings' , 'contact-form-7' ) ) ; ?></h2>
            <div>
                <p scope="row">
                    <label><?php echo esc_html( __( 'Allow Referrals' , 'contact-form-7' ) ) ; ?></label>
                </p>
                <p>
                    <input type="checkbox" class="fs_affiliates_cf_referrals_enable" name="<?php echo $id ?>[fs_affiliates_cf_referrals_enable]" value="yes" <?php echo ( ! empty( $affiliate_props_settings[ 'fs_affiliates_cf_referrals_enable' ] ) ) ? ' checked="checked"' : '' ; ?>    />
                </p>
                <p class="fs_affiliates_cf_referrals_content">
                    <label><?php echo esc_html( __( 'Referral Type' , 'contact-form-7' ) ) ; ?></label>
                </p>
                <p class="fs_affiliates_cf_referrals_content">
                    <select class="large-text" name="<?php echo $id ?>[fs_affiliates_cf_referral_type]" id="">
                        <option value="sale" <?php if ( esc_attr( $affiliate_props_settings[ 'fs_affiliates_cf_referral_type' ] ) == 'sale' ) { ?> selected='selected' <?php } ?>> <?php echo esc_html__( 'Sale' , 'contact-form-7' ) ?></option>
                        <option value="opt-in" <?php if ( esc_attr( $affiliate_props_settings[ 'fs_affiliates_cf_referral_type' ] ) == "opt-in" ) { ?> selected='selected' <?php } ?>> <?php echo esc_html__( 'Opt-In' , 'contact-form-7' ) ?> </option>
                        <option value="lead" <?php if ( esc_attr( $affiliate_props_settings[ 'fs_affiliates_cf_referral_type' ] ) == "lead" ) { ?> selected='selected' <?php } ?>>  <?php echo esc_html__( 'Lead' , 'contact-form-7' ) ?> </option>
                    </select>
                </p>
                <p class="fs_affiliates_cf_referrals_content"> 
                    <label><?php echo esc_html( __( 'Commission Value' , 'contact-form-7' ) ) ; ?></label>
                </p>
                <p class="fs_affiliates_cf_referrals_content">
                    <input type="text" class="large-text fs_affiliates_input_price" name="<?php echo $id ?>[fs_affiliates_cf_commission_value]"  value="<?php echo esc_attr( $affiliate_props_settings[ 'fs_affiliates_cf_commission_value' ] ) ; ?>"  />
                </p>
                <p scope="row">
                    <label><?php echo esc_html( __( 'Submitted by' , 'contact-form-7' ) ) ; ?></label>
                </p>
                <p class="fs_affiliates_cf_referrals_content">
                    <input type="text" class="large-text" name="<?php echo $id ?>[fs_affiliates_cf_username_form_key]"  value="<?php echo esc_attr( $affiliate_props_settings[ 'fs_affiliates_cf_username_form_key' ] ) ; ?>"  />
                    <span><?php echo esc_html( __( 'Place the key value of the form field for which you wish to  display as Referrals' , 'contact-form-7' ) ) ?></span>
                </p>
            </div>
            <?php
        }

        public function save_contact_form( $contact_form , $args , $context ) {

            $args[ 'affiliate_settings' ] = isset( $_POST[ 'wpcf7-affiliate-settings' ] ) ? $_POST[ 'wpcf7-affiliate-settings' ] : '' ;

            $properties = $contact_form->get_properties() ;

            $properties[ 'affiliate_settings' ]                                        = $args[ 'affiliate_settings' ] ;
            $properties[ 'affiliate_settings' ][ 'fs_affiliates_cf_commission_value' ] = fs_affiliates_format_decimal( $properties[ 'affiliate_settings' ][ 'fs_affiliates_cf_commission_value' ] , true ) ;

            $contact_form->set_properties( $properties ) ;

            if ( 'save' == $context ) {
                $contact_form->save() ;
            }
        }

        public function form_after_success( $contact_form , $result ) {

            $affiliate_settings                = $contact_form->prop( 'affiliate_settings' ) ;
            $fs_affiliates_cf_referrals_enable = (isset( $affiliate_settings[ 'fs_affiliates_cf_referrals_enable' ] )) ? $affiliate_settings[ 'fs_affiliates_cf_referrals_enable' ] : '' ;
            $submission_status                 = (isset( $result[ 'status' ] )) ? $result[ 'status' ] : 0 ;

            if ( $fs_affiliates_cf_referrals_enable != 'yes' || $submission_status != 'mail_sent' || fs_affiliates_get_id_from_cookie( 'fsaffiliateid' ) == '0' ) {
                return ;
            }
            $form_id                           = $contact_form->id() ;
            $time_now                          = time() ;
            $format_reference                  = $form_id . ' - ' . $time_now ;
            $fs_affiliates_cf_referral_type    = (isset( $affiliate_settings[ 'fs_affiliates_cf_referral_type' ] )) ? $affiliate_settings[ 'fs_affiliates_cf_referral_type' ] : ' - ' ;
            $fs_affiliates_cf_commission_value = (isset( $affiliate_settings[ 'fs_affiliates_cf_commission_value' ] ) && ! empty( $affiliate_settings[ 'fs_affiliates_cf_commission_value' ] )) ? $affiliate_settings[ 'fs_affiliates_cf_commission_value' ] : 0 ;
            $affiliate_id                      = fs_affiliates_get_id_from_cookie( 'fsaffiliateid' ) ;

            $description        = get_option( 'fs_affiliates_referral_desc_contact7_label' , 'Contact Form 7' ) ;
            $user_name_form_key = (isset( $affiliate_settings[ 'fs_affiliates_cf_username_form_key' ] ) && ! empty( $affiliate_settings[ 'fs_affiliates_cf_username_form_key' ] )) ? $affiliate_settings[ 'fs_affiliates_cf_username_form_key' ] : '' ;
            $user_name          = (isset( $_REQUEST[ $user_name_form_key ] )) ? $_REQUEST[ $user_name_form_key ] : '' ;
            $description        = str_replace( '{username}' , $user_name , $description ) ;

            $meta_data = array(
                'reference'   => $format_reference ,
                'description' => $description ,
                'amount'      => $fs_affiliates_cf_commission_value ,
                'type'        => $fs_affiliates_cf_referral_type ,
                'date'        => $time_now ,
                'visit_id'    => fs_affiliates_get_id_from_cookie( 'fsvisitid' ) ,
                'campaign'    => fs_affiliates_get_id_from_cookie( 'fscampaign' , '' ) ,
                    ) ;

            fs_affiliates_create_new_referral( $meta_data , array( 'post_author' => $affiliate_id ) ) ;
        }

    }

}
    

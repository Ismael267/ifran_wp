<?php
/**
 * Formidable Forms
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'FS_Affiliates_Formidable_Forms' ) ) {

    /**
     * Class FS_Affiliates_Formidable_Forms
     */
    class FS_Affiliates_Formidable_Forms extends FS_Affiliates_Integrations {

        /**
         * Class Constructor
         */
        public function __construct() {
            $this->id    = 'formidable_forms' ;
            $this->title = __( 'Formidable Forms' , FS_AFFILIATES_LOCALE ) ;

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
            add_action( 'frm_add_form_msg_options' , array( $this , 'form_settings' ) , 1 ) ;
            add_filter( 'frm_form_options_before_update' , array( $this , 'save_form_settings' ) , 10 , 2 ) ;
        }

        /*
         * Plugin enabled
         */

        public function is_plugin_enabled() {
            if ( class_exists( 'FrmHooksController' ) ) {
                return true ;
            }
        }

        /*
         * Frontend Actions
         */

        public function frontend_action() {
            add_action( 'frm_process_entry' , array( $this , 'frm_after_entry_processed' ) , 10 , 3 ) ;
        }

        public function frm_after_entry_processed( $params , $errors , $form ) {
            if ( ! is_object( $form ) ) {
                return ;
            }

            $get_form_options = $form->options ;
            if ( empty( $get_form_options ) || ! is_array( $get_form_options ) ) {
                return ;
            }

            $form_id          = $form->id ;
            $time_now         = time() ;
            $format_reference = $form_id . ' - ' . $time_now ;

            $is_ff_referrals_enable = isset( $get_form_options[ 'fs_affiliates_enable_affiliates' ] ) ? $get_form_options[ 'fs_affiliates_enable_affiliates' ] : '' ;

            if ( $is_ff_referrals_enable != 1 || fs_affiliates_get_id_from_cookie( 'fsaffiliateid' ) == '0' ) {
                return ;
            }

            $fs_affiliates_ff_referral_type    = isset( $get_form_options[ 'fs_affiliates_referral_type' ] ) ? $get_form_options[ 'fs_affiliates_referral_type' ] : '' ;
            $fs_affiliates_ff_commission_value = isset( $get_form_options[ 'fs_affiliates_commission_value' ] ) ? $get_form_options[ 'fs_affiliates_commission_value' ] : '' ;
            $affiliate_id                      = fs_affiliates_get_id_from_cookie( 'fsaffiliateid' ) ;

            $meta_data = array(
                'reference'   => $format_reference ,
                'description' => get_option( 'fs_affiliates_referral_desc_fomidable_form_label' , 'Formidable Forms' ) ,
                'amount'      => $fs_affiliates_ff_commission_value ,
                'type'        => $fs_affiliates_ff_referral_type ,
                'date'        => time() ,
                'visit_id'    => fs_affiliates_get_id_from_cookie( 'fsvisitid' ) ,
                'campaign'    => fs_affiliates_get_id_from_cookie( 'fscampaign' , '' ) ,
                    ) ;

            fs_affiliates_create_new_referral( $meta_data , array( 'post_author' => $affiliate_id ) ) ;
        }

        /*
         * Display Form Settings
         */

        public function form_settings( $values ) {
            $allow_referral   = isset( $values[ 'fs_affiliates_enable_affiliates' ] ) ? $values[ 'fs_affiliates_enable_affiliates' ] : '' ;
            $referral_type    = isset( $values[ 'fs_affiliates_referral_type' ] ) ? $values[ 'fs_affiliates_referral_type' ] : '' ;
            $commission_value = isset( $values[ 'fs_affiliates_commission_value' ] ) ? $values[ 'fs_affiliates_commission_value' ] : '' ;
            ?>
            </table>
            <!--AJAX Section-->
            <h3><?php _e( 'Affiliates Pro' , 'formidable' ) ?>
                <span class="frm_help frm_icon_font frm_tooltip_icon" title="<?php esc_attr_e( 'This is Description' , 'formidable' ) ?>" ></span>
            </h3>
            <table class="form-table">
                <tr>
                    <td>
                        <label for="fs_affiliates_enable_affiliates">
                            <input type="checkbox" name="options[fs_affiliates_enable_affiliates]" id="fs_affiliates_enable_affiliates" value="1"<?php echo $allow_referral ? ' checked="checked"' : '' ; ?> /> <?php _e( 'Allow Referrals' , 'formidable' ) ?>
                        </label>
                        <span class="frm_help frm_icon_font frm_tooltip_icon" title="<?php esc_attr_e( 'This is Description.' , 'formidable' ) ?>" ></span>
                    </td>
                </tr>
                <tr>
                    <td class="frm_left_label"><label for="fs_affiliates_referral_type"><?php _e( 'Referral Type' , 'formidable' ) ?></label></td>
                    <td>
                        <select name="options[fs_affiliates_referral_type]" id="fs_affiliates_referral_type">
                            <option value="sale" <?php selected( 'sale' , $referral_type ) ?>><?php _e( 'Sale' , 'formidable' ) ?></option>
                            <option value="opt-in" <?php selected( 'opt-in' , $referral_type ) ?>><?php _e( 'Opt-in' , 'formidable' ) ?></option>
                            <option value="lead" <?php selected( 'lead' , $referral_type ) ?>><?php _e( 'Lead' , 'formidable' ) ?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div><?php _e( 'Commission Value' , 'formidable' ) ?></div>
                    </td>
                    <td><input type="text" name="options[fs_affiliates_commission_value]" class = 'fs_affiliates_input_price' value="<?php echo $commission_value ; ?>" /></td>
                </tr>

                <?php
            }

            /*
             * Save Form Settings
             */

            public function save_form_settings( $options , $values ) {
                $options[ 'fs_affiliates_enable_affiliates' ] = ( isset( $values[ 'options' ][ 'fs_affiliates_enable_affiliates' ] ) ) ? $values[ 'options' ][ 'fs_affiliates_enable_affiliates' ] : '' ;
                $options[ 'fs_affiliates_referral_type' ]     = ( isset( $values[ 'options' ][ 'fs_affiliates_referral_type' ] ) ) ? $values[ 'options' ][ 'fs_affiliates_referral_type' ] : '' ;
                $options[ 'fs_affiliates_commission_value' ]  = ( isset( $values[ 'options' ][ 'fs_affiliates_commission_value' ] ) ) ? fs_affiliates_format_decimal( $values[ 'options' ][ 'fs_affiliates_commission_value' ] , true ) : '' ;

                return $options ;
            }

        }

    }
    
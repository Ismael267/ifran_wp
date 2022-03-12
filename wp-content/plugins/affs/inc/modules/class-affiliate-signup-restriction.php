<?php
/**
 * Affiliate Signup Restrictions
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'FS_Affiliates_Signup_Restriction' ) ) {

    /**
     * Class FS_Affiliates_Signup_Restriction
     */
    class FS_Affiliates_Signup_Restriction extends FS_Affiliates_Modules {
        /*
         * Data
         */

        protected $data = array(
            'enabled'                      => 'no' ,
            'eligibility'                  => '1' ,
            'restriction_type'             => '' ,
            'single_restriction_message'   => '' ,
            'multiple_restriction_message' => ''
                ) ;

        /**
         * Class Constructor
         */
        public function __construct() {
            $this->id    = 'affiliate_signup_restriction' ;
            $this->title = esc_html__( 'Affiliate Signup Restriction' , FS_AFFILIATES_LOCALE ) ;

            parent::__construct() ;
        }

        /*
         * Plugin enabled
         */

        public function is_plugin_enabled() {
            $woocommerce = FS_Affiliates_Integration_Instances::get_integration_by_id( 'woocommerce' ) ;

            if ( $woocommerce->is_enabled() )
                return true ;

            return false ;
        }

        /*
         * Get settings link
         */

        public function settings_link() {
            return add_query_arg( array( 'page' => 'fs_affiliates' , 'tab' => 'modules' , 'section' => $this->id ) , admin_url( 'admin.php' ) ) ;
        }

        /*
         * Get default message for single
         */

        public function get_default_single_restriction() {

            return 'Affiliate Application Form will be visible only when you reached any one of the following restriction,

i) <b>Account Restriction</b> - After <b>[account_restriction]</b> days from account creation
ii) <b>Product Purchase Restriction</b> - Once you spent <b>[purchase_restriction]</b> in the site' ;
        }

        /*
         * Get default message for single
         */

        public function get_default_multiple_restriction() {

            return 'Affiliate Application Form will be visible only when you reached all the following restriction,

i) <b>Account Restriction</b> - After <b>[account_restriction]</b> days from account creation
ii) <b>Product Purchase Restriction</b> - Once you spent <b>[purchase_restriction]</b> in the site' ;
        }
        
         /*
         * Get default message for guest user.
         */

        public function get_default_guest_restriction() {

            return 'Affiliate Application Form is restricted for Guest Users. Click here to [woo_signup]' ;
        }

        /*
         * Get settings options array
         */

        public function settings_options_array() {
            return array(
                array(
                    'type'  => 'title' ,
                    'title' => esc_html__( 'Affiliate Signup Restriction' , FS_AFFILIATES_LOCALE ) ,
                    'id'    => 'affiliate_signup_restriction_options' ,
                ) ,
                array(
                    'title'   => esc_html__( 'Eligibility for Submitting Affiliate Application Form' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->get_field_key( 'eligibility' ) ,
                    'type'    => 'select' ,
                    'default' => '1' ,
                    'options' => array(
                        '1' => esc_html__( 'Should Satisfy Any one Condition' , FS_AFFILIATES_LOCALE ) ,
                        '2' => esc_html__( 'Should Satisfy All the Conditions' , FS_AFFILIATES_LOCALE ) ,
                    ) ,
                ) ,
                array(
                    'type' => 'sectionend' ,
                    'id'   => 'affiliate_signup_restriction_options' ,
                ) ,
                array(
                    'type'  => 'title' ,
                    'title' => esc_html__( 'Message Settings' , FS_AFFILIATES_LOCALE ) ,
                    'id'    => 'affiliate_signup_restriction_message_options' ,
                ) ,
                array(
                    'title'   => esc_html__( 'Single Action Restriction Message' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->get_field_key( 'single_restriction_message' ) ,
                    'type'    => 'textarea' ,
                    'default' => $this->get_default_single_restriction() ,
                ) ,
                array(
                    'title'   => esc_html__( 'Multiple Action Restriction Message' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->get_field_key( 'multiple_restriction_message' ) ,
                    'type'    => 'textarea' ,
                    'default' => $this->get_default_multiple_restriction()
                ) ,
                array(
                    'title'   => esc_html__( 'Guest Restriction Message' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->get_field_key( 'guest_restriction_message' ) ,
                    'type'    => 'textarea' ,
                    'default' => $this->get_default_guest_restriction()
                ) ,
                array(
                    'type' => 'sectionend' ,
                    'id'   => 'affiliate_signup_restriction_message_options' ,
                ) ,
                    ) ;
        }

        /*
         * Admin action
         */

        public function admin_action() {
            add_action( $this->plugin_slug . '_settings_affiliate_signup_restriction_options_after' , array( $this , 'html_restriction_type' ) ) ;
        }

        /**
         * Frontend Actions
         */
        public function frontend_action() {
            add_action( 'fs_affiliates_before_register_form' , array( $this , 'affiliate_register_form' ) ) ;
            add_filter( 'fs_affiliates_checkout_affiliate_registration' , array( $this , 'checkout_affiliate_registration' ) ) ;
            add_filter( 'fs_affiliates_myaccount_affiliate_registeration' , array( $this , 'myaccount_affiliate_registeration' ) ) ;
        }

        /**
         * html for restriction type
         */
        public function html_restriction_type() {
            $key               = $this->get_field_key( 'restriction_type' ) ;
            $restriction_types = $this->get_restriction_types() ;
            ?><table class="fs_affiliates_restriction_type fs_affiliates_mlm_rules_table widefat">
                <thead>
                    <tr>
                        <th><?php esc_html_e( 'Restriction Type' , FS_AFFILIATES_LOCALE ) ; ?></th>
                        <th><?php esc_html_e( 'Restriction Value' , FS_AFFILIATES_LOCALE ) ; ?></th>
                        <th><?php esc_html_e( 'Status' , FS_AFFILIATES_LOCALE ) ; ?></th>
                    </tr>
                </thead>
                <?php
                if ( fs_affiliates_check_is_array( $restriction_types ) ) {
                    foreach ( $restriction_types as $restriction_key => $restriction_type ) {
                        $restriction_key = $key . "[" . $restriction_key . "]" ;
                        ?>
                        <tr>
                            <td><?php echo $restriction_type[ 'label' ] ; ?></td>
                            <td>
                                <?php
                                switch ( $restriction_type[ 'type' ] ) {
                                    case 'number':
                                        ?><input type="number" name="<?php echo $restriction_key ; ?>[value]" value="<?php echo $restriction_type[ 'value' ] ; ?>"/>
                                        <p style="text-align:left !important;"><?php echo $restriction_type[ 'desc' ] ; ?></p><?php
                                        break ;
                                    case 'text' :
                                        ?><input type="text" name="<?php echo $restriction_key ; ?>[value]" value="<?php echo $restriction_type[ 'value' ] ; ?>"/>
                                        <p style="text-align:left !important;"><?php echo $restriction_type[ 'desc' ] ; ?></p><?php
                                        break ;
                                    default:
                                        do_action( 'fs_affiliates_restriction_type_value_html' ) ;
                                        break ;
                                }
                                ?>
                            </td>
                            <td><input type="checkbox" name="<?php echo $restriction_key ; ?>[status]" value="yes" <?php echo checked( 'yes' , $restriction_type[ 'status' ] ) ; ?>/></td>
                        </tr>
                        <?php
                    }
                }
                ?>
            </table>
            <?php
        }

        /**
         * Validate Affiliate Registration form
         */
        public function affiliate_register_form() {
            $user = wp_get_current_user() ;
            if ( ! $user->exists() )
                $link       = '<a href="'.get_permalink( get_option('woocommerce_myaccount_page_id') ).'" target="__blank">'.esc_html__('Register', FS_AFFILIATES_LOCALE ).'</a>';
                $message    = str_replace( array('[woo_signup]') , $link , $this->get_option( 'guest_restriction_message' , $this->get_default_guest_restriction() ) ) ;
                throw new Exception( $message );

            if ( $this->validate_restriction_type( $user ) )
                return ;

            if ( $this->eligibility == '2' ) {
                $message = $this->get_option( 'multiple_restriction_message' , $this->get_default_multiple_restriction() ) ;
            } else {
                $message = $this->get_option( 'single_restriction_message' , $this->get_default_single_restriction() ) ;
            }

            $restriction_types = $this->get_restriction_types() ;

            $find_array    = array( '[account_restriction]' , '[purchase_restriction]' ) ;
            $replace_array = array( $restriction_types[ 'account_restriction' ][ 'value' ] , fs_affiliates_price( $restriction_types[ 'purchase_restriction' ][ 'value' ] ) ) ;

            $message = str_replace( $find_array , $replace_array , $message ) ;
            $message = wpautop( $message ) ;

            throw new Exception( $message ) ;
        }

        /**
         * Display checkout Registration form
         */
        public function checkout_affiliate_registration() {

            $user = wp_get_current_user() ;
            if ( ! $user->exists() )
                return false ;

            return $this->validate_restriction_type( $user ) ;
        }

        /**
         * Display myaccount Affiliate Registration
         */
        public function myaccount_affiliate_registeration() {

            $user = wp_get_current_user() ;
            if ( ! $user->exists() )
                return false ;

            return $this->validate_restriction_type( $user ) ;
        }

        /**
         * validate restriction type
         */
        public function validate_restriction_type( $user ) {

            $restriction_types = $this->get_restriction_types() ;
            $restriction       = true ;
            foreach ( $restriction_types as $key => $restriction_type ) {

                if ( $restriction_type[ 'status' ] == 'no' )
                    continue ;

                switch ( $key ) {

                    case 'account_restriction':
                        if ( ! empty( $restriction_type[ 'value' ] ) ) {
                            $last_date = strtotime( '+' . $restriction_type[ 'value' ] . ' DAYS' , strtotime( $user->user_registered ) ) ;

                            if ( $last_date > time() ) {
                                $restriction = false ;
                            } elseif ( $this->eligibility != '2' ) {
                                return true ;
                            }
                        }
                        break ;

                    case 'purchase_restriction':
                        if ( ! empty( $restriction_type[ 'value' ] ) ) {

                            if ( $restriction_type[ 'value' ] > $this->get_purchase_amount( $user->ID ) ) {
                                $restriction = false ;
                            } elseif ( $this->eligibility != '2' ) {
                                return true ;
                            }
                        }
                        break ;
                }
            }

            return $restriction ;
        }

        /**
         * get user purchase amount
         */
        public function get_purchase_amount( $user_id ) {
            global $wpdb ;

            return $wpdb->get_var( $wpdb->prepare( "SELECT SUM(meta2.meta_value)
			FROM $wpdb->posts as posts

			LEFT JOIN {$wpdb->postmeta} AS meta ON posts.ID = meta.post_id
			LEFT JOIN {$wpdb->postmeta} AS meta2 ON posts.ID = meta2.post_id

			WHERE   meta.meta_key       = '_customer_user'
			AND     meta.meta_value     = %d
			AND     posts.post_type     IN ('" . implode( "','" , wc_get_order_types( 'reports' ) ) . "')
			AND     posts.post_status   IN ( '" . implode( "','" , array( 'wc-completed' , 'wc-processing' ) ) . " ' )
			AND     meta2.meta_key      = '_order_total'
		" , $user_id )
                    ) ;
        }

        /**
         * get restriction types
         */
        public function get_restriction_types() {
            $key            = $this->get_field_key( 'restriction_type' ) ;
            $default_values = $this->get_restriction_type_default_values() ;

            $restrict_types = get_option( $key , $default_values ) ;

            return array_filter( fs_affiliates_array_merge_recursive_distinct( $restrict_types , $default_values ) ) ;
        }

        /**
         * get default restriction types
         */
        public function get_restriction_type_default_values() {
            $default_values = array(
                'account_restriction'  => array(
                    'label'  => esc_html__( 'Account Restriction' , FS_AFFILIATES_LOCALE ) ,
                    'value'  => '' ,
                    'status' => 'yes' ,
                    'desc'   => esc_html__( 'By enabling this checkbox, an affiliate application form will display to registered users after the days set in this field reached from the account creation' , FS_AFFILIATES_LOCALE ) ,
                    'type'   => 'number' ,
                ) ,
                'purchase_restriction' => array(
                    'label'  => esc_html__( 'Product Purchase Restriction' , FS_AFFILIATES_LOCALE ) ,
                    'value'  => '' ,
                    'status' => 'yes' ,
                    'desc'   => esc_html__( 'By enabling this checkbox, an affiliate application form will display to registered users once they spent the amount set in this field' , FS_AFFILIATES_LOCALE ) ,
                    'type'   => 'text' ,
                ) ,
                    ) ;

            return apply_filters( 'fs_affiliates_restriction_type_default_values' , $default_values ) ;
        }

        /*
         * Save
         */

        public function after_save() {
            $id = $this->get_field_key( 'restriction_type' ) ;
            if ( ! isset( $_POST[ $id ] ) )
                return ;

            $restriction_types = $_POST[ $id ] ;

            if ( ! fs_affiliates_check_is_array( $restriction_types ) )
                return ;

            $saving_restriction_types = array() ;
            foreach ( $restriction_types as $key => $restriction_type ) {
                $restriction_type[ 'status' ]     = isset( $restriction_type[ 'status' ] ) ? 'yes' : 'no' ;
                $saving_restriction_types[ $key ] = $restriction_type ;
            }

            $this->restriction_type = $saving_restriction_types ;

            $this->update_option( 'restriction_type' , $saving_restriction_types ) ;
        }

    }

}
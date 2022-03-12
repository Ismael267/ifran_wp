<?php
/**
 * Referral Code
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'FS_Affiliates_Referral_Code' ) ) {

    /**
     * Class FS_Affiliates_Referral_Code
     */
    class FS_Affiliates_Referral_Code extends FS_Affiliates_Modules {
        /*
         * Data
         */

        protected $data = array(
            'enabled'                         => 'no' ,
            'checkout_page_visible'           => 'no' ,
            'checkout_page_visible_type'      => '1' ,
            'myaccount_page_visible'          => 'no' ,
            'myaccount_page_visible_type'     => '1' ,
            'registration_page_visible'       => 'no' ,
            'registration_page_visible_type'  => '1' ,
            'dashboard_label'                 => 'Referral Code' ,
            'creation_prefix'                 => '' ,
            'creation_sufix'                  => '' ,
            'field_label'                     => '' ,
            'apply_field_caption'             => '' ,
            'field_link_label'                => '' ,
            'field_placeholder'               => '' ,
            'submit_button_caption'           => '' ,
            'creation_length'                 => '15' ,
            'checkout_page_message'           => '' ,
            'referral_code_mandatory_message' => '' ,
                ) ;

        /**
         * Class Constructor
         */
        public function __construct() {
            $this->id    = 'referral_code' ;
            $this->title = __( 'Referral Code' , FS_AFFILIATES_LOCALE ) ;

            parent::__construct() ;
        }

        /*
         * Get settings link
         */

        public function settings_link() {
            return add_query_arg( array( 'page' => 'fs_affiliates' , 'tab' => 'modules' , 'section' => $this->id ) , admin_url( 'admin.php' ) ) ;
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
         * Get settings options array
         */

        public function settings_options_array() {
            return array(
                array(
                    'type'  => 'title' ,
                    'title' => __( 'Referral Code Display Settings' , FS_AFFILIATES_LOCALE ) ,
                    'id'    => 'referral_code_display_options' ,
                ) ,
                array(
                    'title'   => __( 'Referral Code Field will be visible on' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->get_field_key( 'checkout_page_visible' ) ,
                    'desc'    => __( 'Checkout Page' , FS_AFFILIATES_LOCALE ) ,
                    'type'    => 'checkbox' ,
                    'default' => 'no' ,
                ) ,
                array(
                    'title'   => esc_html__( 'Referral Code Field is' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->get_field_key( 'checkout_page_visible_type' ) ,
                    'type'    => 'select' ,
                    'default' => '1' ,
                    'options' => array( '1' => esc_html__( 'Optional' , FS_AFFILIATES_LOCALE ) , '2' => esc_html__( 'Mandatory' , FS_AFFILIATES_LOCALE ) )
                ) ,
                array(
                    'title'   => '' ,
                    'id'      => $this->get_field_key( 'myaccount_page_visible' ) ,
                    'desc'    => __( 'My Account Page' , FS_AFFILIATES_LOCALE ) ,
                    'type'    => 'checkbox' ,
                    'default' => 'no' ,
                ) ,
                array(
                    'title'   => esc_html__( 'Referral Code Field is' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->get_field_key( 'myaccount_page_visible_type' ) ,
                    'type'    => 'select' ,
                    'default' => '1' ,
                    'options' => array( '1' => esc_html__( 'Optional' , FS_AFFILIATES_LOCALE ) , '2' => esc_html__( 'Mandatory' , FS_AFFILIATES_LOCALE ) )
                ) ,
                array(
                    'title'   => '' ,
                    'id'      => $this->get_field_key( 'registration_page_visible' ) ,
                    'desc'    => __( 'Affiliate Registration Page' , FS_AFFILIATES_LOCALE ) ,
                    'type'    => 'checkbox' ,
                    'default' => 'no' ,
                ) ,
                array(
                    'title'   => esc_html__( 'Referral Code Field is' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->get_field_key( 'registration_page_visible_type' ) ,
                    'type'    => 'select' ,
                    'default' => '1' ,
                    'options' => array( '1' => esc_html__( 'Optional' , FS_AFFILIATES_LOCALE ) , '2' => esc_html__( 'Mandatory' , FS_AFFILIATES_LOCALE ) )
                ) ,
                array(
                    'type' => 'sectionend' ,
                    'id'   => 'referral_code_display_options' ,
                ) ,
                array(
                    'type'  => 'title' ,
                    'title' => __( 'Referral Code Label Settings' , FS_AFFILIATES_LOCALE ) ,
                    'id'    => 'referral_code_customization_options' ,
                ) ,
                array(
                    'title'   => __( 'Affiliate Dashboard Referral Label' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->get_field_key( 'dashboard_label' ) ,
                    'type'    => 'text' ,
                    'default' => 'Referral Code' ,
                ) ,
                array(
                    'title'   => __( 'Affiliate Referral Field Label' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->get_field_key( 'field_label' ) ,
                    'type'    => 'text' ,
                    'default' => 'Have a Referral Code' ,
                ) ,
                array(
                    'title'   => __( 'Affiliate Referral Field Link Label' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->get_field_key( 'field_link_label' ) ,
                    'type'    => 'text' ,
                    'default' => 'Click here to enter your code' ,
                ) ,
                array(
                    'title'   => __( 'Apply field Caption' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->get_field_key( 'apply_field_caption' ) ,
                    'type'    => 'text' ,
                    'default' => 'Referral Code' ,
                ) ,
                array(
                    'title'   => __( 'Referral Code Field Placeholder' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->get_field_key( 'field_placeholder' ) ,
                    'type'    => 'text' ,
                    'default' => 'Referral Code' ,
                ) ,
                array(
                    'title'   => __( 'Submit Button Label' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->get_field_key( 'submit_button_caption' ) ,
                    'type'    => 'text' ,
                    'default' => 'Apply Referral Code' ,
                ) ,
                array(
                    'type' => 'sectionend' ,
                    'id'   => 'referral_code_customization_options' ,
                ) ,
                array(
                    'type'  => 'title' ,
                    'title' => __( 'Referral Code Creation Settings' , FS_AFFILIATES_LOCALE ) ,
                    'id'    => 'referral_code_creation_options' ,
                ) ,
                array(
                    'title'   => __( 'Prefix' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->get_field_key( 'creation_prefix' ) ,
                    'type'    => 'text' ,
                    'default' => '' ,
                ) ,
                array(
                    'title'   => __( 'Sufix' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->get_field_key( 'creation_sufix' ) ,
                    'type'    => 'text' ,
                    'default' => '' ,
                ) ,
                array(
                    'title'   => __( 'Referral Code Length' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->get_field_key( 'creation_length' ) ,
                    'type'    => 'number' ,
                    'default' => '10' ,
                ) ,
                array(
                    'type' => 'sectionend' ,
                    'id'   => 'referral_code_creation_options' ,
                ) ,
                array(
                    'type'  => 'title' ,
                    'title' => __( ' Message Settings' , FS_AFFILIATES_LOCALE ) ,
                    'id'    => 'referral_message_options' ,
                ) ,
                array(
                    'title'   => __( 'Checkout Page Message' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->get_field_key( 'checkout_page_message' ) ,
                    'type'    => 'text' ,
                    'default' => 'Submitted Successfully' ,
                ) ,
                array(
                    'title'   => esc_html__( 'Error Message to display when a user didn\'t apply the referral code[Applicable when the field is set to Mandatory]' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->get_field_key( 'referral_code_mandatory_message' ) ,
                    'type'    => 'text' ,
                    'default' => 'Referral Code Field is Mandatory' ,
                ) ,
                array(
                    'type' => 'sectionend' ,
                    'id'   => 'referral_message_options' ,
                ) ,
                    ) ;
        }

        /**
         * Frontend Actions
         */
        public function frontend_action() {
            add_filter( 'fs_affiliates_referral_code' , array( $this , 'affiliate_referral_code' ) , 10 , 2 ) ;
            add_action( 'fs_affiliates_before_link_generator' , array( $this , 'display_referral_code' ) , 10 , 2 ) ;

            if ( 'yes' == $this->checkout_page_visible && '1' == $this->checkout_page_visible_type ) {
                add_action( 'woocommerce_before_checkout_form' , array( $this , 'display_apply_referral_code' ) ) ;
            } elseif ( 'yes' == $this->checkout_page_visible && '2' == $this->checkout_page_visible_type ) {
                add_action( 'woocommerce_checkout_after_customer_details' , array( $this , 'wc_checkout_referral_code_field' ) ) ;
                add_action( 'woocommerce_checkout_process' , array( $this , 'wc_checkout_registration_errors' ) ) ;
            }

            if ( $this->myaccount_page_visible == 'yes' ) {
                add_action( 'woocommerce_register_post' , array( $this , 'wc_registration_errors' ) , 10 , 3 ) ;
                add_action( 'woocommerce_register_form' , array( $this , 'display_apply_referral_code_wc_register_form' ) ) ;
            }

            if ( $this->registration_page_visible == 'yes' ) {
                add_action( 'fs_affiliates_after_register_fields' , array( $this , 'display_apply_referral_code_register_form' ) ) ;
                add_filter( 'fs_affiliates_registration_errors' , array( $this , 'registration_errors' ) , 10 , 2 ) ;
            }
        }

        /**
         * Get the affiliate referral code.
         * 
         * @return string
         */
        public function affiliate_referral_code( $referral_code , $affiliate_id ) {
            return $this->get_referral_code( $affiliate_id ) ;
        }

        /**
         * Display Affiliate Referral Code
         */
        public function display_referral_code( $affiliate_id , $user_id ) {
            ?>
            <p class="fs_affiliates_referral_code">
                <b> <?php echo $this->dashboard_label . '</b> : ' ; ?><?php echo $this->get_referral_code( $affiliate_id ) ; ?>
            </p>
            <?php
        }

        /**
         * Get the referral code.
         * 
         * @return string
         */
        public function get_referral_code( $affiliate_id ) {
            $affiliate_object = new FS_Affiliates_Data( $affiliate_id ) ;
            if ( ! is_object( $affiliate_object ) ) {
                return '' ;
            }

            if ( ! empty( $affiliate_object->referral_code ) ) {
                $referral_code = $affiliate_object->referral_code ;
            } else {

                do {
                    $referral_code = fs_affiliates_code_generator( $this->creation_length , $this->creation_prefix , $this->creation_sufix ) ;
                } while ( fs_affiliates_get_affiliate_by_metakey( 'referral_code' , $referral_code ) ) ;

                $affiliate_object->update_meta( 'referral_code' , $referral_code ) ;
            }

            return $referral_code ;
        }

        /**
         * Display apply Affiliate Referral Code form
         */
        public function display_apply_referral_code() {

            if ( isset( $_COOKIE[ 'fsaffiliateid' ] ) || ! apply_filters( 'fs_affiliates_display_checkout_referral_code' , true ) )
                return ;

            $retrun = $this->process_referral_code() ;

            if ( $retrun )
                return ;
            ?> <div class = "fs_affiliates_referral_code_notice">
                <div class = "woocommerce-info">
                    <?php echo $this->field_label ;
                    ?>
                    <a href="javascript:void(0)" class="fs_affiliates_referral_code_link"> <?php echo $this->field_link_label ; ?></a>
                </div>
            </div>
            <div class="fs_affiliates_apply_referral_code" style="display:none">
                <form method="POST" class="fs_affiliates_apply_referral_code_form">
                    <p> <?php echo $this->apply_field_caption ; ?></p>
                    <p> 
                        <input type="text" id="fs_affiliates_referral_code_field" class="input-text" name="referral_code" value="" placeholder="<?php echo $this->field_placeholder ; ?>">
                        <input type="hidden" name="action" value="referral_code"/>
                        <input type="hidden" name="fs_nonce" value="<?php echo wp_create_nonce( 'referral_code' ) ?>"/>
                        <input class="button" type="submit" value="<?php echo $this->submit_button_caption ; ?>">
                    </p>
                </form>
            </div>
            <?php
        }

        /**
         * Apply Referral Code field in WooCommerce Register Form
         */
        public function display_apply_referral_code_wc_register_form() {
            if ( isset( $_COOKIE[ 'fsaffiliateid' ] ) )
                return ;
            ?>
            <p class="fs-affiliates-form-row">
                <label for="fs_affiliates_referral_code_field"><?php echo $this->apply_field_caption ; ?>
                    <?php if ( '2' == $this->myaccount_page_visible_type ) { ?>
                        <abbr class="required" title="required">*</abbr>
                    <?php } ?>
                </label>
                <input class="fs_affiliates_referral_code_field" type="password" name="fs_affiliates_apply_referral_code" placeholder="<?php echo $this->field_placeholder ; ?>" value=""/>
            </p>
            <?php
        }

        /**
         * Apply Referral Code field in Register Form
         */
        public function display_apply_referral_code_register_form() {
            if ( isset( $_COOKIE[ 'fsaffiliateid' ] ) )
                return ;
            ?>
            <p class="fs-affiliates-form-row">
                <label for="fs_affiliates_referral_code_field"><?php echo $this->apply_field_caption ; ?>
                    <?php if ( '2' == $this->registration_page_visible_type ) { ?>
                        <abbr class="required" title="required">*</abbr>
                    <?php } ?>
                </label>
                <input class="fs_affiliates_referral_code_field" type="password" name="affiliate[apply_referral_code]" placeholder="<?php echo $this->field_placeholder ; ?>" value=""/>
            </p>
            <?php
        }

        /**
         * Process registration Referral Code
         */
        public function registration_errors( $errors , $meta_data ) {
            if ( '2' == $this->registration_page_visible_type && empty( $meta_data[ 'apply_referral_code' ] ) )
                return $this->referral_code_mandatory_message ;

            if ( empty( $meta_data[ 'apply_referral_code' ] ) )
                return $errors ;

            $affiliate_id = fs_affiliates_get_affiliate_by_metakey( 'referral_code' , $meta_data[ 'apply_referral_code' ] ) ;

            if ( ! $affiliate_id ) {
                return __( 'Invalid Referral Code' , FS_AFFILIATES_LOCALE ) ;
            }

            $current_affiliate = fs_affiliates_is_user_having_affiliate() ;
            if ( $current_affiliate == $affiliate_id && ! apply_filters( 'fs_affiliates_is_restricted_own_commission' , false ) ) {
                return __( 'Referral Code cannot be used by the same affiliate' , FS_AFFILIATES_LOCALE ) ;
            }

            $cookieValidity = fs_affiliates_get_cookie_validity_value() ;

            fs_affiliates_setcookie( 'fsaffiliateid' , base64_encode( $affiliate_id ) , time() + $cookieValidity ) ;

            $_COOKIE[ 'fsaffiliateid' ] = base64_encode( $affiliate_id ) ;
        }

        /**
         * Process WC registration Referral Code
         */
        public function wc_registration_errors( $username , $email , $validation_errors ) {

            if ( '2' == $this->myaccount_page_visible_type && empty( $_POST[ 'fs_affiliates_apply_referral_code' ] ) ) {
                $validation_errors->add( 'fs_affiliates_apply_referral_code_error' , $this->referral_code_mandatory_message , FS_AFFILIATES_LOCALE ) ;

                return $validation_errors ;
            }

            if ( empty( $_POST[ 'fs_affiliates_apply_referral_code' ] ) )
                return $validation_errors ;

            $affiliate_id = fs_affiliates_get_affiliate_by_metakey( 'referral_code' , $_POST[ 'fs_affiliates_apply_referral_code' ] ) ;

            if ( ! $affiliate_id ) {
                $validation_errors->add( 'fs_affiliates_apply_referral_code_error' , __( 'Invalid Referral Code' , FS_AFFILIATES_LOCALE ) , FS_AFFILIATES_LOCALE ) ;

                return $validation_errors ;
            }

            $current_affiliate = fs_affiliates_is_user_having_affiliate() ;
            if ( $current_affiliate == $affiliate_id && ! apply_filters( 'fs_affiliates_is_restricted_own_commission' , false ) ) {
                $validation_errors->add( 'fs_affiliates_apply_referral_code_error' , __( 'Referral Code cannot be used by the same affiliate' , FS_AFFILIATES_LOCALE ) , FS_AFFILIATES_LOCALE ) ;

                return $validation_errors ;
            }

            $cookieValidity = fs_affiliates_get_cookie_validity_value() ;

            fs_affiliates_setcookie( 'fsaffiliateid' , base64_encode( $affiliate_id ) , time() + $cookieValidity ) ;

            $_COOKIE[ 'fsaffiliateid' ] = base64_encode( $affiliate_id ) ;

            return $validation_errors ;
        }

        /**
         * Apply Referral Code field in Register Form
         */
        public function wc_checkout_referral_code_field() {
            if ( isset( $_COOKIE[ 'fsaffiliateid' ] ) )
                return ;
            ?>
            <p class="fs-affiliates-form-row">
                <label for="fs_affiliates_referral_code_field"><?php echo $this->apply_field_caption ; ?>
                    <?php if ( '2' == $this->checkout_page_visible_type ) { ?>
                        <abbr class="required" title="required">*</abbr>
                    <?php } ?>
                </label>
                <input class="fs_affiliates_referral_code_field" type="password" name="fs_affiliates_referral_code_field" placeholder="<?php echo $this->field_placeholder ; ?>" value=""/>
            </p>
            <?php
        }

        /**
         * Process for check out registration
         */
        public function wc_checkout_registration_errors() {
            if ( '2' == $this->checkout_page_visible_type && empty( $_POST[ 'fs_affiliates_referral_code_field' ] ) ) {
                wc_add_notice( wp_kses_post( $this->referral_code_mandatory_message ) , 'error' ) ;
                return ;
            }

            $affiliate_id = fs_affiliates_get_affiliate_by_metakey( 'referral_code' , $_POST[ 'fs_affiliates_referral_code_field' ] ) ;

            if ( ! $affiliate_id ) {
                wc_add_notice( esc_html__( 'Invalid Referral Code' , FS_AFFILIATES_LOCALE ) , 'error' ) ;
                return ;
            }

            $current_affiliate = fs_affiliates_is_user_having_affiliate() ;

            if ( $current_affiliate == $affiliate_id && ! apply_filters( 'fs_affiliates_is_restricted_own_commission' , false ) ) {
                wc_add_notice( esc_html__( 'Referral Code cannot be used by the same affiliate' , FS_AFFILIATES_LOCALE ) , 'error' ) ;
                return ;
            }

            $cookieValidity = fs_affiliates_get_cookie_validity_value() ;

            fs_affiliates_setcookie( 'fsaffiliateid' , base64_encode( $affiliate_id ) , time() + $cookieValidity ) ;

            $_COOKIE[ 'fsaffiliateid' ] = base64_encode( $affiliate_id ) ;
        }

        /**
         * Process Referral Code
         */
        public function process_referral_code() {

            if ( ! isset( $_POST[ 'action' ] ) || ! isset( $_POST[ 'fs_nonce' ] ) )
                return false ;

            if ( ! wp_verify_nonce( $_POST[ 'fs_nonce' ] , 'referral_code' ) )
                return false ;

            $referral_code = $_POST[ 'referral_code' ] ;

            if ( empty( $referral_code ) ) {
                wc_print_notice( __( 'Please enter a Referral Code' , FS_AFFILIATES_LOCALE ) , 'error' ) ;
                return false ;
            }

            $affiliate_id = fs_affiliates_get_affiliate_by_metakey( 'referral_code' , $referral_code ) ;

            if ( ! $affiliate_id ) {
                wc_print_notice( __( 'Invalid Referral Code' , FS_AFFILIATES_LOCALE ) , 'error' ) ;
                return false ;
            }

            $current_affiliate = fs_affiliates_is_user_having_affiliate() ;
            if ( $current_affiliate == $affiliate_id && ! apply_filters( 'fs_affiliates_is_restricted_own_commission' , false ) ) {
                wc_print_notice( __( 'Referral Code cannot be used by the same affiliate' , FS_AFFILIATES_LOCALE ) , 'error' ) ;
                return false ;
            }

            $cookieValidity = fs_affiliates_get_cookie_validity_value() ;

            fs_affiliates_setcookie( 'fsaffiliateid' , base64_encode( $affiliate_id ) , time() + $cookieValidity ) ;

            $_COOKIE[ 'fsaffiliateid' ] = base64_encode( $affiliate_id ) ;

            wc_print_notice( $this->checkout_page_message , 'success' ) ;

            return true ;
        }

    }

}
    
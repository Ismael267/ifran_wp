<?php
/**
 * Settings Tab
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( class_exists( 'FS_Affiliates_Settings_Tab' ) ) {
    return new FS_Affiliates_Settings_Tab() ;
}

/**
 * FS_Affiliates_Settings_Tab.
 */
class FS_Affiliates_Settings_Tab extends FS_Affiliates_Settings_Page {

    /**
     * Constructor.
     */
    public function __construct() {
        $this->id    = 'settings' ;
        $this->label = esc_html__( 'Settings' , FS_AFFILIATES_LOCALE ) ;

        add_action( $this->plugin_slug . '_admin_field_output_frontend_form' , array( $this , 'output_frontend_form' ) ) ;
        add_action( $this->plugin_slug . '_admin_field_fs_affiliates_bulk_affiliates' , array( $this , 'output_affiliate_bulk_update' ) ) ;
        add_action( $this->plugin_slug . '_admin_field_fs_affiliates_payment_preference_table' , array( $this , 'output_payment_preference_table' ) ) ;
        parent::__construct() ;
    }

    /**
     * Get sections.
     */
    public function get_sections() {
        $sections = array(
            'default'            => array(
                'label' => esc_html__( 'Default Pages' , FS_AFFILIATES_LOCALE ) ,
                'code'  => 'fa-file-text'
            ) ,
            'general'            => array(
                'label' => esc_html__( 'General Settings' , FS_AFFILIATES_LOCALE ) ,
                'code'  => 'fa-cog'
            ) ,
            'frontend_form'      => array(
                'label' => esc_html__( 'Frontend Form' , FS_AFFILIATES_LOCALE ) ,
                'code'  => 'fa-list-alt'
            ) ,
            'form_customization' => array(
                'label' => esc_html__( 'Frontend Form Customization' , FS_AFFILIATES_LOCALE ) ,
                'code'  => 'fa-wrench'
            ) ,
            'advanced'           => array(
                'label' => esc_html__( 'Advanced' , FS_AFFILIATES_LOCALE ) ,
                'code'  => 'fa-sliders'
            ) ,
            'localization'       => array(
                'label' => esc_html__( 'Localization' , FS_AFFILIATES_LOCALE ) ,
                'code'  => 'fa fa-pencil-square-o'
            ) ,
                ) ;

        return apply_filters( $this->plugin_slug . '_get_sections_' . $this->id , $sections ) ;
    }

    /**
     * Get settings array.
     */
    public function get_settings( $current_section = '' ) {
        switch ( $current_section ) {
            case 'general':
                $settings = $this->general_section_fields() ;
                break ;
            case 'frontend_form':
                $settings = $this->frontend_form_section_fields() ;
                break ;
            case 'form_customization':
                $settings = $this->form_customization_section_fields() ;
                break ;
            case 'advanced':
                $settings = $this->advanced_section_fields() ;
                break ;
            case 'localization':
                $settings = $this->localization_section_fields() ;
                break ;
            default:
                $settings = $this->default_section_fields() ;
                break ;
        }

        return apply_filters( $this->plugin_slug . '_get_settings_' . $this->id , $settings , $current_section ) ;
    }

    /**
     * Get settings frontend form section array.
     */
    public function frontend_form_section_fields() {
        return array(
            array(
                'id'      => 'fs_affiliates_frontend_form_fields' ,
                'default' => fs_affiliates_get_default_form_fields() ,
                'type'    => 'output_frontend_form'
            ) ,
                ) ;
    }

    /**
     * Get settings general section array.
     */
    public function general_section_fields() {
        $currencies = get_fs_affiliates_currencies() ;

        $general_section_fields = array(
            array(
                'type'  => 'title' ,
                'title' => esc_html__( 'Referral Settings' , FS_AFFILIATES_LOCALE ) ,
                'id'    => 'fs_affiliates_referral_options' ,
            ) ,
            array(
                'title'   => esc_html__( 'Affiliate Link Type' , FS_AFFILIATES_LOCALE ) ,
                'desc'    => wpautop( esc_html__( 'Default - Affiliates have to manually generate the link on their dashboard[Affiliate Tools -> Affiliates Links]
                         Static URL -  Affiliate link will be automatically generated and displayed on the Affiliate\'s Dashboard[Affiliate Tools -> Affiliates Links]' , FS_AFFILIATES_LOCALE ) ) ,
                'id'      => 'fs_affiliates_referral_link_type' ,
                'default' => '1' ,
                'type'    => 'select' ,
                'options' => array(
                    '1' => esc_html__( 'Default' , FS_AFFILIATES_LOCALE ) ,
                    '2' => esc_html__( 'Static URL' , FS_AFFILIATES_LOCALE )
                ) ,
            ) ,
            array(
                'title'   => esc_html__( 'Default Referral Link Label' , FS_AFFILIATES_LOCALE ) ,
                'id'      => 'fs_affiliates_default_referral_link_label' ,
                'class'   => 'fs_affiliates_default_referral_settings' ,
                'type'    => 'text' ,
                'default' => esc_html__( 'Affiliate Link Generator' , FS_AFFILIATES_LOCALE )
            ) ,
            array(
                'title'   => esc_html__( 'Default Referral URL' , FS_AFFILIATES_LOCALE ) ,
                'desc'    => esc_html__( 'The Default URL which will be visible in the Link Generator for the Affiliate' , FS_AFFILIATES_LOCALE ) ,
                'id'      => 'fs_affiliates_default_referral_url' ,
                'class'   => 'fs_affiliates_default_referral_settings' ,
                'type'    => 'text' ,
                'default' => site_url() ,
            ) ,
            array(
                'title'   => esc_html__( 'Static Referral Link Label' , FS_AFFILIATES_LOCALE ) ,
                'id'      => 'fs_affiliates_static_referral_link_label' ,
                'class'   => 'fs_affiliates_static_referral_settings' ,
                'type'    => 'text' ,
                'default' => esc_html__( 'Affiliate Link' , FS_AFFILIATES_LOCALE )
            ) ,
            array(
                'title'   => esc_html__( 'Static Referral URL' , FS_AFFILIATES_LOCALE ) ,
                'id'      => 'fs_affiliates_static_referral_url' ,
                'class'   => 'fs_affiliates_static_referral_settings' ,
                'type'    => 'text' ,
                'default' => site_url() ,
                'desc'    => esc_html__( 'The Static URL will be visible for the Affiliate' , FS_AFFILIATES_LOCALE ) ,
            ) ,
            array(
                'title'   => esc_html__( 'Referral Identifier' , FS_AFFILIATES_LOCALE ) ,
                'id'      => 'fs_affiliates_referral_identifier' ,
                'type'    => 'text' ,
                'default' => 'ref' ,
                'desc'    => esc_html__( 'The Referral Identifier will be used in the affiliate links before the affiliate name. < /br>'
                        . '<b>Example:</b> If the Referral Identifier is <b>"ref"</b>, then an affiliate link will look like  <b>http://yoursite.com/?ref=affiliate1</b>' , FS_AFFILIATES_LOCALE ) ,
            ) ,
            array(
                'title'   => esc_html__( 'Referral ID Format' , FS_AFFILIATES_LOCALE ) ,
                'desc'    => esc_html__( 'Examples of Referral ID Format are listed below </br>
                    <b>Affiliate ID</b> – http://yoursite.com/?ref=123 </br><b>Affiliate Username</b> – http://yoursite.com/?ref=affiliate1' , FS_AFFILIATES_LOCALE ) ,
                'id'      => 'fs_affiliates_referral_id_format' ,
                'default' => 'id' ,
                'type'    => 'select' ,
                'options' => array(
                    'id'   => esc_html__( 'Affiliate ID' , FS_AFFILIATES_LOCALE ) ,
                    'name' => esc_html__( 'Affiliate Username' , FS_AFFILIATES_LOCALE )
                ) ,
            ) ,
            array(
                'title'   => esc_html__( 'Referral Status' , FS_AFFILIATES_LOCALE ) ,
                'desc'    => esc_html__( 'When set to <b>"Pending"</b>, the commission amount has to be approved before before Paying the Affiliate. If set to <b>"Unpaid"</b>, the commission amount can paid directly.' , FS_AFFILIATES_LOCALE ) ,
                'id'      => 'fs_affiliates_referral_default_status' ,
                'default' => 'fs_unpaid' ,
                'type'    => 'select' ,
                'options' => array(
                    'fs_unpaid'  => __( 'Unpaid' , FS_AFFILIATES_LOCALE ) ,
                    'fs_pending' => __( 'Pending' , FS_AFFILIATES_LOCALE ) ,
                ) ,
            ) ,
            array(
                'type' => 'sectionend' ,
                'id'   => 'fs_affiliates_referral_options' ,
            ) ,
            array(
                'type'  => 'title' ,
                'title' => esc_html__( 'Referral Validity Settings' , FS_AFFILIATES_LOCALE ) ,
                'id'    => 'fs_affiliates_referral_validity_options' ,
            ) ,
            array(
                'title'       => esc_html__( 'Referral Cookie Validity' , FS_AFFILIATES_LOCALE ) ,
                'desc'        => esc_html__( "The validity of the affiliate links will be decided based on the cookie validity. If the cookie validity is set as 7 Days, then the referral cookie will be automatically deleted from the user's browser after 7 Days." , FS_AFFILIATES_LOCALE ) ,
                'id'          => 'fs_affiliates_referral_cookie_validity' ,
                'type'        => 'relative_date_selector' ,
                'periods'     => array( 'days'   => esc_html__( 'Day(s)' , FS_AFFILIATES_LOCALE ) ,
                    'weeks'  => esc_html__( 'Week(s)' , FS_AFFILIATES_LOCALE ) ,
                    'months' => esc_html__( 'Month(s)' , FS_AFFILIATES_LOCALE ) ,
                ) ,
                'placeholder' => esc_html__( 'N/A' , FS_AFFILIATES_LOCALE ) ,
                'default'     => array(
                    'number' => '1' ,
                    'unit'   => 'days' ,
                ) ,
            ) ,
            array(
                'type' => 'sectionend' ,
                'id'   => 'fs_affiliates_referral_validity_options' ,
            ) ,
            array(
                'type'  => 'title' ,
                'title' => esc_html__( 'Currency Settings' , FS_AFFILIATES_LOCALE ) ,
                'id'    => 'fs_affiliates_currency_options' ,
            ) ,
            array(
                'title'   => esc_html__( 'Affiliate Currency' , FS_AFFILIATES_LOCALE ) ,
                'id'      => 'fs_affiliates_currency' ,
                'type'    => 'select' ,
                'default' => 'USD' ,
                'options' => $currencies ,
                'desc'    => esc_html__( 'Affiliate Commissions will be awarded in the currency set in this option.' , FS_AFFILIATES_LOCALE ) ,
            ) ,
            array(
                'title'   => esc_html__( 'Currency Symbol Position' , FS_AFFILIATES_LOCALE ) ,
                'id'      => 'fs_affiliates_currency_position' ,
                'type'    => 'select' ,
                'default' => 'left' ,
                'options' => array(
                    'left'        => esc_html__( 'Left' , FS_AFFILIATES_LOCALE ) ,
                    'right'       => esc_html__( 'Right' , FS_AFFILIATES_LOCALE ) ,
                    'left_space'  => esc_html__( 'Left After a Space' , FS_AFFILIATES_LOCALE ) ,
                    'right_space' => esc_html__( 'Right After a Space' , FS_AFFILIATES_LOCALE )
                ) ,
                'desc'    => esc_html__( 'This option controls the position of the currency symbol for referral commission amounts.' , FS_AFFILIATES_LOCALE ) ,
            ) ,
            array(
                'title'   => esc_html__( 'Decimal Separator' , FS_AFFILIATES_LOCALE ) ,
                'id'      => 'fs_affiliates_currency_decimal_separator' ,
                'type'    => 'text' ,
                'default' => '.' ,
                'desc'    => esc_html__( 'This option controls the number of decimal places for affiliate commission amounts.' , FS_AFFILIATES_LOCALE ) ,
            ) ,
            array(
                'title'   => esc_html__( 'Number of decimals' , FS_AFFILIATES_LOCALE ) ,
                'id'      => 'fs_affiliates_price_num_decimals' ,
                'type'    => 'number' ,
                'default' => '2' ,
                'desc'    => esc_html__( 'This option controls the rounding up of affiliate commission amounts.' , FS_AFFILIATES_LOCALE ) ,
            ) ,
            array(
                'title'   => esc_html__( 'Thousand Separator' , FS_AFFILIATES_LOCALE ) ,
                'id'      => 'fs_affiliates_currency_thousand_separator' ,
                'type'    => 'text' ,
                'default' => ',' ,
                'desc'    => esc_html__( 'This option can be used a thousand separator for affiliate commission amounts' , FS_AFFILIATES_LOCALE ) ,
            ) ,
            array(
                'type' => 'sectionend' ,
                'id'   => 'fs_affiliates_currency_options' ,
            ) ,
            array(
                'type'  => 'title' ,
                'title' => esc_html__( 'Global Commission Settings' , FS_AFFILIATES_LOCALE ) ,
                'id'    => 'fs_affiliates_global_commissions_options' ,
            ) ,
            array(
                'title'   => esc_html__( 'Commission Type' , FS_AFFILIATES_LOCALE ) ,
                'id'      => 'fs_affiliates_commission_type' ,
                'type'    => 'select' ,
                'default' => 'percentage' ,
                'options' => array(
                    'percentage' => esc_html__( 'Percentage Based Commission' , FS_AFFILIATES_LOCALE ) ,
                    'fixed'      => esc_html__( 'Fixed Value Commission' , FS_AFFILIATES_LOCALE ) ,
                ) ,
                'desc'    => esc_html__( 'The Default Commission which your Affiliates will earn if they get referrals through Product Purchases' , FS_AFFILIATES_LOCALE ) ,
            ) ,
            array(
                'title'   => esc_html__( 'Percentage Commission Value' , FS_AFFILIATES_LOCALE ) ,
                'id'      => 'fs_affiliates_percentage_commission_value' ,
                'type'    => 'price' ,
                'default' => '10' ,
                'desc'    => esc_html__( 'Enter the commission percentage' , FS_AFFILIATES_LOCALE ) ,
            ) ,
            array(
                'title'   => esc_html__( 'Fixed Commission Value' , FS_AFFILIATES_LOCALE ) ,
                'id'      => 'fs_affiliates_fixed_commission_value' ,
                'type'    => 'price' ,
                'default' => '10' ,
                'desc'    => esc_html__( 'Enter the commission amount' , FS_AFFILIATES_LOCALE ) ,
            ) ,
            array(
                'type' => 'sectionend' ,
                'id'   => 'fs_affiliates_global_commissions_options' ,
            ) ,
            array(
                'type'  => 'title' ,
                'title' => esc_html__( 'Payment Settings' , FS_AFFILIATES_LOCALE ) ,
                'id'    => 'payment_settings_options' ,
            ) ,
            array(
                'title'   => esc_html__( 'Payment Method Selection by' , FS_AFFILIATES_LOCALE ) ,
                'id'      => 'fs_affiliates_payment_method_selection_type' ,
                'type'    => 'select' ,
                'default' => '1' ,
                'options' => array(
                    '1' => esc_html__( 'Affiliates' , FS_AFFILIATES_LOCALE ) ,
                    '2' => esc_html__( 'Admin' , FS_AFFILIATES_LOCALE ) ,
                ) ,
                'desc'    => wpautop( esc_html__( 'Affiliates - Affiliates can select their preferred payment method to get their commission from the Frontend Dashboard -> Profile -> Payment Management.
Admin - Affiliates can get their commission through the payment method selected by the admin.' , FS_AFFILIATES_LOCALE ) ) ,
            ) ,
            array(
                'title'   => esc_html__( 'Payment Method' , FS_AFFILIATES_LOCALE ) ,
                'id'      => 'fs_affiliates_admin_payment_method' ,
                'type'    => 'select' ,
                'default' => '1' ,
                'options' => fs_affiliates_get_available_payment_method() ,
            ) ,
            array(
                'type' => 'fs_affiliates_payment_preference_table' ,
            ) ,
            array(
                'type' => 'sectionend' ,
                'id'   => 'payment_settings_options' ,
            ) ,
                ) ;

        $woocommerce = FS_Affiliates_Integration_Instances::get_integration_by_id( 'woocommerce' ) ;
        if ( fs_affiliates_check_if_woocommerce_is_active() && $woocommerce->is_enabled() ) {
            $general_section_fields[] = array(
                'type'  => 'title' ,
                'title' => esc_html__( 'WooCommerce Settings' , FS_AFFILIATES_LOCALE ) ,
                'id'    => 'fs_affiliates_woocommerce_options' ,
                    ) ;

            $general_section_fields[] = array(
                'title'   => esc_html__( 'Order Status to approve Product Purchase Referrals' , FS_AFFILIATES_LOCALE ) ,
                'type'    => 'multiselect' ,
                'class'   => 'fs_affiliates_select2' ,
                'default' => array( 'processing' , 'completed' ) ,
                'options' => fp_affiliates_get_order_statuses() ,
                'id'      => 'fs_affiliates_order_status_to_approved' ,
                    ) ;
            $general_section_fields[] = array(
                'title'   => esc_html__( 'Order Status to reject Unpaid Referrals' , FS_AFFILIATES_LOCALE ) ,
                'type'    => 'multiselect' ,
                'class'   => 'fs_affiliates_select2' ,
                'default' => array( 'cancelled' , 'failed' , 'refunded' ) ,
                'options' => fp_affiliates_get_order_statuses() ,
                'id'      => 'fs_affiliates_order_status_to_reject_unpaid_referral' ,
                    ) ;
            $general_section_fields[] = array(
                'title'   => esc_html__( 'Calculate Commission after Applying Coupon/Discount' , FS_AFFILIATES_LOCALE ) ,
                'type'    => 'checkbox' ,
                'default' => 'no' ,
                'id'      => 'fs_affiliates_calculate_commission_before_apply_coupon' ,
                'desc'    => esc_html__( 'When enabled, the affiliate commission will be calculated for the price which is obtained after applying the coupon.' , FS_AFFILIATES_LOCALE ) ,
                    ) ;
            $general_section_fields[] = array(
                'title'   => esc_html__( 'Exclude Tax Costs for Commission Calculation' , FS_AFFILIATES_LOCALE ) ,
                'type'    => 'checkbox' ,
                'default' => 'no' ,
                'id'      => 'fs_affiliates_exclude_tax_costs_for_commission_calculation' ,
                'desc'    => esc_html__( 'When enabled, tax costs will not be used for commission calculation.' , FS_AFFILIATES_LOCALE ) ,
                    ) ;
            $general_section_fields[] = array(
                'title'   => esc_html__( 'Restrict to use WooCommerce Coupons' , FS_AFFILIATES_LOCALE ) ,
                'type'    => 'checkbox' ,
                'default' => 'no' ,
                'id'      => 'fs_affiliates_wc_coupon_restrict' ,
                'desc'    => esc_html__( 'By enabling this option, affiliates cannot get a discount by using the WooCommerce Coupon.' , FS_AFFILIATES_LOCALE ) ,
                    ) ;
            $general_section_fields[] = array(
                'title'   => esc_html__( 'Restrict Commission based on Quantity' , FS_AFFILIATES_LOCALE ) ,
                'type'    => 'checkbox' ,
                'default' => 'no' ,
                'id'      => 'fs_affiliates_qty_commission_restrict' ,
                'desc'    => esc_html__( 'By enabling this checkbox, the referral product purchase commission will be awarded for one quantity of the product' , FS_AFFILIATES_LOCALE ) ,
                    ) ;
            $general_section_fields[] = array(
                'title'   => esc_html__( 'Message' , FS_AFFILIATES_LOCALE ) ,
                'type'    => 'text' ,
                'default' => 'You are restricted to use this coupon' ,
                'id'      => 'fs_affiliates_wc_coupon_restrict_msg' ,
                    ) ;
            $general_section_fields[] = array(
                'type' => 'sectionend' ,
                'id'   => 'fs_affiliates_woocommerce_options' ,
                    ) ;
        }

        return $general_section_fields ;
    }

    /**
     * Get settings advanced section array.
     */
    public function advanced_section_fields() {
        $user_roles = fs_affiliates_get_user_roles() ;
        unset( $user_roles[ 'administrator' ] ) ;

        $advances_section = array(
            array(
                'type'  => 'title' ,
                'title' => esc_html__( 'Account Registration Settings' , FS_AFFILIATES_LOCALE ) ,
                'id'    => 'fs_affiliates_account_registration_options' ,
            ) ,
            array(
                'title'   => esc_html__( 'Allow Logged-In Users to Register as Affiliates' , FS_AFFILIATES_LOCALE ) ,
                'id'      => 'fs_affiliates_allow_users_to_register' ,
                'type'    => 'checkbox' ,
                'default' => 'yes' ,
                'desc'    => esc_html__( 'When enabled, logged in users will be able to register themselves as affiliates on your site.' , FS_AFFILIATES_LOCALE ) ,
            ) ,
            array(
                'title'   => esc_html__( 'Allow Guests to Register as Affiliates' , FS_AFFILIATES_LOCALE ) ,
                'id'      => 'fs_affiliates_allow_guest_to_register' ,
                'type'    => 'checkbox' ,
                'default' => 'yes' ,
                'desc'    => esc_html__( 'When enabled, guests can register themselves as affiiates on your site' , FS_AFFILIATES_LOCALE ) ,
            ) ,
            array(
                'title'   => esc_html__( 'Affiliate Registration Requires Admin Approval' , FS_AFFILIATES_LOCALE ) ,
                'id'      => 'fs_affiliates_admin_approval_required' ,
                'type'    => 'checkbox' ,
                'default' => 'yes' ,
                'desc'    => esc_html__( 'When enabled, new affiliate applications will be approved only after admin approval.' , FS_AFFILIATES_LOCALE ) ,
            ) ,
            array(
                'title'   => esc_html__( 'When a Logged In User tries to submit an Affiliate Registration Form' , FS_AFFILIATES_LOCALE ) ,
                'desc'    => esc_html__( 'This option controls how the user accounts should be managed for Affiliates.' , FS_AFFILIATES_LOCALE ) ,
                'id'      => 'fs_affiliates_account_creation_type' ,
                'type'    => 'select' ,
                'default' => 'existing_account' ,
                'options' => array(
                    'existing_account' => esc_html__( 'Link Affiliate Account with Existing Account' , FS_AFFILIATES_LOCALE ) ,
                    'new_account'      => esc_html__( 'Create a Separate Affiliate Account' , FS_AFFILIATES_LOCALE ) ,
                    'user_decide'      => esc_html__( 'Allow Users to Decide' , FS_AFFILIATES_LOCALE ) ,
                ) ,
            ) ,
            array(
                'title'     => esc_html__( 'Default Parent Affiliate ' , FS_AFFILIATES_LOCALE ) ,
                'desc'      => esc_html__( 'When a user becomes an affiliate on your site without using any affiliate links, then the affiliate set on this option will be considered as the parent affiliate for the new affiliate.' , FS_AFFILIATES_LOCALE ) ,
                'id'        => 'fs_affiliates_default_affiliate' ,
                'type'      => 'ajaxmultiselect' ,
                'class'     => 'fs_affiliates_selected_affiliate' ,
                'list_type' => 'affiliates' ,
                'action'    => 'fs_affiliates_search' ,
                'multiple'  => false ,
                'default'   => array() ,
            ) ,
            array(
                'type' => 'sectionend' ,
                'id'   => 'fs_affiliates_account_registration_options' ,
            ) ,
            array(
                'type'  => 'title' ,
                'title' => esc_html__( 'Affiliate Campaigns Settings' , FS_AFFILIATES_LOCALE ) ,
                'id'    => 'fs_affiliates_overview_tab_settings' ,
            ) ,
            array(
                'title'   => esc_html__( 'Campaigns Section' , FS_AFFILIATES_LOCALE ) ,
                'id'      => 'fs_affiliates_campaign_field_toggle' ,
                'type'    => 'select' ,
                'class'   => 'fs_affiliates_campaign_field_toggle' ,
                'options' => array(
                    '1' => esc_html__( 'Show' , FS_AFFILIATES_LOCALE ) ,
                    '2' => esc_html__( 'Hide' , FS_AFFILIATES_LOCALE ) ,
                ) ,
                'default' => '1' ,
                'desc'    => esc_html__( 'If Hide" is selected, then campaigns information will not be displayed at frontend dashboard[Overview, Affiliate Tools].' , FS_AFFILIATES_LOCALE ) ,
            ) ,
            array(
                'type' => 'sectionend' ,
                'id'   => 'fs_affiliates_overview_tab_settings' ,
            ) ,
            array(
                'type'  => 'title' ,
                'title' => esc_html__( 'Affiliate Registration Bulk Update Settings' , FS_AFFILIATES_LOCALE ) ,
                'id'    => 'bulk_update_affiliates_options' ,
            ) ,
            array(
                'title'   => esc_html__( 'User Selection' , FS_AFFILIATES_LOCALE ) ,
                'id'      => 'fs_affiliates_user_selection_type' ,
                'type'    => 'select' ,
                'default' => '1' ,
                'options' => array(
                    '1' => esc_html__( 'All Users' , FS_AFFILIATES_LOCALE ) ,
                    '2' => esc_html__( 'Selected Users' , FS_AFFILIATES_LOCALE ) ,
                ) ,
            ) ,
            array(
                'title'       => esc_html__( 'Selected Users' , FS_AFFILIATES_LOCALE ) ,
                'id'          => 'fs_affiliates_selected_users' ,
                'type'        => 'ajaxmultiselect' ,
                'list_type'   => 'customers' ,
                'placeholder' => esc_html__( 'Select a user' , FS_AFFILIATES_LOCALE ) ,
                'action'      => 'fs_user_search' ,
                'default'     => array() ,
            ) ,
            array(
                'type' => 'fs_affiliates_bulk_affiliates' ,
            ) ,
            array(
                'type' => 'sectionend' ,
                'id'   => 'bulk_update_affiliates_options' ,
            ) ,
            array(
                'type'  => 'title' ,
                'title' => esc_html__( 'Affiliate User Role Settings' , FS_AFFILIATES_LOCALE ) ,
                'id'    => 'affiliate_user_role_options' ,
            ) ,
            array(
                'title'   => esc_html__( 'User Role Selection' , FS_AFFILIATES_LOCALE ) ,
                'id'      => 'fs_affiliates_user_role_type' ,
                'type'    => 'select' ,
                'default' => 'subscriber' ,
                'options' => $user_roles ,
                'desc'    => esc_html__( 'The selected user role in this option will assign to the affiliate when registering through the affiliate application form' , FS_AFFILIATES_LOCALE )
            ) ,
            array(
                'type' => 'sectionend' ,
                'id'   => 'affiliate_user_role_options' ,
            ) ,
            array(
                'type'  => 'title' ,
                'title' => esc_html__( 'Google reCAPTCHA Settings' , FS_AFFILIATES_LOCALE ) ,
                'id'    => 'fs_affiliates_recaptcha_options' ,
            ) ,
            array(
                'title'   => esc_html__( 'Display Google reCAPTCHA on' , FS_AFFILIATES_LOCALE ) ,
                'id'      => 'fs_affiliates_recaptcha_registration_page' ,
                'type'    => 'checkbox' ,
                'default' => 'no' ,
                'desc'    => esc_html__( 'Affiliate Registration Page' , FS_AFFILIATES_LOCALE ) ,
            ) ,
            array(
                'title'   => '' ,
                'id'      => 'fs_affiliates_recaptcha_login_page' ,
                'type'    => 'checkbox' ,
                'default' => 'no' ,
                'desc'    => esc_html__( 'Affiliate Login Page' , FS_AFFILIATES_LOCALE ) ,
            ) ,
            array(
                'title'   => esc_html__( 'Site Key' , FS_AFFILIATES_LOCALE ) ,
                'id'      => 'fs_affiliates_recaptcha_site_key' ,
                'type'    => 'text' ,
                'default' => '' ,
                'desc'    => sprintf( esc_html__( 'You can find the site key %s' , FS_AFFILIATES_LOCALE ) , '<a href="https://developers.google.com/recaptcha">' . esc_html__( "here" , FS_AFFILIATES_LOCALE ) . '</a>' ) ,
            ) ,
            array(
                'title'   => esc_html__( 'Secret Key' , FS_AFFILIATES_LOCALE ) ,
                'id'      => 'fs_affiliates_recaptcha_secret_key' ,
                'type'    => 'text' ,
                'default' => '' ,
                'desc'    => sprintf( esc_html__( 'You can find the secret key %s' , FS_AFFILIATES_LOCALE ) , '<a href="https://developers.google.com/recaptcha">' . esc_html__( "here" , FS_AFFILIATES_LOCALE ) . '</a>' ) ,
            ) ,
            array(
                'type' => 'sectionend' ,
                'id'   => 'fs_affiliates_recaptcha_options' ,
            ) , ) ;

        $advances_section[] = array(
            'type'  => 'title' ,
            'title' => esc_html__( 'Referral Deletion Settings' , FS_AFFILIATES_LOCALE ) ,
            'id'    => 'fs_affiliates_account_deletion_options' ,
                ) ;
        $advances_section[] = array(
            'title'   => esc_html__( 'Affiliate Deletion Behaviour' , FS_AFFILIATES_LOCALE ) ,
            'desc'    => esc_html__( 'This option controls how the affiliate data should be deleted. When an affiliate,user is deleted.' , FS_AFFILIATES_LOCALE ) ,
            'id'      => 'fs_affiliates_account_deletion_type' ,
            'type'    => 'select' ,
            'default' => '1' ,
            'options' => array(
                '1' => esc_html__( 'Only Delete Affiliate' , FS_AFFILIATES_LOCALE ) ,
                '2' => esc_html__( 'Delete Affiliate and the User Account' , FS_AFFILIATES_LOCALE ) ,
            ) ,
                ) ;
        $advances_section[] = array(
            'type' => 'sectionend' ,
            'id'   => 'fs_affiliates_account_deletion_options' ,
                ) ;
        $advances_section[] = array(
            'type'  => 'title' ,
            'title' => esc_html__( 'Referral Tracking Settings' , FS_AFFILIATES_LOCALE ) ,
            'id'    => 'fs_affiliates_referral_tracking_options' ,
                ) ;
        $advances_section[] = array(
            'title'   => esc_html__( 'Ignore Referrals with 0 Amount' , FS_AFFILIATES_LOCALE ) ,
            'id'      => 'fs_affiliates_ignore_referrals' ,
            'type'    => 'checkbox' ,
            'default' => 'no' ,
            'desc'    => esc_html__( 'When enabled, Referrals with 0 Commission amount will not be recorded.' , FS_AFFILIATES_LOCALE ) ,
                ) ;
        $advances_section[] = array(
            'title'   => esc_html__( 'Disable IP Logging' , FS_AFFILIATES_LOCALE ) ,
            'id'      => 'fs_affiliates_disable_ip_logging' ,
            'type'    => 'checkbox' ,
            'default' => 'no' ,
            'desc'    => esc_html__( 'When enabled, IP Address of the users will not be captured in the visits table.' , FS_AFFILIATES_LOCALE ) ,
                ) ;
        $advances_section[] = array(
            'title'   => esc_html__( 'Affiliate Restriction' , FS_AFFILIATES_LOCALE ) ,
            'id'      => 'fs_affiliates_link_restriction' ,
            'type'    => 'checkbox' ,
            'default' => 'no' ,
            'desc'    => esc_html__( 'By enabling this checkbox, the affiliate cannot access the affiliate link given by another affiliate' , FS_AFFILIATES_LOCALE ) ,
                ) ;
        $advances_section[] = array(
            'type' => 'sectionend' ,
            'id'   => 'fs_affiliates_referral_tracking_options' ,
                ) ;
//        $advances_section[] = array (
//            'type'  => 'title' ,
//            'title' => esc_html__( 'File Upload Settings' , FS_AFFILIATES_LOCALE ) ,
//            'id'    => 'fs_affiliates_file_upload_options' ,
//                ) ;
//        $advances_section[] = array (
//            'title'   => esc_html__( 'Allow Affiliates to Delete the Uploaded Files' , FS_AFFILIATES_LOCALE ) ,
//            'id'      => 'fs_affiliates_allow_to_delete_uploaded_files' ,
//            'type'    => 'checkbox' ,
//            'default' => 'no' ,
//                ) ;
//        $advances_section[] = array (
//            'title'   => esc_html__( 'File Attachment Max File Size' , FS_AFFILIATES_LOCALE ) ,
//            'id'      => 'fs_affiliates_max_file_size' ,
//            'type'    => 'number' ,
//            'default' => '2' ,
//                ) ;
//        $advances_section[] = array (
//            'title'   => esc_html__( 'Restricted File Types' , FS_AFFILIATES_LOCALE ) ,
//            'id'      => 'fs_affiliates_restricted_file_types' ,
//            'type'    => 'textarea' ,
//            'default' => '' ,
//                ) ;
//        $advances_section[] = array (
//            'type' => 'sectionend' ,
//            'id'   => 'fs_affiliates_file_upload_options' ,
//                ) ;
        $advances_section[] = array(
            'type'  => 'title' ,
            'title' => esc_html__( 'Frontend Dashboard Customization' , FS_AFFILIATES_LOCALE ) ,
            'id'    => 'fs_affiliates_dashboard_customization' ,
                ) ;
        $advances_section[] = array(
            'title'   => esc_html__( 'Dashboard Style' , FS_AFFILIATES_LOCALE ) ,
            'id'      => 'fs_affiliates_frontend_dashboard_customization' ,
            'type'    => 'select' ,
            'options' => array(
                '1'  => esc_html__( 'Style 1' , FS_AFFILIATES_LOCALE ) ,
                '2'  => esc_html__( 'Style 2' , FS_AFFILIATES_LOCALE ) ,
                '3'  => esc_html__( 'Style 3' , FS_AFFILIATES_LOCALE ) ,
                '4'  => esc_html__( 'Style 4' , FS_AFFILIATES_LOCALE ) ,
                '5'  => esc_html__( 'Style 5' , FS_AFFILIATES_LOCALE ) ,
                '6'  => esc_html__( 'Style 6' , FS_AFFILIATES_LOCALE ) ,
                '7'  => esc_html__( 'Style 7' , FS_AFFILIATES_LOCALE ) ,
                '8'  => esc_html__( 'Style 8' , FS_AFFILIATES_LOCALE ) ,
                '9'  => esc_html__( 'Style 9' , FS_AFFILIATES_LOCALE ) ,
                '10' => esc_html__( 'Style 10' , FS_AFFILIATES_LOCALE ) ,
            ) ,
            'default' => '1' ,
                ) ;
        $advances_section[] = array(
            'type' => 'sectionend' ,
            'id'   => 'fs_affiliates_dashboard_customization' ,
                ) ;
        $advances_section[] = array(
            'type'  => 'title' ,
            'title' => esc_html__( 'Menu Restriction Settings Based on User Role' , FS_AFFILIATES_LOCALE ) ,
            'id'    => 'menu_restrictions_settings' ,
                ) ;

        global $wp_roles ;

        $tabs = apply_filters( 'fs_affiliates_settings_tabs_array' , array() ) ;

        foreach ( $wp_roles->role_names as $role_key => $role_name ) {
            if ( $role_key == 'administrator' || $role_key == 'customer' )
                continue ;

            $advances_section[] = array(
                'title'   => sprintf( esc_html__( 'Menu Restriction for %s User Role' , FS_AFFILIATES_LOCALE ) , $role_name ) ,
                'desc'    => sprintf( esc_html__( 'Restrict the menus for %s user role' , FS_AFFILIATES_LOCALE ) , $role_name ) ,
                'class'   => 'fs_affiliates_select2 fs_affiliates_restrict_settings_menus' ,
                'id'      => 'fs_affiliates_restrict_setting_menu_for_' . $role_key ,
                'type'    => 'multiselect' ,
                'options' => $tabs ,
                'default' => array() ,
                    ) ;
        }
        $advances_section[] = array(
            'type' => 'sectionend' ,
            'id'   => 'menu_restrictions_settings' ,
                ) ;
        $advances_section[] = array(
            'type'  => 'title' ,
            'title' => esc_html__( 'Frontend Pagination Settings' , FS_AFFILIATES_LOCALE ) ,
            'id'    => 'posttable_settings_options' ,
                ) ;
        $advances_section[] = array(
            'title'             => esc_html__( 'Creatives Table' , FS_AFFILIATES_LOCALE ) ,
            'id'                => 'fs_affiliates_creatives_per_page_count' ,
            'type'              => 'number' ,
            'default'           => '5' ,
            'custom_attributes' => array( 'min' => '1' ) ,
            'desc'              => esc_html__( 'Enter the number of entries to display in each page' , FS_AFFILIATES_LOCALE ) ,
                ) ;
        $advances_section[] = array(
            'title'             => esc_html__( 'Referrals Table' , FS_AFFILIATES_LOCALE ) ,
            'id'                => 'fs_affiliates_referrals_per_page_count' ,
            'type'              => 'number' ,
            'default'           => '5' ,
            'custom_attributes' => array( 'min' => '1' ) ,
            'desc'              => esc_html__( 'Enter the number of entries to display in each page' , FS_AFFILIATES_LOCALE ) ,
                ) ;

        $advances_section[] = array(
            'title'             => esc_html__( 'Visits Table' , FS_AFFILIATES_LOCALE ) ,
            'id'                => 'fs_affiliates_visits_per_page_count' ,
            'type'              => 'number' ,
            'default'           => '5' ,
            'custom_attributes' => array( 'min' => '1' ) ,
            'desc'              => esc_html__( 'Enter the number of entries to display in each page' , FS_AFFILIATES_LOCALE ) ,
                ) ;
        $advances_section[] = array(
            'title'             => esc_html__( 'Payments Table' , FS_AFFILIATES_LOCALE ) ,
            'id'                => 'fs_affiliates_payments_per_page_count' ,
            'type'              => 'number' ,
            'default'           => '5' ,
            'custom_attributes' => array( 'min' => '1' ) ,
            'desc'              => esc_html__( 'Enter the number of entries to display in each page' , FS_AFFILIATES_LOCALE ) ,
                ) ;
        $advances_section[] = array(
            'title'             => esc_html__( 'Wallet Table' , FS_AFFILIATES_LOCALE ) ,
            'id'                => 'fs_affiliates_wallet_per_page_count' ,
            'type'              => 'number' ,
            'default'           => '5' ,
            'custom_attributes' => array( 'min' => '1' ) ,
            'desc'              => esc_html__( 'Enter the number of entries to display in each page' , FS_AFFILIATES_LOCALE ) ,
                ) ;
        $advances_section[] = array(
            'type' => 'sectionend' ,
            'id'   => 'posttable_settings_options' ,
                ) ;

        $advances_section[] = array(
            'type'  => 'title' ,
            'title' => esc_html__( 'Plugin Menu Display Settings' , FS_AFFILIATES_LOCALE ) ,
            'id'    => 'fs_affiliates_settings_menu_disp_type' ,
                ) ;
        $advances_section[] = array(
            'title'   => esc_html__( 'Display Plugin Menu for' , FS_AFFILIATES_LOCALE ) ,
            'id'      => 'fs_affiliates_menu_disp_type' ,
            'type'    => 'select' ,
            'default' => '0' ,
            'options' => array( '0' => 'Administrator Only' , '1' => 'Administrator & Shop Manager' )
                ) ;
        $advances_section[] = array(
            'type' => 'sectionend' ,
            'id'   => 'fs_affiliates_settings_menu_disp_type' ,
                ) ;

        $advances_section[] = array(
            'type'  => 'title' ,
            'title' => esc_html__( 'Custom CSS Settings' , FS_AFFILIATES_LOCALE ) ,
            'id'    => 'fs_affiliates_custom_css_options' ,
                ) ;
        $content            = '' ;
        $default_classes    = fs_affiliates_get_default_classes() ;
        foreach ( $default_classes as $each_class ) {
            $content .= $each_class . '{  }' ;
        }
        $advances_section[] = array(
            'title'             => esc_html__( 'Custom CSS' , FS_AFFILIATES_LOCALE ) ,
            'id'                => 'fs_affiliates_frontend_custom_css' ,
            'type'              => 'textarea' ,
            'default'           => $content ,
            'custom_attributes' => array( 'rows' => '12' )
                ) ;
        $advances_section[] = array(
            'type' => 'sectionend' ,
            'id'   => 'fs_affiliates_custom_css_options' ,
                ) ;

        return $advances_section ;
    }

    /**
     * Get settings localization section array.
     */
    public function localization_section_fields() {
        $localization_section     = array() ;
        $localization_section[]   = array(
            'type'  => 'title' ,
            'title' => esc_html__( 'Email Settings' , FS_AFFILIATES_LOCALE ) ,
            'id'    => 'fs_affiliates_email_options' ,
                ) ;
        $localization_section[]   = array(
            'title'   => esc_html__( 'Email From Name' , FS_AFFILIATES_LOCALE ) ,
            'id'      => 'fs_affiliates_email_from_name' ,
            'type'    => 'text' ,
            'default' => esc_attr( get_bloginfo( 'name' , 'display' ) ) ,
            'desc'    => esc_html__( 'This name will be used as the From Name for all Affiliate Related Emails.' , FS_AFFILIATES_LOCALE ) ,
                ) ;
        $localization_section[]   = array(
            'title'   => esc_html__( 'Email From Address' , FS_AFFILIATES_LOCALE ) ,
            'id'      => 'fs_affiliates_email_from_address' ,
            'type'    => 'text' ,
            'default' => esc_attr( get_bloginfo( 'admin_email' , 'display' ) ) ,
            'desc'    => esc_html__( 'This email will be used as the From Email for all Affiliate Related Emails.' , FS_AFFILIATES_LOCALE ) ,
                ) ;
        $localization_section[]   = array(
            'title'   => esc_html__( 'Admin Email Address' , FS_AFFILIATES_LOCALE ) ,
            'id'      => 'fs_affiliates_admin_emails' ,
            'type'    => 'textarea' ,
            'default' => get_option( 'fs_affiliates_email_from_address' ) ,
            'desc'    => esc_html__( 'This email id will be used for Admin to receive the Affiliate Related Emails. You can use multiple email id by using a comma separator.' , FS_AFFILIATES_LOCALE ) ,
                ) ;
        $localization_section[]   = array(
            'type' => 'sectionend' ,
            'id'   => 'fs_affiliates_email_options' ,
                ) ;
        $localization_section[]   = array(
            'type'  => 'title' ,
            'title' => esc_html__( 'Account Registration Customization Settings' , FS_AFFILIATES_LOCALE ) ,
            'id'    => 'fs_affiliates_registration_customization' ,
                ) ;
        $localization_section[]   = array(
            'title'   => esc_html__( 'Registered User Restriction Message' , FS_AFFILIATES_LOCALE ) ,
            'id'      => 'fs_affiliates_user_restriction_msg' ,
            'type'    => 'text' ,
            'default' => 'Logged in user cannot register as an affiliate.' ,
                ) ;
        $localization_section[]   = array(
            'title'   => esc_html__( 'Guest Restriction Message' , FS_AFFILIATES_LOCALE ) ,
            'id'      => 'fs_affiliates_guest_restriction_msg' ,
            'type'    => 'text' ,
            'default' => 'Guest user cannot register as an affiliate.' ,
                ) ;
        $localization_section[]   = array(
            'type' => 'sectionend' ,
            'id'   => 'fs_affiliates_registration_customization' ,
                ) ;
        $localization_section[]   = array(
            'type'  => 'title' ,
            'title' => esc_html__( 'Affiliate Dashboard Label Customization' , FS_AFFILIATES_LOCALE ) ,
            'id'    => 'fs_affiliates_dashboard_customizations_options' ,
                ) ;
        $localization_section[]   = array(
            'title'   => esc_html__( 'Overview Label' , FS_AFFILIATES_LOCALE ) ,
            'id'      => 'fs_affiliates_dashboard_customization_overview_label' ,
            'type'    => 'text' ,
            'default' => 'Overview' ,
                ) ;
        $localization_section[]   = array(
            'title'   => esc_html__( 'Affiliate Tools Label' , FS_AFFILIATES_LOCALE ) ,
            'id'      => 'fs_affiliates_dashboard_customization_tools_label' ,
            'type'    => 'text' ,
            'default' => 'Affiliate Tools' ,
                ) ;
        $wc_coupon_linking_module = FS_Affiliates_Module_Instances::get_module_by_id( 'wc_coupon_linking' ) ;
        if ( $wc_coupon_linking_module->is_enabled() ) {
            $localization_section[] = array(
                'title'   => esc_html__( 'Affiliate Coupon Label' , FS_AFFILIATES_LOCALE ) ,
                'id'      => 'fs_affiliates_dashboard_customization_coupon_label' ,
                'type'    => 'text' ,
                'default' => 'Linked Coupon(s)' ,
                    ) ;
        }
        $localization_section[] = array(
            'title'   => esc_html__( 'Campaigns Label' , FS_AFFILIATES_LOCALE ) ,
            'id'      => 'fs_affiliates_dashboard_customization_campaigns_label' ,
            'type'    => 'text' ,
            'default' => 'Campaigns' ,
                ) ;
        $localization_section[] = array(
            'title'   => esc_html__( 'Affiliate Links Label' , FS_AFFILIATES_LOCALE ) ,
            'id'      => 'fs_affiliates_dashboard_customization_links_label' ,
            'type'    => 'text' ,
            'default' => 'Affiliate Links' ,
                ) ;
        $localization_section[] = array(
            'title'   => esc_html__( 'Creatives Label' , FS_AFFILIATES_LOCALE ) ,
            'id'      => 'fs_affiliates_dashboard_customization_creatives_label' ,
            'type'    => 'text' ,
            'default' => 'Creatives' ,
                ) ;
        $localization_section[] = array(
            'title'   => esc_html__( 'Refer a Friend Form Label' , FS_AFFILIATES_LOCALE ) ,
            'id'      => 'fs_affiliates_dashboard_customization_friend_form_label' ,
            'type'    => 'text' ,
            'default' => 'Refer a Friend Form' ,
                ) ;
        $localization_section[] = array(
            'title'   => esc_html__( 'Referrals Label' , FS_AFFILIATES_LOCALE ) ,
            'id'      => 'fs_affiliates_dashboard_customization_referrals_label' ,
            'type'    => 'text' ,
            'default' => 'Referrals' ,
                ) ;
        $localization_section[] = array(
            'title'   => esc_html__( 'Visits Label' , FS_AFFILIATES_LOCALE ) ,
            'id'      => 'fs_affiliates_dashboard_customization_visits_label' ,
            'type'    => 'text' ,
            'default' => 'Visits' ,
                ) ;
        $localization_section[] = array(
            'title'   => esc_html__( 'Payouts Label' , FS_AFFILIATES_LOCALE ) ,
            'id'      => 'fs_affiliates_dashboard_customization_payouts_label' ,
            'type'    => 'text' ,
            'default' => 'Payments' ,
                ) ;
        $localization_section[] = array(
            'title'   => esc_html__( 'Profile Label' , FS_AFFILIATES_LOCALE ) ,
            'id'      => 'fs_affiliates_dashboard_customization_profile_label' ,
            'type'    => 'text' ,
            'default' => 'Profile' ,
                ) ;
        $localization_section[] = array(
            'title'   => esc_html__( 'Basic Details Label' , FS_AFFILIATES_LOCALE ) ,
            'id'      => 'fs_affiliates_dashboard_customization_basic_details_label' ,
            'type'    => 'text' ,
            'default' => 'Basic Details' ,
                ) ;
        $localization_section[] = array(
            'title'   => esc_html__( 'Account Management Label' , FS_AFFILIATES_LOCALE ) ,
            'id'      => 'fs_affiliates_dashboard_customization_acc_mgmt_label' ,
            'type'    => 'text' ,
            'default' => 'Account Management' ,
                ) ;
        $localization_section[] = array(
            'title'   => esc_html__( 'Payment Management Label' , FS_AFFILIATES_LOCALE ) ,
            'id'      => 'fs_affiliates_dashboard_customization_payment_mgmt_label' ,
            'type'    => 'text' ,
            'default' => 'Payment Management' ,
                ) ;
        $localization_section[] = array(
            'title'   => esc_html__( 'Logout Label' , FS_AFFILIATES_LOCALE ) ,
            'id'      => 'fs_affiliates_dashboard_customization_logout_label' ,
            'type'    => 'text' ,
            'default' => 'Logout' ,
                ) ;
        $localization_section[] = array(
            'type' => 'sectionend' ,
            'id'   => 'fs_affiliates_dashboard_customizations_options' ,
                ) ;
        $localization_section[] = array(
            'type'  => 'title' ,
            'title' => esc_html__( 'Referral Description Settings' , FS_AFFILIATES_LOCALE ) ,
            'id'    => 'fs_affiliates_referral_desc_options' ,
                ) ;
        $localization_section[] = array(
            'title'   => esc_html__( 'Email Subscription Label' , FS_AFFILIATES_LOCALE ) ,
            'id'      => 'fs_affiliates_referral_desc_email_subs_label' ,
            'type'    => 'text' ,
            'default' => 'Email Subscription' ,
                ) ;
        $localization_section[] = array(
            'title'   => esc_html__( 'Contact Form 7 Label' , FS_AFFILIATES_LOCALE ) ,
            'id'      => 'fs_affiliates_referral_desc_contact7_label' ,
            'type'    => 'text' ,
            'default' => 'Contact Form 7' ,
            'desc'    => __( '<b>Note:</b> Use this shortcode {username} to display the username who submitted the form using an affiliate link' , FS_AFFILIATES_LOCALE ) ,
                ) ;
        $localization_section[] = array(
            'title'   => esc_html__( 'Formidable Forms Label' , FS_AFFILIATES_LOCALE ) ,
            'id'      => 'fs_affiliates_referral_desc_fomidable_form_label' ,
            'type'    => 'text' ,
            'default' => 'Formidable Forms' ,
                ) ;
        $localization_section[] = array(
            'title'   => esc_html__( 'WP Forms Label' , FS_AFFILIATES_LOCALE ) ,
            'id'      => 'fs_affiliates_referral_desc_wp_form_label' ,
            'type'    => 'text' ,
            'default' => 'WP Forms' ,
                ) ;
        $localization_section[] = array(
            'title'   => esc_html__( 'WooCommerce Signup Label' , FS_AFFILIATES_LOCALE ) ,
            'id'      => 'fs_affiliates_referral_desc_woocommerce_signup_label' ,
            'type'    => 'text' ,
            'default' => 'WooCommerce Signup' ,
                ) ;
        $localization_section[] = array(
            'title'   => esc_html__( 'Affiliate Signup Label' , FS_AFFILIATES_LOCALE ) ,
            'id'      => 'fs_affiliates_referral_desc_affs_signup_label' ,
            'type'    => 'text' ,
            'default' => 'Affiliate Signup' ,
                ) ;
        $localization_section[] = array(
            'title'   => esc_html__( 'Landing Commission Label' , FS_AFFILIATES_LOCALE ) ,
            'id'      => 'fs_affiliates_referral_desc_landing_commission_label' ,
            'type'    => 'text' ,
            'default' => 'Landing Commission' ,
                ) ;
        $localization_section[] = array(
            'title'   => esc_html__( 'MLM Label' , FS_AFFILIATES_LOCALE ) ,
            'id'      => 'fs_affiliates_referral_desc_mlm_label' ,
            'type'    => 'text' ,
            'default' => 'MLM Level {affiliate_level} Commission for {referral_actions}' ,
                ) ;
        $localization_section[] = array(
            'title'   => esc_html__( 'Split Commission Label' , FS_AFFILIATES_LOCALE ) ,
            'id'      => 'fs_affiliates_referral_desc_split_commission_label' ,
            'type'    => 'text' ,
            'default' => 'Split Commission for Affiliate {affiliate_level}' ,
                ) ;
        $localization_section[] = array(
            'title'   => esc_html__( 'WooCommerce Order Label' , FS_AFFILIATES_LOCALE ) ,
            'id'      => 'fs_affiliates_referral_desc_woocommerce_order_label' ,
            'type'    => 'text' ,
            'default' => 'WooCommerce Order' ,
            'desc'    => esc_html__( 'Note: Use this shortcode {product_name} to display the product name' , FS_AFFILIATES_LOCALE ) ,
                ) ;
        $localization_section[] = array(
            'title'   => esc_html__( 'Signup Bonus Label' , FS_AFFILIATES_LOCALE ) ,
            'id'      => 'fs_affiliates_referral_desc_signup_bonus_label' ,
            'type'    => 'text' ,
            'default' => 'Signup Bonus' ,
                ) ;
        $localization_section[] = array(
            'type' => 'sectionend' ,
            'id'   => 'fs_affiliates_referral_desc_options' ,
                ) ;

        return $localization_section ;
    }

    /**
     * Get settings form customizations section array.
     */
    public function form_customization_section_fields() {

        return array(
            array(
                'type'  => 'title' ,
                'title' => esc_html__( 'Registration Form Customization' , FS_AFFILIATES_LOCALE ) ,
                'id'    => 'register_form_customizations_options' ,
            ) ,
            array(
                'title'   => esc_html__( 'Registration Form Style' , FS_AFFILIATES_LOCALE ) ,
                'desc'    => esc_html__( 'Select the registration form style which you want to display to your user. A Preview of the form can be viewed below.' , FS_AFFILIATES_LOCALE ) ,
                'id'      => 'fs_affiliates_register_form_style' ,
                'default' => '1' ,
                'type'    => 'select' ,
                'options' => array(
                    '1' => esc_html__( 'Style 1' , FS_AFFILIATES_LOCALE ) ,
                    '2' => esc_html__( 'Style 2' , FS_AFFILIATES_LOCALE ) ,
                    '3' => esc_html__( 'Style 3' , FS_AFFILIATES_LOCALE ) ,
                    '4' => esc_html__( 'Style 4' , FS_AFFILIATES_LOCALE )
                ) ,
            ) ,
            array(
                'title'   => esc_html__( 'Form Title Color' , FS_AFFILIATES_LOCALE ) ,
                'id'      => 'fs_affiliates_register_form_title_color' ,
                'type'    => 'colorpicker' ,
                'default' => '#000000' ,
                'desc'    => esc_html__( 'This color picker controls the color of the Form Title.' , FS_AFFILIATES_LOCALE ) ,
            ) ,
            array(
                'title'   => esc_html__( 'Form Field Color' , FS_AFFILIATES_LOCALE ) ,
                'id'      => 'fs_affiliates_register_form_field_color' ,
                'type'    => 'colorpicker' ,
                'default' => '#000000' ,
                'desc'    => esc_html__( 'This color picker controls the colors of fields like First Name, Last Name, etc.' , FS_AFFILIATES_LOCALE ) ,
            ) ,
            array(
                'title'   => esc_html__( 'Form Button Color' , FS_AFFILIATES_LOCALE ) ,
                'id'      => 'fs_affiliates_register_form_button_color' ,
                'type'    => 'colorpicker' ,
                'default' => '#ffffff' ,
                'desc'    => esc_html__( 'This color picker controls the color of the form buttons.' , FS_AFFILIATES_LOCALE ) ,
            ) ,
            array(
                'title'   => esc_html__( 'Form Button Background Color' , FS_AFFILIATES_LOCALE ) ,
                'id'      => 'fs_affiliates_register_form_button_background_color' ,
                'type'    => 'colorpicker' ,
                'default' => '#52ac67' ,
                'desc'    => esc_html__( 'This color picker controls the background color of the form buttons.' , FS_AFFILIATES_LOCALE ) ,
            ) ,
            array(
                'title'   => esc_html__( 'Form Background Color' , FS_AFFILIATES_LOCALE ) ,
                'id'      => 'fs_affiliates_register_form_background_color' ,
                'type'    => 'colorpicker' ,
                'default' => '#ffffff' ,
                'desc'    => esc_html__( 'This color picker controls the background color of the registration form.' , FS_AFFILIATES_LOCALE ) ,
            ) ,
            array(
                'title'   => esc_html__( 'Form Border Color' , FS_AFFILIATES_LOCALE ) ,
                'id'      => 'fs_affiliates_register_form_border_color' ,
                'type'    => 'colorpicker' ,
                'default' => '#000000' ,
                'desc'    => esc_html__( 'This color picker controls the border color of the login form.' , FS_AFFILIATES_LOCALE ) ,
            ) ,
            array(
                'type' => 'sectionend' ,
                'id'   => 'register_form_customizations_options' ,
            ) ,
            array(
                'type'  => 'title' ,
                'title' => esc_html__( 'Login Form Customization' , FS_AFFILIATES_LOCALE ) ,
                'id'    => 'login_form_customizations_options' ,
            ) ,
            array(
                'title'   => esc_html__( 'Login Form Style' , FS_AFFILIATES_LOCALE ) ,
                'desc'    => esc_html__( 'Select the login form style which you want to display to your user. A Preview of the form can be viewed below.' , FS_AFFILIATES_LOCALE ) ,
                'id'      => 'fs_affiliates_login_form_style' ,
                'default' => '1' ,
                'type'    => 'select' ,
                'options' => array(
                    '1' => esc_html__( 'Style 1' , FS_AFFILIATES_LOCALE ) ,
                    '2' => esc_html__( 'Style 2' , FS_AFFILIATES_LOCALE ) ,
                    '3' => esc_html__( 'Style 3' , FS_AFFILIATES_LOCALE ) ,
                    '4' => esc_html__( 'Style 4' , FS_AFFILIATES_LOCALE ) ,
                    '5' => esc_html__( 'Style 5' , FS_AFFILIATES_LOCALE )
                ) ,
            ) ,
            array(
                'title'   => esc_html__( 'Form Title Color' , FS_AFFILIATES_LOCALE ) ,
                'id'      => 'fs_affiliates_login_form_title_color' ,
                'type'    => 'colorpicker' ,
                'default' => '#000000' ,
                'desc'    => esc_html__( 'This color picker controls the color of the Form Title.' , FS_AFFILIATES_LOCALE ) ,
            ) ,
            array(
                'title'   => esc_html__( 'Form Field Color' , FS_AFFILIATES_LOCALE ) ,
                'id'      => 'fs_affiliates_login_form_field_color' ,
                'type'    => 'colorpicker' ,
                'default' => '#000000' ,
                'desc'    => esc_html__( 'This color picker controls the colors of fields like First Name, Last Name, etc.' , FS_AFFILIATES_LOCALE ) ,
            ) ,
            array(
                'title'   => esc_html__( 'Form Button Color' , FS_AFFILIATES_LOCALE ) ,
                'id'      => 'fs_affiliates_login_form_button_color' ,
                'type'    => 'colorpicker' ,
                'default' => '#ffffff' ,
                'desc'    => esc_html__( 'This color picker controls the color of the form buttons.' , FS_AFFILIATES_LOCALE ) ,
            ) ,
            array(
                'title'   => esc_html__( 'Form Button Background Color' , FS_AFFILIATES_LOCALE ) ,
                'id'      => 'fs_affiliates_login_form_button_background_color' ,
                'type'    => 'colorpicker' ,
                'default' => '#52ac67' ,
                'desc'    => esc_html__( 'This color picker controls the background color of the form buttons.' , FS_AFFILIATES_LOCALE ) ,
            ) ,
            array(
                'title'   => esc_html__( 'Form Background Color' , FS_AFFILIATES_LOCALE ) ,
                'id'      => 'fs_affiliates_login_form_background_color' ,
                'type'    => 'colorpicker' ,
                'default' => '#ffffff' ,
                'desc'    => esc_html__( 'This color picker controls the background color of the registration form.' , FS_AFFILIATES_LOCALE ) ,
            ) ,
            array(
                'title'   => esc_html__( 'Form Border Color' , FS_AFFILIATES_LOCALE ) ,
                'id'      => 'fs_affiliates_login_form_border_color' ,
                'type'    => 'colorpicker' ,
                'default' => '#000000' ,
                'desc'    => esc_html__( 'This color picker controls the border color of the login form.' , FS_AFFILIATES_LOCALE ) ,
            ) ,
            array(
                'type' => 'sectionend' ,
                'id'   => 'login_form_customizations_options' ,
            ) ,
                ) ;
    }

    /**
     * Get settings default section array.
     */
    public function default_section_fields() {
        $page_ids = fs_affiliates_get_page_ids() ;
        return array(
            array(
                'type' => 'title' ,
                'id'   => 'fs_affiliates_registration' ,
            ) ,
            array(
                'title'   => esc_html__( 'Affiliate Registration Method' , FS_AFFILIATES_LOCALE ) ,
                'id'      => 'fs_affiliates_registration_method' ,
                'type'    => 'select' ,
                'default' => 'basic' ,
                'options' => array( 'basic' => esc_html__( 'Basic' ) , 'advanced' => esc_html__( 'Advanced' ) ) ,
                'desc'    => esc_html__( '<b>Basic</b> – Affiliate registration form and login form will be displayed on the same page
                                  < /br><b>Advanced</b> - Affiliate registration form and login form will be displayed on two separate pages' , FS_AFFILIATES_LOCALE ) ,
            ) ,
            array(
                'title'   => esc_html__( 'Affiliate Registration Page' , FS_AFFILIATES_LOCALE ) ,
                'desc'    => esc_html__( 'Page to display the Advanced Mode Signup Page' , FS_AFFILIATES_LOCALE ) ,
                'id'      => 'fs_affiliates_register_page_id' ,
                'class'   => 'fs_affiliates_advanced_registration' ,
                'default' => fs_affiliates_get_page_id( 'register' ) ,
                'type'    => 'select' ,
                'options' => $page_ids ,
            ) ,
            array(
                'title'   => esc_html__( 'Affiliate Login Page' , FS_AFFILIATES_LOCALE ) ,
                'desc'    => esc_html__( 'Page to display the Advanced Mode Login Page' , FS_AFFILIATES_LOCALE ) ,
                'id'      => 'fs_affiliates_login_page_id' ,
                'class'   => 'fs_affiliates_advanced_registration' ,
                'default' => fs_affiliates_get_page_id( 'login' ) ,
                'type'    => 'select' ,
                'options' => $page_ids ,
            ) ,
            array(
                'title'   => esc_html__( 'Affiliate Dashboard Page' , FS_AFFILIATES_LOCALE ) ,
                'desc'    => esc_html__( 'Page to display the Advanced Mode Dashboard Page' , FS_AFFILIATES_LOCALE ) ,
                'id'      => 'fs_affiliates_dashboard_page_id' ,
                'default' => fs_affiliates_get_page_id( 'dashboard' ) ,
                'type'    => 'select' ,
                'options' => $page_ids ,
            ) ,
            array(
                'title'   => esc_html__( 'Terms of Use Page' , FS_AFFILIATES_LOCALE ) ,
                'desc'    => esc_html__( 'Page to display the terms of conditions of the affiliate program. This page will be used as a hyperlink in the affiliate registration form.' , FS_AFFILIATES_LOCALE ) ,
                'id'      => 'fs_affiliates_terms_page_id' ,
                'default' => fs_affiliates_get_page_id( 'terms' ) ,
                'type'    => 'select' ,
                'options' => $page_ids ,
            ) ,
            array(
                'title'   => esc_html__( 'Lost Password Page' , FS_AFFILIATES_LOCALE ) ,
                'desc'    => esc_html__( 'Page to display the lost password form. This page will be used as a hyperlink in the affiliate login form.' , FS_AFFILIATES_LOCALE ) ,
                'id'      => 'fs_affiliates_lost_password_page_id' ,
                'default' => fs_affiliates_get_page_id( 'lost_password' ) ,
                'type'    => 'select' ,
                'options' => $page_ids ,
            ) ,
            array(
                'type' => 'sectionend' ,
                'id'   => 'fs_affiliates_registration' ,
            )
                ) ;
    }

    /**
     * Output the settings buttons.
     */
    public function output_buttons() {
        global $current_section , $current_sub_section ;

        if ( $current_section != 'frontend_form' ) {
            FS_Affiliates_Settings::output_buttons() ;
        }
    }

    /*
     * Output Frontend Form
     */

    public function output_frontend_form() {
        global $current_sub_section ;
        switch ( $current_sub_section ) {
            case 'edit' :
                $this->display_edit_page() ;
                break ;
            default:
                $this->display_table() ;
                break ;
        }
    }

    /*
     * Output Affiliate Bulk update
     */

    public function output_affiliate_bulk_update() {
        ?>
        <tr>
            <td>
            </td>
            <td>
                <input type="button" class="fs_affiliates_bulk_update_affiliates button-primary" value="<?php _e( 'Register as Affiliate' , FS_AFFILIATES_LOCALE ) ?>"/>
            </td>
        <tr>
            <?php
        }

        /*
         * Output Affiliate Output Table
         */

        public function output_payment_preference_table() {
            $status = fs_affiliates_get_paymethod_preference_status() ;

            $available_payments = is_array( get_option( 'fs_affiliates_payment_preference' , array( 'direct' => 'enable' , 'paypal' => 'enable' , 'wallet' => 'enable' ) ) ) ? get_option( 'fs_affiliates_payment_preference' , array( 'direct' => 'enable' , 'paypal' => 'enable' , 'wallet' => 'enable' ) ) + $status : $status ;
            ?>
        <tbody id="fs_affiliates_payment_settings_table">
            <?php
            foreach ( $available_payments as $pay_key => $status ) {

                $payment_label = fs_affiliates_get_paymethod_preference( $pay_key ) ;

                if ( $payment_label == '' ) {
                    continue ;
                }
                ?>
                <tr>
                    <td>
                        <label><?php echo $payment_label ; ?></label>
                        <input type="hidden" name="sorted_payments_label[<?php echo $pay_key ; ?>]" value="<?php echo $payment_label ; ?>" >
                    </td>
                    <td>
                        <select name="sorted_payments_status[<?php echo $pay_key ; ?>]" >
                            <option <?php if ( $status == 'enable' ) { ?> selected="" <?php } ?> value="<?php echo 'enable' ; ?>">Enable</option>
                            <option <?php if ( $status == 'disable' ) { ?> selected="" <?php } ?> value="<?php echo 'disable' ; ?>">Disable</option>
                        </select>
                        <input type="hidden" name="sorted_payments_demo[]" value ="<?php echo $pay_key ; ?>" >
                    </td>
                    <td class="sort fs_affiliates_payments_sort_handle" style="cursor: move;" >
                        <img src="<?php echo FS_AFFILIATES_PLUGIN_URL . '/assets/images/others/drag-icon.png' ; ?> "></img>
                    </td>
                <tr>
                <?php }
                ?>
        </tbody>
        <?php
    }

    /**
     * Output the frontend form settings
     */
    public function display_table() {
        if ( ! class_exists( 'FS_Affiliates_Form_Fields_Post_Table' ) ) {
            require_once( FS_AFFILIATES_PLUGIN_PATH . '/inc/admin/menu/wp-list-table/class-fs-affiliates-form-fields-table.php' ) ;
        }

        echo '<div class="' . $this->plugin_slug . '_table_wrap">' ;
        echo '<h2 class="wp-heading-inline">' . esc_html__( 'Frontend Registration Form Fields' , FS_AFFILIATES_LOCALE ) . '</h2>' ;
        $post_table = new FS_Affiliates_Form_Fields_Post_Table() ;
        $post_table->prepare_items() ;
        $post_table->display() ;
        echo '</div>' ;
    }

    /**
     * Output the edit frontend form settings
     */
    public function display_edit_page() {
        if ( ! isset( $_GET[ 'id' ] ) )
            return ;

        $field_key = $_GET[ 'id' ] ;
        $fields    = fs_affiliates_get_form_fields() ;

        if ( ! isset( $fields[ $field_key ] ) )
            return ;

        $field = $fields[ $field_key ] ;
        extract( $field ) ;

        $hide_place_fields      = array( 'country' ) ;
        $disabled_status_fields = array( 'email' , 'user_name' , 'password' ) ;
        $disabled_type_fields   = array( 'email' , 'user_name' , 'password' , 'repeated_password' ) ;


        $disabled_status  = in_array( $field_key , $disabled_status_fields ) ? 'disabled="disabled"' : '' ;
        $disabled_type    = in_array( $field_key , $disabled_type_fields ) ? 'disabled="disabled"' : '' ;
        $hide_placeholder = in_array( $field_key , $hide_place_fields ) ? true : false ;

        include_once( FS_AFFILIATES_PLUGIN_PATH . '/inc/admin/menu/views/form-fields-edit.php' ) ;
    }

    /*
     * Extra Fields
     */

    public function output_extra_fields() {
        global $current_section ;

        if ( $current_section != 'form_customization' )
            return ;

        /* Include css */
        include_once(FS_AFFILIATES_PLUGIN_PATH . '/assets/css/frontend/form.php') ;

        $account_type = fs_affiliates_get_account_creation_type() ;
        $fields       = fs_affiliates_get_form_fields() ;
        ?>
        <div class="fs_affiliates_form_preview_wrap">
            <div class="fs_affiliates_register_form_preview">
                <?php
                /* Include Register page */
                include_once(FS_AFFILIATES_PLUGIN_PATH . '/inc/admin/menu/views/preview/register.php') ;
                ?>
            </div>
            <div class="fs_affiliates_login_form_preview">
                <?php
                /* Include Register page */
                if ( apply_filters( 'fs_affiliates_block_unsuccessful_login' , true ) ) {
                    include_once(FS_AFFILIATES_PLUGIN_PATH . '/inc/admin/menu/views/preview/login.php') ;
                } else {
                    $Duration = get_option( 'fs_affiliates_fraud_protection_min_duration' ) ;
                    $Msg      = ($Duration[ 'unit' ] == 'minutes') ? $Duration[ 'number' ] . ' minute(s)' : $Duration[ 'number' ] . ' hour(s)' ;
                    esc_html_e( 'Login attempt count exceeds. Please login after ' . $Msg , FS_AFFILIATES_LOCALE ) ;
                }
                ?>
            </div>
        </div>
        <?php
    }

    /**
     * Save settings.
     */
    public function save_section() {
        global $current_section ;
        if ( empty( $_POST[ 'save' ] ) )
            return ;

        $settings = $this->get_settings( $current_section ) ;
        FS_Affiliates_Settings::save_fields( $settings ) ;
        FS_Affiliates_Settings::add_message( esc_html__( 'Your settings have been saved.' , FS_AFFILIATES_LOCALE ) ) ;

        if ( isset( $_POST[ 'sorted_payments_label' ] ) && isset( $_POST[ 'sorted_payments_status' ] ) ) {
            update_option( 'fs_affiliates_payment_preference_label' , $_POST[ 'sorted_payments_label' ] ) ;
            update_option( 'fs_affiliates_payment_preference' , $_POST[ 'sorted_payments_status' ] ) ;
        }

        // Update Payment Data from admin
        if ( isset( $_POST[ 'fs_affiliates_payment_method_selection_type' ] ) && isset( $_POST[ 'fs_affiliates_admin_payment_method' ] ) && ( '2' == $_POST[ 'fs_affiliates_payment_method_selection_type' ]) ) {
            $affiliate_ids = fs_get_all_affiliate_ids() ;

            if ( ! fs_affiliates_check_is_array( $affiliate_ids ) ) {
                return ;
            }

            foreach ( $affiliate_ids as $affiliate_id ) {
                if ( $_POST[ 'fs_affiliates_admin_payment_method' ] == 'wallet' && ! fs_affiliates_is_wallet_eligible( $affiliate_id ) ) {
                    continue ;
                }

                fs_update_affiliate_payment_data( $affiliate_id , $_POST[ 'fs_affiliates_admin_payment_method' ] , 'exist' ) ;
            }
        }
    }

    /**
     * Save subsection settings.
     */
    public function save_subsection() {
        global $current_sub_section ;

        if ( $current_sub_section == '' || empty( $_POST[ 'edit_form_fields' ] ) )
            return ;

        check_admin_referer( $this->plugin_slug . '_edit_form_fields' , '_' . $this->plugin_slug . '_nonce' ) ;

        $fields          = fs_affiliates_get_form_fields() ;
        $form_field_data = $_POST[ 'form_field' ] ;

        if ( empty( $form_field_data[ 'field_key' ] ) || $form_field_data[ 'field_key' ] != $_REQUEST[ 'id' ] ) {
            throw new Exception( esc_html__( 'Cannot Change Form field key' , FS_AFFILIATES_LOCALE ) ) ;
        }

        $field = $fields[ $form_field_data[ 'field_key' ] ] ;

        foreach ( $form_field_data as $field_key => $field_data ) {
            if ( ! array_key_exists( $field_key , $field ) )
                continue ;

            $field [ $field_key ] = $field_data ;
        }

        $fields[ $form_field_data[ 'field_key' ] ] = $field ;

        update_option( 'fs_affiliates_frontend_form_fields' , $fields ) ;

        FS_Affiliates_Settings::add_message( esc_html__( 'Edit Form Field has been completed.' , FS_AFFILIATES_LOCALE ) ) ;
    }

    /**
     * Save settings.
     */
    public function save() {

        try {
            $this->save_section() ;
            $this->save_subsection() ;
        } catch ( Exception $ex ) {
            FS_Affiliates_Settings::add_error( $ex->getMessage() ) ;
        }
    }

}

return new FS_Affiliates_Settings_Tab() ;

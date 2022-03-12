<?php
/**
 * Paypal payouts
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'FS_Affiliates_Payout_Statements' ) ) {

    /**
     * Class
     */
    class FS_Affiliates_Payout_Statements extends FS_Affiliates_Modules {
        /*
         * Data
         */

        protected $data = array(
            'enabled'                       => 'no' ,
            'dashboard_enable'              => 'no' ,
            'dashboard_menu_label'          => '' ,
            'is_view_payout_enable'         => 'no' ,
            'char_count'                    => '' ,
            'prefix'                        => '' ,
            'suffix'                        => '' ,
            'name_label_heading'            => '' ,
            'sequence_number'               => '' ,
            'date_heading'                  => '' ,
            'image_url'                     => '' ,
            'logo_max_percent'              => '' ,
            'admin_section_heading'         => '' ,
            'admin_section'                 => '' ,
            'validation_fields'             => array(
                'name_label_heading' ,
                'addr1_label' ,
                'addr2_label' ,
                'city_label' ,
                'state_label' ,
                'zip_code_label' ,
                'tax_cred_label' ,
            ) ,
            'billing_details_section_label' => '' ,
            'company_name_label'            => '' ,
            'addr1_label'                   => '' ,
            'addr2_label'                   => '' ,
            'city_label'                    => '' ,
            'state_label'                   => '' ,
            'zip_code_label'                => '' ,
            'tax_cred_label'                => '' ,
            'table_heading'                 => '' ,
            'additional_details_heading'    => '' ,
            'additional_details'            => '' ,
            'footer_text'                   => '' ,
                ) ;

        /**
         * Class Constructor
         */
        public function __construct() {

            $this->id    = 'payout_statements' ;
            $this->title = esc_html__( 'Payout Statements' , FS_AFFILIATES_LOCALE ) ;

            parent::__construct() ;
        }

        /*
         * Get settings link
         */

        public function settings_link() {

            return add_query_arg( array( 'page' => 'fs_affiliates' , 'tab' => 'modules' , 'section' => $this->id ) , admin_url( 'admin.php' ) ) ;
        }

        public function actions() {
            add_shortcode( 'fs_affiliate_billing_details' , array( $this , 'shortcode_billing_details' ) ) ;
            add_shortcode( 'fs_affiliate_billing_details_alert' , array( $this , 'shortcode_billing_details_error' ) ) ;
            add_filter( 'fs_affiliates_is_pay_slip_exists' , array( $this , 'check_is_pay_slip_exists' ) , 10 , 2 ) ;
        }

        public function settings_options_array() {
            return array(
                array(
                    'type'  => 'title' ,
                    'title' => esc_html__( 'Payout Statement Settings' , FS_AFFILIATES_LOCALE ) ,
                    'id'    => 'payout_statements_general_settings' ,
                ) ,
                array(
                    'title'   => esc_html__( 'Dashboard Label' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->plugin_slug . '_' . $this->id . '_dashboard_menu_label' ,
                    'type'    => 'text' ,
                    'default' => 'Billing Address' ,
                ) ,
                array(
                    'title'   => esc_html__( 'Allow Affiliates to Download their Payout Statements from their Dashboard' , FS_AFFILIATES_LOCALE ) ,
                    'desc'    => esc_html__( 'if enabled, affiliates will be able to download the generated payout statements from their "Payouts" menu in dashboard' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->plugin_slug . '_' . $this->id . '_is_view_payout_enable' ,
                    'type'    => 'checkbox' ,
                    'default' => 'yes' ,
                ) ,
                array(
                    'title'   => esc_html__( 'Payout File Name' , FS_AFFILIATES_LOCALE ) ,
                    'desc'    => esc_html__( 'If "Default" is selected, the file name will generate based on the format set in below field. If "Advanced" is selected, you can customize the name as per your needs using the given options.' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->plugin_slug . '_' . $this->id . '_payout_name_disp_type' ,
                    'type'    => 'select' ,
                    'options' => array( '1' => esc_html__( 'Default' , FS_AFFILIATES_LOCALE ) , '2' => esc_html__( 'Advanced' , FS_AFFILIATES_LOCALE ) ) ,
                    'default' => '2' ,
                ) ,
                array(
                    'title'   => esc_html__( 'Format' , FS_AFFILIATES_LOCALE ) ,
                    'desc'    => esc_html__( 'By default, the file name will be generated as "Payout-YYYY-MM-DD @ hh.mm.ss". You can customize as per your needs' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->plugin_slug . '_' . $this->id . '_file_name_format' ,
                    'class'   => $this->plugin_slug . '_' . $this->id . '_date_format_payout_fields' ,
                    'type'    => 'text' ,
                    'default' => 'Y-m-d @ H.i.s' ,
                ) ,
                array(
                    'title'   => esc_html__( 'Statement Name Character Count' , FS_AFFILIATES_LOCALE ) ,
                    'desc'    => esc_html__( 'character count excluding prefix and suffix' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->plugin_slug . '_' . $this->id . '_char_count' ,
                    'class'   => $this->plugin_slug . '_' . $this->id . '_advanced_payout_fields' ,
                    'type'    => 'text' ,
                    'default' => 10 ,
                ) ,
                array(
                    'title'   => esc_html__( 'Statement Name Prefix' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->plugin_slug . '_' . $this->id . '_prefix' ,
                    'class'   => $this->plugin_slug . '_' . $this->id . '_advanced_payout_fields' ,
                    'type'    => 'text' ,
                    'default' => '' ,
                ) ,
                array(
                    'title'   => esc_html__( 'Statement Name Suffix' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->plugin_slug . '_' . $this->id . '_suffix' ,
                    'class'   => $this->plugin_slug . '_' . $this->id . '_advanced_payout_fields' ,
                    'type'    => 'text' ,
                    'default' => '' ,
                ) ,
                array(
                    'title'   => esc_html__( 'Payout Statement Sequence Starting Number' , FS_AFFILIATES_LOCALE ) ,
                    'desc'    => esc_html__( 'if prefix is given, then this number will come after prefix' , FS_AFFILIATES_LOCALE ) ,
                    'class'   => $this->plugin_slug . '_' . $this->id . '_advanced_payout_fields' ,
                    'id'      => $this->plugin_slug . '_' . $this->id . '_sequence_number' ,
                    'type'    => 'text' ,
                    'default' => 1 ,
                ) ,
                array(
                    'title'   => esc_html__( 'Statement Name Label' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->plugin_slug . '_' . $this->id . '_name_label_heading' ,
                    'type'    => 'text' ,
                    'default' => 'Payout Statement Name' ,
                ) ,
                array(
                    'title'   => esc_html__( 'Statement Date Label' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->plugin_slug . '_' . $this->id . '_date_heading' ,
                    'type'    => 'text' ,
                    'default' => 'Date' ,
                ) ,
                array(
                    'type' => 'sectionend' ,
                    'id'   => 'payout_statements_general_settings' ,
                ) ,
                array(
                    'type'  => 'title' ,
                    'title' => esc_html__( 'Admin Section Customization' , FS_AFFILIATES_LOCALE ) ,
                    'id'    => 'admin_settings_customization' ,
                ) ,
                array(
                    'type' => 'output_logo_upload' ,
                ) ,
                array(
                    'title'   => esc_html__( 'Logo Maximum Width(in %)' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->plugin_slug . '_' . $this->id . '_logo_max_percent' ,
                    'type'    => 'text' ,
                    'default' => '' ,
                ) ,
                array(
                    'title'   => esc_html__( 'Admin Details Section Heading' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->plugin_slug . '_' . $this->id . '_admin_section_heading' ,
                    'type'    => 'text' ,
                    'default' => 'Admin Details' ,
                ) ,
                array(
                    'title'   => esc_html__( 'Admin Details' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->plugin_slug . '_' . $this->id . '_admin_section' ,
                    'type'    => 'textarea' ,
                    'default' => '' ,
                ) ,
                array(
                    'type' => 'sectionend' ,
                    'id'   => 'admin_settings_customization' ,
                ) ,
                array(
                    'type'  => 'title' ,
                    'title' => esc_html__( 'Frontend Dashboard Customization' , FS_AFFILIATES_LOCALE ) ,
                    'id'    => 'affiliate_section_customization' ,
                ) ,
                array(
                    'title'   => esc_html__( 'Payout Statements Field(s)' , FS_AFFILIATES_LOCALE ) ,
                    'desc'    => esc_html__( 'Selected field(s) in this option will be displayed as a mandatory in billing address section at frontend[Affiliate Dashboard -> Profile -> Payout Statements]' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->plugin_slug . '_' . $this->id . '_validation_fields' ,
                    'class'   => 'fs_affiliates_select2' ,
                    'type'    => 'multiselect' ,
                    'default' => array(
                        'name_label_heading' ,
                        'addr1_label' ,
                        'addr2_label' ,
                        'city_label' ,
                        'state_label' ,
                        'zip_code_label' ,
                        'tax_cred_label' ,
                    ) ,
                    'options' => fs_affiliates_get_payout_fields() ,
                ) ,
                array(
                    'title'   => esc_html__( 'Billing Details Section Label' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->plugin_slug . '_' . $this->id . '_billing_details_section_label' ,
                    'type'    => 'text' ,
                    'default' => 'Billing Details' ,
                ) ,
                array(
                    'title'   => esc_html__( 'Company Name Label' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->plugin_slug . '_' . $this->id . '_company_name_label' ,
                    'type'    => 'text' ,
                    'default' => 'Company Name' ,
                ) ,
                array(
                    'title'   => esc_html__( 'Name Label' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->plugin_slug . '_' . $this->id . '_name_label' ,
                    'type'    => 'text' ,
                    'default' => 'Name' ,
                ) ,
                array(
                    'title'   => esc_html__( 'Address Line 1 Label' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->plugin_slug . '_' . $this->id . '_addr1_label' ,
                    'type'    => 'text' ,
                    'default' => 'Address Line 1' ,
                ) ,
                array(
                    'title'   => esc_html__( 'Address Line 2 Label' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->plugin_slug . '_' . $this->id . '_addr2_label' ,
                    'type'    => 'text' ,
                    'default' => 'Address Line 2' ,
                ) ,
                array(
                    'title'   => esc_html__( 'City Label' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->plugin_slug . '_' . $this->id . '_city_label' ,
                    'type'    => 'text' ,
                    'default' => 'City' ,
                ) ,
                array(
                    'title'   => esc_html__( 'State Label' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->plugin_slug . '_' . $this->id . '_state_label' ,
                    'type'    => 'text' ,
                    'default' => 'State' ,
                ) ,
                array(
                    'title'   => esc_html__( 'Zip Code Label' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->plugin_slug . '_' . $this->id . '_zip_code_label' ,
                    'type'    => 'text' ,
                    'default' => 'Zip Code' ,
                ) ,
                array(
                    'title'   => esc_html__( 'Tax Credentials Label' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->plugin_slug . '_' . $this->id . '_tax_cred_label' ,
                    'type'    => 'text' ,
                    'default' => 'Tax Credentials' ,
                ) ,
                array(
                    'type' => 'sectionend' ,
                    'id'   => 'affiliate_section_customization' ,
                ) ,
                array(
                    'type'  => 'title' ,
                    'title' => esc_html__( 'Referral Section Customization' , FS_AFFILIATES_LOCALE ) ,
                    'id'    => 'referral_section_customization' ,
                ) ,
                array(
                    'title'   => esc_html__( 'Referral Table Heading' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->plugin_slug . '_' . $this->id . '_table_heading' ,
                    'type'    => 'text' ,
                    'default' => '' ,
                ) ,
                array(
                    'title'   => esc_html__( 'Additional Details Heading' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->plugin_slug . '_' . $this->id . '_additional_details_heading' ,
                    'type'    => 'text' ,
                    'default' => '' ,
                ) ,
                array(
                    'title'   => esc_html__( 'Additional Details' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->plugin_slug . '_' . $this->id . '_additional_details' ,
                    'type'    => 'textarea' ,
                    'default' => '' ,
                ) ,
                array(
                    'title'   => esc_html__( 'Footer Text' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->plugin_slug . '_' . $this->id . '_footer_text' ,
                    'type'    => 'textarea' ,
                    'default' => '' ,
                ) ,
                array(
                    'type' => 'sectionend' ,
                    'id'   => 'referral_section_customization' ,
                ) ,
                    ) ;
        }

        /**
         * Frontend Actions
         */
        public function frontend_action() {
            add_filter( 'fs_affiliates_frontend_dashboard_profile_submenus' , array( $this , 'custom_dashboard_menu' ) , 11 , 3 ) ;
            add_action( 'fs_affiliates_dashboard_content_payout_statements' , array( $this , 'display_dashboard_content' ) , 10 , 2 ) ;

            add_action( 'fs_affiliates_before_dashboard_content' , array( $this , 'display_before_dashboard_content' ) , 10 , 3 ) ;

            add_filter( 'fs_affiliates_is_payout_statements_available' , array( $this , 'is_payout_statements_available' ) , 10 , 1 ) ;
        }

        public function is_payout_statements_available( $bool ) {

            if ( get_option( 'fs_affiliates_payout_statements_is_view_payout_enable' ) == 'yes' ) {
                return true ;
            }

            return $bool ;
        }

        public function admin_action() {
            add_action( 'fs_affiliates_new_payout' , array( $this , 'create_payout_payout_statements' ) , 10 , 2 ) ;
            add_filter( 'fs_affiliates_email_attachments' , array( $this , 'add_payout_pdf_attachments' ) , 10 , 2 ) ;
            add_action( $this->plugin_slug . '_admin_field_output_logo_upload' , array( $this , 'display_output_logo_upload' ) ) ;
            add_filter( 'fs_affiliates_is_pay_slip_download_available' , array( $this , 'is_pay_slip_download_available' ) , 10 , 4 ) ;
            add_action( 'admin_head' , array( $this , 'generate_payout_statements' ) ) ;
        }

        public function is_pay_slip_download_available( $actions , $payout_id , $affilate_id , $current_url ) {

            if ( apply_filters( 'fs_affiliates_is_pay_slip_exists' , false , $payout_id ) ) {
                $actions[ 'download' ] = '<a href="' . esc_url_raw( add_query_arg( array( 'section' => 'payout_statement' , 'payout_statement_id' => $payout_id ) , $current_url ) ) . '">' . esc_html__( 'Download Statement' , FS_AFFILIATES_LOCALE ) . '</a>' ;
            } else {
                $affiliate_data = new FS_Affiliates_Data( $affilate_id ) ;
                if ( $affiliate_data->payout_form_status_successfull == 'yes' ) {
                    $actions[ 'generate' ] = '<a href="' . esc_url_raw( add_query_arg( array( 'section' => 'payout_statement' , 'payout_generate_id' => $payout_id , 'payout_affiliate_id' => $affilate_id ) , $current_url ) ) . '">' . esc_html__( 'Generate Statement' , FS_AFFILIATES_LOCALE ) . '</a>' ;
                }
            }

            return $actions ;
        }

        public function display_before_dashboard_content( $current_tab , $user_id , $affilate_id ) {
            $affiliate_data = new FS_Affiliates_Data( $affilate_id ) ;

            $validation_fields = get_option( 'fs_affiliates_payout_statements_validation_fields' ) ;
            if ( fs_affiliates_check_is_array( $validation_fields ) ) {
                foreach ( $validation_fields as $each_field ) {
                    if ( empty( $affiliate_data->$each_field ) ) {
                        $affiliate_data->update_meta( 'payout_form_status_successfull' , 'no' ) ;
                        break ;
                    }
                }
            } else {
                $affiliate_data->update_meta( 'payout_form_status_successfull' , 'yes' ) ;
            }

            $affiliate_data        = new FS_Affiliates_Data( $affilate_id ) ;
            $query_nonce           = wp_create_nonce( 'affiliates-' . $user_id ) ;
            $get_permalink         = FS_AFFILIATES_PROTOCOL . $_SERVER[ 'HTTP_HOST' ] . $_SERVER[ 'REQUEST_URI' ] ;
            $payout_statement_href = fs_affiliates_dashboard_menu_link( $get_permalink , 'payout_statements' , $query_nonce ) ;
            $payout_statement_link = ' <a style="color:white;text-decoration:underline" href=' . $payout_statement_href . '> ' . esc_html__( 'click the following link' , FS_AFFILIATES_LOCALE ) . '</a>' ;


            if ( $affiliate_data->payout_form_status_successfull != 'yes' && ! isset( $_POST[ 'aff_set_payout_particulars' ] ) ) {

                if ( apply_filters( 'fs_affiliates_show_warning_notice' , true , 'profile' , 'payout_statements' ) ) {
                    ?>
                    <div>
                        <span class="fs_affiliates_msg_fails_post"><i class="fa fa-exclamation-triangle"></i><?php echo sprintf( esc_html__( 'Billing details is required for generating payout statements. To provide the billing details %s' , FS_AFFILIATES_LOCALE ) , $payout_statement_link ) ; ?></span>
                    </div>
                    <?php
                }
            }
        }

        /*
         * Custom Dashboard Menu
         */

        public function custom_dashboard_menu( $menus , $user_id , $affiliate_id ) {
            $menus[ 'payout_statements' ] = get_option( 'fs_affiliates_payout_statements_dashboard_menu_label' ) ;

            return $menus ;
        }

        /*
         * Display the billing details.
         */

        public function shortcode_billing_details( $atts , $content , $tag ) {
            if ( ! is_user_logged_in() )
                return $content ;

            $affiliate_id = fs_affiliates_is_user_having_affiliate() ;
            if ( ! $affiliate_id )
                return $content ;

            $user_id = get_current_user_id() ;

            ob_start() ;
            self::display_dashboard_content( $user_id , $affiliate_id ) ; // output for shortcode
            $content = ob_get_contents() ;
            ob_end_clean() ;

            return $content ;
        }

        /*
         * Display the billing details error.
         */

        public function shortcode_billing_details_error( $atts , $content , $tag ) {
            if ( ! is_user_logged_in() )
                return $content ;

            $affiliate_id = fs_affiliates_is_user_having_affiliate() ;
            if ( ! $affiliate_id )
                return $content ;

            $user_id = get_current_user_id() ;

            ob_start() ;
            self::display_before_dashboard_content( 'shortcode' , $user_id , $affiliate_id ) ; // output for shortcode
            $content = ob_get_contents() ;
            ob_end_clean() ;

            return $content ;
        }

        /*
         * Profile - PDF Payout Slip
         */

        public static function display_dashboard_content( $user_id , $AffiliateId ) {
            $affiliate_data = new FS_Affiliates_Data( $AffiliateId ) ;

            $validation_fields = get_option( 'fs_affiliates_payout_statements_validation_fields' ) ;

            $payout_fields = fs_affiliates_get_payout_fields() ;

            if ( ! empty( $_POST[ 'aff_set_payout_particulars' ] ) ) {
                $error_message = '' ;
                try {
                    $i = 0 ;
                    $x = 0 ;
                    foreach ( $payout_fields as $each_id => $each_label ) {
                        if ( isset( $_POST[ "$each_id" ] ) ) {
                            $affiliate_data->update_meta( "$each_id" , $_POST[ "$each_id" ] ) ;
                            if ( in_array( $each_id , $validation_fields ) && empty( $_POST[ "$each_id" ] ) ) {
                                $x ++ ;
                            }
                        }
                    }

                    $payout_form_status = ( $x == 0 ) ? 'yes' : 'no' ;

                    $affiliate_data->update_meta( 'payout_form_status_successfull' , $payout_form_status ) ;

                    if ( $payout_form_status != 'yes' ) {
                        $error_message .= esc_html__( 'Please fill the required fields' , FS_AFFILIATES_LOCALE ) . '<br>' ;
                    }
                    if ( $error_message ) {
                        throw new Exception( $error_message ) ;
                    }
                    ?><div>
                        <span class="fs_affiliates_msg_success_post"><i class="fa fa-check"></i><?php esc_html_e( 'Billing Address updated sucessfully' , FS_AFFILIATES_LOCALE ) ; ?></span>
                    </div><?php
                    do_action( 'fs_affiliates_payout_payslip_updated' , $AffiliateId ) ;
                } catch ( Exception $e ) {
                    ?><div>
                        <span class="fs_affiliates_msg_fails_post"><i class="fa fa-exclamation-triangle"></i><?php echo $e->getMessage() ; ?></span>
                    </div><?php
                }
            }

            // Account Management of Profile
            include_once(FS_AFFILIATES_PLUGIN_PATH . '/inc/frontend/views/dashboard/pdf-payslip-management.php') ;
        }

        public function check_is_pay_slip_exists( $bool , $payout_id ) {

            if ( fs_affiliates_is_file_exists( $payout_id ) ) {
                return true ;
            }
            return $bool ;
        }

        public function add_payout_pdf_attachments( $attachments , $payouts ) {
            $payout_id = isset( $payouts->payout_id ) ? $payouts->payout_id : '' ;
            if ( empty( $payout_id ) ) {
                return $attachments ;
            }
            $update_payout_datas = new FS_Affiliates_Payouts( $payout_id ) ;
            $file_url            = isset( $update_payout_datas->pay_statement_file_name ) ? $update_payout_datas->pay_statement_file_name : '' ;

            if ( ! empty( $file_url ) && fs_affiliates_is_file_exists( $payout_id ) ) {
                $attachments[] = $file_url ;
            }

            return $attachments ;
        }

        public function create_payout_payout_statements( $payout_id , $affiliate_id ) {
            $affiliate_data = new FS_Affiliates_Data( $affiliate_id ) ;
            if ( $affiliate_data->payout_form_status_successfull != 'yes' ) {
                return ;
            }
            include_once ( FS_AFFILIATES_PLUGIN_PATH . '/inc/lib/TCPDF/tcpdf.php' ) ;
            include_once ( FS_AFFILIATES_PLUGIN_PATH . '/inc/lib/TCPDF/payslip-tempalte.php' ) ;
        }

        public function generate_payout_statements() {

            if ( ! isset( $_GET[ 'payout_generate_id' ] ) || ! isset( $_GET[ 'payout_affiliate_id' ] ) ) {
                return ;
            }

            $payout_id      = $_GET[ 'payout_generate_id' ] ;
            $affiliate_id   = $_GET[ 'payout_affiliate_id' ] ;
            $affiliate_data = new FS_Affiliates_Data( $affiliate_id ) ;
            if ( $affiliate_data->payout_form_status_successfull != 'yes' ) {
                return ;
            }

            include_once ( FS_AFFILIATES_PLUGIN_PATH . '/inc/lib/TCPDF/tcpdf.php' ) ;
            include_once ( FS_AFFILIATES_PLUGIN_PATH . '/inc/lib/TCPDF/payslip-tempalte.php' ) ;
        }

        public function output_buttons() {

            FS_Affiliates_Settings::output_buttons() ;
        }

        public static function display_output_logo_upload() {

            $image = get_option( 'fs_affiliates_payout_statements_image_url' ) ;
            ?>
            <tr>
                <th> <?php echo esc_html__( 'Select Logo' , FS_AFFILIATES_LOCALE ) ; ?> </th>
                <td>
                    <input type="text" class="fs_affiliates_payout_statements_image_url" name='fs_affiliates_payout_statements_image_url' value='<?php echo $image ; ?>'/>
                    <input class="fs_affiliates_payout_statements_image_url_btn button-secondary" data-title="<?php _e( 'Choose Image' , FS_AFFILIATES_LOCALE ) ; ?>"
                           data-button="<?php esc_html_e( 'Use Image' , FS_AFFILIATES_LOCALE ) ; ?>"
                           type="button" value="<?php esc_html_e( 'Choose Image' , FS_AFFILIATES_LOCALE ) ; ?>" />
                </td>
            </tr>

            <?php
        }

        /**
         * Save subsection settings.
         */
        public function before_save() {
            if ( ! isset( $_POST[ 'fs_affiliates_payout_statements_image_url' ] ) ) {
                return ;
            }

            $this->update_option( 'image_url' , $_POST[ 'fs_affiliates_payout_statements_image_url' ] ) ;
        }

    }

}

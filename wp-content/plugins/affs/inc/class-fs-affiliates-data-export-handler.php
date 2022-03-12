<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly
}

/**
 * Handle Data Exporter.
 */
class FS_Affiliates_Data_Export_Handler {

    /**
     * Init FS_Affiliates_Data_Export_Handler.
     */
    public static function init() {
        add_action( 'admin_head' , array( __CLASS__ , 'export_affiliates_data' ) ) ;
        add_action( 'admin_head' , array( __CLASS__ , 'export_referrals_data' ) ) ;
        add_action( 'admin_head' , array( __CLASS__ , 'export_visits_data' ) ) ;
        add_action( 'admin_head' , array( __CLASS__ , 'export_payouts_data' ) ) ;
    }

    /**
     * Generate the CSV file.
     */
    public static function export_file( $args ) {

        $default = array(
            'filename'      => '' ,
            'delimiter'     => ',' ,
            'enclosure'     => '"' ,
            'field_heading' => array() ,
            'field_datas'   => array() ,
                ) ;

        $args = wp_parse_args( $args , $default ) ;

        ob_end_clean() ;
        header( "Content-type: text/csv" ) ;
        header( "Content-Disposition: attachment; filename=" . $args[ 'filename' ] . "-" . date_i18n( "Y-m-d H:i:s" ) . ".csv" ) ;
        header( "Pragma: no-cache" ) ;
        header( "Expires: 0" ) ;

        $handle = fopen( "php://output" , 'w' ) ;

        fputcsv( $handle , $args[ 'field_heading' ] , $args[ 'delimiter' ] , $args[ 'enclosure' ] ) ; // here you can change delimiter/enclosure

        if ( fs_affiliates_check_is_array( $args[ 'field_datas' ] ) ) {
            foreach ( $args[ 'field_datas' ] as $column => $data ) {
                fputcsv( $handle , $data , $args[ 'delimiter' ] , $args[ 'enclosure' ] ) ; // here you can change delimiter/enclosure
            }
        }

        fclose( $handle ) ;
        exit() ;
    }

    /**
     * Export Affiliate Data
     */
    public static function export_affiliates_data() {
        if ( isset( $_POST[ 'action' ] , $_POST[ 'fs_nonce' ] ) && wp_verify_nonce( wp_unslash( $_POST[ 'fs_nonce' ] ) , 'export_affiliates' ) && 'export_affiliates' === wp_unslash( $_POST[ 'action' ] ) ) {

            $field_datas = self::generate_affiliates_field_data() ;

            if ( ! fs_affiliates_check_is_array( $field_datas ) ) {
                FS_Affiliates_Settings::add_message( __( 'No records found' , FS_AFFILIATES_LOCALE ) ) ;
                return ;
            }

            $field_heading = apply_filters( 'fs_affiliates_export_affiliate_headings' , array(
                __( 'Affiliate ID' , FS_AFFILIATES_LOCALE ) ,
                __( 'First Name' , FS_AFFILIATES_LOCALE ) ,
                __( 'Last Name' , FS_AFFILIATES_LOCALE ) ,
                __( 'Username' , FS_AFFILIATES_LOCALE ) ,
                __( 'Email ID' , FS_AFFILIATES_LOCALE ) ,
                __( 'Phone Number' , FS_AFFILIATES_LOCALE ) ,
                __( 'Website' , FS_AFFILIATES_LOCALE ) ,
                __( 'Payment Method' , FS_AFFILIATES_LOCALE ) ,
                __( 'Payment Details' , FS_AFFILIATES_LOCALE ) ,
                __( 'Parent Affiliate' , FS_AFFILIATES_LOCALE ) ,
                __( 'Referrals' , FS_AFFILIATES_LOCALE ) ,
                __( 'Visits' , FS_AFFILIATES_LOCALE ) ,
                __( 'Commission value' , FS_AFFILIATES_LOCALE ) ,
                __( 'Commission Type' , FS_AFFILIATES_LOCALE ) ,
                __( 'Paid Commission' , FS_AFFILIATES_LOCALE ) ,
                __( 'Unpaid Commission' , FS_AFFILIATES_LOCALE ) ,
                __( 'Status' , FS_AFFILIATES_LOCALE ) ,
                __( 'Registered Date' , FS_AFFILIATES_LOCALE ) ,
                    )
                    ) ;

            $args = array(
                'filename'      => 'fs-affiliates-export-data' ,
                'delimiter'     => apply_filters( 'fs_affiliates_export_affiliate_delimiter' , ',' ) ,
                'enclosure'     => apply_filters( 'fs_affiliates_export_affiliate_enclosure' , '"' ) ,
                'field_heading' => $field_heading ,
                'field_datas'   => apply_filters( 'fs_affiliates_export_affiliate_field_datas' , $field_datas ) ,
                    ) ;

            self::export_file( $args ) ;
        }
    }

    /**
     * Export Referrals Data
     */
    public static function export_referrals_data() {
        if ( isset( $_POST[ 'action' ] , $_POST[ 'fs_nonce' ] ) && wp_verify_nonce( wp_unslash( $_POST[ 'fs_nonce' ] ) , 'export_referrals' ) && 'export_referrals' === wp_unslash( $_POST[ 'action' ] ) ) {

            $field_datas = self::generate_referrals_field_data() ;

            if ( ! fs_affiliates_check_is_array( $field_datas ) ) {
                FS_Affiliates_Settings::add_message( __( 'No records found' , FS_AFFILIATES_LOCALE ) ) ;
                return ;
            }

            $field_heading = apply_filters( 'fs_affiliates_export_referral_headings' , array(
                __( 'Referral ID' , FS_AFFILIATES_LOCALE ) ,
                __( 'Affiliate Username' , FS_AFFILIATES_LOCALE ) ,
                __( 'Amount' , FS_AFFILIATES_LOCALE ) ,
                __( 'Currency' , FS_AFFILIATES_LOCALE ) ,
                __( 'Reference' , FS_AFFILIATES_LOCALE ) ,
                __( 'Description' , FS_AFFILIATES_LOCALE ) ,
                __( 'Campaign' , FS_AFFILIATES_LOCALE ) ,
                __( 'Type' , FS_AFFILIATES_LOCALE ) ,
                __( 'Status' , FS_AFFILIATES_LOCALE ) ,
                __( 'Registered Date' , FS_AFFILIATES_LOCALE ) ,
                    )
                    ) ;

            $args = array(
                'filename'      => 'fs-affiliates-export-referral-data' ,
                'delimiter'     => apply_filters( 'fs_affiliates_export_referral_delimiter' , ',' ) ,
                'enclosure'     => apply_filters( 'fs_affiliates_export_referral_enclosure' , '"' ) ,
                'field_heading' => $field_heading ,
                'field_datas'   => apply_filters( 'fs_affiliates_export_referral_field_datas' , $field_datas ) ,
                    ) ;

            self::export_file( $args ) ;
        }
    }

    /**
     * Export Visits Data
     */
    public static function export_visits_data() {
        if ( isset( $_POST[ 'action' ] , $_POST[ 'fs_nonce' ] ) && wp_verify_nonce( wp_unslash( $_POST[ 'fs_nonce' ] ) , 'export_visits' ) && 'export_visits' === wp_unslash( $_POST[ 'action' ] ) ) {

            $field_datas = self::generate_visits_field_data() ;

            if ( ! fs_affiliates_check_is_array( $field_datas ) ) {
                FS_Affiliates_Settings::add_message( __( 'No records found' , FS_AFFILIATES_LOCALE ) ) ;
                return ;
            }

            $field_heading = apply_filters( 'fs_affiliates_export_visit_headings' , array(
                __( 'Visit ID' , FS_AFFILIATES_LOCALE ) ,
                __( 'URL' , FS_AFFILIATES_LOCALE ) ,
                __( 'Campaign' , FS_AFFILIATES_LOCALE ) ,
                __( 'Referring URL' , FS_AFFILIATES_LOCALE ) ,
                __( 'IP Address' , FS_AFFILIATES_LOCALE ) ,
                __( 'Affiliate Username/ID' , FS_AFFILIATES_LOCALE ) ,
                __( 'Referral ID' , FS_AFFILIATES_LOCALE ) ,
                __( 'Status' , FS_AFFILIATES_LOCALE ) ,
                __( 'Registered Date' , FS_AFFILIATES_LOCALE ) ,
                    )
                    ) ;

            $args = array(
                'filename'      => 'fs-affiliates-export-visit-data' ,
                'delimiter'     => apply_filters( 'fs_affiliates_export_visit_delimiter' , ',' ) ,
                'enclosure'     => apply_filters( 'fs_affiliates_export_visit_enclosure' , '"' ) ,
                'field_heading' => $field_heading ,
                'field_datas'   => apply_filters( 'fs_affiliates_export_visit_field_datas' , $field_datas ) ,
                    ) ;

            self::export_file( $args ) ;
        }
    }

    /**
     * Export Payouts Data
     */
    public static function export_payouts_data() {
        if ( isset( $_POST[ 'action' ] , $_POST[ 'fs_nonce' ] ) && wp_verify_nonce( wp_unslash( $_POST[ 'fs_nonce' ] ) , 'export_payouts' ) && 'export_payouts' === wp_unslash( $_POST[ 'action' ] ) ) {

            $field_datas = self::generate_payouts_field_data() ;

            if ( ! fs_affiliates_check_is_array( $field_datas ) ) {
                FS_Affiliates_Settings::add_message( __( 'No records found' , FS_AFFILIATES_LOCALE ) ) ;
                return ;
            }

            $field_heading = apply_filters( 'fs_affiliates_export_payout_headings' , array(
                __( 'Payout ID' , FS_AFFILIATES_LOCALE ) ,
                __( 'Affiliate Name' , FS_AFFILIATES_LOCALE ) ,
                __( 'Referrals' , FS_AFFILIATES_LOCALE ) ,
                __( 'Payment Mode' , FS_AFFILIATES_LOCALE ) ,
                __( 'Amount' , FS_AFFILIATES_LOCALE ) ,
                __( 'Currency' , FS_AFFILIATES_LOCALE ) ,
                __( 'Payout Generated By' , FS_AFFILIATES_LOCALE ) ,
                __( 'Status' , FS_AFFILIATES_LOCALE ) ,
                __( 'Payout Generated Date' , FS_AFFILIATES_LOCALE ) ,
                    )
                    ) ;

            $args = array(
                'filename'      => 'fs-affiliates-export-payout-data' ,
                'delimiter'     => apply_filters( 'fs_affiliates_export_payout_delimiter' , ',' ) ,
                'enclosure'     => apply_filters( 'fs_affiliates_export_payout_enclosure' , '"' ) ,
                'field_heading' => $field_heading ,
                'field_datas'   => apply_filters( 'fs_affiliates_export_payout_field_datas' , $field_datas ) ,
                    ) ;

            self::export_file( $args ) ;
        }
    }

    /**
     * Generate affiliates data to export
     */
    public static function generate_affiliates_field_data() {
        $affiliates = array() ;

        $posts = self::get_posts( 'fs-affiliates' ) ;

        if ( ! fs_affiliates_check_is_array( $posts ) )
            return $affiliates ;

        foreach ( $posts as $id ) {
            $payment_preference = fs_affiliates_paymethod_preference();
            $payment_preference = get_option('fs_affiliates_payment_preference', array('direct' => 'enable', 'paypal' => 'enable', 'wallet' => 'enable'));
            $default_paymethod = fs_affiliates_get_default_gateway($payment_preference);
            $payment_datas = get_post_meta($id, 'fs_affiliates_user_payment_datas', true);
            $saved_pay_method = isset($payment_datas['fs_affiliates_payment_method']) ? $payment_datas['fs_affiliates_payment_method'] : '';
            $pay_method = ( empty($saved_pay_method) && !empty($default_paymethod) ) ? $default_paymethod : $saved_pay_method;
            $paypal_email = isset($payment_datas['fs_affiliates_paypal_email']) ? $payment_datas['fs_affiliates_paypal_email'] : '';
            $pay_bank = isset($payment_datas['fs_affiliates_bank_details']) ? $payment_datas['fs_affiliates_bank_details'] : '';
            $affiliate_object = new FS_Affiliates_Data( $id ) ;

            $parent = empty( $affiliate_object->parent ) ? '' : get_the_title( $affiliate_object->parent ) ;

            $affiliates[ $id ][ 'id' ]               = $affiliate_object->get_id() ;
            $affiliates[ $id ][ 'first_name' ]       = $affiliate_object->first_name ;
            $affiliates[ $id ][ 'last_name' ]        = $affiliate_object->last_name ;
            $affiliates[ $id ][ 'user_name' ]        = $affiliate_object->user_name ;
            $affiliates[ $id ][ 'email' ]            = $affiliate_object->email ;
            $affiliates[ $id ][ 'phone_number' ]     = $affiliate_object->phone_number ;
            $affiliates[ $id ][ 'website' ]          = $affiliate_object->website ;
            $affiliates[ $id ][ 'payment_method' ]   = empty($saved_pay_method) ? esc_html__('Not Yet Selected' , FS_AFFILIATES_LOCALE ) : fs_affiliates_get_paymethod_preference($pay_method);
            
            if( !empty($pay_method) && in_array($pay_method , array('direct' , 'paypal'))){
                $affiliates[ $id ][ 'payment_details' ]    = ('paypal' == $pay_method) ? $affiliate_object->payment_email : $pay_bank;
            } else {
                $affiliates[ $id ][ 'payment_details' ]    = '-';
            }
            
            $affiliates[ $id ][ 'parent' ]           = $parent ;
            $affiliates[ $id ][ 'referrals' ]        = $affiliate_object->get_referrals_count() ;
            $affiliates[ $id ][ 'visits' ]           = $affiliate_object->get_visits_count() ;
            $affiliates[ $id ][ 'commission_type' ]  = $affiliate_object->commission_type ;
            $affiliates[ $id ][ 'commission_value' ] = $affiliate_object->commission_value ;
            $affiliates[ $id ][ 'earnings' ]         = $affiliate_object->paid_earnings ;
            $affiliates[ $id ][ 'unpaid_earnings' ]  = fs_affiliates_get_referrals_commission( $affiliate_object->get_id() ) ;
            $affiliates[ $id ][ 'status' ]           = strip_tags( fs_affiliates_get_status_display( $affiliate_object->get_status() ) ) ;
            $affiliates[ $id ][ 'date' ]             = fs_affiliates_local_datetime( $affiliate_object->date ) ;
        }

        return $affiliates ;
    }

    /**
     * Generate referral data to export
     */
    public static function generate_referrals_field_data() {
        $referrals = array() ;

        $posts = self::get_posts( 'fs-referrals' ) ;

        if ( ! fs_affiliates_check_is_array( $posts ) )
            return $referrals ;

        $currency = get_option( 'fs_affiliates_currency' , 'USD' ) ;
        foreach ( $posts as $id ) {
            $referral_object  = new FS_Affiliates_Referrals( $id ) ;
            $affiliate_object = new FS_Affiliates_Data( $referral_object->affiliate ) ;

            $referrals[ $id ][ 'id' ]          = $referral_object->get_id() ;
            $referrals[ $id ][ 'user_name' ]   = $affiliate_object->user_name ;
            $referrals[ $id ][ 'amount' ]      = $referral_object->amount ;
            $referrals[ $id ][ 'currency' ]    = $currency ;
            $referrals[ $id ][ 'reference' ]   = $referral_object->reference ;
            $referrals[ $id ][ 'description' ] = $referral_object->description ;
            $referrals[ $id ][ 'campaign' ]    = $referral_object->campaign ;
            $referrals[ $id ][ 'type' ]        = $referral_object->type ;
            $referrals[ $id ][ 'status' ]      = strip_tags( fs_affiliates_get_status_display( $referral_object->get_status() ) ) ;
            $referrals[ $id ][ 'date' ]        = fs_affiliates_local_datetime( $referral_object->date ) ;
        }

        return $referrals ;
    }

    /**
     * Generate visits data to export
     */
    public static function generate_visits_field_data() {
        $visits = array() ;

        $posts = self::get_posts( 'fs-visits' ) ;

        if ( ! fs_affiliates_check_is_array( $posts ) )
            return $visits ;

        foreach ( $posts as $id ) {
            $visit_object     = new FS_Affiliates_Visits( $id ) ;
            $affiliate_object = new FS_Affiliates_Data( $visit_object->affiliate ) ;

            $referral_id = ($visit_object->referral_id) ? '#' . $visit_object->referral_id : '' ;

            $visits[ $id ][ 'id' ]           = $visit_object->get_id() ;
            $visits[ $id ][ 'url' ]          = $visit_object->landing_page ;
            $visits[ $id ][ 'campaign' ]     = $visit_object->campaign ;
            $visits[ $id ][ 'referral_url' ] = $visit_object->referral_url ;
            $visits[ $id ][ 'ip_address' ]   = $visit_object->ip_address ;
            $visits[ $id ][ 'user_name' ]    = $affiliate_object->user_name . ' (#' . $visit_object->affiliate . ')' ;
            $visits[ $id ][ 'referral_id' ]  = $referral_id ;
            $visits[ $id ][ 'status' ]       = strip_tags( fs_affiliates_get_status_display( $visit_object->get_status() ) ) ;
            $visits[ $id ][ 'date' ]         = fs_affiliates_local_datetime( $visit_object->date ) ;
        }

        return $visits ;
    }

    /**
     * Generate payouts data to export
     */
    public static function generate_payouts_field_data() {
        $payouts = array() ;

        $posts = self::get_posts( 'fs-payouts' ) ;

        if ( ! fs_affiliates_check_is_array( $posts ) )
            return $payouts ;

        $currency = get_option( 'fs_affiliates_currency' , 'USD' ) ;
        foreach ( $posts as $id ) {
            $generate_by      = __( 'Automatic' , FS_AFFILIATES_LOCALE ) ;
            $payout_object    = new FS_Affiliates_Payouts( $id ) ;
            $affiliate_object = new FS_Affiliates_Data( $payout_object->affiliate ) ;

            $user_object = get_user_by( 'id' , $payout_object->generate_by ) ;
            if ( is_object( $user_object ) )
                $generate_by = $user_object->display_name . ' (#' . $payout_object->generate_by . ')' ;

            $payouts[ $id ][ 'id' ]           = $payout_object->get_id() ;
            $payouts[ $id ][ 'user_name' ]    = $affiliate_object->user_name . ' (#' . $payout_object->affiliate . ')' ;
            $payouts[ $id ][ 'referrals' ]    = implode( ',' , ( array ) $payout_object->referrals ) ;
            $payouts[ $id ][ 'payment_mode' ] = fs_affiliates_display_payment_method($payout_object->payment_mode) ;
            $payouts[ $id ][ 'paid_amount' ]  = $payout_object->paid_amount ;
            $payouts[ $id ][ 'currency' ]     = $currency ;
            $payouts[ $id ][ 'generate_by' ]  = $generate_by ;
            $payouts[ $id ][ 'status' ]       = strip_tags( fs_affiliates_get_status_display( $payout_object->get_status() ) ) ;
            $payouts[ $id ][ 'date' ]         = fs_affiliates_local_datetime( $payout_object->date ) ;
        }

        return $payouts ;
    }

    /**
     * Get Posts
     */
    public static function get_posts( $post_type = 'fs-affiliates' ) {
        $post_args = array(
            'post_type'   => $post_type ,
            'numberposts' => -1 ,
            'post_status' => 'any' ,
            'fields'      => 'ids' ,
            'date_query'  => array() ,
                ) ;


        if ( isset( $_POST[ 'fs_affiliates_selection' ] ) && $_POST[ 'fs_affiliates_selection' ] == '2' ) {
            if ( isset( $_POST[ 'fs_affiliates_selected_affiliates' ] ) && fs_affiliates_check_is_array( $_POST[ 'fs_affiliates_selected_affiliates' ] ) ) {
                $post_args[ 'include' ] = implode( ',' , $_POST[ 'fs_affiliates_selected_affiliates' ] ) ;
            }
        }

        if ( isset( $_POST[ 'from_date' ] ) && $_POST[ 'from_date' ] ) {
            $from_strtotime                                  = strtotime( $_POST[ 'from_date' ] ) ;
            $post_args[ 'date_query' ][ 'after' ][ 'year' ]  = date( 'Y' , $from_strtotime ) ;
            $post_args[ 'date_query' ][ 'after' ][ 'month' ] = date( 'n' , $from_strtotime ) ;
            $post_args[ 'date_query' ][ 'after' ][ 'day' ]   = date( 'j' , $from_strtotime ) ;
            $post_args[ 'date_query' ][ 'inclusive' ]        = true ;
        }

        if ( isset( $_POST[ 'to_date' ] ) && $_POST[ 'to_date' ] ) {
            $to_strtotime                                     = strtotime( $_POST[ 'to_date' ] ) ;
            $post_args[ 'date_query' ][ 'before' ][ 'year' ]  = date( 'Y' , $to_strtotime ) ;
            $post_args[ 'date_query' ][ 'before' ][ 'month' ] = date( 'n' , $to_strtotime ) ;
            $post_args[ 'date_query' ][ 'before' ][ 'day' ]   = date( 'j' , $to_strtotime ) ;
            $post_args[ 'date_query' ][ 'inclusive' ]         = true ;
        }

        if ( isset( $_POST[ 'fs_affiliates_status' ] ) && $_POST[ 'fs_affiliates_status' ] != 'all' ) {
            $post_args[ 'post_status' ] = $_POST[ 'fs_affiliates_status' ] ;
        }

        return get_posts( $post_args ) ;
    }

}

FS_Affiliates_Data_Export_Handler::init() ;

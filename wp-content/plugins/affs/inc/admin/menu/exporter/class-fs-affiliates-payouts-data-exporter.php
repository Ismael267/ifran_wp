<?php

if ( !defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly
}

/**
 * Handle Payouts Data Exporter.
 * 
 * @class FS_Affiliates_Payouts_Data_Exporter
 * @category Class
 */
class FS_Affiliates_Payouts_Data_Exporter {

    /**
     * Exporter page.
     *
     * @var string
     */
    public static $exporter_page = 'payouts-data-exporter' ;

    /**
     * Init FS_Affiliates_Payouts_Data_Exporter.
     */
    public static function init() {
        add_action( 'admin_head' , __CLASS__ . '::download_export_file' ) ;
    }

    /**
     * Generate the CSV file.
     */
    public static function download_export_file() {

        if ( isset( $_GET[ 'action' ] , $_GET[ 'nonce' ] ) && wp_verify_nonce( wp_unslash( $_GET[ 'nonce' ] ) , 'fs_affiliates-generate-payout' ) && 'download_exported_payouts_data_csv' === wp_unslash( $_GET[ 'action' ] ) ) {
            $field_datas = json_decode( base64_decode( $_GET[ 'data' ] ) , true ) ;

            ob_end_clean() ;
            header( "Content-type: text/csv" ) ;
            header( "Content-Disposition: attachment; filename=fs_affiliates-payouts-data-" . date_i18n( "Y-m-d H:i:s" ) . ".csv" ) ;
            header( "Pragma: no-cache" ) ;
            header( "Expires: 0" ) ;

            $handle        = fopen( "php://output" , 'w' ) ;
            $delimiter     = apply_filters( 'fs_affiliates_export_csv_delimiter' , ',' ) ;
            $enclosure     = apply_filters( 'fs_affiliates_export_csv_enclosure' , '"' ) ;
            $field_heading = apply_filters( 'fs_affiliates_export_csv_headings' , array (
                'Affiliate Name' ,
                'Payout Method' ,
                'Payment Details' ,
                'Commission' ,
                    ) ) ;
            $field_datas   = apply_filters( 'fs_affiliates_export_csv_field_datas' , $field_datas ) ;

            fputcsv( $handle , $field_heading , $delimiter , $enclosure ) ; // here you can change delimiter/enclosure

            if ( is_array( $field_datas ) && $field_datas ) {
                foreach ( $field_datas as $column => $data ) {
                    fputcsv( $handle , $data , $delimiter , $enclosure ) ; // here you can change delimiter/enclosure
                }
            }

            fclose( $handle ) ;
            exit() ;
        }
    }

    /**
     * Get generate payout page url
     * @return string
     */
    public static function get_generate_payout_page_url() {
        return add_query_arg( array ( 'page' => 'fs_affiliates' , 'tab' => 'referrals' , 'section' => 'generate_payout' ) , admin_url( 'admin.php' ) ) ;
    }

    /**
     * Get exported data download url
     * @param mixed $generated_data
     * @return string
     */
    public static function get_download_url( $generated_data = array () ) {
        $generated_data = is_array( $generated_data ) ? json_encode( $generated_data ) : $generated_data ;

        return esc_url_raw( add_query_arg( array (
            'nonce'  => wp_create_nonce( 'fs_affiliates-generate-payout' ) ,
            'action' => 'download_exported_payouts_data_csv' ,
            'data'   => base64_encode( $generated_data ) ,
                        ) , self::get_generate_payout_page_url() ) ) ;
    }

    /**
     * Generate data to export.
     * @param mixed $referrals
     * @param string $selected_payout_method
     * @param mixed $prev_generated_affiliates
     * @return array
     */
    public static function generate_data( $referrals , $selected_payout_method , $prev_generated_affiliates = array () ) {
        if ( empty( $selected_payout_method ) || empty( $referrals ) || !is_array( $referrals ) ) {
            return array () ;
        }

        $affiliates = array () ;
        foreach ( $referrals as $referral_id ) {
            $affiliate_id           = get_post( $referral_id )->post_author ;
            $payment_data           = get_post_meta( $affiliate_id , 'fs_affiliates_user_payment_datas' , true ) ;
            $selected_payout_method = str_replace( 'pay-via-' , '' , $selected_payout_method ) ;

            if ( !empty( $payment_data[ 'fs_affiliates_payment_method' ] ) && $selected_payout_method === $payment_data[ 'fs_affiliates_payment_method' ] ) {
                $Affiliate = new FS_Affiliates_Data( $affiliate_id ) ;
                $Referral  = new FS_Affiliates_Referrals( $referral_id ) ;

                if ( 'paypal' === $selected_payout_method ) {
                    if ( !empty( $payment_data[ 'fs_affiliates_paypal_email' ] ) ) {
                        $affiliates[ $affiliate_id ][ 'aff_name' ]        = $Affiliate->user_name ;
                        $affiliates[ $affiliate_id ][ 'payout_method' ]   = ucwords( $selected_payout_method ) ;
                        $affiliates[ $affiliate_id ][ 'payment_details' ] = $payment_data[ 'fs_affiliates_paypal_email' ] ;

                        if ( !isset( $affiliates[ $affiliate_id ][ 'commission' ] ) ) {
                            $affiliates[ $affiliate_id ][ 'commission' ] = 0 ;
                        }
                        if ( !empty( $prev_generated_affiliates[ $affiliate_id ][ 'commission' ] ) ) {
                            $affiliates[ $affiliate_id ][ 'commission' ] += floatval( $prev_generated_affiliates[ $affiliate_id ][ 'commission' ] ) ;
                        }
                        $affiliates[ $affiliate_id ][ 'commission' ] += floatval( $Referral->amount ) ;
                    }
                } else {
                    $affiliates[ $affiliate_id ][ 'aff_name' ]        = $Affiliate->user_name ;
                    $affiliates[ $affiliate_id ][ 'payout_method' ]   = ucwords( $selected_payout_method ) ;
                    $affiliates[ $affiliate_id ][ 'payment_details' ] = !empty( $payment_data[ 'fs_affiliates_bank_details' ] ) ? $payment_data[ 'fs_affiliates_bank_details' ] : $payment_data[ 'fs_affiliates_paypal_email' ] ;

                    if ( !isset( $affiliates[ $affiliate_id ][ 'commission' ] ) ) {
                        $affiliates[ $affiliate_id ][ 'commission' ] = 0 ;
                    }
                    if ( !empty( $prev_generated_affiliates[ $affiliate_id ][ 'commission' ] ) ) {
                        $affiliates[ $affiliate_id ][ 'commission' ] += floatval( $prev_generated_affiliates[ $affiliate_id ][ 'commission' ] ) ;
                    }
                    $affiliates[ $affiliate_id ][ 'commission' ] += floatval( $Referral->amount ) ;
                }
            }
        }
        return $affiliates ;
    }

}

FS_Affiliates_Payouts_Data_Exporter::init() ;

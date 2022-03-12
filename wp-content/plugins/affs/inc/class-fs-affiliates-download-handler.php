<?php

/**
 *  Handles download
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}
if ( ! class_exists( 'FS_Affiliates_Download_Handler' ) ) {

    /**
     * Class
     */
    class FS_Affiliates_Download_Handler {

        /**
         * Class Initialization.
         */
        public static function init() {
            add_action( 'wp_loaded' , array( __CLASS__ , 'download_affiliate_file' ) ) ;
            add_action( 'wp_loaded' , array( __CLASS__ , 'download_payout_statements' ) ) ;
        }

        /*
         * Process Download Affiliate File
         */

        public static function download_affiliate_file() {

            if ( ! isset( $_GET[ 'email' ] ) || ! $_GET[ 'email' ] || ! isset( $_GET[ 'fs_nonce' ] ) || ! $_GET[ 'fs_nonce' ] || ! isset( $_GET[ 'download_file' ] ) )
                return ;

            try {
                $affiliate_id = fs_affiliates_get_affiliate_by_metakey( 'email' , $_GET[ 'email' ] ) ;
                if ( ! $affiliate_id )
                    throw new Exception( __( 'Invalid Download Link' , FS_AFFILIATES_LOCALE ) ) ;

                $affiliates_object = new FS_Affiliates_Data( $affiliate_id ) ;

                $email_hash = function_exists( 'hash' ) ? hash( 'sha256' , $affiliates_object->email ) : sha1( $affiliates_object->email ) ;
                if ( $_GET[ 'fs_nonce' ] != $email_hash )
                    throw new Exception( __( 'Invalid Download Link' , FS_AFFILIATES_LOCALE ) ) ;

                $uploaded_files = $affiliates_object->uploaded_files ;

                if ( ! isset( $uploaded_files[ $_GET[ 'download_file' ] ] ) )
                    throw new Exception( __( 'File not exists' , FS_AFFILIATES_LOCALE ) ) ;

                $file_name = $_GET[ 'download_file' ] ;
                $file_path = $uploaded_files[ $file_name ] ;

                self::download( $file_path ) ;
            } catch ( Exception $ex ) {

                wp_die( $ex->getMessage() ) ; // WPCS: XSS ok.
            }
        }

        /*
         * Process Download Payout Statements
         */

        public static function download_payout_statements() {

            if ( ! isset( $_GET[ 'section' ] ) || ! isset( $_GET[ 'payout_statement_id' ] ) )
                return ;

            try {
                $payout_id        = $_GET[ 'payout_statement_id' ] ;
                $get_payout_datas = new FS_Affiliates_Payouts( $payout_id ) ;

                $file_path = $get_payout_datas->pay_statement_file_name ;

                self::download( $file_path ) ;
            } catch ( Exception $ex ) {

                wp_die( $ex->getMessage() ) ; // WPCS: XSS ok.
            }
        }

        /*
         * Process Download
         */

        public static function download( $file_path ) {

            self::headers( $file_path ) ;

            if ( ! self::readfile_chunked( $file_path ) )
                throw new Exception( __( 'File not exists' , FS_AFFILIATES_LOCALE ) ) ;

            exit() ;
        }

        /*
         * Headers
         */

        public static function headers( $file_path ) {

            header( 'Content-Description: File Transfer' ) ;
            header( 'Content-Type: ' . self::get_content_type( $file_path ) ) ;
            header( 'Content-Disposition: attachment; filename="' . basename( $file_path ) . '"' ) ;
            header( 'Content-Transfer-Encoding: binary' ) ;
            header( 'Content-Length: ' . @filesize( $file_path ) ) ;
        }

        /**
         * Get content type of a download.
         */
        private static function get_content_type( $file_path ) {
            $file_extension = strtolower( substr( strrchr( $file_path , '.' ) , 1 ) ) ;
            $ctype          = 'application/octet-stream' ;

            foreach ( get_allowed_mime_types() as $mime => $type ) {
                $mimes = explode( '|' , $mime ) ;
                if ( in_array( $file_extension , $mimes , true ) ) {
                    $ctype = $type ;
                    break ;
                }
            }

            return $ctype ;
        }

        /*
         * read file by chunked
         */

        public static function readfile_chunked( $file ) {

            $handle = @fopen( $file , 'r' ) ;

            if ( false === $handle )
                return false ;

            $length = @filesize( $file ) ; // length of file

            $current_position = 0 ;
            $read_length      = 1024 * 1024 ;
            $end              = $length - 1 ;

            while ( ! @feof( $handle ) && $current_position <= $end ) {

                if ( $current_position + $read_length > $end ) {
                    $read_length = $end - $current_position + 1 ;
                }

                echo @fread( $handle , $read_length ) ;

                $current_position = @ftell( $handle ) ;

                if ( ob_get_length() ) {
                    ob_flush() ;
                    flush() ;
                }
            }

            return @fclose( $handle ) ;
        }

    }

    FS_Affiliates_Download_Handler::init() ;
}

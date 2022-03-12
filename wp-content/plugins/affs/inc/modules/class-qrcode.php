<?php

/**
 * QR Code
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'FS_Affiliates_QR_Code' ) ) {

    /**
     * Class FS_Affiliates_QR_Code
     */
    class FS_Affiliates_QR_Code extends FS_Affiliates_Modules {
        /*
         * Data
         */

        protected $data = array(
            'enabled'        => 'no' ,
            'image_size'     => '5' ,
            'image_type'     => 'png' ,
            'ecc_data_level' => 'l' ,
                ) ;

        /**
         * Class Constructor
         */
        public function __construct() {
            $this->id    = 'qrcode' ;
            $this->title = __( 'QR Code' , FS_AFFILIATES_LOCALE ) ;

            parent::__construct() ;
        }

        /*
         * is enabled
         */

        public function is_plugin_enabled() {

            $phpversion = phpversion() ;

            return version_compare( $phpversion , '7.0.0' , '>' ) ;
        }

        /*
         * Get settings link
         */

        public function settings_link() {
            return add_query_arg( array( 'page' => 'fs_affiliates' , 'tab' => 'modules' , 'section' => $this->id ) , admin_url( 'admin.php' ) ) ;
        }

        /*
         * Get settings options array
         */

        public function settings_options_array() {
            return array(
                array(
                    'type'  => 'title' ,
                    'title' => __( 'QR Code Settings' , FS_AFFILIATES_LOCALE ) ,
                    'id'    => 'qrcode_options' ,
                ) ,
                array(
                    'title'   => __( 'Size' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->get_field_key( 'image_size' ) ,
                    'type'    => 'number' ,
                    'default' => '5' ,
                ) ,
                array(
                    'title'   => __( 'Image Type' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->get_field_key( 'image_type' ) ,
                    'type'    => 'select' ,
                    'default' => 'png' ,
                    'options' => array(
                        'png' => __( 'PNG' , FS_AFFILIATES_LOCALE ) ,
                        'jpg' => __( 'JPG' , FS_AFFILIATES_LOCALE ) ,
                        'gif' => __( 'GIF' , FS_AFFILIATES_LOCALE ) ,
                    )
                ) ,
                array(
                    'title'   => __( "Data Level" , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->get_field_key( 'ecc_data_level' ) ,
                    'type'    => 'select' ,
                    'default' => 'l' ,
                    'options' => array(
                        'l' => __( 'L' , FS_AFFILIATES_LOCALE ) ,
                        'm' => __( 'M' , FS_AFFILIATES_LOCALE ) ,
                        'q' => __( 'Q' , FS_AFFILIATES_LOCALE ) ,
                        'h' => __( 'H' , FS_AFFILIATES_LOCALE )
                    )
                ) ,
                array(
                    'type' => 'sectionend' ,
                    'id'   => 'qrcode_options' ,
                ) ,
                    ) ;
        }

        /*
         * Action
         */

        public function actions() {
            add_filter( 'fs_affiliates_generate_affiliate_link' , array( $this , 'generate_qrcode_link' ) , 10 , 2 ) ;
        }

        /*
         * Generate QRCode Link
         */

        public function generate_qrcode_link( $output , $link ) {

            $src = $this->generate_qrcode( $link ) ;

            $output .= '<p class="fs_affiliates_display_qrcode">' ;
            $output .= '<img src="' . $src . '" />' ;
            $output .= '<a class="fs_affiliates_qrdownload_btn" href="' . $src . '" download="' . basename( $src ) . '">' . __( "DOWNLOAD" , FS_AFFILIATES_LOCALE ) . '<i class="fa fa-download"></i></a>' ;
            $output .= '</p>' ;

            return $output ;
        }

        /*
         * Generate QRCode Link
         */

        public function generate_qrcode( $link ) {

            include_once(FS_AFFILIATES_PLUGIN_PATH . '/inc/lib/qrcode/autoloader.php') ;

            if ( empty( $this->image_size ) )
                $this->image_size = 5 ;

            switch ( $this->image_type ) {
                case 'jpg':
                    $type = \chillerlan\QRCode\QRCode::OUTPUT_IMAGE_JPG ;
                    break ;
                case 'gif':
                    $type = \chillerlan\QRCode\QRCode::OUTPUT_IMAGE_GIF ;
                    break ;
                default :
                    $type = \chillerlan\QRCode\QRCode::OUTPUT_IMAGE_PNG ;
                    break ;
            }

            switch ( $this->ecc_data_level ) {
                case 'q':
                    $ecc_data_level = \chillerlan\QRCode\QRCode::ECC_Q ;
                    break ;
                case 'l':
                    $ecc_data_level = \chillerlan\QRCode\QRCode::ECC_L ;
                    break ;
                case 'm':
                    $ecc_data_level = \chillerlan\QRCode\QRCode::ECC_M ;
                    break ;
                default :
                    $ecc_data_level = \chillerlan\QRCode\QRCode::ECC_H ;
                    break ;
            }

            $options = new \chillerlan\QRCode\QROptions( array(
                'version'    => ( int ) $this->image_size ,
                'outputType' => $type ,
                'eccLevel'   => $ecc_data_level ,
                    ) ) ;

            $object = new \chillerlan\QRCode\QRCode( $options ) ;

            //Generate QR Code with Link
            return $object->render( $link ) ;
        }

    }

}
    
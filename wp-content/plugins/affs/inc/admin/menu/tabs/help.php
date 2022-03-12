<?php
/**
 * Help Tab
 */
if ( !defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( class_exists( 'FS_Affiliates_Help_Tab' ) ) {
    return new FS_Affiliates_Help_Tab() ;
}

/**
 * FS_Affiliates_Help_Tab.
 */
class FS_Affiliates_Help_Tab extends FS_Affiliates_Settings_Page {

    /**
     * Constructor.
     */
    public function __construct() {
        $this->id    = 'help' ;
        $this->label = __( 'Help' , FS_AFFILIATES_LOCALE ) ;

        add_action( $this->plugin_slug . '_admin_field_output_help' , array ( $this , 'output_help' ) ) ;

        parent::__construct() ;
    }

    /**
     * Get settings array.
     */
    public function get_settings( $current_section = '' ) {
        return array (
            array ( 'type' => 'output_help' )
                ) ;
    }

    /**
     * Output the settings buttons.
     */
    public function output_buttons() {
        
    }

    /**
     * Output the affiliates help table
     */
    public function output_help() {
        $setting_page_url = '<a href="' . admin_url( "admin.php?page=fs-affiliates-welcome-page" ) . '" > ' ;
        $support_site_url = '<a href="http://support.fantasticplugins.com/" target="_blank"> ' ;
        ?>
        <div class="fs_affiliates_help_content">
            <h3><?php _e( 'Welcome Page' , FS_AFFILIATES_LOCALE ) ; ?></h3>
            <p><?php echo sprintf( __( 'For more information on SUMO Affiliates Pro please check the %s Welcome page' , FS_AFFILIATES_LOCALE ) , $setting_page_url ) ; ?></a></p>
            <h3><?php _e( 'Documentation' , FS_AFFILIATES_LOCALE ) ; ?></h3>
            <p> <?php _e( 'Please check the documentation as we have lots of information there. The documentation file can be found inside the documentation folder which you will find when you unzip the downloaded zip file.' , FS_AFFILIATES_LOCALE ) ; ?></p>
            <h3><?php _e( 'Contact Support' , FS_AFFILIATES_LOCALE ) ; ?></h3>
            <p id="fs_affiliates_support_content"> <?php echo sprintf( __( 'For support, feature request or any help, please %s register and open a support ticket on our site' , FS_AFFILIATES_LOCALE ) , $support_site_url ) ; ?></a></p>   
        </div>
        <?php
    }

}

return new FS_Affiliates_Help_Tab() ;

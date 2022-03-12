<?php

/**
 * Payouts Tab
 */
if ( !defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( class_exists( 'FS_Affiliates_Payouts_Tab' ) ) {
    return new FS_Affiliates_Payouts_Tab() ;
}

/**
 * FS_Affiliates_Payouts_Tab.
 */
class FS_Affiliates_Payouts_Tab extends FS_Affiliates_Settings_Page {

    /**
     * Constructor.
     */
    public function __construct() {
        $this->id    = 'payouts' ;
        $this->label = __( 'Payouts' , FS_AFFILIATES_LOCALE ) ;

        add_action( $this->plugin_slug . '_admin_field_output_payouts' , array ( $this , 'output_payouts' ) ) ;

        parent::__construct() ;
    }

    /**
     * Get settings array.
     */
    public function get_settings( $current_section = '' ) {

        return array (
            array ( 'type' => 'output_payouts' )
                ) ;
    }

    /**
     * Output the settings buttons.
     */
    public function output_buttons() {
        
    }

    /**
     * Output the affiliates payouts table
     */
    public function output_payouts() {

        global $current_section ;

        switch ( $current_section ) {
            case 'view' :
                $this->display_view_page() ;
                break ;
            default:
                $this->display_table() ;
                break ;
        }
    }

    /**
     * Output the affiliates payouts table
     */
    public function display_table() {
        if ( !class_exists( 'FS_Affiliates_Payouts_Post_Table' ) ) {
            require_once( FS_AFFILIATES_PLUGIN_PATH . '/inc/admin/menu/wp-list-table/class-fs-affiliates-payouts-table.php' ) ;
        }

        echo '<div class="' . $this->plugin_slug . '_table_wrap">' ;
        echo '<h2 class="wp-heading-inline">' . __( 'Payouts' , FS_AFFILIATES_LOCALE ) . '</h2>' ;
        if ( isset( $_REQUEST[ 's' ] ) && strlen( $_REQUEST[ 's' ] ) ) {
            /* translators: %s: search keywords */
            printf( ' <span class="subtitle">' . __( 'Search results for &#8220;%s&#8221;' ) . '</span>' , $_REQUEST[ 's' ] ) ;
        }

        $post_table = new FS_Affiliates_Payouts_Post_Table() ;
        $post_table->prepare_items() ;
        $post_table->views() ;
        $post_table->search_box( __( 'Search Payouts' , FS_AFFILIATES_LOCALE ) , $this->plugin_slug . '_search' ) ;
        $post_table->display() ;
        echo '</div>' ;
    }

    /**
     * Output the edit payout page
     */
    public function display_view_page() {
        if ( !isset( $_GET[ 'id' ] ) )
            return ;

        $payout_id  = $_GET[ 'id' ] ;
        $payout_obj = new FS_Affiliates_Payouts( $payout_id ) ;

        include_once( FS_AFFILIATES_PLUGIN_PATH . '/inc/admin/menu/views/payouts-view.php' ) ;
    }

}

return new FS_Affiliates_Payouts_Tab() ;

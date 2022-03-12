<?php

/**
 * Visits Tab
 */
if ( !defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( class_exists( 'FS_Affiliates_Visits_Tab' ) ) {
    return new FS_Affiliates_Visits_Tab() ;
}

/**
 * FS_Affiliates_Visits_Tab.
 */
class FS_Affiliates_Visits_Tab extends FS_Affiliates_Settings_Page {

    /**
     * Constructor.
     */
    public function __construct() {
        $this->id    = 'visits' ;
        $this->label = __( 'Visits' , FS_AFFILIATES_LOCALE ) ;

        add_action( $this->plugin_slug . '_admin_field_output_visits' , array ( $this , 'output_visits' ) ) ;

        parent::__construct() ;
    }

    /**
     * Get settings array.
     */
    public function get_settings( $current_section = '' ) {
        return array (
            array ( 'type' => 'output_visits' )
                ) ;
    }

    /**
     * Output the settings buttons.
     */
    public function output_buttons() {
        
    }

    /**
     * Output the affiliates visits table
     */
    public function output_visits() {
        if ( !class_exists( 'FS_Affiliates_Visits_Post_Table' ) ) {
            require_once( FS_AFFILIATES_PLUGIN_PATH . '/inc/admin/menu/wp-list-table/class-fs-affiliates-visits-table.php' ) ;
        }

        echo '<div class="' . $this->plugin_slug . '_table_wrap">' ;
        echo '<h2 class="wp-heading-inline">' . __( 'Visits' , FS_AFFILIATES_LOCALE ) . '</h2>' ;
        if ( isset( $_REQUEST[ 's' ] ) && strlen( $_REQUEST[ 's' ] ) ) {
            /* translators: %s: search keywords */
            printf( ' <span class="subtitle">' . __( 'Search results for &#8220;%s&#8221;' ) . '</span>' , $_REQUEST[ 's' ] ) ;
        }

        $post_table = new FS_Affiliates_Visits_Post_Table() ;
        $post_table->prepare_items() ;
        $post_table->views() ;
        $post_table->search_box( __( 'Search Visits' , FS_AFFILIATES_LOCALE ) , $this->plugin_slug . '_search' ) ;
        $post_table->display() ;
        echo '</div>' ;
    }

}

return new FS_Affiliates_Visits_Tab() ;

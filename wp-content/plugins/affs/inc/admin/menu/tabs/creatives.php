<?php

/**
 * Creatives Tab
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( class_exists( 'FS_Affiliates_Creatives_Tab' ) ) {
    return new FS_Affiliates_Creatives_Tab() ;
}

/**
 * FS_Affiliates_Creatives_Tab.
 */
class FS_Affiliates_Creatives_Tab extends FS_Affiliates_Settings_Page {

    /**
     * Constructor.
     */
    public function __construct() {
        $this->id    = 'creatives' ;
        $this->label = __( 'Creatives' , FS_AFFILIATES_LOCALE ) ;

        add_action( $this->plugin_slug . '_admin_field_output_creatives' , array( $this , 'output_creatives' ) ) ;

        parent::__construct() ;
    }

    /**
     * Get settings array.
     */
    public function get_settings( $current_section = '' ) {
        return array(
            array( 'type' => 'output_creatives' )
                ) ;
    }

    /**
     * Output the settings buttons.
     */
    public function output_buttons() {
        
    }

    /**
     * Output the affiliates creatives
     */
    public function output_creatives() {
        global $current_section ;

        switch ( $current_section ) {
            case 'new' :
                $this->display_new_page() ;
                break ;
            case 'edit' :
                $this->display_edit_page() ;
                break ;
            default:
                $this->display_table() ;
                break ;
        }
    }

    /**
     * Output the affiliates creatives table
     */
    public function display_table() {
        if ( ! class_exists( 'FS_Affiliates_Creatives_Post_Table' ) ) {
            require_once( FS_AFFILIATES_PLUGIN_PATH . '/inc/admin/menu/wp-list-table/class-fs-affiliates-creatives-table.php' ) ;
        }

        $new_section_url = add_query_arg( array( 'page' => 'fs_affiliates' , 'tab' => 'creatives' , 'section' => 'new' ) , admin_url( 'admin.php' ) ) ;
        echo '<div class="' . $this->plugin_slug . '_table_wrap">' ;
        echo '<h2 class="wp-heading-inline">' . __( 'Creatives' , FS_AFFILIATES_LOCALE ) . '</h2>' ;
        echo '<a class="page-title-action ' . $this->plugin_slug . '_add_btn" href="' . $new_section_url . '">' . __( 'Add New Creative' , FS_AFFILIATES_LOCALE ) . '</a>' ;
        if ( isset( $_REQUEST[ 's' ] ) && strlen( $_REQUEST[ 's' ] ) ) {
            /* translators: %s: search keywords */
            printf( ' <span class="subtitle">' . __( 'Search results for &#8220;%s&#8221;' ) . '</span>' , $_REQUEST[ 's' ] ) ;
        }

        $post_table = new FS_Affiliates_Creatives_Post_Table() ;
        $post_table->prepare_items() ;
        $post_table->views() ;
        $post_table->search_box( __( 'Search Creatives' , FS_AFFILIATES_LOCALE ) , $this->plugin_slug . '_search' ) ;
        $post_table->display() ;
        echo '</div>' ;
    }

    /**
     * Output the new affiliate page
     */
    public function display_new_page() {

        include_once( FS_AFFILIATES_PLUGIN_PATH . '/inc/admin/menu/views/creatives-new.php' ) ;
    }

    /**
     * Output the edit affiliate page
     */
    public function display_edit_page() {
        if ( ! isset( $_GET[ 'id' ] ) )
            return ;

        $creative_id     = $_GET[ 'id' ] ;
        $creative_object = new FS_Affiliates_Creatives( $creative_id ) ;

        include_once( FS_AFFILIATES_PLUGIN_PATH . '/inc/admin/menu/views/creatives-edit.php' ) ;
    }

    /**
     * Save settings.
     */
    public function save() {
        global $current_section ;

        if ( ! empty( $_POST[ 'register_new_creatives' ] ) ) {
            $this->create_new_creatives() ;
        } elseif ( ! empty( $_POST[ 'edit_creatives' ] ) ) {
            $this->update_creatives() ;
        }
    }

    /*
     * Create a new creative
     */

    public function create_new_creatives() {
        check_admin_referer( $this->plugin_slug . '_register_new_creatives' , '_' . $this->plugin_slug . '_nonce' ) ;

        try {
            $meta_data = $_POST[ 'creative' ] ;

            if ( $meta_data[ 'name' ] == '' ) {
                throw new Exception( __( 'Name cannot be empty' , FS_AFFILIATES_LOCALE ) ) ;
            }

            if ( empty( $meta_data[ 'image' ] ) ) {
                throw new Exception( __( 'Image cannot be empty' , FS_AFFILIATES_LOCALE ) ) ;
            }

            if ( $meta_data[ 'url' ] == '' ) {
                throw new Exception( __( 'URL cannot be empty' , FS_AFFILIATES_LOCALE ) ) ;
            }

            $meta_data[ 'description' ]         = $_POST[ 'description' ] ;
            $meta_data[ 'affiliate_selection' ] = absint( $meta_data[ 'affiliate_selection' ] ) ;
            if ( $meta_data[ 'affiliate_selection' ] == '2' ) {
                $meta_data[ 'include_affiliates' ] = isset( $meta_data[ 'include_affiliates' ] ) ? $meta_data[ 'include_affiliates' ] : array() ;
            } elseif ( $meta_data[ 'affiliate_selection' ] == '3' ) {
                $meta_data[ 'exclude_affiliates' ] = isset( $meta_data[ 'exclude_affiliates' ] ) ? $meta_data[ 'exclude_affiliates' ] : array() ;
            }

            $post_args = array(
                'post_status' => $meta_data[ 'status' ] ,
                'post_title'  => $meta_data[ 'name' ]
                    ) ;

            $referral_object = new FS_Affiliates_Creatives() ;
            $referral_object->create( $meta_data , $post_args ) ;

            FS_Affiliates_Settings::add_message( __( 'New Creative has been created.' , FS_AFFILIATES_LOCALE ) ) ;

            unset( $_POST[ 'creative' ] ) ;
        } catch ( Exception $ex ) {
            FS_Affiliates_Settings::add_error( $ex->getMessage() ) ;
        }
    }

    /*
     * Create a new creatives
     */

    public function update_creatives() {
        check_admin_referer( $this->plugin_slug . '_edit_creatives' , '_' . $this->plugin_slug . '_nonce' ) ;

        try {
            $meta_data = $_POST[ 'creative' ] ;

            if ( empty( $meta_data[ 'id' ] ) || $meta_data[ 'id' ] != $_REQUEST[ 'id' ] ) {
                throw new Exception( __( 'Cannot modify Creative Id' , FS_AFFILIATES_LOCALE ) ) ;
            }

            if ( $meta_data[ 'name' ] == '' ) {
                throw new Exception( __( 'Name cannot be empty' , FS_AFFILIATES_LOCALE ) ) ;
            }

            if ( empty( $meta_data[ 'image' ] ) ) {
                throw new Exception( __( 'Image cannot be empty' , FS_AFFILIATES_LOCALE ) ) ;
            }

            if ( $meta_data[ 'url' ] == '' ) {
                throw new Exception( __( 'URL cannot be empty' , FS_AFFILIATES_LOCALE ) ) ;
            }

            $meta_data[ 'description' ]         = $_POST[ 'description' ] ;
            $meta_data[ 'affiliate_selection' ] = absint( $meta_data[ 'affiliate_selection' ] ) ;
            if ( $meta_data[ 'affiliate_selection' ] == '2' ) {
                $meta_data[ 'include_affiliates' ] = isset( $meta_data[ 'include_affiliates' ] ) ? $meta_data[ 'include_affiliates' ] : array() ;
            } elseif ( $meta_data[ 'affiliate_selection' ] == '3' ) {
                $meta_data[ 'exclude_affiliates' ] = isset( $meta_data[ 'exclude_affiliates' ] ) ? $meta_data[ 'exclude_affiliates' ] : array() ;
            }

            $post_args = array(
                'post_status' => $meta_data[ 'status' ] ,
                'post_title'  => $meta_data[ 'name' ]
                    ) ;

            $affiliates_object = new FS_Affiliates_Creatives( $meta_data[ 'id' ] ) ;
            $affiliates_object->update( $meta_data , $post_args ) ;

            unset( $_POST[ 'creative' ] ) ;

            FS_Affiliates_Settings::add_message( __( 'Creative has been updated successfully.' , FS_AFFILIATES_LOCALE ) ) ;
        } catch ( Exception $ex ) {
            FS_Affiliates_Settings::add_error( $ex->getMessage() ) ;
        }
    }

}

return new FS_Affiliates_Creatives_Tab() ;
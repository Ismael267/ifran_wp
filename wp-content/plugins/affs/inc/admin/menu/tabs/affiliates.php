<?php

/**
 * Affiliates Tab
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( class_exists( 'FS_Affiliates_Tab' ) ) {
    return new FS_Affiliates_Tab() ;
}

/**
 * FS_Affiliates_Tab.
 */
class FS_Affiliates_Tab extends FS_Affiliates_Settings_Page {

    /**
     * Constructor.
     */
    public function __construct() {
        $this->id    = 'affiliates' ;
        $this->label = __( 'Affiliates' , FS_AFFILIATES_LOCALE ) ;

        add_action( $this->plugin_slug . '_admin_field_output_affiliates' , array( $this , 'output_affiliates' ) ) ;
        add_action( $this->plugin_slug . '_after_saved_' . $this->id , array( $this , 'save' ) ) ;

        parent::__construct() ;
    }

    /**
     * Get settings array.
     */
    public function get_settings( $current_section = '' ) {

        return array(
            array( 'type' => 'output_affiliates' )
                ) ;
    }

    /**
     * Output the settings buttons.
     */
    public function output_buttons() {
        
    }

    /**
     * Output the affiliates
     */
    public function output_affiliates() {
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
     * Output the affiliates table
     */
    public function display_table() {
        if ( ! class_exists( 'FS_Affiliates_Post_Table' ) ) {
            require_once( FS_AFFILIATES_PLUGIN_PATH . '/inc/admin/menu/wp-list-table/class-fs-affiliates-table.php' ) ;
        }

        $new_section_url = add_query_arg( array( 'page' => 'fs_affiliates' , 'tab' => 'affiliates' , 'section' => 'new' ) , admin_url( 'admin.php' ) ) ;
        echo '<div class="' . $this->plugin_slug . '_table_wrap">' ;
        echo '<h2 class="wp-heading-inline">' . __( 'Affiliates' , FS_AFFILIATES_LOCALE ) . '</h2>' ;
        echo '<a class="page-title-action ' . $this->plugin_slug . '_add_btn" href="' . $new_section_url . '">' . __( 'Add New Affiliate' , FS_AFFILIATES_LOCALE ) . '</a>' ;
        if ( isset( $_REQUEST[ 's' ] ) && strlen( $_REQUEST[ 's' ] ) ) {
            /* translators: %s: search keywords */
            printf( ' <span class="subtitle">' . __( 'Search results for &#8220;%s&#8221;' ) . '</span>' , $_REQUEST[ 's' ] ) ;
        }

        $post_table = new FS_Affiliates_Post_Table() ;
        $post_table->prepare_items() ;
        $post_table->views() ;
        $post_table->search_box( __( 'Search Affiliates' , FS_AFFILIATES_LOCALE ) , $this->plugin_slug . '_search' ) ;
        $post_table->display() ;
        echo '</div>' ;
    }

    /**
     * Output the new affiliate page
     */
    public function display_new_page() {

        include_once( FS_AFFILIATES_PLUGIN_PATH . '/inc/admin/menu/views/affiliates-new.php' ) ;
    }

    /**
     * Output the edit affiliate page
     */
    public function display_edit_page() {
        if ( ! isset( $_GET[ 'id' ] ) )
            return ;

        $affiliate_id      = $_GET[ 'id' ] ;
        $affiliates_object = new FS_Affiliates_Data( $affiliate_id ) ;

        include_once( FS_AFFILIATES_PLUGIN_PATH . '/inc/admin/menu/views/affiliates-edit.php' ) ;
    }

    /**
     * Save settings.
     */
    public function save() {
        global $current_section ;

        if ( ! empty( $_POST[ 'register_new_affiliates' ] ) ) {
            $this->create_new_affiliates() ;
        } elseif ( ! empty( $_POST[ 'edit_affiliates' ] ) ) {
            $this->update_affiliates() ;
        }
    }

    /*
     * Create a new affiliates
     */

    public function create_new_affiliates() {
        check_admin_referer( $this->plugin_slug . '_register_new_affiliates' , '_' . $this->plugin_slug . '_nonce' ) ;

        try {
            $meta_data           = $_POST[ 'affiliate' ] ;
            $meta_data[ 'date' ] = time() ;

            if ( $meta_data[ 'user_selection' ] == 'existing' ) {
                if ( ! isset( $meta_data[ 'user_id' ][ 0 ] ) ) {
                    throw new Exception( __( 'Please select a user to affiliate' , FS_AFFILIATES_LOCALE ) ) ;
                }

                $user_id = $meta_data[ 'user_id' ][ 0 ] ;
                $user    = get_user_by( 'id' , $user_id ) ;

                $meta_data[ 'first_name' ] = $user->first_name ;
                $meta_data[ 'last_name' ]  = $user->last_name ;
                $meta_data[ 'email' ]      = $user->user_email ;
                $meta_data[ 'user_role' ]  = $user->role ;
                $meta_data[ 'website' ]    = $user->user_url ;
                $user_name                 = $user->user_login ;
            } else {
                if ( $meta_data[ 'user_name' ] == '' ) {
                    throw new Exception( __( 'Please provide a username to create a new user' , FS_AFFILIATES_LOCALE ) ) ;
                }

                if ( $meta_data[ 'email' ] == '' ) {
                    throw new Exception( __( 'Please provide an email to create a new user' , FS_AFFILIATES_LOCALE ) ) ;
                }

                if ( ! filter_var( $meta_data[ 'email' ] , FILTER_VALIDATE_EMAIL ) ) {
                    throw new Exception( __( 'Please enter a valid email' , FS_AFFILIATES_LOCALE ) ) ;
                }

                if ( $meta_data[ 'password' ] != $meta_data[ 'repeated_password' ] ) {
                    throw new Exception( __( 'Passwords do not match' , FS_AFFILIATES_LOCALE ) ) ;
                }

                $user_data = array(
                    'user_login' => $meta_data[ 'user_name' ] ,
                    'user_pass'  => $meta_data[ 'password' ] ,
                    'user_email' => $meta_data[ 'email' ] ,
                    'first_name' => $meta_data[ 'first_name' ] ,
                    'last_name'  => $meta_data[ 'last_name' ] ,
                    'role'       => $meta_data[ 'user_role' ] ,
                    'user_url'   => $meta_data[ 'website' ]
                        ) ;

                $user_id = fs_affiliates_insert_user( $user_data ) ;
                if ( ! $user_id ) {
                    throw new Exception( __( 'Error while creating a new user' , FS_AFFILIATES_LOCALE ) ) ;
                }

                $user_name = $meta_data[ 'user_name' ] ;
            }

            $parent_id = ( isset( $meta_data[ 'parent' ][ 0 ] ) ) ? $meta_data[ 'parent' ][ 0 ] : fs_affiliates_get_default_parent_affiliate() ;

            $post_args = array(
                'post_status' => $meta_data[ 'status' ] ,
                'post_author' => $user_id ,
                'post_parent' => $parent_id ,
                'post_title'  => $user_name
                    ) ;

            $meta_data[ 'uploaded_files' ]   = get_transient( $meta_data[ 'uploaded_key' ] ) ;
            $meta_data[ 'commission_value' ] = fs_affiliates_format_decimal( $meta_data[ 'commission_value' ] , true ) ;
            $meta_data[ 'wc_product_rates' ] = isset( $meta_data[ 'wc_product_rates' ] ) ? $meta_data[ 'wc_product_rates' ] : array() ;

            $affiliate_id = fs_affiliates_create_new_affiliate( $meta_data , $post_args ) ;

            // Update Payment Data from admin
            if ( isset( $meta_data[ 'payment_method' ] ) ) {
                fs_update_affiliate_payment_data( $affiliate_id , $meta_data[ 'payment_method' ] , 'new' ) ;
            }

            if ( isset( $meta_data[ 'email_notification' ] ) ) {
                do_action( 'fs_affiliates_admin_to_affiliate_notification' , $affiliate_id , $meta_data ) ;
            }
            unset( $_POST[ 'affiliate' ] ) ;

            FS_Affiliates_Settings::add_message( __( 'New Affiliate has been created.' , FS_AFFILIATES_LOCALE ) ) ;
        } catch ( Exception $ex ) {
            FS_Affiliates_Settings::add_error( $ex->getMessage() ) ;
        }
    }

    /*
     * Create a new affiliates
     */

    public function update_affiliates() {
        check_admin_referer( $this->plugin_slug . '_edit_affiliates' , '_' . $this->plugin_slug . '_nonce' ) ;

        try {
            $meta_data = $_POST[ 'affiliate' ] ;

            if ( empty( $meta_data[ 'id' ] ) || $meta_data[ 'id' ] != $_REQUEST[ 'id' ] ) {
                throw new Exception( __( 'Cannot modify Affiliate Id' , FS_AFFILIATES_LOCALE ) ) ;
            }

            if ( $meta_data[ 'email' ] == '' ) {
                throw new Exception( __( 'Email field cannot be empty' , FS_AFFILIATES_LOCALE ) ) ;
            }

            if ( ! filter_var( $meta_data[ 'email' ] , FILTER_VALIDATE_EMAIL ) ) {
                throw new Exception( __( 'Please enter a valid email' , FS_AFFILIATES_LOCALE ) ) ;
            }

            $parent_id = ( isset( $meta_data[ 'parent' ][ 0 ] ) ) ? $meta_data[ 'parent' ][ 0 ] : fs_affiliates_get_default_parent_affiliate() ;

            $post_args = array(
                'post_status' => $meta_data[ 'status' ] ,
                'post_title'  => $meta_data[ 'user_name' ] ,
                'post_parent' => $parent_id
                    ) ;

            $meta_data[ 'uploaded_files' ]   = get_transient( 'fs_affiliates_file_upload_' . $meta_data[ 'id' ] ) ;
            $meta_data[ 'commission_value' ] = fs_affiliates_format_decimal( $meta_data[ 'commission_value' ] , true ) ;
            $meta_data[ 'wc_product_rates' ] = isset( $meta_data[ 'wc_product_rates' ] ) ? $meta_data[ 'wc_product_rates' ] : array() ;

            // Update Payment Data from admin
            if ( isset( $meta_data[ 'payment_method' ] ) ) {
                fs_update_affiliate_payment_data( $meta_data[ 'id' ] , $meta_data[ 'payment_method' ] , 'exist' ) ;
            }

            //update Affiliate
            fs_affiliates_update_affiliate( $meta_data[ 'id' ] , $meta_data , $post_args ) ;

            unset( $_POST[ 'affiliate' ] ) ;

            FS_Affiliates_Settings::add_message( __( 'Affiliate has been updated successfully.' , FS_AFFILIATES_LOCALE ) ) ;
        } catch ( Exception $ex ) {
            FS_Affiliates_Settings::add_error( $ex->getMessage() ) ;
        }
    }

}

return new FS_Affiliates_Tab() ;

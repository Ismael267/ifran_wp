<?php
/**
 * Paypal payouts
 */
if( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if( ! class_exists( 'FS_Affiliates_landing_pages' ) ) {

    /**
     * Class
     */
    class FS_Affiliates_landing_pages extends FS_Affiliates_Modules {
        /*
         * Data
         */

        protected $data = array (
            'enabled'       => 'no' ,
            'alert_message' => '' ,
            'hide_affiliate_link' => 'no',
                ) ;

        /**
         * Class Constructor
         */
        public function __construct() {

            $this->id    = 'landing_pages' ;
            $this->title = __( 'Affiliate Landing Pages' , FS_AFFILIATES_LOCALE ) ;

            parent::__construct() ;
        }

        /*
         * Get settings link
         */

        public function settings_link() {
            return add_query_arg( array ( 'page' => 'fs_affiliates' , 'tab' => 'modules' , 'section' => $this->id ) , admin_url( 'admin.php' ) ) ;
        }

        /**
         * Get settings array.
         */
        public function settings_options_array() {
            return array (
                array (
                    'type'  => 'title' ,
                    'title' => esc_html__( 'Affiliate Landing Page' , FS_AFFILIATES_LOCALE ) ,
                    'id'    => 'landing_page_settings_option' ,
                ) ,
                array (
                    'title'   => esc_html__( 'Landing Page Alert Message' , FS_AFFILIATES_LOCALE ) ,
                    'desc'    => esc_html__( 'This message will display when affiliate trying to generate the link using their landing page.' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->plugin_slug . '_' . $this->id . '_alert_message' ,
                    'type'    => 'text' ,
                    'default' => 'You cannot use an affiliate landing page here' ,
                ) ,
                array (
                    'title'   => esc_html__( 'Hide Affiliate Link Generator' , FS_AFFILIATES_LOCALE ) ,
                    'desc'    => esc_html__( 'By enabling this checkbox, you can hide the affiliate link generator on the frontend dashboard when an affiliate landing page is assigned to affiliates.' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->plugin_slug . '_' . $this->id . '_hide_affiliate_link' ,
                    'type'    => 'checkbox' ,
                    'default' => 'no' ,
                ) ,
                array (
                    'type' => 'sectionend' ,
                    'id'   => 'landing_page_settings_option' ,
                ) ,
                    ) ;
        }

        /*
         * Admin Actions
         */

        public function admin_action() {
            add_action( 'add_meta_boxes' , array( $this , 'landing_pages_meta_boxes' ) , 10 ) ;

            add_action( 'save_post' , array( $this , 'save_post' ) , 10 , 1 ) ;
        }

        /**
         * Frontend Actions
         */
        public function frontend_action() {
            add_filter( 'fs_affiliates_check_is_affiliate' , array( $this , 'get_landing_page_affiliate_id' ) , 10 , 1 ) ;
            add_action( 'fs_affiliates_before_link_generator' , array( $this , 'display_landing_page_table' ) , 11 , 2 ) ;
            add_action( 'fs_affiliates_before_generate_affiliate_url' , array( $this , 'validate_affiliate_url' ) , 10 , 1 ) ;
            add_action( 'fs_affiliates_disp_link_generator' , array( $this , 'display_affiliate_link_generator' ) , 10 , 1 ) ;
            
        }

        /*
         * Custom Meta box for landing pages
         */

        public function landing_pages_meta_boxes() {
            add_meta_box( 'fs_select_affiliates' , __( 'SUMO Affiliates Pro – Affiliate Landing Page' , FS_AFFILIATES_LOCALE ) , array( $this , 'select_affiliates' ) , array( 'page' , 'post' , 'product' ) , 'side' , 'high' ) ;
        }

        /*
         * Get landing page affiliate ID
         */

        public function get_landing_page_affiliate_id( $affiliateid ) {

            if( ! $affiliateid && ! apply_filters( 'fs_affiliates_check_if_last_referral' , false ) ) {
                $post_id     = get_the_ID() ;
                $affiliateid = get_post_meta( $post_id , 'fs_affiliates_landing_page' , true ) ;
            }

            return $affiliateid ;
        }

        /*
         * Validate affiliate Url
         */

        public function validate_affiliate_url( $affiliateurl ) {

            $post_id = url_to_postid( $affiliateurl ) ;

            $AffiliateID = get_post_meta( $post_id , 'fs_affiliates_landing_page' , true ) ;

            if ( $AffiliateID ) {
                throw new exception($this->alert_message) ;
            }
        }
        
        /*
         * Display Affiliate Link Generator
         */

        public function display_affiliate_link_generator( $affiliateid ) {
            $landing_pages = fs_affiliates_get_landing_pages( $affiliateid ) ;

            if(fs_affiliates_check_is_array($landing_pages) && $this->hide_affiliate_link == 'yes') {
                return false;
            }
            
            return true;
        }

        /*
         * Display landing page table
         */

        public function display_landing_page_table( $affiliateid , $user_id ) {

            $landing_pages = fs_affiliates_get_landing_pages( $affiliateid ) ;
            ?>
            <table class="fs_affiliates_landing_page_table fs_affiliates_table fs_affiliates_frontend_table">
                <tr>
                    <th class="fs_affiliates_sno fs_affiliates_landing_page_sno"> <label><?php esc_html_e( 'S.No' , FS_AFFILIATES_LOCALE ) ; ?></label> </th>
                    <th> <label><?php esc_html_e( 'Landing Pages' , FS_AFFILIATES_LOCALE ) ; ?></label></th>
                    <th><label><?php esc_html_e( 'Copy Link' , FS_AFFILIATES_LOCALE ) ; ?></label></th>
                </tr>
                <?php
                if( fs_affiliates_check_is_array( $landing_pages ) ) {
                    $landing_pages = array_filter( $landing_pages ) ;
                    $i             = 0 ;
                    foreach( $landing_pages as $post_id ) {
                        $i ++ ;
                        $permalink = get_permalink( $post_id ) ;
                        ?>
                        <tr>
                            <td data-title="<?php esc_html_e( 'S.No' , FS_AFFILIATES_LOCALE ) ?>" style="text-align:center" class="fs_affiliates_sno fs_affiliates_landing_page_sno">
                                <label><?php echo $i ; ?></label>
                            </td>
                            <td data-title="<?php esc_html_e( 'Landing Pages' , FS_AFFILIATES_LOCALE ) ?>" >
                                <div class="fs_landing_pages_link">
                                    <p><?php echo $permalink ; ?></p>
                                    <?php echo apply_filters( 'fs_affiliates_generate_affiliate_link' , '' , $permalink ) ; ?>
                                </div>
                            </td>
                            <td data-title="<?php esc_html_e( 'Copy Link' , FS_AFFILIATES_LOCALE ) ?>" class="fs_copy_affiliate_link">
                                <?php echo fs_display_copy_affiliate_link_image( $permalink ) ; ?>
                            </td>
                        </tr>
                        <?php
                    }
                }
                ?>
            </table> 
            <?php
        }

        /*
         * Save post
         */

        public function save_post( $post_id ) {

            if( ( wp_is_post_revision( $post_id ) || wp_is_post_autosave( $post_id ) ) )
                return ;

            $affiliate_id = get_post_meta( $post_id , 'fs_affiliates_landing_page' , true ) ;

            if( $affiliate_id ) {
                $existed_posts = get_post_meta( $affiliate_id , 'landing_pages' , true ) ;

                if( fs_affiliates_check_is_array( $existed_posts ) ) {
                    $index_value = array_search( $post_id , $existed_posts ) ;

                    unset( $existed_posts[ $index_value ] ) ;

                    update_post_meta( $affiliate_id , 'landing_pages' , $existed_posts ) ;
                }
            }

            if( isset( $_POST[ 'fs_affiliates_landing_page' ] ) ) {

                $affiliate_id = $_POST[ 'fs_affiliates_landing_page' ][ 0 ] ;

                update_post_meta( $post_id , 'fs_affiliates_landing_page' , $affiliate_id ) ;

                $existed_posts = ( array ) get_post_meta( $affiliate_id , 'landing_pages' , true ) ;

                if( ( ! in_array( $post_id , $existed_posts ) ) ) {
                    if( fs_affiliates_check_is_array( $existed_posts ) ) {
                        $updated_array = array_merge( $existed_posts , array( $post_id ) ) ;
                        update_post_meta( $affiliate_id , 'landing_pages' , $updated_array ) ;
                    } else {
                        update_post_meta( $affiliate_id , 'landing_pages' , array( $post_id ) ) ;
                    }
                }
            } else {
                delete_post_meta( $post_id , 'fs_affiliates_landing_page' ) ;
            }
        }

        /*
         * Display Select affiliates Dropdown
         */

        public function select_affiliates() {
            global $post ;

            $suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min' ;

            FS_Affiliates_Admin_Assets::select2( $suffix ) ;

            $selected_affiliate = isset( $post->ID ) ? get_post_meta( $post->ID , 'fs_affiliates_landing_page' , true ) : '' ;
            fs_affiliates_select2_html(
                    array(
                        'title'     => __( 'Select an Affiliate' , FS_AFFILIATES_LOCALE ) ,
                        'id'        => 'fs_affiliates_landing_page' ,
                        'type'      => 'ajaxmultiselect' ,
                        'class'     => 'fs_affiliates_selected_affiliate' ,
                        'css'       => 'width:250px;' ,
                        'list_type' => 'affiliates' ,
                        'multiple'  => false ,
                        'action'    => 'fs_affiliates_search' ,
                        'options'   => array( $selected_affiliate ) ,
                        'default'   => array() ,
                    )
            ) ;
        }

    }

}

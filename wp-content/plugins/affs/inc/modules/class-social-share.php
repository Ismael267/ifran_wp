<?php
/**
 * Social Share
 */
if( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if( ! class_exists( 'FS_Affiliates_Social_Share' ) ) {

    /**
     * Class FS_Affiliates_Social_Share
     */
    class FS_Affiliates_Social_Share extends FS_Affiliates_Modules {
        /*
         * Data
         */

        protected $data = array(
            'enabled'       => 'no' ,
            'fbshare'       => 'no' ,
            'tweet'         => 'no' ,
            'gplus_share'   => 'no' ,
            'vkshare'       => 'no' ,
            'whatsappshare' => 'no' ,
            'fbappid'       => ''
                ) ;

        /**
         * Class Constructor
         */
        public function __construct() {
            $this->id    = 'socialshare' ;
            $this->title = __( 'Social Share' , FS_AFFILIATES_LOCALE ) ;

            parent::__construct() ;
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
                    'title' => __( 'Social Share Settings' , FS_AFFILIATES_LOCALE ) ,
                    'id'    => 'social_share_options' ,
                ) ,
                array(
                    'title'   => __( 'Facebook Share' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->plugin_slug . '_' . $this->id . '_fbshare' ,
                    'desc'    => __( 'By enabling this checkbox, affiliates can promote their affiliate link by sharing on their facebook account.' , FS_AFFILIATES_LOCALE ) ,
                    'type'    => 'checkbox' ,
                    'default' => 'no' ,
                ) ,
                array(
                    'title'   => __( 'Facebook Application ID' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->plugin_slug . '_' . $this->id . '_fbappid' ,
                    'desc'    => __( 'Please enter facebook application id. <a href="https://developers.facebook.com/" target="_blank">Click here</a> to find the app id.' , FS_AFFILIATES_LOCALE ) ,
                    'type'    => 'text' ,
                    'default' => '' ,
                ) ,
                array(
                    'title'   => __( 'Twitter Tweet' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->plugin_slug . '_' . $this->id . '_tweet' ,
                    'desc'    => __( 'By enabling this checkbox, affiliates can promote their affiliate link by tweeting on their twitter account.' , FS_AFFILIATES_LOCALE ) ,
                    'type'    => 'checkbox' ,
                    'default' => 'no' ,
                ) ,
                array(
                    'title'   => __( 'Google+ Share' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->plugin_slug . '_' . $this->id . '_gplus_share' ,
                    'desc'    => __( 'By enabling this checkbox, affiliates can promote their affiliate link by sharing on their  g+ account.' , FS_AFFILIATES_LOCALE ) ,
                    'type'    => 'checkbox' ,
                    'default' => 'no' ,
                ) ,
                array(
                    'title'   => __( 'VK.Com Share' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->plugin_slug . '_' . $this->id . '_vkshare' ,
                    'desc'    => __( 'By enabling this checkbox, affiliates can promote their affiliate link by sharing on their vk account.' , FS_AFFILIATES_LOCALE ) ,
                    'type'    => 'checkbox' ,
                    'default' => 'no' ,
                ) ,
                array(
                    'title'   => __( 'Whatsapp Share' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->plugin_slug . '_' . $this->id . '_whatsappshare' ,
                    'desc'    => __( 'By enabling this checkbox, affiliates can promote their affiliate link by sharing to their contacts.' , FS_AFFILIATES_LOCALE ) ,
                    'type'    => 'checkbox' ,
                    'default' => 'no' ,
                ) ,
                array(
                    'type' => 'sectionend' ,
                    'id'   => 'social_share_options' ,
                ) ,
                    ) ;
        }

        /*
         * Action
         */

        public function actions() {
            add_filter( 'fs_affiliates_generate_affiliate_link' , array( $this , 'display_social_share_buttons' ) , 1 , 2 ) ;
        }

        public function display_social_share_buttons( $output , $link ) {
            if( $this->fbshare == 'yes' && ! empty( $this->fbappid ) )
                $output .= self::fb_share_button( $link ) ;

            if( $this->tweet == 'yes' )
                $output .= self::tweet_button( $link ) ;

            if( $this->gplus_share == 'yes' )
                $output .= self::gplus_share_button( $link ) ;

            if( $this->vkshare == 'yes' )
                $output .= self::vk_share_button( $link ) ;

            if( $this->whatsappshare == 'yes' )
                $output .= self::whatsapp_share_button( $link ) ;

            return $output ;
        }

        public function fb_share_button( $link ) {
            ob_start() ;
            ?>
            <div id="fb-root"></div>
            <span class="fp_share_button fp_affiliates_fb" data-href="<?php echo $link ; ?>" data-size="small" data-mobile-iframe="true">
                <i class="fa fa-facebook-square" aria-hidden="true"></i>
                <?php _e( 'Share' , FS_AFFILIATES_LOCALE ) ; ?>
            </span>
            <?php
            return ob_get_clean() ;
        }

        public function tweet_button( $link ) {
            ob_start() ;
            ?>
            <a class="twitter-share-button" id="twitter-share-button" href="https://twitter.com/share" data-url="<?php echo $link ; ?>"><?php _e( 'Tweet' , FS_AFFILIATES_LOCALE ) ; ?></a>
            <?php
            return ob_get_clean() ;
        }

        public function gplus_share_button( $link ) {
            ob_start() ;
            ?>
            <script src="https://apis.google.com/js/platform.js" async defer></script>
            <div class="g-plus" data-action="share" data-annotation="none" data-href="<?php echo $link ; ?>">
                <g:plus></g:plus>
            </div>
            <?php
            return ob_get_clean() ;
        }

        public function vk_share_button( $link ) {
            ob_start() ;
            ?>
            <a class="fp_affiliates_vk" href="http://vk.com/share.php?url=<?php echo $link ; ?>" target="_blank">
                <i class="fa fa-vk" aria-hidden="true"></i>
                <?php _e( 'Share' , FS_AFFILIATES_LOCALE ) ; ?>
            </a>
            <?php
            return ob_get_clean() ;
        }

        public function whatsapp_share_button( $link ) {
            ob_start() ;
            ?>
            <a class="fp_affiliates_whatsapp" href="https://web.whatsapp.com://send?text=<?php echo $link ; ?>"><i class="fa fa-whatsapp" aria-hidden="true"></i><?php _e( 'Share' , FS_AFFILIATES_LOCALE ) ; ?></a>
            <?php
            return ob_get_clean() ;
        }

    }

}
    
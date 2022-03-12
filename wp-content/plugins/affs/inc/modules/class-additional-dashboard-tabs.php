<?php
/**
 * Additional Dashboard Tabs
 */
if ( !defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( !class_exists( 'FS_Affiliates_Additional_Dashboard_Tabs' ) ) {

    /**
     * Class FS_Affiliates_Additional_Dashboard_Tabs
     */
    class FS_Affiliates_Additional_Dashboard_Tabs extends FS_Affiliates_Modules {
        /*
         * Data
         */

        protected $data = array (
            'enabled' => 'no' ,
            'rules'   => array ()
                ) ;

        /**
         * Class Constructor
         */
        public function __construct() {
            $this->id    = 'additional_dashboard_tabs' ;
            $this->title = __( 'Additional Dashboard Tabs' , FS_AFFILIATES_LOCALE ) ;

            parent::__construct() ;
        }

        /*
         * Get settings link
         */

        public function settings_link() {
            return add_query_arg( array ( 'page' => 'fs_affiliates' , 'tab' => 'modules' , 'section' => $this->id ) , admin_url( 'admin.php' ) ) ;
        }

        /*
         * Get settings options array
         */

        public function settings_options_array() {

            return array (
                array (
                    'id'      => $this->get_field_key( 'rules' ) ,
                    'type'    => 'output_dashboard_additional_tabs' ,
                    'default' => fs_affiliates_get_default_additional_dashboard_tab_settings() ,
                )
                    ) ;
        }

        /**
         * Frontend Actions
         */
        public function frontend_action() {
            add_filter('fs_affiliate_get_additional_dashboard_tab_settings',array ( $this , 'get_additional_dashboard_tab_settings' ) , 10 , 1 );
            add_action( 'fs_affiliates_dashboard_content' , array ( $this , 'custom_dashboard_content' ) , 10 , 3 ) ;
            add_filter( 'fs_affiliates_frontend_dashboard_menu' , array ( $this , 'hide_dashboard_menu' ) , 99 , 3 ) ;
            add_filter( 'fs_affiliates_frontend_dashboard_affiliate_tools_submenus' , array ( $this , 'hide_affiliate_tools_submenu' ) , 99 , 3);
            add_filter( 'fs_affiliates_frontend_dashboard_profile_submenus' , array ( $this , 'hide_profile_submenu' ) , 99 , 3);
            add_filter( 'fs_affiliates_show_warning_notice', array ( $this , 'show_warning_notice' ) , 99 , 3);
        }

        /**
         * Admin Actions
         */
        public function admin_action() {
            add_action( $this->plugin_slug . '_admin_field_output_dashboard_additional_tabs' , array ( $this , 'output_dashboard_additional_tabs' ) ) ;
        }

        /*
         * Get Rules
         */

        public function get_rules() {
            $default_fields = fs_affiliates_get_default_additional_dashboard_tab_settings() ;

            return array_filter( fs_affiliates_array_merge_recursive_distinct( $this->rules , $default_fields ) ) ;
        }

        /*
         * hide Dashboard Menu
         */

        public function hide_dashboard_menu( $menus , $user_id , $affiliate_id ) {
            $rules = $this->get_rules() ;

            if ( !fs_affiliates_check_is_array( $rules ) )
                return $menus ;

            foreach ($rules as $tab_key => $tab) {
                // add extra additional fields
                if (isset($rules[$tab_key]['content']))
                    $menus[$tab_key] = array('label' => $rules[$tab_key]['tile'], 'code' => $rules[$tab_key]['code']);
                // hide additional fields
                if ($tab['hide'] == 'yes')
                    unset($menus[$tab_key]);
            }

            return $menus ;
        }

        /*
         * Affiliate Tools Submenu
         */

        public function hide_affiliate_tools_submenu( $menus , $user_id , $affiliate_id ) {
            $rules = $this->get_rules() ;

            if( $rules['affiliate_tools']['hide'] == 'yes' ){
              return $menus ;
            }

            $rules = $rules['affiliate_tools']['selected_submenu'];

            if(fs_affiliates_check_is_array($rules)) {
              foreach($rules as $hide) {
                unset($menus[$hide]);
              }
            }

            return $menus ;
        }

        public function get_additional_dashboard_tab_settings($menus){
          return $this->get_rules() ;
        }

        /*
         * Profile Submenu
         */

        public function hide_profile_submenu( $menus , $user_id , $affiliate_id ) {
            $rules = $this->get_rules() ;

            if( $rules['profile']['hide'] == 'yes' )
              return $menus ;

            $rules = $rules['profile']['selected_submenu'];

            if(fs_affiliates_check_is_array($rules)) {
              foreach($rules as $hide) {
                unset($menus[$hide]);
              }
            }

            return $menus ;
        }

        /*
         * Profile Submenu
         */

        public function show_warning_notice ( $true , $menu , $submenu ) {
            $rules = $this->get_rules() ;

            if( $rules[$menu]['hide'] == 'yes' )
              return false ;

            $selected_submenus = $rules[$menu]['selected_submenu'];

            foreach($selected_submenus as $selected_submenu) {
              if( $selected_submenu == $submenu )
                return false ;
            }

          return $true ;
        }
        /*
         * Custom Dashboard Content
         */

        public function custom_dashboard_content( $tab_name , $user_id , $affiliate_id ) {
            $rules = $this->get_rules() ;
            if ( !array_key_exists( $tab_name , $rules ) )
                return ;

            $tab = $rules[ $tab_name ] ;

            if ( !isset( $tab[ 'content' ] ) )
                return ;

            $content = wpautop( stripslashes( $tab[ 'content' ] ) ) ;

            $content = do_shortcode( $content ) ;

            echo $content ;
        }

        /**
         * Output dashboard additional tabs settings
         */
        public function output_dashboard_additional_tabs() {
            $setting_key  = $this->get_field_key( 'rules' ) ;
            ?>
            <h2><?php _e( 'Additional Dashboard Tabs' , FS_AFFILIATES_LOCALE ) ; ?></h2>
            <input type="button" class="fs_affiliates_add_dashboard_tab_rule"  value="<?php _e( 'Add New Tab' , FS_AFFILIATES_LOCALE ) ; ?>"/>
            <table class="widefat fs_affiliates_dashboard_additional_tabs_table">
                <tbody id="fs_affiliates_list">
                    <?php
                    $key          = 0 ;
                    $rules        = $this->get_rules() ;
                    $font_awesome = fs_affiliates_get_font_awesome_codes() ;
                    if ( fs_affiliates_check_is_array( $rules ) ) {

                        foreach ( $rules as $tab_key => $tab ) {
                            $custom_tab = !in_array( $tab_key , array ( 'overview' , 'profile' , 'affiliate_tools' , 'referrals' , 'visits' , 'payouts' , 'wallet' , 'pushover_notifications' , 'leaderboard' , 'wc_coupon_linking' , 'url_masking' , 'payout_request', 'logout') ) ;
                            ?><tr>
                                <td>
                                    <input type="hidden" id="fs_affiliates_dashboard_tab_rule_id" value="<?php echo $key ; ?>"/>
                                    <input type="hidden" class="fs_affiliates_sortable" name="<?php echo $setting_key . '[' . $key . ' ][key]' ; ?>" value="<?php echo $tab[ 'key' ] ; ?>"/>
                                    <input type="hidden" name="<?php echo $setting_key . '[' . $key . ' ][tile]' ; ?>" value="<?php echo $tab[ 'tile' ] ; ?>"/>
                                    <div class = "fs_affiliates_tabs_sort_handle fs_affiliates_tabs_toggle">
                                        <h3><?php echo $tab[ 'tile' ] ; ?>
                                            <i class="fa fa-bars" ></i>
                                        </h3>
                                    </div>
                                    <div class="fs_affiliates_cell_one">
                                        <?php
                                        if ( $custom_tab ) {
                                            ?>
                                            <p>
                                                <label><?php _e( 'Custom Tab Tile' , FS_AFFILIATES_LOCALE ) ; ?>:&nbsp;</label>
                                                <input type="text" required="required" name="<?php echo $setting_key . '[' . $key . ' ][tile]' ; ?>" value="<?php echo $tab[ 'tile' ] ; ?>"/>
                                            </p>
                                            <div class="fs_affiliates_custom_drop_down">
                                                <label class="fs_affiliates_icon"><?php _e( 'Custom Tab Icon' , FS_AFFILIATES_LOCALE ) ; ?>:&nbsp;</label>
                                                <input type="hidden" class="fs_affiliates_selected_icon_code" name="<?php echo $setting_key . '[' . $key . ' ][code]' ; ?>" value="<?php echo $tab[ 'code' ] ?>"/>
                                                <div class="fs_affiliates_selected_icon"><i class="fa <?php echo $tab[ 'code' ] ?>"></i></div>
                                                <div class="fs_affiliates_popup_icons" style="display:none;">
                                                    <ul>
                                                        <?php foreach ( $font_awesome as $base_class => $code ): ?>
                                                            <li class="fs_affiliates_popup_icon" data-class="<?php echo $base_class ; ?>"><i class="fa <?php echo $base_class ; ?>"></i></li>
                                                        <?php endforeach ; ?>
                                                    </ul>
                                                </div>
                                            </div>
                                            <p style="display:block;">
                                                <label><?php _e('Custom Tab Content', FS_AFFILIATES_LOCALE); ?>:&nbsp;</label>
                                                <?php wp_editor(stripslashes($tab['content']), $setting_key . '-' . $key, array('textarea_name' => $setting_key . '[' . $key . ' ][content]', 'media_buttons' => false)); ?>
                                            </p>
                                        <?php } ?>
                                        <p>
                                            <label><?php _e( 'Hide Tab In Frontend Dashboard' , FS_AFFILIATES_LOCALE ) ; ?>:&nbsp;</label>
                                            <label class="switch">
                                                <input type="checkbox" class="menu_show_or_hide" name="<?php echo $setting_key . '[' . $key . ' ][hide]' ; ?>" value="yes" <?php checked( $tab[ 'hide' ] , 'yes' ) ; ?>/>
                                                <span class="slider round"></span>
                                            </label>
                                        </p>
                                          <?php if( isset ( $tab["submenu"] ) )  { ?>
                                          <p>
                                            <label><?php _e( 'Select submenu to hide' , FS_AFFILIATES_LOCALE ) ; ?>:&nbsp;</label>
                                            <span class="submenu_selector">
                                            <select name = "<?php echo $setting_key . '[' . $key . ' ][selected_submenu][]' ?>" class = "fs_affiliates_select2" multiple >
                                              <?php
                                                foreach( $tab["submenu"] as $submenu_key => $submenu_value ) {
                                                  $selected = '';

                                                  if(in_array( $submenu_key ,$tab['selected_submenu'] ) )
                                                    $selected = 'selected="selected"';
                                              ?>
                                                <option value="<?php echo $submenu_key ?>" <?php echo $selected; ?> > <?php echo $submenu_value ?> </option>

                                              <?php } ?>

                                            </select>
                                          </span>
                                        </p>
                                        <?php
                                        }
                                        if ( $custom_tab ) {
                                            ?>
                                            <input type="button" class="fs_affiliates_remove_dashboard_tab_rule" value="<?php _e( 'Remove' , FS_AFFILIATES_LOCALE ) ; ?>"/>
                                        <?php } ?>
                                    </div>
                                </td>
                            </tr><?php
                            $key++ ;
                        }
                    }
                    ?>
                </tbody>
            </table>
            <?php
        }

        /*
         * Save
         */

        public function after_save() {
            $id = $this->get_field_key( 'rules' ) ;
            if ( !isset( $_POST[ $id ] ) )
                return ;

            $rules = $_POST[ $id ] ;


            if ( !fs_affiliates_check_is_array( $rules ) )
                return ;

            $saving_rules = array () ;
            foreach ( $rules as $rule ) {
                $rule_key                  = (isset( $rule[ "key" ] ) ) ? $rule[ "key" ] : sanitize_key( $rule[ "tile" ] ) ;
                $rule[ 'key' ]             = $rule_key ;
                $rule[ 'hide' ]            = isset( $rule[ 'hide' ] ) ? 'yes' : 'no' ;
                $rule[ 'selected_submenu' ]= isset( $rule[ 'selected_submenu' ] ) ? $rule[ 'selected_submenu' ] : array() ;
                $saving_rules[ $rule_key ] = $rule ;
            }

            $this->rules = $saving_rules ;

            $this->update_option( 'rules' , $saving_rules ) ;
        }

    }

}

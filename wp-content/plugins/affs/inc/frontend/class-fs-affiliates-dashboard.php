<?php
/**
 * Dashboard
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}
if ( ! class_exists( 'FS_Affiliates_Dashboard' ) ) {

    /**
     *  Class.
     */
    class FS_Affiliates_Dashboard {
        /*
         * output the dashboard
         */

        public static function output() {
            $user_id = get_current_user_id() ;

            $affilate_id = fs_get_affiliate_id_for_user( $user_id ) ;
            $args        = array( 'post_type' => 'fs-creatives' , 'numberposts' => -1 , 'post_status' => 'fs_active' , 'fields' => 'ids' ) ;
            $creatives   = get_posts( $args ) ;

            $menus = apply_filters( 'fs_affiliates_frontend_dashboard_menu' , array(
                'overview'        => array(
                    'label' => get_option( 'fs_affiliates_dashboard_customization_overview_label' ) ,
                    'code'  => 'fa-search' ,
                ) ,
                'affiliate_tools' => array(
                    'label' => get_option( 'fs_affiliates_dashboard_customization_tools_label' ) ,
                    'code'  => 'fa-cog' ,
                ) ,
                'referrals'       => array(
                    'label' => get_option( 'fs_affiliates_dashboard_customization_referrals_label' ) ,
                    'code'  => 'fa-link' ,
                ) ,
                'visits'          => array(
                    'label' => get_option( 'fs_affiliates_dashboard_customization_visits_label' ) ,
                    'code'  => 'fa-eye' ,
                ) ,
                'payouts'         => array(
                    'label' => get_option( 'fs_affiliates_dashboard_customization_payouts_label' ) ,
                    'code'  => 'fa-money' ,
                ) ,
                'profile'         => array(
                    'label' => get_option( 'fs_affiliates_dashboard_customization_profile_label' ) ,
                    'code'  => 'fa-user' ,
                ) ,
                'logout'          => array(
                    'label' => get_option( 'fs_affiliates_dashboard_customization_logout_label' ) ,
                    'code'  => 'fa fa-sign-out'
                ) ,
                    ) , $user_id , $affilate_id ) ;

            if ( ! fs_affiliates_check_is_array( $menus ) )
                return ;

            add_filter( 'fs_affiliates_frontend_dashboard_affiliate_tools_submenus' , array( 'FS_Affiliates_Dashboard' , 'add_affiliate_tools_submenus' ) , 10 , 3 ) ;
            add_filter( 'fs_affiliates_frontend_dashboard_profile_submenus' , array( 'FS_Affiliates_Dashboard' , 'add_profile_submenus' ) , 10 , 3 ) ;

            $menus       = fs_affiliates_dashboard_menu( $menus ) ;
            $current_tab = isset( $_REQUEST[ 'fs_section' ] ) ? $_REQUEST[ 'fs_section' ] : key( $menus ) ;

            if ( ! isset( $_REQUEST[ 'fs_nonce' ] ) || ! wp_verify_nonce( $_REQUEST[ 'fs_nonce' ] , 'affiliates-' . $user_id ) ) {
                $current_tab = key( $menus ) ;
            }

            $query_nonce   = wp_create_nonce( 'affiliates-' . $user_id ) ;
            $get_permalink = FS_AFFILIATES_PROTOCOL . $_SERVER[ 'HTTP_HOST' ] . $_SERVER[ 'REQUEST_URI' ] ;
            ?>
            <div class="fs_affiliates_frontend_dashboard">
                <div class="fs_affiliates_menu">
                    <ul class="fs_affiliates_menu_ul">
                        <?php
                        foreach ( $menus as $menu_key => $menu ) {
                            $eachurl      = fs_affiliates_dashboard_menu_link( $get_permalink , $menu_key , $query_nonce ) ;
                            $class        = ($current_tab == $menu_key ) ? "current" : '' ;
                            $filter_array = array() ;
                            $select       = '' ;
                            $filter_array = apply_filters( 'fs_affiliates_frontend_dashboard_' . $menu_key . '_submenus' , $filter_array , $user_id , $affilate_id ) ;

                            if ( strpos( $eachurl , $menu_key ) ) {
                                $class  = (array_key_exists( $current_tab , $filter_array ) ) ? "current" : $class ;
                                $select = "<ul class='submenu' style='display:none'>" ;
                                if ( fs_affiliates_check_is_array( $filter_array ) ) {
                                    foreach ( $filter_array as $tools_menu_key => $tools_menu_label ) {
                                        $select .= "<li><a class='" . $class . "' href='" . fs_affiliates_dashboard_menu_link( $eachurl , $tools_menu_key , $query_nonce ) . "'>" . $tools_menu_label . "</a></li>" ;
                                    }
                                    $eachurl = '#' ;
                                }
                                $select .= "</ul>" ;
                            }

                            echo "<li class='fs_affiliates_dashboard_icon'><a class='" . $class . "' href='" . $eachurl . "'><i class='fa " . $menu[ 'code' ] . "'></i>" . $menu[ 'label' ] . "</a>" . $select . "</li>" ;
                        }
                        ?>
                    </ul>
                </div>
                <div class="fs_affiliates_menu_content">
                    <?php
                    do_action( 'fs_affiliates_before_dashboard_content' , $current_tab , $user_id , $affilate_id ) ;
                    self::display_tab_content( $current_tab , $user_id , $affilate_id ) ;
                    ?>
                </div>
            </div>
            <?php
        }

        /*
         * Affiliate Tools Submenu
         */

        public static function add_affiliate_tools_submenus( $menus , $user_id , $affiliate_id ) {

            $submenu = array(
                'campaigns'      => get_option( 'fs_affiliates_dashboard_customization_campaigns_label' ) ,
                'affiliate_link' => get_option( 'fs_affiliates_dashboard_customization_links_label' ) ,
                'creatives'      => get_option( 'fs_affiliates_dashboard_customization_creatives_label' ) ,
                    ) ;

            if ( get_option( 'fs_affiliates_campaign_field_toggle' , '1' ) == '2' ) {
                unset( $submenu[ 'campaigns' ] ) ;
            }

            foreach ( $submenu as $key => $value ) {
                $menus [ $key ] = $value ;
            }

            return $menus ;
        }

        /*
         * Profile Submenu
         */

        public static function add_profile_submenus( $menus , $user_id , $affiliate_id ) {
            $submenu = array( 'basic_details'      => get_option( 'fs_affiliates_dashboard_customization_basic_details_label' ) ,
                'account_management' => get_option( 'fs_affiliates_dashboard_customization_acc_mgmt_label' ) ,
                'payment_management' => get_option( 'fs_affiliates_dashboard_customization_payment_mgmt_label' ) ,
                    ) ;

            foreach ( $submenu as $key => $value ) {
                $menus [ $key ] = $value ;
            }

            return $menus ;
        }

        /**
         * Display tab content
         */
        public static function display_tab_content( $current_tab , $user_id , $affilate_id ) {

            switch ( $current_tab ) {
                case 'overview':
                case 'affiliate_tools':
                case 'campaigns':
                case 'affiliate_link':
                case 'creatives':
                case 'referrals':
                case 'visits':
                case 'payouts':
                case 'profile':
                case 'basic_details':
                case 'account_management' :
                case 'payment_management':
                case 'logout' :

                    self::$current_tab( $user_id , $affilate_id , $current_tab ) ;
                    break ;
                default:
                    do_action( 'fs_affiliates_dashboard_content' , $current_tab , $user_id , $affilate_id ) ;
                    do_action( 'fs_affiliates_dashboard_content_' . $current_tab , $user_id , $affilate_id ) ;
                    break ;
            }
        }

        /*
         * Logout Functionality
         */

        public static function logout( $user_id , $affiliate_id , $current_tab = '' ) {

            wp_logout() ; // logout the user

            wp_redirect( get_permalink( fs_affiliates_get_page_id( 'login' ) ) ) ;
            exit() ;
        }

        /*
         * Display Overview menu content
         */

        public static function overview( $user_id , $affiliate_id , $current_tab = '' ) {
            $affiliates_object = new FS_Affiliates_Data( $affiliate_id ) ;

            self:: affiliate_overview_table( $user_id , $affiliate_id , $affiliates_object ) ;

            if ( get_option( 'fs_affiliates_campaign_field_toggle' , '1' ) != '2' )
                self:: affiliate_campaign_table( $user_id , $affiliate_id , $affiliates_object ) ;

            self:: affiliate_commission_table( $user_id , $affiliate_id , $affiliates_object ) ;
        }

        /*
         * Display affiliate overview table
         */

        public static function affiliate_overview_table( $user_id , $affiliate_id , $affiliates_object = false ) {
            if ( ! is_object( $affiliates_object ) )
                $affiliates_object = new FS_Affiliates_Data( $affiliate_id ) ;
            ?><div class="fs_affiliates_form">
                <h2><?php _e( 'Overview' , FS_AFFILIATES_LOCALE ) ?></h2>
                <table class="fs_affiliates_overview_frontend_table fs_affiliates_frontend_table">
                    <tbody>
                        <tr>
                            <th>
                                <?php _e( 'Affiliate ID' , FS_AFFILIATES_LOCALE ) ; ?>
                            </th>
                            <td>
                                <?php echo $affiliate_id ; ?>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <?php _e( 'Affiliate User Name' , FS_AFFILIATES_LOCALE ) ; ?>
                            </th>
                            <td>
                                <?php echo $affiliates_object->user_name ; ?>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <?php _e( 'Affiliate Email' , FS_AFFILIATES_LOCALE ) ; ?>
                            </th>
                            <td>
                                <?php echo $affiliates_object->email ; ?>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <?php _e( 'Referral' , FS_AFFILIATES_LOCALE ) ; ?>
                            </th>
                            <td>
                                <?php
                                echo $affiliates_object->get_referrals_count() ;
                                ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <?php
        }

        /*
         * Display affiliate campaign table
         */

        public static function affiliate_campaign_table( $user_id , $affiliate_id , $affiliates_object = false ) {

            if ( ! is_object( $affiliates_object ) )
                $affiliates_object = new FS_Affiliates_Data( $affiliate_id ) ;

            $campaigns = ( array ) $affiliates_object->campaign ;
            $campaigns = array_filter( $campaigns ) ;
            ?><div class="fs_affiliates_form">
                <h2><?php _e( 'Campaigns' , FS_AFFILIATES_LOCALE ) ?></h2>
                <table class="fs_affiliates_campaigns_frontend_table fs_affiliates_frontend_table">
                    <tbody>
                        <tr>
                            <th><?php _e( 'Campaigns' , FS_AFFILIATES_LOCALE ) ?></th>
                            <th><?php _e( 'Visits' , FS_AFFILIATES_LOCALE ) ?></th>
                            <th><?php _e( 'Converted' , FS_AFFILIATES_LOCALE ) ?></th>
                            <th><?php _e( 'Conversion Rate' , FS_AFFILIATES_LOCALE ) ?></th>
                        </tr>
                        <?php
                        if ( fs_affiliates_check_is_array( $campaigns ) ) {
                            foreach ( $campaigns as $campaign_name ) {
                                ?>
                                <tr>
                                    <td data-title="<?php esc_html_e( 'Campaigns' , FS_AFFILIATES_LOCALE ) ?>" ><?php echo $campaign_name ?></td>
                                    <td data-title="<?php esc_html_e( 'Visits' , FS_AFFILIATES_LOCALE ) ?>" ><?php
                                        $args1            = array( 'post_type'   => 'fs-visits' , 'fields'      => 'ids' , 'post_status' => array( 'fs_notconverted' , 'fs_converted' ) , 'numberposts' => -1 , 'author'      => $affiliate_id , 'meta_query'  => array(
                                                array(
                                                    'key'     => 'campaign' ,
                                                    'value'   => $campaign_name , //array
                                                    'compare' => '=' ,
                                                )
                                            ) ) ;
                                        $all_visits       = get_posts( $args1 ) ;
                                        $all_count        = count( $all_visits ) ;
                                        echo $all_count ;
                                        ?></td>
                                    <td data-title="<?php esc_html_e( 'Converted' , FS_AFFILIATES_LOCALE ) ?>" ><?php
                                        $args             = array( 'post_type'   => 'fs-visits' , 'fields'      => 'ids' , 'post_status' => array( 'fs_converted' ) , 'numberposts' => -1 , 'author'      => $affiliate_id , 'meta_query'  => array(
                                                array(
                                                    'key'     => 'campaign' ,
                                                    'value'   => $campaign_name , //array
                                                    'compare' => '=' ,
                                                )
                                            ) ) ;
                                        $converted_visits = get_posts( $args ) ;
                                        $converted_count  = count( $converted_visits ) ;
                                        echo $converted_count ;
                                        ?></td>
                                    <td data-title="<?php esc_html_e( 'Conversion Rate' , FS_AFFILIATES_LOCALE ) ?>" ><?php echo ( $all_count > 0 ) ? (($converted_count / $all_count) * 100) . ' %' : '-' ; ?></td>
                                </tr>
                                <?php
                            }
                        }
                        ?></tbody></table></div><?php
        }

        /*
         * Display affiliate commission table
         */

        public static function affiliate_commission_table( $user_id , $affiliate_id , $affiliates_object = false , $atts = array() ) {
            if ( ! is_object( $affiliates_object ) )
                $affiliates_object = new FS_Affiliates_Data( $affiliate_id ) ;

            $hide = wp_parse_args( $atts , array( 'hide' => '' ) ) ;
            $hide = explode( ',' , $hide[ 'hide' ] ) ;
            ?>
            <div class="fs_affiliates_form">
                <h2><?php _e( 'Commission' , FS_AFFILIATES_LOCALE ) ?></h2>
                <table class="fs_affiliates_commission_frontend_table fs_affiliates_frontend_table">
                    <tbody>
                        <?php if ( ! in_array( 'paid' , $hide ) ): ?>
                            <tr>
                                <th>
                                    <?php _e( 'Paid Earnings' , FS_AFFILIATES_LOCALE ) ; ?>
                                </th>
                                <td>
                                    <?php
                                    echo $affiliates_object->get_paid_commission() ;
                                    ?>
                                </td>
                            </tr>
                            <?php
                        endif ;
                        if ( ! in_array( 'unpaid' , $hide ) ):
                            ?>
                            <tr>
                                <th>
                                    <?php _e( 'Unpaid Earnings' , FS_AFFILIATES_LOCALE ) ; ?>
                                </th>
                                <td>
                                    <?php
                                    echo $affiliates_object->get_unpaid_commission() ;
                                    ?>
                                </td>
                            </tr>
                            <?php
                        endif ;
                        if ( ! in_array( 'overall' , $hide ) ):
                            ?>
                            <tr>
                                <th>
                                    <?php _e( 'Overall Earnings' , FS_AFFILIATES_LOCALE ) ; ?>
                                </th>
                                <td>
                                    <?php
                                    echo $affiliates_object->get_overall_commission() ;
                                    ?>
                                </td>
                            </tr>
                        <?php endif ; ?>
                    </tbody>
                </table>
            </div>
            <?php
        }

        /*
         * Display Affiliate Tools Menu Content
         */

        public static function affiliate_tools( $user_id , $affiliate_id , $current_tab = '' ) {
            $filter_array = array() ;
            $filter_array = apply_filters( 'fs_affiliates_frontend_dashboard_affiliate_tools_submenus' , $filter_array , $user_id , $affiliate_id ) ;
            $filter_array = key( $filter_array ) ;
            self::display_tab_content( $filter_array , $user_id , $affiliate_id ) ;
        }

        /*
         * Display Campaigns Submenu Content
         */

        public static function campaigns( $user_id , $affiliate_id , $current_tab = '' ) {

            $campaigns = ( array ) get_post_meta( $affiliate_id , 'campaign' , true ) ;

            if ( isset( $_POST[ 'add_new_campaign' ] ) && $_POST[ 'add_new_campaign' ] && isset( $_POST[ 'fs_new_campaign' ] ) ) {
                try {

                    if ( empty( $_POST[ 'fs_new_campaign' ] ) ) {
                        throw new Exception( __( 'Campaign name should not be empty' , FS_AFFILIATES_LOCALE ) ) ;
                    }

                    if ( preg_match( '/[\'^�$%&*!()}{@#~?><>,|=_+�-]/' , $_POST[ 'fs_new_campaign' ] ) ) {
                        throw new Exception( __( 'Campaign name should not contain any symbols' , FS_AFFILIATES_LOCALE ) ) ;
                    }
                    if ( is_array( $campaigns ) && in_array( $_POST[ 'fs_new_campaign' ] , $campaigns ) ) {
                        throw new Exception( __( 'Campaign already exists' , FS_AFFILIATES_LOCALE ) ) ;
                    }

                    $time               = time() ;
                    $campaigns[ $time ] = $_POST[ 'fs_new_campaign' ] ;
                    ?><div>
                        <span class="fs_affiliates_msg_success_post"><i class="fa fa-check"></i><?php _e( 'Campaign Created sucessfully' , FS_AFFILIATES_LOCALE ) ; ?></span>
                    </div><?php
                    update_post_meta( $affiliate_id , 'campaign' , $campaigns ) ;
                } catch ( Exception $e ) {
                    ?><div>
                        <span class="fs_affiliates_msg_fails_post"><i class="fa fa-exclamation-triangle"></i><?php echo $e->getMessage() ; ?></span>
                    </div><?php
                }
            }
            if ( isset( $_POST[ 'remove_campaign' ] ) && $_POST[ 'remove_campaign' ] && isset( $_POST[ 'fs_remove_campaign' ] ) && $_POST[ 'fs_remove_campaign' ] ) {
                unset( $campaigns[ $_POST[ 'fs_remove_campaign' ] ] ) ;
                update_post_meta( $affiliate_id , 'campaign' , $campaigns ) ;
            }

            $campaigns = array_filter( $campaigns ) ;
            ?>
            <form method="post" class='fs_affiliates_form'>
                <h2><?php _e( 'Create New Campaign' , FS_AFFILIATES_LOCALE ) ?></h2>
                <p><label><?php _e( 'Campaign Name' , FS_AFFILIATES_LOCALE ) ?></label> <input type="text" name="fs_new_campaign" value=""> </p>
                <input type="submit" name="add_new_campaign" class="fs_affiliates_form_save" value="<?php _e( 'Create Campaign' , FS_AFFILIATES_LOCALE ) ?>" >
            </form>
            <?php
            if ( fs_affiliates_check_is_array( $campaigns ) ) {
                ?><div class="fs_affiliates_form">
                    <h2><?php _e( 'Lists of Campaigns' , FS_AFFILIATES_LOCALE ) ?></h2>
                    <table class="fs_affiliate_campaigns_list_table fs_affiliates_frontend_table">
                        <tbody><tr><th><?php _e( 'Campaign Name' , FS_AFFILIATES_LOCALE ) ?></th>
                                <th><?php _e( 'Created Date' , FS_AFFILIATES_LOCALE ) ?></th>
                                <th><?php _e( 'Referrals' , FS_AFFILIATES_LOCALE ) ?></th>
                                <th><?php _e( 'Conversion Rate' , FS_AFFILIATES_LOCALE ) ?></th>
                                <th><?php _e( 'Delete' , FS_AFFILIATES_LOCALE ) ?></th>
                            </tr><?php
                            foreach ( $campaigns as $key => $campaign ) {
                                ?><tr class="<?php echo $key ?>" >
                                    <td data-title="<?php esc_html_e( 'Campaign Name' , FS_AFFILIATES_LOCALE ) ?>" class="campaign_name" ><?php echo $campaign ?></td>
                                    <td data-title="<?php esc_html_e( 'Created Date' , FS_AFFILIATES_LOCALE ) ?>" class="campaign_date" ><?php echo date( 'Y/m/d H:i:s' , $key ) ?></td>
                                    <td data-title="<?php esc_html_e( 'Referrals' , FS_AFFILIATES_LOCALE ) ?>" >
                                        <?php
                                        $args      = array( 'post_type'   => 'fs-referrals' , 'fields'      => 'ids' , 'post_status' => array( 'fs_unpaid' , 'fs_paid' ) , 'numberposts' => -1 , 'author'      => $affiliate_id , 'meta_query'  => array(
                                                array(
                                                    'key'     => 'campaign' ,
                                                    'value'   => $campaign , //array
                                                    'compare' => '=' ,
                                                )
                                            ) ) ;
                                        $referrals = get_posts( $args ) ;
                                        $amount    = 0 ;
                                        foreach ( $referrals as $referral_id ) {
                                            $amount += ( float ) get_post_meta( $referral_id , 'amount' , true ) ;
                                        }
                                        echo $amount ;
                                        ?>
                                    </td>
                                    <td data-title="<?php esc_html_e( 'Conversion Rate' , FS_AFFILIATES_LOCALE ) ?>" ><?php
                                        $args1            = array( 'post_type'   => 'fs-visits' , 'fields'      => 'ids' , 'post_status' => array( 'fs_notconverted' , 'fs_converted' ) , 'numberposts' => -1 , 'author'      => $affiliate_id , 'meta_query'  => array(
                                                array(
                                                    'key'     => 'campaign' ,
                                                    'value'   => $campaign , //array
                                                    'compare' => '=' ,
                                                )
                                            ) ) ;
                                        $all_visits       = get_posts( $args1 ) ;
                                        $all_count        = count( $all_visits ) ;
                                        $args             = array( 'post_type'   => 'fs-visits' , 'fields'      => 'ids' , 'post_status' => array( 'fs_converted' ) , 'numberposts' => -1 , 'author'      => $affiliate_id , 'meta_query'  => array(
                                                array(
                                                    'key'     => 'campaign' ,
                                                    'value'   => $campaign , //array
                                                    'compare' => '=' ,
                                                )
                                            ) ) ;
                                        $converted_visits = get_posts( $args ) ;
                                        $converted_count  = count( $converted_visits ) ;
                                        if ( $all_count > 0 ) {
                                            echo (($converted_count / $all_count) * 100) . ' %' ;
                                        }
                                        ?></td>
                                    <td data-title="<?php esc_html_e( 'Delete' , FS_AFFILIATES_LOCALE ) ?>" class="campaign_delete"><?php ?><form method="post"><input type="hidden" name="fs_remove_campaign" value="<?php echo $key ; ?>"><input type="submit" name="remove_campaign" class="remove_campaigns_list_btn" value="<?php _e( 'Remove' , FS_AFFILIATES_LOCALE ) ?>" ></form></td>
                                </tr><?php
                            }
                            ?></tbody></table></div><?php
            }
        }

        /*
         * Display Affiliate Link Submenu Content
         */

        public static function affiliate_link( $user_id , $AffiliateID , $current_tab = '' ) {
            $campaigns           = array_filter( ( array ) get_post_meta( $AffiliateID , 'campaign' , true ) ) ;
            ?>
            <div class="fs_affiliates_link_generator">
                <?php
                $referral_link_label = ( '2' == get_option( 'fs_affiliates_referral_link_type' , '1' ) ) ? get_option( 'fs_affiliates_static_referral_link_label' , esc_html__( 'Affiliate Link' , FS_AFFILIATES_LOCALE ) ) : get_option( 'fs_affiliates_default_referral_link_label' , esc_html__( 'Affiliate Link Generator' , FS_AFFILIATES_LOCALE ) ) ;
                ?>
                <h2><?php echo esc_attr( $referral_link_label ) ; ?></h2>
                <?php if ( '2' == get_option( 'fs_affiliates_referral_link_type' , '1' ) ) { ?> 

                    <?php do_action( 'fs_affiliates_before_link_generator' , $AffiliateID , $user_id ) ; ?>
                    <div class="fs_display_generated_link">
                        <?php echo '<p>' . sprintf( '<b>' . esc_html__( "Copy the Link: " , FS_AFFILIATES_LOCALE ) . '</b>' . "%s" , get_option( 'fs_affiliates_static_referral_url' ) ) . ' ' . fs_display_copy_affiliate_link_image( get_option( 'fs_affiliates_static_referral_url' ) ) . '</p>' ; ?>
                    </div>
                    <div class="fs_static_affiliate_content">
                        <table class="fs_affiliates_generate_link_table show_if_affiliate_link_generator fs_affiliates_frontend_table">
                            <?php if ( '2' != get_option( 'fs_affiliates_campaign_field_toggle' , '1' ) && fs_affiliates_check_is_array( $campaigns ) ) { ?>
                                <tr>
                                    <td>
                                        <label><b><?php esc_html_e( 'Select Campaign' , FS_AFFILIATES_LOCALE ) ?></b></label>
                                        <select name="campaign" class="campaign_for_affiliate">
                                            <?php
                                            echo '<option value="">' . esc_html__( 'Select' , FS_AFFILIATES_LOCALE ) . '</option>' ;

                                            foreach ( $campaigns as $campaign ) {
                                                echo '<option value="' . $campaign . '">' . $campaign . '</option>' ;
                                            }
                                            ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <button id="fs_affiliates_generate_campaign_affiliate_link" class="fs_affiliates_generate_campaign_affiliate_link"><?php esc_html_e( 'Generate Link with Campaign' , FS_AFFILIATES_LOCALE ) ; ?></button>
                                    </td>
                                </tr>
                            <?php } ?>
                        </table>
                    </div>
                    <div class="fs_display_generated_campaign_link"></div>
                <?php } else { ?>

                    <div class="fs_default_affiliate_content">
                        <?php
                        do_action( 'fs_affiliates_before_link_generator' , $AffiliateID , $user_id ) ;
                        if ( apply_filters( 'fs_affiliates_disp_link_generator' , $AffiliateID ) ) {
                            ?>
                            <table class="fs_affiliates_generate_link_table show_if_affiliate_link_generator fs_affiliates_frontend_table">
                                <?php if ( get_option( 'fs_affiliates_campaign_field_toggle' , '1' ) != '2' && fs_affiliates_check_is_array( $campaigns ) ) { ?>
                                    <tr>
                                        <td>
                                            <label><b><?php _e( 'Select Campaign' , FS_AFFILIATES_LOCALE ) ?></b></label>
                                            <select name="campaign" class="campaign_for_affiliate">
                                                <?php
                                                echo '<option value="">' . __( 'Select' , FS_AFFILIATES_LOCALE ) . '</option>' ;

                                                foreach ( $campaigns as $campaign ) {
                                                    echo '<option value="' . $campaign . '">' . $campaign . '</option>' ;
                                                }
                                                ?>
                                            </select>
                                        </td>
                                    </tr>
                                <?php } ?>
                                <tr>
                                    <td>
                                        <?php $referral_url = get_option( 'fs_affiliates_default_referral_url' , site_url() ) ; ?>
                                        <input type="text" value="<?php echo $referral_url ; ?>" id="fs_url_to_generate_affiliate_link" class="fs_url_to_generate_affiliate_link" />
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <button id="fs_affiliates_generate_affiliate_link" class="fs_affiliates_generate_affiliate_link"><?php _e( 'Generate Link' , FS_AFFILIATES_LOCALE ) ; ?></button>
                                    </td>
                                </tr>
                            </table>

                            <?php do_action( 'fs_affiliates_after_link_generator' , $AffiliateID , $user_id ) ; ?>

                            <div class="fs_display_generated_link"></div>
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>
            <?php
        }

        /*
         * Display Creatives Submenu Content
         */

        public static function creatives( $user_id , $affiliate_id , $current_tab = '' ) {
            $args1             = array( 'post_type' => 'fs-creatives' , 'numberposts' => -1 , 'post_status' => 'fs_active' , 'fields' => 'ids' ) ;
            $post1             = get_posts( $args1 ) ;
            $count             = count( $post1 ) ;
            $current_page      = isset( $_REQUEST[ 'page_no' ] ) && $_REQUEST[ 'page_no' ] ? ( int ) $_REQUEST[ 'page_no' ] : 1 ;
            $per_page          = get_option( 'fs_affiliates_creatives_per_page_count' , 5 ) ;
            $offset            = ($current_page - 1) * $per_page ;
            $page_count        = ceil( $count / $per_page ) ;
            ?><div class="fs_affiliates_form">
                <h2><?php _e( 'Creatives' , FS_AFFILIATES_LOCALE ) ?></h2>
                <table class="fs_affiliates_creatives_frontend_table fs_affiliates_frontend_table">
                    <tbody>
                        <tr>
                            <th class="fs_affiliates_sno fs_affiliates_creatives_frontend_sno"><?php _e( 'S.No' , FS_AFFILIATES_LOCALE ) ?></th>
                            <th><?php esc_html_e( 'Image' , FS_AFFILIATES_LOCALE ) ?></th>
                            <th><?php esc_html_e( 'URL' , FS_AFFILIATES_LOCALE ) ?></th>
                            <th><?php esc_html_e( 'Copy Link' , FS_AFFILIATES_LOCALE ) ; ?></th>
                        </tr>
                        <?php
                        $args              = array( 'post_type' => 'fs-creatives' , 'offset' => $offset , 'numberposts' => $per_page , 'post_status' => 'fs_active' , 'fields' => 'ids' ) ;
                        $post              = get_posts( $args ) ;
                        $affiliate_name_id = get_option( 'fs_affiliates_referral_id_format' ) == 'name' ? get_the_title( $affiliate_id ) : $affiliate_id ;
                        $sno               = $offset + 1 ;
                        foreach ( $post as $creative_id ) {

                            if ( ! self::is_valid_creative( $creative_id , $affiliate_id ) )
                                continue ;

                            $image = get_post_meta( $creative_id , 'image' , true ) ;
                            $alt   = get_post_meta( $creative_id , 'alternative_text' , true ) ;
                            $name  = get_the_title( $creative_id ) ;
                            $myurl = get_post_meta( $creative_id , 'url' , true ) ;

                            $query_arg = esc_url_raw( add_query_arg( array( 'ref' => $affiliate_name_id ) , $myurl ) ) ;
                            $url       = '<a href="' . $query_arg . '" title="' . $alt . '"><img src="' . $image . '" alt="' . $alt . '"/></a>' ;
                            ?>
                            <tr>
                                <td data-title="<?php esc_html_e( 'S.No' , FS_AFFILIATES_LOCALE ) ?>" class="fs_affiliates_sno fs_affiliates_creatives_frontend_sno"><?php echo $sno ; ?></td>
                                <td data-title="<?php esc_html_e( 'Image' , FS_AFFILIATES_LOCALE ) ?>" ><image src="<?php echo $image ; ?>" alt="<?php echo $alt ; ?>" title="<?php echo $name ; ?>"></td>
                                <td data-title="<?php esc_html_e( 'URL' , FS_AFFILIATES_LOCALE ) ?>" ><?php echo htmlentities( $url ) ; ?></td>
                                <td data-title="<?php esc_html_e( 'Copy Link' , FS_AFFILIATES_LOCALE ) ?>" class="fs_copy_creatives_link"><?php echo fs_display_copy_affiliate_link_image( htmlentities( $url ) ) ; ?></td>
                            </tr><?php
                            $sno ++ ;
                        }
                        ?></tbody>
                    <tfoot>
                        <tr style="clear:both;">
                            <td colspan="6" class="footable-visible">
                                <div class="pagination pagination-centered">
                                    <?php
                                    if ( $page_count > 1 ) {
                                        self::fs_affiliates_set_pagination( $current_page , $page_count ) ;
                                    }
                                    ?>
                                </div>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div><?php
        }

        /*
         * Check is valid creative to display affiliate
         */

        public static function is_valid_creative( $creative_id , $affiliate_id ) {
            $affiliate_selection = get_post_meta( $creative_id , 'affiliate_selection' , true ) ;

            switch ( $affiliate_selection ) {
                case '2':
                    $include_affiliates = get_post_meta( $creative_id , 'include_affiliates' , true ) ;

                    if ( ! in_array( $affiliate_id , $include_affiliates ) )
                        return false ;

                    break ;
                case '3':
                    $exclude_affiliates = get_post_meta( $creative_id , 'exclude_affiliates' , true ) ;

                    if ( in_array( $affiliate_id , $exclude_affiliates ) )
                        return false ;

                    break ;
            }

            return true ;
        }

        /*
         * Display Referrals Submenu Content
         */

        public static function referrals( $user_id , $affiliate_id , $current_tab = '' ) {
            if ( isset( $_REQUEST[ 'order_id' ] ) && $_REQUEST[ 'order_id' ] ) {
                self::referral_order_details( $user_id , $_REQUEST[ 'order_id' ] ) ;
            } else {
                self::referral_table( $user_id , $affiliate_id ) ;
            }
        }

        /*
         * Referral Order Details
         */

        public static function referral_order_details( $user_id , $order_id ) {

            do_action( 'referral_order_details_table' , $user_id , $order_id ) ;
        }

        /*
         * Referrals Table
         */

        public static function referral_table( $user_id , $affiliate_id ) {
            $user_id = empty( $user_id ) ? get_current_user_id() : $user_id ;

            if ( empty( $user_id ) ) {
                return '' ;
            }

            $affiliate_id = empty( $affiliate_id ) ? fs_get_affiliate_id_for_user( $user_id ) : $affiliate_id ;

            if ( empty( $affiliate_id ) ) {
                return '' ;
            }

            $args1 = array( 'post_type'   => 'fs-referrals' ,
                'numberposts' => -1 ,
                'fields'      => 'ids' ,
                'author'      => $affiliate_id ,
                'post_status' => array( 'fs_paid' , 'fs_unpaid' , 'fs_rejected' ) ,
                    ) ;

            $post1 = get_posts( $args1 ) ;
            $count = fs_affiliates_check_is_array( $post1 ) ? count( $post1 ) : 0 ;

            $current_page  = isset( $_REQUEST[ 'page_no' ] ) && $_REQUEST[ 'page_no' ] ? ( int ) $_REQUEST[ 'page_no' ] : 1 ;
            $per_page      = get_option( 'fs_affiliates_referrals_per_page_count' , 5 ) ;
            $offset        = ($current_page - 1) * $per_page ;
            $page_count    = ceil( $count / $per_page ) ;
            $unpaid_amount = fs_affiliates_get_referrals_commission( $affiliate_id , 'unpaid' ) ;

            $args = array(
                'affiliate_id'  => $affiliate_id ,
                'unpaid_amount' => $unpaid_amount ,
                'offset'        => $offset ,
                'per_page'      => $per_page ,
                'current_page'  => $current_page ,
                'page_count'    => $page_count
                    ) ;

            return fs_affiliates_get_template( 'dashboard/referrals.php' , $args ) ;
        }

        /*
         * Display Visits
         */

        public static function visits( $user_id , $affiliate_id , $current_tab = '' ) {
            $user_id = empty( $user_id ) ? get_current_user_id() : $user_id ;

            if ( empty( $user_id ) ) {
                return '' ;
            }

            $affiliate_id = empty( $affiliate_id ) ? fs_get_affiliate_id_for_user( $user_id ) : $affiliate_id ;

            if ( empty( $affiliate_id ) ) {
                return '' ;
            }

            $args1        = array( 'post_type'   => 'fs-visits' ,
                'author'      => $affiliate_id ,
                'numberposts' => -1 ,
                'fields'      => 'ids' ,
                'post_status' => array( 'fs_notconverted' , 'fs_converted' )
                    ) ;
            $post1        = get_posts( $args1 ) ;
            $count        = count( $post1 ) ;
            $current_page = isset( $_REQUEST[ 'page_no' ] ) && $_REQUEST[ 'page_no' ] ? ( int ) $_REQUEST[ 'page_no' ] : 1 ;
            $per_page     = get_option( 'fs_affiliates_visits_per_page_count' , 5 ) ;
            $offset       = ($current_page - 1) * $per_page ;
            $page_count   = ceil( $count / $per_page ) ;

            $args = array(
                'affiliate_id' => $affiliate_id ,
                'offset'       => $offset ,
                'per_page'     => $per_page ,
                'current_page' => $current_page ,
                'page_count'   => $page_count
                    ) ;

            return fs_affiliates_get_template( 'dashboard/visits.php' , $args ) ;
        }

        /*
         * Display Payouts
         */

        public static function payouts( $user_id , $affiliate_id , $current_tab = '' ) {
            $user_id = empty( $user_id ) ? get_current_user_id() : $user_id ;

            if ( empty( $user_id ) ) {
                return '' ;
            }

            $affiliate_id = empty( $affiliate_id ) ? fs_get_affiliate_id_for_user( $user_id ) : $affiliate_id ;

            if ( empty( $affiliate_id ) ) {
                return '' ;
            }

            $args1        = array( 'post_type'   => 'fs-payouts' ,
                'author'      => $affiliate_id ,
                'numberposts' => -1 ,
                'fields'      => 'ids' ,
                'post_status' => array( 'fs_paid' )
                    ) ;
            $post1        = get_posts( $args1 ) ;
            $count        = count( $post1 ) ;
            $current_page = isset( $_REQUEST[ 'page_no' ] ) && $_REQUEST[ 'page_no' ] ? ( int ) $_REQUEST[ 'page_no' ] : 1 ;
            $per_page     = get_option( 'fs_affiliates_payments_per_page_count' , 5 ) ;
            $offset       = ($current_page - 1) * $per_page ;
            $page_count   = ceil( $count / $per_page ) ;

            $args = array(
                'affiliate_id'     => $affiliate_id ,
                'offset'           => $offset ,
                'per_page'         => $per_page ,
                'current_page'     => $current_page ,
                'page_count'       => $page_count ,
                'footable_colspan' => 6
                    ) ;

            return fs_affiliates_get_template( 'dashboard/payouts.php' , $args ) ;
        }

        /*
         * Display Basic Details submenu
         */

        public static function basic_details( $user_id , $AffiliateId , $current_tab = '' ) {
            if ( ! empty( $_POST[ 'aff_update' ] ) ) {

                try {
                    $error_messages = '' ;
                    $AffiliateData  = array() ;

                    if ( ! isset( $_POST[ 'aff_firstname' ] ) || empty( $_POST[ 'aff_firstname' ] ) ) {
                        $error_messages .= __( 'First Name is required' , FS_AFFILIATES_LOCALE ) . '<br>' ;
                    }
                    if ( ! isset( $_POST[ 'aff_lastname' ] ) || empty( $_POST[ 'aff_lastname' ] ) ) {
                        $error_messages .= __( 'Last Name is required' , FS_AFFILIATES_LOCALE ) . '<br>' ;
                    }
                    if ( ! isset( $_POST[ 'aff_email' ] ) || empty( $_POST[ 'aff_email' ] ) ) {
                        $error_messages .= __( 'Email is required' , FS_AFFILIATES_LOCALE ) . '<br>' ;
                    } else if ( ! filter_var( $_POST[ 'aff_email' ] , FILTER_VALIDATE_EMAIL ) ) {
                        $error_messages .= __( 'Enter a valid email' , FS_AFFILIATES_LOCALE ) . '<br>' ;
                    }
                    if ( isset( $_POST[ 'aff_change_slug' ] ) ) {
                        if ( ! isset( $_POST[ 'aff_new_slug' ] ) ) {
                            $error_messages .= __( 'A valid affiliate slug is required' , FS_AFFILIATES_LOCALE ) . '<br>' ;
                        }
                    }
                    if ( $error_messages ) {
                        throw new Exception( $error_messages ) ;
                    } else {

                        $ModifySlug                        = isset( $_POST[ 'aff_change_slug' ] ) ? 'yes' : 'no' ;
                        $AffiliateData[ 'first_name' ]     = $_POST[ 'aff_firstname' ] ;
                        $AffiliateData[ 'last_name' ]      = $_POST[ 'aff_lastname' ] ;
                        $AffiliateData[ 'email' ]          = $_POST[ 'aff_email' ] ;
                        $AffiliateData[ 'phone_number' ]   = isset( $_POST[ 'aff_phonenumber' ] ) ? $_POST[ 'aff_phonenumber' ] : '' ;
                        $AffiliateData[ 'uploaded_files' ] = get_transient( 'fs_affiliates_file_upload_' . $AffiliateId ) ;
                        $AffiliateData[ 'modify_slug' ]    = $ModifySlug ;
                        $post_args                         = isset( $_POST[ 'aff_new_slug' ] ) ? array( 'post_name' => $_POST[ 'aff_new_slug' ] ) : array() ;

                        fs_affiliates_update_affiliate( $AffiliateId , $AffiliateData , $post_args ) ;
                    }
                    ?><div>
                        <span class="fs_affiliates_msg_success_post"><i class="fa fa-check"></i><?php _e( 'Profile updated sucessfully' , FS_AFFILIATES_LOCALE ) ; ?></span>
                    </div><?php
                    do_action( 'fs_affiliates_profile_updated' , $AffiliateId ) ;
                } catch ( Exception $e ) {
                    ?><div>
                        <span class="fs_affiliates_msg_fails_post"><i class="fa fa-exclamation-triangle"></i><?php echo $e->getMessage() ; ?></span>
                    </div><?php
                }
            }

            $AffiliateObj = new FS_Affiliates_Data( $AffiliateId ) ;

            // Basic Details of Profile
            include_once('views/dashboard/basic.php') ;
        }

        /*
         * Display Profile
         */

        public static function profile( $user_id , $affiliate_id , $current_tab = '' ) {
            $filter_array = apply_filters( 'fs_affiliates_frontend_dashboard_profile_submenus' , array() , $user_id , $affiliate_id ) ;
            $filter_array = key( $filter_array ) ;

            self::$filter_array( $user_id , $affiliate_id , $current_tab ) ;
        }

        /*
         * Profile - Account Management
         */

        public static function account_management( $user_id , $AffiliateId , $current_tab = '' ) {

            if ( ! empty( $_POST[ 'aff_set_pswd' ] ) ) {
                $error_message = '' ;
                try {
                    $user_info = get_userdata( $user_id ) ;
                    if ( ! isset( $_POST[ 'aff_old_password' ] ) || empty( $_POST[ 'aff_old_password' ] ) ) {
                        $error_message .= __( 'Old Password is required' , FS_AFFILIATES_LOCALE ) . '<br>' ;
                    } elseif ( ! wp_check_password( $_POST[ 'aff_old_password' ] , $user_info->data->user_pass , $user_id ) ) {
                        $error_message .= __( 'Old Password is incorrect' , FS_AFFILIATES_LOCALE ) . '<br>' ;
                    }
                    if ( $_POST[ 'aff_new_password' ] == '' && $_POST[ 'aff_repeat_password' ] ) {
                        $error_message .= __( 'Password should not empty' , FS_AFFILIATES_LOCALE ) . '<br>' ;
                    } elseif ( $_POST[ 'aff_new_password' ] && $_POST[ 'aff_repeat_password' ] == '' ) {
                        $error_message .= __( 'Please repeat the password' , FS_AFFILIATES_LOCALE ) . '<br>' ;
                    } elseif ( $_POST[ 'aff_new_password' ] != $_POST[ 'aff_repeat_password' ] ) {
                        $error_message .= __( "Passwords do not match" , FS_AFFILIATES_LOCALE ) . '<br>' ;
                    }
                    if ( $error_message ) {
                        throw new Exception( $error_message ) ;
                    } else {
                        wp_set_password( $_POST[ 'aff_new_password' ] , $user_id ) ;
                    }
                    ?><div>
                        <span class="fs_affiliates_msg_success_post"><i class="fa fa-check"></i><?php _e( 'Profile updated sucessfully' , FS_AFFILIATES_LOCALE ) ; ?></span>
                    </div><?php
                    do_action( 'fs_affiliates_profile_updated' , $AffiliateId ) ;
                } catch ( Exception $e ) {
                    ?><div>
                        <span class="fs_affiliates_msg_fails_post"><i class="fa fa-exclamation-triangle"></i><?php echo $e->getMessage() ; ?></span>
                    </div><?php
                }
            }

            // Account Management of Profile
            include_once('views/dashboard/account-management.php') ;
        }

        /*
         * Profile - Payment Management
         */

        public static function payment_management( $user_id , $affilate_id , $current_tab = '' ) {

            if ( isset( $_POST[ 'fs_affiliates_payment_method' ] ) && ! empty( $_POST[ 'fs_affiliates_payment_method' ] ) ) {

                $error_message = '' ;
                try {
                    $pay_method   = isset( $_POST[ 'fs_affiliates_payment_method' ] ) ? $_POST[ 'fs_affiliates_payment_method' ] : '' ;
                    $paypal_email = isset( $_POST[ 'fs_affiliates_paypal_email' ] ) ? $_POST[ 'fs_affiliates_paypal_email' ] : '' ;
                    $pay_bank     = isset( $_POST[ 'fs_affiliates_bank_details' ] ) ? $_POST[ 'fs_affiliates_bank_details' ] : '' ;

                    if ( ($pay_method == 'direct' && $pay_bank == '') || ( $pay_method == 'paypal' && $paypal_email == '' ) ) {
                        $error_message .= __( 'Please Fill Creditional Fields' , FS_AFFILIATES_LOCALE ) . '<br>' ;
                    }
                    if ( $error_message ) {
                        throw new Exception( $error_message ) ;
                    } else {
                        $payment_datas = array(
                            'fs_affiliates_current_id'     => $affilate_id ,
                            'fs_affiliates_payment_method' => $pay_method ,
                            'fs_affiliates_paypal_email'   => $paypal_email ,
                            'fs_affiliates_bank_details'   => $pay_bank ,
                                ) ;

                        update_post_meta( $affilate_id , 'fs_affiliates_user_payment_datas' , $payment_datas ) ;
                        update_post_meta( $affilate_id , 'payment_email' , $paypal_email ) ;
                    }
                    ?>
                    <div>
                        <span class="fs_affiliates_msg_success_post"><i class="fa fa-check"></i><?php _e( 'Payment Method Updated Successfully' , FS_AFFILIATES_LOCALE ) ; ?></span>
                    </div><?php } catch ( Exception $e ) {
                    ?><div>
                        <span class="fs_affiliates_msg_fails_post"><i class="fa fa-exclamation-triangle"></i><?php echo $e->getMessage() ; ?></span>
                    </div><?php
                }
            } else {
                //Getting Default Payment Gateway
                $payment_preference = get_option( 'fs_affiliates_payment_preference' , array( 'direct' => 'enable' , 'paypal' => 'enable' , 'wallet' => 'enable' ) ) ;
                $default_paymethod  = fs_affiliates_get_default_gateway( $payment_preference ) ;

                //Getting Default Payment Gateway Datas
                $payment_datas    = get_post_meta( $affilate_id , 'fs_affiliates_user_payment_datas' , true ) ;
                $saved_pay_method = isset( $payment_datas[ 'fs_affiliates_payment_method' ] ) ? $payment_datas[ 'fs_affiliates_payment_method' ] : '' ;

                $pay_method            = ( empty( $saved_pay_method ) && ! empty( $default_paymethod ) ) ? $default_paymethod : $saved_pay_method ;
                $paypal_email          = isset( $payment_datas[ 'fs_affiliates_paypal_email' ] ) ? $payment_datas[ 'fs_affiliates_paypal_email' ] : '' ;
                $pay_bank              = isset( $payment_datas[ 'fs_affiliates_bank_details' ] ) ? $payment_datas[ 'fs_affiliates_bank_details' ] : '' ;
                $payment_change_notice = get_post_meta( $affilate_id , 'fs_affiliates_payment_notice_disp_type' , true ) ;

                if ( empty( $default_paymethod ) ) {
                    delete_post_meta( $affilate_id , 'fs_affiliates_user_payment_datas' ) ;
                    delete_post_meta( $affilate_id , 'payment_email' ) ;
                    ?>
                    <div>
                        <span class="fs_affiliates_msg_fails_post fs_affiliates_payment_methods_error_msg"><i class="fa fa-exclamation-triangle"></i><?php _e( 'Admin restricted Payment Method selection' , FS_AFFILIATES_LOCALE ) ; ?></span>
                    </div><?php
                    return ;
                }

                if ( fs_affiliates_is_payment_method_enable( $payment_preference , $saved_pay_method ) && '1' == get_option( 'fs_affiliates_payment_method_selection_type' , '1' ) || ($saved_pay_method == 'direct' && $pay_bank == '') || ( $saved_pay_method == 'paypal' && $paypal_email == '' ) || (in_array( $payment_change_notice , array( 'new' , 'exist' ) ) && ! in_array( $saved_pay_method , array( 'wallet' , 'reward_points' ) ) && ($saved_pay_method == 'direct' && $pay_bank == '') || ( $saved_pay_method == 'paypal' && $paypal_email == '' )) ) {
                    ?>
                    <div>
                        <span class="fs_affiliates_msg_fails_post fs_affiliates_payment_methods_error_msg"><i class="fa fa-exclamation-triangle"></i><?php _e( 'Payment Method selection is required to get your commission. Site Admin has changed the Payment Method Configuration' , FS_AFFILIATES_LOCALE ) ; ?></span>
                    </div><?php
                }
            }

            // Payment Management of Profile
            include_once('views/dashboard/payment-management.php') ;
        }

        public static function fs_affiliates_set_pagination( $current_page , $page_count , $get_permalink = false ) {

            if ( ! $get_permalink )
                $get_permalink = FS_AFFILIATES_PROTOCOL . $_SERVER[ 'HTTP_HOST' ] . $_SERVER[ 'REQUEST_URI' ] ;

            $prev_count = (($current_page - 1) == 0) ? 1 : ($current_page - 1) ;

            echo '<span style="padding:5px;border:1px solid #ccc;border-radius:2px;"><a href="' . self::prepare_page_link( $get_permalink , '1' ) . '"><i class="fa fa-angle-double-left" ></i></a></span>' ;
            echo '<span style="padding:5px;border:1px solid #ccc;border-radius:2px;"><a href="' . self::prepare_page_link( $get_permalink , $prev_count ) . '"><i class="fa fa-angle-left" ></i></a></span>' ;

            for ( $i = 1 ; $i <= $page_count ; $i ++ ) {
                $display = false ;

                if ( $current_page <= 10 && $i <= 10 ) {
                    $page_no = $i ;
                    $display = true ;
                } else if ( $current_page > 10 ) {
                    $overall_count = $current_page - 10 + $i ;

                    if ( $overall_count <= $current_page ) {
                        $page_no = $overall_count ;
                        $display = true ;
                    }
                }

                if ( $display )
                    echo '<span style="padding:5px;border:1px solid #ccc;border-radius:2px;"><a href="' . self::prepare_page_link( $get_permalink , $page_no ) . '">' . $page_no . '</a></span>' ;
            }

            $next_count = (($current_page + 1) > ($page_count - 1)) ? ($current_page) : ($current_page + 1) ;

            echo '<span style="padding:5px;border:1px solid #ccc;border-radius:2px;"><a href="' . self::prepare_page_link( $get_permalink , $next_count ) . '"><i class="fa fa-angle-right" ></i></a></span>' ;
            echo '<span style="padding:5px;border:1px solid #ccc;border-radius:2px;"><a href="' . self::prepare_page_link( $get_permalink , $page_count ) . '"><i class="fa fa-angle-double-right" ></i></a></span>' ;
        }

        public static function prepare_page_link( $get_permalink , $page_no = 1 ) {
            $permalink   = esc_url( remove_query_arg( array( 'page_no' , 'order_id' , 'fs_nonce' , 'fs_status' ) , $get_permalink ) ) ;
            $query_nonce = wp_create_nonce( 'affiliates-' . get_current_user_id() ) ;

            return add_query_arg( array( 'page_no' => $page_no , 'fs_nonce' => $query_nonce ) , $permalink ) ;
        }

    }

}

<?php
/**
 * Leaderboard
 */
if ( !defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( !class_exists( 'FS_Affiliates_Leaderboard' ) ) {

    /**
     * Class FS_Affiliates_Leaderboard
     */
    class FS_Affiliates_Leaderboard extends FS_Affiliates_Modules {
        /*
         * Data
         */

        protected $data = array (
            'enabled'         => 'no' ,
            'limit'           => '50' ,
            'predefined_type' => '1' ,
            'display_method'  => '1' ,
            'menu_label'      => ''
                ) ;

        /**
         * Class Constructor
         */
        public function __construct() {
            $this->id    = 'leaderboard' ;
            $this->title = __( 'Leaderboard' , FS_AFFILIATES_LOCALE ) ;

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
                    'type'  => 'title' ,
                    'title' => __( 'Admin Leaderboard Settings' , FS_AFFILIATES_LOCALE ) ,
                    'id'    => 'admin_leaderboard_options' ,
                ) ,
                array (
                    'title'   => __( 'Menu Label' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->plugin_slug . '_' . $this->id . '_menu_label' ,
                    'type'    => 'text' ,
                    'default' => 'Leaderboard' ,
                ) ,
                array (
                    'title'   => __( 'Limit' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->plugin_slug . '_' . $this->id . '_limit' ,
                    'desc'    => __( 'The Number of Affiliates to be Displayed in a Page' , FS_AFFILIATES_LOCALE ) ,
                    'type'    => 'number' ,
                    'default' => '50' ,
                ) ,
                array (
                    'title'   => __( 'Leaderboard Display Method' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->plugin_slug . '_' . $this->id . '_display_method' ,
                    'desc'    => __( 'Predefined – Leaderboard will be visible to the affiliates based on admin option. User Defined – Affiliates can view the Leaderboard based on multiple filters.' , FS_AFFILIATES_LOCALE ) ,
                    'type'    => 'select' ,
                    'default' => '1' ,
                    'options' => array (
                        '1' => __( 'Predefined' , FS_AFFILIATES_LOCALE ) ,
                        '2' => __( 'User Defined' , FS_AFFILIATES_LOCALE ) ,
                    ) ,
                ) ,
                array (
                    'title'   => __( 'Predefined Leaderboard Type' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->plugin_slug . '_' . $this->id . '_predefined_type' ,
                    'desc'    => __( 'Leaderboard will be generated based on the value set in this option' , FS_AFFILIATES_LOCALE ) ,
                    'type'    => 'select' ,
                    'default' => '1' ,
                    'options' => array (
                        '1' => __( 'Commission Earned' , FS_AFFILIATES_LOCALE ) ,
                        '2' => __( 'No of Referrals' , FS_AFFILIATES_LOCALE ) ,
                        '3' => __( 'No of Orders Placed by Referrals' , FS_AFFILIATES_LOCALE ) ,
                        '4' => __( 'Amount Spent by Referrals' , FS_AFFILIATES_LOCALE ) ,
                    ) ,
                ) ,
                array (
                    'type' => 'sectionend' ,
                    'id'   => 'admin_leaderboard_options' ,
                ) ,
                    ) ;
        }

        /**
         * Frontend Actions
         */
        public function frontend_action() {
            add_filter( 'fs_affiliates_frontend_dashboard_menu' , array ( $this , 'leaderboard_menu' ) , 12 , 3 ) ;

            add_action( 'fs_affiliates_dashboard_content_leaderboard' , array ( $this , 'display_dashboard_content' ) , 10 , 3 ) ;
        }

        /*
         * Custom Dashboard Menu
         */

        public function leaderboard_menu( $menus , $user_id , $affiliate_id ) {

            $menus[ 'leaderboard' ] = array ( 'label' => $this->menu_label , 'code' => 'fa-trophy' ) ;

            return $menus ;
        }

        /*
         * Display Dashboard Content
         */

        public function display_dashboard_content( $user_id , $affiliate_id ) {
            echo '<div class = "fs_affiliates_form">' ;
            echo '<h2>' . __( 'Leaderboard' , FS_AFFILIATES_LOCALE ) . '</h2>' ;

            $display_type = isset( $_GET[ 'type' ] ) ? $_GET[ 'type' ] : $this->predefined_type ;

            if ( $this->display_method == '2' ) {
                if ( isset( $_POST[ 'fs_affiliates_leaderboard_type' ] ) ) {
                    $display_type = $_POST[ 'fs_affiliates_leaderboard_type' ] ;
                    unset( $_REQUEST[ 'page_no' ] ) ;
                }
                $this->display_filter( $display_type ) ;
            }

            $get_permalink = FS_AFFILIATES_PROTOCOL . $_SERVER[ 'HTTP_HOST' ] . $_SERVER[ 'REQUEST_URI' ] ;
            $get_permalink = remove_query_arg( array ( 'type' ) , $get_permalink ) ;
            $get_permalink = add_query_arg( array ( 'type' => $display_type ) , $get_permalink ) ;

            //prepare the affiliates
            $affiliates = $this->prepare_data( $display_type ) ;
            //sort the affiliates
            arsort( $affiliates ) ;

            $position = array_search( $affiliate_id , array_keys( $affiliates ) ) ;

            echo '<p><label><b>' . __( 'Current Leaderboard Position' , FS_AFFILIATES_LOCALE ) . '</b>:&nbsp' . ++$position . '</label></p>' ;

            switch ( $display_type ) {
                case '2':
                    $this->display_no_of_referrals( $affiliates , $get_permalink ) ;
                    break ;
                case '3':
                    $this->display_no_of_orders_placed_by_referrals( $affiliates , $get_permalink ) ;
                    break ;
                case '4':
                    $this->display_amount_spent_by_referrals( $affiliates , $get_permalink ) ;
                    break ;
                default:
                    $this->display_commision_earned( $affiliates , $get_permalink ) ;
                    break ;
            }
            echo '</div>' ;
        }

        /*
         * Prepare data for leader board
         */

        public function prepare_data( $display_type ) {
            global $wpdb ;

            $overall_affiliates = $wpdb->get_results(
                    $wpdb->prepare( "SELECT p.ID as id FROM $wpdb->posts as p "
                            . "WHERE p.post_type='fs-affiliates' AND p.post_status=%s ORDER BY p.post_date" , 'fs_active' ) , ARRAY_A ) ;

            $overall_affiliates = fs_affiliates_get_array_column_values( $overall_affiliates , 'id' ) ;
            $overall_affiliates = array_fill_keys( $overall_affiliates , 0 ) ;

            switch ( $display_type ) {
                case '2':

                    $referral_affiliates = $wpdb->get_results(
                            $wpdb->prepare( "SELECT p.post_author as id ,count(p.post_author) as count FROM $wpdb->posts as p "
                                    . "WHERE p.post_type=%s GROUP BY p.post_author" , 'fs-referrals' ) , ARRAY_A ) ;

                    $referral_affiliates_ids    = fs_affiliates_get_array_column_values( $referral_affiliates , 'id' ) ;
                    $referral_affiliates_values = fs_affiliates_get_array_column_values( $referral_affiliates , 'count' ) ;
                    $affiliates                 = array_combine( $referral_affiliates_ids , $referral_affiliates_values ) ;

                    break ;
                case '3':
                    $referral_affiliates = $wpdb->get_results(
                            $wpdb->prepare( "SELECT meta2.meta_value as id, count(meta2.meta_value) as count FROM $wpdb->posts as p "
                                    . "INNER JOIN $wpdb->postmeta as meta ON p.ID=meta.post_id "
                                    . "INNER JOIN $wpdb->postmeta as meta2 ON p.ID=meta2.post_id "
                                    . "WHERE p.post_type=%s AND meta.meta_key='fs_commission_awarded' AND meta.meta_value='yes' "
                                    . "AND meta2.meta_key='fs_affiliate_in_order' GROUP BY meta2.meta_value "
                                    . "ORDER BY count(meta2.meta_value) ASC" , 'shop_order' ) , ARRAY_A ) ;

                    $referral_affiliates_ids    = fs_affiliates_get_array_column_values( $referral_affiliates , 'id' ) ;
                    $referral_affiliates_values = fs_affiliates_get_array_column_values( $referral_affiliates , 'count' ) ;
                    $affiliates                 = array_combine( $referral_affiliates_ids , $referral_affiliates_values ) ;
                    break ;
                case '4':
                    $referral_affiliates        = $wpdb->get_results(
                            $wpdb->prepare( "SELECT meta3.meta_value as id, sum(meta2.meta_value) as count FROM $wpdb->posts as p "
                                    . "INNER JOIN $wpdb->postmeta as meta ON p.ID=meta.post_id "
                                    . "INNER JOIN $wpdb->postmeta as meta2 ON p.ID=meta2.post_id "
                                    . "INNER JOIN $wpdb->postmeta as meta3 ON p.ID=meta3.post_id "
                                    . "WHERE p.post_type=%s AND meta.meta_key='fs_commission_awarded' AND meta.meta_value='yes' "
                                    . "AND meta2.meta_key='_order_total' AND meta3.meta_key='fs_affiliate_in_order' "
                                    . "AND meta3.meta_value!='' GROUP BY meta3.meta_value "
                                    . "ORDER BY sum(meta3.meta_value) ASC" , 'shop_order' ) , ARRAY_A ) ;

                    $referral_affiliates_ids    = fs_affiliates_get_array_column_values( $referral_affiliates , 'id' ) ;
                    $referral_affiliates_values = fs_affiliates_get_array_column_values( $referral_affiliates , 'count' ) ;
                    $affiliates                 = array_combine( $referral_affiliates_ids , $referral_affiliates_values ) ;
                    break ;
                default:

                    $query = $wpdb->prepare( "SELECT p.ID as id FROM $wpdb->posts as p "
                            . "INNER JOIN $wpdb->postmeta as meta ON p.ID=meta.post_id "
                            . "WHERE p.post_type='fs-affiliates' AND p.post_status=%s "
                            . "AND meta.meta_key='paid_earnings' ORDER BY meta.meta_value+0 ASC" , 'fs_active' ) ;

                    $affiliate_commission_ids = $wpdb->get_results( $query , ARRAY_A ) ;

                    $affiliate_commission_ids = fs_affiliates_get_array_column_values( $affiliate_commission_ids , 'id' ) ;
                    $affiliates               = array_flip( $affiliate_commission_ids ) ;

                    break ;
            }

            return fs_affiliates_array_merge_based_on_first( $affiliates , $overall_affiliates ) ;
        }

        /*
         * Display user defined filter
         */

        public function display_filter( $display_type ) {
            if ( $this->display_method != '2' )
                return ;
            ?>
            <form method="post">
                <p><label><?php echo _e( 'Display Leaderboard Based On' , FS_AFFILIATES_LOCALE ) ; ?></label>
                    <select name="fs_affiliates_leaderboard_type" >
                        <option value="1" <?php selected( $display_type , '1' ) ; ?>><?php _e( 'Commission Earned' , FS_AFFILIATES_LOCALE ) ; ?></option>
                        <option value="2" <?php selected( $display_type , '2' ) ; ?>><?php _e( 'No of Referrals' , FS_AFFILIATES_LOCALE ) ; ?></option>
                        <option value="3" <?php selected( $display_type , '3' ) ; ?>><?php _e( 'No of Orders Placed by Referrals' , FS_AFFILIATES_LOCALE ) ; ?></option>
                        <option value="4" <?php selected( $display_type , '4' ) ; ?>><?php _e( 'Amount Spent by Referrals' , FS_AFFILIATES_LOCALE ) ; ?></option>
                    </select>
                    <input type="submit" class="fs_affiliates_form_save" value="<?php _e( 'Filter' , FS_AFFILIATES_LOCALE ) ; ?>"/>
                </p>
            </form>

            <?php
        }

        /*
         * Display Commission earned leaderboard
         */

        public function display_commision_earned( &$affiliates , $get_permalink ) {
            $perpage       = 10 ;
            $count         = count( $affiliates ) ;
            $current_page  = isset( $_REQUEST[ 'page_no' ] ) && $_REQUEST[ 'page_no' ] ? ( int ) $_REQUEST[ 'page_no' ] : 1 ;
            $limit         = ($count > $this->limit) ? $this->limit : $count ;
            $page_count    = ceil( $limit / $perpage ) ;
            ?>
            <table class="fs_affiliates_leaderboard_frontend_table fs_affiliates_leaderboard_frontend_table_one fs_affiliates_frontend_table">
                <tbody>
                    <tr>
                        <th class="fs_affiliates_sno fs_affiliates_leaderboard_sno"><?php _e( 'S.No' , FS_AFFILIATES_LOCALE ) ?></th>
                        <th><?php _e( 'Affiliate Name' , FS_AFFILIATES_LOCALE ) ?></th>
                        <th><?php _e( 'Commission Earned' , FS_AFFILIATES_LOCALE ) ?></th>
                    </tr>
                    <?php
                    $overall_count = 1 ;
                    $current_count = 1 ;
                    $offset        = ($current_page - 1) * $perpage ;

                    foreach ( $affiliates as $affiliate_id => $value ) {

                        if ( $overall_count++ <= $offset )
                            continue ;

                        if ( $offset >= $limit || $current_count++ > $perpage )
                            break ;

                        $affiliate_data = new FS_Affiliates_Data( $affiliate_id ) ;
                        ?><tr>
                            <td data-title="<?php esc_html_e( 'S.No' , FS_AFFILIATES_LOCALE ) ?>" class="fs_affiliates_sno fs_affiliates_leaderboard_sno"><?php echo $offset + 1 ; ?></td>
                            <td data-title="<?php esc_html_e( 'Affiliate Name' , FS_AFFILIATES_LOCALE ) ?>" ><?php echo $affiliate_data->user_name ; ?></td>
                            <td data-title="<?php esc_html_e( 'Commission Earned' , FS_AFFILIATES_LOCALE ) ?>" ><?php echo $affiliate_data->get_paid_commission() ; ?></td>
                        </tr><?php
                        $offset ++ ;
                    }
                    ?></tbody>
                <tfoot>
                    <tr style="clear:both;">
                        <td colspan="3" class="footable-visible">
                            <div class="pagination pagination-centered">
                                <?php
                                if ( $page_count > 1 ) {
                                    FS_Affiliates_Dashboard::fs_affiliates_set_pagination( $current_page , $page_count , $get_permalink ) ;
                                }
                                ?>
                            </div>
                        </td>
                    </tr>
                </tfoot>
            </table>
            <?php
        }

        /*
         * Display No of Referrals for Affiliate
         */

        public function display_no_of_referrals( &$affiliates , $get_permalink ) {
            $perpage       = 10 ;
            $count         = count( $affiliates ) ;
            $current_page  = isset( $_REQUEST[ 'page_no' ] ) && $_REQUEST[ 'page_no' ] ? ( int ) $_REQUEST[ 'page_no' ] : 1 ;
            $limit         = ($count > $this->limit) ? $this->limit : $count ;
            $page_count    = ceil( $limit / $perpage ) ;
            ?>
            <table class="fs_affiliates_leaderboard_frontend_table fs_affiliates_leaderboard_frontend_table_two fs_affiliates_frontend_table">
                <tbody>
                    <tr>
                        <th class="fs_affiliates_sno fs_affiliates_leaderboard_two_sno"><?php _e( 'S.No' , FS_AFFILIATES_LOCALE ) ?></th>
                        <th><?php _e( 'Affiliate Name' , FS_AFFILIATES_LOCALE ) ?></th>
                        <th><?php _e( 'No Of Referrals' , FS_AFFILIATES_LOCALE ) ?></th>
                    </tr>
                    <?php
                    $overall_count = 1 ;
                    $current_count = 1 ;
                    $offset        = ($current_page - 1) * $perpage ;
                    foreach ( $affiliates as $affiliate_id => $value ) {
                        if ( $overall_count++ <= $offset )
                            continue ;

                        if ( $offset >= $limit || $current_count++ > $perpage )
                            break ;

                        $affiliate_data = new FS_Affiliates_Data( $affiliate_id ) ;
                        ?><tr>
                            <td data-title="<?php esc_html_e( 'S.No' , FS_AFFILIATES_LOCALE ) ?>" class="fs_affiliates_sno fs_affiliates_leaderboard_two_sno"><?php echo $offset + 1 ; ?></td>
                            <td data-title="<?php esc_html_e( 'Affiliate Name' , FS_AFFILIATES_LOCALE ) ?>" ><?php echo $affiliate_data->user_name ; ?></td>
                            <td data-title="<?php esc_html_e( 'No Of Referrals' , FS_AFFILIATES_LOCALE ) ?>" ><?php echo $value ; ?></td>
                        </tr><?php
                        $offset ++ ;
                    }
                    ?></tbody>
                <tfoot>
                    <tr style="clear:both;">
                        <td colspan="3" class="footable-visible">
                            <div class="pagination pagination-centered">
                                <?php
                                if ( $page_count > 1 ) {
                                    FS_Affiliates_Dashboard::fs_affiliates_set_pagination( $current_page , $page_count , $get_permalink ) ;
                                }
                                ?>
                            </div>
                        </td>
                    </tr>
                </tfoot>
            </table>
            <?php
        }

        /*
         * Display amount spent by Referrals for Affiliate
         */

        public function display_amount_spent_by_referrals( &$affiliates , $get_permalink ) {
            $perpage       = 10 ;
            $count         = count( $affiliates ) ;
            $current_page  = isset( $_REQUEST[ 'page_no' ] ) && $_REQUEST[ 'page_no' ] ? ( int ) $_REQUEST[ 'page_no' ] : 1 ;
            $limit         = ($count > $this->limit) ? $this->limit : $count ;
            $page_count    = ceil( $limit / $perpage ) ;
            ?>
            <table class="fs_affiliates_leaderboard_frontend_table fs_affiliates_leaderboard_frontend_table_three fs_affiliates_frontend_table">
                <tbody>
                    <tr>
                        <th class="fs_affiliates_sno fs_affiliates_leaderboard_three_sno"><?php _e( 'S.No' , FS_AFFILIATES_LOCALE ) ?></th>
                        <th><?php _e( 'Affiliate Name' , FS_AFFILIATES_LOCALE ) ?></th>
                        <th><?php _e( 'Amount Spent by Referrals' , FS_AFFILIATES_LOCALE ) ?></th>
                    </tr>
                    <?php
                    $overall_count = 1 ;
                    $current_count = 1 ;
                    $offset        = ($current_page - 1) * $perpage ;
                    foreach ( $affiliates as $affiliate_id => $value ) {
                        if ( $overall_count++ <= $offset )
                            continue ;

                        if ( $offset >= $limit || $current_count++ > $perpage )
                            break ;

                        $affiliate_data = new FS_Affiliates_Data( $affiliate_id ) ;
                        ?><tr>
                            <td data-title="<?php esc_html_e( 'S.No' , FS_AFFILIATES_LOCALE ) ?>" class="fs_affiliates_sno fs_affiliates_leaderboard_three_sno"><?php echo $offset + 1 ; ?></td>
                            <td data-title="<?php esc_html_e( 'Affiliate Name' , FS_AFFILIATES_LOCALE ) ?>" ><?php echo $affiliate_data->user_name ; ?></td>
                            <td data-title="<?php esc_html_e( 'Amount Spent by Referrals' , FS_AFFILIATES_LOCALE ) ?>" ><?php echo fs_affiliates_price( $value ) ; ?></td>
                        </tr><?php
                        $offset ++ ;
                    }
                    ?></tbody>
                <tfoot>
                    <tr style="clear:both;">
                        <td colspan="3" class="footable-visible">
                            <div class="pagination pagination-centered">
                                <?php
                                if ( $page_count > 1 ) {
                                    FS_Affiliates_Dashboard::fs_affiliates_set_pagination( $current_page , $page_count , $get_permalink ) ;
                                }
                                ?>
                            </div>
                        </td>
                    </tr>
                </tfoot>
            </table>
            <?php
        }

        /*
         * Display no of orders placed by Referrals for Affiliate
         */

        public function display_no_of_orders_placed_by_referrals( &$affiliates , $get_permalink ) {
            $perpage       = 10 ;
            $count         = count( $affiliates ) ;
            $current_page  = isset( $_REQUEST[ 'page_no' ] ) && $_REQUEST[ 'page_no' ] ? ( int ) $_REQUEST[ 'page_no' ] : 1 ;
            $limit         = ($count > $this->limit) ? $this->limit : $count ;
            $page_count    = ceil( $limit / $perpage ) ;
            ?>
            <table class="fs_affiliates_leaderboard_frontend_table fs_affiliates_leaderboard_frontend_table_four fs_affiliates_frontend_table">
                <tbody>
                    <tr>
                        <th class="fs_affiliates_sno fs_affiliates_leaderboard_order_placed_sno"><?php _e( 'S.No' , FS_AFFILIATES_LOCALE ) ?></th>
                        <th><?php _e( 'Affiliate Name' , FS_AFFILIATES_LOCALE ) ?></th>
                        <th><?php _e( 'No of Referral Orders Placed' , FS_AFFILIATES_LOCALE ) ?></th>
                    </tr>
                    <?php
                    $overall_count = 1 ;
                    $current_count = 1 ;
                    $offset        = ($current_page - 1) * $perpage ;
                    foreach ( $affiliates as $affiliate_id => $value ) {
                        if ( $overall_count++ <= $offset )
                            continue ;

                        if ( $offset >= $limit || $current_count++ > $perpage )
                            break ;

                        $affiliate_data = new FS_Affiliates_Data( $affiliate_id ) ;
                        ?><tr>
                            <td data-title="<?php esc_html_e( 'S.No' , FS_AFFILIATES_LOCALE ) ?>" class="fs_affiliates_sno fs_affiliates_leaderboard_order_placed_sno"><?php echo $offset + 1 ; ?></td>
                            <td data-title="<?php esc_html_e( 'Affiliate Name' , FS_AFFILIATES_LOCALE ) ?>" ><?php echo $affiliate_data->user_name ; ?></td>
                            <td data-title="<?php esc_html_e( 'No of Referral Orders Placed' , FS_AFFILIATES_LOCALE ) ?>" ><?php echo $value ; ?></td>
                        </tr><?php
                        $offset ++ ;
                    }
                    ?></tbody>
                <tfoot>
                    <tr style="clear:both;">
                        <td colspan="3" class="footable-visible">
                            <div class="pagination pagination-centered">
                                <?php
                                if ( $page_count > 1 ) {
                                    FS_Affiliates_Dashboard::fs_affiliates_set_pagination( $current_page , $page_count , $get_permalink ) ;
                                }
                                ?>
                            </div>
                        </td>
                    </tr>
                </tfoot>
            </table>
            <?php
        }

    }

}

<?php
/**
 * Overview Tab
 */
if ( ! defined ( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( class_exists ( 'FS_Affiliates_Overview_Tab' ) ) {
    return new FS_Affiliates_Overview_Tab() ;
}

/**
 * FS_Affiliates_Overview_Tab.
 */
class FS_Affiliates_Overview_Tab extends FS_Affiliates_Settings_Page {

    /**
     * Constructor.
     */
    public function __construct() {
        $this->id    = 'overview' ;
        $this->label = __ ( 'Overview' , FS_AFFILIATES_LOCALE ) ;

        add_action ( $this->plugin_slug . '_admin_field_output_overview' , array ( $this , 'output_overview' ) ) ;

        parent::__construct () ;
    }

    /**
     * Output the settings buttons.
     */
    public function output_buttons() {

    }

    /**
     * Get settings array.
     */
    public function get_settings( $current_section = '' ) {

        return array (
            array ( 'type' => 'output_overview' )
                ) ;
    }

    /**
     * Output the affiliates overview
     */
    public function output_overview() {
        ?>

        <div class="fs_affiliates_overview_wrapper">
            <div class="fs_affiliates_overview_colm_one">
                <?php
                echo self::payments_table () ;

                echo self::valuable_affiliate_table () ;

                echo self::recent_affiliate_table () ;
                ?>
            </div>
            <div class="fs_affiliates_overview_colm_two">
                <?php
                echo self::recent_referrals_table () ;

                echo self::recent_visits_table () ;
                ?>
            </div>
        </div>

        <?php
    }
    /* Table Data Display Functionalities */
    public function payments_table() {
        ?>
        <h2> <?php echo __ ( 'Payments' , FS_AFFILIATES_LOCALE ) ?> </h2>

        <?php
        $total_paid                    = self::payment_datas ( 'fs_paid' ) ;
        $total_paid_this_month         = self::payment_datas ( 'fs_paid' , 1 ) ;
        $total_paid_today              = self::payment_datas ( 'fs_paid' , 2 ) ;
        ?>
        <table class="fs_afffiliates_overview_paid">
            <tr>
                <th><?php echo __ ( 'Total Payments Paid' , FS_AFFILIATES_LOCALE ) ?></th>
                <th><?php echo __ ( 'Payments Paid for this Month' , FS_AFFILIATES_LOCALE ) ?></th>
                <th><?php echo __ ( 'Payments Paid for Today' , FS_AFFILIATES_LOCALE ) ?></th>
            </tr>
            <tr>
                <td><?php echo fs_affiliates_price ( $total_paid ) ; ?></td>
                <td><?php echo fs_affiliates_price ( $total_paid_this_month ) ; ?></td>
                <td><?php echo fs_affiliates_price ( $total_paid_today ) ; ?></td>
            </tr>
        </table> <br><br>

        <?php
        $total_unpaid_count            = self::payment_datas ( 'fs_unpaid' , '' , true ) ;
        $total_unpaid_count_this_month = self::payment_datas ( 'fs_unpaid' , 1 , true ) ;
        $total_unpaid_count_today      = self::payment_datas ( 'fs_unpaid' , 2 , true) ;
        ?>
        <table class="fs_afffiliates_overview_unpaid_count">
            <tr>
                <th><?php echo __ ( 'Unpaid referrals' , FS_AFFILIATES_LOCALE ) ?></th>
                <th><?php echo __ ( 'Unpaid referrals for this month' , FS_AFFILIATES_LOCALE ) ?></th>
                <th><?php echo __ ( 'Unpaid referrals today' , FS_AFFILIATES_LOCALE ) ?></th>
            </tr>
            <tr>
                <td><?php echo $total_unpaid_count ; ?></td>
                <td><?php echo $total_unpaid_count_this_month ; ?></td>
                <td><?php echo $total_unpaid_count_today ; ?></td>
            </tr>
        </table> <br><br>

        <?php
        $total_unpaid                  = self::payment_datas ( 'fs_unpaid' ) ;
        $total_unpaid_this_month       = self::payment_datas ( 'fs_unpaid' , 1 ) ;
        $total_unpaid_today            = self::payment_datas ( 'fs_unpaid' , 2 ) ;
        ?>
        <table border="1" class="fs_afffiliates_overview_unpaid">
            <tr>
                <th><?php echo __ ( 'Unpaid Payments' , FS_AFFILIATES_LOCALE ) ?></th>
                <th><?php echo __ ( 'Unpaid Payments for this Month' , FS_AFFILIATES_LOCALE ) ?></th>
                <th><?php echo __ ( 'Unpaid Payments for Today' , FS_AFFILIATES_LOCALE ) ?></th>
            </tr>
            <tr>
                <td><?php echo fs_affiliates_price ( $total_unpaid ) ; ?></td>
                <td><?php echo fs_affiliates_price ( $total_unpaid_this_month ) ; ?></td>
                <td><?php echo fs_affiliates_price ( $total_unpaid_today ) ; ?></td>
            </tr>
        </table>
        <?php
    }

    public function valuable_affiliate_table() {

        $valuable_affiliate_ids    = self::valuable_affilite_datas () ;
        ?>

        <h2> <?php echo __ ( 'Most Valuable Affiliates' , FS_AFFILIATES_LOCALE ) ?> </h2>

        <table class="fs_afffiliates_overview_valuable_affiliates">
            <tr>
                <th><?php echo __ ( 'Affiliate' , FS_AFFILIATES_LOCALE ) ?></th>
                <th><?php echo __ ( 'Earning(s)' , FS_AFFILIATES_LOCALE ) ?></th>
                <th><?php echo __ ( 'Visit(s)' , FS_AFFILIATES_LOCALE ) ?></th>
                <th><?php echo __ ( 'Referral(s)' , FS_AFFILIATES_LOCALE ) ?></th>
            </tr>

            <?php
            $affiliates_count = false ;
            if ( fs_affiliates_check_is_array ( $valuable_affiliate_ids ) ) {
              foreach ($valuable_affiliate_ids as $affiliate_id ) {
                if ( get_post_status ( $affiliate_id ) ) {
                    $affiliates_count = true ;
                    $afffiliate_datas = new FS_Affiliates_Data ( $affiliate_id ) ;
                    ?>
                    <tr>
                        <td><?php echo $afffiliate_datas->user_name ; ?></td>
                        <td><?php echo $afffiliate_datas->get_paid_commission(); ?></td>
                        <td><?php echo $afffiliate_datas->get_visits_count(); ?></td>
                        <td><?php echo $afffiliate_datas->get_referrals_count(); ?></td>
                    </tr>
                    <?php
                }
              }

            }

            if ( ! $affiliates_count ) {
                ?>
                <tr>
                    <td colspan="2"><?php echo __ ( 'No Records found' , FS_AFFILIATES_LOCALE ) ; ?></td>
                </tr>
                <?php
            }
            ?>
        </table>

        <?php
    }

    public function recent_affiliate_table() {

        $affiliate_ids    = self::recent_affilite_datas () ;
        ?>

        <h2> <?php echo __ ( 'Most Recent Affiliates' , FS_AFFILIATES_LOCALE ) ?> </h2>

        <table class="fs_afffiliates_overview_recent_affiliates">
            <tr>
                <th><?php echo __ ( 'Affiliate' , FS_AFFILIATES_LOCALE ) ?></th>
                <th><?php echo __ ( 'Affiliate Status' , FS_AFFILIATES_LOCALE ) ?></th>
            </tr>

            <?php
            $affiliates_count = false ;
            if ( fs_affiliates_check_is_array ( $affiliate_ids ) ) {
                foreach ( $affiliate_ids as $each_id ) {
                    if ( get_post_status ( $each_id ) == "fs_active" ) {
                        $affiliates_count = true ;
                        $afffiliate_datas = new FS_Affiliates_Data ( $each_id ) ;
                        ?>
                        <tr>
                            <td><?php echo $afffiliate_datas->user_name ; ?></td>
                            <td><?php echo __ ( 'Active' , FS_AFFILIATES_LOCALE ) ; ?></td>
                        </tr>
                        <?php
                    }
                }
            }

            if ( ! $affiliates_count ) {
                ?>
                <tr>
                    <td colspan="2"><?php echo __ ( 'No Records found' , FS_AFFILIATES_LOCALE ) ; ?></td>
                </tr>
                <?php
            }
            ?>
        </table>

        <?php
    }

    public function recent_referrals_table() {

        $referrals_ids   = self::recent_referrals_datas () ;
        ?>

        <h2> <?php echo __ ( 'Most Recent Referrals' , FS_AFFILIATES_LOCALE ) ?> </h2>

        <table class="fs_afffiliates_overview_recent_referrals">
            <tr>
                <th><?php echo __ ( 'Affiliate' , FS_AFFILIATES_LOCALE ) ?></th>
                <th><?php echo __ ( 'Referral Amount' , FS_AFFILIATES_LOCALE ) ?></th>
            </tr>

            <?php
            $referrals_count = false ;
            if ( fs_affiliates_check_is_array ( $referrals_ids ) ) {
                foreach ( $referrals_ids as $each_id ) {
                    if ( get_post_status ( $each_id ) ) {
                        $referrals_count = true ;
                        $referrals_data = new FS_Affiliates_Referrals ( $each_id ) ;
                        ?>
                        <tr>
                            <td><?php echo get_the_title ( $referrals_data->affiliate ) ; ?></td>
                            <td><?php echo __ ( fs_affiliates_price ( $referrals_data->amount ) , FS_AFFILIATES_LOCALE ) ; ?></td>
                        </tr>
                        <?php
                    }
                }
            }

            if ( ! $referrals_count ) {
                ?>
                <tr>
                    <td colspan="2"><?php echo __ ( 'No Records found' , FS_AFFILIATES_LOCALE ) ; ?></td>
                </tr>
                <?php
            }
            ?>
        </table>

        <?php
    }

    public function recent_visits_table() {
        $visits_ids   = self::recent_visits_datas () ;
        ?>
        <h2> <?php echo __ ( 'Most Recent Referral Visits' , FS_AFFILIATES_LOCALE ) ?> </h2>
        <table class="fs_afffiliates_overview_referrals_visit">
            <tr>
                <th><?php echo __ ( 'Affiliates' , FS_AFFILIATES_LOCALE ) ?></th>
                <th><?php echo __ ( 'Conversion Status' , FS_AFFILIATES_LOCALE ) ?></th>

            </tr>
            <?php
            $visits_count = false ;

            if ( fs_affiliates_check_is_array ( $visits_ids ) ) {
                foreach ( $visits_ids as $each_id ) {
                    if ( get_post_status ( $each_id['affiliate_id'] ) ) {
                        $visits_count     = true ;
                        $afffiliate_datas = new FS_Affiliates_Data ( $each_id['affiliate_id'] ) ;
                        $visit_status     = get_post_status ( $each_id['visit_id'] ) ;
                        $formatted_status = ($visit_status == 'fs_notconverted') ? __ ( 'No' , FS_AFFILIATES_LOCALE ) : __ ( 'Yes' , FS_AFFILIATES_LOCALE ) ;
                        ?>
                        <tr>
                            <td><?php echo $afffiliate_datas->user_name ; ?></td>
                            <td><?php echo $formatted_status ; ?></td>
                        </tr>
                        <?php
                    }
                }
            }

            if ( ! $visits_count ) {
                ?>
                <tr>
                    <td colspan="2"><?php echo __ ( 'No Records found' , FS_AFFILIATES_LOCALE ) ; ?></td>
                </tr>
                <?php
            }
            ?>
        </table><br><br>

        <?php
    }

    /* Table Data Query Functionalities */

    public function payment_datas( $status , $flag = '' , $count = '' ) {
        global $wpdb ;

        $post_table = fs_affiliates_get_table_name ( 'posts' ) ;

        $meta_table = fs_affiliates_get_table_name ( 'meta' ) ;

        $additional_query = self::additional_query ( $flag ) ;

        if( !$count )
          $query = "SELECT SUM(b.meta_value) as result_value from $post_table as a INNER JOIN $meta_table as b ON a.ID=b.post_id and a.post_type='fs-referrals' and a.post_status='$status' and b.meta_key='amount'" . $additional_query ;

        else
          $query = "SELECT b.meta_value as result_value from $post_table as a INNER JOIN $meta_table as b ON a.ID=b.post_id and a.post_type='fs-referrals' and a.post_status='$status' and b.meta_key='amount'" . $additional_query ;

        $result = $wpdb->get_results ( $query , ARRAY_A ) ;

        if( !$count )
          $result_value = fs_affiliates_get_extracted_value ( $result ) ;

        else
          $result_value = fs_affiliates_get_extracted_value ( $result , true ) ;

        return $result_value ;
    }

    public function valuable_affilite_datas( $flag = '') {

      global $wpdb ;

      $post_table = fs_affiliates_get_table_name ( 'posts' ) ;

      $meta_table = fs_affiliates_get_table_name ( 'meta' ) ;

      $additional_query = self::additional_query ( $flag ) ;

      $query = "SELECT ID as affiliate_id from $post_table as a INNER JOIN $meta_table as b ON a.ID=b.post_id and a.post_type='fs-affiliates' and a.post_status='fs_active' and b.meta_key='paid_earnings' ORDER BY CAST(b.meta_value AS unsigned) DESC LIMIT 5" . $additional_query ;

      $result = $wpdb->get_results ( $query , ARRAY_A ) ;

      $result_value = fs_affiliates_get_array_column_values ( $result , 'affiliate_id' ) ;

      return $result_value ;
    }

    public function recent_affilite_datas() {

        global $wpdb ;

        $post_table = fs_affiliates_get_table_name ( 'posts' ) ;

        $query = "SELECT ID from $post_table where post_type='fs-affiliates' and post_status='fs_active' GROUP BY ID ORDER BY post_date DESC LIMIT 5" ;

        $result = $wpdb->get_results ( $query , ARRAY_A ) ;

        $affiliate_ids = fs_affiliates_get_array_column_values ( $result , 'ID' ) ;

        return $affiliate_ids ;
    }

    public function recent_referrals_datas() {
        global $wpdb ;

        $post_table = fs_affiliates_get_table_name ( 'posts' ) ;

        $query = "SELECT ID from $post_table  where post_type = 'fs-referrals' AND post_status = 'fs_unpaid' GROUP BY ID ORDER BY ID DESC LIMIT 5" ;

        $result = $wpdb->get_results ( $query , ARRAY_A ) ;

        $referrals_ids = fs_affiliates_get_array_column_values ( $result , 'ID' ) ;

        return $referrals_ids ;
    }

    public function recent_visits_datas() {
        global $wpdb ;

        $post_table = fs_affiliates_get_table_name ( 'posts' ) ;

        $query = "SELECT ID as visit_id , post_author as affiliate_id from $post_table  where post_type='fs-visits' AND post_status NOT IN('trash') GROUP BY ID ORDER BY ID DESC LIMIT 5" ;

        $result = $wpdb->get_results ( $query , ARRAY_A ) ;

        return $result ;
    }

    public function additional_query( $flag ) {

        $current_time = current_time ( 'timestamp' ) ;

        $additional_query = '' ;

        if ( $flag == 1 ) {
            //Get this month value
            $day_start = 01 ;
            $month     = date ( 'n' , $current_time ) ;
            $day_end   = date ( 't' , $current_time ) ;
            $year      = date ( 'Y' , $current_time ) ;
            $from_date = $year . '-' . $month . '-' . $day_start . ' 00:00:00' ;
            $to_date   = $year . '-' . $month . '-' . $day_end . ' 23:59:59' ;
        } else if ( $flag == 2 ) {
            //Get today value
            $day_start = date ( 'd' , $current_time ) ;
            $month     = date ( 'n' , $current_time ) ;
            $year      = date ( 'Y' , $current_time ) ;
            $from_date = $year . '-' . $month . '-' . $day_start . ' 00:00:00' ;
            $to_date   = $year . '-' . $month . '-' . $day_start . ' 23:59:59' ;
        }

        if ( $flag == 1 || $flag == 2 ) {
            $additional_query = " and post_date BETWEEN '$from_date'AND '$to_date' " ;
        }

        return $additional_query ;
    }

}

return new FS_Affiliates_Overview_Tab() ;

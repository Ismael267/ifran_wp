<?php
/**
 * Reports Tab
 */
if ( !defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( class_exists( 'FS_Affiliates_Payouts_Reports' ) ) {
    return new FS_Affiliates_Payouts_Reports() ;
}

/**
 * FS_Affiliates_Reports_Tab.
 */
class FS_Affiliates_Payouts_Reports extends FS_Affiliates_Settings_Page {

    /**
     * Constructor.
     */
    public function __construct() {

        $this->id    = 'reports' ;
        $this->label = __( 'Reports' , FS_AFFILIATES_LOCALE ) ;

        add_action( $this->plugin_slug . '_' . $this->id . '_payouts_display' , array ( $this , 'payouts_reports' ) ) ;
    }

    /**
     * Output the referrals reports
     */
    public function payouts_reports() {

        FS_Affiliates_Reports_Tab::fs_affiliates_filter_fields( 'fs_report_payouts' ) ;

        echo self::fs_get_meta_boxes() ;

        echo FS_Affiliates_Reports_Tab::fs_affiliates_meta_boxes_layout() ;

        echo self::get_affiliates_graph() ;
    }

    public function fs_get_meta_boxes() {
        $this->pagehook = 'toplevel_page_fs_affiliates' ;
        wp_nonce_field( 'meta-box-order' , 'meta-box-order-nonce' , false ) ;
        add_meta_box( 'fs_affiliates_total_earnings' , __( 'Total Paid Earnings' , FS_AFFILIATES_LOCALE ) , array ( $this , 'fs_affiliates_total_earnings' ) , $this->pagehook , 'primary' , 'core' ) ;
        add_meta_box( 'fs_affiliates_total_earnings_filter' , __( 'Paid Earnings' , FS_AFFILIATES_LOCALE ) , array ( $this , 'fs_affiliates_total_earnings_filter' ) , $this->pagehook , 'secondary' , 'core' ) ;
        add_meta_box( 'fs_affiliates_referrals_per_payout' , __( 'Average Referrals per Payout' , FS_AFFILIATES_LOCALE ) , array ( $this , 'fs_affiliates_referrals_per_payout' ) , $this->pagehook , 'secondary' , 'core' ) ;
        add_meta_box( 'fs_affiliates_total_payouts' , __( 'Total Payouts' , FS_AFFILIATES_LOCALE ) , array ( $this , 'fs_affiliates_total_payouts' ) , $this->pagehook , 'primary' , 'core' ) ;
    }

    public function fs_affiliates_total_earnings() {
        $count = self::fs_get_payouts_earning( 'total' , 'fs_paid' ) ;

        echo '<div align="center">' . fs_affiliates_price( $count ) . '</div>' . '<div align="center"> ' . __( 'All Time' ) . '</div>' ;
    }

    public function fs_affiliates_total_earnings_filter() {
        $count = self::fs_get_payouts_earning( '' , 'fs_paid' ) ;

        echo fs_affiliates_display_content( fs_affiliates_price( $count ) , 'fs_report_payouts' ) ;
    }

    public function fs_affiliates_referrals_per_payout() {
        $count = self::fs_get_average_referrals( '' ) ;

        echo fs_affiliates_display_content( $count , 'fs_report_payouts' ) ;
    }

    public function fs_affiliates_total_payouts() {
        $count = self::fs_get_total_payouts( 'fs_paid' ) ;

        echo fs_affiliates_display_content( $count , 'fs_report_payouts' ) ;
    }

    public function get_payout_datas( $status ) {
        global $wpdb ;

        $post_table = fs_affiliates_get_table_name( 'posts' ) ;

        $meta_table = fs_affiliates_get_table_name( 'meta' ) ;

        $additional_query = self :: fs_get_additional_query() ;

        if ( $status == 'fs_paid' ) {
            $query = "SELECT a.post_author as x , b.meta_value as y from $post_table as a , $meta_table as b where a.post_type='fs-payouts' and a.post_status='$status' and b.meta_key='paid_amount' $additional_query and a.ID=b.post_id GROUP BY a.post_author" ;
        } else {
            $query = "SELECT a.post_author as x , b.meta_value as y from $post_table as a , $meta_table as b where a.post_type='fs-payouts' and b.meta_key='paid_amount' $additional_query and a.ID=b.post_id GROUP BY a.post_author" ;
        }

        $result = $wpdb->get_results( $query , ARRAY_A ) ;

        return $result ;
    }

    public function get_affiliates_graph() {
        $generated_earnings = self::get_payout_datas( '' ) ;
        $paid_earnings      = self::get_payout_datas( 'fs_paid' ) ;
        ?>
        <canvas id="canvas-payouts" width="800" height="350"></canvas>

        <script type="text/javascript">


            var timeFormat = 'YYYY-MM-DD h:mm:ss' ;

            var config = {
                type : 'line' ,
                data : {
                    datasets : [
                        {
                            label : "<?php _e( "Unpaid Earnings" , FS_AFFILIATES_LOCALE ) ; ?>" ,
                            data : <?php echo json_encode( $generated_earnings ) ; ?> ,
                            fill : false ,
                            borderColor : 'green'
                        } ,
                        {
                            label : "<?php _e( "Paid Earnings" , FS_AFFILIATES_LOCALE ) ; ?>" ,
                            data : <?php echo json_encode( $paid_earnings ) ; ?> ,
                            fill : false ,
                            borderColor : 'blue'
                        } ,
                    ]
                } ,
                options : {
                    responsive : true ,
                    title : {
                        display : true ,
                        text : "<?php _e( "Earnings Graphical Overview" , FS_AFFILIATES_LOCALE ) ; ?>"
                    } ,
                    scales : {
                        xAxes : [ {
                                type : "time" ,
                                time : {
                                    format : timeFormat ,
                                    tooltipFormat : 'll'
                                } ,
                                scaleLabel : {
                                    display : true ,
                                    labelString : "<?php _e( "Duration" , FS_AFFILIATES_LOCALE ) ; ?>"
                                } ,
                                ticks : {
                                    min : 'March'
                                }
                            } ] ,
                        yAxes : [ {
                                scaleLabel : {
                                    display : true ,
                                    labelString : "<?php _e( "Earnings" , FS_AFFILIATES_LOCALE ) ; ?>"
                                } ,
                                ticks : {
                                    min : 0 ,
                                }
                            } ]
                    }
                }
            } ;

            var ctx = document.getElementById( "canvas-payouts" ).getContext( "2d" ) ;
            new Chart( ctx , config ) ;

        </script>
        <?php
    }

    public function fs_get_payouts_earning( $status ) {
        global $wpdb ;

        $post_table = fs_affiliates_get_table_name( 'posts' ) ;

        $meta_table = fs_affiliates_get_table_name( 'meta' ) ;

        $additional_query = self::fs_get_additional_query() ;

        $query = "SELECT SUM(b.meta_value) as result_value from $post_table as a , $meta_table as b where a.post_type='fs-payouts' and a.post_status='$status' and b.meta_key='paid_amount' and a.ID=b.post_id" . $additional_query ;

        $result = $wpdb->get_results( $query , ARRAY_A ) ;

        $result_value = fs_affiliates_get_extracted_value( $result ) ;

        return $result_value ;
    }

    public function fs_get_average_referrals( $status ) {
        global $wpdb ;

        $post_table = fs_affiliates_get_table_name( 'posts' ) ;

        $meta_table = fs_affiliates_get_table_name( 'meta' ) ;

        $additional_query = self::fs_get_additional_query() ;

        $query = "SELECT AVG(b.meta_value) as result_value from $post_table as a , $meta_table as b where a.post_type='fs-payouts' and a.post_status='$status' and b.meta_key='referrals' and a.ID=b.post_id" . $additional_query ;

        $result = $wpdb->get_results( $query , ARRAY_A ) ;

        $result_value = fs_affiliates_get_extracted_value( $result ) ;

        return $result_value ;
    }

    public function fs_get_total_payouts( $status ) {
        global $wpdb ;

        $post_table = fs_affiliates_get_table_name( 'posts' ) ;

        $query = "SELECT * from $post_table where post_type='fs-payouts' and post_status='$status'" ;

        $result = $wpdb->get_results( $query , ARRAY_A ) ;

        $total_count = count( $result ) ;

        return $total_count . ' ' . __( 'Payouts' ) ;
    }

    public function fs_get_additional_query( $type_args = '' ) {
        $additional_query = '' ;
        if ( (isset( $_REQUEST[ 'fs_report_payouts' ] ) && $_REQUEST[ 'fs_report_payouts' ] != 'all') && $type_args != 'total' ) {
            $filter_type = $_REQUEST[ 'fs_report_payouts' ] ;

            $from_date = fs_affiliates_get_date_ranges( $filter_type , 'from' ) ;

            $to_date = fs_affiliates_get_date_ranges( $filter_type , 'to' ) ;

            if ( $from_date != '' && $to_date != '' ) {
                $additional_query = " and post_date BETWEEN '$from_date'AND '$to_date' " ;
            }
        }
        return $additional_query ;
    }

}

return new FS_Affiliates_Payouts_Reports() ;

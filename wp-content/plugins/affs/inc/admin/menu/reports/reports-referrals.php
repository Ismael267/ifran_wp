<?php
/**
 * Reports Tab
 */
if ( !defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( class_exists( 'FS_Affiliates_Referrals_Reports' ) ) {
    return new FS_Affiliates_Referrals_Reports() ;
}

/**
 * FS_Affiliates_Reports_Tab.
 */
class FS_Affiliates_Referrals_Reports extends FS_Affiliates_Settings_Page {

    /**
     * Constructor.
     */
    public function __construct() {

        $this->id    = 'reports' ;
        $this->label = __( 'Reports' , FS_AFFILIATES_LOCALE ) ;

        add_action( $this->plugin_slug . '_' . $this->id . '_referrals_display' , array ( $this , 'referrals_reports' ) ) ;
    }

    /**
     * Output the referrals reports
     */
    public function referrals_reports() {

        FS_Affiliates_Reports_Tab::fs_affiliates_filter_fields( 'fs_report_referrals' ) ;

        echo self::fs_get_meta_boxes() ;

        echo FS_Affiliates_Reports_Tab::fs_affiliates_meta_boxes_layout() ;

        echo self::get_referral_graph() ;
    }

    public function fs_get_meta_boxes() {
        $settings_hook = 'toplevel_page_fs_affiliates' ;
        wp_nonce_field( 'meta-box-order' , 'meta-box-order-nonce' , false ) ;
        add_meta_box( 'fs_total_referral' , __( 'Total Referrals' , FS_AFFILIATES_LOCALE ) , array ( $this , 'fs_total_referral' ) , $settings_hook , 'primary' , 'core' ) ;
        add_meta_box( 'fs_referral_paid' , __( 'Paid Referrals' , FS_AFFILIATES_LOCALE ) , array ( $this , 'fs_referral_paid' ) , $settings_hook , 'secondary' , 'core' ) ;
        add_meta_box( 'fs_referral_unpaid' , __( 'Unpaid Referrals' , FS_AFFILIATES_LOCALE ) , array ( $this , 'fs_referral_unpaid' ) , $settings_hook , 'primary' , 'core' ) ;
        add_meta_box( 'fs_referral_amount_paid' , __( 'Paid Referral Amount' , FS_AFFILIATES_LOCALE ) , array ( $this , 'fs_referral_amount_paid' ) , $settings_hook , 'secondary' , 'core' ) ;
        add_meta_box( 'fs_referral_amount_unpaid' , __( 'Unpaid Referral Amount' , FS_AFFILIATES_LOCALE ) , array ( $this , 'fs_referral_amount_unpaid' ) , $settings_hook , 'primary' , 'core' ) ;
        add_meta_box( 'fs_referral_avg_amount' , __( 'Average Referral Amount' , FS_AFFILIATES_LOCALE ) , array ( $this , 'fs_referral_avg_amount' ) , $settings_hook , 'secondary' , 'core' ) ;
    }

    public function fs_total_referral() {
        global $wpdb ;

        $post_table = fs_affiliates_get_table_name( 'posts' ) ;

        $query = "SELECT * from $post_table where post_type='fs-referrals' and post_status!='trash'" ;

        $result = $wpdb->get_results( $query , ARRAY_A ) ;

        $total_count = count( $result ) ;

        $total_referrals = $total_count . ' ' . __( 'Referrals' , FS_AFFILIATES_LOCALE ) ;

        echo '<div align="center">' . $total_referrals . '</div>' . '<div align="center"> ' . __( 'All Time' , FS_AFFILIATES_LOCALE ) . '</div>' ;
    }

    public function fs_referral_get_paid_unpaid( $type ) {
        global $wpdb ;

        $post_table = fs_affiliates_get_table_name( 'posts' ) ;

        $additional_query = self::fs_get_additional_query() ;

        $query = "SELECT * from $post_table where post_type='fs-referrals' and post_status='$type'" . $additional_query ;

        $result = $wpdb->get_results( $query , ARRAY_A ) ;

        $total_count = count( $result ) ;

        return $total_count . ' ' . __( 'Referrals' ) ;
    }

    public function fs_referral_paid() {
        $total_paid = self::fs_referral_get_paid_unpaid( 'fs_paid' ) ;

        echo fs_affiliates_display_content( $total_paid , 'fs_report_referrals' ) ;
    }

    public function fs_referral_unpaid() {
        $total_unpaid = self::fs_referral_get_paid_unpaid( 'fs_unpaid' ) ;

        echo fs_affiliates_display_content( $total_unpaid , 'fs_report_referrals' ) ;
    }

    public function fs_referral_amount_paid_unpaid( $type ) {

        global $wpdb ;

        $post_table = fs_affiliates_get_table_name( 'posts' ) ;

        $meta_table = fs_affiliates_get_table_name( 'meta' ) ;

        $additional_query = self::fs_get_additional_query() ;

        $query = "SELECT SUM(b.meta_value) as result_value from $post_table as a , $meta_table as b where a.post_type='fs-referrals' and a.post_status='$type' and b.meta_key='amount' and a.ID=b.post_id" . $additional_query ;

        $result = $wpdb->get_results( $query , ARRAY_A ) ;

        $result_value = fs_affiliates_get_extracted_value( $result ) ;

        return $result_value ;
    }

    public function fs_referral_amount_paid() {
        $referral_amount_paid = self::fs_referral_amount_paid_unpaid( 'fs_paid' ) ;

        echo fs_affiliates_display_content( fs_affiliates_price( $referral_amount_paid ) , 'fs_report_referrals' ) ;
    }

    public function fs_referral_amount_unpaid() {
        $referral_amount_unpaid = self::fs_referral_amount_paid_unpaid( 'fs_unpaid' ) ;

        echo fs_affiliates_display_content( fs_affiliates_price( $referral_amount_unpaid ) , 'fs_report_referrals' ) ;
    }

    public function fs_referral_avg_amount() {
        global $wpdb ;

        $post_table = fs_affiliates_get_table_name( 'posts' ) ;

        $meta_table = fs_affiliates_get_table_name( 'meta' ) ;

        $additional_query = self::fs_get_additional_query() ;

        $query = "SELECT AVG(b.meta_value) as result_value from $post_table as a , $meta_table as b where a.post_type='fs-referrals' and b.meta_key='amount' and b.meta_value!='' and a.ID=b.post_id" . $additional_query ;

        $result = $wpdb->get_results( $query , ARRAY_A ) ;

        $result_value = fs_affiliates_get_extracted_value( $result ) ;

        echo fs_affiliates_display_content( fs_affiliates_price( $result_value ) , 'fs_report_referrals' ) ;
    }

    public function get_referral_datas( $status ) {
        global $wpdb ;

        $post_table = fs_affiliates_get_table_name( 'posts' ) ;

        $meta_table = fs_affiliates_get_table_name( 'meta' ) ;

        $additional_query = self::fs_get_additional_query() ;

        $query = "SELECT a.post_date as x , b.meta_value as y from $post_table as a , $meta_table as b where a.post_type='fs-referrals' and a.post_status='$status' and b.meta_key='amount' and a.ID=b.post_id" . $additional_query ;

        $result = $wpdb->get_results( $query , ARRAY_A ) ;

        $earnings_array = array_values( $result ) ;

        return $earnings_array ;
    }

    public function get_referral_graph() {

        $unpaid_array   = self::get_referral_datas( 'fs_unpaid' ) ;
        $paid_array     = self::get_referral_datas( 'fs_paid' ) ;
        $pending_array  = self::get_referral_datas( 'fs_pending' ) ;
        $rejected_array = self::get_referral_datas( 'fs_rejected' ) ;
        ?>
        <canvas id="canvas-referrals" width="800" height="350"></canvas>

        <script type="text/javascript">


            var timeFormat = 'YYYY-MM-DD h:mm:ss' ;

            var config = {
                type : 'line' ,
                data : {
                    datasets : [
                        {
                            label : "<?php _e( "Unpaid Earnings" , FS_AFFILIATES_LOCALE ) ; ?>" ,
                            data : <?php echo json_encode( $unpaid_array ) ; ?> ,
                            fill : false ,
                            borderColor : 'red'
                        } ,
                        {
                            label : "<?php _e( "Paid Earnings" , FS_AFFILIATES_LOCALE ) ; ?>" ,
                            data : <?php echo json_encode( $paid_array ) ; ?> ,
                            fill : false ,
                            borderColor : 'blue'
                        } ,
                        {
                            label : "<?php _e( "Pending Earnings" , FS_AFFILIATES_LOCALE ) ; ?>" ,
                            data : <?php echo json_encode( $pending_array ) ; ?> ,
                            fill : false ,
                            borderColor : 'yellow'
                        } ,
                        {
                            label : "<?php _e( "Rejected Earnings" , FS_AFFILIATES_LOCALE ) ; ?>" ,
                            data : <?php echo json_encode( $rejected_array ) ; ?> ,
                            fill : false ,
                            borderColor : 'brown'
                        } ,
                    ]
                } ,
                options : {
                    responsive : true ,
                    title : {
                        display : true ,
                        text : "<?php _e( "Referral Earnings Graphical Overview" , FS_AFFILIATES_LOCALE ) ; ?>"
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
                                    labelString : "<?php _e( 'Duration' , FS_AFFILIATES_LOCALE ) ; ?>"
                                } ,
                                ticks : {
                                    min : 'March'
                                }
                            } ] ,
                        yAxes : [ {
                                scaleLabel : {
                                    display : true ,
                                    labelString : "<?php _e( 'Earnings' , FS_AFFILIATES_LOCALE ) ; ?>"
                                } ,
                                ticks : {
                                    min : 0 ,
                                }
                            } ]
                    }
                }
            } ;

            var ctx = document.getElementById( "canvas-referrals" ).getContext( "2d" ) ;
            new Chart( ctx , config ) ;

        </script>
        <?php
    }

    public function fs_get_additional_query( $type_args = '' ) {
        $additional_query = '' ;
        if ( (isset( $_REQUEST[ 'fs_report_referrals' ] ) && $_REQUEST[ 'fs_report_referrals' ] != 'all') && $type_args != 'total' ) {
            $filter_type = $_REQUEST[ 'fs_report_referrals' ] ;

            $from_date = fs_affiliates_get_date_ranges( $filter_type , 'from' ) ;

            $to_date = fs_affiliates_get_date_ranges( $filter_type , 'to' ) ;

            if ( $from_date != '' && $to_date != '' ) {
                $additional_query = " and post_date BETWEEN '$from_date'AND '$to_date' " ;
            }
        }

        return $additional_query ;
    }

}

return new FS_Affiliates_Referrals_Reports() ;

<?php
/**
 * Reports Tab
 */
if ( !defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( class_exists( 'FS_Affiliates_Visits_Reports' ) ) {
    return new FS_Affiliates_Visits_Reports() ;
}

/**
 * FS_Affiliates_Reports_Tab.
 */
class FS_Affiliates_Visits_Reports extends FS_Affiliates_Settings_Page {

    /**
     * Constructor.
     */
    public function __construct() {

        $this->id    = 'reports' ;
        $this->label = __( 'Reports' , FS_AFFILIATES_LOCALE ) ;

        add_action( $this->plugin_slug . '_' . $this->id . '_visits_display' , array ( $this , 'visits_reports' ) ) ;
    }

    /**
     * Output the referrals reports
     */
    public function visits_reports() {

        FS_Affiliates_Reports_Tab::fs_affiliates_filter_fields( 'fs_report_visits' ) ;

        echo self::fs_get_meta_boxes() ;

        echo FS_Affiliates_Reports_Tab::fs_affiliates_meta_boxes_layout() ;

        echo self::get_visits_graph() ;
    }

    public function fs_get_meta_boxes() {
        $settings_hook = 'toplevel_page_fs_affiliates' ;
        wp_nonce_field( 'meta-box-order' , 'meta-box-order-nonce' , false ) ;
        add_meta_box( 'fs_visits_total' , __( 'Total Visits' , FS_AFFILIATES_LOCALE ) , array ( $this , 'fs_visits_total' ) , $settings_hook , 'primary' , 'core' ) ;
        add_meta_box( 'fs_visits_total_filter' , __( 'Total Visits' , FS_AFFILIATES_LOCALE ) , array ( $this , 'fs_visits_total_filter' ) , $settings_hook , 'primary' , 'core' ) ;
        add_meta_box( 'fs_visits_converted_visits' , __( 'Converted Visits' , FS_AFFILIATES_LOCALE ) , array ( $this , 'fs_visits_converted_visits' ) , $settings_hook , 'primary' , 'core' ) ;
        add_meta_box( 'fs_visits_convertion_rate' , __( 'Conversion Rate' , FS_AFFILIATES_LOCALE ) , array ( $this , 'fs_visits_convertion_rate' ) , $settings_hook , 'secondary' , 'core' ) ;
    }

    public function get_visits_datas( $status ) {
        global $wpdb ;

        $post_table = fs_affiliates_get_table_name( 'posts' ) ;

        $additional_query = self :: fs_get_additional_query() ;

        $query = "SELECT post_date as x , COUNT(post_date) as y  from $post_table where post_type='fs-visits' and post_status='$status' $additional_query GROUP BY post_date" ;

        $result = $wpdb->get_results( $query , ARRAY_A ) ;

        $earnings_array = array_values( $result ) ;

        return $earnings_array ;
    }

    public function fs_visits_total() {
        $total_visits = self::fs_get_visits_datas( 'total' ) ;
        $count        = $total_visits . ' ' . __( 'Visits' , FS_AFFILIATES_LOCALE ) ;

        echo '<div align="center">' . $count . '</div>' . '<div align="center"> ' . __( 'All Time' ) . '</div>' ;
    }

    public function fs_visits_converted_visits() {
        $total_converted_visits = self::fs_get_visits_datas( 'converted' ) ;
        $count                  = $total_converted_visits . ' ' . __( 'Visits' , FS_AFFILIATES_LOCALE ) ;
        echo fs_affiliates_display_content( $count , 'fs_report_visits' ) ;
    }

    public function fs_visits_convertion_rate() {
        $conversion_rates = self::fs_get_visits_datas( 'convertion_rate' ) ;
        $count            = $conversion_rates . ' ' . __( 'Visits' , FS_AFFILIATES_LOCALE ) ;
        echo fs_affiliates_display_content( $count , 'fs_report_visits' ) ;
    }

    public function fs_visits_total_filter() {
        $total_filter = self::fs_get_visits_datas( 'total_filter' ) ;
        $count        = $total_filter . ' ' . __( 'Visits' , FS_AFFILIATES_LOCALE ) ;

        echo fs_affiliates_display_content( $count , 'fs_report_visits' ) ;
    }

    public function get_visits_graph() {

        $converted_array    = self::get_visits_datas( 'fs_converted' ) ;
        $notconverted_array = self::get_visits_datas( 'fs_notconverted' ) ;
        ?>
        <canvas id="canvas-visits" width="800" height="350"></canvas>

        <script type="text/javascript">


            var timeFormat = 'YYYY-MM-DD h:mm:ss' ;

            var config = {
                type : 'line' ,
                data : {
                    datasets : [
                        {
                            label : "<?php _e( "Converted Visits" , FS_AFFILIATES_LOCALE ) ; ?>" ,
                            data : <?php echo json_encode( $converted_array ) ; ?> ,
                            fill : false ,
                            borderColor : 'green'
                        } ,
                        {
                            label : "<?php _e( "Non-Converted Visits" , FS_AFFILIATES_LOCALE ) ; ?>" ,
                            data : <?php echo json_encode( $notconverted_array ) ; ?> ,
                            fill : false ,
                            borderColor : 'red'
                        } ,
                    ]
                } ,
                options : {
                    responsive : true ,
                    title : {
                        display : true ,
                        text : "<?php _e( "Visits Graphical Overview" , FS_AFFILIATES_LOCALE ) ; ?>"
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
                                    labelString : "<?php _e( "Duartion" , FS_AFFILIATES_LOCALE ) ; ?>"
                                } ,
                                ticks : {
                                    min : 'March'
                                }
                            } ] ,
                        yAxes : [ {
                                scaleLabel : {
                                    display : true ,
                                    labelString : "<?php _e( "Visits" , FS_AFFILIATES_LOCALE ) ; ?>"
                                } ,
                                ticks : {
                                    min : 0 ,
                                    stepSize : 1
                                }
                            } ]
                    }
                }
            } ;

            var ctx = document.getElementById( "canvas-visits" ).getContext( "2d" ) ;
            new Chart( ctx , config ) ;

        </script>
        <?php
    }

    public function fs_get_visits_datas( $type_args = '' ) {
        global $wpdb ;

        $post_table = fs_affiliates_get_table_name( 'posts' ) ;

        $additional_query = self :: fs_get_additional_query( $type_args ) ;

        if ( $type_args == 'total' ) {
            $query = "SELECT * from $post_table where post_type='fs-visits'" ;
        } if ( $type_args == 'total_filter' ) {
            $query = "SELECT * from $post_table where post_type='fs-visits'" . $additional_query ;
        } else if ( $type_args == 'converted' ) {
            $query = "SELECT * from $post_table where post_type='fs-visits' and post_status='fs_converted'" . $additional_query ;
        } else if ( $type_args == 'convertion_rate' ) {
            $query = "SELECT * from $post_table where post_type='fs-visits' and post_status='fs_notconverted'" . $additional_query ;
        }

        $result = $wpdb->get_results( $query , ARRAY_A ) ;

        $total_count = count( $result ) ;

        return $total_count ;
    }

    public function fs_get_top_referrers() {
        global $wpdb ;

        $post_table = fs_affiliates_get_table_name( 'posts' ) ;

        $additional_query = self::fs_get_additional_query() ;

        $query = "SELECT post_author as id , COUNT(post_author) as count from $post_table where post_type='fs-visits' and post_status='fs_notconverted' $additional_query GROUP BY post_author" ;

        $result = $wpdb->get_results( $query , ARRAY_A ) ;

        return $result ;
    }

    public function fs_get_additional_query( $type_args = '' ) {
        $additional_query = '' ;

        if ( isset( $_REQUEST[ 'fs_report_visits' ] ) && $_REQUEST[ 'fs_report_visits' ] != 'all' && $type_args != 'total' ) {
            $filter_type = $_REQUEST[ 'fs_report_visits' ] ;

            $from_date = fs_affiliates_get_date_ranges( $filter_type , 'from' ) ;

            $to_date = fs_affiliates_get_date_ranges( $filter_type , 'to' ) ;

            if ( $from_date != '' && $to_date != '' ) {
                $additional_query = " and post_date BETWEEN '$from_date'AND '$to_date' " ;
            }
        }

        return $additional_query ;
    }

}

return new FS_Affiliates_Visits_Reports() ;

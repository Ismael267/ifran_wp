<?php
/**
 * Reports Tab
 */
if ( !defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( class_exists( 'FS_Affiliates_Campaigns_Reports' ) ) {
    return new FS_Affiliates_Campaigns_Reports() ;
}

/**
 * FS_Affiliates_Campaigns_Reports.
 */
class FS_Affiliates_Campaigns_Reports extends FS_Affiliates_Settings_Page {

    /**
     * Constructor.
     */
    public function __construct() {

        $this->id    = 'reports' ;
        $this->label = __( 'Reports' , FS_AFFILIATES_LOCALE ) ;

        add_action( $this->plugin_slug . '_' . $this->id . '_campaigns_display' , array ( $this , 'campaigns_reports' ) ) ;
    }

    /**
     * Output the campaigns reports
     */
    public function campaigns_reports() {

        FS_Affiliates_Reports_Tab::fs_affiliates_filter_fields( 'fs_report_campaigns' ) ;

        echo self::fs_get_meta_boxes() ;

        echo FS_Affiliates_Reports_Tab::fs_affiliates_meta_boxes_layout() ;

        echo self::get_visits_graph() ;
    }

    public function fs_get_meta_boxes() {
        $settings_hook = 'toplevel_page_fs_affiliates' ;
        wp_nonce_field( 'meta-box-order' , 'meta-box-order-nonce' , false ) ;
        add_meta_box( 'fs_overall_best_converting_campaigns' , __( 'Overall Best Converting Campaign' , FS_AFFILIATES_LOCALE ) , array ( $this , 'overall_best_converting_campaigns' ) , $settings_hook , 'primary' , 'core' ) ;
        add_meta_box( 'fs_overall_active_converting_campaigns' , __( 'Overall Most Active Campaign' , FS_AFFILIATES_LOCALE ) , array ( $this , 'overall_active_converting_campaigns' ) , $settings_hook , 'secondary' , 'core' ) ;
        add_meta_box( 'fs_most_active_campaign' , __( 'Most Active Campaign' , FS_AFFILIATES_LOCALE ) , array ( $this , 'most_active_campaign' ) , $settings_hook , 'secondary' , 'core' ) ;
        add_meta_box( 'fs_best_converting_campaign' , __( 'Best Converting Campaign' , FS_AFFILIATES_LOCALE ) , array ( $this , 'best_converting_campaign' ) , $settings_hook , 'primary' , 'core' ) ;
    }

    public function overall_best_converting_campaigns() {
        $this->display_content( 'overall_best' ) ;
    }

    public function overall_active_converting_campaigns() {
        $this->display_content( 'overall_active' ) ;
    }

    public function most_active_campaign() {
        $this->display_content( 'most_active' ) ;
    }

    public function best_converting_campaign() {
        $this->display_content( 'best' ) ;
    }

    public function display_content( $type ) {

        $campaigns = self::fs_get_visits_datas( $type ) ;

        if ( !fs_affiliates_check_is_array( $campaigns ) ) {
            _e( 'No data' , FS_AFFILIATES_LOCALE ) ;
            return ;
        }

        echo '<div> <p><label>' . __( 'Campaign Name:' , FS_AFFILIATES_LOCALE ) . '<label> &nbsp ' . $campaigns[ 'name' ] . '</p>
                <p><label>' . __( 'Affiliate Name:' , FS_AFFILIATES_LOCALE ) . '<label> &nbsp ' . get_the_title( $campaigns[ 'id' ] ) . '</p>
                    <p><label>' . __( 'Visits:' , FS_AFFILIATES_LOCALE ) . '<label> &nbsp ' . $campaigns[ 'count' ] . '</p>'
        . '</div>' ;
    }

    public function get_visits_datas( $status ) {
        global $wpdb ;

        $additional_query = self :: fs_get_additional_query() ;

        $query = $wpdb->prepare( "SELECT post.post_date as x , COUNT(post.post_date) as y from $wpdb->posts as post "
                . "INNER JOIN $wpdb->postmeta as meta ON post.ID=meta.post_id "
                . "where post.post_type='fs-visits' AND post.post_status=%s AND meta.meta_key='campaign' AND "
                . "meta.meta_value!='' %s GROUP BY post.post_date" , $status , $additional_query ) ;

        $result = $wpdb->get_results( $query , ARRAY_A ) ;

        $earnings_array = array_values( $result ) ;

        return $earnings_array ;
    }

    public function get_visits_graph() {

        $converted_array    = self::get_visits_datas( 'fs_converted' ) ;
        $notconverted_array = self::get_visits_datas( 'fs_notconverted' ) ;
        ?>
        <canvas id="canvas-Compaigns" width="800" height="350"></canvas>

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

            var ctx = document.getElementById( "canvas-Compaigns" ).getContext( "2d" ) ;
            new Chart( ctx , config ) ;

        </script>
        <?php
    }

    public function fs_get_visits_datas( $type_args = '' ) {
        global $wpdb ;

        $additional_query = self :: fs_get_additional_query( $type_args ) ;

        $query = "SELECT post.post_author as id, count(meta.meta_value) as count , meta.meta_value as name "
                . "from $wpdb->posts as post INNER JOIN $wpdb->postmeta as meta ON post.ID=meta.post_id "
                . "where post.post_type='fs-visits' AND meta.meta_key='campaign' AND meta.meta_value!=''" ;

        if ( $type_args == 'overall_best' ) {
            $query .= " AND post_status='fs_converted'" ;
        } elseif ( $type_args == 'overall_active' ) {
            $query .= " AND post_status IN('fs_converted','fs_notconverted')" ;
        } elseif ( $type_args == 'most_active' ) {
            $query .= " AND post_status IN('fs_converted','fs_notconverted')" . $additional_query ;
        } else if ( $type_args == 'best' ) {
            $query .= " AND post_status='fs_converted'" . $additional_query ;
        }

        $query .= " GROUP BY post.post_author,meta.meta_value ORDER BY post.post_date DESC LIMIT 1" ;

        $result = $wpdb->get_results( $query , ARRAY_A ) ;

        return reset( $result ) ;
    }

    public function fs_get_additional_query( $type_args = '' ) {
        $additional_query = '' ;

        if ( isset( $_REQUEST[ 'fs_report_visits' ] ) && $_REQUEST[ 'fs_report_visits' ] != 'all' && $type_args != 'total' ) {
            $filter_type = $_REQUEST[ 'fs_report_visits' ] ;

            $from_date = fs_affiliates_get_date_ranges( $filter_type , 'from' ) ;

            $to_date = fs_affiliates_get_date_ranges( $filter_type , 'to' ) ;

            if ( $from_date != '' && $to_date != '' ) {
                $additional_query = " and post.post_date BETWEEN '$from_date'AND '$to_date' " ;
            }
        }

        return $additional_query ;
    }

}

return new FS_Affiliates_Campaigns_Reports() ;

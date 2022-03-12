<?php
/**
 * Reports Tab
 */
if ( !defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( class_exists( 'FS_Affiliates_Affiliates_Reports' ) ) {
    return new FS_Affiliates_Affiliates_Reports() ;
}

/**
 * FS_Affiliates_Reports_Tab.
 */
class FS_Affiliates_Affiliates_Reports extends FS_Affiliates_Settings_Page {

    /**
     * Constructor.
     */
    public function __construct() {

        $this->id    = 'reports' ;
        $this->label = __( 'Reports' , FS_AFFILIATES_LOCALE ) ;

        add_action( $this->plugin_slug . '_' . $this->id . '_affiliates_display' , array ( $this , 'affiliates_reports' ) ) ;
    }

    /**
     * Output the referrals reports
     */
    public function affiliates_reports() {

        FS_Affiliates_Reports_Tab::fs_affiliates_filter_fields( 'fs_report_affiliates' ) ;

        echo self::fs_get_meta_boxes() ;

        echo FS_Affiliates_Reports_Tab::fs_affiliates_meta_boxes_layout() ;

        echo self::get_affiliates_graph() ;
    }

    public function fs_get_meta_boxes() {
        $settings_hook = 'toplevel_page_fs_affiliates' ;
        wp_nonce_field( 'meta-box-order' , 'meta-box-order-nonce' , false ) ;
        add_meta_box( 'fs_total_affiliates' , __( 'Total Affiliates' , FS_AFFILIATES_LOCALE ) , array ( $this , 'fs_total_affiliates' ) , $settings_hook , 'primary' , 'core' ) ;
        add_meta_box( 'fs_affiliates_registed' , __( 'Affiliates Registered' , FS_AFFILIATES_LOCALE ) , array ( $this , 'fs_affiliates_registed' ) , $settings_hook , 'secondary' , 'core' ) ;
        add_meta_box( 'fs_affiliates_top_earning' , __( 'Top Earning Affiliates' , FS_AFFILIATES_LOCALE ) , array ( $this , 'fs_affiliates_top_earning' ) , $settings_hook , 'secondary' , 'core' ) ;
//        add_meta_box ( 'fs_affiliates_highest_converting' , ' Highest Converting Affiliates' , array ( $this , 'fs_affiliates_highest_converting' ) , $settings_hook , 'primary' , 'core' ) ;
    }

    public function fs_total_affiliates() {

        $total_count = self::fs_get_total_affiliates( 'total' ) ;

        $total_affiliates = $total_count . ' ' . __( 'Affiliates' , FS_AFFILIATES_LOCALE ) ;

        echo '<div align="center">' . $total_affiliates . '</div>' . '<div align="center"> ' . __( 'All Time' ) . '</div>' ;
    }

    public function fs_affiliates_registed() {
        $count = self::fs_get_total_affiliates() ;

        $registed_affilites = $count . ' ' . __( 'Affiliates' , FS_AFFILIATES_LOCALE ) ;

        echo fs_affiliates_display_content( $registed_affilites , 'fs_report_affiliates' ) ;
    }

    public function fs_affiliates_top_earning() {
        global $wpdb ;
        $post_table       = fs_affiliates_get_table_name( 'posts' ) ;
        $meta_table       = fs_affiliates_get_table_name( 'meta' ) ;
        $additional_query = self::fs_get_additional_query() ;
        $query            = "SELECT MAX(b.meta_value) , b.post_id as affiliate_id from $post_table as a , $meta_table as b  where  a.ID = b.post_id and a.post_type='fs-affiliates' and a.post_status='fs_active' and b.meta_key='paid_earnings'" . $additional_query ;
        $result = $wpdb->get_results( $query , ARRAY_A ) ;

        $affiliate_id = fs_affiliates_get_array_column_values ( $result , 'affiliate_id' ) ;
        $affiliate_name = '-';

        if(isset($affiliate_id[0]) && !empty($affiliate_id[0])) {
            $afffiliate_datas = new FS_Affiliates_Data( $affiliate_id[0] ) ;
            $affiliate_name = $afffiliate_datas->user_name;
        }

        echo fs_affiliates_display_content( $affiliate_name , 'fs_report_affiliates' ) ;
    }

    public function fs_affiliates_highest_converting() {
        global $wpdb ;
        $array_to_start   = array () ;
        $post_table       = fs_affiliates_get_table_name( 'posts' ) ;
        $additional_query = self::fs_get_additional_query() ;
        $query            = "SELECT post_author as post_author , COUNT(post_author) as count from $post_table where post_type='fs-visits' and post_status='fs_notconverted' $additional_query GROUP BY post_author" ;
        $results          = $wpdb->get_results( $query , ARRAY_A ) ;
        $array_values     = array_values( $results ) ;

        foreach ( $array_values as $each_array ) {
            $post_author                     = $each_array[ 'post_author' ] ? $each_array[ 'post_author' ] : '' ;
            $post_count                      = $each_array[ 'count' ] ? $each_array[ 'count' ] : '' ;
            $author_count []                 = $post_count ;
            $array_to_start [ $post_author ] = $post_count ;
        }
    }

    public function get_affiliates_datas() {
        global $wpdb ;

        $post_table = fs_affiliates_get_table_name( 'posts' ) ;

        $additional_query = self :: fs_get_additional_query() ;

        $query = "SELECT post_date as x , COUNT(post_date) as y from $post_table where post_type='fs-affiliates' and post_status='fs_active' $additional_query GROUP BY ID" ;

        $result = $wpdb->get_results( $query , ARRAY_A ) ;

        return $result ;
    }

    public function get_affiliates_graph() {

        $affiliate_registration = self::get_affiliates_datas() ;
        ?>
        <canvas id="canvas-affiliate" width="800" height="350"></canvas>

        <script type="text/javascript">


            var timeFormat = 'YYYY-MM-DD h:mm:ss' ;

            var config = {
                type : 'line' ,
                data : {
                    datasets : [
                        {
                            label : "<?php _e( "Affiliate Registrations" , FS_AFFILIATES_LOCALE ) ; ?>" ,
                            data : <?php echo json_encode( $affiliate_registration ) ; ?> ,
                            fill : false ,
                            borderColor : 'green'
                        } ,
                    ]
                } ,
                options : {
                    responsive : true ,
                    title : {
                        display : true ,
                        text : "<?php _e( "Affiliates Graphical Overview" , FS_AFFILIATES_LOCALE ) ; ?>"
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

                            } ] ,
                        yAxes : [ {
                                scaleLabel : {
                                    display : true ,
                                    labelString : "<?php _e( "Affiliates" , FS_AFFILIATES_LOCALE ) ; ?>"
                                } ,
                                ticks : {
                                    min : 0 ,
                                    stepSize : 1
                                }
                            } ]
                    }
                }
            } ;

            var ctx = document.getElementById( "canvas-affiliate" ).getContext( "2d" ) ;
            new Chart( ctx , config ) ;

        </script>
        <?php
    }

    public function fs_get_total_affiliates( $type_args = '' ) {
        global $wpdb ;

        $post_table = fs_affiliates_get_table_name( 'posts' ) ;

        $additional_query = self :: fs_get_additional_query( $type_args ) ;

        $query = "SELECT * from $post_table where post_type='fs-affiliates' and post_status='fs_active'" . $additional_query ;

        $result = $wpdb->get_results( $query , ARRAY_A ) ;

        $total_count = count( $result ) ;

        return $total_count ;
    }

    public function fs_get_additional_query( $type_args = '' ) {
        $additional_query = '' ;
        if ( isset( $_REQUEST[ 'fs_report_affiliates' ] ) && $_REQUEST[ 'fs_report_affiliates' ] != 'all' && $type_args != 'total' ) {
            $filter_type = $_REQUEST[ 'fs_report_affiliates' ] ;

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

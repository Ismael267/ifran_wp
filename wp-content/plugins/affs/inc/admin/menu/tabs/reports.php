<?php
/**
 * Reports Tab
 */
if ( !defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( class_exists( 'FS_Affiliates_Reports_Tab' ) ) {
    return new FS_Affiliates_Reports_Tab() ;
}

/**
 * FS_Affiliates_Reports_Tab.
 */
class FS_Affiliates_Reports_Tab extends FS_Affiliates_Settings_Page {

    /**
     * Constructor.
     */
    public function __construct() {

        $this->id    = 'reports' ;
        $this->label = __( 'Reports' , FS_AFFILIATES_LOCALE ) ;

        include_once(FS_AFFILIATES_PLUGIN_PATH . '/inc/admin/menu/reports/reports-referrals.php') ;
        include_once(FS_AFFILIATES_PLUGIN_PATH . '/inc/admin/menu/reports/reports-affiliates.php') ;
        include_once(FS_AFFILIATES_PLUGIN_PATH . '/inc/admin/menu/reports/reports-visits.php') ;
        include_once(FS_AFFILIATES_PLUGIN_PATH . '/inc/admin/menu/reports/reports-payouts.php') ;
        include_once(FS_AFFILIATES_PLUGIN_PATH . '/inc/admin/menu/reports/reports-campaigns.php') ;

        parent::__construct() ;
    }

    /**
     * Get sections.
     */
    public function get_sections() {

        $sections = array (
            'referrals'  => array (
                'label' => __( 'Referrals' , FS_AFFILIATES_LOCALE ) ,
                'code'  => 'fa-handshake-o'
            ) ,
            'affiliates' => array (
                'label' => __( 'Affiliates' , FS_AFFILIATES_LOCALE ) ,
                'code'  => 'fa-user'
            ) ,
            'payouts'    => array (
                'label' => __( 'Payouts' , FS_AFFILIATES_LOCALE ) ,
                'code'  => 'fa-usd'
            ) ,
            'visits'     => array (
                'label' => __( 'Visits' , FS_AFFILIATES_LOCALE ) ,
                'code'  => 'fa-eye'
            ) ,
            'campaigns'  => array (
                'label' => __( 'Campaigns' , FS_AFFILIATES_LOCALE ) ,
                'code'  => 'fa-users'
            ) ,
                ) ;

        return apply_filters( $this->plugin_slug . '_get_sections_' . $this->id , $sections ) ;
    }

    public static function fs_affiliates_meta_boxes_layout() {
        $settings_hook = 'toplevel_page_fs_affiliates' ;
        ob_start() ;
        ?>
        <div id="affwp-dashboard-widgets-wrap">
            <div id="dashboard-widgets" class="metabox-holder">

                <div class="postbox-container">
                    <?php do_meta_boxes( $settings_hook , 'primary' , null ) ; ?>
                </div>

                <div class="postbox-container">
                    <?php do_meta_boxes( $settings_hook , 'secondary' , null ) ; ?>
                </div>
            </div>
        </div>

        <script type="text/javascript">
            jQuery( document ).ready( function ( $ ) {
                $( '.if-js-closed' ).removeClass( 'if-js-closed' ).addClass( 'closed' ) ;
                postboxes.add_postbox_toggles( '<?php echo $settings_hook ; ?>' ) ;
            } ) ;
        </script>
        <?php
        $contents      = ob_get_contents() ;
        ob_end_clean() ;
        return $contents ;
    }

    public static function fs_affiliates_filter_fields( $fileld_name ) {
        $report_based    = fs_affiliates_get_report_based_on() ;
        $selected_filter = ( isset( $_REQUEST[ "$fileld_name" ] )) ? $_REQUEST[ "$fileld_name" ] : '' ;
        ?>
        <select name="<?php echo $fileld_name ?>" id="<?php echo $fileld_name ?>">
            <?php foreach ( $report_based as $eachkey => $eachlabel ) { ?>
                <option value="<?php echo $eachkey ; ?>" <?php if ( $selected_filter == $eachkey ) { ?> selected="" <?php } ?>><?php echo __( $eachlabel , FS_AFFILIATES_LOCALE ) ; ?></option>
            <?php } ?>
        </select>
        <input type="submit" id="referrals_reports" class="button" value="<?php echo __( 'Filter' , FS_AFFILIATES_LOCALE ) ; ?>">
        <?php
    }

    /**
     * Output the settings buttons.
     */
    public function output_buttons() {
        
    }

}

return new FS_Affiliates_Reports_Tab() ;

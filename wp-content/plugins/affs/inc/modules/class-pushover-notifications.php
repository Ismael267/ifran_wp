<?php
/**
 * Pushover Notifications
 */
if ( !defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( !class_exists( 'FS_Affiliates_Pushover_Notifications_module' ) ) {

    /**
     * Class FS_Affiliates_Pushover_Notifications_module
     */
    class FS_Affiliates_Pushover_Notifications_module extends FS_Affiliates_Modules {
        /*
         * Data
         */

        protected $data = array (
            'enabled'                 => 'no' ,
            'api_key'                 => '' ,
            'admin_user_key'          => '' ,
            'device_name'             => '' ,
            'admin_notifications'     => array () ,
            'new_visit_subject'       => '' ,
            'new_visit_message'       => '' ,
            'new_referral_subject'    => '' ,
            'new_referral_message'    => '' ,
            'new_payout_subject'      => '' ,
            'new_payout_message'      => '' ,
            'allow_affiliates'        => 'no' ,
            'affiliate_notifications' => array () ,
            'dashboard_menu_label'    => '' ,
            'pushover_key_label'      => '' ,
            'new_visit_label'         => '' ,
            'new_referral_label'      => '' ,
            'new_payout_label'        => '' ,
            'device_name_label'       => '' ,
            'sound_notification'      => 'bike'
                ) ;

        /**
         * Class Constructor
         */
        public function __construct() {
            $this->id    = 'pushover_notifications' ;
            $this->title = __( 'Pushover Notifications' , FS_AFFILIATES_LOCALE ) ;

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
                    'title' => __( 'General Settings' , FS_AFFILIATES_LOCALE ) ,
                    'id'    => 'pushover_notifications_general_options' ,
                ) ,
                array (
                    'title'   => __( 'API Token/Key' , FS_AFFILIATES_LOCALE ) ,
                    'desc'    => sprintf( __( 'To Obtain a API Key please visit %s' , FS_AFFILIATES_LOCALE ) , '<a href="https://pushover.net/apps/build">https://pushover.net/apps/build</a>' ) ,
                    'id'      => $this->get_field_key( 'api_key' ) ,
                    'type'    => 'text' ,
                    'default' => '' ,
                ) ,
                array (
                    'title'   => __( "Admin's User Key" , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->get_field_key( 'admin_user_key' ) ,
                    'desc'    => sprintf( __( "Site Admin's User Key. Please check %s for more info" , FS_AFFILIATES_LOCALE ) , '<a href="https://pushover.net/faq#overview-what">https://pushover.net/faq#overview-what</a>' ) ,
                    'type'    => 'text' ,
                    'default' => '' ,
                ) ,
                array (
                    'title'   => __( 'Device Name' , FS_AFFILIATES_LOCALE ) ,
                    'desc'    => __( 'The Device to which the Pushover Notification has to be sent.' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->get_field_key( 'device_name' ) ,
                    'type'    => 'text' ,
                    'default' => '' ,
                ) ,
                array (
                    'title'   => __( 'Admin Notifications' , FS_AFFILIATES_LOCALE ) ,
                    'desc'    => __( 'Affiliate Actions for which Site Admin will receive Pushover Notification' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->get_field_key( 'admin_notifications' ) ,
                    'class'   => 'fs_affiliates_select2' ,
                    'type'    => 'multiselect' ,
                    'default' => array ( 'visit' , 'referral' , 'payout' ) ,
                    'options' => array (
                        'visit'    => __( 'Visit' , FS_AFFILIATES_LOCALE ) ,
                        'referral' => __( 'Referral' , FS_AFFILIATES_LOCALE ) ,
                        'payout'   => __( 'Payout' , FS_AFFILIATES_LOCALE )
                    ) ,
                ) ,
                array (
                    'type' => 'sectionend' ,
                    'id'   => 'pushover_notifications_general_options' ,
                ) ,
                array (
                    'type'  => 'title' ,
                    'title' => __( 'Notification Settings' , FS_AFFILIATES_LOCALE ) ,
                    'id'    => 'pushover_notifications_options' ,
                ) ,
                array (
                    'title'   => __( "Visit Subject" , FS_AFFILIATES_LOCALE ) ,
                    'class'   => 'fs_affiliates_new_visit_notification_fields' ,
                    'id'      => $this->get_field_key( 'new_visit_subject' ) ,
                    'type'    => 'text' ,
                    'default' => 'New Visit Created' ,
                ) ,
                array (
                    'title'   => __( 'Visit Message' , FS_AFFILIATES_LOCALE ) ,
                    'class'   => 'fs_affiliates_new_visit_notification_fields' ,
                    'id'      => $this->get_field_key( 'new_visit_message' ) ,
                    'type'    => 'wpeditor' ,
                    'default' => 'A New Visit has been created for {affiliate_name}' ,
                ) ,
                array (
                    'title'   => __( "Referral Subject" , FS_AFFILIATES_LOCALE ) ,
                    'class'   => 'fs_affiliates_new_referral_notification_fields' ,
                    'id'      => $this->get_field_key( 'new_referral_subject' ) ,
                    'type'    => 'text' ,
                    'default' => 'New Referral Created' ,
                ) ,
                array (
                    'title'   => __( 'Referral Message' , FS_AFFILIATES_LOCALE ) ,
                    'class'   => 'fs_affiliates_new_referral_notification_fields' ,
                    'id'      => $this->get_field_key( 'new_referral_message' ) ,
                    'type'    => 'wpeditor' ,
                    'default' => 'A New Referral has been created for {affiliate_name}' ,
                ) ,
                array (
                    'title'   => __( "Payout Subject" , FS_AFFILIATES_LOCALE ) ,
                    'class'   => 'fs_affiliates_new_payout_notification_fields' ,
                    'id'      => $this->get_field_key( 'new_payout_subject' ) ,
                    'type'    => 'text' ,
                    'default' => 'New Payout Created' ,
                ) ,
                array (
                    'title'   => __( 'Payout Message' , FS_AFFILIATES_LOCALE ) ,
                    'class'   => 'fs_affiliates_new_payout_notification_fields' ,
                    'id'      => $this->get_field_key( 'new_payout_message' ) ,
                    'type'    => 'wpeditor' ,
                    'default' => 'A New Payout has been made on {site_name}' ,
                ) ,
                array (
                    'type' => 'sectionend' ,
                    'id'   => 'pushover_notifications_options' ,
                ) ,
                array (
                    'type'  => 'title' ,
                    'title' => __( 'Affiliate Dashboard Settings' , FS_AFFILIATES_LOCALE ) ,
                    'id'    => 'affiliate_dashboard_options' ,
                ) ,
                array (
                    'title'   => __( 'Allow Affiliates to Opt-In for Pushover Notifications' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->get_field_key( 'allow_affiliates' ) ,
                    'type'    => 'checkbox' ,
                    'default' => 'no' ,
                ) ,
                array (
                    'title'   => __( 'Affiliate Notifications' , FS_AFFILIATES_LOCALE ) ,
                    'desc'    => __( 'When enabled, affiliates, can configure their User Key from their Affiliate Dashboard' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->get_field_key( 'affiliate_notifications' ) ,
                    'class'   => 'fs_affiliates_select2' ,
                    'type'    => 'multiselect' ,
                    'default' => array ( 'visit' , 'referral' , 'payout' ) ,
                    'options' => array (
                        'visit'    => __( 'Visit' , FS_AFFILIATES_LOCALE ) ,
                        'referral' => __( 'Referral' , FS_AFFILIATES_LOCALE ) ,
                        'payout'   => __( 'Payout' , FS_AFFILIATES_LOCALE )
                    ) ,
                ) ,
                array (
                    'title'   => __( 'Affiliate Dashboard Menu Label' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->get_field_key( 'dashboard_menu_label' ) ,
                    'type'    => 'text' ,
                    'default' => 'Pushover Notifications' ,
                ) ,
                array (
                    'title'   => __( 'Affiliate Pushover Key Label' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->get_field_key( 'pushover_key_label' ) ,
                    'type'    => 'text' ,
                    'default' => 'Your Pushover Key' ,
                ) ,
                array (
                    'title'   => __( 'Affiliate New Visit Label' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->get_field_key( 'new_visit_label' ) ,
                    'type'    => 'text' ,
                    'default' => 'Receive Notifications for New Visits' ,
                ) ,
                array (
                    'title'   => __( 'Affiliate New Referral Label' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->get_field_key( 'new_referral_label' ) ,
                    'type'    => 'text' ,
                    'default' => 'Receive Notifications for New Referals' ,
                ) ,
                array (
                    'title'   => __( 'Affiliate New Payout Label' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->get_field_key( 'new_payout_label' ) ,
                    'type'    => 'text' ,
                    'default' => 'Receive Notifications for New Payouts' ,
                ) ,
                array (
                    'title'   => __( 'Device Name Label' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->get_field_key( 'device_name_label' ) ,
                    'type'    => 'text' ,
                    'default' => 'Device Name' ,
                ) ,
                array (
                    'type' => 'sectionend' ,
                    'id'   => 'affiliate_dashboard_options' ,
                ) ,
                array (
                    'type'  => 'title' ,
                    'title' => __( 'Sound Settings' , FS_AFFILIATES_LOCALE ) ,
                    'id'    => 'sound_notifications_options' ,
                ) ,
                array (
                    'title'   => __( 'Pushover Notification Tune' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->get_field_key( 'sound_notification' ) ,
                    'type'    => 'select' ,
                    'default' => 'pushover' ,
                    'options' => fs_affiliates_get_pushover_sound_notifications() ,
                ) ,
                array (
                    'type' => 'sectionend' ,
                    'id'   => 'sound_notifications_options' ,
                ) ,
                array (
                    'type'  => 'title' ,
                    'title' => __( 'Test Pushover Notification' , FS_AFFILIATES_LOCALE ) ,
                    'id'    => 'test_notifications_options' ,
                ) ,
                array (
                    'type' => 'test_notifications' ,
                ) ,
                array (
                    'type' => 'sectionend' ,
                    'id'   => 'test_notifications_options' ,
                ) ,
                    ) ;
        }

        /**
         * Actions
         */
        public function actions() {
            add_action( 'fs_affiliates_new_referral' , array ( 'FS_Affiliates_Pushover_Handler' , 'send_referral_pushover_notifications' ) , 10 , 2 ) ;
            add_action( 'fs_affiliates_new_visit' , array ( 'FS_Affiliates_Pushover_Handler' , 'send_visit_pushover_notifications' ) , 10 , 2 ) ;
            add_action( 'fs_affiliates_new_payout' , array ( 'FS_Affiliates_Pushover_Handler' , 'send_payout_pushover_notifications' ) , 10 , 2 ) ;
        }

        /*
         * Admin action
         */

        public function admin_action() {
            add_action( $this->plugin_slug . '_admin_field_test_notifications' , array ( $this , 'test_notifications' ) ) ;
        }

        /**
         * Frontend Actions
         */
        public function frontend_action() {
            add_filter( 'fs_affiliates_frontend_dashboard_menu' , array ( $this , 'pushover_notification_menu' ) , 13 , 3 ) ;
            add_action( 'fs_affiliates_dashboard_content_pushover_notifications' , array ( $this , 'display_dashboard_content' ) , 10 , 3 ) ;
        }

        /*
         * Custom Dashboard Menu
         */

        public function pushover_notification_menu( $menus , $user_id , $affiliate_id ) {
            if ( $this->allow_affiliates == 'no' )
                return $menus ;

            $menus[ 'pushover_notifications' ] = array ( 'label' => $this->dashboard_menu_label , 'code' => 'fa-bell' ) ;

            return $menus ;
        }

        /*
         * Display Dashboard Content
         */

        public function display_dashboard_content( $user_id , $affiliate_id ) {

            if ( !empty( $_POST[ 'fs-affiliate-pushover-action' ] ) ) {
                try {
                    $nonce_value = isset( $_POST[ 'fs-affiliate-pushover-nonce' ] ) ? $_POST[ 'fs-affiliate-pushover-nonce' ] : null ;
                    if ( !wp_verify_nonce( $nonce_value , 'fs-affiliate-pushover' ) )
                        throw new Exception( __( "Invalid Request" , FS_AFFILIATES_LOCALE ) ) ;

                    $AffiliateData[ 'pushover_key' ] = $_POST[ 'pushover_key' ] ;
                    $AffiliateData[ 'device_name' ]  = $_POST[ 'device_name' ] ;

                    if ( in_array( 'visit' , $this->affiliate_notifications ) )
                        $AffiliateData[ 'visit_pushover' ] = isset( $_POST[ 'visit_pushover' ] ) ? 'yes' : 'no' ;

                    if ( in_array( 'referral' , $this->affiliate_notifications ) )
                        $AffiliateData[ 'referral_pushover' ] = isset( $_POST[ 'referral_pushover' ] ) ? 'yes' : 'no' ;

                    if ( in_array( 'payout' , $this->affiliate_notifications ) )
                        $AffiliateData[ 'payout_pushover' ] = isset( $_POST[ 'payout_pushover' ] ) ? 'yes' : 'no' ;


                    fs_affiliates_update_affiliate( $affiliate_id , $AffiliateData ) ;

                    do_action( 'fs_affiliates_profile_updated' , $affiliate_id ) ;
                    ?><div>
                        <span class="fs_affiliates_msg_success_post"><i class="fa fa-check"></i><?php _e( 'Pushover updated sucessfully' , FS_AFFILIATES_LOCALE ) ; ?></span>
                    </div><?php
                } catch ( Exception $ex ) {
                    ?><div>
                        <span class="fs_affiliates_msg_fails_post"><i class="fa fa-exclamation-triangle"></i><?php echo $e->getMessage() ; ?></span>
                    </div><?php
                }
            }

            $affiliate = new FS_Affiliates_Data( $affiliate_id ) ;
            ?><div class="fs_affiliates_form">
                <h2><?php _e( 'Pushover Notifications' , FS_AFFILIATES_LOCALE ) ?></h2>
                <form method="post" class="fs_affiliates_form">
                    <p class="affiliate-form-row">
                        <label><?php echo $this->pushover_key_label ; ?>&nbsp;</label>
                        <input type="text" class="affiliate-Input" name="pushover_key"  value="<?php echo $affiliate->pushover_key ; ?>"/>
                    </p>
                    <p class="affiliate-form-row">
                        <label><?php echo $this->device_name_label ; ?>&nbsp;</label>
                        <input type="text" class="affiliate-Input" name="device_name"  value="<?php echo $affiliate->device_name ; ?>"/>
                    </p>
                    <?php if ( in_array( 'visit' , $this->affiliate_notifications ) ) { ?>
                        <p class="affiliate-form-row">
                            <label class="fs_affiliates_label"><?php echo $this->new_visit_label ; ?>&nbsp;</label>
                            <input type="checkbox" class="affiliate-Input fs_affiliates_checkbox" name="visit_pushover" value="yes" <?php checked( $affiliate->visit_pushover , 'yes' ) ; ?>  />
                        </p>
                    <?php } if ( in_array( 'referral' , $this->affiliate_notifications ) ) { ?>
                        <p class="affiliate-form-row">
                            <label class="fs_affiliates_label"><?php echo $this->new_referral_label ; ?>&nbsp;</label>
                            <input type="checkbox" class="affiliate-Input fs_affiliates_checkbox" name="referral_pushover" value="yes" <?php checked( $affiliate->referral_pushover , 'yes' ) ; ?> />
                        </p>
                    <?php } if ( in_array( 'payout' , $this->affiliate_notifications ) ) { ?>
                        <p class="affiliate-form-row">
                            <label class="fs_affiliates_label"><?php echo $this->new_payout_label ; ?>&nbsp;</label>
                            <input type="checkbox" class="affiliate-Input fs_affiliates_checkbox" name="payout_pushover" value="yes" <?php checked( $affiliate->payout_pushover , 'yes' ) ; ?> />
                        </p>
                    <?php } ?>
                    <p class="affiliate-form-row">
                        <?php wp_nonce_field( 'fs-affiliate-pushover' , 'fs-affiliate-pushover-nonce' ) ; ?>
                        <input type="hidden" name="fs-affiliate-pushover-action" value="pushover" />
                        <button type="submit" class="fs_affiliates_form_save button"><?php esc_html_e( 'Update Preferences' , FS_AFFILIATES_LOCALE ) ; ?></button>
                    </p>
                </form>
            </div>
            <?php
        }

        /**
         * Test Notifications
         */
        public function test_notifications() {
            ?><tr valign="top">
                <th scope="row">
                    <label><?php _e( 'Test Notification' , FS_AFFILIATES_LOCALE ) ; ?></label>
                </th>
                <td>
                    <input type="button" class="fs_affiliates_test_pushover_notification" value="<?php _e( 'Send Test Notification' , FS_AFFILIATES_LOCALE ) ; ?>"/>
                </td>
            </tr>
            <?php
        }

    }

}

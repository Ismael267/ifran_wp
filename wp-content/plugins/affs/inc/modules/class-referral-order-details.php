<?php
/**
 * Referral Order Details
 */
if ( !defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( !class_exists( 'FS_Affiliates_Referral_Order_details' ) ) {

    /**
     * Class FS_Affiliates_Referral_Order_details
     */
    class FS_Affiliates_Referral_Order_details extends FS_Affiliates_Modules {
        /*
         * Data
         */

        protected $data = array (
            'enabled'                           => 'no' ,
            'allow_order_details'               => 'no' ,
            'allowed_affiliates_method'         => '1' ,
            'selected_affiliates'               => array () ,
            'display_order_id'                  => 'no' ,
            'display_order_amount'              => 'no' ,
            'display_order_date'                => 'no' ,
            'display_customer_name'             => 'no' ,
            'display_customer_email'            => 'no' ,
            'display_customer_phone'            => 'no' ,
            'display_customer_billing_address'  => 'no' ,
            'display_customer_shipping_address' => 'no' ,
            'display_coupon_used'               => 'no' ,
            'display_commission'                => 'no'
                ) ;

        /**
         * Class Constructor
         */
        public function __construct() {
            $this->id    = 'referral_order_details' ;
            $this->title = __( 'Referral Order Details' , FS_AFFILIATES_LOCALE ) ;

            parent::__construct() ;
        }

        /*
         * Plugin enabled
         */

        public function is_plugin_enabled() {
            $woocommerce = FS_Affiliates_Integration_Instances::get_integration_by_id( 'woocommerce' ) ;

            if ( $woocommerce->is_enabled() )
                return true ;

            return false ;
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
                    'title' => __( 'Referral Order Details' , FS_AFFILIATES_LOCALE ) ,
                    'id'    => 'referral_order_details_options' ,
                ) ,
                array (
                    'title'   => __( 'Allow Affiliates to View Order Details of the Referrals' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->plugin_slug . '_' . $this->id . '_allow_order_details' ,
                    'desc'    => __( 'When enabled, affiliates can view certain additional data about the orders placed by their referrals.' , FS_AFFILIATES_LOCALE ) ,
                    'type'    => 'checkbox' ,
                    'default' => 'no' ,
                ) ,
                array (
                    'title'   => __( 'Referral Order Details can be Viewed by' , FS_AFFILIATES_LOCALE ) ,
                    'desc'    => __( 'This option controls which affiliates will be able to view the additional order details' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->plugin_slug . '_' . $this->id . '_allowed_affiliates_method' ,
                    'type'    => 'select' ,
                    'default' => '1' ,
                    'class'   => 'fs_affiliates_allowed_affiliates_method' ,
                    'options' => array (
                        '1' => __( 'All Affiliates' , FS_AFFILIATES_LOCALE ) ,
                        '2' => __( 'Selected Affiliates' , FS_AFFILIATES_LOCALE ) ,
                    ) ,
                ) ,
                array (
                    'title'     => __( 'Selected Affiliates' , FS_AFFILIATES_LOCALE ) ,
                    'id'        => $this->plugin_slug . '_' . $this->id . '_selected_affiliates' ,
                    'type'      => 'ajaxmultiselect' ,
                    'class'     => 'fs_affiliates_selected_affiliate' ,
                    'list_type' => 'affiliates' ,
                    'action'    => 'fs_affiliates_search' ,
                    'default'   => array () ,
                ) ,
                array (
                    'title'   => __( 'Details Displayed to the Buyer' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->plugin_slug . '_' . $this->id . '_display_order_id' ,
                    'type'    => 'checkbox' ,
                    'default' => 'no' ,
                    'desc'    => __( 'Order ID' , FS_AFFILIATES_LOCALE ) ,
                ) ,
                array (
                    'title'   => '' ,
                    'id'      => $this->plugin_slug . '_' . $this->id . '_display_order_amount' ,
                    'type'    => 'checkbox' ,
                    'class'   => 'fs_affiliates_hidden_field_space' ,
                    'default' => 'no' ,
                    'desc'    => __( 'Order Amount' , FS_AFFILIATES_LOCALE ) ,
                ) ,
                array (
                    'title'   => '' ,
                    'id'      => $this->plugin_slug . '_' . $this->id . '_display_order_date' ,
                    'type'    => 'checkbox' ,
                    'class'   => 'fs_affiliates_hidden_field_space' ,
                    'default' => 'no' ,
                    'desc'    => __( 'Order Date' , FS_AFFILIATES_LOCALE ) ,
                ) ,
                array (
                    'title'   => '' ,
                    'id'      => $this->plugin_slug . '_' . $this->id . '_display_customer_name' ,
                    'type'    => 'checkbox' ,
                    'class'   => 'fs_affiliates_hidden_field_space' ,
                    'default' => 'no' ,
                    'desc'    => __( 'Customer Name' , FS_AFFILIATES_LOCALE ) ,
                ) ,
                array (
                    'title'   => '' ,
                    'id'      => $this->plugin_slug . '_' . $this->id . '_display_customer_email' ,
                    'type'    => 'checkbox' ,
                    'class'   => 'fs_affiliates_hidden_field_space' ,
                    'default' => 'no' ,
                    'desc'    => __( 'Customer Email' , FS_AFFILIATES_LOCALE ) ,
                ) ,
                array (
                    'title'   => '' ,
                    'id'      => $this->plugin_slug . '_' . $this->id . '_display_customer_phone' ,
                    'type'    => 'checkbox' ,
                    'class'   => 'fs_affiliates_hidden_field_space' ,
                    'default' => 'no' ,
                    'desc'    => __( 'Customer Phone' , FS_AFFILIATES_LOCALE ) ,
                ) ,
                array (
                    'title'   => '' ,
                    'id'      => $this->plugin_slug . '_' . $this->id . '_display_customer_billing_address' ,
                    'type'    => 'checkbox' ,
                    'class'   => 'fs_affiliates_hidden_field_space' ,
                    'default' => 'no' ,
                    'desc'    => __( 'Customer Billing Address' , FS_AFFILIATES_LOCALE ) ,
                ) ,
                array (
                    'title'   => '' ,
                    'id'      => $this->plugin_slug . '_' . $this->id . '_display_customer_shipping_address' ,
                    'type'    => 'checkbox' ,
                    'class'   => 'fs_affiliates_hidden_field_space' ,
                    'default' => 'no' ,
                    'desc'    => __( 'Customer Shipping Address' , FS_AFFILIATES_LOCALE ) ,
                ) ,
                array (
                    'title'   => '' ,
                    'id'      => $this->plugin_slug . '_' . $this->id . '_display_coupon_used' ,
                    'type'    => 'checkbox' ,
                    'class'   => 'fs_affiliates_hidden_field_space' ,
                    'default' => 'no' ,
                    'desc'    => __( 'Coupon Used' , FS_AFFILIATES_LOCALE ) ,
                ) ,
                array (
                    'title'   => '' ,
                    'id'      => $this->plugin_slug . '_' . $this->id . '_display_commission' ,
                    'type'    => 'checkbox' ,
                    'class'   => 'fs_affiliates_hidden_field_space' ,
                    'default' => 'no' ,
                    'desc'    => __( 'Affiliate Commission' , FS_AFFILIATES_LOCALE ) ,
                ) ,
                array (
                    'type' => 'sectionend' ,
                    'id'   => 'referral_order_details_options' ,
                ) ,
                    ) ;
        }

        /**
         * Frontend Actions
         */
        public function frontend_action() {
            if ( $this->allow_order_details == 'yes' ) {
                add_filter( 'fs_affiliates_order_link' , array ( $this , 'fs_affiliates_order_link' ) , 10 , 3 ) ;
                add_action( 'referral_order_details_table' , array ( $this , 'referral_order_details_table' ) , 10 , 2 ) ;
            }
        }

        public function fs_affiliates_order_link( $referral_name , $order_id , $affiliate_id ) {
            $allow = $this->enable_order_link( $affiliate_id ) ;
            if ( $allow && ($this->fs_display_order_details() || $this->fs_display_customer_details()) ) {
                $get_permalink = FS_AFFILIATES_PROTOCOL . $_SERVER[ 'HTTP_HOST' ] . $_SERVER[ 'REQUEST_URI' ] ;
                $permalink     = remove_query_arg( 'page_no' , $get_permalink ) ;
                $url           = add_query_arg( 'order_id' , $order_id , $permalink ) ;
                $referral_name = '<a href="' . esc_url( $url ) . '">' . $order_id . '</a>' ;
            }

            return $referral_name ;
        }

        public function enable_order_link( $affiliate_id ) {
            $return = false ;
            if ( $this->allowed_affiliates_method == '1' ) {
                $return = true ;
            } else {
                if ( in_array( $affiliate_id , $this->selected_affiliates ) ) {
                    $return = true ;
                }
            }
            return $return ;
        }

        public function referral_order_details_table( $user_id , $order_id ) {
            $order = wc_get_order( $order_id ) ;
            if ( !is_object( $order ) )
                return ;

            if ( $this->fs_display_order_details() )
                $this->fs_affiliate_order_detail_table( $order , $order_id ) ;

            if ( $this->fs_display_customer_details() )
                $this->fs_affiliate_customer_detail_table( $order , $order_id ) ;
        }

        public function fs_display_order_details() {
            if ( $this->display_order_id == 'no' && $this->display_order_amount == 'no' && $this->display_coupon_used == 'no' && $this->display_order_date == 'no' && $this->display_commission == 'no' )
                return false ;

            return true ;
        }

        public function fs_display_customer_details() {
            if ( $this->display_customer_name == 'no' && $this->display_customer_email == 'no' && $this->display_customer_phone == 'no' && $this->display_customer_billing_address == 'no' && $this->display_customer_shipping_address == 'no' )
                return false ;

            return true ;
        }

        public function fs_affiliate_order_detail_table( $order , $order_id ) {
            ob_start() ;
            ?>
            <div class="fs_affiliates_form">
                <h2><?php _e( 'Order Detail(s)' , FS_AFFILIATES_LOCALE ) ?></h2>
                <table class="fs_affiliates_order_detail_frontend_table">
                    <tbody>
                        <?php if ( $this->display_order_id == 'yes' ) { ?>
                            <tr>
                                <th>
                                    <?php _e( 'Order ID' , FS_AFFILIATES_LOCALE ) ; ?>
                                </th>
                                <td>
                                    <?php echo $order_id ; ?>
                                </td>
                            </tr>
                        <?php } ?>
                        <?php if ( $this->display_order_amount == 'yes' ) { ?>
                            <tr>
                                <th>
                                    <?php _e( 'Order Amount' , FS_AFFILIATES_LOCALE ) ; ?>
                                </th>
                                <td>
                                    <?php echo fs_affiliates_price( $order->get_total() ) ; ?>
                                </td>
                            </tr>
                        <?php } ?>
                        <?php if ( $this->display_coupon_used == 'yes' ) { ?>
                            <tr>
                                <th>
                                    <?php _e( 'Coupon Used' , FS_AFFILIATES_LOCALE ) ; ?>
                                </th>
                                <td>
                                    <?php echo implode( ',' , $order->get_coupon_codes() ) == '' ? '-' : implode( ',' , $order->get_coupon_codes() ) ; ?>
                                </td>
                            </tr>
                        <?php } ?>
                        <?php if ( $this->display_order_date == 'yes' ) { ?>
                            <tr>
                                <th>
                                    <?php _e( 'Order Date' , FS_AFFILIATES_LOCALE ) ; ?>
                                </th>
                                <td>
                                    <?php echo $order->get_date_created() ; ?>
                                </td>
                            </tr>
                        <?php } ?>
                        <?php
                        if ( $this->display_commission == 'yes' ) {
                            $args            = array (
                                'post_type'   => 'fs-referrals' ,
                                'post_status' => array ( 'fs_unpaid' , 'fs_paid' ) ,
                                'numberposts' => -1 ,
                                'fields'      => 'ids' ,
                                'meta_query'  => array (
                                    array (
                                        'key'   => 'reference' ,
                                        'value' => $order_id ,
                                    )
                                ) ) ;
                            $posts           = get_posts( $args ) ;
                            $referral_amount = fs_affiliates_check_is_array( $posts ) ? get_post_meta( $posts[ 0 ] , 'amount' , true ) : 0 ;
                            ?>
                            <tr>
                                <th>
                                    <?php _e( 'Affiliate Commission' , FS_AFFILIATES_LOCALE ) ; ?>
                                </th>
                                <td>
                                    <?php echo fs_affiliates_price( $referral_amount ) ; ?>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <?php
            echo ob_get_clean() ;
        }

        public function fs_affiliate_customer_detail_table( $order , $order_id ) {
            ob_start() ;
            ?>
            <div class="fs_affiliates_form">
                <h2><?php _e( 'Customer Detail(s)' , FS_AFFILIATES_LOCALE ) ?></h2>
                <table class="fs_affiliates_customer_detail_frontend_table">
                    <tbody>
                        <?php if ( $this->display_customer_name == 'yes' ) { ?>
                            <tr>
                                <th>
                                    <?php _e( 'Customer Name' , FS_AFFILIATES_LOCALE ) ; ?>
                                </th>
                                <td>
                                    <?php echo $order->get_billing_first_name() . ' ' . $order->get_billing_last_name() ; ?>
                                </td>
                            </tr>
                        <?php } ?>
                        <?php if ( $this->display_customer_email == 'yes' ) { ?>
                            <tr>
                                <th>
                                    <?php _e( 'Customer Email' , FS_AFFILIATES_LOCALE ) ; ?>
                                </th>
                                <td>
                                    <?php echo $order->get_billing_email() == '' ? '-' : $order->get_billing_email() ; ?>
                                </td>
                            </tr>
                        <?php } ?>
                        <?php if ( $this->display_customer_phone == 'yes' ) { ?>
                            <tr>
                                <th>
                                    <?php _e( 'Customer Phone' , FS_AFFILIATES_LOCALE ) ; ?>
                                </th>
                                <td>
                                    <?php echo $order->get_billing_phone() == '' ? '-' : $order->get_billing_phone() ; ?>
                                </td>
                            </tr>
                        <?php } ?>
                        <?php if ( $this->display_customer_billing_address == 'yes' ) { ?>
                            <tr>
                                <th>
                                    <?php _e( 'Customer Billing Address' , FS_AFFILIATES_LOCALE ) ; ?>
                                </th>
                                <td>
                                    <?php
                                    $Billaddrs1  = $order->get_billing_address_1() == '' ? '-' : $order->get_billing_address_1() ;
                                    $Billaddrs2  = $order->get_billing_address_2() == '' ? '-' : $order->get_billing_address_2() ;
                                    $Billcity    = $order->get_billing_city() == '' ? '-' : $order->get_billing_city() ;
                                    $BillState   = $order->get_billing_state() == '' ? '-' : $order->get_billing_state() ;
                                    $BillPincode = $order->get_billing_postcode() == '' ? '-' : $order->get_billing_postcode() ;
                                    $BillCountry = $order->get_billing_country() == '' ? '-' : $order->get_billing_country() ;
                                    ?>
                                    <address>
                                        <?php echo $Billaddrs1 . '<br>' . $Billaddrs2 . '<br>' . $Billcity . '<br>' . $BillState . '<br>' . $BillPincode . '<br>' . $BillCountry ; ?>
                                    </address>
                                </td>
                            </tr>
                        <?php } ?>
                        <?php
                        if ( $this->display_customer_shipping_address == 'yes' ) {
                            ?>
                            <tr>
                                <th>
                                    <?php _e( 'Customer Shipping Address' , FS_AFFILIATES_LOCALE ) ; ?>
                                </th>
                                <td>
                                    <?php
                                    $Shipaddrs1  = $order->get_shipping_address_1() == '' ? '-' : $order->get_shipping_address_1() ;
                                    $Shipaddrs2  = $order->get_shipping_address_2() == '' ? '-' : $order->get_shipping_address_2() ;
                                    $Shipcity    = $order->get_shipping_city() == '' ? '-' : $order->get_shipping_city() ;
                                    $ShipState   = $order->get_shipping_state() == '' ? '-' : $order->get_shipping_state() ;
                                    $ShipPincode = $order->get_shipping_postcode() == '' ? '-' : $order->get_shipping_postcode() ;
                                    $ShipCountry = $order->get_shipping_country() == '' ? '-' : $order->get_shipping_country() ;
                                    ?>
                                    <address>
                                        <?php echo $Shipaddrs1 . '<br>' . $Shipaddrs2 . '<br>' . $Shipcity . '<br>' . $ShipState . '<br>' . $ShipPincode . '<br>' . $ShipCountry ; ?>
                                    </address>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <?php
            echo ob_get_clean() ;
        }

    }

}
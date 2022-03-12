<?php

/**
 * WooCommerce Referral Restriction
 */
if ( !defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( !class_exists( 'FS_Affiliates_WC_Referral_Restriction' ) ) {

    /**
     * Class FS_Affiliates_WC_Referral_Restriction
     */
    class FS_Affiliates_WC_Referral_Restriction extends FS_Affiliates_Modules {
        /*
         * Data
         */

        protected $data = array (
            'enabled'         => 'no' ,
            'stop_commission' => '1' ,
            'order_count'     => '' ,
            'amount_spent'    => '' ,
            'order_amount'    => '' ,
            'order_status'    => array ()
                ) ;

        /**
         * Class Constructor
         */
        public function __construct() {
            $this->id    = 'wc_referral_restriction' ;
            $this->title = __( 'WooCommerce Referral Restriction' , FS_AFFILIATES_LOCALE ) ;

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
                    'title' => __( 'WooCommerce Referral Restrictions' , FS_AFFILIATES_LOCALE ) ,
                    'id'    => 'wc_referral_restriction_options' ,
                ) ,
                array (
                    'title'   => __( 'Stop Awarding Commission to Affiliate after' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->plugin_slug . '_' . $this->id . '_stop_commission' ,
                    'type'    => 'select' ,
                    'default' => '1' ,
                    'options' => array (
                        '1' => __( 'Number of Successful Orders' , FS_AFFILIATES_LOCALE ) ,
                        '2' => __( 'Total Amount Spent on Site' , FS_AFFILIATES_LOCALE ) ,
                        '3' => __( 'Amount Spent on Current Order' , FS_AFFILIATES_LOCALE ) ,
                    ) ,
                    'desc'    => __( '<b>Number of Successful Orders – </b> Affiliate will not receive commission for future orders if the referred person has placed more than the specified number of orders'
                            . '       </br><b>Total Amount Spent on Site – </b> Affiliate will not receive commission for future orders if the referred person has purchased more than the amount specified'
                            . '       </br><b>Amount Spent on Current Order – </b>Affiliate will not receive commission for future orders if the reffered person has spent more than a specified amount in a single order.' , FS_AFFILIATES_LOCALE ) ,
                ) ,
                array (
                    'title'             => __( 'Number of Orders' , FS_AFFILIATES_LOCALE ) ,
                    'id'                => $this->plugin_slug . '_' . $this->id . '_order_count' ,
                    'class'             => 'fs_affiliates_order_count' ,
                    'type'              => 'number' ,
                    'custom_attributes' => array (
                        'min' => 0
                    ) ,
                    'default'           => '' ,
                ) ,
                array (
                    'title'             => __( 'Amount Spent' , FS_AFFILIATES_LOCALE ) ,
                    'id'                => $this->plugin_slug . '_' . $this->id . '_amount_spent' ,
                    'type'              => 'text' ,
                    'class'             => 'fs_affiliates_amount_spent' ,
                    'default'           => '' ,
                ) ,
                array (
                    'title'             => __( 'Order Amount' , FS_AFFILIATES_LOCALE ) ,
                    'id'                => $this->plugin_slug . '_' . $this->id . '_order_amount' ,
                    'type'              => 'text' ,
                    'class'             => 'fs_affiliates_order_amount' ,
                    'default'           => '' ,
                ) ,
                array (
                    'title'   => __( 'Order Status to be Considered to Stop Awarding Commission' , FS_AFFILIATES_LOCALE ) ,
                    'type'    => 'multiselect' ,
                    'class'   => 'fs_affiliates_select2' ,
                    'default' => array ( 'processing' , 'completed' ) ,
                    'options' => fp_affiliates_get_order_statuses() ,
                    'id'      => $this->plugin_slug . '_' . $this->id . '_order_status' ,
                ) ,
                array (
                    'type' => 'sectionend' ,
                    'id'   => 'wc_referral_restriction_options' ,
                ) ,
                    ) ;
        }

        /*
         * Action
         */

        public function actions() {
            add_filter( 'fs_affiliates_is_restricted_referral' , array ( $this , 'is_restricted_referral' ) , 10 , 3 ) ;
        }

        /*
         * Check If Product is allowed to award commission
         */

        public function is_restricted_referral( $bool , $UserId , $OrderObj ) {
            global $wpdb ;
            $AmountSpentInSite = array () ;
            $Status            = 'wc-' . implode( ',wc-' , $this->order_status ) ;
            $OrderIds          = $wpdb->get_results( "SELECT posts.ID
			FROM $wpdb->posts as posts
			LEFT JOIN {$wpdb->postmeta} AS meta ON posts.ID = meta.post_id
			WHERE   meta.meta_key       = '_customer_user'
			AND     posts.post_type     IN ('" . implode( "','" , wc_get_order_types( 'order-count' ) ) . "')
			AND     posts.post_status   IN ('$Status')
			AND     meta_value          = $UserId
		" , ARRAY_A ) ;

            if ( $this->stop_commission == '1' ) {
                $NoofSuccessfullOrders = count( $OrderIds ) ;
                if ( $this->order_count > $NoofSuccessfullOrders )
                    return true ;
            } elseif ( $this->stop_commission == '2' ) {
                if ( !fs_affiliates_check_is_array( $OrderIds ) )
                    return false ;

                foreach ( $OrderIds as $Key ) {
                    $AmountSpentInSite[] = get_post_meta( $Key[ 'ID' ] , '_order_total' , true ) ;
                }
                if ( !fs_affiliates_check_is_array( $AmountSpentInSite ) )
                    return false ;

                $TotalAmountSpentInSite = array_sum( $AmountSpentInSite ) ;
                if ( $this->amount_spent > $TotalAmountSpentInSite )
                    return true ;
            } else {
                $OrderTotal = $OrderObj->get_total() ;
                if ( $this->order_amount >= $OrderTotal )
                    return true ;
            }
            return false ;
        }

    }

}

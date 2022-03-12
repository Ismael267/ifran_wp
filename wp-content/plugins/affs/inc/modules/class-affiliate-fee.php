<?php

/**
 * Affiliate Fee
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'FS_Affiliates_Fee' ) ) {

    /**
     * Class FS_Affiliates_Fee
     */
    class FS_Affiliates_Fee extends FS_Affiliates_Modules {

        protected static $transient_data ;
        protected static $add_to_cart_transient ;
        protected static $redirect_to_cart      = false ;
        protected static $aff_inactive_statuses = array( 'fs_pending_payment' , 'fs_suspended' ) ;


        /*
         * Data
         */
        protected $data = array(
            'enabled'                          => 'no' ,
            'affiliate_fee_product'            => '' ,
            'aff_fee_product_purchase_msg'     => '' ,
            'aff_fee_product_purchase_msg_reg' => '' ,
            'aff_fee_single_product_page_msg'  => '' ,
            'aff_fee_product_renew_msg'        => '' ,
            'aff_fee_product_pending_msg'      => '' ,
            'aff_fee_product_paused_msg'       => '' ,
            'aff_fee_product_overdue_msg'      => '' ,
            'aff_fee_product_suspended_msg'    => '' ,
            'aff_fee_product_cancelled_msg'    => '' ,
            'aff_fee_product_expired_msg'      => '' ,
                ) ;

        /**
         * Class Constructor
         */
        public function __construct() {
            $this->id    = 'affiliate_fee' ;
            $this->title = __( 'Affiliate Fee' , FS_AFFILIATES_LOCALE ) ;

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

        public function charge_affiliate_fee_by_recurring() {
            $sumosubscriptions = FS_Affiliates_Integration_Instances::get_integration_by_id( 'sumo_subscriptions' ) ;

            if ( $sumosubscriptions->is_enabled() && 'yes' === $sumosubscriptions->charge_affiliate_fee_by_recurring )
                return true ;

            return false ;
        }

        /*
         * Get settings link
         */

        public function settings_link() {
            return add_query_arg( array( 'page' => 'fs_affiliates' , 'tab' => 'modules' , 'section' => $this->id ) , admin_url( 'admin.php' ) ) ;
        }

        /*
         * Front End Action
         */

        public function frontend_action() {
            add_action( 'wp' , array( $this , 'show_frontend_notice' ) , 10 ) ;
            add_action( 'woocommerce_before_add_to_cart_button' , array( $this , 'set_affiliate_fee_product' ) , 10 ) ;
            add_filter( 'woocommerce_is_sold_individually' , array( $this , 'is_sold_individually' ) , 20 , 2 ) ;
            add_filter( 'woocommerce_add_to_cart_validation' , array( $this , 'validate_add_to_cart' ) , 20 , 6 ) ;
            add_filter( 'woocommerce_add_cart_item' , array( $this , 'add_cart_item_data' ) , 20 , 2 ) ;
            add_action( 'woocommerce_before_calculate_totals' , array( $this , 'refresh_cart' ) , 20 ) ;
            add_action( 'wp_loaded' , array( $this , 'redirect_to_cart' ) , 20 ) ;
            add_filter( 'fs_affiliates_render_dashboard' , array( $this , 'prevent_rendering_dashboard' ) , 20 , 2 ) ;
        }

        /*
         * Both Front End and Back End Action
         */

        public function actions() {
            add_filter( 'fs_affiliate_status_while_submit_application' , array( $this , 'while_submitting_register_form' ) , 10 , 2 ) ;
            add_action( 'fs_affiliates_frontend_register_form_submitted' , array( $this , 'register_form_submitted' ) ) ;
            add_filter( 'fs_affiliates_create_new_referral' , array( $this , 'prevent_new_referral' ) , 20 , 3 ) ;
            add_filter( 'fs_email_verified_affiliate_status' , array( $this , 'set_email_verified_affiliate_status' ) , 20 , 2 ) ;
            add_action( 'woocommerce_checkout_update_order_meta' , array( $this , 'update_order_meta' ) , 20 , 2 ) ;
            add_action( 'woocommerce_order_status_changed' , array( $this , 'payment_complete' ) , 20 , 3 ) ;
            add_action( 'woocommerce_product_query' , array( $this , 'hide_aff_fee_product' ) , 20 ) ;

            add_action( 'sumosubscriptions_subscription_created' , array( $this , 'subscription_actions' ) , 20 , 2 ) ;
            add_action( 'sumosubscriptions_active_subscription' , array( $this , 'subscription_actions' ) , 20 , 2 ) ;
            add_action( 'sumosubscriptions_pause_subscription' , array( $this , 'subscription_actions' ) , 20 , 2 ) ;
            add_action( 'sumosubscriptions_cancel_subscription' , array( $this , 'subscription_actions' ) , 20 , 2 ) ;
            add_action( 'fs_affiliates_before_register_form' , array( $this , 'show_affiliate_fee_notice' ) ) ;
        }

        /**
         * show affiliate fee notice
         */
        public static function show_affiliate_fee_notice() {
            $product_id  = FS_Affiliates_Module_Instances::get_module_by_id( 'affiliate_fee' )->affiliate_fee_product[ 0 ] ;
            $message     = FS_Affiliates_Module_Instances::get_module_by_id( 'affiliate_fee' )->aff_fee_product_purchase_msg_reg ;
            $product_url = '<a style="color:#000FFF;" href="' . esc_url_raw( get_permalink( $product_id ) ) . '"> ' . get_the_title( $product_id ) . '</a>' ;
            $message     = str_replace( '{product_url}' , $product_url , $message ) ;
            FS_Affiliates_Form_Handler::show_error( $message ) ;
        }

        /*
         * Admin Actions
         */

        public function admin_action() {
            add_filter( 'fs_get_affiliates_status_to_display_in_edit_page' , array( $this , 'set_affiliate_status' ) , 10 , 2 ) ;
        }

        /*
         * Get settings options array
         */

        public function settings_options_array() {
            $return = array(
                array(
                    'type'  => 'title' ,
                    'title' => __( 'Affiliate Fee Product Settings' , FS_AFFILIATES_LOCALE ) ,
                    'id'    => 'fs_affiliates_fee_product_options' ,
                ) ,
                array(
                    'title'     => __( 'Select Affiliate Fee Product' , FS_AFFILIATES_LOCALE ) ,
                    'id'        => $this->plugin_slug . '_' . $this->id . '_affiliate_fee_product' ,
                    'type'      => 'ajaxsingleselect' ,
                    'list_type' => 'products' ,
                    'action'    => 'fs_affiliates_aff_fee_products_search' ,
                    'default'   => '' ,
                ) ,
                array(
                    'type' => 'sectionend' ,
                    'id'   => 'fs_affiliates_fee_product_options' ,
                ) ,
                array(
                    'type'  => 'title' ,
                    'title' => __( 'Affiliate Fee Single Product Page Message Settings' , FS_AFFILIATES_LOCALE ) ,
                    'id'    => 'fs_affiliates_single_product_msg_options' ,
                ) ,
                array(
                    'title'   => __( 'Message to be Displayed in Single Product Page' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->plugin_slug . '_' . $this->id . '_aff_fee_single_product_page_msg' ,
                    'type'    => 'text' ,
                    'default' => 'Pay Affiliate Fee by purchasing this product to become an Affiliate on this Site' ,
                ) ,
                array(
                    'type' => 'sectionend' ,
                    'id'   => 'fs_affiliates_single_product_msg_options' ,
                ) ,
                array(
                    'type'  => 'title' ,
                    'title' => __( 'Dashboard Message Settings' , FS_AFFILIATES_LOCALE ) ,
                    'id'    => 'fs_affiliates_dashboard_msg_options' ,
                ) ,
                array(
                    'title'   => __( 'Affiliate Fee Product Purchase Message' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->plugin_slug . '_' . $this->id . '_aff_fee_product_purchase_msg' ,
                    'type'    => 'text' ,
                    'default' => 'To become an Affiliate on this Site, pay Affiliate Fee by purchasing the product {product_url)' ,
                ) ,
                    ) ;

            if ( $this->charge_affiliate_fee_by_recurring() ) {
                $return = array_merge( $return , array(
                    array(
                        'title'   => __( 'Affiliate Fee Product Renew Message' , FS_AFFILIATES_LOCALE ) ,
                        'id'      => $this->plugin_slug . '_' . $this->id . '_aff_fee_product_renew_msg' ,
                        'type'    => 'text' ,
                        'default' => 'Your Affiliate Fee renewal has been generated. To pay for the renewal fee {click_here}' ,
                    ) ,
                    array(
                        'title'   => __( 'Affiliate Fee Product Pending Message' , FS_AFFILIATES_LOCALE ) ,
                        'id'      => $this->plugin_slug . '_' . $this->id . '_aff_fee_product_pending_msg' ,
                        'type'    => 'text' ,
                        'default' => 'Your payment for Affiliate Fee is Pending. Your Affiliate account will be Active once the payment is Processed' ,
                    ) ,
                    array(
                        'title'   => __( 'Affiliate Fee Product Paused Message' , FS_AFFILIATES_LOCALE ) ,
                        'id'      => $this->plugin_slug . '_' . $this->id . '_aff_fee_product_paused_msg' ,
                        'type'    => 'text' ,
                        'default' => 'Your subscription for Affiliate account has been Paused. {click_here} to view more details' ,
                    ) ,
                    array(
                        'title'   => __( 'Affiliate Fee Product Overdue Message' , FS_AFFILIATES_LOCALE ) ,
                        'id'      => $this->plugin_slug . '_' . $this->id . '_aff_fee_product_overdue_msg' ,
                        'type'    => 'text' ,
                        'default' => 'Your Affiliate Fee renewal is Overdue. {click_here} to make the payment for uninterrupted Affiliate access' ,
                    ) ,
                    array(
                        'title'   => __( 'Affiliate Fee Product Suspended Message' , FS_AFFILIATES_LOCALE ) ,
                        'id'      => $this->plugin_slug . '_' . $this->id . '_aff_fee_product_suspended_msg' ,
                        'type'    => 'text' ,
                        'default' => 'Your Affiliate Fee renewal is not completed so far and hence your Affiliate access is Suspended. {click_here} to make the payment to continue your Affiliate access' ,
                    ) ,
                    array(
                        'title'   => __( 'Affiliate Fee Product Cancelled Message' , FS_AFFILIATES_LOCALE ) ,
                        'id'      => $this->plugin_slug . '_' . $this->id . '_aff_fee_product_cancelled_msg' ,
                        'type'    => 'text' ,
                        'default' => 'Your subscription for Affiliate account has been Cancelled. To get Affiliate access again, pay Affiliate Fee by purchasing the product {product_url)' ,
                    ) ,
                    array(
                        'title'   => __( 'Affiliate Fee Product Expired Message' , FS_AFFILIATES_LOCALE ) ,
                        'id'      => $this->plugin_slug . '_' . $this->id . '_aff_fee_product_expired_msg' ,
                        'type'    => 'text' ,
                        'default' => 'Your subscription for Affiliate account is Expired. To get Affiliate access again, pay Affiliate Fee by purchasing the product {product_url)' ,
                    ) ) ) ;
            }

            $return = array_merge( $return , array(
                array(
                    'type' => 'sectionend' ,
                    'id'   => 'fs_affiliates_dashboard_msg_options' ,
                ) ,
                    ) ) ;

            $return = array_merge( $return , array(
                array(
                    'type'  => 'title' ,
                    'title' => esc_html__( 'Register Form Message Settings' , FS_AFFILIATES_LOCALE ) ,
                    'id'    => 'fs_affiliates_register_form_msg_options' ,
                ) ,
                array(
                    'title'   => esc_html__( 'Affiliate Fee Product Purchase Message' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->plugin_slug . '_' . $this->id . '_aff_fee_product_purchase_msg_reg' ,
                    'type'    => 'text' ,
                    'default' => 'You need to purchase the product {product_url} to become an affiliate after submitting the form' ,
                ) ,
                array(
                    'type' => 'sectionend' ,
                    'id'   => 'fs_affiliates_register_form_msg_options' ,
                ) ,
                    ) ) ;
            return $return ;
        }

        public function affiliate_to_pay_fee( $affiliate ) {
            $affiliate_id = is_callable( array( $affiliate , 'get_id' ) ) ? $affiliate->get_id() : $affiliate ;
            return in_array( get_post_meta( $affiliate_id , '_aff_fee_paying_as' , true ) , array( 'recurring' , 'onetime' ) ) ;
        }

        public function affiliate_to_pay_fee_as( $affiliate , $pay_as ) {
            $affiliate_id = is_callable( array( $affiliate , 'get_id' ) ) ? $affiliate->get_id() : $affiliate ;
            return $pay_as === get_post_meta( $affiliate_id , '_aff_fee_paying_as' , true ) ;
        }

        public function affiliate_fee_payable_product( $product = null ) {
            if ( ! is_object( WC()->session ) ) {
                return false ;
            }

            $session_data = WC()->session->get( 'fs_affiliate_fee_props' , null ) ;

            if ( $product && isset( $session_data[ 'affiliate_id' ] , $session_data[ 'product_id' ] ) && $this->affiliate_to_pay_fee( $session_data[ 'affiliate_id' ] ) && $session_data[ 'product_id' ] == $product ) {
                return true ;
            } else if ( isset( $_GET[ 'fs_status' ] , $_GET[ 'fs_aff_id' ] , $_GET[ 'fs_nonce' ] ) && 'fs_pending_payment' === $_GET[ 'fs_status' ] && wp_verify_nonce( $_GET[ 'fs_nonce' ] , '_fs_affiliates' ) ) {
                return true ;
            }
            return false ;
        }

        public function cart_contains_affiliate_fee_product() {
            if ( ! is_object( WC()->cart ) ) {
                return false ;
            }

            foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
                if ( ! empty( $cart_item[ 'fs_affiliates' ][ 'affiliate_fee' ][ 'affiliate_id' ] ) && is_numeric( $cart_item[ 'fs_affiliates' ][ 'affiliate_fee' ][ 'affiliate_id' ] ) ) {
                    return $cart_item[ 'fs_affiliates' ][ 'affiliate_fee' ] ;
                }
            }
            return false ;
        }

        public function affiliate_fee_payable_order( $order_id , $pay_as ) {
            $fee_props = get_post_meta( $order_id , 'fs_affiliate_fee_props' , true ) ;
            return ! empty( $fee_props[ 'affiliate_id' ] ) && $this->affiliate_to_pay_fee_as( $fee_props[ 'affiliate_id' ] , $pay_as ) ? $fee_props : false ;
        }

        public function while_submitting_register_form( $status , $meta_data ) {
            if ( ! empty( $this->affiliate_fee_product[ 0 ] ) && ($product = wc_get_product( $this->affiliate_fee_product[ 0 ] )) ) {

                if ( $this->charge_affiliate_fee_by_recurring() && sumo_is_subscription_product( $product->get_id() ) ) {
                    self::$transient_data = array(
                        'paying_as'  => 'recurring' ,
                        'product_id' => $product->get_id() ,
                            ) ;
                } else {
                    self::$transient_data = array(
                        'paying_as'  => 'onetime' ,
                        'product_id' => $product->get_id() ,
                            ) ;
                }

                if ( FS_Affiliates_Module_Instances::get_module_by_id( 'email_verification' )->is_enabled() || 'yes' === get_option( 'fs_affiliates_admin_approval_required' ) ) {
                    //Do nothing
                } else {
                    $status = 'fs_pending_payment' ;
                }
            }
            return $status ;
        }

        public function register_form_submitted( $affiliate_id ) {

            if ( ! empty( self::$transient_data[ 'product_id' ] ) ) {
                update_post_meta( $affiliate_id , '_aff_fee_paying_as' , self::$transient_data[ 'paying_as' ] ) ;
                update_post_meta( $affiliate_id , '_aff_fee_product' , self::$transient_data[ 'product_id' ] ) ;
            }
        }

        public function prevent_new_referral( $bool , $OrderId , $AffiliateId ) {
            $affiliate = new FS_Affiliates_Data( $AffiliateId ) ;

            if ( $this->affiliate_to_pay_fee( $affiliate ) && in_array( $affiliate->get_status() , self::$aff_inactive_statuses ) ) {
                $bool = false ;
            }
            return $bool ;
        }

        public function set_email_verified_affiliate_status( $status , $affiliate ) {
            if ( $this->affiliate_to_pay_fee( $affiliate ) ) {
                $status = 'fs_pending_payment' ;
            }
            return $status ;
        }

        public function set_affiliate_status( $statuses , $affiliate ) {
            if ( $this->affiliate_to_pay_fee_as( $affiliate , 'onetime' ) ) {
                $statuses = array() ;
                switch ( $affiliate->get_status() ) {
                    case 'fs_pending_payment':
                        $statuses[ 'fs_suspended' ]       = fs_affiliates_get_status_display( 'fs_suspended' , false ) ;
                        break ;
                    case 'fs_active':
                        $statuses[ 'fs_suspended' ]       = fs_affiliates_get_status_display( 'fs_suspended' , false ) ;
                        break ;
                    case 'fs_pending_approval':
                        $statuses[ 'fs_pending_payment' ] = fs_affiliates_get_status_display( 'fs_pending_payment' , false ) ;
                        break ;
                }
                $statuses[ $affiliate->get_status() ] = fs_affiliates_get_status_display( $affiliate->get_status() , false ) ;
            } else if ( $this->affiliate_to_pay_fee_as( $affiliate , 'recurring' ) ) {
                $statuses = array() ;
                switch ( $affiliate->get_status() ) {
                    case 'fs_pending_approval':
                        $statuses[ 'fs_pending_payment' ] = fs_affiliates_get_status_display( 'fs_pending_payment' , false ) ;
                        break ;
                }
                $statuses[ $affiliate->get_status() ] = fs_affiliates_get_status_display( $affiliate->get_status() , false ) ;
            }
            return $statuses ;
        }

        public function set_affiliate_fee_product() {
            global $product ;

            if ( $this->affiliate_fee_payable_product() ) {
                WC()->session->set( 'fs_affiliate_fee_props' , array(
                    'affiliate_id' => absint( $_GET[ 'fs_aff_id' ] ) ,
                    'product_id'   => $product->get_id() ,
                    'paying_as'    => get_post_meta( $_GET[ 'fs_aff_id' ] , '_aff_fee_paying_as' , true ) ,
                ) ) ;
            }
        }

        public function prevent_rendering_dashboard( $bool , $affiliate_id ) {
            if ( 'fs_active' !== get_post_status( $affiliate_id ) && $this->affiliate_to_pay_fee( $affiliate_id ) ) {
                $bool = false ;
            }
            return $bool ;
        }

        public function hide_aff_fee_product( $q ) {
            if ( isset( WC()->session ) && is_callable( array( WC()->session , 'get' ) ) ) {
                $session_data = WC()->session->get( 'fs_affiliate_fee_props' , null ) ;

                if ( isset( $session_data[ 'affiliate_id' ] , $session_data[ 'product_id' ] ) && $this->affiliate_to_pay_fee( $session_data[ 'affiliate_id' ] ) ) {
                    $q->set( 'post__not_in' , array( $session_data[ 'product_id' ] ) ) ;
                }
            }
        }

        public function show_frontend_notice() {
            if ( is_product() ) {
                global $post ;

                if ( $this->affiliate_fee_payable_product( $post->ID ) ) {
                    wc_add_notice( $this->aff_fee_single_product_page_msg , 'success' ) ;
                }
            }
        }

        public function is_sold_individually( $bool , $product ) {
            if ( $this->affiliate_fee_payable_product( $product->get_id() ) ) {
                $bool = true ;
            }
            return $bool ;
        }

        public function validate_add_to_cart( $bool , $product_id , $quantity , $variation_id = 0 , $variations = array() , $cart_item_data = array() ) {
            if ( $this->cart_contains_affiliate_fee_product() ) {
                wc_add_notice( __( 'Sorry you cannot add to cart this product since affiliate fee product is already in cart. Make sure to empty the cart and try again.' , FS_AFFILIATES_LOCALE ) , 'error' ) ;
                return false ;
            }

            $add_to_cart_product_id = ( $variation_id ? $variation_id : $product_id ) ;

            if ( $this->affiliate_fee_payable_product( $add_to_cart_product_id ) ) {
                self::$add_to_cart_transient = WC()->session->get( 'fs_affiliate_fee_props' , null ) ;

                if ( self::$add_to_cart_transient[ 'product_id' ] != $add_to_cart_product_id ) {
                    wc_add_notice( __( 'Something went wrong while adding the product to cart.' , FS_AFFILIATES_LOCALE ) , 'error' ) ;
                    return false ;
                }

                if ( is_array( WC()->cart->cart_contents ) ) {
                    foreach ( WC()->cart->cart_contents as $cart_item_key => $cart_item ) {
                        if ( ! empty( $cart_item[ 'data' ] ) ) {
                            WC()->cart->remove_cart_item( $cart_item_key ) ;
                        }
                    }
                }
            }
            return $bool ;
        }

        public function add_cart_item_data( $cart_item , $cart_item_key ) {
            if ( $this->affiliate_fee_payable_product( $cart_item[ 'data' ]->get_id() ) ) {
                $cart_item[ 'fs_affiliates' ][ 'affiliate_fee' ] = self::$add_to_cart_transient ;

                self::$redirect_to_cart = true ;
            }
            return $cart_item ;
        }

        public function redirect_to_cart() {
            if ( self::$redirect_to_cart ) {
                wp_safe_redirect( wc_get_page_permalink( 'cart' ) ) ;
                exit ;
            }
        }

        public function refresh_cart( $cart ) {
            if ( ! is_object( WC()->cart ) ) {
                return ;
            }

            foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
                if ( ! empty( $cart_item[ 'fs_affiliates' ][ 'affiliate_fee' ][ 'affiliate_id' ] ) ) {
                    WC()->cart->cart_contents[ $cart_item_key ][ 'fs_affiliates' ][ 'affiliate_fee' ][ 'paying_as' ] = get_post_meta( $cart_item[ 'fs_affiliates' ][ 'affiliate_fee' ][ 'affiliate_id' ] , '_aff_fee_paying_as' , true ) ;
                } else {
                    WC()->cart->cart_contents[ $cart_item_key ][ 'fs_affiliates' ][ 'affiliate_fee' ] = array() ;
                }
            }
        }

        public function update_order_meta( $order_id , $data ) {
            if ( $props = $this->cart_contains_affiliate_fee_product() ) {
                update_post_meta( $order_id , 'fs_affiliate_fee_props' , $props ) ;
            }
        }

        public function payment_complete( $order_id , $old_order_status , $new_order_status ) {

            if (
                    in_array( $new_order_status , array( 'completed' , 'processing' ) ) &&
                    ! in_array( $old_order_status , array( 'completed' , 'processing' ) ) &&
                    ($fee_props = $this->affiliate_fee_payable_order( $order_id , 'onetime' ))
            ) {
                $affiliate = new FS_Affiliates_Data( $fee_props[ 'affiliate_id' ] ) ;
                $affiliate->update_status( 'fs_active' ) ;
            }
        }

        public function subscription_actions( $subscription_id , $order_id ) {
            if ( wp_get_post_parent_id( $order_id ) > 0 ) {
                $order_id = wp_get_post_parent_id( $order_id ) ;
            }

            if ( $fee_props = $this->affiliate_fee_payable_order( $order_id , 'recurring' ) ) {
                $affiliate = new FS_Affiliates_Data( $fee_props[ 'affiliate_id' ] ) ;

                add_post_meta( $fee_props[ 'affiliate_id' ] , '_aff_fee_subscription_id' , $subscription_id ) ;

                if ( doing_action( 'sumosubscriptions_active_subscription' ) ) {
                    $affiliate->update_status( 'fs_active' ) ;
                } else if ( doing_action( 'sumosubscriptions_pause_subscription' ) || doing_action( 'sumosubscriptions_cancel_subscription' ) ) {
                    $affiliate->update_status( 'fs_suspended' ) ;
                }
            }
        }

    }

}   

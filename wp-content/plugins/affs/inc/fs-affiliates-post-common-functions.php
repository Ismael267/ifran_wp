<?php

/*
 * Post Common functions
 */

if ( ! defined( 'ABSPATH' ) )
    exit ; // Exit if accessed directly.

if ( ! function_exists( 'fs_affiliates_create_new_referral' ) ) {

    function fs_affiliates_create_new_referral( $meta_args , $post_args = array() ) {
        $ignore_referrals = get_option( 'fs_affiliates_ignore_referrals' ) ;

        //ignore referrals when amount less than are equal to 0
        if ( $ignore_referrals == 'yes' && $meta_args[ 'amount' ] <= 0 )
            return false ;

        $affiliate_id = isset( $post_args[ 'post_author' ] ) ? $post_args[ 'post_author' ] : '' ;

        if ( ! fs_affiliates_is_affiliate_active( $affiliate_id ) ) {
            return ;
        }

        $status = (isset( $post_args[ 'post_status' ] )) ? $post_args[ 'post_status' ] : get_option( 'fs_affiliates_referral_default_status' , 'fs_unpaid' ) ;

        $commision_amount = apply_filters( 'is_membership_commision_value_available' , $meta_args[ 'amount' ] , $affiliate_id ) ;

        $meta_args[ 'amount' ]     = floatval( $commision_amount ) ;
        $meta_args[ 'ip_address' ] = fs_affiliates_get_ip_address() ;

        $post_args[ 'post_status' ] = apply_filters( 'fs_affiliates_referral_commission_threshold' , $status , $meta_args[ 'amount' ] ) ;

        $referral_id = apply_filters( 'fs_affiliates_new_referrals' , false , $affiliate_id , $meta_args , $post_args ) ;

        if ( ! $referral_id ) {
            // Manual Referral check
            $skip_mlm_check = isset( $post_args[ 'skip_mlm_check' ] ) ?  ! $post_args[ 'skip_mlm_check' ] : true ;

            if ( $skip_mlm_check ) {
                $check_threshold = apply_filters( 'fs_affiliates_check_direct_referral_threshold' , true , $affiliate_id ) ;
                //ignore referrals based on direct referral thershold
                if ( ! $check_threshold )
                    return false ;
            }

            $referals_object = new FS_Affiliates_Referrals ( ) ;
            $referral_id     = $referals_object->create( $meta_args , $post_args ) ;

            do_action( 'fs_affiliates_create_referrals' , $affiliate_id , $meta_args , $post_args ) ;
        }

        $total_unpaid_amount = get_post_meta( $affiliate_id , 'unpaid_earnings' , true ) ;
        $unpaid_earnings     = floatval( $total_unpaid_amount ) + floatval( $commision_amount ) ;
        update_post_meta( $affiliate_id , 'unpaid_earnings' , $unpaid_earnings ) ;

        if ( ! empty( $meta_args[ 'visit_id' ] ) ) {
            $VisitObj = new FS_Affiliates_Visits( $meta_args[ 'visit_id' ] ) ;
            $VisitObj->update_status( 'fs_converted' ) ;
            $VisitObj->update_meta( 'referral_id' , $referral_id ) ;
        }

        do_action( 'fs_affiliates_new_referral' , $referral_id , $affiliate_id ) ;

        do_action( 'fs_affiliates_refferal_status_changed_new_to_' . get_post_status( $referral_id ) , $referral_id ) ;

        return $referral_id ;
    }

}

if ( ! function_exists( 'fs_affiliates_create_new_affiliate' ) ) {

    function fs_affiliates_create_new_affiliate( $meta_args , $post_args = array() ) {

        $default_args = array(
            'unpaid_earnings' => 0 ,
            'paid_earnings'   => 0
                ) ;

        $meta_args = wp_parse_args( $meta_args , $default_args ) ;

        $affiliates_object = new FS_Affiliates_Data() ;
        $affiliate_id      = $affiliates_object->create( $meta_args , $post_args ) ;

        update_user_meta( $post_args[ 'post_author' ] , 'fs_affiliates_enabled' , 'yes' ) ;

        do_action( 'fs_affiliates_status_changed' , $affiliate_id ) ;

        do_action( 'fs_affiliates_status_changed_new_to_' . $affiliates_object->get_status() , $affiliate_id ) ;

        return $affiliate_id ;
    }

}


if ( ! function_exists( 'fs_affiliates_create_new_visit' ) ) {

    function fs_affiliates_create_new_visit( $meta_args , $post_args = array() ) {

        $visit_object = new FS_Affiliates_Visits() ;
        $visit_id     = $visit_object->create( $meta_args , $post_args ) ;

        $affiliate_id = isset( $post_args[ 'post_author' ] ) ? $post_args[ 'post_author' ] : '' ;

        do_action( 'fs_affiliates_new_visit' , $visit_id , $affiliate_id ) ;

        return $visit_id ;
    }

}

if ( ! function_exists( 'fs_affiliates_create_new_landing_commission' ) ) {

    function fs_affiliates_create_new_landing_commission( $meta_args , $post_args = array() ) {

        $landing_commission_object = new FS_Affiliates_Landing_Commission() ;
        $landing_commission_id     = $landing_commission_object->create( $meta_args , $post_args ) ;

        do_action( 'fs_affiliates_new_landing_commission' , $landing_commission_id ) ;

        return $landing_commission_id ;
    }

}


if ( ! function_exists( 'fs_affiliates_update_landing_commission' ) ) {

    function fs_affiliates_update_landing_commission( $landing_commission_id , $meta_args = array() , $post_args = array() ) {

        $landing_commission_object = new FS_Affiliates_Landing_Commission( $landing_commission_id ) ;
        $old_status                = $landing_commission_object->get_status() ;

        $landing_commission_object->update( $meta_args , $post_args ) ;
        $new_status = $landing_commission_object->get_status() ;

        do_action( 'fs_affiliates_landing_commission_status_changed' , $landing_commission_id ) ;

        do_action( 'fs_affiliates_landing_commission_status_changed_to_' . $new_status , $landing_commission_id , $landing_commission_object ) ;
        do_action( 'fs_affiliates_landing_commission_status_changed_' . $old_status . '_to_' . $new_status , $landing_commission_id , $landing_commission_object ) ;

        return true ;
    }

}

if ( ! function_exists( 'fs_affiliates_create_new_transaction_log_for_wallet' ) ) {

    function fs_affiliates_create_new_transaction_log_for_wallet( $meta_args , $post_args = array() ) {

        $wallet_object  = new FS_Affiliates_Wallet() ;
        $transaction_id = $wallet_object->create( $meta_args , $post_args ) ;

        return $transaction_id ;
    }

}

if ( ! function_exists( 'fs_affiliates_create_new_payouts' ) ) {

    function fs_affiliates_create_new_payouts( $meta_args , $post_args = array() ) {

        $payouts_object = new FS_Affiliates_Payouts() ;
        $payout_id      = $payouts_object->create( $meta_args , $post_args ) ;

        $affiliate_id = isset( $post_args[ 'post_author' ] ) ? $post_args[ 'post_author' ] : '' ;

        $total_paid_amount = ( float ) get_post_meta( $affiliate_id , 'paid_earnings' , true ) ;
        update_post_meta( $affiliate_id , 'paid_earnings' , $total_paid_amount + $meta_args[ 'paid_amount' ] ) ;

        do_action( 'fs_affiliates_new_payout' , $payout_id , $affiliate_id ) ;

        return $payout_id ;
    }

}

if ( ! function_exists( 'fs_affiliates_create_new_payout_request' ) ) {

    function fs_affiliates_create_new_payout_request( $meta_args , $post_args = array() ) {

        $payouts_object = new FS_Affiliates_Payout_Request_Data() ;
        $payout_id      = $payouts_object->create( $meta_args , $post_args ) ;

        $affiliate_id = isset( $post_args[ 'post_author' ] ) ? $post_args[ 'post_author' ] : '' ;

        do_action( 'fs_affiliates_new_payout_request' , $payout_id , $affiliate_id ) ;

        return $payout_id ;
    }

}

if ( ! function_exists( 'fs_affiliates_update_affiliate' ) ) {

    function fs_affiliates_update_affiliate( $affiliate_id , $meta_args = array() , $post_args = array() ) {

        $affiliates_object = new FS_Affiliates_Data( $affiliate_id ) ;
        $old_status        = $affiliates_object->get_status() ;

        $user_id = get_post_field( 'post_author' , $affiliate_id ) ;

        if ( $user_id && ('yes' != get_user_meta( $user_id , 'fs_affiliates_enabled' , 'yes' )) ) {
            update_user_meta( $user_id , 'fs_affiliates_enabled' , 'yes' ) ;
        }

        $affiliates_object->update( $meta_args , $post_args ) ;
        $new_status = $affiliates_object->get_status() ;

        do_action( 'fs_affiliates_status_changed' , $affiliate_id ) ;

        do_action( 'fs_affiliates_status_changed_to_' . $new_status , $affiliate_id , $affiliates_object ) ;
        do_action( 'fs_affiliates_status_changed_' . $old_status . '_to_' . $new_status , $affiliate_id , $affiliates_object ) ;

        return true ;
    }

}

if ( ! function_exists( 'fs_affiliates_update_referral' ) ) {

    function fs_affiliates_update_referral( $referral_id , $meta_args , $post_args = array() ) {

        $referral_object = new FS_Affiliates_Referrals( $referral_id ) ;
        $old_status      = $referral_object->get_status() ;

        $referral_object->update( $meta_args , $post_args ) ;
        $new_status = $referral_object->get_status() ;

        do_action( 'fs_affiliates_refferal_status_changed_to_' . $new_status , $referral_id , $referral_object ) ;

        do_action( 'fs_affiliates_refferal_status_changed_' . $old_status . '_to_' . $new_status , $referral_id , $referral_object ) ;

        return true ;
    }

}
if ( ! function_exists( 'fs_affiliates_link_new_affiliate' ) ) {

    function fs_affiliates_link_new_affiliate( $meta_args , $post_args = array() ) {

        $wc_coupon_linked_object = new FS_Linked_Affiliates_Data() ;
        $wc_coupon_linked_id     = $wc_coupon_linked_object->create( $meta_args , $post_args ) ;
        return $wc_coupon_linked_id ;
    }

}

if ( ! function_exists( 'fs_affiliates_update_linked_affiliate' ) ) {

    function fs_affiliates_update_linked_affiliate( $wc_coupon_linked_id , $meta_args = array() , $post_args = array() ) {

        $wc_coupon_linked_object = new FS_Linked_Affiliates_Data( $wc_coupon_linked_id ) ;
        $wc_coupon_linked_object->update( $meta_args , $post_args ) ;

        return true ;
    }

}

if ( ! function_exists( 'fs_affiliates_delete_affiliate' ) ) {

    function fs_affiliates_delete_affiliate( $affiliate_id , $user_delete = true , $affiliates_object = array() ) {

        if ( ! is_a( $affiliates_object , 'FS_Affiliates_Data' ) )
            $affiliates_object = new FS_Affiliates_Data( $affiliate_id ) ;

        wp_delete_post( $affiliate_id , true ) ; //delete affiliate

        fs_affiliates_delete_referrals( $affiliate_id ) ; // delete all referrals related this affiliate

        fs_affiliates_delete_visits( $affiliate_id ) ; //delete all visits related this affiliate

        $user_id = $affiliates_object->user_id ;
        if ( $user_delete && get_option( 'fs_affiliates_account_deletion_type' ) == '2' ) {
            if ( get_user_meta( $user_id , 'fs_affiliates_created' , true ) == 'yes' ) {
                wp_delete_user( $user_id ) ; //delete user
            }
        }

        delete_user_meta( $user_id , 'fs_affiliates_enabled' ) ;

        do_action( 'fs_affiliates_status_changed_' . $affiliates_object->get_status() . '_to_deleted' , $affiliate_id , $affiliates_object ) ;

        do_action( 'fs_affiliates_account_deleted' , $affiliate_id , $affiliates_object ) ;

        return true ;
    }

}


if ( ! function_exists( 'fs_affiliates_delete_referrals' ) ) {

    function fs_affiliates_delete_referrals( $affiliate_id ) {
        $args = array(
            'numberposts' => -1 ,
            'post_type'   => 'fs-referrals' ,
            'post_status' => array( 'fs_pending' , 'fs_unpaid' , 'fs_paid' , 'fs_rejected' ) ,
            'fields'      => 'ids' ,
            'author'      => $affiliate_id ,
                ) ;

        $ids = get_posts( $args ) ;

        if ( ! fs_affiliates_check_is_array( $ids ) )
            return true ;

        foreach ( $ids as $id ) {
            wp_delete_post( $id , true ) ; //delete visit
        }

        return true ;
    }

}


if ( ! function_exists( 'fs_affiliates_delete_visits' ) ) {

    function fs_affiliates_delete_visits( $affiliate_id ) {
        $args = array(
            'numberposts' => -1 ,
            'post_type'   => 'fs-visits' ,
            'post_status' => array( 'fs_converted' , 'fs_notconverted' , 'trash' ) ,
            'fields'      => 'ids' ,
            'author'      => $affiliate_id ,
                ) ;

        $ids = get_posts( $args ) ;

        if ( ! fs_affiliates_check_is_array( $ids ) )
            return true ;

        foreach ( $ids as $id ) {
            wp_delete_post( $id , true ) ; //delete visit
        }

        return true ;
    }

}

if ( ! function_exists( 'fs_affiliates_get_visits_count' ) ) {

    function fs_affiliates_get_visits_count( $affiliate_id ) {
        $args = array(
            'numberposts' => -1 ,
            'post_type'   => 'fs-visits' ,
            'post_status' => array( 'fs_converted' , 'fs_notconverted' ) ,
            'fields'      => 'ids' ,
            'author'      => $affiliate_id ,
                ) ;

        $ids = get_posts( $args ) ;

        if ( ! fs_affiliates_check_is_array( $ids ) )
            return 0 ;

        return count( $ids ) ;
    }

}

if ( ! function_exists( 'fs_affiliates_get_referrals_count' ) ) {

    function fs_affiliates_get_referrals_count( $affiliate_id ) {
        $args = array(
            'numberposts' => -1 ,
            'post_type'   => 'fs-referrals' ,
            'post_status' => array( 'fs_unpaid' , 'fs_paid' , 'fs_pending' ) ,
            'fields'      => 'ids' ,
            'author'      => $affiliate_id ,
                ) ;

        $ids = get_posts( $args ) ;

        if ( ! fs_affiliates_check_is_array( $ids ) )
            return 0 ;

        return count( $ids ) ;
    }

}


if ( ! function_exists( 'fs_affiliates_get_referrals' ) ) {

    function fs_affiliates_get_referrals( $status = false , $object = false ) {
        if ( ! $status )
            $status = array( 'fs_unpaid' , 'fs_paid' , 'fs_pending' ) ;

        $args = array(
            'numberposts' => -1 ,
            'post_type'   => 'fs-referrals' ,
            'post_status' => $status ,
            'fields'      => 'ids' ,
                ) ;

        $ids = get_posts( $args ) ;

        if ( ! fs_affiliates_check_is_array( $ids ) )
            return array() ;

        if ( ! $object )
            return $ids ;

        $referral_objects = array() ;
        foreach ( $ids as $id ) {
            $referral_objects[] = new FS_Affiliates_Referrals( $id ) ;
        }

        return $referral_objects ;
    }

}


if ( ! function_exists( 'fs_affiliates_get_referrals_commission' ) ) {

    function fs_affiliates_get_referrals_commission( $affiliate_id , $status = 'unpaid' ) {
        global $wpdb ;
        $status = ($status == 'unpaid') ? 'fs_unpaid' : 'fs_paid' ;

        $query = $wpdb->prepare( "SELECT sum(meta_value) FROM $wpdb->posts as p "
                . "INNER JOIN $wpdb->postmeta as meta ON p.ID=meta.post_id "
                . "WHERE p.post_type='fs-referrals' AND p.post_status=%s "
                . "AND p.post_author=%s AND meta.meta_key='amount'" , $status , $affiliate_id ) ;

        $amount = $wpdb->get_var( $query ) ;

        return isset( $amount ) ? $amount : 0 ;
    }

}

if ( ! function_exists( 'fs_affiliates_get_affiliate_by_metakey' ) ) {

    function fs_affiliates_get_affiliate_by_metakey( $meta_key , $meta_value ) {
        $args = array(
            'post_type'   => 'fs-affiliates' ,
            'numberposts' => -1 ,
            'post_status' => array( 'fs_active' , 'fs_inactive' , 'fs_pending_approval' , 'fs_rejected' , 'fs_suspended' , 'fs_hold' ) ,
            'fields'      => 'ids' ,
            'meta_key'    => $meta_key ,
            'meta_value'  => $meta_value
                ) ;

        $ids = get_posts( $args ) ;

        if ( ! fs_affiliates_check_is_array( $ids ) )
            return false ;

        return reset( $ids ) ;
    }

}

if ( ! function_exists( 'fs_affiliates_get_affiliateid_from_name' ) ) {

    function fs_affiliates_get_affiliateid_from_name( $name ) {
        if ( empty( $name ) )
            return 0 ;

        if ( is_numeric( $name ) )
            return $name ;

        $args = array(
            'post_type'   => 'fs-affiliates' ,
            'numberposts' => -1 ,
            'name'        => $name ,
            'post_status' => array( 'fs_active' , 'fs_inactive' , 'fs_pending_approval' , 'fs_rejected' , 'fs_suspended' , 'fs_hold' ) ,
            'fields'      => 'ids' ,
                ) ;

        $ids = get_posts( $args ) ;

        if ( ! fs_affiliates_check_is_array( $ids ) )
            return 0 ;

        return reset( $ids ) ;
    }

}

if ( ! function_exists( 'fs_affiliates_is_domain_already_exists' ) ) {

    function fs_affiliates_is_domain_already_exists( $domain_name ) {
        $status = array( 'fs_active' , 'fs_pending_approval' , 'fs_suspended' , 'fs_rejected' ) ;
        $args   = array(
            'post_type'   => 'fs-url-masking' ,
            'numberposts' => -1 ,
            'post_status' => $status ,
            'fields'      => 'ids' ,
            'meta_key'    => 'url_masking_domain' ,
            'meta_value'  => $domain_name
                ) ;

        $get_data = get_posts( $args ) ;

        return count( $get_data ) ;
        ;
    }

}

if ( ! function_exists( 'fs_affiliates_get_active_affiliates' ) ) {
    /*
     * Get all active affiliates ids
     */

    function fs_affiliates_get_active_affiliates() {
        $args = array(
            'numberposts' => -1 ,
            'post_type'   => 'fs-affiliates' ,
            'post_status' => 'fs_active' ,
            'fields'      => 'ids' ,
                ) ;

        $aff_ids = get_posts( $args ) ;

        if ( ! fs_affiliates_check_is_array( $aff_ids ) ) {
            return array() ;
        }

        return $aff_ids ;
    }

}

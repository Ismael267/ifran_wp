<?php

/**
 *  WooCommerce Commission
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}
if ( ! class_exists( 'FS_Affiliates_WC_Commission' ) ) {

    /**
     * Class FS_Affiliates_WC_Commission
     */
    class FS_Affiliates_WC_Commission {

        public static function insert_referrals_post( $OrderId ) {
            do_action( 'fs_affiliates_before_referral_creation', $OrderId ) ;

            $parent_order = apply_filters( 'fs_affiliates_create_referral_by_parent', false, $OrderId ) ;

            if ( $parent_order ) {
                $CheckIfFinalPayment = function_exists( '_sumo_pp_is_final_payment_order' ) ? _sumo_pp_is_final_payment_order( $OrderId ) : false ;
                if ( ! $CheckIfFinalPayment )
                    return ;

                $ParentOrderId = wp_get_post_parent_id( $OrderId ) ;
                self::create_referrals_post( $ParentOrderId ) ;
            } else {
                self::create_referrals_post( $OrderId ) ;
            }

            do_action( 'fs_affiliates_after_referral_creation', $OrderId ) ;
        }

        public static function create_referrals_post( $OrderId ) {
            $CheckIfAlreadyAwarded = get_post_meta( $OrderId, 'fs_commission_awarded', true ) ;
            if ( $CheckIfAlreadyAwarded == 'yes' )
                return ;

            $OrderObj    = new WC_Order( $OrderId ) ;
            $AffiliateId = get_post_meta( $OrderId, 'fs_affiliate_in_order', true ) ;

            if ( empty( $AffiliateId ) || ! apply_filters( 'fs_affiliates_create_new_referral', true, $OrderId, $AffiliateId ) )
                return ;

            $product_name                        = fs_affiliates_get_product_name_from_order( $OrderId ) ;
            $ReferralData[ 'type' ]              = 'sale' ;
            $ReferralData[ 'amount' ]            = get_post_meta( $OrderId, 'fs_commission_to_be_awarded_in_order', true ) ;
            $ReferralData[ 'description' ]       = get_option( 'fs_affiliates_referral_desc_woocommerce_order_label', 'WooCommerce Order' ) ;
            $ReferralData[ 'description' ]       = str_replace( array( '{product_name}' ), array( $product_name ), $ReferralData[ 'description' ] ) ;
            $ReferralData[ 'reference' ]         = $OrderId ;
            $ReferralData[ 'original_price' ]    = get_post_meta( $OrderId, 'fs_get_regular_prices', true ) ;
            $ReferralData[ 'split_commissions' ] = get_post_meta( $OrderId, 'fs_split_commission', true ) ;
            $ReferralData[ 'date' ]              = time() ;
            $ReferralData[ 'visit_id' ]          = get_post_meta( $OrderId, 'fs_visit_in_order', true ) ;
            $ReferralData[ 'campaign' ]          = get_post_meta( $OrderId, 'fs_campaign_in_order', true ) ;

            $referral_created = fs_affiliates_create_new_referral( $ReferralData, array( 'post_author' => $AffiliateId ) ) ;

            if ( $referral_created )
                update_post_meta( $OrderId, 'fs_commission_awarded', 'yes' ) ;
        }

        public static function save_affiliate_commission_for_order( $OrderId ) {
            $OrderObj         = new WC_Order( $OrderId ) ;
            $LimitForReferral = apply_filters( 'fs_affiliates_is_restricted_referral', true, $OrderObj->get_user_id(), $OrderObj ) ;

            if ( ! $LimitForReferral )
                return ;

            $AffiliateId = apply_filters( 'fs_affiliates_order_affiliate_id', fs_affiliates_get_id_from_cookie( 'fsaffiliateid' ), $OrderId, $OrderObj ) ;

            if ( empty( $AffiliateId ) )
                return ;

            if ( ! apply_filters( 'fs_affiliates_commission_from_same_ip', true, $OrderId, $AffiliateId ) )
                return ;

            if ( ! self::is_restricted_own_commission( $AffiliateId, $OrderObj->get_user_id() ) )
                return ;

            $VisitId           = apply_filters( 'fs_affiliates_order_visit_id', fs_affiliates_get_id_from_cookie( 'fsvisitid' ), $OrderId, $OrderObj ) ;
            $campaign          = apply_filters( 'fs_affiliates_order_campaign_id', fs_affiliates_get_id_from_cookie( 'fscampaign', '' ), $OrderId, $OrderObj ) ;
            $CommissionToAward = apply_filters( 'fs_affiliates_order_commission', 0, $OrderId, $OrderObj, $AffiliateId ) ;

            if ( empty( $CommissionToAward ) && ( 'string' != gettype( $CommissionToAward ) || 'no_commission' != $CommissionToAward ) )
                $CommissionToAward = self::award_commission_for_product_purchase( $OrderId, $AffiliateId ) ;

            update_post_meta( $OrderId, 'fs_affiliate_in_order', $AffiliateId ) ;
            update_post_meta( $OrderId, 'fs_visit_in_order', $VisitId ) ;
            update_post_meta( $OrderId, 'fs_campaign_in_order', $campaign ) ;
            update_post_meta( $OrderId, 'fs_commission_to_be_awarded_in_order', $CommissionToAward ) ;

            if ( isset( $_COOKIE[ 'fsproductid' ] ) ) {
                fs_affiliates_setcookie( 'fsproductid', '', time() - 86400 ) ;

                if ( isset( $_COOKIE[ 'fsaffiliateid' ] ) )
                    fs_affiliates_setcookie( 'fsaffiliateid', '', time() - 86400 ) ;
            }

            do_action( 'fs_affiliates_update_order_meta', $OrderId, $AffiliateId, $OrderObj ) ;
        }

        /**
         * Commission for Purchase
         */
        public static function award_commission_for_product_purchase( $OrderId, $AffiliateId ) {
            $Commissions      = array() ;
            $GetRegularPrices = array() ;
            $OrderObj         = new WC_Order( $OrderId ) ;
            $items            = $OrderObj->get_items() ;

            foreach ( $items as $Item ) {
                $AllowedProduct = apply_filters( 'fs_affiliates_is_restricted_product', true, $Item[ 'product_id' ], $Item[ 'variation_id' ] ) ;

                if ( ! $AllowedProduct )
                    continue ;

                $GetRegularPrice    = self::get_regular_price( $Item, $OrderObj ) ;
                $RegularPrice       = apply_filters( 'fs_affiliate_regular_price_for_purchase', $GetRegularPrice, $OrderId, $Item ) ;
                $GetRegularPrices[] = $GetRegularPrice ;
                $product_obj        = (function_exists( 'wc_get_product' )) ? wc_get_product( $Item[ 'product_id' ] ) : get_product( $Item[ 'product_id' ] ) ;

                if ( $product_obj->get_type() == 'variable' ) {
                    $args = array( 'fields'      => 'ids',
                        'post_type'   => 'product_variation',
                        'post_status' => 'publish',
                        'numberposts' => -1,
                        'orderby'     => 'menu_order',
                        'order'       => 'asc',
                        'child_of'    => $Item[ 'product_id' ]
                            ) ;

                    $variation_ids = get_posts( $args ) ;

                    foreach ( $variation_ids as $variation_id ) {

                        if ( $Item[ 'variation_id' ] == $variation_id )
                            $Commissions[] = self::check_if_product_level( $Item[ 'product_id' ], $variation_id, $Item[ 'qty' ], $AffiliateId, $RegularPrice ) ;
                    }
                } else {
                    $Commissions[] = self::check_if_product_level( $Item[ 'product_id' ], $Item[ 'variation_id' ], $Item[ 'qty' ], $AffiliateId, $RegularPrice ) ;
                }
            }

            update_post_meta( $OrderId, 'fs_get_regular_prices', array_sum( $GetRegularPrices ) ) ;

            return array_sum( $Commissions ) ;
        }

        /*
         * Check If Product is allowed to award commission
         */

        public static function is_restricted_own_commission( $AffiliateId, $UserId ) {
            $CheckIfAffiliate = fs_get_affiliate_id_for_user( $UserId ) ;

            if ( $CheckIfAffiliate == $AffiliateId )
                return apply_filters( 'fs_affiliates_is_restricted_own_commission', false ) ;

            return true ;
        }

        public static function get_regular_price( $Item, $OrderObj ) {
            if ( ! isset( $Item[ 'line_subtotal' ] ) )
                return 0 ;

            $LineSubTotalTax = isset( $Item[ 'line_subtotal_tax' ] ) ? $Item[ 'line_subtotal_tax' ] : 0 ;
            $RegularPrice    = $Item[ 'line_subtotal' ] ;
            if ( get_option( 'woocommerce_tax_display_cart' ) == 'incl' )
                if ( get_option( 'fs_affiliates_exclude_tax_costs_for_commission_calculation' ) == 'no' )
                    $RegularPrice    = $Item[ 'line_subtotal' ] + $LineSubTotalTax ;

            if ( get_option( 'fs_affiliates_calculate_commission_before_apply_coupon' ) == 'yes' && ! empty( $OrderObj->get_discount_total() ) ) {
                $ConversionRate = $OrderObj->get_subtotal() / $OrderObj->get_discount_total() ;
                $ValuetoDetect  = $RegularPrice / $ConversionRate ;
                $RegularPrice   = $RegularPrice - $ValuetoDetect ;
            }

            return apply_filters( 'fs_affiliates_product_regular_price', $RegularPrice, $Item, $OrderObj ) ;
        }

        public static function check_if_product_level( $ProductId, $VariationId, $Quantity, $AffiliateId, $RegularPrice ) {
            $productid                 = (empty( $VariationId )) ? $ProductId : $VariationId ;
            $BlockCommissionforProduct = empty( $VariationId ) ? get_post_meta( $productid, 'fs_block_commission_for_product', true ) : get_post_meta( $productid, 'fs_block_commission_for_variant', true ) ;
            if ( $BlockCommissionforProduct == 'yes' )
                return 0 ;

            $CommissionTypeInProductLevel  = empty( $VariationId ) ? get_post_meta( $productid, 'fs_commission_type_for_affiliate_in_product_level', true ) : get_post_meta( $productid, 'fs_commission_type_for_affiliate_in_variation_level', true ) ;
            $CommissionValueInProductLevel = empty( $VariationId ) ? get_post_meta( $productid, 'fs_commission_value_for_affiliate_in_product_level', true ) : get_post_meta( $productid, 'fs_commission_value_for_affiliate_in_variation_level', true ) ;

            if ( $CommissionValueInProductLevel == '' )
                return self::check_if_category_level( $ProductId, $VariationId, $Quantity, $AffiliateId, $RegularPrice ) ;

            if ( $CommissionTypeInProductLevel == '1' ) {
                $CommissionValue = $CommissionValueInProductLevel * $Quantity ;
            } else {
                $CommissionValue = ($RegularPrice / 100) * $CommissionValueInProductLevel ;
            }

            if ( 'yes' == get_option( 'fs_affiliates_qty_commission_restrict', 'no' ) ) {
                $CommissionValue = $CommissionValue / $Quantity ;
            }

            return $CommissionValue ;
        }

        public static function check_if_category_level( $ProductId, $VariationId, $Quantity, $AffiliateId, $RegularPrice ) {
            $CategoryListForProduct  = wp_get_post_terms( $ProductId, 'product_cat' ) ;
            $CategoryCountForProduct = count( $CategoryListForProduct ) ;
            $GetTerms                = get_the_terms( $ProductId, 'product_cat' ) ;
            $CommissionValue         = array() ;

            if ( ! fs_affiliates_check_is_array( $GetTerms ) )
                return self::check_if_affiliate_level( $ProductId, $VariationId, $Quantity, $AffiliateId, $RegularPrice ) ;

            foreach ( $GetTerms as $Term ) {
                $CommissionTypeInCategoryLevel  = get_term_meta( $Term->term_id, 'fs_commission_type_for_affiliate_in_category_level', true ) ;
                $CommissionValueInCategoryLevel = get_term_meta( $Term->term_id, 'fs_commission_value_for_affiliate_in_category_level', true ) ;
                $BlockCommissionInCategoryLevel = get_term_meta( $Term->term_id, 'fs_block_commission_for_category', true ) ;

                if ( $BlockCommissionInCategoryLevel == 'yes' )
                    continue ;

                if ( $CommissionValueInCategoryLevel == '' )
                    continue ;

                if ( $CategoryCountForProduct >= '1' ) {
                    if ( $CommissionTypeInCategoryLevel == '1' ) {
                        $CommissionValue[] = $CommissionValueInCategoryLevel * $Quantity ;
                    } else {
                        $CommissionValue[] = ($RegularPrice / 100) * $CommissionValueInCategoryLevel ;
                    }
                }
            }

            if ( ! fs_affiliates_check_is_array( $CommissionValue ) )
                return self::check_if_affiliate_level( $ProductId, $VariationId, $Quantity, $AffiliateId, $RegularPrice ) ;

            $CommissionAmount = max( $CommissionValue ) ;

            if ( 'yes' == get_option( 'fs_affiliates_qty_commission_restrict', 'no' ) ) {
                $CommissionAmount = $CommissionAmount / $Quantity ;
            }

            return $CommissionAmount ;
        }

        public static function get_affiliate_level_product_commission( $AffiliateData, $productid, $variationid, $Quantity, $AffiliateId, $RegularPrice ) {
            $newdata              = array() ;
            $CheckIfModuleEnabled = apply_filters( 'fs_affiliates_is_affiliate_level_product_commission', false ) ;

            if ( ! $CheckIfModuleEnabled )
                return $newdata ;

            $wcratesdata = get_post_meta( $AffiliateId, 'wc_product_rates', true ) ;
            if ( ! fs_affiliates_check_is_array( $wcratesdata ) )
                return $newdata ;

            foreach ( $wcratesdata as $key => $individualdata ) {
                $ProductToCheck = isset( $individualdata[ 'products' ] ) ? $individualdata[ 'products' ] : array() ;

                if ( in_array( $productid, $ProductToCheck ) || in_array( $variationid, $ProductToCheck ) ) {
                    if ( $individualdata[ 'commission_value' ] == '' )
                        continue ;

                    if ( $individualdata[ 'commission_type' ] == 'fixed' ) {
                        $CommissionValue = $individualdata[ 'commission_value' ] * $Quantity ;
                    } else {
                        $CommissionValue = ($RegularPrice / 100) * $individualdata[ 'commission_value' ] ;
                    }
                    $productid               = (empty( $variationid )) ? $productid : $variationid ;
                    $newdata[ $productid ][] = $CommissionValue ;
                }
            }

            return $newdata ;
        }

        public static function check_if_affiliate_level( $ProductId, $VariationId, $Quantity, $AffiliateId, $RegularPrice ) {
            $AffiliateData                   = new FS_Affiliates_Data( $AffiliateId ) ;
            $AffiliateLevelProductCommission = self::get_affiliate_level_product_commission( $AffiliateData, $ProductId, $VariationId, $Quantity, $AffiliateId, $RegularPrice ) ;
            $productid                       = (empty( $VariationId )) ? $ProductId : $VariationId ;

            if ( fs_affiliates_check_is_array( $AffiliateLevelProductCommission ) ) {
                $CommissionValueInAffiliateLevel = $AffiliateData->rule_priority == 1 ? reset( $AffiliateLevelProductCommission[ $productid ] ) : end( $AffiliateLevelProductCommission[ $productid ] ) ;
                $CommissionValueInAffiliateLevel = empty( $CommissionValueInAffiliateLevel ) ? $AffiliateData->commission_value : $CommissionValueInAffiliateLevel ;

                if ( empty( $CommissionValueInAffiliateLevel ) )
                    return self::check_if_global_level( $Quantity, $RegularPrice ) ;

                $CommissionValue = $CommissionValueInAffiliateLevel ;
            } else {
                if ( empty( $AffiliateData->commission_value ) )
                    return self::check_if_global_level( $Quantity, $RegularPrice ) ;

                if ( $AffiliateData->commission_type == 'fixed' ) {
                    $CommissionValue = $AffiliateData->commission_value * $Quantity ;
                } else {
                    $CommissionValue = ($RegularPrice / 100) * $AffiliateData->commission_value ;
                }
            }

            if ( 'yes' == get_option( 'fs_affiliates_qty_commission_restrict', 'no' ) ) {
                $CommissionValue = $CommissionValue / $Quantity ;
            }

            return $CommissionValue ;
        }

        public static function check_if_global_level( $Quantity, $RegularPrice ) {
            $CommissionTypeInGlobalLevel         = get_option( 'fs_affiliates_commission_type' ) ;
            $CommissionPercentValueInGlobalLevel = get_option( 'fs_affiliates_percentage_commission_value' ) ;
            $CommissionFixedValueInGlobalLevel   = get_option( 'fs_affiliates_fixed_commission_value' ) ;

            if ( $CommissionTypeInGlobalLevel == 'fixed' ) {
                if ( empty( $CommissionFixedValueInGlobalLevel ) )
                    return 0 ;

                $CommissionValue = $CommissionFixedValueInGlobalLevel * $Quantity ;
            } else {
                if ( empty( $CommissionPercentValueInGlobalLevel ) )
                    return 0 ;

                $CommissionValue = ($RegularPrice / 100) * $CommissionPercentValueInGlobalLevel ;
            }

            if ( 'yes' == get_option( 'fs_affiliates_qty_commission_restrict', 'no' ) ) {
                $CommissionValue = $CommissionValue / $Quantity ;
            }

            return $CommissionValue ;
        }

        public static function reject_unpaid_referral_upon_refund( $OrderId ) {
            $CheckIfAlreadyAwarded = get_post_meta( $OrderId, 'fs_commission_awarded', true ) ;
            if ( $CheckIfAlreadyAwarded != 'yes' )
                return ;

            $args = array(
                'post_type'   => 'fs-referrals',
                'numberposts' => -1,
                'post_status' => array( 'fs_unpaid' ),
                'meta_query'  => array(
                    array(
                        'key'   => 'reference',
                        'value' => $OrderId,
                    )
                ),
                'fields'      => 'ids' ) ;

            $ReferralIds = get_posts( $args ) ;
            if ( ! fs_affiliates_check_is_array( $ReferralIds ) )
                return ;

            foreach ( $ReferralIds as $Id ) {
                $ReferralObj = new FS_Affiliates_Referrals( $Id ) ;
                $ReferralObj->update_status( 'fs_rejected' ) ;
            }
        }

    }

}

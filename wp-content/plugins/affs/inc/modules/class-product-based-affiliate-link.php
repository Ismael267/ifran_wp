<?php
/**
 * Product Based Affiliate Link
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'FS_Product_Based_Affiliate_Link_Module' ) ) {

    /**
     * Class FS_Product_Based_Affiliate_Link_Module
     */
    class FS_Product_Based_Affiliate_Link_Module extends FS_Affiliates_Modules {
        /*
         * Data
         */

        protected $data = array(
            'enabled'                   => 'no' ,
            'allowed_affiliates_method' => '1' ,
            'selected_affiliates'       => array() ,
                ) ;

        /**
         * Class Constructor
         */
        public function __construct() {
            $this->id    = 'product_based_affiliate_link' ;
            $this->title = __( 'Product Based Affiliate Link' , FS_AFFILIATES_LOCALE ) ;

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
            return add_query_arg( array( 'page' => 'fs_affiliates' , 'tab' => 'modules' , 'section' => $this->id ) , admin_url( 'admin.php' ) ) ;
        }

        /*
         * Front End Action
         */

        public function frontend_action() {
            add_filter( 'fs_affiliates_link_generator' , array( $this , 'generate_product_based_link' ) , 1 , 6 ) ;
            add_action( 'fs_affiliates_before_set_cookie' , array( $this , 'set_cookie_for_product_based_affiliate' ) , 10 , 3 ) ;
            add_action( 'fs_affiliates_before_link_generator' , array( $this , 'display_link_generator_type' ) , 20 , 2 ) ;
            add_action( 'fs_affiliates_after_link_generator' , array( $this , 'display_product_link_generator' ) , 10 , 2 ) ;
        }

        /*
         * Both Front End and Back End Action
         */

        public function actions() {
            add_filter( 'fs_affiliate_check_if_product_based_affiliate_link_enabled' , array( $this , 'check_if_product_based_affiliate_link_enabled' ) , 10 , 1 ) ;
            add_filter( 'fs_affiliates_order_commission' , array( $this , 'get_commission_value_from_product_based_link' ) , 3 , 4 ) ;
            add_filter( 'fs_affiliates_order_affiliate_id' , array( $this , 'get_affiliate_id_from_product_based_link' ) , 3 , 3 ) ;
        }

        /*
         * Get settings options array
         */

        public function settings_options_array() {
            return array(
                array(
                    'type'  => 'title' ,
                    'title' => __( 'Product Based Affiliate Link' , FS_AFFILIATES_LOCALE ) ,
                    'id'    => 'fs_affiliates_product_based_affiliate_link' ,
                ) ,
                array(
                    'title'   => __( 'Product based Affiliate Link can be accessible for' , FS_AFFILIATES_LOCALE ) ,
                    'desc'    => __( 'The affiliates selected in this option can have access to generate the affiliate based on the product' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->plugin_slug . '_' . $this->id . '_allowed_affiliates_method' ,
                    'type'    => 'select' ,
                    'class'   => 'fs_affiliates_allowed_affiliates_method' ,
                    'default' => '1' ,
                    'options' => array(
                        '1' => __( 'All Affiliates' , FS_AFFILIATES_LOCALE ) ,
                        '2' => __( 'Selected Affiliates' , FS_AFFILIATES_LOCALE ) ,
                    ) ,
                ) ,
                array(
                    'title'     => __( 'Selected Affiliates' , FS_AFFILIATES_LOCALE ) ,
                    'id'        => $this->plugin_slug . '_' . $this->id . '_selected_affiliates' ,
                    'type'      => 'ajaxmultiselect' ,
                    'class'     => 'fs_affiliates_selected_affiliate' ,
                    'list_type' => 'affiliates' ,
                    'action'    => 'fs_affiliates_search' ,
                    'default'   => array() ,
                ) ,
                array(
                    'type' => 'sectionend' ,
                    'id'   => 'fs_affiliates_product_based_affiliate_link' ,
                ) ,
                    ) ;
        }

        public function check_if_product_based_affiliate_link_enabled( $bool ) {
            return true ;
        }

        /*
         * Get eligible affiliates
         */

        public function check_if_valid_affiliate( $affilate_id ) {

            if ( $this->allowed_affiliates_method == '2' ) {
                $eligible_affiliates = $this->selected_affiliates ;

                if ( fs_affiliates_check_is_array( $eligible_affiliates ) && ! in_array( $affilate_id , $eligible_affiliates ) ) {
                    return false ;
                }
            }

            return true ;
        }

        public function set_cookie_for_product_based_affiliate( $AffiliateID , $product , $cookieValidity ) {
            if ( $product ) {
                if ( ! apply_filters( 'fs_affiliates_check_if_last_referral' , false ) ) {
                    if ( isset( $_COOKIE[ 'fsproductid' ] ) )
                        return ;
                }

                if ( ! $AffiliateID )
                    return ;

                if ( ! fs_affiliates_is_affiliate_active( $AffiliateID ) )
                    return ;

                $ArrayToSerialize = array( 'affiliateid' => $AffiliateID , 'productid' => $product ) ;
                $CookieValue      = serialize( $ArrayToSerialize ) ;
                fs_affiliates_setcookie( 'fsproductid' , base64_encode( $CookieValue ) , time() + $cookieValidity ) ;
            }
        }

        public function generate_product_based_link( $formatted_affiliate_link , $affiliate_link , $ReferralIdentifier , $Identifier , $campaign , $manual = false ) {

            if ( $manual )
                return $formatted_affiliate_link ;

            if ( ! isset( $_POST[ 'product' ] ) || ! $_POST[ 'product' ] )
                return $formatted_affiliate_link ;

            $formatted_affiliate_link = add_query_arg( 'fsproduct' , $_POST[ 'product' ] , $formatted_affiliate_link ) ;

            return $formatted_affiliate_link ;
        }

        public function get_affiliate_id_from_product_based_link( $AffiliateId , $OrderId , $OrderObj ) {
            if ( ! isset( $_COOKIE[ 'fsproductid' ] ) )
                return $AffiliateId ;

            $UnserializedArray       = unserialize( fs_affiliates_get_id_from_cookie( 'fsproductid' ) ) ;
            $ProductBasedAffiliateId = isset( $UnserializedArray[ 'affiliateid' ] ) ? $UnserializedArray[ 'affiliateid' ] : 0 ;

            if ( empty( $ProductBasedAffiliateId ) )
                return $AffiliateId ;

            return $ProductBasedAffiliateId ;
        }

        public function get_commission_value_from_product_based_link( $CommissionValue , $OrderId , $OrderObj , $AffiliateId ) {
            if ( ! isset( $_COOKIE[ 'fsproductid' ] ) || ! empty( $CommissionValue ) )
                return $CommissionValue ;

            $UnserializedArray = unserialize( fs_affiliates_get_id_from_cookie( 'fsproductid' ) ) ;
            $AffiliateId       = isset( $UnserializedArray[ 'affiliateid' ] ) ? $UnserializedArray[ 'affiliateid' ] : 0 ;
            $ProductId         = isset( $UnserializedArray[ 'productid' ] ) ? $UnserializedArray[ 'productid' ] : 0 ;

            if ( empty( $AffiliateId ) || empty( $ProductId ) )
                return $CommissionValue ;

            return $this->award_commission_for_product_based_affiliate( $OrderId , $AffiliateId , $ProductId ) ;
        }

        /**
         * Commission for Purchase
         */
        public static function award_commission_for_product_based_affiliate( $OrderId , $AffiliateId , $ProductIdFromCookie ) {
            $OrderObj            = new WC_Order( $OrderId ) ;
            $UserId              = $OrderObj->get_user_id() ;
            $DiscountedValue     = $OrderObj->get_discount_total() ;
            $Commissions         = 'no_commission' ;
            $cookie_product      = wc_get_product( $ProductIdFromCookie ) ;
            $ProductIdFromCookie = (is_object( $cookie_product ) && 'variable' == $cookie_product->get_type()) ? $cookie_product->get_children() : array( $ProductIdFromCookie ) ;

            foreach ( $OrderObj->get_items() as $Item ) {
                $GetRegularPrice    = FS_Affiliates_WC_Commission::get_regular_price( $Item , $OrderObj ) ;
                $RegularPrice       = apply_filters( 'fs_affiliate_regular_price_for_purchase' , $GetRegularPrice , $OrderId , $Item ) ;
                $Quantity           = $Item[ 'qty' ] ;
                $ProductId          = $Item[ 'product_id' ] ;
                $VariationId        = $Item[ 'variation_id' ] ;
                $ProductToCheck     = empty( $VariationId ) ? $ProductId : $VariationId ;
                $AllowedProduct     = apply_filters( 'fs_affiliates_is_restricted_product' , true , $ProductId , $VariationId ) ;
                $AllowOwnCommission = FS_Affiliates_WC_Commission::is_restricted_own_commission( $AffiliateId , $UserId ) ;

                if ( $AllowedProduct && $AllowOwnCommission ) {
                    if ( in_array( $ProductToCheck , $ProductIdFromCookie ) ) {
                        $Commissions = FS_Affiliates_WC_Commission::check_if_product_level( $ProductId , $VariationId , $Quantity , $AffiliateId , $RegularPrice ) ;
                        return $Commissions ;
                    }
                }
            }

            return $Commissions ;
        }

        public function display_link_generator_type( $affilate_id , $user_id ) {
            if ( '2' == get_option( 'fs_affiliates_referral_link_type' , '1' ) ) {
                return ;
            }

            if ( ! $this->check_if_valid_affiliate( $affilate_id ) )
                return ;
            ?>
            <p class="fs_affiliates_link_generator_type_selector">
                <label><b><?php _e( 'Generate Affiliate Link based on' , FS_AFFILIATES_LOCALE ) ?></b></label>
                <select name="fs_affiliates_link_generator_type" class="fs_affiliates_link_generator_type">
                    <option value="1"><?php _e( 'Affiliate ID/Name' , FS_AFFILIATES_LOCALE ) ?></option>
                    <option value="2"><?php _e( 'Product Name' , FS_AFFILIATES_LOCALE ) ?></option>
                </select>
            </p>
            <?php
        }

        public function display_product_link_generator( $affilate_id , $user_id ) {
            if ( ! $this->check_if_valid_affiliate( $affilate_id ) )
                return ;
            $campaigns = array_filter( ( array ) get_post_meta( $affilate_id , 'campaign' , true ) ) ;
            ?>
            <table class="fs_affiliates_generate_link_table show_if_product_link_generator">
                <?php if ( fs_affiliates_check_is_array( $campaigns ) ) { ?>
                    <tr>
                        <td>
                            <label><b><?php _e( 'Select Campaign' , FS_AFFILIATES_LOCALE ) ?></b></label>
                            <select name="campaign" class="campaign_for_product">
                                <?php
                                echo '<option value="">' . __( 'Select' , FS_AFFILIATES_LOCALE ) . '</option>' ;

                                foreach ( $campaigns as $campaign ) {
                                    echo '<option value="' . $campaign . '">' . $campaign . '</option>' ;
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                <?php } ?>
                <tr>
                    <td>
                        <label><b><?php _e( 'Product Link Generator' , FS_AFFILIATES_LOCALE ) ?></b></label><?php
                        $product_selection_args = array(
                            'id'          => 'productid' ,
                            'name'        => 'products' ,
                            'list_type'   => 'products' ,
                            'class'       => 'wc-product-search' ,
                            'action'      => 'fs_affiliates_products_search' ,
                            'placeholder' => __( 'Search a Product' , FS_AFFILIATES_LOCALE ) ,
                            'multiple'    => false ,
                            'selected'    => true ,
                            'options'     => array() ,
                                ) ;
                        fs_affiliates_select2_html( $product_selection_args ) ;
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <button id="fs_affiliates_generate_affiliate_link" class="fs_affiliates_generate_affiliate_link"><?php _e( 'Generate Link' , FS_AFFILIATES_LOCALE ) ; ?></button>
                    </td>
                </tr>
            </table>
            <?php
        }

    }

}
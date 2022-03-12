<?php
/**
 * WC Coupon Linking
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'FS_Affiliates_WC_Coupon_Linking' ) ) {

    /**
     * Class FS_Affiliates_WC_Coupon_Linking
     */
    class FS_Affiliates_WC_Coupon_Linking extends FS_Affiliates_Modules {
        /*
         * Data
         */

        protected $data = array(
            'enabled' => 'no' ,
                ) ;

        /**
         * Class Constructor
         */
        public function __construct() {
            $this->id    = 'wc_coupon_linking' ;
            $this->title = __( 'WooCommerce Coupon Linking' , FS_AFFILIATES_LOCALE ) ;

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

        /**
         * Output the settings buttons.
         */
        public function output_buttons() {
            
        }

        /**
         * Get settings array.
         */
        public function settings_options_array() {

            return array(
                array(
                    'type' => 'output_linked_affiliates'
                ) ,
                    ) ;
        }

        /**
         * Output the affiliates
         */
        public function output_linked_affiliates() {
            global $current_sub_section ;

            switch ( $current_sub_section ) {
                case 'new_linking' :
                    $this->display_new_link_page() ;
                    break ;
                case 'edit_linking' :
                    $this->display_edit_link_page() ;
                    break ;
                default :
                    $this->display_linked_table() ;
                    break ;
            }
        }

        /*
         * Admin Actions
         */

        public function admin_action() {
            add_action( $this->plugin_slug . '_admin_field_output_linked_affiliates' , array( $this , 'output_linked_affiliates' ) ) ;
            add_action( 'woocommerce_process_shop_order_meta' , array( $this , 'get_manual_order_commission' ) ) ;
        }

        /*
         * Frontend Actions
         */

        public function frontend_action() {
            add_filter( 'fs_affiliates_frontend_dashboard_menu' , array( $this , 'wc_coupon_linking_menu' ) , 14 , 3 ) ;
            add_action( 'fs_affiliates_dashboard_content_wc_coupon_linking' , array( $this , 'dashboard_content_for_affiliate_coupon' ) , 10 , 2 ) ;
        }

        /*
         * Actions
         */

        public function actions() {
            add_filter( 'fs_affiliates_order_affiliate_id' , array( $this , 'get_affiliate_id_from_coupon' ) , 1 , 3 ) ;
            add_filter( 'fs_affiliates_order_commission' , array( $this , 'get_affiliate_commission_from_coupon' ) , 1 , 4 ) ;
        }

        /*
         * Apply commission to linked affiliate from the coupon
         */

        public function get_manual_order_commission( $order_id ) {
            if ( empty( $order_id ) ) {
                return ;
            }

            $order_obj = wc_get_order( $order_id ) ;

            if ( ! is_object( $order_obj ) ) {
                return ;
            }

            $referral_limit = apply_filters( 'fs_affiliates_is_restricted_referral' , true , $order_obj->get_user_id() , $order_obj ) ;

            if ( ! $referral_limit )
                return ;

            $affiliate_id = $this->get_affiliate_id_from_coupon( 0 , $order_id , $order_obj ) ;

            if ( empty( $affiliate_id ) ) {
                return ;
            }

            $commission_to_award = apply_filters( 'fs_affiliates_order_commission' , 0 , $order_id , $order_obj , $affiliate_id ) ;

            if ( empty( $commission_to_award ) && ( 'string' != gettype( $commission_to_award ) || 'no_commission' != $commission_to_award ) )
                $commission_to_award = FS_Affiliates_WC_Commission::award_commission_for_product_purchase( $order_id , $affiliate_id ) ;

            update_post_meta( $order_id , 'fs_affiliate_in_order' , $affiliate_id ) ;
            update_post_meta( $order_id , 'fs_commission_to_be_awarded_in_order' , $commission_to_award ) ;

            do_action( 'fs_affiliates_update_order_meta' , $order_id , $affiliate_id , $order_obj ) ;
        }

        /*
         * Insert Coupon Menu in Dashboard
         */

        public function wc_coupon_linking_menu( $menus , $user_id , $affiliate_id ) {

            $menus[ 'wc_coupon_linking' ] = array( 'label' => get_option( 'fs_affiliates_dashboard_customization_coupon_label' , 'Linked Coupon(s)' ) , 'code' => 'fa-ticket' ) ;

            return $menus ;
        }

        public function get_affiliate_id_from_coupon( $affiliate_id , $OrderId , $OrderObj ) {
            $ReturnedData = $this->award_commission_for_linked_coupon( $OrderId ) ;

            if ( ! fs_affiliates_check_is_array( $ReturnedData ) )
                return $affiliate_id ;

            $AffiliateId = $ReturnedData[ 'affiliate_id' ] ;

            return $AffiliateId ;
        }

        public function get_affiliate_commission_from_coupon( $commissionvalue , $OrderId , $OrderObj , $AffiliateId ) {
            $ReturnedData = $this->award_commission_for_linked_coupon( $OrderId ) ;

            if ( ! fs_affiliates_check_is_array( $ReturnedData ) )
                return $commissionvalue ;

            $affiliate_commission = $ReturnedData[ 'commission' ] ;
            return $affiliate_commission ;
        }

        public function award_commission_for_linked_coupon( $OrderId ) {
            $OrderObj         = new WC_Order( $OrderId ) ;
            $UserId           = $OrderObj->get_user_id() ;
            $LimitForReferral = apply_filters( 'fs_affiliates_is_restricted_referral' , true , $UserId , $OrderObj ) ;

            if ( ! $LimitForReferral )
                return array() ;

            $UsedCoupons  = $OrderObj->get_coupon_codes() ;
            $BillingEmail = $OrderObj->get_billing_email() ;

            if ( ! fs_affiliates_check_is_array( $UsedCoupons ) )
                return array() ;

            if ( apply_filters( 'fs_affiliates_check_if_last_referral' , false ) ) {
                $UsedCoupons = array_reverse( $UsedCoupons ) ;
            }

            $AffiliateId      = '' ;
            $linked_coupon_id = '' ;

            foreach ( $UsedCoupons as $CouponCode ) {
                $CouponObj = new WC_Coupon( $CouponCode ) ;
                if ( ! is_object( $CouponObj ) )
                    continue ;

                $linked_coupon_id = is_linked_coupon( $CouponObj->get_id() ) ;
                if ( empty( $linked_coupon_id ) ) {
                    continue ;
                }

                $linked_coupon = new FS_Linked_Affiliates_Data( $linked_coupon_id ) ;
                if ( empty( $linked_coupon->post_author ) ) {
                    continue ;
                }

                $AffiliateId = $linked_coupon->post_author ;
                break ;
            }

            if ( empty( $AffiliateId ) || empty( $linked_coupon_id ) )
                return array() ;

            if ( '2' == $linked_coupon->commission_level ) {
                $CommissionToAward = $this->coupon_level_commission( $OrderObj , $linked_coupon ) ;
            } else {
                $CommissionToAward = FS_Affiliates_WC_Commission::award_commission_for_product_purchase( $OrderId , $AffiliateId ) ;
            }

            update_post_meta( $OrderId , 'fs_affiliate_in_order' , $AffiliateId ) ;

            return array( 'affiliate_id' => $AffiliateId , 'commission' => $CommissionToAward ) ;
        }

        /**
         * Get the coupon level commission.
         * 
         * @return float
         */
        public function coupon_level_commission( $OrderObj , $linked_coupon ) {
            if ( '2' == $linked_coupon->commission_type ) {
                return $linked_coupon->commission_value ;
            }

            $commission = floatval( $OrderObj->get_subtotal() ) ;
            // Don't consider discount for the commission.
            if ( 'yes' == get_option( 'fs_affiliates_calculate_commission_before_apply_coupon' ) ) {
                $commission = $commission - floatval( $OrderObj->get_discount_total() ) ;
            }

            // Don't consider tax for the commission.
            if ( 'incl' == get_option( 'woocommerce_tax_display_cart' ) && 'yes' == get_option( 'fs_affiliates_exclude_tax_costs_for_commission_calculation' ) ) {
                $commission = $commission - floatval( $OrderObj->get_total_tax() ) ;
            }

            $commission = ($linked_coupon->commission_value / 100) * $commission ;

            return $commission ;
        }

        /*
         * Insert Coupon Menu Content in Dashboard
         */

        public function dashboard_content_for_affiliate_coupon( $user_id , $AffiliateId ) {
            $args             = array( 'post_type' => 'fs-coupon-linking' , 'numberposts' => -1 , 'post_status' => 'fs_link' , 'author' => $AffiliateId , 'fields' => 'ids' ) ;
            $GetLinkedCoupons = get_posts( $args ) ;
            $count            = count( $GetLinkedCoupons ) ;
            $current_page     = isset( $_REQUEST[ 'page_no' ] ) && $_REQUEST[ 'page_no' ] ? ( int ) $_REQUEST[ 'page_no' ] : 1 ;
            $per_page         = 2 ;
            $offset           = ($current_page - 1) * $per_page ;
            $page_count       = ceil( $count / $per_page ) ;
            ?>
            <div class="fs_affiliates_form">
                <h2><?php _e( 'Linked Coupon(s)' , FS_AFFILIATES_LOCALE ) ; ?></h2>
                <table class="fs_affiliates_coupon_linking_table fs_affiliates_frontend_table">
                    <th class="fs_affiliates_sno fs_affiliates_coupon_linking_sno"><?php _e( 'S.No' , FS_AFFILIATES_LOCALE ) ; ?></th>
                    <th><?php _e( 'Coupon Name' , FS_AFFILIATES_LOCALE ) ; ?></th>
                    <th><?php _e( 'Coupon Attributes' , FS_AFFILIATES_LOCALE ) ; ?></th>
                    <th><?php _e( 'Status' , FS_AFFILIATES_LOCALE ) ; ?></th>
                    <tbody>
                        <?php
                        if ( fs_affiliates_check_is_array( $GetLinkedCoupons ) ) {
                            $args          = array( 'post_type' => 'fs-coupon-linking' , 'offset' => $offset , 'numberposts' => $per_page , 'post_status' => 'fs_link' , 'author' => $AffiliateId , 'fields' => 'ids' ) ;
                            $LinkedCoupons = get_posts( $args ) ;
                            $i             = $offset + 1 ;
                            foreach ( $LinkedCoupons as $LinkedId ) {
                                $LinkedCouponObj = new FS_Linked_Affiliates_Data( $LinkedId ) ;
                                $CouponName      = ! empty( $LinkedCouponObj->coupon_data ) ? get_the_title( $LinkedCouponObj->coupon_data ) : '' ;
                                $Status          = $this->get_coupon_status( $LinkedCouponObj->coupon_data ) ;
                                ?>
                                <tr>
                                    <td data-title="<?php esc_html_e( 'S.No' , FS_AFFILIATES_LOCALE ) ?>" class="fs_affiliates_sno fs_affiliates_coupon_linking_sno"><?php echo $i ; ?></td>
                                    <td data-title="<?php esc_html_e( 'Coupon Name' , FS_AFFILIATES_LOCALE ) ?>" ><?php echo $CouponName ; ?></td>
                                    <td data-title="<?php esc_html_e( 'Coupon Attributes' , FS_AFFILIATES_LOCALE ) ?>" ><?php echo $this->get_coupon_datas( $LinkedCouponObj->coupon_data ) ; ?></td>
                                    <?php if ( in_array( 'Invalid' , $Status ) ) { ?>
                                        <td data-title="<?php esc_html_e( 'Status' , FS_AFFILIATES_LOCALE ) ?>" ><?php _e( 'Invalid' , FS_AFFILIATES_LOCALE ) ; ?></td>
                                    <?php } else { ?>
                                        <td data-title="<?php esc_html_e( 'Status' , FS_AFFILIATES_LOCALE ) ?>" ><?php _e( 'Valid' , FS_AFFILIATES_LOCALE ) ; ?></td>
                                    <?php } ?>
                                </tr>
                                <?php
                                $i ++ ;
                            }
                        }
                        ?>
                    </tbody>
                    <tfoot>
                        <tr style="clear:both;">
                            <td colspan="6" class="footable-visible">
                                <div class="pagination pagination-centered">
                                    <?php
                                    if ( $page_count > 1 ) {
                                        FS_Affiliates_Dashboard::fs_affiliates_set_pagination( $current_page , $page_count ) ;
                                    }
                                    ?>
                                </div>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <?php
        }

        public function get_coupon_datas( $CouponId ) {
            $DataToDisplay = '' ;
            $DiscountType  = get_post_meta( $CouponId , 'discount_type' , true ) ;
            if ( $DiscountType == 'fixed_cart' ) {
                $TypeToDisplay = __( 'Fixed cart discount' , FS_AFFILIATES_LOCALE ) ;
            } elseif ( $DiscountType == 'percent' ) {
                $TypeToDisplay = __( 'Percentage discount' , FS_AFFILIATES_LOCALE ) ;
            } else {
                $TypeToDisplay = __( 'Fixed cart product' , FS_AFFILIATES_LOCALE ) ;
            }
            if ( ! empty( $DiscountType ) )
                $DataToDisplay .= '<b>' . __( 'Discount type : ' , FS_AFFILIATES_LOCALE ) . '</b>' . $TypeToDisplay . '<br />' ;

            $CouponAmount  = get_post_meta( $CouponId , 'coupon_amount' , true ) ;
            $coupon_value  = ($DiscountType != 'percent') ? fs_affiliates_price( $CouponAmount ) : $CouponAmount . ' %' ;
            $DataToDisplay .= '<b>' . __( 'Coupon amount : ' , FS_AFFILIATES_LOCALE ) . '</b>' . $coupon_value . '<br/>' ;

            $ExpiryDate    = get_post_meta( $CouponId , 'expiry_date' , true ) ;
            if ( ! empty( $ExpiryDate ) )
                $DataToDisplay .= '<b>' . __( 'Coupon expiry date : ' , FS_AFFILIATES_LOCALE ) . '</b>' . $ExpiryDate . '<br/>' ;

            $MinimumSpend  = get_post_meta( $CouponId , 'minimum_amount' , true ) ;
            if ( ! empty( $MinimumSpend ) )
                $DataToDisplay .= '<b>' . __( 'Minimum spend : ' , FS_AFFILIATES_LOCALE ) . '</b>' . fs_affiliates_price( $MinimumSpend ) . '<br/>' ;

            $MaximumSpend  = get_post_meta( $CouponId , 'maximum_amount' , true ) ;
            if ( ! empty( $MaximumSpend ) )
                $DataToDisplay .= '<b>' . __( 'Maximum spend : ' , FS_AFFILIATES_LOCALE ) . '</b>' . fs_affiliates_price( $MaximumSpend ) . '<br/>' ;

            $IndividualUse = get_post_meta( $CouponId , 'individual_use' , true ) ;
            if ( ! empty( $IndividualUse ) )
                $DataToDisplay .= '<b>' . __( 'Individual use only : ' , FS_AFFILIATES_LOCALE ) . '</b>' . $IndividualUse . '<br/>' ;

            $ExcludeSaleItem = get_post_meta( $CouponId , 'exclude_sale_items' , true ) ;
            if ( ! empty( $ExcludeSaleItem ) )
                $DataToDisplay   .= '<b>' . __( 'Exclude sale items : ' , FS_AFFILIATES_LOCALE ) . '</b>' . $ExcludeSaleItem . '<br/>' ;

            $UsageLimitPerCoupon = get_post_meta( $CouponId , 'usage_limit' , true ) ;
            if ( ! empty( $UsageLimitPerCoupon ) )
                $DataToDisplay       .= '<b>' . __( 'Usage limit per coupon : ' , FS_AFFILIATES_LOCALE ) . '</b>' . $UsageLimitPerCoupon . '<br/>' ;

            $UsageLimitPerUser = get_post_meta( $CouponId , 'usage_limit_per_user' , true ) ;
            if ( ! empty( $UsageLimitPerUser ) )
                $DataToDisplay     .= '<b>' . __( 'Usage limit per user : ' , FS_AFFILIATES_LOCALE ) . '</b>' . $UsageLimitPerUser . '<br/>' ;

            $IncludedProducts = get_post_meta( $CouponId , 'product_ids' , true ) ;
            if ( ! empty( $IncludedProducts ) ) {
                $ExplodedIncProducts = explode( ',' , $IncludedProducts ) ;
                if ( fs_affiliates_check_is_array( $ExplodedIncProducts ) ) {
                    $TitleofProducts = $this->get_product_and_category_title( $ExplodedIncProducts ) ;
                    $DataToDisplay   .= '<b>' . __( 'Included Products : ' , FS_AFFILIATES_LOCALE ) . '</b>' . $TitleofProducts . '<br/>' ;
                }
            }

            $ExcludedProducts = get_post_meta( $CouponId , 'exclude_product_ids' , true ) ;
            if ( ! empty( $ExcludedProducts ) ) {
                $ExplodedExcProducts = explode( ',' , $ExcludedProducts ) ;
                if ( fs_affiliates_check_is_array( $ExplodedExcProducts ) ) {
                    $TitleofProducts = $this->get_product_and_category_title( $ExplodedExcProducts ) ;
                    $DataToDisplay   .= '<b>' . __( 'Excluded Products : ' , FS_AFFILIATES_LOCALE ) . '</b>' . $TitleofProducts . '<br/>' ;
                }
            }

            $IncludedCategories = get_post_meta( $CouponId , 'product_categories' , true ) ;
            if ( ! empty( $IncludedCategories ) ) {
                if ( fs_affiliates_check_is_array( $IncludedCategories ) ) {
                    $TitleofProducts = $this->get_product_and_category_title( $IncludedCategories ) ;
                    $DataToDisplay   .= '<b>' . __( 'Included Categories : ' , FS_AFFILIATES_LOCALE ) . '</b>' . $TitleofProducts . '<br/>' ;
                }
            }

            $ExcludedCategories = get_post_meta( $CouponId , 'exclude_product_categories' , true ) ;
            if ( ! empty( $ExcludedCategories ) ) {
                if ( fs_affiliates_check_is_array( $ExcludedCategories ) ) {
                    $TitleofProducts = $this->get_product_and_category_title( $ExcludedCategories ) ;
                    $DataToDisplay   .= '<b>' . __( 'Excluded Categories : ' , FS_AFFILIATES_LOCALE ) . '</b>' . $TitleofProducts . '<br/>' ;
                }
            }

            return $DataToDisplay ;
        }

        public function get_coupon_status( $CouponId ) {
            $StatusofCoupon    = array() ;
            $CouponObj         = new WC_Coupon( $CouponId ) ;
            $UsageCountPerUser = $CouponObj->get_usage_count() ;
            $ExpiryDate        = get_post_meta( $CouponId , 'expiry_date' , true ) ;
            if ( ! empty( $ExpiryDate ) ) {
                $CurrentDate = date( 'Y-m-d' ) ;
                if ( strtotime( $CurrentDate ) > strtotime( $ExpiryDate ) ) {
                    $StatusofCoupon[] = 'Invalid' ;
                } else {
                    $StatusofCoupon[] = 'Valid' ;
                }
            }

            $UsageLimitPerUser = get_post_meta( $CouponId , 'usage_limit_per_user' , true ) ;
            if ( ! empty( $UsageLimitPerUser ) ) {
                if ( $UsageCountPerUser >= $UsageLimitPerUser ) {
                    $StatusofCoupon[] = 'Invalid' ;
                } else {
                    $StatusofCoupon[] = 'Valid' ;
                }
            }
            return $StatusofCoupon ;
        }

        public function get_product_and_category_title( $ArrayList ) {
            $TitleofProduct = array() ;
            foreach ( $ArrayList as $ProductId ) {
                $ProductTitle = get_the_title( $ProductId ) ;
                if ( $ProductTitle != '' ) {
                    $ListofTitle = $ProductTitle ;
                } else {
                    $CatObj      = get_term( $ProductId ) ;
                    $ListofTitle = is_object( $CatObj ) ? $CatObj->name : '' ;
                }
                $TitleofProduct[] = $ListofTitle ;
            }
            return implode( ',' , $TitleofProduct ) ;
        }

        /*
         * Extra Fields
         */

        public function display_linked_table() {
            if ( ! class_exists( 'FS_Affiliates_WC_Coupon_Linking_Post_Table' ) ) {
                require_once( FS_AFFILIATES_PLUGIN_PATH . '/inc/admin/menu/wp-list-table/class-fs-affiliates-wc-coupon-linking-table.php' ) ;
            }

            $new_section_url = add_query_arg( array( 'page' => 'fs_affiliates' , 'tab' => 'modules' , 'section' => $this->id , 'subsection' => 'new_linking' ) , admin_url( 'admin.php' ) ) ;
            echo '<div class="' . $this->plugin_slug . '_table_wrap">' ;
            echo '<h2 class="wp-heading-inline">' . __( 'WooCommerce Coupon Linking' , FS_AFFILIATES_LOCALE ) . '</h2>' ;

            echo '<a class="page-title-action ' . $this->plugin_slug . '_add_btn" href="' . $new_section_url . '">' . __( 'Link Coupon' , FS_AFFILIATES_LOCALE ) . '</a>' ;
            if ( isset( $_REQUEST[ 's' ] ) && strlen( $_REQUEST[ 's' ] ) ) {
                /* translators: %s: search keywords */
                printf( ' <span class="subtitle">' . __( 'Search results for &#8220;%s&#8221;' ) . '</span>' , $_REQUEST[ 's' ] ) ;
            }

            $post_table = new FS_Affiliates_WC_Coupon_Linking_Post_Table() ;
            $post_table->prepare_items() ;
            $post_table->views() ;
            $post_table->display() ;
            echo '</div>' ;
        }

        /**
         * Output the new affiliate page
         */
        public function display_new_link_page() {

            include_once('views/wc-coupon-linking-new.php' ) ;
        }

        /**
         * Output the edit affiliate page
         */
        public function display_edit_link_page() {
            if ( ! isset( $_GET[ 'id' ] ) )
                return ;

            $post_id     = $_GET[ 'id' ] ;
            $post_object = new FS_Linked_Affiliates_Data( $post_id ) ;

            include_once( 'views/wc-coupon-linking-edit.php' ) ;
        }

        /**
         * Save settings.
         */
        public function before_save() {

            if ( ! empty( $_POST[ 'link_new_affiliates' ] ) ) {
                $this->link_new_affiliates() ;
            } elseif ( ! empty( $_POST[ 'edit_linked_affiliates' ] ) ) {
                $this->update_linked_affiliates() ;
            }
        }

        /*
         * Link a new affiliates
         */

        public function link_new_affiliates() {
            global $current_sub_section ;
            if ( $current_sub_section == '' )
                return ;

            check_admin_referer( $this->plugin_slug . '_link_new_affiliates' , '_' . $this->plugin_slug . '_nonce' ) ;

            try {
                $meta_data = $_POST[ 'coupon_linking' ] ;
                if ( ! isset( $meta_data[ 'coupon_data' ] ) )
                    throw new Exception( __( 'Please select a coupon' , FS_AFFILIATES_LOCALE ) ) ;

                if ( ! isset( $meta_data[ 'affiliate_id' ] ) )
                    throw new Exception( __( 'Please select an affiliate' , FS_AFFILIATES_LOCALE ) ) ;

                $post_args = array(
                    'post_status' => $meta_data[ 'status' ] ,
                    'post_author' => $meta_data[ 'affiliate_id' ][ 0 ] ,
                        ) ;

                $meta_data[ 'coupon_data' ]      = isset( $meta_data[ 'coupon_data' ][ 0 ] ) ? $meta_data[ 'coupon_data' ][ 0 ] : '' ;
                $meta_data[ 'commission_level' ] = isset( $meta_data[ 'commission_level' ] ) ? $meta_data[ 'commission_level' ] : '' ;
                $meta_data[ 'commission_type' ]  = isset( $meta_data[ 'commission_type' ] ) ? $meta_data[ 'commission_type' ] : '' ;
                $meta_data[ 'commission_value' ] = isset( $meta_data[ 'commission_value' ] ) ? fs_affiliates_format_decimal( $meta_data[ 'commission_value' ] , true ) : '' ;

                fs_affiliates_link_new_affiliate( $meta_data , $post_args ) ;

                unset( $_POST[ 'coupon_linking' ] ) ;

                FS_Affiliates_Settings::add_message( __( 'Coupon has been Linked successfully.' , FS_AFFILIATES_LOCALE ) ) ;
            } catch ( Exception $ex ) {
                FS_Affiliates_Settings::add_error( $ex->getMessage() ) ;
            }
        }

        /*
         * Update a linked affiliates
         */

        public function update_linked_affiliates() {
            global $current_sub_section ;
            if ( $current_sub_section == '' )
                return ;

            check_admin_referer( $this->plugin_slug . '_edit_linked_affiliates' , '_' . $this->plugin_slug . '_nonce' ) ;

            try {
                $meta_data = $_POST[ 'coupon_linking' ] ;
                if ( ! isset( $meta_data[ 'coupon_data' ] ) )
                    throw new Exception( __( 'Please select a coupon' , FS_AFFILIATES_LOCALE ) ) ;

                if ( ! isset( $meta_data[ 'affiliate_id' ] ) )
                    throw new Exception( __( 'Please select an affiliate' , FS_AFFILIATES_LOCALE ) ) ;

                $post_args = array(
                    'post_status' => $meta_data[ 'status' ] ,
                    'post_author' => $meta_data[ 'affiliate_id' ][ 0 ] ,
                        ) ;

                $meta_data[ 'coupon_data' ]      = isset( $meta_data[ 'coupon_data' ][ 0 ] ) ? $meta_data[ 'coupon_data' ][ 0 ] : '' ;
                $meta_data[ 'commission_level' ] = isset( $meta_data[ 'commission_level' ] ) ? $meta_data[ 'commission_level' ] : '' ;
                $meta_data[ 'commission_type' ]  = isset( $meta_data[ 'commission_type' ] ) ? $meta_data[ 'commission_type' ] : '' ;
                $meta_data[ 'commission_value' ] = isset( $meta_data[ 'commission_value' ] ) ? fs_affiliates_format_decimal( $meta_data[ 'commission_value' ] , true ) : '' ;

                //update Affiliate
                fs_affiliates_update_linked_affiliate( $_REQUEST[ 'id' ] , $meta_data , $post_args ) ;

                unset( $_POST[ 'coupon_linking' ] ) ;

                FS_Affiliates_Settings::add_message( __( 'Coupon Linking has been updated successfully.' , FS_AFFILIATES_LOCALE ) ) ;
            } catch ( Exception $ex ) {
                FS_Affiliates_Settings::add_error( $ex->getMessage() ) ;
            }
        }

    }

}

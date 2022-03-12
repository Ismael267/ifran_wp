<?php
/**
 * WooCommerce Product Level Settings for Affiliate
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}
if ( ! class_exists( 'FS_Affiliates_WC_Product_Level_Settings' ) ) {

    /**
     * Class FS_Affiliates_WC_Product_Level_Settings
     */
    class FS_Affiliates_WC_Product_Level_Settings {

        public static function product_level_settings_for_simple_product() {
            global $post ;
            ?>
            <div class="options_group show_if_simple show_if_external">
                <?php
                woocommerce_wp_select(
                        array(
                            'id'      => 'fs_commission_type_for_affiliate_in_product_level' ,
                            'class'   => 'fs_commission_type_for_affiliate_in_product_level' ,
                            'label'   => __( 'Commission Type' , 'rewardsystem' ) ,
                            'options' => array(
                                '2' => __( 'Percentage Based Commission' , FS_AFFILIATES_LOCALE ) ,
                                '1' => __( 'Fixed Value Commission' , FS_AFFILIATES_LOCALE ) ,
                            )
                        )
                ) ;
                woocommerce_wp_text_input(
                        array(
                            'id'          => 'fs_commission_value_for_affiliate_in_product_level' ,
                            'class'       => 'fs_commission_value_for_affiliate_in_product_level fs_affiliates_input_price' ,
                            'name'        => 'fs_commission_value_for_affiliate_in_product_level' ,
                            'desc_tip'    => 'true' ,
                            'description' => __( 'When left empty, Category, Affiliate and Global Settings will be considered in the same order and Current Settings (Product Settings) will be ignored. '
                                    . 'When value greater than or equal to 0 is entered then Current Settings (Product Settings) will be considered and Category/Global Settings will be ignored.  ' , FS_AFFILIATES_LOCALE ) ,
                            'label'       => __( 'Commission value' , FS_AFFILIATES_LOCALE ) ,
                            'value'       => fs_affiliates_format_decimal( get_post_meta( $post->ID , 'fs_commission_value_for_affiliate_in_product_level' , true ) )
                        )
                ) ;
                woocommerce_wp_checkbox(
                        array(
                            'id'    => 'fs_block_commission_for_product' ,
                            'class' => 'fs_block_commission_for_product' ,
                            'label' => __( 'Block Affiliate Commission for this Product' , FS_AFFILIATES_LOCALE ) ,
                        )
                ) ;
                ?>
            </div>
            <?php
        }

        public static function product_level_settings_for_variable_product( $loop , $variation_data , $variations ) {

            $CommissionType  = '' ;
            $CommissionValue = '' ;
            $variation_data  = get_post_meta( $variations->ID ) ;
            if ( isset( $variation_data[ 'fs_commission_type_for_affiliate_in_variation_level' ][ 0 ] ) )
                $CommissionType  = $variation_data[ 'fs_commission_type_for_affiliate_in_variation_level' ][ 0 ] ;

            woocommerce_wp_select(
                    array(
                        'id'       => 'fs_commission_type_for_affiliate_in_variation_level[' . $loop . ']' ,
                        'label'    => __( 'Commission Type' , FS_AFFILIATES_LOCALE ) ,
                        'desc_tip' => true ,
                        'class'    => 'fs_commission_type_for_affiliate_in_variation_level' ,
                        'value'    => $CommissionType ,
                        'default'  => '2' ,
                        'options'  => array(
                            '2' => __( 'Percentage Based Commission' , FS_AFFILIATES_LOCALE ) ,
                            '1' => __( 'Fixed Value Commission' , FS_AFFILIATES_LOCALE ) ,
                        )
                    )
            ) ;

            if ( isset( $variation_data[ 'fs_commission_value_for_affiliate_in_variation_level' ][ 0 ] ) )
                $CommissionValue = $variation_data[ 'fs_commission_value_for_affiliate_in_variation_level' ][ 0 ] ;

            woocommerce_wp_text_input(
                    array(
                        'id'    => 'fs_commission_value_for_affiliate_in_variation_level[' . $loop . ']' ,
                        'label' => __( 'Commission value' , FS_AFFILIATES_LOCALE ) ,
                        'class' => 'fs_commission_value_for_affiliate_in_variation_level fs_affiliates_input_price' ,
                        'value' => fs_affiliates_format_decimal( $CommissionValue ) ,
                    )
            ) ;

            if ( isset( $variation_data[ 'fs_block_commission_for_variant' ][ 0 ] ) )
                $BlockCommission = $variation_data[ 'fs_block_commission_for_variant' ][ 0 ] ;

            woocommerce_wp_checkbox(
                    array(
                        'id'    => 'fs_block_commission_for_variant[' . $loop . ']' ,
                        'class' => 'fs_block_commission_for_variant' ,
                        'label' => __( 'Block Affiliate Commission for this Product' , FS_AFFILIATES_LOCALE ) ,
                        'value' => $BlockCommission ,
                    )
            ) ;
        }

        public static function save_product_level_settings( $PostId , $Post ) {
            if ( isset( $_POST[ 'fs_commission_type_for_affiliate_in_product_level' ] ) )
                update_post_meta( $PostId , 'fs_commission_type_for_affiliate_in_product_level' , $_POST[ 'fs_commission_type_for_affiliate_in_product_level' ] ) ;

            if ( isset( $_POST[ 'fs_commission_value_for_affiliate_in_product_level' ] ) )
                update_post_meta( $PostId , 'fs_commission_value_for_affiliate_in_product_level' , fs_affiliates_format_decimal( $_POST[ 'fs_commission_value_for_affiliate_in_product_level' ] , true ) ) ;

            $BlockCommission = isset( $_POST[ 'fs_block_commission_for_product' ] ) ? $_POST[ 'fs_block_commission_for_product' ] : 'no' ;
            update_post_meta( $PostId , 'fs_block_commission_for_product' , $BlockCommission ) ;
        }

        public static function save_variant_level_settings( $VariationId , $i ) {

            $CommissionType = isset( $_POST[ 'fs_commission_type_for_affiliate_in_variation_level' ] ) ? $_POST[ 'fs_commission_type_for_affiliate_in_variation_level' ] : array() ;
            if ( isset( $CommissionType[ $i ] ) )
                update_post_meta( $VariationId , 'fs_commission_type_for_affiliate_in_variation_level' , stripslashes( $CommissionType[ $i ] ) ) ;

            $CommissionValue = isset( $_POST[ 'fs_commission_value_for_affiliate_in_variation_level' ] ) ? $_POST[ 'fs_commission_value_for_affiliate_in_variation_level' ] : array() ;
            if ( isset( $CommissionValue[ $i ] ) )
                update_post_meta( $VariationId , 'fs_commission_value_for_affiliate_in_variation_level' , fs_affiliates_format_decimal( stripslashes( $CommissionValue[ $i ] ) , true ) ) ;

            $BlockCommission = isset( $_POST[ 'fs_block_commission_for_variant' ] ) ? $_POST[ 'fs_block_commission_for_variant' ] : array() ;
            if ( isset( $BlockCommission[ $i ] ) ) {
                update_post_meta( $VariationId , 'fs_block_commission_for_variant' , stripslashes( $BlockCommission[ $i ] ) ) ;
            } else {
                update_post_meta( $VariationId , 'fs_block_commission_for_variant' , 'no' ) ;
            }
        }

    }

}

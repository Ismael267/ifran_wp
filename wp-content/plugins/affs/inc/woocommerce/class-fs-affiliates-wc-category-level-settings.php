<?php
/**
 * WooCommerce Category Level Settings for Affiliate
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}
if ( ! class_exists( 'FS_Affiliates_WC_Category_Level_Settings' ) ) {

    /**
     * Class FS_Affiliates_WC_Category_Level_Settings
     */
    class FS_Affiliates_WC_Category_Level_Settings {

        public static function category_level_settings_in_add_form() {
            ?>
            <div class="form-field">
                <label for="fs_commission_type_for_affiliate_in_category_level"><?php esc_html_e( 'Commission Type' , FS_AFFILIATES_LOCALE ) ; ?></label>
                <select id="fs_commission_type_for_affiliate_in_category_level" name="fs_commission_type_for_affiliate_in_category_level" class="postform">
                    <option value="2"><?php esc_html_e( 'Percentage Based Commission' , FS_AFFILIATES_LOCALE ) ; ?></option>
                    <option value="1"><?php esc_html_e( 'Fixed Value Commission' , FS_AFFILIATES_LOCALE ) ; ?></option>
                </select>
                <p><?php
                    esc_html_e( 'Category Settings will be considered when Product Settings is Enabled and Values are Empty. '
                            . 'Priority Order is Product Settings, Category Settings, Affiliate Settings and Global Settings in the Same Order. ' , FS_AFFILIATES_LOCALE ) ;
                    ?></p>
            </div>
            <div class="form-field">
                <label for="fs_commission_value_for_affiliate_in_category_level"><?php esc_html_e( 'Commission value' , FS_AFFILIATES_LOCALE ) ; ?></label>
                <input type="text" name="fs_commission_value_for_affiliate_in_category_level" id="fs_commission_value_for_affiliate_in_category_level" class="fs_affiliates_input_price" value=""/>
                <p><?php esc_html_e( 'When left empty, Product, Affiliate and Global Settings will be considered in the same order and Current Settings (Category Settings) will be ignored. When value greater than or equal to 0 is entered then Current Settings (Category Settings) will be considered and Product/Global Settings will be ignored.' , FS_AFFILIATES_LOCALE )
                    ?></p>
            </div>
            <div class="form-field">
                <label for="fs_block_commission_for_category"><?php esc_html_e( 'Block Affiliate Commission for this Category' , FS_AFFILIATES_LOCALE ) ; ?></label>
                <input type="checkbox" name="fs_block_commission_for_category" id="fs_block_commission_for_category"/>
                <p><?php esc_html_e( 'When left empty, Product, Affiliate and Global Settings will be considered in the same order and Current Settings (Category Settings) will be ignored. When value greater than or equal to 0 is entered then Current Settings (Category Settings) will be considered and Product/Global Settings will be ignored.' , FS_AFFILIATES_LOCALE )
                    ?></p>
            </div>
            <?php
        }

        public static function category_level_settings_in_edit_form( $Term , $Taxonomy ) {
            $CommissionType  = get_term_meta( $Term->term_id , 'fs_commission_type_for_affiliate_in_category_level' , true ) ;
            $CommissionValue = get_term_meta( $Term->term_id , 'fs_commission_value_for_affiliate_in_category_level' , true ) ;
            $BlockCommission = get_term_meta( $Term->term_id , 'fs_block_commission_for_category' ) ;
            ?>
            <tr class="form-field">
                <th scope="row" valign="top"><label> <?php esc_html_e( 'Commission Type' , FS_AFFILIATES_LOCALE ) ; ?></label></th>
                <td>
                    <select id="fs_commission_type_for_affiliate_in_category_level" name="fs_commission_type_for_affiliate_in_category_level" class="postform">
                        <option value="2"<?php selected( '2' , $CommissionType ) ; ?>><?php esc_html_e( 'Percentage Based Commission' , FS_AFFILIATES_LOCALE ) ; ?></option>
                        <option value="1"<?php selected( '1' , $CommissionType ) ; ?>><?php esc_html_e( 'Fixed Value Commission' , FS_AFFILIATES_LOCALE ) ; ?></option>
                    </select>
                    <p><?php
                        esc_html_e( 'Category Settings will be considered when Product Settings is Enabled and Values are Empty. '
                                . 'Priority Order is Product Settings, Category Settings, Affiliate Settings and Global Settings in the Same Order. ' , 'rewardsystem' ) ;
                        ?></p>
                </td>
            </tr>
            <tr class="form-field">
                <th scope="row" valign="top"><label><?php esc_html_e( 'Commission value' , FS_AFFILIATES_LOCALE ) ; ?></label></th>
                <td>
                    <input type="text" class="fs_affiliates_input_price"  name="fs_commission_value_for_affiliate_in_category_level" id="fs_commission_value_for_affiliate_in_category_level" value="<?php echo fs_affiliates_format_decimal( $CommissionValue ) ; ?>"/>
                    <p><?php esc_html_e( 'When left empty, Product, Affiliate and Global Settings will be considered in the same order and Current Settings (Category Settings) will be ignored. When value greater than or equal to 0 is entered then Current Settings (Category Settings) will be considered and Product/Global Settings will be ignored' , FS_AFFILIATES_LOCALE )
                        ?></p>
                </td>
            </tr>
            <tr class="form-field">
                <th scope="row" valign="top"><label><?php esc_html_e( 'Block Affiliate Commission for this Category' , FS_AFFILIATES_LOCALE ) ; ?></label></th>
                <td>
                    <input type="checkbox" <?php if ( $BlockCommission == 'on' ) { ?>checked="checked"<?php } ?> name="fs_block_commission_for_category" id="fs_block_commission_for_category"/>
                    <p><?php esc_html_e( 'When left empty, Product, Affiliate and Global Settings will be considered in the same order and Current Settings (Category Settings) will be ignored. When value greater than or equal to 0 is entered then Current Settings (Category Settings) will be considered and Product/Global Settings will be ignored' , FS_AFFILIATES_LOCALE )
                        ?></p>
                </td>
            </tr>
            <?php
        }

        public static function save_category_level_settings( $TermId , $TTId , $Taxonomy ) {
            if ( isset( $_POST[ 'fs_commission_type_for_affiliate_in_category_level' ] ) )
                update_term_meta( $TermId , 'fs_commission_type_for_affiliate_in_category_level' , $_POST[ 'fs_commission_type_for_affiliate_in_category_level' ] ) ;

            if ( isset( $_POST[ 'fs_commission_value_for_affiliate_in_category_level' ] ) )
                update_term_meta( $TermId , 'fs_commission_value_for_affiliate_in_category_level' , fs_affiliates_format_decimal( $_POST[ 'fs_commission_value_for_affiliate_in_category_level' ] , true ) ) ;

            if ( isset( $_POST[ 'fs_block_commission_for_category' ] ) ) {
                update_term_meta( $TermId , 'fs_block_commission_for_category' , $_POST[ 'fs_block_commission_for_category' ] ) ;
            } else {
                update_term_meta( $TermId , 'fs_block_commission_for_category' , 'no' ) ;
            }
        }

    }

}

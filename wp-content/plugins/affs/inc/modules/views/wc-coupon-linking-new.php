<?php
/* New Affiliates Page */

if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}
$affiliate_id     = isset( $_POST[ 'coupon_linking' ][ 'affiliate_id' ] ) ? $_POST[ 'coupon_linking' ][ 'affiliate_id' ] : array() ;
$coupon_id        = isset( $_POST[ 'coupon_linking' ][ 'coupon_data' ] ) ? $_POST[ 'coupon_linking' ][ 'coupon_data' ] : array() ;
$status           = isset( $_POST[ 'coupon_linking' ][ 'status' ] ) ? $_POST[ 'coupon_linking' ][ 'status' ] : '' ;
$commission_level = isset( $_POST[ 'coupon_linking' ][ 'commission_level' ] ) ? $_POST[ 'coupon_linking' ][ 'commission_level' ] : '1' ;
$commission_type  = isset( $_POST[ 'coupon_linking' ][ 'commission_type' ] ) ? $_POST[ 'coupon_linking' ][ 'commission_type' ] : '1' ;
$commission_value = isset( $_POST[ 'coupon_linking' ][ 'commission_value' ] ) ? $_POST[ 'coupon_linking' ][ 'commission_value' ] : '' ;
?>
<div class="<?php echo $this->plugin_slug ; ?>_affiliates_new">
    <h2><?php _e( 'Affiliate Coupon Linking' , FS_AFFILIATES_LOCALE ) ; ?></h2>
    <table class="form-table">
        <tbody>
            <tr>
                <th scope='row'>
                    <label><?php _e( 'Coupon Name' , FS_AFFILIATES_LOCALE ) ; ?></label>
                </th>
                <td>
                    <?php
                    $args             = array(
                        'id'          => 'user_id' ,
                        'name'        => 'coupon_linking[coupon_data]' ,
                        'list_type'   => 'coupons' ,
                        'action'      => 'fs_coupon_search' ,
                        'placeholder' => __( 'Search a Coupon' , FS_AFFILIATES_LOCALE ) ,
                        'multiple'    => false ,
                        'selected'    => true ,
                        'options'     => ( array ) $coupon_id
                            ) ;
                    fs_affiliates_select2_html( $args ) ;
                    ?>
                </td>
            </tr>
            <tr>
                <th scope='row'>
                    <label><?php _e( 'Affiliate Name' , FS_AFFILIATES_LOCALE ) ; ?></label>
                </th>
                <td>
                    <?php
                    $args             = array(
                        'id'          => 'user_id' ,
                        'name'        => 'coupon_linking[affiliate_id]' ,
                        'list_type'   => 'affiliates' ,
                        'action'      => 'fs_affiliates_search' ,
                        'placeholder' => __( 'Search a Affiliate' , FS_AFFILIATES_LOCALE ) ,
                        'multiple'    => false ,
                        'selected'    => true ,
                        'options'     => ( array ) $affiliate_id
                            ) ;
                    fs_affiliates_select2_html( $args ) ;
                    ?>
                </td>
            </tr>
            <tr>
                <th scope='row'>
                    <label><?php _e( 'Status' , FS_AFFILIATES_LOCALE ) ; ?></label>
                </th>
                <td>
                    <select name='coupon_linking[status]' class='fs_affiliates_status'>
                        <?php
                        $selected_status  = array(
                            'fs_link'   => __( 'Link' , FS_AFFILIATES_LOCALE ) ,
                            'fs_unlink' => __( 'Unlink' , FS_AFFILIATES_LOCALE ) ,
                                ) ;
                        foreach ( $selected_status as $type => $name ) {
                            ?>
                            <option value="<?php echo $type ; ?>" <?php selected( $status , $type ) ; ?>><?php echo $name ; ?></option>
                        <?php } ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th scope='row'>
                    <label><?php _e( 'Commission Level' , FS_AFFILIATES_LOCALE ) ; ?></label>
                </th>
                <td>
                    <select name='coupon_linking[commission_level]' id='fs_affiliates_coupon_commission_level'>
                        <?php
                        $commission_levels = array(
                            '1' => __( 'Priority Level' , FS_AFFILIATES_LOCALE ) ,
                            '2' => __( 'Coupon Level' , FS_AFFILIATES_LOCALE ) ,
                                ) ;
                        foreach ( $commission_levels as $type => $name ) {
                            ?>
                            <option value="<?php echo $type ; ?>" <?php selected( $commission_level , $type ) ; ?>><?php echo $name ; ?></option>
                        <?php } ?>
                    </select>
                    <p><?php echo wp_kses_post( __( '<b>Priority Level</b> - Commission will be calculated based on Product Level/Category Level/Affiliate Level/Global Level'
                            . '</ br><b>Coupon Level</b> - Commission will be calculated based on the value configured here' , FS_AFFILIATES_LOCALE ) ) ; ?></p>
                </td>
            </tr>
            <tr>
                <th scope='row'>
                    <label><?php _e( 'Commission Type' , FS_AFFILIATES_LOCALE ) ; ?></label>
                </th>
                <td>
                    <select name='coupon_linking[commission_type]' id="fs_affiliates_coupon_commission_type" class='fs_affiliates_coupon_commission_level'>
                        <?php
                        $commission_types = array(
                            '1' => __( 'Percentage Based Commission' , FS_AFFILIATES_LOCALE ) ,
                            '2' => __( 'Fixed Value Commission' , FS_AFFILIATES_LOCALE ) ,
                                ) ;
                        foreach ( $commission_types as $type => $name ) {
                            ?>
                            <option value="<?php echo $type ; ?>" <?php selected( $commission_type , $type ) ; ?>><?php echo $name ; ?></option>
                        <?php } ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th scope='row'>
                    <label><?php _e( 'Commission Value' , FS_AFFILIATES_LOCALE ) ; ?></label>
                </th>
                <td>
                    <input type="text" name='coupon_linking[commission_value]' class='fs_affiliates_commission_value fs_affiliates_coupon_commission_level fs_affiliates_input_price' value='<?php echo fs_affiliates_format_decimal( $commission_value ) ; ?>'/>
                </td>
            </tr>
        </tbody>
    </table>
    <p class="submit">
        <input name='<?php echo $this->plugin_slug ; ?>_save' class='button-primary <?php echo $this->plugin_slug ; ?>_save_btn' type='submit' value="<?php _e( 'Link Coupon' , FS_AFFILIATES_LOCALE ) ; ?>" />
        <input type="hidden" name="link_new_affiliates" value="add-new"/>
        <input type="hidden" name="extra_module_save" value="yes"/>
        <?php
        wp_nonce_field( $this->plugin_slug . '_link_new_affiliates' , '_' . $this->plugin_slug . '_nonce' , false , true ) ;
        ?>
    </p>
</div>

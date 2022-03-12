<?php
/* New Affiliates Creative Page */

if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

$name                = isset( $_POST[ 'creative' ][ 'name' ] ) ? $_POST[ 'creative' ][ 'name' ] : '' ;
$description         = isset( $_POST[ 'creative' ][ 'description' ] ) ? $_POST[ 'creative' ][ 'description' ] : '' ;
$image               = isset( $_POST[ 'creative' ][ 'image' ] ) ? $_POST[ 'creative' ][ 'image' ] : '' ;
$url                 = isset( $_POST[ 'creative' ][ 'url' ] ) ? $_POST[ 'creative' ][ 'url' ] : '' ;
$alternative_text    = isset( $_POST[ 'creative' ][ 'alternative_text' ] ) ? $_POST[ 'creative' ][ 'alternative_text' ] : '' ;
$status              = isset( $_POST[ 'creative' ][ 'status' ] ) ? $_POST[ 'creative' ][ 'status' ] : '' ;
$affiliate_selection = isset( $_POST[ 'creative' ][ 'affiliate_selection' ] ) ? $_POST[ 'creative' ][ 'affiliate_selection' ] : '1' ;
$exclude_affiliates  = isset( $_POST[ 'creative' ][ 'exclude_affiliates' ] ) ? $_POST[ 'creative' ][ 'exclude_affiliates' ] : array() ;
$include_affiliates  = isset( $_POST[ 'creative' ][ 'include_affiliates' ] ) ? $_POST[ 'creative' ][ 'include_affiliates' ] : array() ;
?>
<div class="<?php echo $this->plugin_slug ; ?>_affiliates_creative_new">
    <h2><?php _e( 'New Creative' , FS_AFFILIATES_LOCALE ) ; ?></h2>
    <table class="form-table">
        <tbody>
            <tr>
                <th scope='row'>
                    <label><?php esc_html_e( 'Name' , FS_AFFILIATES_LOCALE ) ; ?></label>
                </th>
                <td>
                    <input type="text" name='creative[name]' value='<?php echo $name ; ?>'/>
                </td>
            </tr>
            <tr>
                <th scope='row'>
                    <label><?php esc_html_e( 'Affiliate(s) Selection' , FS_AFFILIATES_LOCALE ) ; ?></label>
                </th>
                <td>
                    <select name='creative[affiliate_selection]' class = 'fs_affiliates_allowed_affiliates'>
                        <?php
                        $options             = array(
                            '1' => esc_html__( 'All Affiliates' , FS_AFFILIATES_LOCALE ) ,
                            '2' => esc_html__( 'Include Affiliate(s)' , FS_AFFILIATES_LOCALE ) ,
                            '3' => esc_html__( 'Exclude Affiliate(s)' , FS_AFFILIATES_LOCALE )
                                ) ;
                        foreach ( $options as $id => $option ):
                            ?>
                            <option value="<?php echo esc_attr( $id ) ; ?>" <?php selected( $affiliate_selection , $id ) ; ?>><?php echo esc_html( $option ) ; ?></option>
                        <?php endforeach ; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th scope='row'>
                    <label><?php esc_html_e( 'Select Exclude Affiliate(s)' , FS_AFFILIATES_LOCALE ) ; ?></label>
                </th>
                <td>
                    <?php
                    $selection_args = array(
                        'class'       => 'fs_affiliates_selection fs_affiliates_exclude_affiliate' ,
                        'name'        => 'creative[exclude_affiliates]' ,
                        'list_type'   => 'affiliates' ,
                        'action'      => 'fs_affiliates_search' ,
                        'placeholder' => esc_html__( 'Search a Affiliate' , FS_AFFILIATES_LOCALE ) ,
                        'multiple'    => true ,
                        'selected'    => true ,
                        'options'     => $exclude_affiliates ,
                            ) ;
                    fs_affiliates_select2_html( $selection_args ) ;
                    ?>
                </td>
            </tr>
            <tr>
                <th scope='row'>
                    <label><?php esc_html_e( 'Select Include Affiliate(s)' , FS_AFFILIATES_LOCALE ) ; ?></label>
                </th>
                <td>
                    <?php
                    $selection_args = array(
                        'class'       => 'fs_affiliates_selection fs_affiliates_include_affiliate' ,
                        'name'        => 'creative[include_affiliates]' ,
                        'list_type'   => 'affiliates' ,
                        'action'      => 'fs_affiliates_search' ,
                        'placeholder' => esc_html__( 'Search a Affiliate' , FS_AFFILIATES_LOCALE ) ,
                        'multiple'    => true ,
                        'selected'    => true ,
                        'options'     => $include_affiliates ,
                            ) ;
                    fs_affiliates_select2_html( $selection_args ) ;
                    ?>
                </td>
            </tr>
            <tr>
                <th scope='row'>
                    <label><?php esc_html_e( 'Description' , FS_AFFILIATES_LOCALE ) ; ?></label>
                </th>
                <td>
                    <?php wp_editor( $description , 'description' , array( 'media_buttons' => false ) ) ; ?>
                </td>
            </tr>
            <tr>
                <th scope='row'>
                    <label><?php esc_html_e( 'Image' , FS_AFFILIATES_LOCALE ) ; ?></label>
                </th>
                <td>
                    <input type="text" class="fs_creative_upload_image_url" name='creative[image]' value='<?php echo $image ; ?>'/>
                    <input class="fs_creative_upload_image_button button-secondary" data-title="<?php _e( 'Choose Image' , FS_AFFILIATES_LOCALE ) ; ?>"
                           data-button="<?php esc_html_e( 'Use Image' , FS_AFFILIATES_LOCALE ) ; ?>"
                           type="button" value="<?php esc_html_e( 'Choose Image' , FS_AFFILIATES_LOCALE ) ; ?>" />
                    <div id="fs_creative_preview_image">
                        <img src="<?php echo $image ; ?>" />
                    </div>
                </td>
            </tr>
            <tr>
                <th scope='row'>
                    <label><?php esc_html_e( 'URL' , FS_AFFILIATES_LOCALE ) ; ?></label>
                </th>
                <td>
                    <input type="url" name='creative[url]' value='<?php echo $url ; ?>'/>
                </td>
            </tr>
            <tr>
                <th scope='row'>
                    <label><?php esc_html_e( 'Alternative Text' , FS_AFFILIATES_LOCALE ) ; ?></label>
                </th>
                <td>
                    <input type="text" name='creative[alternative_text]' value='<?php echo $alternative_text ; ?>'/>
                </td>
            </tr>
            <tr>
                <th scope='row'>
                    <label><?php esc_html_e( 'Status' , FS_AFFILIATES_LOCALE ) ; ?></label>
                </th>
                <td>
                    <select name='creative[status]'>
                        <?php
                        $status_options = array(
                            'fs_active'   => esc_html__( 'Active' , FS_AFFILIATES_LOCALE ) ,
                            'fs_inactive' => esc_html__( 'Inactive' , FS_AFFILIATES_LOCALE ) ,
                                ) ;
                        foreach ( $status_options as $status_type => $status_name ) {
                            ?>
                            <option value="<?php echo $status_type ; ?>" <?php selected( $status , $status_type ) ?> ><?php echo $status_name ; ?></option>
                        <?php } ?>
                    </select>
                </td>
            </tr>
        </tbody>
    </table>
    <p class="submit">
        <input name='<?php echo $this->plugin_slug ; ?>_save' class='button-primary <?php echo $this->plugin_slug ; ?>_save_btn' type='submit' value="<?php _e( 'Save Creative' , FS_AFFILIATES_LOCALE ) ; ?>" />
        <input type="hidden" name="register_new_creatives" value="add-new"/>
        <?php
        wp_nonce_field( $this->plugin_slug . '_register_new_creatives' , '_' . $this->plugin_slug . '_nonce' , false , true ) ;
        ?>
    </p>
</div>
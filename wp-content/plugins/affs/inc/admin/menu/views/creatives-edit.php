<?php
/* Edit Affiliates Creatives Page */

if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}
?>
<div class="<?php echo $this->plugin_slug ; ?>_affiliates_creative_edit">
    <h2><?php _e( 'Edit Referral' , FS_AFFILIATES_LOCALE ) ; ?></h2>
    <table class="form-table">
        <tbody>
            <tr>
                <th scope='row'>
                    <label><?php _e( 'Name' , FS_AFFILIATES_LOCALE ) ; ?></label>
                </th>
                <td>
                    <input type="hidden" name='creative[id]' value='<?php echo $creative_object->get_id() ; ?>'/>
                    <input type="text" name='creative[name]' value='<?php echo $creative_object->name ; ?>'/>
                </td>
            </tr>
            <tr>
                <th scope='row'>
                    <label><?php esc_html_e( 'Affiliate(s) Selection' , FS_AFFILIATES_LOCALE ) ; ?></label>
                </th>
                <td>
                    <select name='creative[affiliate_selection]' class='fs_affiliates_allowed_affiliates'>
                        <?php
                        $options = array(
                            '1' => esc_html__( 'All Affiliates' , FS_AFFILIATES_LOCALE ) ,
                            '2' => esc_html__( 'Include Affiliate(s)' , FS_AFFILIATES_LOCALE ) ,
                            '3' => esc_html__( 'Exclude Affiliate(s)' , FS_AFFILIATES_LOCALE )
                                ) ;
                        foreach ( $options as $id => $option ):
                            ?>
                            <option value="<?php echo esc_attr( $id ) ; ?>" <?php selected( $creative_object->affiliate_selection , $id ) ; ?>><?php echo esc_html( $option ) ; ?></option>
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
                        'options'     => $creative_object->exclude_affiliates ,
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
                        'options'     => $creative_object->include_affiliates ,
                            ) ;
                    fs_affiliates_select2_html( $selection_args ) ;
                    ?>
                </td>
            </tr>
            <tr>
                <th scope='row'>
                    <label><?php _e( 'Description' , FS_AFFILIATES_LOCALE ) ; ?></label>
                </th>
                <td>
                    <?php wp_editor( $creative_object->description , 'description' , array( 'media_buttons' => false ) ) ; ?>
                </td>
            </tr>
            <tr>
                <th scope='row'>
                    <label><?php _e( 'Image' , FS_AFFILIATES_LOCALE ) ; ?></label>
                </th>
                <td>
                    <input type="text" class="fs_creative_upload_image_url" name='creative[image]' value='<?php echo $creative_object->image ; ?>'/>
                    <input class="fs_creative_upload_image_button button-secondary" data-title="<?php _e( 'Choose Image' , FS_AFFILIATES_LOCALE ) ; ?>"
                           data-button="<?php _e( 'Use Image' , FS_AFFILIATES_LOCALE ) ; ?>"
                           type="button" value="<?php _e( 'Choose Image' , FS_AFFILIATES_LOCALE ) ; ?>" />
                    <div id="fp_creative_preview_image">
                        <img style="width:100px;height:auto;margin-top:10px;" src="<?php echo $creative_object->image ; ?>" />
                    </div>
                </td>
            </tr>
            <tr>
                <th scope='row'>
                    <label><?php _e( 'URL' , FS_AFFILIATES_LOCALE ) ; ?></label>
                </th>
                <td>
                    <input type="url" name='creative[url]' value='<?php echo $creative_object->url ; ?>'/>
                </td>
            </tr>
            <tr>
                <th scope='row'>
                    <label><?php _e( 'Alternative Text' , FS_AFFILIATES_LOCALE ) ; ?></label>
                </th>
                <td>
                    <input type="text" name='creative[alternative_text]' value='<?php echo $creative_object->alternative_text ; ?>'/>
                </td>
            </tr>
            <tr>
                <th scope='row'>
                    <label><?php _e( 'Status' , FS_AFFILIATES_LOCALE ) ; ?></label>
                </th>
                <td>
                    <select name='creative[status]'>
                        <?php
                        $status_options = array(
                            'fs_active'   => __( 'Active' , FS_AFFILIATES_LOCALE ) ,
                            'fs_inactive' => __( 'Inactive' , FS_AFFILIATES_LOCALE ) ,
                                ) ;
                        foreach ( $status_options as $status_type => $status_name ) {
                            ?>
                            <option value="<?php echo $status_type ; ?>" <?php selected( $creative_object->get_status() , $status_type ) ; ?>><?php echo $status_name ; ?></option>
                        <?php } ?>
                    </select>
                </td>
            </tr>
        </tbody>
    </table>
    <p class="submit">
        <input name='<?php echo $this->plugin_slug ; ?>_save' class='button-primary <?php echo $this->plugin_slug ; ?>_save_btn' type='submit' value="<?php _e( 'Update Creative' , FS_AFFILIATES_LOCALE ) ; ?>" />
        <input type="hidden" name="edit_creatives" value="add-edit"/>
        <?php
        wp_nonce_field( $this->plugin_slug . '_edit_creatives' , '_' . $this->plugin_slug . '_nonce' , false , true ) ;
        ?>
    </p>
</div>
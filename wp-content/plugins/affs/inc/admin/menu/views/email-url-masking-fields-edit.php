<?php
/* Edit Affiliates Creatives Page */

if ( !defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}


$affs_domain = isset( $affs_domain ) ? $affs_domain : '' ;
?>

<div class="<?php echo $this->plugin_slug ; ?>_form_fields_edit">
    <?php
    if ( $sub_section == 'add_new_domain' ) {
        $form_title = 'Add New Domain for Masking' ;
    } else {
        $form_title = 'Edit Masked Domain' ;
    }
    ?>
    <h2><?php _e( $form_title , FS_AFFILIATES_LOCALE ) ; ?></h2>
    <table class="form-table">
        <tbody>
            <tr>
                <th scope='row'>
                    <label><?php _e( 'Affiliate Name' , FS_AFFILIATES_LOCALE ) ; ?></label>
                </th>
                <td>
                    <?php
                    if ( $sub_section == 'add_new_domain' ) {

                        $suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min' ;

                        FS_Affiliates_Admin_Assets::select2( $suffix ) ;

                        fs_affiliates_select2_html(
                                array (
                                    'title'     => __( 'Selected Affiliates' , FS_AFFILIATES_LOCALE ) ,
                                    'id'        => 'fs_affiliates_add_new_domain' ,
                                    'type'      => 'ajaxmultiselect' ,
                                    'class'     => 'fs_affiliates_selected_affiliate' ,
                                    'css'       => 'width:250px;' ,
                                    'list_type' => 'affiliates' ,
                                    'multiple'  => false ,
                                    'action'    => 'fs_affiliates_search' ,
                                )
                        ) ;
                    } else {
                        ?>
                        <input type="hidden" name='form_field[field_key]' value='<?php echo $field_key ; ?>'/>
                        <label><?php echo $affs_name ; ?></label>
                        <?php
                    }
                    ?>   
                </td>
            </tr>
            <?php
            if ( $sub_section != 'add_new_domain' ) {
                ?>
                <tr>
                    <th scope='row'>
                        <label><?php _e( 'Affiliate ID' , FS_AFFILIATES_LOCALE ) ; ?></label>
                    </th>
                    <td>
                        <label><?php echo $affs_id ; ?></label>
                    </td>
                </tr>

            <?php } ?>

            <tr>
                <th scope='row'>
                    <label><?php _e( 'Domain Name' , FS_AFFILIATES_LOCALE ) ; ?></label>
                </th>
                <td>  
                    <input type="text" name='form_field[domain_name]' value='<?php echo $affs_domain ; ?>'/>
                </td>
            </tr>

            <?php
            if ( $sub_section != 'add_new_domain' ) {
                ?>
                <tr>
                    <th scope='row'>
                        <label><?php _e( 'Date' , FS_AFFILIATES_LOCALE ) ; ?></label>
                    </th>
                    <td>
                        <label><?php echo $affs_date ; ?></label>
                    </td>
                </tr>
                <?php
            }
            if ( $sub_section != 'add_new_domain' ) {
                ?>
                <tr>
                    <th scope='row'>
                        <label><?php _e( 'Status' , FS_AFFILIATES_LOCALE ) ; ?></label>
                    </th>
                    <td>
                        <select name='form_field[field_status]'>
                            <?php
                            $status_options = array (
                                'fs_active'           => __( 'Active' , FS_AFFILIATES_LOCALE ) ,
                                'fs_pending_approval' => __( 'Pending Approval' , FS_AFFILIATES_LOCALE ) ,
                                'fs_suspended'        => __( 'Suspended' , FS_AFFILIATES_LOCALE ) ,
                                'fs_rejected'         => __( 'Rejected' , FS_AFFILIATES_LOCALE ) ,
                                    ) ;
                            foreach ( $status_options as $status_type => $status_name ) {
                                ?>
                                <option value="<?php echo $status_type ; ?>" <?php selected( $domain_req_status , $status_type ) ; ?>><?php echo $status_name ; ?></option>
                            <?php } ?>
                        </select>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    <p class="submit">
        <?php
        $value = ($sub_section == 'add_new_domain') ? 'add_new' : 'edit' ;

        $label = ($sub_section == 'add_new_domain') ? 'Add New Domain' : 'Edit Domain' ;
        ?>
        <input class='button-primary <?php echo $this->plugin_slug ; ?>_save_btn' type='submit' value="<?php _e( $label , FS_AFFILIATES_LOCALE ) ; ?>" />

        <input type="hidden" name="url_masking_fields" value="<?php echo $value ; ?>"/>
    </p>
</div>

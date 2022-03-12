<?php
/* Export Visit Layout */

if ( !defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}
?><h2><?php _e( 'Export Visits' , FS_AFFILIATES_LOCALE ) ; ?></h2>
<form method="POST" class="fs_affiliates_export_visit_data_form">
    <table class="form-table">
        <tbody>
            <tr>
                <th><label><?php _e( 'Affiliate Selection' , FS_AFFILIATES_LOCALE ) ?></label></th>
                <td>
                    <select name="fs_affiliates_selection" class="fs_affiliates_allowed_affiliates_method">
                        <option value="1"><?php _e( 'All Affiliates' , FS_AFFILIATES_LOCALE ) ?></option>
                        <option value="2"><?php _e( 'Selected Affiliates' , FS_AFFILIATES_LOCALE ) ?></option>
                    </select>
                </td>
            </tr>
            <tr>
                <th><label><?php _e( 'Select Affiliates' , FS_AFFILIATES_LOCALE ) ?></label></th>
                <td>
                    <?php
                    $parent_selection_args = array (
                        'id'          => 'fs_affiliates_selected_affiliates' ,
                        'list_type'   => 'affiliates' ,
                        'class'       => 'fs_affiliates_selected_affiliate' ,
                        'action'      => 'fs_affiliates_search' ,
                        'placeholder' => __( 'Search a Affiliate' , FS_AFFILIATES_LOCALE ) ,
                        'selected'    => true ,
                            ) ;
                    fs_affiliates_select2_html( $parent_selection_args ) ;
                    ?>
                </td>
            </tr>
            <tr>
                <th><label><?php _e( 'From Date' , FS_AFFILIATES_LOCALE ) ?></label></th>
                <td>
                    <input type="text" name="from_date" class="fs_affilaites_datepicker" value=""/>
                </td>
            </tr>
            <tr>
                <th><label><?php _e( 'To Date' , FS_AFFILIATES_LOCALE ) ?></label></th>
                <td>
                    <input type="text" name="to_date" class="fs_affilaites_datepicker" value=""/>
                </td>
            </tr>
            <tr>
                <th><label><?php _e( 'Visit Status' , FS_AFFILIATES_LOCALE ) ?></label></th>
                <td>
                    <select name="fs_affiliates_status">
                        <option value="all"><?php _e( 'All Statuses' , FS_AFFILIATES_LOCALE ) ?></option>
                        <option value="fs_converted"><?php _e( 'Converted' , FS_AFFILIATES_LOCALE ) ?></option>
                        <option value="fs_notconverted"><?php _e( 'Not Converted' , FS_AFFILIATES_LOCALE ) ?></option>
                    </select>
                </td>
            </tr>
            <tr>
                <th><label><?php _e( 'Export Visits' , FS_AFFILIATES_LOCALE ) ?></label></th>
                <td>
                    <input type="hidden" name="fs_nonce" value="<?php echo wp_create_nonce( 'export_visits' ) ?>"/>
                    <input type="hidden" name="action" value="export_visits"/>
                    <input type="submit" style="margin-top: 0px !important" class="fs_affiliates_export_data fs_affiliates_add_btn" value="<?php _e( 'Export Visits as CSV' , FS_AFFILIATES_LOCALE ) ; ?>"/>
                </td>
            </tr>
        </tbody>
    </table>
</form>
<?php

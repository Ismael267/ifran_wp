<?php

//============================================================+
// File name   : example_048.php
// Begin       : 2009-03-20
// Last Update : 2013-05-14
//
// Description : Example 048 for TCPDF class
//               HTML tables and table headers
//
// Author: Nicola Asuni
//
// (c) Copyright:
//               Nicola Asuni
//               Tecnick.com LTD
//               www.tecnick.com
//               info@tecnick.com
//============================================================+

/**
 * Creates an example PDF TEST document using TCPDF
 * @package com.tecnick.tcpdf
 * @abstract TCPDF - Example: HTML tables and table headers
 * @author Nicola Asuni
 * @since 2009-03-20
 */
ob_start () ;

class MYPDF_invoice extends TCPDF {

    public function Header() {
        // Logo
        $payout_statements = new FS_Affiliates_Payout_Statements () ;
        $image_file        = $payout_statements->image_url ;
        $logo_max_width    = $payout_statements->logo_max_percent ;

        if ( $image_file != '' ) {
            $image_details = getimagesize ( $image_file ) ;
            $width         = $image_details[ 0 ] ;
            $height        = $image_details[ 1 ] ;
            $percetage     = $logo_max_width / 100 ;
            if ( $logo_max_width != '' ) {
                $new_image_width  = $percetage * $width ;
                $new_image_height = ($height / $width) * $new_image_width ;
                $width            = $new_image_width ;
                $height           = $new_image_height ;
            }

              $image_html_path = '<table><tr>'
                      . '<td><p><img src="' . $image_file . '" alt="alt attribute" width="' . $width . 'px" height="' . $height . 'px" ></p></td>'
                      . '<td align="center"><h2>Payout Statements</h2></td>'
                      . '<td></td></tr></table>';
//writing header html information
                $this->writeHTML ( $image_html_path , true , false , true , false , '' ) ;
        }

    }

    // Page footer
    public function Footer() {

        $statement_object = new FS_Affiliates_Payout_Statements () ;
        $footer_text      = $statement_object->footer_text ;
        // Position at 15 mm from bottom
        $this->SetY ( -15 ) ;
        // Page number
        $this->Cell ( 0 , 10 , 'Page ' . $this->getAliasNumPage () . '/' . $this->getAliasNbPages () , 0 , false , 'C' , 0 , '' , 0 , false , 'T' , 'M' ) ;

        $this->Cell ( 0 , 10 , $footer_text , 0 , false , 'C' , 0 , '' , 0 , false , 'T' , 'M' ) ;
    }

}

// create new PDF document
$pdf = new MYPDF_invoice ( PDF_PAGE_ORIENTATION , PDF_UNIT , PDF_PAGE_FORMAT , true , 'UTF-8' , false ) ;

// set document information
$pdf->SetCreator ( PDF_CREATOR ) ;
$pdf->SetTitle ( 'Sumo Affiliate Pro - Payouts PDF' ) ;

// set default header data
$pdf->SetHeaderData ( PDF_HEADER_LOGO , PDF_HEADER_LOGO_WIDTH , PDF_HEADER_TITLE . ' 048' , PDF_HEADER_STRING ) ;

// set header and footer fonts
$pdf->setHeaderFont ( Array ( PDF_FONT_NAME_MAIN , '' , PDF_FONT_SIZE_MAIN ) ) ;
$pdf->setFooterFont ( Array ( PDF_FONT_NAME_DATA , '' , PDF_FONT_SIZE_DATA ) ) ;

// set default monospaced font
$pdf->SetDefaultMonospacedFont ( PDF_FONT_MONOSPACED ) ;

// set margins
$pdf->SetMargins ( PDF_MARGIN_LEFT , PDF_MARGIN_TOP , PDF_MARGIN_RIGHT ) ;
$pdf->SetHeaderMargin ( PDF_MARGIN_HEADER ) ;
$pdf->SetFooterMargin ( PDF_MARGIN_FOOTER ) ;

// set auto page breaks
$pdf->SetAutoPageBreak ( TRUE , PDF_MARGIN_BOTTOM ) ;

// set image scale factor
$pdf->setImageScale ( PDF_IMAGE_SCALE_RATIO ) ;

// set some language-dependent strings (optional)
if ( @file_exists ( dirname ( __FILE__ ) . '/lang/eng.php' ) ) {
    require_once(dirname ( __FILE__ ) . '/lang/eng.php') ;
    $pdf->setLanguageArray ( $l ) ;
}

// ---------------------------------------------------------
// add a page
$pdf->AddPage () ;

$statement_object = new FS_Affiliates_Payout_Statements () ;

//General Datas
$random_length   = $statement_object->char_count ;
$prefix          = $statement_object->prefix ;
$suffix          = $statement_object->suffix ;
$sequence_number = $statement_object->sequence_number ;

$statement_name_label = $statement_object->name_label_heading ;


$statement_date_heading = $statement_object->date_heading ;

//Admin Section
$admin_section_heading = $statement_object->admin_section_heading ;
$admin_section         = $statement_object->admin_section ;

//Billing Section
$billing_details_section_label = $statement_object->billing_details_section_label ;

$affs_object    = new FS_Affiliates_Data ( $affiliate_id ) ;
$name_label     = $affs_object->name_label_heading ; 
$addr1_label    = $affs_object->addr1_label ;
$addr2_label    = $affs_object->addr2_label ;
$city_label     = $affs_object->city_label ;
$state_label    = $affs_object->state_label ;
$zip_code_label = $affs_object->zip_code_label ;
$tax_cred_label = $affs_object->tax_cred_label ;

//Table Heading Section
$statement_table_heading = $statement_object->table_heading ;

//Additional Details Section
$statement_additional_heading = $statement_object->additional_details_heading ;
$statement_additional_details = $statement_object->additional_details ;

$payout_object = new FS_Affiliates_Payouts ( $payout_id ) ;
$paid_amount   = $payout_object->paid_amount ;
$payout_date   = fs_affiliates_local_datetime ( $payout_object->date ) ;


if(get_option('fs_affiliates_payout_statements_payout_name_disp_type', '2') == '1'){
    $date_format = get_option('fs_affiliates_payout_statements_file_name_format', 'Y-m-d @ H.i.s' );
    $statement_name = 'Payout-'. date($date_format , current_time( 'timestamp'));
} else{
    $statement_name = get_payout_pdf_statement_name ( $sequence_number , $random_length , $prefix , $suffix ) ;
}

$prepare_statement_name = '<b>' . __ ( $statement_name_label , FS_AFFILIATES_LOCALE ) . '</b>' . '<br>' . $statement_name ;

$prepare_statement_date = '<b>' . __ ( $statement_date_heading , FS_AFFILIATES_LOCALE ) . '</b>' . '<br>' . $payout_date ;

$prepare_admin_address = '<br><b>' . $admin_section_heading . '</b>' . '<br>' . $admin_section ;

$prepare_affs_address = '<br><b>' . $billing_details_section_label . '</b>' . '<br>' . $name_label . '<br>' . $addr1_label . '<br>' . $addr2_label . '<br>' . $city_label . '<br>' . $state_label . '<br>' . $zip_code_label . '<br>' . $tax_cred_label ;

$statement_particulars = '<table style="display:block;" cellpadding="5"> 
<tr align="center"><td >' . $prepare_statement_name . '</td><td>' . $prepare_statement_date . '</td></tr>
</table>' ;

$pdf->writeHTML ( $statement_particulars , true , false , false , false , '' ) ;

$table_address = '<table cellpadding="5" > 
<tr><td style="border:1px solid #ccc;" >' . $prepare_admin_address . '</td><td style="border:1px solid #ccc;" >' . $prepare_affs_address . '</td></tr>
</table>' ;

$pdf->writeHTML ( $table_address , true , false , false , false , '' ) ;

$Referral_object = new FS_Affiliates_Payouts ( $payout_id ) ;

$ReferralIDs = ( array ) get_post_meta ( $payout_id , 'referral_id' , true ) ;
$ReferralIDs = reset ( $ReferralIDs ) ;

$thead = '<tr>
    <th style="border:1px solid #ccc;"><b> ' . __ ( 'Referral ID' , FS_AFFILIATES_LOCALE ) . '</b></th>
    <th style="border:1px solid #ccc;"><b>' . __ ( 'Description' , FS_AFFILIATES_LOCALE ) . '</b></th>
    <th style="border:1px solid #ccc;"><b>' . __ ( 'Date' , FS_AFFILIATES_LOCALE ) . '</b></th>
    <th style="border:1px solid #ccc;"><b>' . __ ( 'Amount' , FS_AFFILIATES_LOCALE ) . '</b></th>
</tr>' ;

if ( ! fs_affiliates_check_is_array ( $ReferralIDs ) )
    return ;


$row_content = '<tbody>' ;

foreach ( $ReferralIDs as $Id ) {
    $ReferralObj = new FS_Affiliates_Referrals ( $Id ) ;
    $row_content .= '<tr>
                    <td style="border:1px solid #ccc;">' . '#' . $Id . '</td>
                         <td style="border:1px solid #ccc;">' . $ReferralObj->description . '</td>
                               <td style="border:1px solid #ccc;">' . fs_affiliates_local_datetime ( $ReferralObj->date ) . '</td> 
                              <td style="border:1px solid #ccc;">' . fs_affiliates_price ( $ReferralObj->amount ) . '</td> 
                </tr>' ;
}

$row_content .= '<tr>
                <td></td>
                     <td ></td>
                      <td style="border:1px solid #ccc;"><b>' . __ ( 'TOTAL' , FS_AFFILIATES_LOCALE ) . '</b> </td>
                          <td style="border:1px solid #ccc;"><b>' . fs_affiliates_price ( $paid_amount ) . '</b></td>
                   
                </tr>' ;

$row_content .= '</tbody>' ;
$tbl         = '<h2>' . __ ( $statement_table_heading , FS_AFFILIATES_LOCALE ) . '</h2>
<table cellpadding="5" border="0">
 ' . $thead . ' 
    ' . $row_content . '
</table>' ;

$pdf->writeHTML ( $tbl , true , false , false , false , '' ) ;

$additional_info = "<h2>" . __ ( $statement_additional_heading , FS_AFFILIATES_LOCALE ) . "</h2>
<table border='1'>  
<tr align='center'><td>$statement_additional_details</td><td></td></tr>
</table>" ;

$pdf->writeHTML ( $additional_info , true , false , false , false , '' ) ;


$upload_dir        = wp_upload_dir () ;
$current_year      = date ( 'Y' ) ;
$current_month     = date ( 'm' ) ;
$year_dir_path     = $upload_dir[ 'basedir' ] . '/Sumo_Affiliate_Pro_uploads/' . $current_year ;
$year_dir_path_url = $upload_dir[ 'baseurl' ] . '/Sumo_Affiliate_Pro_uploads/' . $current_year ;
if ( ! file_exists ( $year_dir_path ) && ! is_dir ( $year_dir_path ) ) {
    wp_mkdir_p ( $year_dir_path ) ;
}
//month dir creation
$month_dir_path = $year_dir_path . '/' . $current_month ;
$month_dir_url  = $year_dir_path_url . '/' . $current_month ;
if ( ! file_exists ( $month_dir_path ) && ! is_dir ( $month_dir_path ) ) {
    wp_mkdir_p ( $month_dir_path ) ;
}

$statement_name = $month_dir_path . '/' . $statement_name . '.pdf' ;

$normalized_path = wp_normalize_path ( $statement_name ) ;

$update_payout_datas = new FS_Affiliates_Payouts ( $payout_id ) ;

$update_payout_datas->update_meta ( 'pay_statement_file_name' , $normalized_path ) ;

$statement_object->update_option ( 'sequence_number' , (( int ) $sequence_number) + 1 ) ;


$pdf->Output ( $normalized_path , 'F' ) ;

chmod ( $normalized_path , 0777 ) ;

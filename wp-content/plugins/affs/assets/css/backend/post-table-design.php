<?php $color_mode = get_option( 'fs_affiliates_settings_color_mode', 1 ) ; ?>
<style>
    /*post table design*/
    .fs_affiliates_tab_inner_content table td input[type='email'],
    .fs_affiliates_tab_inner_content table td input[type='url']{
        box-shadow: 0 -5px 10px 2px rgba(0, 0, 0, 0.03) inset;
        border: none;
        width: 350px !important;
        border: 1px solid #ccc;
        border-radius: 5px;
        height: 30px !important;
    }
    .fs_affiliates_tab_inner_content .fs_affiliates_table_wrap #fs_affiliates_search-search-input{
        box-shadow: 0 -5px 10px 2px rgba(0, 0, 0, 0.03) inset;
        border: none;
        width: 250px !important;
        border: 1px solid #ccc;
        border-radius: 5px;
        height: 30px !important;
    }
    .fs_affiliates_tab_inner_content .fs_affiliates_table_wrap #fs_affiliates_search-search-input:focus{
        background:none !important;
    }

    .fs_affiliates_table_wrap ul.subsubsub{
        clear:both;
    }
    .fs_affiliates_table_wrap .search-box{
        margin-top:10px !important;
    }
    .fs_affiliates_add_btn, .fs_affiliates_ref_add_btn,
    .fs_affiliates_ref_payout_btn,
    .fs_affiliates_paypal_btn,
    .fs_affiliates_Creatives_add_btn,
    .fs_affiliates_add_mlm_rule{
        padding: 10px 15px !important;
        color: #fefefe !important;
        text-align:center;
        height: auto !important;
        border: none !important;
        box-shadow: 0 5px #042c7e !important;
        text-shadow: none !important;
        font-weight: 500;
        font-size:14px !important;
        letter-spacing: 1px;
        margin-top: 20px !important;
        display: block;
        float:left;
        cursor: pointer;
    }
    .fs_affiliates_add_mlm_rule{
        margin-bottom: 26px !important;
    }
    .fs_affiliates_add_btn:active,
    .fs_affiliates_ref_add_btn:active,
    .fs_affiliates_ref_payout_btn:active,
    .fs_affiliates_paypal_btn:active,
    .fs_affiliates_Creatives_add_btn:active,
    .fs_affiliates_add_mlm_rule:active
    {
        transform: translateY(5px);
        box-shadow: none !important;
    }
    .fs_affiliates_tab_inner_content #doaction,
    .fs_affiliates_tab_inner_content #doaction2{
        padding:0 10px 1px !important;
        color: #fefefe !important;
        width:auto!important;
        text-align:center;
        height: auto !important;
        border: none !important;
        box-shadow:none !important;
        text-shadow: none !important;
        font-weight: 500;
        font-size:14px !important;
    }
    .fs_affiliates_tab_inner_content #search-submit{
        padding:0 10px 1px !important;
        color: #fff !important;
        width:auto!important;
        text-align:center;
        height: auto !important;
        box-shadow:none !important;
        text-shadow: none !important;
        font-weight: 500;
        font-size:14px !important;
    }

    fs_affiliates_add_btn:active{
        transform: translateY(5px);
        box-shadow: none !important;
    }
    .fs_affiliates_tab_inner_content table.fs_affiliates_mlm_rules_table{
        margin:20px 0px;
        float:left;
    }
    .fs_affiliates_tab_inner_content table.fs_affiliates_mlm_rules_table{
        border:1px solid #45085e;
    }
    .fs_affiliates_tab_inner_content table.fs_affiliates_mlm_rules_table tbody tr:nth-child(even){
        background:#f9f9f9;
    }
    .fs_affiliates_tab_inner_content table.fs_affiliates_mlm_rules_table tbody td{
        vertical-align: middle !important;
    }
    .fs_affiliates_tab_inner_content table.fs_affiliates_mlm_rules_table tbody td span{
        font-size:14px;
        color:#000;
    }
    .fs_affiliates_tab_inner_content table.fs_affiliates_mlm_rules_table p{
        text-align:center;
    }
    .fs_affiliates_tab_inner_content table.fs_affiliates_mlm_rules_table p.fs_affiliates_remove_mlm_rule img{
        cursor:pointer;
    }
    .fs_affiliates_tab_inner_content table.toplevel_page_fs_affiliates{
        border:none !important;
    }
    .fs_affiliates_tab_inner_content table.toplevel_page_fs_affiliates thead th .sorting-indicator:before,
    .fs_affiliates_tab_inner_content table.toplevel_page_fs_affiliates tfoot th .sorting-indicator:before{
        color:#fff !important;
    }
    .fs_affiliates_tab_inner_content .inline{
        border:none !important;
        background:#c61759 !important;
        padding-left:10px;
    }
    .fs_affiliates_tab_inner_content .inline i{
        margin-right: 10px;
        font-size: 16px;
    }
    .fs_affiliates_tab_inner_content .error p{
        color:#fff;
    }
    .fp_creative_preview_image {
        text-align:center;
    }
    .fs_affiliates_tab_inner_content table.toplevel_page_fs_affiliates tbody .preview{
        float:left !important;
    }
    .fs_affiliates_tab_inner_content .fs_creative_upload_image_button{
        background: #00796b !important;
        padding:0 10px 1px !important;
        color: #fefefe !important;
        width:auto!important;
        text-align:center;
        height: auto !important;
        border: none !important;
        box-shadow:none !important;
        text-shadow: none !important;
        font-weight: 500;
        font-size:14px !important;
        vertical-align: middle !important
    }
    #fs_creative_preview_image img{
        width:100px;
        height:auto;
        margin-top:10px;
    }
    .fs_affiliates_tab_inner_content .wp-editor-wrap{
        width:500px;
    }
    .fs_affiliates_tab_inner_content .wp-editor-wrap textarea{
        border:none !important;
        width:100% !important;
        height:200px !important;
    }
    .fs_affiliates_fields_sort_handle img{
        cursor: move;
    }
    .fs_affiliates_active_status,
    .fs_affiliates_inactive_status,
    .fs_affiliates_pending_approval_status,
    .fs_affiliates_paid_status,
    .fs_affiliates_unpaid_status,
    .fs_affiliates_rejected_status,
    .fs_affiliates_suspended_status,
    .fs_affiliates_acknowledged_status,
    .fs_affiliates_denied_status,
    .fs_affiliates_pending_status,
    .fs_affiliates_processing_status,
    .fs_affiliates_success_status,
    .fs_affiliates_new_status,
    .fs_affiliates_cancelled_status,
    .fs_affiliates_notconverted_status,
    .fs_affiliates_converted_status,
    .fs_affiliates_active_status,
    .fs_affiliates_hold_status,
    .fs_affiliates_download_btns{
        display: inline-flex;
        border-radius: 4px;
        border-bottom: 1px solid rgba(0,0,0,.05);
        margin: -.25em 0;
        cursor: inherit !important;
        /*    white-space: nowrap;*/
        max-width: 100%;
        padding:0.3em 1em;
        font-weight:bold;
        word-break: break-word;
    }
    .fs_affiliates_active_status{
        background:#02cc4f;
        color:#fff;
    }
    .fs_affiliates_inactive_status{
        background:#eba3a3;
        color:#761919;
    }
    .fs_affiliates_pending_approval_status{
        background:#f55b11;
        color:#fff;
    }
    .fs_affiliates_paid_status{
        background: #c6e1c6;
        color: #5b841b;
    }
    .fs_affiliates_unpaid_status{
        background: #616e70;
        color: #fff;
    }
    .fs_affiliates_rejected_status{
        background: #d63369;
        color: #fff;
    }
    .fs_affiliates_suspended_status{
        background: #287cd6;
        color: #fff;
    }
    .fs_affiliates_acknowledged_status{
        background: #8e1093;
        color: #fff;
    }
    .fs_affiliates_denied_status{
        background: #0b9fba;
        color: #fff;
    }
    .fs_affiliates_pending_status{
        background: #efd812;
        color: #000;
    }
    .fs_affiliates_pending_status{
        background: #efd812;
        color: #000;
    }
    .fs_affiliates_processing_status{
        background: #0498ff;
        color: #fff;
    }
    .fs_affiliates_success_status{
        background: #219b52;
        color: #fff;
    }
    .fs_affiliates_new_status{
        background: #102f80;
        color: #fff;
    }
    .fs_affiliates_cancelled_status{
        background: #ff0000;
        color: #fff;
    }
    .fs_affiliates_notconverted_status{
        background: #c95555;
        color: #fff;
    }
    .fs_affiliates_converted_status{
        background: #6c9606;
        color: #fff;
    }
    .fs_affiliates_hold_status{
        background:#161515;
        color: #fff;
    }
    .fs_affiliates_download_btns{
        background:#2fa3f2;
        color: #fff;
        text-decoration:none;
    }
    .fs_affiliates_download_btns:hover{
        background:#2fa3f2;
        color: #000;

    }
    .fs_affiliates_download_btns i{
        margin-left:10px;
    }

    <?php if ( $color_mode != '2' ) { ?>
        /* Dark change css code */


        .fs_affiliates_add_btn,
        .fs_affiliates_ref_add_btn,
        .fs_affiliates_Creatives_add_btn,
        .fs_affiliates_add_mlm_rule{
            background: #00339a !important;
            box-shadow: 0 5px #042c7e !important;
        }
        .fs_affiliates_ref_payout_btn{
            background: #5d4037 !important;
            box-shadow: 0 5px #412e28 !important;
        }
        .fs_affiliates_paypal_btn{
            background: #f57c00 !important;
            box-shadow: 0 5px #d97810 !important;
        }
        .fs_affiliates_tab_inner_content #doaction,
        .fs_affiliates_tab_inner_content #doaction2{
            background: #138f30 !important;
        }
        .fs_affiliates_tab_inner_content #search-submit{
            background: #00339a !important;
            border:1px solid #00339a !important;
        }
        .fs_affiliates_tab_inner_content #search-submit:hover{
            background:#00339a !important;
            color:#fff !important;
        }
        .fs_affiliates_tab_inner_content table.toplevel_page_fs_affiliates thead,
        .fs_affiliates_tab_inner_content table.toplevel_page_fs_affiliates tfoot,
        .fs_affiliates_tab_inner_content table.fs_affiliates_mlm_rules_table thead{
            background:linear-gradient(#444, #333, #444) !important;
            color:#fff !important;
        }
        .fs_affiliates_tab_inner_content table.toplevel_page_fs_affiliates thead th a,
        .fs_affiliates_tab_inner_content table.toplevel_page_fs_affiliates tfoot th a,
        .fs_affiliates_tab_inner_content table.toplevel_page_fs_affiliates thead th,
        .fs_affiliates_tab_inner_content table.fs_affiliates_mlm_rules_table thead th,
        .fs_affiliates_tab_inner_content table.toplevel_page_fs_affiliates tfoot th{
            color:#fff !important;
        }
        .fs_affiliates_fileupload_table{
            width:100%;
            border-collapse:collapse;
        }
        .fs_affiliates_fileupload_table thead{
            background:linear-gradient(#444, #333, #444) !important;
            color:#fff !important;
            text-align:center;
        }
        .fs_affiliates_fileupload_table tbody td{
            border:1px solid #ccc;
        }
        .fs_affiliates_file_uploader{
            margin:15px 0px;
        }
    <?php } else { ?>
        /* Light change css code */

        .fs_affiliates_add_btn,
        .fs_affiliates_ref_add_btn,
        .fs_affiliates_Creatives_add_btn,
        .fs_affiliates_add_mlm_rule,
        .fs_affiliates_ref_payout_btn,
        .fs_affiliates_paypal_btn{
            background: #444 !important;
            box-shadow: 0 5px #333 !important;
        }
        .fs_affiliates_tab_inner_content #doaction,
        .fs_affiliates_tab_inner_content #doaction2{
            background: #444 !important;
        }
        .fs_affiliates_tab_inner_content #search-submit{
            background: #444 !important;
            border:1px solid #444 !important;
        }
        .fs_affiliates_tab_inner_content #search-submit:hover{
            background:#444 !important;
            color:#fff !important;
        }
        .fs_affiliates_tab_inner_content table.toplevel_page_fs_affiliates thead,
        .fs_affiliates_tab_inner_content table.toplevel_page_fs_affiliates tfoot,
        .fs_affiliates_tab_inner_content table.fs_affiliates_mlm_rules_table thead{
            /*            background:linear-gradient(to bottom, rgba(240,240,240,1) 0%,rgba(255,255,255,1) 50%,rgba(240,240,240,1) 100%);*/
            background:#999;
            color:#fff !important;
        }
        .fs_affiliates_tab_inner_content table.toplevel_page_fs_affiliates thead th a,
        .fs_affiliates_tab_inner_content table.toplevel_page_fs_affiliates tfoot th a,
        .fs_affiliates_tab_inner_content table.toplevel_page_fs_affiliates thead th,
        .fs_affiliates_tab_inner_content table.fs_affiliates_mlm_rules_table thead th,
        .fs_affiliates_tab_inner_content table.toplevel_page_fs_affiliates tfoot th{
            color:#fff !important;
        }
        .fs_affiliates_fileupload_table{
            width:100%;
            border-collapse:collapse;
        }
        .fs_affiliates_fileupload_table thead{
            background:#444 !important;
            color:#fff !important;
            text-align:center;
        }
        .fs_affiliates_fileupload_table tbody td{
            border:1px solid #ccc;
        }
        .fs_affiliates_file_uploader{
            margin:15px 0px;
        }
    <?php } ?>

</style> <?php

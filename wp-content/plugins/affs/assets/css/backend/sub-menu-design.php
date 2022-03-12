<?php $color_mode = get_option( 'fs_affiliates_settings_color_mode' , 1 ) ; ?>
<style>

    /*Submenu design*/

    .fs_affiliates_tab_content ul.fs_affiliates_subtab{
        width:100% !important;
        float:left !important;
        display:block !important;
        margin:0px !important;
        padding:5px 0px !important;
        box-shadow: 0 2px 2px #111 !important;
    }
    .fs_affiliates_tab_content ul.fs_affiliates_subtab li{
        list-style: none;
        margin-left:5px;
    }
    .fs_affiliates_tab_content ul.fs_affiliates_subtab li a{
        padding:7px 12px 7px 12px;
        font-size:13px !important;
        box-shadow: none !important;
        border:none !important;
    }
    .fs_affiliates_tab_content ul.fs_affiliates_subtab li a i{
        margin-right:10px;
        font-size:16px;
    }
    .fs_affiliates_tab_content ul.fs_affiliates_subtab li a.current{
        color:#f55b11 !important;
    }
    .fs_affiliates_tab_content ul.fs_affiliates_subtab li a:active{
        box-shadow: none !important;
        border:none !important;
    }

    <?php if ( $color_mode != '2' ) { ?>

        /* Dark change css code */

        .fs_affiliates_tab_content ul.fs_affiliates_subtab{
            width:100% !important;
            float:left !important;
            display:block !important;
            margin:0px !important;
            padding:5px 0px !important;
            box-shadow: 0 2px 2px #111 !important;
            background:#222 !important;
        }
        .fs_affiliates_tab_content ul.fs_affiliates_subtab li a{
            color:#fff !important;
        }

    <?php } else { ?>

      /* Light change css code */

        .fs_affiliates_tab_content ul.fs_affiliates_subtab{
            width:100% !important;
            float:left !important;
            display:block !important;
            margin:0px !important;
            padding:5px 0px !important;
            box-shadow: 0 2px 2px #111 !important;
            background:#fff !important;
        }
        .fs_affiliates_tab_content ul.fs_affiliates_subtab li a{
            color:#444;
        }
        <?php } ?>

        </style> <?php

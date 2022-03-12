<?php
$dashboard_customization = get_option( 'fs_affiliates_frontend_dashboard_customization' ) ;
    if ( $dashboard_customization == '2' ) {
        ?>
        <style type="text/css">
        /* Dashboard Change Css Code  */
        /*    Model-2   */

        .fs_affiliates_frontend_dashboard{
            background:#ddd;
            border:2px solid #6A486B;
        }
        .fs_affiliates_frontend_dashboard .fs_affiliates_menu{
            background:#6A486B;
        }
        .fs_affiliates_frontend_dashboard .fs_affiliates_menu ul.fs_affiliates_menu_ul li a{
            color:#fff;
        }
        .fs_affiliates_frontend_dashboard .fs_affiliates_menu ul.fs_affiliates_menu_ul li a:hover{
            color:#000;
        }
        .fs_affiliates_frontend_dashboard .fs_affiliates_menu ul.fs_affiliates_menu_ul li a.current{
            color:#000;
        }
        /* Submenu color */

        .fs_affiliates_frontend_dashboard .fs_affiliates_menu ul.fs_affiliates_menu_ul li:hover ul{
            background:#6A486B;
        }
        .fs_affiliates_frontend_dashboard .fs_affiliates_menu ul.fs_affiliates_menu_ul li:hover ul li a{
            background:#6A486B;
            color:#fff; 
        }
        .fs_affiliates_frontend_dashboard .fs_affiliates_menu ul.fs_affiliates_menu_ul li ul li a:hover{
            color:#000;
            background:#926c93;
        }

        /* Menu Content Title border color */

        .fs_affiliates_frontend_dashboard .fs_affiliates_menu_content .fs_affiliates_link_generator h2,
        .fs_affiliates_frontend_dashboard .fs_affiliates_menu_content .fs_affiliates_form h2{
            color:#222;
            border-bottom:1px dashed #352621;
        }

        /* Form Save button */ 

        .fs_affiliates_generate_affiliate_link, .fs_affiliates_form_save{
            background: #352621 !important;
            box-shadow: 0 5px #1d1613 !important;
        }

        /* frontend table design */

        .fs_affiliates_menu_content table.fs_affiliates_referrals_frontend_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_visits_frontend_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_creatives_frontend_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliate_transaction_log_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliate_payout_request_log_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_overview_frontend_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_order_detail_frontend_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_customer_detail_frontend_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_campaigns_frontend_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_commission_frontend_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliate_campaigns_list_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_Payout_frontend_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_leaderboard_frontend_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_fileupload_frontend_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_landing_page_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_coupon_linking_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_domain_table tbody th{
            background:#352621;
            color:#fff;
        }
        @media screen and (max-width: 767px){
            .fs_affiliates_frontend_dashboard .fs_affiliates_menu ul.fs_affiliates_menu_ul li:hover ul li a{
                background:#926c93;
                color:#fff;
            }
            .fs_affiliates_frontend_dashboard .fs_affiliates_menu ul.fs_affiliates_menu_ul li ul li a:hover{
                color:#000;
                background:#926c93;
            }
            .fs_affiliates_menu_content table.fs_affiliates_referrals_frontend_table tbody td:before,
            .fs_affiliates_menu_content table.fs_affiliates_visits_frontend_table tbody td:before,
            .fs_affiliates_menu_content table.fs_affiliates_creatives_frontend_table tbody td:before,
            .fs_affiliates_menu_content table.fs_affiliate_transaction_log_table tbody td:before,
            .fs_affiliates_menu_content table.fs_affiliate_payout_request_log_table tbody td:before,
            .fs_affiliates_menu_content table.fs_affiliates_campaigns_frontend_table tbody td:before,
            .fs_affiliates_menu_content table.fs_affiliate_campaigns_list_table tbody td:before,
            .fs_affiliates_menu_content table.fs_affiliates_Payout_frontend_table tbody td:before,
            .fs_affiliates_menu_content table.fs_affiliates_leaderboard_frontend_table tbody td:before,
            .fs_affiliates_menu_content table.fs_affiliates_fileupload_frontend_table tbody td:before,
            .fs_affiliates_menu_content table.fs_affiliates_landing_page_table tbody td:before,
            .fs_affiliates_menu_content table.fs_affiliates_coupon_linking_table tbody td:before,
            .fs_affiliates_menu_content table.fs_affiliates_domain_table tbody td:before{
                background:#352621;
                color:#fff;
            }
        }
</style>
        <?php
    } else if ( $dashboard_customization == '3' ) {
        ?>
<style type="text/css">
        /*    Model-3   */

        .fs_affiliates_frontend_dashboard{
            background:#ddd;
            border:2px solid #837100;
        }
        .fs_affiliates_frontend_dashboard .fs_affiliates_menu{
            background:#837100;
        }
        .fs_affiliates_frontend_dashboard .fs_affiliates_menu ul.fs_affiliates_menu_ul li a{
            color:#fff;
        }
        .fs_affiliates_frontend_dashboard .fs_affiliates_menu ul.fs_affiliates_menu_ul li a:hover{
            color:#000;
        }
        .fs_affiliates_frontend_dashboard .fs_affiliates_menu ul.fs_affiliates_menu_ul li a.current{
            color:#000;
        }
        /* Submenu color */

        .fs_affiliates_frontend_dashboard .fs_affiliates_menu ul.fs_affiliates_menu_ul li:hover ul{
            background:#837100;
        }
        .fs_affiliates_frontend_dashboard .fs_affiliates_menu ul.fs_affiliates_menu_ul li:hover ul li a{
            background:#837100;
            color:#fff; 
        }
        .fs_affiliates_frontend_dashboard .fs_affiliates_menu ul.fs_affiliates_menu_ul li ul li a:hover{
            color:#000;
            background:#a28d03;
        }

        /* Menu Content Title border color */

        .fs_affiliates_frontend_dashboard .fs_affiliates_menu_content .fs_affiliates_link_generator h2,
        .fs_affiliates_frontend_dashboard .fs_affiliates_menu_content .fs_affiliates_form h2{
            color:#222;
            border-bottom:1px dashed #D1AE00;
        }

        /* Form Save button */

        .fs_affiliates_generate_affiliate_link, .fs_affiliates_form_save, .fs_affiliates_generate_campaign_affiliate_link{
            background: #D1AE00 !important;
            box-shadow: 0 5px #b09206 !important;
        }

        /* frontend table design   */

        .fs_affiliates_menu_content table.fs_affiliates_referrals_frontend_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_visits_frontend_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_creatives_frontend_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliate_transaction_log_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliate_payout_request_log_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_overview_frontend_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_order_detail_frontend_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_customer_detail_frontend_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_campaigns_frontend_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_commission_frontend_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliate_campaigns_list_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_Payout_frontend_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_leaderboard_frontend_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_fileupload_frontend_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_landing_page_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_coupon_linking_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_domain_table tbody th{
            background:#D1AE00;
            color:#fff;
        }
        @media screen and (max-width: 767px){
            .fs_affiliates_frontend_dashboard .fs_affiliates_menu ul.fs_affiliates_menu_ul li:hover ul li a{
                background:#837100;
                color:#fff;
            }
            .fs_affiliates_frontend_dashboard .fs_affiliates_menu ul.fs_affiliates_menu_ul li ul li a:hover{
                color:#000;
                background:#a28d03;
            }
            .fs_affiliates_menu_content table.fs_affiliates_referrals_frontend_table tbody td:before,
            .fs_affiliates_menu_content table.fs_affiliates_visits_frontend_table tbody td:before,
            .fs_affiliates_menu_content table.fs_affiliates_creatives_frontend_table tbody td:before,
            .fs_affiliates_menu_content table.fs_affiliate_transaction_log_table tbody td:before,
            .fs_affiliates_menu_content table.fs_affiliate_payout_request_log_table tbody td:before,
            .fs_affiliates_menu_content table.fs_affiliates_campaigns_frontend_table tbody td:before,
            .fs_affiliates_menu_content table.fs_affiliate_campaigns_list_table tbody td:before,
            .fs_affiliates_menu_content table.fs_affiliates_Payout_frontend_table tbody td:before,
            .fs_affiliates_menu_content table.fs_affiliates_leaderboard_frontend_table tbody td:before,
            .fs_affiliates_menu_content table.fs_affiliates_fileupload_frontend_table tbody td:before,
            .fs_affiliates_menu_content table.fs_affiliates_landing_page_table tbody td:before,
            .fs_affiliates_menu_content table.fs_affiliates_coupon_linking_table tbody td:before,
            .fs_affiliates_menu_content table.fs_affiliates_domain_table tbody td:before{
                background:#D1AE00;
                color:#fff;
            }
        }
        </style>
        <?php
    } else if ( $dashboard_customization == '4' ) {
        ?>
        <style type="text/css">
        /*    Model-4   */

        .fs_affiliates_frontend_dashboard{
            background:#ddd;
            border:2px solid #3F4259;
        }
        .fs_affiliates_frontend_dashboard .fs_affiliates_menu{
            background:#3F4259;
        }
        .fs_affiliates_frontend_dashboard .fs_affiliates_menu ul.fs_affiliates_menu_ul li a{
            color:#fff;
        }
        .fs_affiliates_frontend_dashboard .fs_affiliates_menu ul.fs_affiliates_menu_ul li a:hover{
            color:#0f0;
        }
        .fs_affiliates_frontend_dashboard .fs_affiliates_menu ul.fs_affiliates_menu_ul li a.current{
            color:#0f0;
        }
        /* Submenu color */

        .fs_affiliates_frontend_dashboard .fs_affiliates_menu ul.fs_affiliates_menu_ul li:hover ul{
            background:#3F4259;
        }
        .fs_affiliates_frontend_dashboard .fs_affiliates_menu ul.fs_affiliates_menu_ul li:hover ul li a{
            background:#3F4259;
            color:#fff; 
        }
        .fs_affiliates_frontend_dashboard .fs_affiliates_menu ul.fs_affiliates_menu_ul li ul li a:hover{
            color:#0f0;
            background:#505472;
        }

        /* Menu Content Title border color */

        .fs_affiliates_frontend_dashboard .fs_affiliates_menu_content .fs_affiliates_link_generator h2,
        .fs_affiliates_frontend_dashboard .fs_affiliates_menu_content .fs_affiliates_form h2{
            color:#222;
            border-bottom:1px dashed #79829A;
        }

        /*  Form Save button */

        .fs_affiliates_generate_affiliate_link, .fs_affiliates_form_save, .fs_affiliates_generate_campaign_affiliate_link{
            background: #79829A !important;
            box-shadow: 0 5px #666e82 !important;
        }

        /* frontend table design */  

        .fs_affiliates_menu_content table.fs_affiliates_referrals_frontend_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_visits_frontend_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_creatives_frontend_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliate_transaction_log_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliate_payout_request_log_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_overview_frontend_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_order_detail_frontend_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_customer_detail_frontend_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_campaigns_frontend_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_commission_frontend_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliate_campaigns_list_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_Payout_frontend_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_leaderboard_frontend_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_fileupload_frontend_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_landing_page_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_coupon_linking_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_domain_table tbody th{
            background:#79829A;
            color:#fff;
        }
        @media screen and (max-width: 767px){
            .fs_affiliates_frontend_dashboard .fs_affiliates_menu ul.fs_affiliates_menu_ul li:hover ul li a{
                background:#3F4259;
                color:#fff;
            }
            .fs_affiliates_frontend_dashboard .fs_affiliates_menu ul.fs_affiliates_menu_ul li ul li a:hover{
                color:#0f0;
                background:#505472;
            }
            .fs_affiliates_menu_content table.fs_affiliates_referrals_frontend_table tbody td:before,
            .fs_affiliates_menu_content table.fs_affiliates_visits_frontend_table tbody td:before,
            .fs_affiliates_menu_content table.fs_affiliates_creatives_frontend_table tbody td:before,
            .fs_affiliates_menu_content table.fs_affiliate_transaction_log_table tbody td:before,
            .fs_affiliates_menu_content table.fs_affiliate_payout_request_log_table tbody td:before,
            .fs_affiliates_menu_content table.fs_affiliates_campaigns_frontend_table tbody td:before,
            .fs_affiliates_menu_content table.fs_affiliate_campaigns_list_table tbody td:before,
            .fs_affiliates_menu_content table.fs_affiliates_Payout_frontend_table tbody td:before,
            .fs_affiliates_menu_content table.fs_affiliates_leaderboard_frontend_table tbody td:before,
            .fs_affiliates_menu_content table.fs_affiliates_fileupload_frontend_table tbody td:before,
            .fs_affiliates_menu_content table.fs_affiliates_landing_page_table tbody td:before,
            .fs_affiliates_menu_content table.fs_affiliates_coupon_linking_table tbody td:before,
            .fs_affiliates_menu_content table.fs_affiliates_domain_table tbody td:before{
                background:#79829A;
                color:#fff;
            }
        }
        </style>
        <?php
    } else if ( $dashboard_customization == '5' ) {
        ?>
        <style type="text/css">
        /*    Model-5   */

        .fs_affiliates_frontend_dashboard{
            background:#f5f5f5;
            border:2px solid #9EC621;
        }
        .fs_affiliates_frontend_dashboard .fs_affiliates_menu{
            background:#9EC621;
        }
        .fs_affiliates_frontend_dashboard .fs_affiliates_menu ul.fs_affiliates_menu_ul li a{
            color:#fff;
        }
        .fs_affiliates_frontend_dashboard .fs_affiliates_menu ul.fs_affiliates_menu_ul li a:hover{
            color:#000;
        }
        .fs_affiliates_frontend_dashboard .fs_affiliates_menu ul.fs_affiliates_menu_ul li a.current{
            color:#000;
        }

        /* Submenu color */

        .fs_affiliates_frontend_dashboard .fs_affiliates_menu ul.fs_affiliates_menu_ul li:hover ul{
            background:#9EC621;
        }
        .fs_affiliates_frontend_dashboard .fs_affiliates_menu ul.fs_affiliates_menu_ul li:hover ul li a{
            background:#9EC621;
            color:#fff; 
        }
        .fs_affiliates_frontend_dashboard .fs_affiliates_menu ul.fs_affiliates_menu_ul li ul li a:hover{
            color:#000;
            background:#b0de25;
        }

        /* Menu Content Title border color */

        .fs_affiliates_frontend_dashboard .fs_affiliates_menu_content .fs_affiliates_link_generator h2,
        .fs_affiliates_frontend_dashboard .fs_affiliates_menu_content .fs_affiliates_form h2{
            color:#222;
            border-bottom:1px dashed #DEF3C8;
        }

        /* Form Save button */

        .fs_affiliates_generate_affiliate_link, .fs_affiliates_form_save, .fs_affiliates_generate_campaign_affiliate_link{
            background: #DEF3C8 !important;
            color:#81A610;
            box-shadow: 0 5px #82a221 !important;
        }

        /* frontend table design  */

        .fs_affiliates_menu_content table.fs_affiliates_referrals_frontend_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_visits_frontend_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_creatives_frontend_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliate_transaction_log_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliate_payout_request_log_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_overview_frontend_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_order_detail_frontend_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_customer_detail_frontend_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_campaigns_frontend_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_commission_frontend_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliate_campaigns_list_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_Payout_frontend_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_leaderboard_frontend_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_fileupload_frontend_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_landing_page_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_coupon_linking_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_domain_table tbody th{
            background:#DEF3C8;
            color:#81A610;
        }
        @media screen and (max-width: 767px){
            .fs_affiliates_frontend_dashboard .fs_affiliates_menu ul.fs_affiliates_menu_ul li:hover ul li a{
                background:#9EC621;
                color:#fff;
            }
            .fs_affiliates_frontend_dashboard .fs_affiliates_menu ul.fs_affiliates_menu_ul li ul li a:hover{
                color:#000;
                background:#b0de25;
            }
            .fs_affiliates_menu_content table.fs_affiliates_referrals_frontend_table tbody td:before,
            .fs_affiliates_menu_content table.fs_affiliates_visits_frontend_table tbody td:before,
            .fs_affiliates_menu_content table.fs_affiliates_creatives_frontend_table tbody td:before,
            .fs_affiliates_menu_content table.fs_affiliate_transaction_log_table tbody td:before,
            .fs_affiliates_menu_content table.fs_affiliate_payout_request_log_table tbody td:before,
            .fs_affiliates_menu_content table.fs_affiliates_campaigns_frontend_table tbody td:before,
            .fs_affiliates_menu_content table.fs_affiliate_campaigns_list_table tbody td:before,
            .fs_affiliates_menu_content table.fs_affiliates_Payout_frontend_table tbody td:before,
            .fs_affiliates_menu_content table.fs_affiliates_leaderboard_frontend_table tbody td:before,
            .fs_affiliates_menu_content table.fs_affiliates_fileupload_frontend_table tbody td:before,
            .fs_affiliates_menu_content table.fs_affiliates_landing_page_table tbody td:before,
            .fs_affiliates_menu_content table.fs_affiliates_coupon_linking_table tbody td:before,
            .fs_affiliates_menu_content table.fs_affiliates_domain_table tbody td:before{
                background:#DEF3C8;
                color:#81A610;
            }
        }
        </style>
        <?php
    } else if ( $dashboard_customization == '6' ) {
        ?>
        <style type="text/css">
        /*    Model-6   */

        .fs_affiliates_frontend_dashboard{
            background:#ddd;
            border:2px solid #5105fd;
        }
        .fs_affiliates_frontend_dashboard .fs_affiliates_menu{
            background:#5105fd;
        }
        .fs_affiliates_frontend_dashboard .fs_affiliates_menu ul.fs_affiliates_menu_ul li a{
            color:#fff;
        }
        .fs_affiliates_frontend_dashboard .fs_affiliates_menu ul.fs_affiliates_menu_ul li a:hover{
            color:#000;
        }
        .fs_affiliates_frontend_dashboard .fs_affiliates_menu ul.fs_affiliates_menu_ul li a.current{
            color:#000;
        }

        /* Submenu color */

        .fs_affiliates_frontend_dashboard .fs_affiliates_menu ul.fs_affiliates_menu_ul li:hover ul{
            background:#5105fd;
        }
        .fs_affiliates_frontend_dashboard .fs_affiliates_menu ul.fs_affiliates_menu_ul li:hover ul li a{
            background:#5105fd;
            color:#fff; 
        }
        .fs_affiliates_frontend_dashboard .fs_affiliates_menu ul.fs_affiliates_menu_ul li ul li a:hover{
            color:#000;
            background:#4607d7;
        }

        /* Menu Content Title border color */

        .fs_affiliates_frontend_dashboard .fs_affiliates_menu_content .fs_affiliates_link_generator h2,
        .fs_affiliates_frontend_dashboard .fs_affiliates_menu_content .fs_affiliates_form h2{
            color:#222;
            border-bottom:1px dashed #79829A;
        }

        /* Form Save button */

        .fs_affiliates_generate_affiliate_link, .fs_affiliates_form_save, .fs_affiliates_generate_campaign_affiliate_link{
            background: linear-gradient(to right, #5105fd 0%, #b46df1 100%) !important;
            box-shadow: 0 5px #6d2cff !important;
        }

        /* frontend table design   */

        .fs_affiliates_menu_content table.fs_affiliates_referrals_frontend_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_visits_frontend_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_creatives_frontend_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliate_transaction_log_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliate_payout_request_log_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_overview_frontend_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_order_detail_frontend_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_customer_detail_frontend_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_campaigns_frontend_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_commission_frontend_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliate_campaigns_list_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_Payout_frontend_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_leaderboard_frontend_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_fileupload_frontend_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_landing_page_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_coupon_linking_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_domain_table tbody th{
            background:linear-gradient(to right, #5105fd 0%, #b46df1 100%);
            color:#fff;
        }
        @media screen and (max-width: 767px){
            .fs_affiliates_frontend_dashboard .fs_affiliates_menu ul.fs_affiliates_menu_ul li:hover ul li a{
                background:#5105fd;
                color:#fff;
            }
            .fs_affiliates_frontend_dashboard .fs_affiliates_menu ul.fs_affiliates_menu_ul li ul li a:hover{
                color:#000;
                background:#4607d7;
            }
            .fs_affiliates_menu_content table.fs_affiliates_referrals_frontend_table tbody td:before,
            .fs_affiliates_menu_content table.fs_affiliates_visits_frontend_table tbody td:before,
            .fs_affiliates_menu_content table.fs_affiliates_creatives_frontend_table tbody td:before,
            .fs_affiliates_menu_content table.fs_affiliate_transaction_log_table tbody td:before,
            .fs_affiliates_menu_content table.fs_affiliate_payout_request_log_table tbody td:before,
            .fs_affiliates_menu_content table.fs_affiliates_campaigns_frontend_table tbody td:before,
            .fs_affiliates_menu_content table.fs_affiliate_campaigns_list_table tbody td:before,
            .fs_affiliates_menu_content table.fs_affiliates_Payout_frontend_table tbody td:before,
            .fs_affiliates_menu_content table.fs_affiliates_leaderboard_frontend_table tbody td:before,
            .fs_affiliates_menu_content table.fs_affiliates_fileupload_frontend_table tbody td:before,
            .fs_affiliates_menu_content table.fs_affiliates_landing_page_table tbody td:before,
            .fs_affiliates_menu_content table.fs_affiliates_coupon_linking_table tbody td:before,
            .fs_affiliates_menu_content table.fs_affiliates_domain_table tbody td:before{
                background:linear-gradient(to right, #5105fd 0%, #b46df1 100%);
                color:#fff;
            }
        }
</style>
        <?php
    } else if ( $dashboard_customization == '7' ) {
        ?>
<style type="text/css">
        /*    Model-7   */

        .fs_affiliates_frontend_dashboard{
            background:#ddd;
            border:2px solid #1e2519;
        }
        .fs_affiliates_frontend_dashboard .fs_affiliates_menu{
            background:#1e2519;
        }
        .fs_affiliates_frontend_dashboard .fs_affiliates_menu ul.fs_affiliates_menu_ul li a{
            color:#fff;
        }
        .fs_affiliates_frontend_dashboard .fs_affiliates_menu ul.fs_affiliates_menu_ul li a:hover{
            color:#5b6fff;
        }
        .fs_affiliates_frontend_dashboard .fs_affiliates_menu ul.fs_affiliates_menu_ul li a.current{
            color:#5b6fff;
        }

        /* Submenu color */

        .fs_affiliates_frontend_dashboard .fs_affiliates_menu ul.fs_affiliates_menu_ul li:hover ul{
            background:#1e2519;
        }
        .fs_affiliates_frontend_dashboard .fs_affiliates_menu ul.fs_affiliates_menu_ul li:hover ul li a{
            background:#1e2519;
            color:#fff; 
        }
        .fs_affiliates_frontend_dashboard .fs_affiliates_menu ul.fs_affiliates_menu_ul li ul li a:hover{
            color:#5b6fff;
            background:#2e3827;
        }

        /* Menu Content Title border color */

        .fs_affiliates_frontend_dashboard .fs_affiliates_menu_content .fs_affiliates_link_generator h2,
        .fs_affiliates_frontend_dashboard .fs_affiliates_menu_content .fs_affiliates_form h2{
            color:#222;
            border-bottom:1px dashed #79829A;
        }

        /* Form Save button */

        .fs_affiliates_generate_affiliate_link, .fs_affiliates_form_save, .fs_affiliates_generate_campaign_affiliate_link{
            background: linear-gradient(to right, #3c4b32 0%, #5b6f4c 100%);
            box-shadow: 0 5px #283122 !important;
        }

        /* frontend table design  */ 

        .fs_affiliates_menu_content table.fs_affiliates_referrals_frontend_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_visits_frontend_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_creatives_frontend_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliate_transaction_log_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliate_payout_request_log_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_overview_frontend_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_order_detail_frontend_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_customer_detail_frontend_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_campaigns_frontend_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_commission_frontend_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliate_campaigns_list_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_Payout_frontend_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_leaderboard_frontend_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_fileupload_frontend_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_landing_page_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_coupon_linking_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_domain_table tbody th{
            background:linear-gradient(to right, #3c4b32 0%, #5b6f4c 100%);
            color:#fff;
        }
        @media screen and (max-width: 767px){
            .fs_affiliates_frontend_dashboard .fs_affiliates_menu ul.fs_affiliates_menu_ul li:hover ul li a{
                background:#1e2519;
                color:#fff;
            }
            .fs_affiliates_frontend_dashboard .fs_affiliates_menu ul.fs_affiliates_menu_ul li ul li a:hover{
                color:#5b6fff;
                background:#2e3827;
            }
            .fs_affiliates_menu_content table.fs_affiliates_referrals_frontend_table tbody td:before,
            .fs_affiliates_menu_content table.fs_affiliates_visits_frontend_table tbody td:before,
            .fs_affiliates_menu_content table.fs_affiliates_creatives_frontend_table tbody td:before,
            .fs_affiliates_menu_content table.fs_affiliate_transaction_log_table tbody td:before,
            .fs_affiliates_menu_content table.fs_affiliate_payout_request_log_table tbody td:before,
            .fs_affiliates_menu_content table.fs_affiliates_campaigns_frontend_table tbody td:before,
            .fs_affiliates_menu_content table.fs_affiliate_campaigns_list_table tbody td:before,
            .fs_affiliates_menu_content table.fs_affiliates_Payout_frontend_table tbody td:before,
            .fs_affiliates_menu_content table.fs_affiliates_leaderboard_frontend_table tbody td:before,
            .fs_affiliates_menu_content table.fs_affiliates_fileupload_frontend_table tbody td:before,
            .fs_affiliates_menu_content table.fs_affiliates_landing_page_table tbody td:before,
            .fs_affiliates_menu_content table.fs_affiliates_coupon_linking_table tbody td:before,
            .fs_affiliates_menu_content table.fs_affiliates_domain_table tbody td:before{
                background:linear-gradient(to right, #3c4b32 0%, #5b6f4c 100%);
                color:#fff;
            }
        }
        </style>
        <?php
    } else if ( $dashboard_customization == '8' ) {
        ?>
<style type="text/css">
        /*    Model-8   */

        .fs_affiliates_frontend_dashboard{
            background:#ddd;
            border:2px solid #e9a33f;
        }
        .fs_affiliates_frontend_dashboard .fs_affiliates_menu{
            background:#e9a33f;
        }
        .fs_affiliates_frontend_dashboard .fs_affiliates_menu ul.fs_affiliates_menu_ul li a{
            color:#fff;
        }
        .fs_affiliates_frontend_dashboard .fs_affiliates_menu ul.fs_affiliates_menu_ul li a:hover{
            color:#000;
        }
        .fs_affiliates_frontend_dashboard .fs_affiliates_menu ul.fs_affiliates_menu_ul li a.current{
            color:#000;
        }

        /* Submenu color */ 

        .fs_affiliates_frontend_dashboard .fs_affiliates_menu ul.fs_affiliates_menu_ul li:hover ul{
            background:#e9a33f;
        }
        .fs_affiliates_frontend_dashboard .fs_affiliates_menu ul.fs_affiliates_menu_ul li:hover ul li a{
            background:#e9a33f;
            color:#fff; 
        }
        .fs_affiliates_frontend_dashboard .fs_affiliates_menu ul.fs_affiliates_menu_ul li ul li a:hover{
            color:#000;
            background:#ffb449;
        }

        /* Menu Content Title border color */ 

        .fs_affiliates_frontend_dashboard .fs_affiliates_menu_content .fs_affiliates_link_generator h2,
        .fs_affiliates_frontend_dashboard .fs_affiliates_menu_content .fs_affiliates_form h2{
            color:#222;
            border-bottom:1px dashed #79829A;
        }

        /* Form Save button */ 

        .fs_affiliates_generate_affiliate_link, .fs_affiliates_form_save, .fs_affiliates_generate_campaign_affiliate_link{
            background:linear-gradient(to right, #e9a33f 0%, #c2beb3 100%);;
            box-shadow: 0 5px #d59436 !important;
        }

        /* frontend table design */   

        .fs_affiliates_menu_content table.fs_affiliates_referrals_frontend_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_visits_frontend_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_creatives_frontend_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliate_transaction_log_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliate_payout_request_log_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_overview_frontend_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_order_detail_frontend_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_customer_detail_frontend_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_campaigns_frontend_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_commission_frontend_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliate_campaigns_list_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_Payout_frontend_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_leaderboard_frontend_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_fileupload_frontend_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_landing_page_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_coupon_linking_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_domain_table tbody th{
            background:linear-gradient(to right, #e9a33f 0%, #c2beb3 100%);
            color:#fff;
        }
        @media screen and (max-width: 767px){
            .fs_affiliates_frontend_dashboard .fs_affiliates_menu ul.fs_affiliates_menu_ul li:hover ul li a{
                background:#e9a33f;
                color:#fff;
            }
            .fs_affiliates_frontend_dashboard .fs_affiliates_menu ul.fs_affiliates_menu_ul li ul li a:hover{
                color:#000;
                background:#ffb449;
            }
            .fs_affiliates_menu_content table.fs_affiliates_referrals_frontend_table tbody td:before,
            .fs_affiliates_menu_content table.fs_affiliates_visits_frontend_table tbody td:before,
            .fs_affiliates_menu_content table.fs_affiliates_creatives_frontend_table tbody td:before,
            .fs_affiliates_menu_content table.fs_affiliate_transaction_log_table tbody td:before,
            .fs_affiliates_menu_content table.fs_affiliate_payout_request_log_table tbody td:before,
            .fs_affiliates_menu_content table.fs_affiliates_campaigns_frontend_table tbody td:before,
            .fs_affiliates_menu_content table.fs_affiliate_campaigns_list_table tbody td:before,
            .fs_affiliates_menu_content table.fs_affiliates_Payout_frontend_table tbody td:before,
            .fs_affiliates_menu_content table.fs_affiliates_leaderboard_frontend_table tbody td:before,
            .fs_affiliates_menu_content table.fs_affiliates_fileupload_frontend_table tbody td:before,
            .fs_affiliates_menu_content table.fs_affiliates_landing_page_table tbody td:before,
            .fs_affiliates_menu_content table.fs_affiliates_coupon_linking_table tbody td:before,
            .fs_affiliates_menu_content table.fs_affiliates_domain_table tbody td:before{
                background:linear-gradient(to right, #e9a33f 0%, #c2beb3 100%);
                color:#fff;
            }
        }
        </style>
        <?php
    } else if ( $dashboard_customization == '9' ) {
        ?>
        <style type="text/css">
        /*    Model-9   */

        .fs_affiliates_frontend_dashboard{
            background:#ddd;
            border:2px solid #6093ff;
        }
        .fs_affiliates_frontend_dashboard .fs_affiliates_menu{
            background:#6093ff;
        }
        .fs_affiliates_frontend_dashboard .fs_affiliates_menu ul.fs_affiliates_menu_ul li a{
            color:#fff;
        }
        .fs_affiliates_frontend_dashboard .fs_affiliates_menu ul.fs_affiliates_menu_ul li a:hover{
            color:#000;
        }
        .fs_affiliates_frontend_dashboard .fs_affiliates_menu ul.fs_affiliates_menu_ul li a.current{
            color:#000;
        }
        /* Submenu color */ 

        .fs_affiliates_frontend_dashboard .fs_affiliates_menu ul.fs_affiliates_menu_ul li:hover ul{
            background:#6093ff;
        }
        .fs_affiliates_frontend_dashboard .fs_affiliates_menu ul.fs_affiliates_menu_ul li:hover ul li a{
            background:#6093ff;
            color:#fff; 
        }
        .fs_affiliates_frontend_dashboard .fs_affiliates_menu ul.fs_affiliates_menu_ul li ul li a:hover{
            color:#000;
            background:#5884de;
        }

        /* Menu Content Title border color */ 

        .fs_affiliates_frontend_dashboard .fs_affiliates_menu_content .fs_affiliates_link_generator h2,
        .fs_affiliates_frontend_dashboard .fs_affiliates_menu_content .fs_affiliates_form h2{
            color:#222;
            border-bottom:1px dashed #79829A;
        }

        /* Form Save button */

        .fs_affiliates_generate_affiliate_link, .fs_affiliates_form_save, .fs_affiliates_generate_campaign_affiliate_link{
            background:linear-gradient(to right, #6093ff 0%, #41b7ff 100%);
            box-shadow: 0 5px #5884de !important;
        }

        /* frontend table design */   

        .fs_affiliates_menu_content table.fs_affiliates_referrals_frontend_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_visits_frontend_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_creatives_frontend_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliate_transaction_log_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliate_payout_request_log_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_overview_frontend_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_order_detail_frontend_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_customer_detail_frontend_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_campaigns_frontend_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_commission_frontend_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliate_campaigns_list_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_Payout_frontend_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_leaderboard_frontend_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_fileupload_frontend_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_landing_page_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_coupon_linking_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_domain_table tbody th{
            background:linear-gradient(to right, #6093ff 0%, #41b7ff 100%);
            color:#fff;
        }
        @media screen and (max-width: 767px){
            .fs_affiliates_frontend_dashboard .fs_affiliates_menu ul.fs_affiliates_menu_ul li:hover ul li a{
                background:#6093ff;
                color:#fff;
            }
            .fs_affiliates_frontend_dashboard .fs_affiliates_menu ul.fs_affiliates_menu_ul li ul li a:hover{
                color:#000;
                background:#5884de;
            }
            .fs_affiliates_menu_content table.fs_affiliates_referrals_frontend_table tbody td:before,
            .fs_affiliates_menu_content table.fs_affiliates_visits_frontend_table tbody td:before,
            .fs_affiliates_menu_content table.fs_affiliates_creatives_frontend_table tbody td:before,
            .fs_affiliates_menu_content table.fs_affiliate_transaction_log_table tbody td:before,
            .fs_affiliates_menu_content table.fs_affiliate_payout_request_log_table tbody td:before,
            .fs_affiliates_menu_content table.fs_affiliates_campaigns_frontend_table tbody td:before,
            .fs_affiliates_menu_content table.fs_affiliate_campaigns_list_table tbody td:before,
            .fs_affiliates_menu_content table.fs_affiliates_Payout_frontend_table tbody td:before,
            .fs_affiliates_menu_content table.fs_affiliates_leaderboard_frontend_table tbody td:before,
            .fs_affiliates_menu_content table.fs_affiliates_fileupload_frontend_table tbody td:before,
            .fs_affiliates_menu_content table.fs_affiliates_landing_page_table tbody td:before,
            .fs_affiliates_menu_content table.fs_affiliates_coupon_linking_table tbody td:before,
            .fs_affiliates_menu_content table.fs_affiliates_domain_table tbody td:before{
                background:linear-gradient(to right, #6093ff 0%, #41b7ff 100%);
                color:#fff;
            }
        }
        </style>
        <?php
    } else if ( $dashboard_customization == '10' ) {
        ?>
        <style type="text/css">
        /*    Model-10   */

        .fs_affiliates_frontend_dashboard{
            background:#ddd;
            border:2px solid #8c0a0c;
        }
        .fs_affiliates_frontend_dashboard .fs_affiliates_menu{
            background:#8c0a0c;
        }
        .fs_affiliates_frontend_dashboard .fs_affiliates_menu ul.fs_affiliates_menu_ul li a{
            color:#fff;
        }
        .fs_affiliates_frontend_dashboard .fs_affiliates_menu ul.fs_affiliates_menu_ul li a:hover{
            color:#5b6fff;
        }
        .fs_affiliates_frontend_dashboard .fs_affiliates_menu ul.fs_affiliates_menu_ul li a.current{
            color:#5b6fff;
        }
        /* Submenu color */

        .fs_affiliates_frontend_dashboard .fs_affiliates_menu ul.fs_affiliates_menu_ul li:hover ul{
            background:#8c0a0c;
        }
        .fs_affiliates_frontend_dashboard .fs_affiliates_menu ul.fs_affiliates_menu_ul li:hover ul li a{
            background:#8c0a0c;
            color:#fff; 
        }
        .fs_affiliates_frontend_dashboard .fs_affiliates_menu ul.fs_affiliates_menu_ul li ul li a:hover{
            color:#5b6fff;
            background:#ac0d11;
        }

        /*  Menu Content Title border color */

        .fs_affiliates_frontend_dashboard .fs_affiliates_menu_content .fs_affiliates_link_generator h2,
        .fs_affiliates_frontend_dashboard .fs_affiliates_menu_content .fs_affiliates_form h2{
            color:#222;
            border-bottom:1px dashed #79829A;
        }

        /* Form Save button */

        .fs_affiliates_generate_affiliate_link, .fs_affiliates_form_save, .fs_affiliates_generate_campaign_affiliate_link{
            background:linear-gradient(to right, #8c0a0c 0%, #400404 100%);
            box-shadow: 0 5px #ac0d11 !important;
        }

        /*  frontend table design   */

        .fs_affiliates_menu_content table.fs_affiliates_referrals_frontend_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_visits_frontend_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_creatives_frontend_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliate_transaction_log_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliate_payout_request_log_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_overview_frontend_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_order_detail_frontend_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_customer_detail_frontend_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_campaigns_frontend_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_commission_frontend_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliate_campaigns_list_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_Payout_frontend_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_leaderboard_frontend_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_fileupload_frontend_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_landing_page_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_coupon_linking_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_domain_table tbody th{
            background:linear-gradient(to right, #8c0a0c 0%, #400404 100%);
            color:#fff;
        }
        @media screen and (max-width: 767px){
            .fs_affiliates_frontend_dashboard .fs_affiliates_menu ul.fs_affiliates_menu_ul li:hover ul li a{
                background:#8c0a0c;
                color:#fff;
            }
            .fs_affiliates_frontend_dashboard .fs_affiliates_menu ul.fs_affiliates_menu_ul li ul li a:hover{
                color:#5b6fff;
                background:#ac0d11;
            }
            .fs_affiliates_menu_content table.fs_affiliates_referrals_frontend_table tbody td:before,
            .fs_affiliates_menu_content table.fs_affiliates_visits_frontend_table tbody td:before,
            .fs_affiliates_menu_content table.fs_affiliates_creatives_frontend_table tbody td:before,
            .fs_affiliates_menu_content table.fs_affiliate_transaction_log_table tbody td:before,
            .fs_affiliates_menu_content table.fs_affiliate_payout_request_log_table tbody td:before,
            .fs_affiliates_menu_content table.fs_affiliates_campaigns_frontend_table tbody td:before,
            .fs_affiliates_menu_content table.fs_affiliate_campaigns_list_table tbody td:before,
            .fs_affiliates_menu_content table.fs_affiliates_Payout_frontend_table tbody td:before,
            .fs_affiliates_menu_content table.fs_affiliates_leaderboard_frontend_table tbody td:before,
            .fs_affiliates_menu_content table.fs_affiliates_fileupload_frontend_table tbody td:before,
            .fs_affiliates_menu_content table.fs_affiliates_landing_page_table tbody td:before,
            .fs_affiliates_menu_content table.fs_affiliates_coupon_linking_table tbody td:before,
            .fs_affiliates_menu_content table.fs_affiliates_domain_table tbody td:before{
                background:linear-gradient(to right, #8c0a0c 0%, #400404 100%);
                color:#fff;
            }
        }
        </style>
        <?php
    } else {
        ?>
        <style type="text/css">
        /*    Model-1   */

        .fs_affiliates_frontend_dashboard{
            background:#ddd;
            border:2px solid #52ac67;
        }
        .fs_affiliates_frontend_dashboard .fs_affiliates_menu{
            background:#52ac67;
        }
        .fs_affiliates_frontend_dashboard .fs_affiliates_menu ul.fs_affiliates_menu_ul li a{
            color:#fff;
        }
        .fs_affiliates_frontend_dashboard .fs_affiliates_menu ul.fs_affiliates_menu_ul li a:hover{
            color:#000;
        }
        .fs_affiliates_frontend_dashboard .fs_affiliates_menu ul.fs_affiliates_menu_ul li a.current{
            color:#000;
        }

        /* Submenu color */ 

        .fs_affiliates_frontend_dashboard .fs_affiliates_menu ul.fs_affiliates_menu_ul li:hover ul{
            background:#52ac67;
        }
        .fs_affiliates_frontend_dashboard .fs_affiliates_menu ul.fs_affiliates_menu_ul li:hover ul li a{
            background:#52ac67;
            color:#fff; 
        }
        .fs_affiliates_frontend_dashboard .fs_affiliates_menu ul.fs_affiliates_menu_ul li ul li a:hover{
            color:#000;
            background:#5fe0a8;
        }

        /* Menu Content Title border color */

        .fs_affiliates_frontend_dashboard .fs_affiliates_menu_content .fs_affiliates_link_generator h2,
        .fs_affiliates_frontend_dashboard .fs_affiliates_menu_content .fs_affiliates_form h2{
            color:#222;
            border-bottom:1px dashed #00339a;
        }

        /* Form Save button */ 

        .fs_affiliates_generate_affiliate_link, .fs_affiliates_form_save, .fs_affiliates_generate_campaign_affiliate_link{
            background: #00339a !important;
            box-shadow: 0 5px #042c7e !important;
        }

        /* frontend table design */   

        .fs_affiliates_menu_content table.fs_affiliates_referrals_frontend_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_visits_frontend_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_creatives_frontend_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliate_transaction_log_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliate_payout_request_log_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_overview_frontend_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_order_detail_frontend_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_customer_detail_frontend_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_campaigns_frontend_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_commission_frontend_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliate_campaigns_list_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_Payout_frontend_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_leaderboard_frontend_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_fileupload_frontend_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_landing_page_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_coupon_linking_table tbody th,
        .fs_affiliates_menu_content table.fs_affiliates_domain_table tbody th{
            background:#00339a;
            color:#fff;
        }

        @media screen and (max-width: 767px){
            .fs_affiliates_frontend_dashboard .fs_affiliates_menu ul.fs_affiliates_menu_ul li:hover ul li a{
                background:#52ac67;
                color:#fff;
            }
            .fs_affiliates_frontend_dashboard .fs_affiliates_menu ul.fs_affiliates_menu_ul li ul li a:hover{
                color:#000;
                background:#5fe0a8;
            }
            .fs_affiliates_menu_content table.fs_affiliates_referrals_frontend_table tbody td:before,
            .fs_affiliates_menu_content table.fs_affiliates_visits_frontend_table tbody td:before,
            .fs_affiliates_menu_content table.fs_affiliates_creatives_frontend_table tbody td:before,
            .fs_affiliates_menu_content table.fs_affiliate_transaction_log_table tbody td:before,
            .fs_affiliates_menu_content table.fs_affiliate_payout_request_log_table tbody td:before,
            .fs_affiliates_menu_content table.fs_affiliates_campaigns_frontend_table tbody td:before,
            .fs_affiliates_menu_content table.fs_affiliate_campaigns_list_table tbody td:before,
            .fs_affiliates_menu_content table.fs_affiliates_Payout_frontend_table tbody td:before,
            .fs_affiliates_menu_content table.fs_affiliates_leaderboard_frontend_table tbody td:before,
            .fs_affiliates_menu_content table.fs_affiliates_fileupload_frontend_table tbody td:before,
            .fs_affiliates_menu_content table.fs_affiliates_landing_page_table tbody td:before,
            .fs_affiliates_menu_content table.fs_affiliates_coupon_linking_table tbody td:before,
            .fs_affiliates_menu_content table.fs_affiliates_domain_table tbody td:before{
                display:table-cell;
                padding: 10px;
                background:#00339a;
                text-align:left;
                width:50%;
                color:#fff;
                margin-right: 10px;
                font-weight:bold;
            }
        }
    </style>
    <?php
}

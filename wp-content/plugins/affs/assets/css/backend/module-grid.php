<?php

$module_color_mode = get_option( 'fs_affiliates_module_settings_color_mode' , 1 ) ;
?>
<style type="text/css">
    /*Module grid design*/
    .fs_affiliates_modules_grid_wrap{
        width:100%;
        float:left;
    }
    .fs_affiliates_tab_inner_content h2.fs_affiliates_modules_title{
        font-size:18px !important;
        color: #f55b11 !important;
        font-family: 'Roboto', sans-serif !important;
        margin: 0;
        letter-spacing: 0.5px;
        padding: 12px 0 22px 0px;
        border:none !important;
        text-align:center;
    }

    .fs_affiliates_tab_inner_content .fs_affiliates_modules_grid{
        float: left;
        width: 31%;
        height: 220px;
        border-radius: 10px;
        position: relative;
        margin:10px 20px 10px 0px;
    }
    .fs_affiliates_tab_inner_content .fs_affiliates_modules_grid .mask{
        left:0;
        top:0;
        right:0;
        bottom:0;
        width: 100%;
        height: 223px;
        border-radius: 10px;
        position: absolute;
        background:rgba(0, 0, 0, 0.5);
        display:block;
        z-index: 999;
    }
    .fs_affiliates_tab_inner_content .fs_affiliates_modules_grid .fs_affiliates_modules_grid_inner {
        border-radius: 10px;
        box-shadow: 0 0 4px 0 #ccc;
        display: block;
        overflow: hidden;
        position: relative;
        min-width: 300px;
        max-width:465px;
        background:#ddd;
        height:220px;
    }

    .fs_affiliates_tab_inner_content .fs_affiliates_modules_grid .fs_affiliates_modules_grid_inner .fs_affiliates_modules_grid_inner_top{
        float: left;
        min-height: 75px;
        text-align: center;
        width: 100%;

    }
    .fs_affiliates_tab_inner_content .fs_affiliates_modules_grid .fs_affiliates_modules_grid_inner .fs_affiliates_modules_grid_img{
        height:80px;
        width:100%;
        text-align:center;
        float:left;
    }
    .fs_affiliates_tab_inner_content .fs_affiliates_modules_grid .fs_affiliates_modules_grid_inner .fs_affiliates_modules_grid_img img{
        width:80px;
        height:auto;
    }  
    .fs_affiliates_modules_grid_inner_top h3 {
        color: #000;
        font-size: 1.3em;
        text-align:center;
        margin-bottom:0px !important;
        padding:0px 10px;
        line-height: 24px;
    }
    .fs_affiliates_tab_inner_content .fs_affiliates_modules_grid .fs_affiliates_modules_grid_inner .fs_affiliates_modules_grid_inner_bottom{
        float: left;
        width:100%;
        padding-bottom: 10px;
        padding-top: 20px;
    }
    .fs_affiliates_signup_commission_active .fs_affiliates_modules_grid_inner_top h3,
    .fs_affiliates_wc_product_restriction_active .fs_affiliates_modules_grid_inner_top h3,
    .fs_affiliates_paypal_payouts_active .fs_affiliates_modules_grid_inner_top h3,
    .fs_affiliates_wc_referral_restriction_active .fs_affiliates_modules_grid_inner_top h3,
    .fs_affiliates_wc_account_management_active .fs_affiliates_modules_grid_inner_top h3,
    .fs_affiliates_checkout_affiliate_active .fs_affiliates_modules_grid_inner_top h3,
    .fs_affiliates_affiliate_wallet_active .fs_affiliates_modules_grid_inner_top h3,
    .fs_affiliates_referral_order_details_active .fs_affiliates_modules_grid_inner_top h3,
    .fs_affiliates_wc_coupon_linking_active .fs_affiliates_modules_grid_inner_top h3,
    .fs_affiliates_commission_links_active .fs_affiliates_modules_grid_inner_top h3,
    .fs_affiliates_credit_last_referrer_active .fs_affiliates_modules_grid_inner_top h3,
    .fs_affiliates_referral_commission_threshold_active .fs_affiliates_modules_grid_inner_top h3,
    .fs_affiliates_wc_redirect_affiliate_active .fs_affiliates_modules_grid_inner_top h3,
    .fs_affiliates_auto_approve_affiliate_active .fs_affiliates_modules_grid_inner_top h3,
    .fs_affiliates_multi_level_marketing_active .fs_affiliates_modules_grid_inner_top h3,
    .fs_affiliates_affiliate_ranking_active .fs_affiliates_modules_grid_inner_top h3,
    .fs_affiliates_affiliate_url_masking_active .fs_affiliates_modules_grid_inner_top h3,
    .fs_affiliates_lifetime_affiliate_active .fs_affiliates_modules_grid_inner_top h3,
    .fs_affiliates_referral_notification_active .fs_affiliates_modules_grid_inner_top h3,
    .fs_affiliates_email_verification_active .fs_affiliates_modules_grid_inner_top h3,
    .fs_affiliates_pretty-affiliate-links_active .fs_affiliates_modules_grid_inner_top h3,
    .fs_affiliates_admin_referral_notification_active .fs_affiliates_modules_grid_inner_top h3,
    .fs_affiliates_slug_modification_active .fs_affiliates_modules_grid_inner_top h3,
    .fs_affiliates_leaderboard_active .fs_affiliates_modules_grid_inner_top h3,
    .fs_affiliates_refer_friend_active .fs_affiliates_modules_grid_inner_top h3, 
    .fs_affiliates_sms_active .fs_affiliates_modules_grid_inner_top h3,
    .fs_affiliates_additional_dashboard_tabs_active .fs_affiliates_modules_grid_inner_top h3,
    .fs_affiliates_pushover_notifications_active .fs_affiliates_modules_grid_inner_top h3,
    .fs_affiliates_qrcode_active .fs_affiliates_modules_grid_inner_top h3,
    .fs_affiliates_landing_pages_active .fs_affiliates_modules_grid_inner_top h3,
    .fs_affiliates_affs_email_opt_in_active .fs_affiliates_modules_grid_inner_top h3,
    .fs_affiliates_referral_code_active .fs_affiliates_modules_grid_inner_top h3, 
    .fs_affiliates_affiliate_level_product_commission_active .fs_affiliates_modules_grid_inner_top h3,
    .fs_affiliates_url_masking_active .fs_affiliates_modules_grid_inner_top h3,
    .fs_affiliates_export_active .fs_affiliates_modules_grid_inner_top h3,
    .fs_affiliates_lifetime_commission_active .fs_affiliates_modules_grid_inner_top h3,
    .fs_affiliates_landing_commissions_active .fs_affiliates_modules_grid_inner_top h3,
    .fs_affiliates_product_based_affiliate_link_active .fs_affiliates_modules_grid_inner_top h3,
    .fs_affiliates_periodic_reports_active .fs_affiliates_modules_grid_inner_top h3,
    .fs_affiliates_payout_statements_active .fs_affiliates_modules_grid_inner_top h3,
    .fs_affiliates_signup_bonus_active .fs_affiliates_modules_grid_inner_top h3,
    .fs_affiliates_affiliate_fee_active .fs_affiliates_modules_grid_inner_top h3,
    .fs_affiliates_socialshare_active .fs_affiliates_modules_grid_inner_top h3,
    .fs_affiliates_affiliate_signup_restriction_active .fs_affiliates_modules_grid_inner_top h3,
    .fs_affiliates_fraud_protection_active .fs_affiliates_modules_grid_inner_top h3,
    .fs_affiliates_payout_request_active .fs_affiliates_modules_grid_inner_top h3{
        color:#fff !important;
    }

    /*   Native color Multicolor code  */

    .fs_affiliates_signup_commission_active, .fs_affiliates_wc_product_restriction_active, .fs_affiliates_paypal_payouts_active,
    .fs_affiliates_wc_referral_restriction_active, .fs_affiliates_wc_account_management_active, .fs_affiliates_checkout_affiliate_active,
    .fs_affiliates_affiliate_wallet_active, .fs_affiliates_referral_order_details_active, .fs_affiliates_wc_coupon_linking_active,
    .fs_affiliates_commission_links_active, .fs_affiliates_credit_last_referrer_active, .fs_affiliates_referral_commission_threshold_active, 
    .fs_affiliates_wc_redirect_affiliate_active, .fs_affiliates_wc_redirect_affiliate_active, .fs_affiliates_auto_approve_affiliate_active, 
    .fs_affiliates_multi_level_marketing_active, .fs_affiliates_affiliate_ranking_active, .fs_affiliates_lifetime_affiliate_active,
    .fs_affiliates_lifetime_affiliate_active, .fs_affiliates_referral_notification_active, .fs_affiliates_email_verification_active,
    .fs_affiliates_pretty-affiliate-links_active, .fs_affiliates_slug_modification_active, .fs_affiliates_admin_referral_notification_active,
    .fs_affiliates_affiliate_url_masking_active, .fs_affiliates_leaderboard_active, .fs_affiliates_refer_friend_active,
    .fs_affiliates_sms_active, .fs_affiliates_additional_dashboard_tabs_active, .fs_affiliates_pushover_notifications_active,
    .fs_affiliates_qrcode_active, .fs_affiliates_landing_pages_active, .fs_affiliates_affs_email_opt_in_active,
    .fs_affiliates_referral_code_activ, .fs_affiliates_affiliate_level_product_commission_active, .fs_affiliates_url_masking_active,
    .fs_affiliates_url_masking_active, .fs_affiliates_export_active, .fs_affiliates_lifetime_commission_active,
    .fs_affiliates_landing_commissions_active, .fs_affiliates_product_based_affiliate_link_active, .fs_affiliates_periodic_reports_active,
    .fs_affiliates_payout_statements_active, .fs_affiliates_referral_code_active, .fs_affiliates_socialshare_active, .fs_affiliates_signup_bonus_active,
    .fs_affiliates_affiliate_fee_active, .fs_affiliates_affiliate_signup_restriction_active, .fs_affiliates_fraud_protection_active,
    .fs_affiliates_payout_request_active{
        background:#f55b11 !important; 
    }
    .fs_affiliates_switch_round {
        width: 60px !important;
        height: 25px !important;
        position: relative !important;
        display: inline-block !important;
        -moz-box-shadow: inset 0 0 6px #eee !important;
        -webkit-box-shadow: inset 0 0 6px #eee !important;
        box-shadow: 0 0 2px #777 inset !important;
        background: #fff !important;
        -webkit-border-radius: 50px !important;
        -moz-border-radius: 50px !important;
        border-radius: 50px !important;
        margin-left: 25px;
    }
    .fs_affiliates_switch_round::after {
        content: "OFF" !important;
        font-size: 10px !important;
        top: 1px !important;
        line-height: 1.8 !important;
        right: 4px !important;
        position: absolute !important;
        font-weight: bold !important;
        color: #000 !important;
        padding: 3px !important;
        z-index: 1 !important;
        font-family: 'Roboto', sans-serif !important;
    }
    .fs_affiliates_switch_round::before {
        content: "ON" !important;
        font-size: 10px !important;
        font-weight: bold !important;
        line-height: 1.8 !important;
        top: 1px !important;
        left: 4px !important;
        position: absolute !important;
        color: #000 !important;
        padding: 3px !important;
        font-family: 'Roboto', sans-serif !important;
    }
    .fs_affiliates_switch_round input {
        display: none !important;
    }
    .fs_affiliates_slider_round {
        position: absolute !important;
        z-index: 111 !important;
        height: 16px !important;
        width: 26px !important;
        left: 5px !important;
        top: 4px !important;
        background: #999 !important;
        -webkit-box-shadow: 0px 2px 5px 0px rgba(0,0,0,0.3) !important;
        -moz-box-shadow: 0px 2px 5px 0px rgba(0,0,0,0.3) !important;
        box-shadow: 0px 2px 5px 0px rgba(0,0,0,0.3) !important;
        -webkit-transition: all .4s ease !important;
        -moz-transition: all .4s ease !important;
        -o-transition: all .4s ease !important;
        -ms-transition: all .4s ease !important;
        transition: all .4s ease !important;
        -webkit-border-radius: 50px !important;
        -moz-border-radius: 50px !important;
        border-radius: 50px !important;
    }
    .fs_affiliates_switch_round input:checked + .fs_affiliates_slider_round {
        -webkit-transform: translateX(24px) !important;
        -ms-transform: translateX(24px) !important;
        transform: translateX(24px) !important;
    }

    .fs_affiliates_signup_commission_active .fs_affiliates_switch_round input:checked + .fs_affiliates_slider_round,
    .fs_affiliates_wc_product_restriction_active .fs_affiliates_switch_round input:checked + .fs_affiliates_slider_round,
    .fs_affiliates_paypal_payouts_active .fs_affiliates_switch_round input:checked + .fs_affiliates_slider_round,
    .fs_affiliates_wc_referral_restriction_active .fs_affiliates_switch_round input:checked + .fs_affiliates_slider_round,
    .fs_affiliates_wc_account_management_active .fs_affiliates_switch_round input:checked + .fs_affiliates_slider_round,
    .fs_affiliates_checkout_affiliate_active .fs_affiliates_switch_round input:checked + .fs_affiliates_slider_round,
    .fs_affiliates_affiliate_wallet_active .fs_affiliates_switch_round input:checked + .fs_affiliates_slider_round,
    .fs_affiliates_referral_order_details_active .fs_affiliates_switch_round input:checked + .fs_affiliates_slider_round,
    .fs_affiliates_wc_coupon_linking_active .fs_affiliates_switch_round input:checked + .fs_affiliates_slider_round,
    .fs_affiliates_commission_links_active .fs_affiliates_switch_round input:checked + .fs_affiliates_slider_round,
    .fs_affiliates_referral_commission_threshold_active .fs_affiliates_switch_round input:checked + .fs_affiliates_slider_round,
    .fs_affiliates_credit_last_referrer_active .fs_affiliates_switch_round input:checked + .fs_affiliates_slider_round,
    .fs_affiliates_wc_redirect_affiliate_active .fs_affiliates_switch_round input:checked + .fs_affiliates_slider_round,
    .fs_affiliates_auto_approve_affiliate_active .fs_affiliates_switch_round input:checked + .fs_affiliates_slider_round,
    .fs_affiliates_multi_level_marketing_active .fs_affiliates_switch_round input:checked + .fs_affiliates_slider_round,
    .fs_affiliates_affiliate_ranking_active .fs_affiliates_switch_round input:checked + .fs_affiliates_slider_round,
    .fs_affiliates_lifetime_affiliate_active .fs_affiliates_switch_round input:checked + .fs_affiliates_slider_round,
    .fs_affiliates_referral_notification_active .fs_affiliates_switch_round input:checked + .fs_affiliates_slider_round,
    .fs_affiliates_email_verification_active .fs_affiliates_switch_round input:checked + .fs_affiliates_slider_round,
    .fs_affiliates_pretty-affiliate-links_active .fs_affiliates_switch_round input:checked + .fs_affiliates_slider_round,
    .fs_affiliates_slug_modification_active .fs_affiliates_switch_round input:checked + .fs_affiliates_slider_round,
    .fs_affiliates_admin_referral_notification_active .fs_affiliates_switch_round input:checked + .fs_affiliates_slider_round,
    .fs_affiliates_affiliate_url_masking_active .fs_affiliates_switch_round input:checked + .fs_affiliates_slider_round,
    .fs_affiliates_leaderboard_active .fs_affiliates_switch_round input:checked + .fs_affiliates_slider_round,
    .fs_affiliates_refer_friend_active .fs_affiliates_switch_round input:checked + .fs_affiliates_slider_round,
    .fs_affiliates_sms_active .fs_affiliates_switch_round input:checked + .fs_affiliates_slider_round,
    .fs_affiliates_additional_dashboard_tabs_active .fs_affiliates_switch_round input:checked + .fs_affiliates_slider_round,
    .fs_affiliates_pushover_notifications_active .fs_affiliates_switch_round input:checked + .fs_affiliates_slider_round,
    .fs_affiliates_qrcode_active .fs_affiliates_switch_round input:checked + .fs_affiliates_slider_round,
    .fs_affiliates_landing_pages_active .fs_affiliates_switch_round input:checked + .fs_affiliates_slider_round,
    .fs_affiliates_affs_email_opt_in_active .fs_affiliates_switch_round input:checked + .fs_affiliates_slider_round,
    .fs_affiliates_referral_code_active .fs_affiliates_switch_round input:checked + .fs_affiliates_slider_round,
    .fs_affiliates_affiliate_level_product_commission_active .fs_affiliates_switch_round input:checked + .fs_affiliates_slider_round,
    .fs_affiliates_url_masking_active .fs_affiliates_switch_round input:checked + .fs_affiliates_slider_round,
    .fs_affiliates_export_active .fs_affiliates_switch_round input:checked + .fs_affiliates_slider_round,
    .fs_affiliates_lifetime_commission_active .fs_affiliates_switch_round input:checked + .fs_affiliates_slider_round,
    .fs_affiliates_landing_commissions_active .fs_affiliates_switch_round input:checked + .fs_affiliates_slider_round,
    .fs_affiliates_product_based_affiliate_link_active .fs_affiliates_switch_round input:checked + .fs_affiliates_slider_round,
    .fs_affiliates_periodic_reports_active .fs_affiliates_switch_round input:checked + .fs_affiliates_slider_round,
    .fs_affiliates_payout_statements_active .fs_affiliates_switch_round input:checked + .fs_affiliates_slider_round,
    .fs_affiliates_socialshare_active .fs_affiliates_switch_round input:checked + .fs_affiliates_slider_round,
    .fs_affiliates_signup_bonus_active .fs_affiliates_switch_round input:checked + .fs_affiliates_slider_round,
    .fs_affiliates_affiliate_fee_active .fs_affiliates_switch_round input:checked + .fs_affiliates_slider_round,
    .fs_affiliates_affiliate_signup_restriction_active .fs_affiliates_switch_round input:checked + .fs_affiliates_slider_round,
    .fs_affiliates_fraud_protection_active .fs_affiliates_switch_round input:checked + .fs_affiliates_slider_round,
    .fs_affiliates_payout_request_active .fs_affiliates_switch_round input:checked + .fs_affiliates_slider_round{
        background:#f55b11 !important; 
    }

    .fs_affiliates_modules_grid_inner_bottom a {
        background: #000;
        border: none !important;
        border-radius: 10px;
        color: #fff;
        float: right;
        font-family: "Roboto",sans-serif;
        font-size: 12px;
        font-weight: 600;
        margin-right: 25px;
        padding: 2px 6px;
        text-decoration: none;
    }    

    .fs_affiliates_test_pushover_notification{
        text-transform: uppercase !important;
        margin-left:0px !important;
        background: #00339a !important;
        padding: 10px 20px !important;
        color: #fefefe !important;
        width: auto !important;
        height: auto !important;
        border: none !important;
        box-shadow: 0 5px #042c7e !important;
        text-shadow: none !important;
        font-weight: 500;
        margin-bottom: 10px;
        font-size:14px !important;
        letter-spacing: 1px;
    }
    .fs_affiliates_test_pushover_notification:active{
        transform: translateY(5px);
        box-shadow: none !important;
    }
    .fs_affiliates_module_notice{
        width:100%;
        display:block;
    }
    .fs_affiliates_module_notice p{
        margin:0px;
        padding:10px;
        background:#f00;
        color:#fff;
    }
    .fs_affiliates_module_notice p i{
        margin-right:10px;
    }
    /*  Module Native/Multi button design   */

    .fs_affiliates_modules_btn{
        float:right;
        display:block;
    }
    .fs_affiliates_modules_btn .fs_affiliates_modules_btn_content{
        width:210px;
        height:35px;
        background:#fff;
        box-shadow:inset 0 0 3px #000;
        border-radius:5px;
    }
    .fs_affiliates_modules_btn .fs_affiliates_modules_btn_content input[type='radio']{
        position: absolute;
        height:1px;
        width:1px;
        border:0;
        overflow: hidden;
        clip: rect(0, 0, 0, 0);
        /*    display:none;*/
    }
    .fs_affiliates_modules_btn .fs_affiliates_modules_btn_content .fs_affiliates_modules_btn_switch_one,
    .fs_affiliates_modules_btn .fs_affiliates_modules_btn_content .fs_affiliates_modules_btn_switch_two{
        width:105px;
        height:35px;
        background: none;
        float:left;
        text-align:center;
        line-height: 2.2em;
    }
    .fs_affiliates_modules_btn .fs_affiliates_modules_btn_content .fs_affiliates_modules_btn_switch_one label,
    .fs_affiliates_modules_btn .fs_affiliates_modules_btn_content .fs_affiliates_modules_btn_switch_two label{
        width:105px;
        height:35px;
        color:#000;
        float:left;
        cursor: pointer;
        font-weight:550;
        font-size:12px !important;
    }
    .fs_affiliates_modules_btn .fs_affiliates_modules_btn_content .fs_affiliates_modules_btn_switch_one input:checked + label{
        background:#f55b11;
        color:#fff;
        border-top-left-radius: 5px;
        border-bottom-left-radius: 5px;
        box-shadow:inset 0 0 3px #000;
    }
    .fs_affiliates_modules_btn .fs_affiliates_modules_btn_content .fs_affiliates_modules_btn_switch_two input:checked + label{
        background:#cc6699;
        color:#000;
        border-top-right-radius: 5px;
        border-bottom-right-radius: 5px;
        box-shadow:inset 0 0 3px #000;

    }
</style>

<?php if ( $module_color_mode == '2' ) { ?>
    <style type="text/css">
        .fs_affiliates_signup_commission_active{
            background:#046426 !important;
        }
        .fs_affiliates_wc_product_restriction_active{
            background:#7b1fa2 !important;
        }
        .fs_affiliates_paypal_payouts_active{
            background:#00288f !important;
        }
        .fs_affiliates_wc_referral_restriction_active{
            background:#0097a7 !important;
        }
        .fs_affiliates_wc_account_management_active{
            background:#ff9800 !important;
        }
        .fs_affiliates_checkout_affiliate_active{
            background:#5d4037 !important;
        }
        .fs_affiliates_affiliate_wallet_active{
            background:#00796b !important;
        }
        .fs_affiliates_referral_order_details_active{
            background:#81b54d !important;
        }
        .fs_affiliates_wc_coupon_linking_active{
            background:#999966 !important;
        }
        .fs_affiliates_commission_links_active{
            background:#772207 !important;
        }
        .fs_affiliates_credit_last_referrer_active{
            background:#0288d1 !important;
        }
        .fs_affiliates_referral_commission_threshold_active{
            background:#ffc107 !important;
        }
        .fs_affiliates_wc_redirect_affiliate_active{
            background:#ff4081 !important; 
        }
        .fs_affiliates_auto_approve_affiliate_active{
            background:#cddc39 !important; 
        }
        .fs_affiliates_multi_level_marketing_active{
            background:#5acbff !important; 
        }
        .fs_affiliates_affiliate_ranking_active{
            background:#777 !important;
        }
        .fs_affiliates_lifetime_affiliate_active{
            background:#ff8383 !important;
        }
        .fs_affiliates_referral_notification_active{
            background:#990099 !important;
        }
        .fs_affiliates_email_verification_active{
            background:#333300 !important;
        }
        .fs_affiliates_pretty-affiliate-links_active{
            background:#cc0099 !important; 
        }
        .fs_affiliates_slug_modification_active{
            background:#ff3300 !important; 
        }
        .fs_affiliates_admin_referral_notification_active{
            background:#9933ff !important;
        }
        .fs_affiliates_affiliate_url_masking_active{
            background:#ffeb3b !important; 
        }
        .fs_affiliates_leaderboard_active{
            background:#455a64 !important; 
        }
        .fs_affiliates_refer_friend_active{
            background:#996600 !important; 
        }
        .fs_affiliates_sms_active{
            background:#1cc47b !important; 
        }
        .fs_affiliates_additional_dashboard_tabs_active{
            background:#9d33ff !important; 
        }
        .fs_affiliates_pushover_notifications_active{
            background:#cc9900 !important; 
        }
        .fs_affiliates_qrcode_active{
            background:#ff5050 !important; 
        }
        .fs_affiliates_landing_pages_active{
            background:#cc6699 !important; 
        }
        .fs_affiliates_affs_email_opt_in_active{
            background:#990033 !important; 
        }
        .fs_affiliates_referral_code_active{
            background:#666633 !important; 
        }
        .fs_affiliates_affiliate_level_product_commission_active{
            background:#634580 !important; 
        }
        .fs_affiliates_url_masking_active{
            background:#cc3300 !important; 
        }
        .fs_affiliates_export_active{
            background:#ccff33 !important; 
        }
        .fs_affiliates_lifetime_commission_active{
            background:#9999ff !important; 
        }
        .fs_affiliates_landing_commissions_active{
            background:#339966 !important; 
        }
        .fs_affiliates_product_based_affiliate_link_active{
            background:#ffcc00 !important; 
        }
        .fs_affiliates_periodic_reports_active{
            background:#ff0066 !important; 
        }
        .fs_affiliates_payout_statements_active{
            background:#99cc00 !important; 
        }
        .fs_affiliates_socialshare_active{
            background:#00ff99 !important; 
        }
        .fs_affiliates_signup_bonus_active{
            background:#21ae8b !important;  
        }
        .fs_affiliates_affiliate_fee_active{
            background:#cc33ff !important;  
        }
        .fs_affiliates_split_commission_active{
            background:#33cccc !important; 
        }
        .fs_affiliates_affiliate_signup_restriction_active{
            background:#66ccff !important; 
        }
        .fs_affiliates_fraud_protection_active{
            background:#cccc00 !important; 
        }
        .fs_affiliates_payout_request_active{
            background:#669999 !important; 
        }
        .fs_affiliates_signup_commission_active .fs_affiliates_switch_round input:checked + .fs_affiliates_slider_round {
            background-color:#046426 !important;
        }
        .fs_affiliates_wc_product_restriction_active .fs_affiliates_switch_round input:checked + .fs_affiliates_slider_round {
            background-color:#7b1fa2 !important;
        }
        .fs_affiliates_paypal_payouts_active .fs_affiliates_switch_round input:checked + .fs_affiliates_slider_round {
            background-color:#00288f !important;
        }
        .fs_affiliates_wc_referral_restriction_active .fs_affiliates_switch_round input:checked + .fs_affiliates_slider_round {
            background-color:#0097a7 !important;
        }
        .fs_affiliates_wc_account_management_active .fs_affiliates_switch_round input:checked + .fs_affiliates_slider_round {
            background-color:#ff9800 !important;
        }
        .fs_affiliates_checkout_affiliate_active .fs_affiliates_switch_round input:checked + .fs_affiliates_slider_round {
            background-color:#5d4037 !important;
        }
        .fs_affiliates_affiliate_wallet_active .fs_affiliates_switch_round input:checked + .fs_affiliates_slider_round {
            background-color:#00796b !important;
        }
        .fs_affiliates_referral_order_details_active .fs_affiliates_switch_round input:checked + .fs_affiliates_slider_round {
            background-color:#81b54d !important;
        }
        .fs_affiliates_wc_coupon_linking_active .fs_affiliates_switch_round input:checked + .fs_affiliates_slider_round {
            background-color:#999966 !important;
        }
        .fs_affiliates_commission_links_active .fs_affiliates_switch_round input:checked + .fs_affiliates_slider_round {
            background-color:#772207 !important;
        }
        .fs_affiliates_referral_commission_threshold_active .fs_affiliates_switch_round input:checked + .fs_affiliates_slider_round{
            background:#ffc107 !important;
        }
        .fs_affiliates_credit_last_referrer_active .fs_affiliates_switch_round input:checked + .fs_affiliates_slider_round{
            background:#0288d1 !important;
        }
        .fs_affiliates_wc_redirect_affiliate_active .fs_affiliates_switch_round input:checked + .fs_affiliates_slider_round{
            background:#ff4081 !important;
        }
        .fs_affiliates_auto_approve_affiliate_active .fs_affiliates_switch_round input:checked + .fs_affiliates_slider_round{
            background:#cddc39 !important;
        }
        .fs_affiliates_multi_level_marketing_active .fs_affiliates_switch_round input:checked + .fs_affiliates_slider_round{
            background:#5acbff !important;
        }
        .fs_affiliates_affiliate_ranking_active .fs_affiliates_switch_round input:checked + .fs_affiliates_slider_round{
            background:#777 !important;
        }
        .fs_affiliates_lifetime_affiliate_active .fs_affiliates_switch_round input:checked + .fs_affiliates_slider_round{
            background:#ff8383 !important;
        }
        .fs_affiliates_referral_notification_active .fs_affiliates_switch_round input:checked + .fs_affiliates_slider_round{
            background:#990099 !important;
        }
        .fs_affiliates_email_verification_active .fs_affiliates_switch_round input:checked + .fs_affiliates_slider_round{
            background:#333300 !important;
        }
        .fs_affiliates_pretty-affiliate-links_active .fs_affiliates_switch_round input:checked + .fs_affiliates_slider_round{
            background:#cc0099 !important;
        }
        .fs_affiliates_slug_modification_active .fs_affiliates_switch_round input:checked + .fs_affiliates_slider_round{
            background:#ff3300 !important; 
        }
        .fs_affiliates_admin_referral_notification_active .fs_affiliates_switch_round input:checked + .fs_affiliates_slider_round{
            background:#9933ff !important;
        }
        .fs_affiliates_affiliate_url_masking_active .fs_affiliates_switch_round input:checked + .fs_affiliates_slider_round{
            background:#ffeb3b !important; 
        }
        .fs_affiliates_leaderboard_active .fs_affiliates_switch_round input:checked + .fs_affiliates_slider_round{
            background:#455a64 !important; 
        }
        .fs_affiliates_refer_friend_active .fs_affiliates_switch_round input:checked + .fs_affiliates_slider_round{
            background:#996600 !important; 
        }
        .fs_affiliates_sms_active .fs_affiliates_switch_round input:checked + .fs_affiliates_slider_round{
            background:#1cc47b !important; 
        }
        .fs_affiliates_additional_dashboard_tabs_active .fs_affiliates_switch_round input:checked + .fs_affiliates_slider_round{
            background:#9d33ff !important; 
        }
        .fs_affiliates_pushover_notifications_active .fs_affiliates_switch_round input:checked + .fs_affiliates_slider_round{
            background:#cc9900 !important; 
        }
        .fs_affiliates_qrcode_active .fs_affiliates_switch_round input:checked + .fs_affiliates_slider_round{
            background:#ff5050 !important; 
        }
        .fs_affiliates_landing_pages_active .fs_affiliates_switch_round input:checked + .fs_affiliates_slider_round{
            background:#cc6699 !important; 
        }
        .fs_affiliates_affs_email_opt_in_active .fs_affiliates_switch_round input:checked + .fs_affiliates_slider_round{
            background:#990033 !important; 
        }
        .fs_affiliates_referral_code_active .fs_affiliates_switch_round input:checked + .fs_affiliates_slider_round{
            background:#666633 !important; 
        }
        .fs_affiliates_affiliate_level_product_commission_active .fs_affiliates_switch_round input:checked + .fs_affiliates_slider_round{
            background:#634580 !important; 
        }
        .fs_affiliates_url_masking_active .fs_affiliates_switch_round input:checked + .fs_affiliates_slider_round{
            background:#cc3300 !important; 
        }
        .fs_affiliates_export_active .fs_affiliates_switch_round input:checked + .fs_affiliates_slider_round{
            background:#ccff33 !important; 
        }
        .fs_affiliates_lifetime_commission_active .fs_affiliates_switch_round input:checked + .fs_affiliates_slider_round{
            background:#9999ff !important; 
        }
        .fs_affiliates_landing_commissions_active .fs_affiliates_switch_round input:checked + .fs_affiliates_slider_round{
            background:#339966 !important; 
        }
        .fs_affiliates_product_based_affiliate_link_active .fs_affiliates_switch_round input:checked + .fs_affiliates_slider_round{
            background:#ffcc00 !important; 
        }
        .fs_affiliates_socialshare_active .fs_affiliates_switch_round input:checked + .fs_affiliates_slider_round{
            background:#00ff99 !important; 
        }
        .fs_affiliates_periodic_reports_active .fs_affiliates_switch_round input:checked + .fs_affiliates_slider_round{
            background:#ff0066 !important; 
        }
        .fs_affiliates_payout_statements_active .fs_affiliates_switch_round input:checked + .fs_affiliates_slider_round{
            background:#99cc00 !important; 
        }
        .fs_affiliates_signup_bonus_active .fs_affiliates_switch_round input:checked + .fs_affiliates_slider_round{
            background:#21ae8b  !important; 
        }
        .fs_affiliates_affiliate_fee_active .fs_affiliates_switch_round input:checked + .fs_affiliates_slider_round{
            background:#cc33ff  !important; 
        }
        .fs_affiliates_split_commission_active .fs_affiliates_switch_round input:checked + .fs_affiliates_slider_round{
            background:#33cccc !important; 
        }
        .fs_affiliates_affiliate_signup_restriction_active .fs_affiliates_switch_round input:checked + .fs_affiliates_slider_round{
            background:#66ccff !important;
        }
        .fs_affiliates_fraud_protection_active .fs_affiliates_switch_round input:checked + .fs_affiliates_slider_round{
            background:#cccc00 !important;
        }
        .fs_affiliates_payout_request_active .fs_affiliates_switch_round input:checked + .fs_affiliates_slider_round{
            background:#669999 !important;
        }
        .fs_affiliates_modules_grid_inner_bottom a {
            background: #fff;
            border: none !important;
            border-radius: 10px;
            color: #000;
            float: right;
            font-family: "Roboto",sans-serif;
            font-size: 12px;
            font-weight: 600;
            margin-right: 25px;
            padding: 2px 6px;
            text-decoration: none;
        }
        /*checkbox design*/
    </style>

    <?php

} 

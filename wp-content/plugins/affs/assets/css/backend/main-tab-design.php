<?php
$color_mode = get_option( 'fs_affiliates_settings_color_mode' , 1 ) ;
?>
<style>
    .fs_affiliates_wrapper_cover{
        margin:30px 0px 0px -20px !important;
    }
    .fs_affiliates_header{
        height: 120px;
        background: #fff;
        width: 100%;
        float: left;
        border-top:3px solid #f55b11;
    }
    .fs_affiliates_header .fs_affiliates_title, .fs_affiliates_header .fs_affiliates_logo {
        float:left;
        width:40%;
    }
    .fs_affiliates_header .fs_affiliates_logo{
        text-align: right;
    }
    .fs_affiliates_header .fs_affiliates_title h2{
        margin-left:30px;
        margin-top:50px;
        font-size:22px;
    }
    .fs_affiliates_header .fs_affiliates_logo img{
        margin-right:30px;
        margin-top:15px;
    }
    .fs_affiliates_wrapper_cover .wrap{
        margin:0px !important;
    }
    .fs_affiliates_wrapper{
        width:100%;
        float:left;
        background:#fff;
    }
    .fs_affiliates_wrapper ul.fs_affiliates_tab_ul{
        width:100%;
        margin:0;
        padding: 0;
        float:left;
        height:80px;
        border:none !important;
        box-shadow: 0 2px 2px rgba(0, 0, 0, 0.5) !important;
    }
    .fs_affiliates_wrapper ul.fs_affiliates_tab_ul li.fs_affiliates_tab_li {
        margin-bottom:0px;
        border:none !important;
        list-style: none;
        display:inline-block;
        height: 80px;
        text-align: center;
        position: relative;
    }
    .fs_affiliates_wrapper ul.fs_affiliates_tab_ul li.fs_affiliates_tab_li a.fs_affiliates_tab_a{
        text-decoration: none;
        display: block;
        height: 80px;
        font-weight: bold;
        font-size: 13px;
        text-align:center;
        border:none !important;
        padding:0px;
        margin:0px;
        width:80px;
        white-space:normal;
        box-shadow: none !important;
        position: relative;
    }

    .fs_affiliates_wrapper ul.fs_affiliates_tab_ul li.fs_affiliates_tab_li a.fs_affiliates_tab_a:hover,
    .fs_affiliates_wrapper ul.fs_affiliates_tab_ul li.fs_affiliates_tab_li a.fs_affiliates_tab_a:focus{
        border-top:3px solid #f55b11 !important;
        height:77px !important;
        color: #f55b11;
    }
    .fs_affiliates_wrapper ul.fs_affiliates_tab_ul li.fs_affiliates_tab_li a.fs_affiliates_tab_a img{
        padding-top:15px;
        width:20px;
        height:20px;
    }
    .fs_affiliates_wrapper ul.fs_affiliates_tab_ul li.fs_affiliates_tab_li a.fs_affiliates_tab_a span{
        padding-top:0px;
        display: block;
        margin-top: -2px;
    }
    .fs_affiliates_wrapper ul.fs_affiliates_tab_ul li.fs_affiliates_tab_li a.nav-tab-active{
        border-top:3px solid #f55b11 !important;
        height:77px !important;
        color:#f55b11 !important;
    }
    .fs_affiliates_wrapper ul.fs_affiliates_tab_ul li.fs_affiliates_tab_li a.fs_affiliates_tab_a img{
        padding-top:17px;
        width:20px;
        height:20px;
    }
    .fs_affiliates_wrapper ul.fs_affiliates_tab_ul li.fs_affiliates_tab_li a.nav-tab-active span{
        margin-top: -4px !important;
        display: block;
    }
    .fs_affiliates_wrapper ul.fs_affiliates_tab_ul li.fs_affiliates_tab_li a.nav-tab-active:after{
        left: 50%;
        top: 100%;
        content: " ";
        height: 0;
        width: 0;
        position: absolute;
    }
    .fs_affiliates_wrapper ul.fs_affiliates_tab_ul li.fs_affiliates_tab_li a.nav-tab-active:after {
        border-style:solid;
        border-width: 10px;
        margin-left: -10px;
        z-index:9;
    }
    /*  Dark and Light button desigh    */

    .fs_affiliates_darklight_btn{
        float: left;
        margin-top: 50px;
        display: block;
    }
    .fs_affiliates_darklight_btn .fs_affiliates_darklight_content{
        width:180px;
        height:35px;
        background:#fff;
        box-shadow:inset 0 0 3px #000;
        border-radius:5px;
    }
    .fs_affiliates_darklight_btn .fs_affiliates_darklight_content input[type='radio']{
        position: absolute;
        height:1px;
        width:1px;
        border:0;
        overflow: hidden;
        clip: rect(0, 0, 0, 0);
        /*    display:none;*/
    }
    .fs_affiliates_darklight_btn .fs_affiliates_darklight_content .fs_affiliates_switch_one,
    .fs_affiliates_darklight_btn .fs_affiliates_darklight_content .fs_affiliates_switch_two{
        width:90px;
        height:35px;
        background: none;
        float:left;
        text-align:center;
        line-height: 2.7em;
    }
    .fs_affiliates_darklight_btn .fs_affiliates_darklight_content .fs_affiliates_switch_one label,
    .fs_affiliates_darklight_btn .fs_affiliates_darklight_content .fs_affiliates_switch_two label{
        width:90px;
        height:35px;
        float:left;
        cursor: pointer;
        font-weight:bold;
    }
    .fs_affiliates_darklight_btn .fs_affiliates_darklight_content .fs_affiliates_switch_one input:checked + label{
        background:#333;
        color:#fff;
        border-top-left-radius: 5px;
        border-bottom-left-radius: 5px;
        box-shadow:inset 0 0 3px #000;
    }
    .fs_affiliates_darklight_btn .fs_affiliates_darklight_content .fs_affiliates_switch_two input:checked + label{
        background:#ccc;
        color:#000;
        border-top-right-radius: 5px;
        border-bottom-right-radius: 5px;
        box-shadow:inset 0 0 3px #000;

    }
    /*global_tab_content-design*/

    .fs_affiliates_wrapper .fs_affiliates_tab_content{
        width:100%;
        float:left;
    }
    .fs_affiliates_tab_inner_content{
        width:95%;
        margin:30px auto;
    }
    .fs_affiliates_section_wrap{
        width: 95%;
        margin: 10px auto;
        border: 1px solid #dfdfdf;
        border-radius: 5px !important;
        background: #fcfcfc !important;
    }
    .fs_affiliates_tab_inner_content h2{
        /*    background: #3a434b url('../../images/tab/arrow-216-14.png') right 15px center no-repeat;*/
        border-bottom:1px dashed #000 !important;
        font-size: 18px !important;
        color: #f55b11 !important;
        font-family: 'Roboto', sans-serif !important;
        margin: 0;
        letter-spacing: 0.5px;
        padding: 12px 0 12px 0px;
        /*    cursor: pointer; */
    }
    .fs_affiliates_tab_inner_content table td p{
        color:#666 !important;
        padding-top:5px;
    }
    .fs_affiliates_tab_inner_content table td input[type='text'],
    .fs_affiliates_tab_inner_content table td input[type='number'],
    .fs_affiliates_tab_inner_content table td input[type='email'],
    .fs_affiliates_tab_inner_content table td input[type='url'],
    .fs_affiliates_tab_inner_content table td select{
        box-shadow: 0 -5px 10px 2px rgba(0, 0, 0, 0.03) inset;
        border: none;
        width: 350px !important;
        border: 1px solid #ccc;
        border-radius: 5px;
        height: 30px !important;
    }
    .fs_affiliates_tab_inner_content .fs_affiliates_affiliates_edit td input[type='text'],
    .fs_affiliates_tab_inner_content .fs_affiliates_affiliates_new td input[type='text'],
    .fs_affiliates_tab_inner_content .fs_affiliates_affiliates_edit td input[type='number'],
    .fs_affiliates_tab_inner_content .fs_affiliates_affiliates_new td input[type='number'],
    .fs_affiliates_tab_inner_content .fs_affiliates_affiliates_edit td input[type='email'],
    .fs_affiliates_tab_inner_content .fs_affiliates_affiliates_new td input[type='email'],
    .fs_affiliates_tab_inner_content .fs_affiliates_affiliates_edit td input[type='url'],
    .fs_affiliates_tab_inner_content .fs_affiliates_affiliates_new td input[type='url'],
    .fs_affiliates_tab_inner_content .fs_affiliates_affiliates_edit td select,
    .fs_affiliates_tab_inner_content .fs_affiliates_affiliates_new td select{
        box-shadow: 0 -5px 10px 2px rgba(0, 0, 0, 0.03) inset;
        border: none;
        width: 270px !important;
        border: 1px solid #ccc;
        border-radius: 5px;
        height: 30px !important;
    }
    .fs_affiliates_tab_inner_content .fs_affiliates_affiliates_edit td textarea,
    .fs_affiliates_tab_inner_content .fs_affiliates_affiliates_new td textarea{
        box-shadow: 0 -5px 10px 2px rgba(0, 0, 0, 0.03) inset;
        border: none;
        width: 270px !important;
        border: 1px solid #ccc;
        border-radius: 5px;
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
    .fs_affiliates_tab_inner_content table td input[type='text']:focus{
        border-bottom:2px solid #f55b11;
    }
    .fs_affiliates_tab_inner_content table td textarea{
        box-shadow: 0 -5px 10px 2px rgba(0, 0, 0, 0.03) inset;
        border: none;
        width: 350px !important;
        border: 1px solid #ccc;
        border-radius: 5px;

    }
    .fs_affiliates_save_btn{
        text-transform: uppercase !important;
        margin-left:0px !important;
        background: #138f30 !important;
        padding: 5px 20px !important;
        color: #fefefe !important;
        width: auto !important;
        height: auto !important;
        border: none !important;
        box-shadow: 0 5px #146327 !important;
        text-shadow: none !important;
        font-weight: 500;
        margin-bottom: 10px;
        font-size:14px !important;
        letter-spacing: 1px;
    }
    .fs_affiliates_save_btn:active, .fs_affiliates_reset_btn:active{
        transform: translateY(5px);
        box-shadow: none !important;
    }
    .fs_affiliates_reset_btn{
        text-transform: uppercase !important;
        background: #f05050 !important;
        padding: 5px 20px !important;
        color: #fefefe !important;
        width: auto !important;
        height: auto !important;
        border: none !important;
        box-shadow: 0 5px #bf3a3a !important;
        text-shadow: none !important;
        font-weight: 500;
        margin-bottom: 10px;
        font-size:14px !important;
        letter-spacing: 1px;
        margin-top: -60px !important;
        margin-left: 175px !important;
    }
    .fs_affiliates_tab_content .fs_affiliates_tab_inner_content .fs_affiliates_save_msg{
        margin-left:0px;
        border:1px solid #138f30 !important;
        padding:0px !important;
        box-shadow:0 0 1px #138f30  !important;
        background:#138f30 !important;;
    }
    .fs_affiliates_save_msg p{
        margin:0px !important;
        padding:10px 0px 10px 10px !important;
        color:#fff !important;
        font-weight:bold;
    }
    .fs_affiliates_save_msg p i{
        margin-right:10px;
        font-size:16px;
    }

    /*  shortcode table design   */
    table.fs_affiliates_shortcodes_info{
        width:100%;
        border-collapse:collapse;
        border:none;
        margin-top:20px;
        display:inline-table;
    }
    table.fs_affiliates_shortcodes_info thead th{
        padding:15px 10px;
        color:#fff;
        border:1px solid #ccc;
        font-size:16px;
    }
    table.fs_affiliates_shortcodes_info tbody td{
        border:1px solid #ccc;
        padding:10px;
    }
    table.fs_affiliates_shortcodes_info tbody tr:nth-child(even){
        background:#eee;
    }
    table.fs_affiliates_shortcodes_info tbody tr td:first-child{
        font-weight:bold;
        font-size:14px;
    }
    <?php if ( $color_mode != '2' ) { ?>
        /* Dark change css code */

        .fs_affiliates_wrapper ul.fs_affiliates_tab_ul{
            background-image: linear-gradient(#444, #333, #444);
        }
        .fs_affiliates_wrapper ul.fs_affiliates_tab_ul li.fs_affiliates_tab_li a.fs_affiliates_tab_a{
            color: #fff;
            background: linear-gradient(#444, #333, #444);
        }
        .fs_affiliates_wrapper ul.fs_affiliates_tab_ul li.fs_affiliates_tab_li a.nav-tab-active{
            background:#222;
        }
        .fs_affiliates_wrapper ul.fs_affiliates_tab_ul li.fs_affiliates_tab_li a.fs_affiliates_tab_a:hover,
        .fs_affiliates_wrapper ul.fs_affiliates_tab_ul li.fs_affiliates_tab_li a.fs_affiliates_tab_a:focus{
            background:#222;
        }
        .fs_affiliates_wrapper ul.fs_affiliates_tab_ul li.fs_affiliates_tab_li a.nav-tab-active:after {
            border-color: #222 transparent transparent transparent;
        }
        table.fs_affiliates_shortcodes_info thead{
            background-image: linear-gradient(#444, #333, #444);
        }
        /* light change css code */
    <?php } else { ?>

        .fs_affiliates_wrapper ul.fs_affiliates_tab_ul{
            /*            background:linear-gradient(to bottom, rgba(240,240,240,1) 0%,rgba(255,255,255,1) 50%,rgba(240,240,240,1) 100%) !important;*/
            background:#e9e9e9;
        }
        .fs_affiliates_wrapper ul.fs_affiliates_tab_ul li.fs_affiliates_tab_li a.fs_affiliates_tab_a{
            color: #444;
            /*            background:linear-gradient(to bottom, rgba(240,240,240,1) 0%,rgba(255,255,255,1) 50%,rgba(240,240,240,1) 100%);*/
            background:#e9e9e9;
        }
        .fs_affiliates_wrapper ul.fs_affiliates_tab_ul li.fs_affiliates_tab_li a.nav-tab-active{
            background:#fff;
        }
        .fs_affiliates_wrapper ul.fs_affiliates_tab_ul li.fs_affiliates_tab_li a.fs_affiliates_tab_a:hover,
        .fs_affiliates_wrapper ul.fs_affiliates_tab_ul li.fs_affiliates_tab_li a.fs_affiliates_tab_a:focus{
            background:#fff;
        }
        .fs_affiliates_wrapper ul.fs_affiliates_tab_ul li.fs_affiliates_tab_li a.nav-tab-active:after {
            border-color: #fff transparent transparent transparent;
        }
        table.fs_affiliates_shortcodes_info thead{
            background: #999;
        }
    <?php } ?>
    .fs_affiliates_error_tip{
        margin-top:5px;
        background:#e80d0d;
        color:#fff;
        font-size:12px;
        width:340px;
        padding:5px;
        border-radius: 6px;
        text-align: center;
    }
</style>
      <?php

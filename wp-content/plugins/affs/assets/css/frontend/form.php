<?php
//Register Forms get options

$login_style_type                   = get_option( 'fs_affiliates_login_form_style' ) ;
$login_form_title_color             = get_option( 'fs_affiliates_login_form_title_color' ) ;
$login_form_field_color             = get_option( 'fs_affiliates_login_form_field_color' ) ;
$login_form_button_color            = get_option( 'fs_affiliates_login_form_button_color' ) ;
$login_form_button_background_color = get_option( 'fs_affiliates_login_form_button_background_color' ) ;
$login_form_background_color        = get_option( 'fs_affiliates_login_form_background_color' ) ;
$login_form_border_color            = get_option( 'fs_affiliates_login_form_border_color' ) ;

//Register Forms get options

$register_style_type                   = get_option( 'fs_affiliates_register_form_style' ) ;
$register_form_title_color             = get_option( 'fs_affiliates_register_form_title_color' ) ;
$register_form_field_color             = get_option( 'fs_affiliates_register_form_field_color' ) ;
$register_form_button_color            = get_option( 'fs_affiliates_register_form_button_color' ) ;
$register_form_button_background_color = get_option( 'fs_affiliates_register_form_button_background_color' ) ;
$register_form_background_color        = get_option( 'fs_affiliates_register_form_background_color' ) ;
$register_form_border_color            = get_option( 'fs_affiliates_register_form_border_color' ) ;
?>
<style type="text/css">
    /*login form design*/
    .fs_affiliates_form_preview_wrap {
        width:1070px;
    }
    .fs_affiliates_error{
        margin-left:0px;
        margin-bottom:15px;
        border:1px solid #cb0000 !important;
        padding:0px !important;
        box-shadow:0 0 1px #cb0000  !important;
        color:#fff;
        background:#cb0000 !important;
    }
    .fs_affiliates_message{
        margin-left:0px;
        margin-bottom:15px;
        border:1px solid #138f30 !important;
        padding:0px !important;
        box-shadow:0 0 1px #138f30  !important;
        color:#fff;
        background:#138f30 !important;
    }
    .fs_affiliates_message p, .fs_affiliates_error p{
        margin-bottom:0px;
        padding:10px;
    }
    .fs_affiliates_message p i, .fs_affiliates_error p i{
        margin-right:10px;
        font-size:16px;
    }
    .fs_affiliates_login_form_preview{
        margin-left:50px;
    }
    .fs_affiliates_login_form_preview, .fs_affiliates_register_form_preview{
        width:500px;
        float:left;
    }
    .fs_affiliates_form_preview_wrap .fs_affiliates_login_form_preview .fs_affiliates_log_form{
        margin: 50px 0px;
        width:400px;

    }
    .fs_affiliates_form_preview_wrap .fs_affiliates_login_form_preview .fs_affiliates_log_form .fs-affiliates-form-row{
        width:300px;
        position:relative;
        margin:0 auto;
    }
    .fs_affiliates_forms{
        width:100%;
        float:left;
    }

    .fs_affiliates_log_form{
        margin:50px auto;
        width:400px;
        background:<?php echo $login_form_background_color ; ?>;
        border:1px solid <?php echo $login_form_border_color ; ?>;
        box-shadow: 0 0 5px #000;
        border-radius: 10px;
    }
    .fs_affiliates_log_form .fs-affiliates-form-row .fs_affiliates_lostpwd_info{
        display:block;
        margin-top:10px;
        margin-bottom:10px;
        color:#d42020 !important;
        font-size:16px;
    }
    .fs_affiliates_log_form .fs-affiliates-form-row{
        width:300px;
        position:relative;
        margin:0 auto;
    }
    .fs_affiliates_log_form .fs-affiliates-form-row label{
        display:block;
        margin-top:10px;
        margin-bottom:10px;
        color:<?php echo $login_form_field_color ; ?> !important;
        font-size:16px;
    }
    .fs_affiliates_log_form .fs_affiliates_login_form_header{
        width:90%;
        margin:0 auto;
        padding:10px; 
        text-align:center;
    }

    .fs_affiliates_log_form .fs_affiliates_login_form_header h3{
        display:block;
        text-align: center;
        color:<?php echo $login_form_title_color ; ?>;
        font-weight:bold;
        font-weight:18px;
        text-transform: none;
    }
    .fs_affiliates_log_form .fs-affiliates-form-row .fs_login_form_foot_top, 
    .fs_affiliates_log_form .fs-affiliates-form-row .fs_login_form_foot_bottom{
        width:100%;
        display:block;
        margin-top:10px;
    }
    .fs_affiliates_log_form .fs-affiliates-form-row .fs_login_form_foot_top a{
        float:right;
        color:#00f;
        text-align:right;
    }
    .fs_affiliates_log_form .fs-affiliates-form-row .fs_login_form_foot_bottom a{
        color:#00f;
        margin-right:140px !important;
        float:right;
        padding:10px 15px;
        margin:15px 0px;
        box-shadow:none !important;
    }

    .fs-affiliates-form-row .g-recaptcha{
        margin-top:15px;
    }
    <?php if ( $login_style_type == '2' ) { ?>
        /**********************Model-2****************************/

        .fs_affiliates_log_form .fs-affiliates-form-row input[type='text'],
        .fs_affiliates_log_form .fs-affiliates-form-row input[type='password']{
            height:35px;
            border-radius:0px;
            border:1px solid #ccc;
            width:100%;
            padding:0px 0px 0px 35px;
        }
        .fs_affiliates_log_form .fs-affiliates-form-row input[type='text']{
            background:#fff url('<?php echo FS_AFFILIATES_PLUGIN_URL . '/assets/images/frontend/user.png' ?>') left 8px center no-repeat;
        }
        .fs_affiliates_log_form .fs-affiliates-form-row input[type='password']{
            background:#fff url('<?php echo FS_AFFILIATES_PLUGIN_URL . '/assets/images/frontend/password.png' ?>') left 8px center no-repeat;
        }
        .fs_affiliates_log_form .fs-affiliates-form-row .fs-affiliates-button{
            background:<?php echo $login_form_button_background_color ; ?> ;
            border:2px solid <?php echo $login_form_button_background_color ; ?> !important;
            color:<?php echo $login_form_button_color ; ?> !important;
            border-radius:5px !important;
            height:auto !important;
            padding:10px 15px;
            margin:15px 0px;
            box-shadow:none !important;
        }
        .fs_affiliates_log_form .fs-affiliates-form-row .fs-affiliates-button:hover{
            border:2px solid <?php echo $login_form_button_background_color ; ?> !important;
            background:#fff;
            color:#000 !important;
        }
    <?php } elseif ( $login_style_type == '3' ) { ?>
        /**********************Model-3****************************/

        .fs_affiliates_log_form .fs-affiliates-form-row input[type='text'],
        .fs_affiliates_log_form .fs-affiliates-form-row input[type='password']{
            height:35px;
            border-radius:0px;
            border:1px solid #ccc;
            width:100%;
            padding:0px 0px 0px 45px;
        }
        .fs_affiliates_log_form .fs-affiliates-form-row input[type='text']{
            background:#fff url('<?php echo FS_AFFILIATES_PLUGIN_URL . '/assets/images/frontend/dark-username.png' ?>') left center no-repeat;
        }
        .fs_affiliates_log_form .fs-affiliates-form-row input[type='password']{
            background:#fff url('<?php echo FS_AFFILIATES_PLUGIN_URL . '/assets/images/frontend/dark-password.png' ?>') left center no-repeat;
        }
        .fs_affiliates_log_form .fs-affiliates-form-row .fs-affiliates-button{
            background:#fff;
            border:2px solid <?php echo $login_form_button_background_color ; ?> !important;
            height:auto !important;
            color:#000 !important;
            border-radius:5px !important;
            padding:10px 15px;
            margin:15px 0px;
            box-shadow:none !important;
        }
        .fs_affiliates_log_form .fs-affiliates-form-row .fs-affiliates-button:hover{
            border:2px solid <?php echo $login_form_button_background_color ; ?> !important;
            background:<?php echo $login_form_button_background_color ; ?>;
            color:<?php echo $login_form_button_color ; ?> !important;
        }

    <?php } elseif ( $login_style_type == '4' ) { ?>

        /**********************Model-4****************************/

        .fs_affiliates_log_form .fs-affiliates-form-row input[type='text'],
        .fs_affiliates_log_form .fs-affiliates-form-row input[type='password']{
            height:35px;
            border-radius:20px;
            border:1px solid #ccc;
            width:100%;
            padding:0px 0px 0px 45px;
        }
        .fs_affiliates_log_form .fs-affiliates-form-row input[type='text']{
            background:#fff url('<?php echo FS_AFFILIATES_PLUGIN_URL . '/assets/images/frontend/user.png' ?>') left 12px center no-repeat;
        }
        .fs_affiliates_log_form .fs-affiliates-form-row input[type='password']{
            background:#fff url('<?php echo FS_AFFILIATES_PLUGIN_URL . '/assets/images/frontend/password.png' ?>') left 12px center no-repeat;
        }
        .fs_affiliates_log_form .fs-affiliates-form-row input[type='text']:hover, .fs_affiliates_log_form .fs-affiliates-form-row input[type='text']:focus,
        .fs_affiliates_log_form .fs-affiliates-form-row input[type='password']:hover, .fs_affiliates_log_form .fs-affiliates-form-row input[type='password']:focus{
            border:1px solid <?php echo $login_form_button_background_color ; ?>;
        }
        .fs_affiliates_log_form .fs-affiliates-form-row .fs-affiliates-button{
            background:<?php echo $login_form_button_background_color ; ?> ;
            border:2px solid <?php echo $login_form_button_background_color ; ?> !important;
            color:<?php echo $login_form_button_color ; ?> !important;
            border-radius:5px !important;
            height:auto !important;
            padding:10px 15px;
            margin:15px 0px;
            box-shadow:none !important;
        }
        .fs_affiliates_log_form .fs-affiliates-form-row .fs-affiliates-button:hover{
            border:2px solid <?php echo $login_form_button_background_color ; ?> ; !important;
            background:#fff;
            color:#000 !important;
        }

    <?php } elseif ( $login_style_type == '5' ) { ?>

        /**********************Model-5****************************/
        .fs_affiliates_log_form .fs-affiliates-form-row input[type='text'],
        .fs_affiliates_log_form .fs-affiliates-form-row input[type='password']{
            height:35px;
            border-radius:0px;
            border-top:0px;
            border-left:0px;
            border-right:0px;
            border-bottom:2px solid #ccc;
            width:100%;
            padding:0px 0px 0px 30px;
        }
        .fs_affiliates_log_form .fs-affiliates-form-row input[type='text']{
            background:#fff url('<?php echo FS_AFFILIATES_PLUGIN_URL . '/assets/images/frontend/user.png' ?>') left center no-repeat;
        }
        .fs_affiliates_log_form .fs-affiliates-form-row input[type='password']{
            background:#fff url('<?php echo FS_AFFILIATES_PLUGIN_URL . '/assets/images/frontend/password.png' ?>') left center no-repeat;
        }

        .fs_affiliates_log_form .fs-affiliates-form-row span.border{
            width:100%;
            position:relative;
            display: block;
        }
        .fs_affiliates_log_form .fs-affiliates-form-row span.border:before,
        .fs_affiliates_log_form .fs-affiliates-form-row span.border:after{
            content:'';
            width:0;
            height:2px;
            bottom:0px;
            position:absolute;
            transition: 0.5s ease all;
            background:<?php echo $login_form_button_background_color ; ?>;
        }
        .fs_affiliates_log_form .fs-affiliates-form-row span.border:before{
            left:50%;
        }
        .fs_affiliates_log_form .fs-affiliates-form-row span.border:after{
            right:50%;
        }
        .fs_affiliates_log_form .fs-affiliates-form-row input[type='text']:focus ~ span.border:before,
        .fs_affiliates_log_form .fs-affiliates-form-row input[type='text']:focus ~ span.border:after,
        .fs_affiliates_log_form .fs-affiliates-form-row input[type='password']:focus ~ span.border:before,
        .fs_affiliates_log_form .fs-affiliates-form-row input[type='password']:focus ~ span.border:after{
            width:50%;
        }

        .fs_affiliates_log_form .fs-affiliates-form-row .fs-affiliates-button{
            background:<?php echo $login_form_button_background_color ; ?> ;
            border:2px solid <?php echo $login_form_button_background_color ; ?> !important;
            color:<?php echo $login_form_button_color ; ?> !important;
            border-radius:5px !important;
            height:auto !important;
            padding:10px 15px;
            margin:15px 0px;
            box-shadow:none !important;
        }
        .fs_affiliates_log_form .fs-affiliates-form-row .fs-affiliates-button:hover{
            border:2px solid <?php echo $login_form_button_background_color ; ?> !important;
            background:#fff;
            color:#000 !important;
        }

    <?php } else { ?>

        /********************** Model-1 ****************************/

        .fs_affiliates_log_form .fs-affiliates-form-row label{
            display:block;
            margin-top:10px;
            margin-bottom:10px;
            color:#000;
            font-size:16px;
        }
        .fs_affiliates_log_form .fs-affiliates-form-row input[type='text'],
        .fs_affiliates_log_form .fs-affiliates-form-row input[type='password']{
            height:35px;
            border-radius:5px;
            border:1px solid #ccc;
            width:100%;
            padding:0px 0px 0px 10px;
        }
        .fs_affiliates_log_form .fs-affiliates-form-row input[type='text']:focus,
        .fs_affiliates_log_form .fs-affiliates-form-row input[type='password']:focus{
            background:#fff;
        }
        .fs_affiliates_log_form .fs-affiliates-form-row .fs-affiliates-button{
            background:<?php echo $login_form_button_background_color ; ?> ;
            border:2px solid <?php echo $login_form_button_background_color ; ?> !important;
            color:<?php echo $login_form_button_color ; ?> !important;
            border-radius:5px !important;
            height:auto !important;
            padding:10px 15px;
            margin:15px 0px;
            box-shadow:none !important;
        }
        .fs_affiliates_log_form .fs-affiliates-form-row .fs-affiliates-button:hover{
            border:2px solid <?php echo $login_form_button_background_color ; ?> !important;
            background:#fff;
            color:#000 !important;
        }
        /*login form design end*/

    <?php } ?>
    /* Register form design */
    .fs_affiliates_form_preview_wrap .fs_affiliates_register_form_preview .fs_affiliates_register_form{
        margin: 50px auto;
        width:500px;
    }
    .fs_affiliates_form_preview_wrap .fs_affiliates_register_form_preview .fs_affiliates_register_form .fs-affiliates-form-row,
    .fs_affiliates_form_preview_wrap .fs_affiliates_register_form_preview .fs_affiliates_register_form .fs_affiliates_file_uploader{
        width:400px;
        position:relative;
        margin:0 auto;
    }
    .fs_affiliates_form_preview_wrap .fs_affiliates_register_form_preview .fs_affiliates_register_form .fs_affiliates_file_uploader{
        margin-bottom:10px;
    }
    #fs_affiliates_register_form{
        float:left;
        width:100%;
    }
    .fs_affiliates_register_form{
        margin: 50px auto;
        width:600px;
        background:<?php echo $register_form_background_color ; ?>;
        border:1px solid <?php echo $register_form_border_color ; ?>;
        border-radius: 10px;
        box-shadow: 0 0 3px #000;
    }
    .fs_affiliates_register_form .fs_affiliates_login_form_header{
        width:90%;
        margin:0 auto;
        padding:10px; 
        text-align:center;
    }
    .fs_affiliates_register_form .fs_affiliates_login_form_header h3{
        display:block;
        text-align: center;
        color:<?php echo $register_form_title_color ; ?>;
        font-weight:bold;
        font-weight:18px;
        text-transform: none;
    }
    .fs_affiliates_register_form .fs-affiliates-form-row,
    .fs_affiliates_register_form .fs_affiliates_file_uploader{
        width:500px;
        position:relative;
        margin:0 auto;
    }
    .fs_affiliates_register_form .fs_affiliates_file_uploader{
        margin-bottom:10px !important;
    }
    .fs_affiliates_register_form .fs-affiliates-form-row label{
        display:block;
        margin-top:10px;
        margin-bottom:10px;
        color:<?php echo $register_form_field_color ; ?>;
        font-size:16px;
    }
    .fs_affiliates_register_form .fs-affiliates-form-row .select2-container{
        width:100% !important;
    }
    .fs_affiliates_register_form .fs-affiliates-form-row input[type='text'],
    .fs_affiliates_register_form .fs-affiliates-form-row input[type='password'],
    .fs_affiliates_register_form .fs-affiliates-form-row input[type='url'],
    .fs_affiliates_register_form .fs-affiliates-form-row input[type='email'],
    .fs_affiliates_register_form .fs-affiliates-form-row select{
        height:35px;
    }

    .fs_affiliates_notice span.fs_affiliates_success{
        color:#09bc1b;
    }
    .fs_affiliates_notice span.fs_affiliates_warning{
        color:#d30034;
    }
    .fs_affiliates_notice{
        color:#d30034;
    }
    <?php if ( $register_style_type == '2' ) { ?>

        /*********************model-2*************************/

        .fs_affiliates_register_form .fs-affiliates-form-row input[type='text'],
        .fs_affiliates_register_form .fs-affiliates-form-row input[type='password'],
        .fs_affiliates_register_form .fs-affiliates-form-row input[type='url'],
        .fs_affiliates_register_form .fs-affiliates-form-row input[type='email'],
        .fs_affiliates_register_form .fs-affiliates-form-row textarea,
        .fs_affiliates_register_form .fs-affiliates-form-row select
        {
            border-radius:20px;
            border:1px solid #ccc;
            width:100%;
            padding:0px 0px 0px 10px;
        }
        .fs_affiliates_register_form .fs-affiliates-form-row input[type='text']:focus,
        .fs_affiliates_register_form .fs-affiliates-form-row input[type='password']:focus,
        .fs_affiliates_register_form .fs-affiliates-form-row input[type='email']:focus,
        .fs_affiliates_register_form .fs-affiliates-form-row input[type='url']:focus,
        .fs_affiliates_register_form .fs-affiliates-form-row textarea:focus,
        .fs_affiliates_register_form .fs-affiliates-form-row select:focus,
        .fs_affiliates_register_form .fs-affiliates-form-row .select2-selection--single:focus{
            border:2px solid <?php echo $register_form_button_background_color ; ?>;
        }
        .fs_affiliates_register_form .fs-affiliates-form-row .select2-selection--single {
            border-radius:20px;
        }
        .fs_affiliates_register_form .fs-affiliates-form-row .fs-affiliates-button{
            background:<?php echo $register_form_button_background_color ; ?> ;
            border:2px solid <?php echo $register_form_button_background_color ; ?> !important;
            color:<?php echo $register_form_button_color ; ?> !important;
            height:auto !important;
            border-radius:5px !important;
            padding:10px 15px;
            margin:15px 0px;
            box-shadow:none !important;

        }
        .fs_affiliates_register_form .fs-affiliates-form-row .fs-affiliates-button:hover{
            border:2px solid <?php echo $register_form_button_background_color ; ?>  !important;
            background:#fff;
            color:#000 !important;
        }

    <?php } elseif ( $register_style_type == '3' ) { ?>

        /*********************model-3*************************/

        .fs_affiliates_register_form .fs-affiliates-form-row input[type='text'],
        .fs_affiliates_register_form .fs-affiliates-form-row input[type='password'],
        .fs_affiliates_register_form .fs-affiliates-form-row input[type='url'],
        .fs_affiliates_register_form .fs-affiliates-form-row input[type='email'],
        .fs_affiliates_register_form .fs-affiliates-form-row textarea,
        .fs_affiliates_register_form .fs-affiliates-form-row select{
            border-radius:4px;
            border-top:1px solid #ddd;
            border-left:1px solid #ddd;
            border-right:1px solid #ddd;
            border-bottom:2px solid #aaa;
            width:100%;
            padding:0px 0px 0px 10px;
        }
        .fs_affiliates_register_form .fs-affiliates-form-row .select2-selection--single {
            border-radius:4px;
            border-top:1px solid #ddd;
            border-left:1px solid #ddd;
            border-right:1px solid #ddd;
            border-bottom:2px solid #aaa;
        }
        .fs_affiliates_register_form .fs-affiliates-form-row input[type='text']:focus,
        .fs_affiliates_register_form .fs-affiliates-form-row input[type='password']:focus,
        .fs_affiliates_register_form .fs-affiliates-form-row input[type='email']:focus,
        .fs_affiliates_register_form .fs-affiliates-form-row input[type='url']:focus,
        .fs_affiliates_register_form .fs-affiliates-form-row textarea:focus,
        .fs_affiliates_register_form .fs-affiliates-form-row select:focus,
        .fs_affiliates_register_form .fs-affiliates-form-row .select2-selection--single:focus{
            border-bottom:2px solid <?php echo $register_form_button_background_color ; ?>;
        }
        .fs_affiliates_register_form .fs-affiliates-form-row .fs-affiliates-button{
            background:<?php echo $register_form_button_background_color ; ?> ;
            border:2px solid <?php echo $register_form_button_background_color ; ?> !important;
            color:<?php echo $register_form_button_color ; ?> !important;
            border-radius:5px !important;
            padding:10px 15px;
            margin:15px 0px;
            height:auto !important;
            box-shadow:none !important;

        }
        .fs_affiliates_register_form .fs-affiliates-form-row .fs-affiliates-button:hover{
            border:2px solid <?php echo $register_form_button_background_color ; ?> !important;
            background:#fff;
            color:#000 !important;
        }

    <?php } elseif ( $register_style_type == '4' ) { ?>

        /*********************model-4*************************/
        .fs_affiliates_register_form{
            margin: 50px auto;
            width:98% !important;
            background:<?php echo $register_form_background_color ; ?>;
            border:1px solid #bbb;
            border-radius: 10px;
            box-shadow: 0 0 3px <?php echo $register_form_border_color ; ?>;
        }
        .fs_affiliates_register_form .fs-affiliates-form-row{
            width:95% !important;
            position:relative;
            margin:0 auto;
            float:none;
        }
        .fs_affiliates_register_form .fs-affiliates-form-row input[type='text'],
        .fs_affiliates_register_form .fs-affiliates-form-row input[type='password'],
        .fs_affiliates_register_form .fs-affiliates-form-row input[type='url'],
        .fs_affiliates_register_form .fs-affiliates-form-row input[type='email'],
        .fs_affiliates_register_form .fs-affiliates-form-row textarea,
        .fs_affiliates_register_form .fs-affiliates-form-row select{
            border-radius:5px;
            border:1px solid #ccc;
            width:100%;
            padding:0px 0px 0px 10px;
        }
        .fs_affiliates_register_form .fs-affiliates-form-row .fs-affiliates-button{
            background:<?php echo $register_form_button_background_color ; ?> ;
            border:2px solid <?php echo $register_form_button_background_color ; ?> !important;
            color:<?php echo $register_form_button_color ; ?> !important;
            border-radius:5px !important;
            padding:10px 15px;
            margin:15px 0px;
            height:auto !important;
            box-shadow:none !important;
            border:2px solid #52ac67;
        }
        .fs_affiliates_register_form .fs-affiliates-form-row .fs-affiliates-button:hover{
            border:2px solid <?php echo $register_form_button_background_color ; ?> !important;
            background:#fff;
            color:#000 !important;
        }

    <?php } else { ?>

        /***********Model-1********************/

        .fs_affiliates_register_form .fs-affiliates-form-row input[type='text'],
        .fs_affiliates_register_form .fs-affiliates-form-row input[type='password'],
        .fs_affiliates_register_form .fs-affiliates-form-row input[type='url'],
        .fs_affiliates_register_form .fs-affiliates-form-row input[type='email'],
        .fs_affiliates_register_form .fs-affiliates-form-row textarea,
        .fs_affiliates_register_form .fs-affiliates-form-row select{
            border-radius:5px;
            border:1px solid #ccc;
            width:100%;
            padding:0px 0px 0px 10px;
        }
        .fs_affiliates_register_form .fs-affiliates-form-row .fs-affiliates-button{
            background:<?php echo $register_form_button_background_color ; ?> ;
            border:2px solid <?php echo $register_form_button_background_color ; ?> !important;
            color:<?php echo $register_form_button_color ; ?> !important;
            height:auto !important;
            border-radius:5px !important;
            padding:10px 15px;
            margin:15px 0px;
            box-shadow:none !important;
            border:2px solid #52ac67;
        }
        .fs_affiliates_register_form .fs-affiliates-form-row .fs-affiliates-button:hover{
            border:2px solid <?php echo $register_form_button_background_color ; ?>  !important;
            background:#fff;
            color:#000 !important;
        }
    </style>
<?php } ?>
<style type='text/css'>
    /* register form design media query */

    @media screen and (max-width: 700px){
        .fs_affiliates_register_form{
            margin: 50px auto;
            width:300px;
            background:<?php echo $register_form_background_color ; ?>;
            border:1px solid <?php echo $register_form_border_color ; ?>;
            border-radius: 10px;
            box-shadow: 0 0 3px #000;
        }
        
        .fs_affiliates_register_form .fs-affiliates-form-row, 
        .fs_affiliates_register_form .fs_affiliates_file_uploader{
            width:280px;
            position:relative;
            margin:0 auto;
        }
        
    }
    /* register form design media query  end*/

    /* login form design media query */

    @media screen and (max-width: 600px){
        .fs_affiliates_log_form{
            margin:50px auto;
            width:300px;
            background:<?php echo $login_form_background_color ; ?>;
            border:1px solid <?php echo $login_form_border_color ; ?>;
            box-shadow: 0 0 5px #000;
            border-radius: 10px;
        }
        .fs_affiliates_log_form .fs-affiliates-form-row{
            width:280px;
            position:relative;
            margin:0 auto;
        }

    }
    /* login form design media query end*/
</style>
<?php

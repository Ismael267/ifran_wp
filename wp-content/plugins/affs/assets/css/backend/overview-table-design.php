<?php $color_mode = get_option( 'fs_affiliates_settings_color_mode' , 1 ) ; ?>
<style>
    .fs_affiliates_overview_wrapper{
        width:100%;
        float:left;
    }
    .fs_affiliates_overview_wrapper .fs_affiliates_overview_colm_one,
    .fs_affiliates_overview_wrapper .fs_affiliates_overview_colm_two{
        width:48%;
        float:left;
    }
    .fs_affiliates_overview_wrapper .fs_affiliates_overview_colm_two{
        margin-left:2%;
    }
    .fs_affiliates_overview_wrapper .fs_affiliates_overview_colm_one table,
    .fs_affiliates_overview_wrapper .fs_affiliates_overview_colm_two table{
        width:98%;
        margin:20px 0px;
        float:left;
        border-collapse: collapse;
        box-shadow:0 0 3px #000;
    }
    .fs_affiliates_overview_wrapper .fs_affiliates_overview_colm_one table th,
    .fs_affiliates_overview_wrapper .fs_affiliates_overview_colm_two table th{
        font-weight: bold;
        border:1px solid #eee;
        padding:10px;
        text-align: center;
        color:#fff;
        font-size:14px;
    }
    .fs_affiliates_overview_wrapper .fs_affiliates_overview_colm_one table td,
    .fs_affiliates_overview_wrapper .fs_affiliates_overview_colm_two table td{
        padding:10px;
        border:1px solid #eee;
        color:#000;
        font-size:14px;
        text-align:center;
    }

    <?php if ( $color_mode != '2' ) { ?>

        /* Dark change css code */


        /* Paid Unpaid  table design */

        .fs_affiliates_overview_wrapper .fs_affiliates_overview_colm_one table.fs_afffiliates_overview_paid tr:nth-child(2n+1),
        .fs_affiliates_overview_wrapper .fs_affiliates_overview_colm_one table.fs_afffiliates_overview_unpaid_count tr:nth-child(2n+1),
        .fs_affiliates_overview_wrapper .fs_affiliates_overview_colm_one table.fs_afffiliates_overview_unpaid tr:nth-child(2n+1){
            background:#e3f2d3;
        }
        .fs_affiliates_overview_wrapper .fs_affiliates_overview_colm_one table.fs_afffiliates_overview_paid tr th,
        .fs_affiliates_overview_wrapper .fs_affiliates_overview_colm_one table.fs_afffiliates_overview_unpaid_count tr th,
        .fs_affiliates_overview_wrapper .fs_affiliates_overview_colm_one table.fs_afffiliates_overview_unpaid tr th{
            background:#86ca43;
        }
        .fs_affiliates_overview_wrapper .fs_affiliates_overview_colm_one table.fs_afffiliates_overview_paid tr th:nth-child(odd),
        .fs_affiliates_overview_wrapper .fs_affiliates_overview_colm_one table.fs_afffiliates_overview_unpaid_count tr th:nth-child(odd),
        .fs_affiliates_overview_wrapper .fs_affiliates_overview_colm_one table.fs_afffiliates_overview_unpaid tr th:nth-child(odd){
            background:#a4e265;
        }

        /*  Most valuable Affiliates table design */

        .fs_affiliates_overview_wrapper .fs_affiliates_overview_colm_one table.fs_afffiliates_overview_valuable_affiliates tr:nth-child(2n+1){
            background:#c3f7ed;
        }
        .fs_affiliates_overview_wrapper .fs_affiliates_overview_colm_one table.fs_afffiliates_overview_valuable_affiliates tr th{

            background:#39ccaf;
        }
        .fs_affiliates_overview_wrapper .fs_affiliates_overview_colm_one table.fs_afffiliates_overview_valuable_affiliates tr th:nth-child(odd){
            background:#3bd3b5;
        }

        /*  Most Recent Referrals  table design */

        .fs_affiliates_overview_wrapper .fs_affiliates_overview_colm_one table.fs_afffiliates_overview_recent_affiliates tr:nth-child(2n+1){
            background:#f0dcdb;
        }
        .fs_affiliates_overview_wrapper .fs_affiliates_overview_colm_one table.fs_afffiliates_overview_recent_affiliates tr th{

            background:#ef4933;
        }
        .fs_affiliates_overview_wrapper .fs_affiliates_overview_colm_one table.fs_afffiliates_overview_recent_affiliates tr th:nth-child(odd){
            background:#de1f05;
        }

        /*  Referrals Visit  table design */

        .fs_affiliates_overview_wrapper .fs_affiliates_overview_colm_two table.fs_afffiliates_overview_referrals_visit tr:nth-child(2n+1){
            background:#d3ecf1;
        }
        .fs_affiliates_overview_wrapper .fs_affiliates_overview_colm_two table.fs_afffiliates_overview_referrals_visit tr th{
            background:#42b0cb;

        }
        .fs_affiliates_overview_wrapper .fs_affiliates_overview_colm_two table.fs_afffiliates_overview_referrals_visit tr th:nth-child(odd){
            background:#4bbad5;
        }

        /*   Most Recent Affiliates table design */

        .fs_affiliates_overview_wrapper .fs_affiliates_overview_colm_two table.fs_afffiliates_ov_affiliates tr:nth-child(2n+1){
            background:#efefef;
        }
        .fs_affiliates_overview_wrapper .fs_affiliates_overview_colm_two table.fs_afffiliates_ov_affiliates tr th{

            background:#555555;
        }
        .fs_affiliates_overview_wrapper .fs_affiliates_overview_colm_two table.fs_afffiliates_ov_affiliates tr th:nth-child(odd){
            background:#333333;
        }

        /* Referrals table design */

        .fs_affiliates_overview_wrapper .fs_affiliates_overview_colm_two table.fs_afffiliates_ov_referrals tr:nth-child(2n+1){
            background:#9fbefd;
        }
        .fs_affiliates_overview_wrapper .fs_affiliates_overview_colm_two table.fs_afffiliates_ov_referrals tr th{

            background:#0139ac;
        }
        .fs_affiliates_overview_wrapper .fs_affiliates_overview_colm_two table.fs_afffiliates_ov_referrals tr th:nth-child(odd){
            background:#00339a;
        }

        /* High Conversion URL's table design */

        .fs_affiliates_overview_wrapper .fs_affiliates_overview_colm_two table.fs_afffiliates_overview_recent_referrals tr:nth-child(2n+1){
            background:#fca9ff;
        }
        .fs_affiliates_overview_wrapper .fs_affiliates_overview_colm_two table.fs_afffiliates_overview_recent_referrals tr th{

            background:#85028a;
        }
        .fs_affiliates_overview_wrapper .fs_affiliates_overview_colm_two table.fs_afffiliates_overview_recent_referrals tr th:nth-child(odd){
            background:#a001a6;
        }

    <?php } else { ?>
        /* Light change css code */
        .fs_affiliates_overview_wrapper table tr:nth-child(2n+1)
        {
            background:#eee;
        }
        .fs_affiliates_overview_wrapper table tr th{
            background:#999;
        }
        .fs_affiliates_overview_wrapper table tr th:nth-child(odd){
            background:#999;
        }
      <?php } ?>

      </style> <?php

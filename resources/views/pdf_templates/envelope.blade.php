<?php
ini_set('default_charset', 'UTF-8');
mb_internal_encoding('UTF-8');
header('Content-Type: text/html; charset=UTF-8');
date_default_timezone_set('Asia/Tokyo');
?>
<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
    <title>請求書封筒</title>
    <style>
        * {
            margin: 0;
            padding: 0;
        }

        /**************/
        .clearfix:before,
        .clearfix:after {
            content: " ";
            display: table;
        }

        .clearfix:after {
            clear: both
        }

        .col-md-1,
        .col-md-2,
        .col-md-3,
        .col-md-4,
        .col-md-5,
        .col-md-6,
        .col-md-7,
        .col-md-8,
        .col-md-9,
        .col-md-10,
        .col-md-11,
        .col-md-12 {
            float: left
        }

        .col-md-6 {
            margin: 5px 0;
            padding: 0;
        }

        .col-md-6 {
            width: 50%
        }

        .col-md-4 {
            width: 33.33333333%
        }

        .col-md-4 {
            margin: 5px 0;
            padding: 0;
            text-align: center;
        }

        .col-md-12 {
            width: 100%;
            margin: 5px 0;
        }

        .col-xs-12 {
            margin: 3px 0;
            padding: 0;
            width: 100%;
            float: left;
        }

        .textLayer1 {
            margin-top: 200px;
            padding-top: 40px;
            font-size: 15pt;
            margin-left: 150px;
            width: auto;
            letter-spacing: 2px;
            font-weight: bold;
        }

        .textLayer2 {
            font-size: 15pt;
            margin-left: 150px;
            width: auto;
            font-weight: bold;
        }

        .textLayer3 {
            font-size: 18pt;
            margin-left: 230px;
            margin-top: 15px;
            width: auto;
            font-weight: bold;
        }

        .textLayer4 {
            font-size: 23px;
            margin-top: 20px;
            margin-right: 40px;
            width: 150px;
            float: right;
            padding: 5px 0;
            text-align: center;
            border: 3px solid #878787;
            color: #878787;

        }

        .textLayer5 {
            width: 20%;
            text-align: left;
        }

        .textLayer6 {
            width: 49%;
            text-align: left
        }

        .textLayer7 {
            width: 30%;
            margin-top: -5px;
        }

        .textLayer5 span {
            font-weight: bold;
        }

        .logo {
            font-size: 14pt;
            font-weight: bold;
            margin-top: 0;
        }

        .margin-top {
            border-top: 3px solid #000;
            margin-top: 0;
            text-align: right;
            padding-top: 8px;
            font-size: 10pt;
            font-weight: normal
        }

        .margin-bottom {
            margin-bottom: 0
        }

        .margin-top span {
            padding-top: 10px;
            padding-right: 30px
        }

    </style>
</head>

<body>
    <div id="page-wrap">
        <div class="col-md-12 textLayer1">〒{{ $address->zip }}</div>
        <div class="clearfix"></div>
        <div class="col-md-12 textLayer2">{{ $address->address1 }} {{ $address->address2 }}
            {{ $address->address3 }} {{ $address->address4 }}</div>
        <div class="clearfix"></div>
        <div class="col-md-12 textLayer3">{{ $address->comp_type }} 御中</div>
        <div class="clearfix"></div>
        <div class="col-md-12 textLayer4" style="font-weight: bold">請求書在中</div>
        <div class="clearfix"></div>
        <div class="col-md-12 margin-bottom">
            <div class="col-md-4 textLayer5">
                <br>
                <span>&nbsp;&nbsp;</span>
                <span style="font-size:9pt;">株式会社</span>
                <span>&nbsp;</span>
                <span style="font-size:9pt;">フォレスカイ</span>
            </div>
            <div class="col-md-4 textLayer6">
                <div style="font-size:8pt;">〒358-0024　埼玉県入間市久保稲荷4-6-4 ハイム粕谷1-103</div>
                <div style="font-size:8pt;margin-top:3px;">
                    <span>TEL：04-2963-1276</span>
                    <span>&nbsp;</span>
                    <span>FAX不可</span>
                </div>
            </div>
            <div class="col-md-4 textLayer7">
                <div class="logo">ShowaDenshi-WEB</div>
                <div style="font-size:8pt;font-weight:normal;">http://www.showadenshisangyo.co.jp/</div>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="col-md-12 margin-top">
            <span>送信日</span>
            <span>&nbsp;&nbsp;&nbsp;&nbsp;</span>
            <span>{{ $year }}年 &nbsp; {{ $month }}月 &nbsp; {{ $day }}日</span>
            <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
        </div>
    </div>
</body>

</html>

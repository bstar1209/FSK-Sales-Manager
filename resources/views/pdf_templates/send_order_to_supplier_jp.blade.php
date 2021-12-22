<?php
if ($order_detail->type_money == 'JPY') {
$type_money = '円';
} elseif ($order_detail->order_header->type_money == 'USD') {
$type_money = '$';
} elseif ($order_detail->order_header->type_money == 'EUR') {
$type_money = '€';
} else {
$type_money = $order_detail->order_header->type_money;
} ?>

<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
    <title>Editable Invoice</title>
    <style>
        * {
            margin: 0;
            padding: 0;
        }

        table {
            border-collapse: collapse;
        }

        table td,
        table th {
            padding: 4px 5px;
            text-align: center;
        }

        #address {
            width: 250px;
            height: 150px;
            float: left;
        }

        .wrapper_bottom {
            width: 100%;
            border-top: 1px solid;
            margin-top: 420px;
            float: left;
            padding-top: 10px;
        }

        #items {
            clear: both;
            width: 100%;
            margin: 0;
        }

        #items th {
            background: #eee
        }

        #items th,
        #items td {
            border-top: 2px solid #000;
            border-bottom: 2px solid #000;
            border-left: 0;
            border-right: 0;
            font-weight: normal;
            font-size: 10pt;
        }

        #items tr.item-row td {
            vertical-align: top;
            border-bottom: 0;
        }

        #items tr.item-comment td {
            border-top: 0;
            border-bottom: 2px solid #000;
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

        .align_left {
            text-align: left
        }

        .align_right {
            text-align: right
        }

        .lg {
            font-style: italic;
            font-size: 12pt;
            font-weight: bold;
        }

        .t_small {
            font-size: 16px;
            padding-left: 10px;
        }

        .margin-right {
            margin-right: 20px;
            min-width: 272px;
            float: right;
            text-align: left;
            width: auto;
        }

        td.column_three,
        td.column_two {
            text-align: left
        }

        .logo {
            border: 2px solid #000;
            text-align: center;
            font-size: 18pt;
            font-style: italic;
            width: 70%;
            margin: 0 auto 0;
            font-weight: bold;
        }

        fieldset {
            border: 2px solid #000;
            padding: 0px 10px;
            /* padding in fieldset support spotty in IE */
            margin: 0;
            width: 90%;
            margin: 0 auto;
        }

        .float_right {
            text-align: right
        }

        .purchase_date {
            border-top: 2px solid #000;
            text-align: right;
            padding: 5px 0;
        }

        .border-bottom {
            border-bottom: 2px solid #000
        }

        .info_vendor {
            text-align: right;
        }

        .title_order {
            width: 100%;
            font-size: 40px;
            font-weight: bold;
            text-align: center;
            float: left;
        }

        .zip_vendor,
        .address_vendor {
            font-size: 17px;
        }

        .sup_vendor {
            font-size: 15px;
            margin-top: 5px;
            margin-right: 22px;
        }

        .name_vendor {
            font-size: 18px;
            margin-top: 5px;
        }

        .fax_vendor {
            width: 150px;
            float: right;
            text-align: left;
        }

        .tel_vendor {
            width: 150px;
            float: right;
        }

        .fax_tel {
            float: right;
            width: 100%;
            margin-top: 26px;
        }

        .code_send {
            margin-right: 58px;
        }

        .date_current {
            margin-right: 50px;
        }

        .name_hajime {
            font-size: 18px;
            margin-top: 5px;
            float: right;
            margin-right: 20px;
            width: 100%;
        }

        .zip_hajime {
            margin-top: 5px;
            float: right;
            margin-right: 100px;
            width: 100%;

        }

        .info_hajime {
            float: left;
            width: 100%;
        }

        .address_hajime {
            margin-right: 23px;
            float: right;
            width: 100%;
        }

        .tel_fax_hajime {
            margin-right: 10px;
        }

        .msg_vendor {
            margin-left: 10px;
            width: 50%;
            float: left;
        }

        .right_hajime {
            float: right;
            width: 47%;
        }

        .box_one {
            width: 30%;
            float: right;
            height: 100px;
            border: 1px solid;
            text-align: center;
            line-height: 27px;
            border-right: 0;

        }

        .box_two {
            width: 30%;
            float: right;
            height: 100px;
            border: 1px solid;
            text-align: center;
            line-height: 27px;
            border-right: 0;
        }

        .box_three {
            width: 30%;
            float: right;
            height: 100px;
            border: 1px solid;
            text-align: center;
            line-height: 27px;
            margin-right: 10px;
        }

        .fix_top_content {
            float: left;
            margin-top: 50px;
        }

        .footer_pdf {
            border-top: 5px solid;
        }

    </style>
</head>

<body>
    <div id="page-wrap">
        <div id="identity">
            <div class="col-md-12 border-bottom">
                <div class="title_order">注&nbsp;&nbsp;文&nbsp;&nbsp;書</div>
            </div>
            <div class="col-md-12">
                <div class="col-md-6 info_vendor">
                    <div class="zip_vendor">〒{{ $order_detail->supplier->user_info->address->zip }}</div>
                    <div class="address_vendor">{{ $order_detail->supplier->user_info->address->address1 }}</div>
                    <div class="name_vendor">
                        {{ $order_detail->supplier->user_info->company_name }}&nbsp;&nbsp;&nbsp;&nbsp;御中</div>
                    <div class="sup_vendor">{{ $order_detail->supplier->representative }}&nbsp;&nbsp;&nbsp;&nbsp;様
                    </div>
                    <div class="fax_tel">
                        <div class="fax_vendor">&nbsp;&nbsp;Fax
                            &nbsp;&nbsp;{{ $order_detail->supplier->user_info->address->fax }}</div>
                        <div class="tel_vendor">Tel &nbsp;&nbsp;{{ $order_detail->supplier->user_info->address->tel }}
                        </div>
                    </div>

                </div>
                <div class="col-md-6 align_right">
                    <div class="date_current">発注日 &nbsp;&nbsp;&nbsp;&nbsp;<?php echo
                        $order_detail->send_date; ?></div>
                    <div class="code_send">発注書No &nbsp;&nbsp;&nbsp;&nbsp;<?php echo
                        $order_detail->code_send; ?></div>
                    <div class="info_hajime">
                        <span class="name_hajime">株式会社フォレスカイ&nbsp;&nbsp;</span> <br />
                        <span class="zip_hajime">〒358-0024&nbsp;&nbsp;&nbsp;</span> <br />
                        <span class="address_hajime">
                            埼玉県入間市久保稲荷4-6-4 &nbsp;&nbsp;<br />
                            ハイム粕谷1-103&nbsp;&nbsp;<br />
                        </span>
                        <span class="tel_fax_hajime">
                            TEL: 04-2963-1276 FAX不可
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="msg_vendor">
            毎度お引き立てを賜り厚く御礼申し上げます。 <br />
            下記部品を発注いたしますのでご手配方よろしくお願い申し上げます。
        </div>
        <div class="right_hajime">

            <div class="box_three">
                担当 <br />
                <span style="font-size: 13px"> {{ Auth::user()->username }}</span>
            </div>
            <div class="box_one">
                承認
            </div>
            <div class="box_two">
                承認
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="col-md-12 fix_top_content">
            <table id="items">
                <tr>
                    <th class="column_two">メーカー名</th>
                    <th class="column_three">型 式</th>
                    <th class="column_four">発注数量</th>
                    <th class="column_four">単位</th>
                    <th class="column_five">単価</th>
                    <th class="column_six">金額</th>
                    <th class="column_seven">DC</th>
                    <th class="column_eight">Rohs</th>
                    <th class="column_nine">納期</th>
                </tr>
                <tr class="item-row">
                    <td class="column_two">{{ $order_detail->maker }}</td>
                    <td class="column_three">{{ $order_detail->katashiki }}</td>
                    <td class="column_four">{{ $order_detail->ship_quantity }}</td>
                    <td class="column_four">{{ $order_detail->buy_unit }}</td>
                    <td class="column_five">
                        {{ format_number($order_detail->unit_buy_ship, $order_detail->order_header->type_money_buy) }}
                        {{ $type_money }}</td>
                    <td class="column_five">
                        {{ format_number($order_detail->price_ship, $order_detail->order_header->type_money_buy) }}
                        {{ $type_money }}</td>
                    <td class="column_seven">{{ $order_detail->dc }}</td>
                    <td class="column_eight">{{ $order_detail->quote_customer->rohs }}</td>
                    <td class="column_nine">{{ $order_detail->import_date_plan }}</td>
                </tr>
            </table>
            <br />
            <div class="note">
                <span>[備考] </span>
                <span>{{ $order_detail->refer_vendor }}</span>
            </div>
            <br /><br />
        </div>
    </div>
</body>

</html>

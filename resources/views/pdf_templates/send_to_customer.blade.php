<?php
ini_set('default_charset', 'UTF-8');
mb_internal_encoding('UTF-8');
header('Content-Type: text/html; charset=UTF-8');
date_default_timezone_set('Asia/Tokyo');
$type_money = $info_quote[0]->type_money_sell;
if ($type_money == 'JPY') {
$type_money = '￥';
} elseif ($type_money == 'USD') {
$type_money = '$';
} elseif ($type_money == 'EUR') {
$type_money = '€';
}
?>
<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
    <title>見積書</title>
    <style>
        /* ******************ESTIMATE PDF********************** */
        #page-wrap {
            width: 800px;
            margin: 0 auto;
            color: #3d4748;
        }

        textarea {
            border: 0;
            font: 14px Arial, Serif;
            overflow: hidden;
            resize: none;
        }

        table {
            border-collapse: collapse
        }

        table td,
        table th {
            padding: 2px 5px
        }

        #header {
            width: 100%;
            margin: 5px 0;
            background: #eee;
            text-align: center;
            color: black;
            font: bold 24px Arial, Sans-Serif;
            text-decoration: uppercase;
            letter-spacing: 20px;
            padding: 2px 0px;
        }

        #address {
            width: 250px;
            height: 150px;
            float: left;
        }

        #customer-title {
            font-size: 20px;
            font-weight: bold;
            float: left;
        }

        #meta {
            margin: 1px auto;
            width: 100%;
            font-weight: bold;
        }

        #meta td {
            text-align: right;
        }

        #meta td.meta-head {
            text-align: right
        }

        #meta td textarea {
            width: 100%;
            height: 20px;
            text-align: right;
        }

        #items {
            clear: both;
            width: 100%;
            margin: 10px 0 0 0;
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
        }

        #items textarea {
            width: 80px;
            height: 50px;
        }

        #items tr.item-row td {
            vertical-align: top;
            border-bottom: 0;
        }

        #items tr.item-comment td {
            border-top: 0;
            border-bottom: 1px solid #000;
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

        label {
            font-weight: bold
        }

        span {
            font-weight: bold;
            padding: 0 5px;
        }

        p {
            font-size: 20px;
            font-weight: bold;
            letter-spacing: 1px;
        }

        p span {
            padding: 0 8px
        }

        .t_small {
            font-size: 16px;
            padding-left: 10px;
        }

        .align_right p {
            min-width: 272px;
            text-align: left;
            float: right;
            margin-right: 20px;
        }

        .t_r {
            text-align: right !important
        }

        .margin-right {
            margin-right: 20px;
            min-width: 272px;
            float: right;
            text-align: left;
            width: auto;
        }

        .letter {
            letter-spacing: 3px
        }

        .content_nd {
            border-top: 2px solid #000;
            border-bottom: 2px solid #000;
            padding: 10px 5%;
            width: 90%;
        }

        .content_nd p {
            padding: 5px 0;
            font-size: 16px;
        }

        .padding-left {
            padding-left: 92px
        }

        .comment textarea {
            width: 100% !important;
            height: auto !important;
            min-height: 24px;
        }

        .col-md-12 h1 {
            text-align: center;
            font-weight: bold;
            font-size: 16px;
            border: 2px solid #000;
            padding: 5px 0;
            margin: 10px 0 0;
        }

        th.column_four {
            letter-spacing: 5px
        }

        th.column_five,
        th.column_six,
        th.column_seven,
        th.column_eight {
            letter-spacing: 5px
        }

        .column_three {
            text-align: center
        }

        .column_five,
        .column_six,
        .column_seven,
        .column_eight {
            text-align: center
        }

        .lbl_CompName {
            letter-spacing: 15px;
            font-size: 16px;
        }

        .lbl_NoteSupplier {
            font-size: 16px;
            padding-left: 10px;
        }

        .fix_clm {
            text-align: center;
            min-width: 100px;
        }

        .fix_right_result {
            text-align: right !important;
        }

        .fix_left_result {
            width: 80% !important;
        }

    </style>
</head>

<body>
    <div id="page-wrap">
        <div
            style="width:100%;font-size:15pt;font-weight:normal;font-style:bold;text-align:center;line-height:30px;margin:0 auto;border-bottom:2px solid #000;">
            御　見　積　書</div>
        <div id="identity" style="margin-top:-10px;">
            <div class="col-md-12">
                <div class="col-md-6 align_left">
                    <div class="col-xs-12">
                        <label style="font-size:10pt;font-weight:normal;font-style:normal;color:#3c3c3c;">〒</label>
                        <span style="font-size:10pt;font-weight:normal;font-style:normal;color:#3c3c3c;">
                            @php
                                $zip = str_split($info_quote[0]->customer->user_info->address->zip, 3);
                            @endphp
                            {{ $zip[0] }} - @if (isset($zip[1]))
                                {{ $zip[1] }} @endif @if (isset($zip[2]))
                                    {{ $zip[2] }} @endif
                        </span>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-xs-12" style="margin-top:0;">
                        <label style="font-size:10pt;font-weight:normal;font-style:normal;color:#3c3c3c;">
                            {{ $info_quote[0]->customer->user_info->address->address1 }} &nbsp;
                            {{ $info_quote[0]->customer->user_info->address->address2 }} &nbsp;
                            {{ $info_quote[0]->customer->user_info->address->address3 }} &nbsp;
                            {{ $info_quote[0]->customer->user_info->address->address4 }} &nbsp;
                        </label>
                        <span style="font-size:10pt;font-weight:normal;font-style:normal;color:#3c3c3c;">番地</span>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-xs-12 ">
                        <div style="text-align:right;border-bottom: 1px solid #000;">
                            <span
                                style="font-size:14pt;font-weight:normal;font-style:normal;color:#3c3c3c;">{{ $info_quote[0]->customer->user_info->company_name }}</span>
                            <span>&nbsp;&nbsp;&nbsp;</span>
                            <span style="font-size:12pt;font-weight:normal;font-style:normal;color:#3c3c3c;">御中</span>
                        </div>
                        <div style="text-align:right;border-bottom: 1px solid #000;">
                            <span
                                style="font-size:10pt;font-weight:normal;font-style:normal;color:#3c3c3c;">{{ $info_quote[0]->customer->user_info->address->part_name }}</span>
                            <span>&nbsp;&nbsp;&nbsp;</span>
                            <span
                                style="font-size:10pt;font-weight:normal;font-style:normal;color:#3c3c3c;">{{ $info_quote[0]->customer->user_info->representative }}</span>
                            <span>&nbsp;&nbsp;&nbsp;</span>
                            <span style="font-size:10pt;font-weight:normal;font-style:normal;color:#3c3c3c;">様</span>
                        </div>
                        <div style="float:left;width:100%;">
                            <div
                                style="float:left;width:30px;font-size:10pt;font-weight:normal;font-style:normal;color:#3c3c3c;">
                                FAX:</div>
                            <div
                                style="float:left;width:120px;font-size:10pt;font-weight:normal;font-style:normal;color:#3c3c3c;">
                                {{ format_phone($info_quote[0]->customer->user_info->address->fax) }}</div>
                            <div
                                style="float:right;width:110px;margin-right: 50px;font-size:10pt;font-weight:normal;font-style:normal;color:#3c3c3c;">
                                {{ format_phone($info_quote[0]->customer->user_info->address->tel) }}</div>
                            <div
                                style="float:right;width:30px;font-size:10pt;font-weight:normal;font-style:normal;color:#3c3c3c;">
                                TEL:</div>
                        </div> <br />
                        <div style="font-size:10pt;font-weight:normal;font-style:normal;color:#3c3c3c;">
                            毎度お引き立てを賜り誠に有難うございます。下記の通り御見積申し上げます。</div>
                        <div style="font-size:10pt;font-weight:normal;font-style:normal;color:#3c3c3c;">
                            商品在庫数は常に変動しております。ご発注の際は必ず再度ご確認ください。</div>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="col-md-6 align_right">
                    <div class="col-xs-12" style="">
                        <label style="font-size:10pt;font-weight:normal;font-style:normal;color:#3c3c3c;">請求日</label>
                        <span>&nbsp;</span>
                        <span
                            style="font-size:10pt;font-weight:normal;font-style:normal;color:#3c3c3c;">{{ $info_quote[0]->date_send }}</span>
                        <span>&nbsp;&nbsp;&nbsp;&nbsp;</span>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-xs-12" style="margin-top:0;">
                        <label style="font-size:10pt;font-weight:normal;font-style:normal;color:#3c3c3c;">見積書No</label>
                        <span>&nbsp;</span>
                        <span
                            style="font-size:10pt;font-weight:normal;font-style:normal;color:#3c3c3c;">{{ $info_quote[0]->rank_quote }}</span>
                        <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-xs-12 ">
                        <div style="font-size:14pt;font-weight:bold;font-style:normal;color:#3c3c3c;">株式会社 フォレスカイ</div>

                        <div>　
                            <label style="font-size:10pt;font-weight:normal;font-style:normal;color:#3c3c3c;">〒</label>
                            <span
                                style="font-size:10pt;font-weight:normal;font-style:normal;color:#3c3c3c;">358-0024</span>
                            <span>&nbsp;</span>
                            <span
                                style="font-size:10pt;font-weight:normal;font-style:normal;color:#3c3c3c;">埼玉県入間市久保稲荷4-6-4</span>
                            <br>
                            <span
                                style="font-size:10pt;font-weight:normal;font-style:normal;color:#3c3c3c;">ハイム粕谷1-103</span>
                        </div>
                        <div>
                            <em style="font-size:10pt;font-weight:normal;font-style:normal;color:#3c3c3c;">TEL:</em>
                            <span
                                style="font-size:10pt;font-weight:normal;font-style:normal;color:#3c3c3c;">04-2963-1276</span>
                            <span>&nbsp;</span>
                            <em style="font-size:10pt;font-weight:normal;font-style:normal;color:#3c3c3c;">FAX:</em>
                            <span
                                style="font-size:10pt;font-weight:normal;font-style:normal;color:#3c3c3c;">04-2963-1276</span>
                        </div>
                    </div>
                    <div class="clearfix"></div>

                    <div class="clearfix"></div>
                    <!--<div class="col-xs-12">
                            <label style="font-size:10pt;font-weight:normal;font-style:normal;color:#3c3c3c;">担当:</label>
                            <span style="font-size:10pt;font-weight:normal;font-style:normal;color:#3c3c3c;">吉沼</span>
                            <span>&nbsp;&nbsp;&nbsp;&nbsp;</span>
                        </div>-->
                </div>
            </div>
        </div>
        <div class="clearfix"></div>

        <div class="col-md-12">
            <table id="items">
                <tr>
                    <th class="column_one" style="font-size:10pt;font-weight:normal;font-style:normal;color:#3c3c3c;">

                    </th>
                    <th class="column_two" style="font-size:10pt;font-weight:normal;font-style:normal;color:#3c3c3c;">
                        ID
                    </th>
                    <th class="column_three" style="font-size:10pt;font-weight:normal;font-style:normal;color:#3c3c3c;">
                        メーカー
                    </th>
                    <th class="column_four" style="font-size:10pt;font-weight:normal;font-style:normal;color:#3c3c3c;">
                        型番
                    </th>
                    <th class="column_five" style="font-size:10pt;font-weight:normal;font-style:normal;color:#3c3c3c;">
                        数量
                    </th>
                    <th class="column_six" style="font-size:10pt;font-weight:normal;font-style:normal;color:#3c3c3c;">
                        単位
                    </th>
                    <th class="column_seven" style="font-size:10pt;font-weight:normal;font-style:normal;color:#3c3c3c;">
                        単価
                    </th>
                    <th class="column_eight" style="font-size:10pt;font-weight:normal;font-style:normal;color:#3c3c3c;">
                        金額
                    </th>
                    <th class="column_nine" style="font-size:10pt;font-weight:normal;font-style:normal;color:#3c3c3c;">
                        DC
                    </th>
                    <th class="column_ten" style="font-size:10pt;font-weight:normal;font-style:normal;color:#3c3c3c;">
                        Rosh
                    </th>
                    <th class="column_eleven"
                        style="font-size:10pt;font-weight:normal;font-style:normal;color:#3c3c3c;">
                        地域
                    </th>
                    <th class="column_twelve"
                        style="font-size:10pt;font-weight:normal;font-style:normal;color:#3c3c3c;">
                        納期
                    </th>
                </tr>
                @php
                    $money = 0;
                @endphp
                @foreach ($info_quote as $key => $item)
                    @php
                        $money += $item->money_sell;
                    @endphp

                    <tr class="item-row">
                        <td class="column_one"
                            style="font-size:10pt;font-weight:normal;font-style:normal;color:#3c3c3c;">
                            {{ $key + 1 }}
                        </td>
                        <td class="column_two"
                            style="font-size:10pt;font-weight:normal;font-style:normal;color:#3c3c3c;">
                            {{ $item->request_vendors->rfq_request_id }} -
                            {{ $item->request_vendors->rfq_request_child_id }}
                        </td>
                        <td class="column_three"
                            style="font-size:10pt;font-weight:normal;font-style:normal;color:#3c3c3c;">
                            {{ $item->maker }}
                        </td>
                        <td class="column_four"
                            style="text-align:center;font-size:10pt;font-weight:normal;font-style:normal;color:#3c3c3c;">
                            {{ $item->katashiki }}
                        </td>
                        <td class="column_five"
                            style="font-size:10pt;font-weight:normal;font-style:normal;color:#3c3c3c;">
                            {{ $item->sell_quantity }}
                        </td>
                        <td class="column_six"
                            style="font-size:10pt;font-weight:normal;font-style:normal;color:#3c3c3c;">
                            {{ $item->unit_sell }}
                        </td>
                        <td class="column_seven"
                            style="font-size:10pt;font-weight:normal;font-style:normal;color:#3c3c3c;">
                            {{ $type_money }} {{ $unit_price }}
                        </td>
                        <td class="column_eight"
                            style="font-size:10pt;font-weight:normal;font-style:normal;color:#3c3c3c;">
                            {{ $type_money }} {{ format_number($item->money_sell, $item->type_money_sell) }}
                        </td>
                        <td class="column_nine"
                            style="font-size:10pt;font-weight:normal;font-style:normal;color:#3c3c3c;">
                            {{ $item->dc }}
                        </td>
                        <td class="column_ten"
                            style="font-size:10pt;font-weight:normal;font-style:normal;color:#3c3c3c;">
                            {{ $item->rohs }}
                        </td>
                        <td class="column_eleven"
                            style="font-size:10pt;font-weight:normal;font-style:normal;color:#3c3c3c;">
                            {{ $item->kbn2 }}
                        </td>
                        <td class="column_twenlve"
                            style="text-align:center;font-size:10pt;font-weight:normal;font-style:normal;color:#3c3c3c;">
                            {{ $item->deadline_quote }}
                        </td>
                    </tr>
                    <tr class="item-comment">
                        <td class="column_one"></td>
                        <td colspan="11" class="comment"
                            style="font-size:10pt;font-weight:normal;font-style:normal;color:#3c3c3c;text-align:left;">
                            [備 考]: {{ $item->request_vendors->fee_ship2 }}
                        </td>
                    </tr>
                @endforeach

            </table>
        </div>
        <div class="clearfix"></div>
        <div id="customer">
            <table id="meta">
                <tr>
                    <td class="meta-head letter"
                        style="font-size:10pt;font-weight:normal;font-style:normal;color:#3c3c3c;width:70%;text-align:right;padding-right:50px;">
                        小 計
                    </td>
                    <td style="font-size:10pt;font-weight:normal;font-style:normal;color:#3c3c3c;text-align:right">
                        {{ $type_money }} {{ format_number($unit_price, $info_quote[0]->type_money_sell) }}
                    </td>
                </tr>
                @if ($info_quote[0]->type_money_sell == 'JPY')
                    <tr>
                        <td class="meta-head letter"
                            style="font-size:10pt;font-weight:normal;font-style:normal;color:#3c3c3c;width:70%;text-align:right;padding-right:50px;">
                            送料</td>
                        <td style="font-size:10pt;font-weight:normal;font-style:normal;color:#3c3c3c;text-align:right">
                            {{ $type_money }} {{ number_format($fee_ship) }}</td>
                    </tr>
                    @if ($fee_daibiki > 0)
                        <tr>
                            <td class="meta-head letter"
                                style="font-size:10pt;font-weight:normal;font-style:normal;color:#3c3c3c;width:70%;text-align:right;padding-right:50px;">
                                (後払い希望の方はご連絡ください。)代引手数料</td>
                            <td
                                style="font-size:10pt;font-weight:normal;font-style:normal;color:#3c3c3c;text-align:right">
                                {{ $type_money }} {{ number_format($fee_daibiki) }}</td>
                        </tr>
                    @endif
                @endif
                <tr>
                    <td class="meta-head"
                        style="font-size:10pt;font-weight:normal;font-style:normal;color:#3c3c3c;width:70%;text-align:right;padding-right:50px;">
                        消費税
                    </td>
                    <td style="font-size:10pt;font-weight:normal;font-style:normal;color:#3c3c3c;text-align:right">
                        @php
                            $rent = ($money + $fee_ship + $fee_daibiki) * $tax_value;
                        @endphp
                        {{ $type_money }} {{ format_number($rent, $info_quote[0]->type_money_sell) }}
                    </td>
                </tr>
                <tr>
                    <td class="meta-head"
                        style="font-size:10pt;font-weight:normal;font-style:normal;color:#3c3c3c;width:70%;text-align:right;padding-right:50px;">
                        合 計
                    </td>
                    <td style="font-size:10pt;font-weight:normal;font-style:normal;color:#3c3c3c;text-align:right">
                        @php
                            $total_money = $money + $rent + $fee_ship + $fee_daibiki;
                        @endphp
                        {{ $type_money }} {{ format_number($total_money, $info_quote[0]->type_money_sell) }}
                    </td>
                </tr>
            </table>
        </div>
    </div>
    <div style="position:fixed;bottom:0;width:100%">
        <span
            style="font-size:9pt;font-weight:normal;font-style:normal;color:#3c3c3c;">*市場在庫からの調達品は値段、在庫、納期はご注文受け取り後も変更になってしまう可能性
            があることをご容赦ください。*</span>
        <span
            style="font-size:9pt;font-weight:normal;font-style:normal;color:#3c3c3c;">**弊社お取引条件が適用されることをご了解ください。詳しい内容は下記リンクをご覧ください。**</span>
        <span
            style="font-size:8pt;font-weight:normal;font-style:normal;color:#3c3c3c;">http://www.showadenshisangyo.co.jp/joken.htm</span>
    </div>
</body>

</html>

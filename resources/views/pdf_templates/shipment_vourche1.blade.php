<?php
ini_set('default_charset', 'UTF-8');
mb_internal_encoding('UTF-8');
header('Content-Type: application/pdf; charset=UTF-8');
date_default_timezone_set('Asia/Tokyo');
if ($order_details[0]->order_header->type_money == 'JPY') {
$type_money = '円';
} elseif ($order_details[0]->order_header->type_money == 'USD') {
$type_money = '$';
} elseif ($order_details[0]->order_header->type_money == 'EUR') {
$type_money = '€';
} else {
$type_money = $order_details[0]->order_header->type_money;
}
?>
<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
    <style>
        * {
            margin: 0;
            padding: 0;
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
            margin: 5px 0 0;
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
            width: 300px;
            font-weight: bold;
        }

        #meta td {
            text-align: right;
            width: 50%;
            border-bottom: 1px solid #000;
        }

        #meta td.meta-head {
            text-align: center
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
            border-bottom: 1px dashed;
            text-align: center;
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

        label {
            font-weight: bold
        }

        span {
            font-weight: bold;
            padding: 0 5px;
        }

        p {
            font-size: 23px;
            font-weight: bold;
            letter-spacing: 1px;
        }

        p span {
            padding: 0 8px
        }

        .t_small {
            font-size: 13px;
            padding-left: 10px;
        }

        .t_small span {
            padding: 0 0 0 8px
        }

        .t_small_left {
            font-size: 13px;

        }

        .t_small_left em {
            font-style: normal
        }

        .t_small em {
            font-style: normal
        }

        .t_small_nd {
            font-size: 18px;
            font-weight: normal;
        }

        .align_right p {
            width: 300px;
            text-align: right;
            float: right;
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
            letter-spacing: 20px
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
            letter-spacing: 15px
        }

        th.column_five,
        th.column_six,
        th.column_seven,
        th.column_eight {
            letter-spacing: 10px
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

        .c_dau {
            position: absolute;
            top: 85px;
            left: 45%;
            z-index: -1
        }

        .c_dau img {
            width: 100px;
        }

        .c_dau_v {
            position: absolute;
            top: 104px;
            right: 10px;
            z-index: -1
        }

        .c_dau_v img {
            width: 100px;
        }

        .c_dau_t {
            position: absolute;
            top: 345px;
            right: 10px;
            z-index: -1
        }

        .c_dau_t img {
            width: 50px;
        }

        #identity {
            z-index: 999;
        }

        .fix_rows td {
            border-bottom: 1px solid !important;
        }

        .fix_border_top td {
            border-top: none !important;
            ;
        }

        .footer_pdf {
            border-top: 5px solid;
        }

    </style>
</head>

<body>
    <div id="page-wrap" style="color:#3c3c3c">
        <div
            style="font-size:15pt;font-weight:bold;font-style:normal;background:#ccc;width:300px;text-align:center;line-height:30px;margin:0 auto">
            納品書</div>
        <div id="identity" style="margin-top: 80px">
            <div class="col-md-12" style="margin-top:-80px;z-index:100">
                <div class="col-md-6 align_left">
                    <div class="col-xs-12 ">
                        <label
                            style="font-size:11pt;font-weight:normal;font-style:normal;color:#3c3c3c;font-family:Arial">〒</label>
                        <span
                            style="font-size:11pt;font-weight:normal;font-style:normal;color:#3c3c3c;font-family:Arial">{{ $send_address->zip }}</span>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-xs-12" style="margin-top:5px;">
                        <label
                            style="font-size:11pt;font-weight:normal;font-style:normal;color:#3c3c3c">{{ $send_address->address1 }}
                            県 {{ $send_address->address2 }}市</label>
                        <span
                            style="font-size:11pt;font-weight:normal;font-style:normal;color:#3c3c3c">{{ $send_address->address3 }}&nbsp;{{ $send_address->address4 }}</span>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-xs-12" style="margin-top:5px;">
                        <div style="font-size:12pt;font-weight:normal;">
                            <em style="font-size:11pt;font-weight:normal;font-style:normal;color:#3c3c3c">TEL:</em>
                            <span
                                style="font-size:11pt;font-weight:normal;font-style:normal;color:#3c3c3c">{{ $send_address->tel }}</span>
                            <em style="font-size:11pt;font-weight:normal;font-style:normal;color:#3c3c3c">FAX:</em>
                            <span
                                style="font-size:11pt;font-weight:normal;font-style:normal;color:#3c3c3c">{{ $send_address->fax }}</span>
                        </div>
                        <div style="font-size:16pt;font-weight:bold;color:#3c3c3c">
                            {{ $send_address->comp_type }}<span>御中</span></div>
                        <div
                            style="margin-top:5px;border-top:1px solid gray;padding-top: 25px;font-size:12pt;font-weight:normal;color:#3c3c3c">
                            下記の通り納品いたしました。</div>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="col-md-6 align_right">
                    <div class="col-xs-12" style="margin-top:-5px;">
                        <label style="font-size:12pt;font-weight:normal;font-style:normal;color:#3c3c3c"></label>
                        <span
                            style="font-size:12pt;font-weight:normal;font-style:normal;color:#3c3c3c">{{ date_create()->format('Y-m-d') }}</span>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-xs-12" style="margin-top:2px;">
                        <label style="font-size:12pt;font-weight:normal;font-style:normal;color:#3c3c3c">伝票No.</label>
                        <span style="font-size:12pt;font-weight:normal;font-style:normal;color:#3c3c3c">
                            {{ $order_details[0]->order_header->rank_quote }}
                        </span>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-xs-12" style="margin-top:5px;">
                        <div style="font-size:16pt;font-weight:bold;z-index:999;color:#3c3c3c;margin-right:20px;">株式会社
                            フォレスカイ</div>
                        <div
                            style="font-size:10pt;font-weight:normal;font-style:normal;z-index:999;text-align:left;margin-left:100px;;color:#3c3c3c">
                            〒<span
                                style="font-size:10pt;font-weight:normal;font-style:normal;color:#3c3c3c">358-0024</span>
                        </div>
                        <div
                            style="font-size:14pt;font-weight:normal;font-style:normal;color:#3c3c3c;margin-right:20px;">
                            埼玉県入間市久保稲荷4-6-4 <br>ハイム粕谷1-103</div>
                        <div style="text-align:left;margin-left:100px;">
                            <em style="font-size:8pt;font-weight:normal;font-style:normal;color:#3c3c3c">TEL:</em>
                            <span
                                style="font-size:8pt;font-weight:normal;font-style:normal;color:#3c3c3c">04-2963-1276</span>
                            <em style="font-size:8pt;font-weight:normal;font-style:normal;color:#3c3c3c">FAX:</em>
                            <span
                                style="font-size:8pt;font-weight:normal;font-style:normal;color:#3c3c3c">04-2963-1276</span>
                        </div>
                    </div>
                    <div class="clearfix"></div>

                </div>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="col-md-12">
            <table id="items">
                <tr>
                    <th class="column_one" style="font-size:10pt;font-weight:normal;color:black"></th>
                    <th class="column_two" style="font-size:10pt;font-weight:normal;color:black">注文番号</th>
                    <th class="column_three" style="font-size:10pt;font-weight:normal;color:black">品種</th>
                    <th class="column_four" style="font-size:10pt;font-weight:normal;color:black">型式</th>
                    <th class="column_five" style="font-size:10pt;font-weight:normal;color:black">数量</th>
                    <th class="column_six" style="font-size:10pt;font-weight:normal;color:black">単位</th>
                    <th class="column_seven" style="font-size:10pt;font-weight:normal;color:black">単価</th>
                    <th class="column_eight" style="font-size:10pt;font-weight:normal;color:black">金額</th>
                </tr>
                @php
                    $total_money = 0;
                    $fee_daibiki = 0;
                @endphp
                @foreach ($order_details as $key => $item)
                    @php
                        $total_money += $item->sale_money;
                        $class_bot = '';
                        $class_top = 'fix_border_top';
                        
                        if ($key == 0) {
                            $class_top = '';
                        }
                        if ($key < count($order_details) - 1) {
                            $class_bot = 'fix_rows';
                        }
                    @endphp

                    <tr class="item-row {{ $class_top }}">
                        @if ($key == 0)
                            <td class="column_one"
                                rowspan="<?php echo count($order_details) * 2; ?>"
                                style="vertical-align: middle;border-right:1px solid;font-size:10pt;font-weight:normal;">
                                1</td>
                        @endif
                        <td class="column_two" style="font-size:10pt;font-weight:normal;">
                            {{ $item->order_header->order_no_by_customer }}</td>
                        <td class="column_three" style="font-size:10pt;font-weight:normal;"></td>
                        <td class="column_four" style="text-align:center;font-size:10pt;font-weight:normal;">
                            {{ $item->katashiki }}</td>
                        <td class="column_five" style="font-size:10pt;font-weight:normal;">{{ $item->sale_qty }}</td>
                        <td class="column_six" style="font-size:10pt;font-weight:normal;">{{ $item->sale_unit }}</td>
                        <td class="column_seven" style="font-size:10pt;font-weight:normal;">
                            {{ format_number($item->sale_cost, $item->order_header->type_money) }}</td>
                        <td class="column_eight" style="font-size:10pt;font-weight:normal;">
                            {{ format_number($item->sale_money, $item->order_header->type_money) }}
                            {{ $type_money }}</td>
                    </tr>
                    <tr class="item-comment {{ $class_bot }}">
                        <td colspan="7" class="comment" style="font-size:10pt;font-weight:normal;">[備考]
                            {{ $item->quote_customer->request_vendors->fee_ship2 }}</td>
                    </tr>
                @endforeach
                <tr class="item-row">
                    <td class="column_one" rowspan="2"
                        style="vertical-align: middle;border-right:1px solid;font-size:10pt;font-weight:normal;">2</td>
                    <td class="column_two" style="font-size:10pt;font-weight:normal;">送料</td>
                    <td class="column_three" style="font-size:10pt;font-weight:normal;"><?php
                        //echo $item->idQuote->rank_quote
                        ?></td>
                    <td class="column_four" style="text-align:center;font-size:10pt;font-weight:normal;">送料</td>
                    <td class="column_five" style="font-size:10pt;font-weight:normal;">1</td>
                    <td class="column_six" style="font-size:10pt;font-weight:normal;">式</td>
                    <td class="column_seven" style="font-size:10pt;font-weight:normal;">
                        {{ format_number($order_details[0]->order_header->fee_shipping, $order_details[0]->order_header->type_money) }}
                    </td>
                    <td class="column_eight" style="font-size:10pt;font-weight:normal;">
                        {{ format_number($order_details[0]->order_header->fee_shipping, $order_details[0]->order_header->type_money) }}
                        {{ $type_money }}</td>
                </tr>
                <tr class="item-comment">
                    <td colspan="7" class="comment" style="font-size:10pt;font-weight:normal;">[備考]</td>
                </tr>
                <tr class="item-row">
                    <td class="column_one" rowspan="2"
                        style="vertical-align: middle;border-right:1px solid;font-size:10pt;font-weight:normal;">3</td>
                    <td class="column_two" style="font-size:10pt;font-weight:normal;">代引き手数料</td>
                    <td class="column_three" style="font-size:10pt;font-weight:normal;"><?php
                        //echo $item->idQuote->rank_quote
                        ?></td>
                    <td class="column_four" style="text-align:center;font-size:10pt;font-weight:normal;">代引き手数料</td>
                    <td class="column_five" style="font-size:10pt;font-weight:normal;">1</td>
                    <td class="column_six" style="font-size:10pt;font-weight:normal;">式</td>
                    <td class="column_seven" style="font-size:10pt;font-weight:normal;">
                        {{ format_number($order_details[0]->order_header->fee_daibiki, $order_details[0]->order_header->type_money) }}
                    </td>
                    <td class="column_eight" style="font-size:10pt;font-weight:normal;">
                        {{ format_number($order_details[0]->order_header->fee_daibiki, $order_details[0]->order_header->type_money) }}
                        {{ $type_money }}</td>
                </tr>
                <tr class="item-comment">
                    <td colspan="7" class="comment" style="font-size:10pt;font-weight:normal;">[備考]</td>
                </tr>
            </table>
        </div>
        <div class="footer_pdf">担当 : {{ Auth::user()->name }}</div>
    </div>
</body>

</html>

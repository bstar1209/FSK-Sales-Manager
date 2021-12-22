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

        #page-wrap {}

        table {
            border-collapse: collapse
        }

        table td,
        table th {
            padding: 2px 5px;
            color: black;
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

        #customer {}

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

    </style>
</head>

<body>
    <div id="page-wrap" style="color:#3c3c3c">
        <div
            style="font-size:15pt;font-weight:normal;font-style:normal;background:#ccc;width:300px;text-align:center;line-height:30px;margin:0 auto">
            請求書</div>
        <div id="identity">
            <div class="col-md-12" style="margin-top:10px; z-index:100">
                <img src="{{ $image_url }}"
                    style="margin-top:30px;z-index:-1;width:100px;height:50px;margin-left:300px" />
                <!--<img src="../../images/dau.png" style="margin-top:30px;z-index:-1;width:100px;height:50px;margin-left:300px" />-->
            </div>
            <div class="col-md-6 align_left">
                <div class="col-xs-12 ">
                    <label
                        style="font-size:11px;font-weight:normal;font-style:normal;color:#3c3c3c;font-family:Arial">〒</label>
                    <span
                        style="font-size:11px;font-weight:normal;font-style:normal;color:#3c3c3c;font-family:Arial">{{ $orders->order_details[0]->quote_customer->customer->user_info->address->zip }}</span>
                </div>
                <div class="clearfix"></div>
                <div class="col-xs-12" style="margin-top:-5px;">
                    <label
                        style="font-size:11px;font-weight:normal;font-style:normal;color:#3c3c3c">{{ $orders->order_details[0]->quote_customer->customer->user_info->address->address1 }}県{{ $orders->order_details[0]->quote_customer->customer->user_info->address->address2 }}市</label>
                    <span
                        style="font-size:11px;font-weight:normal;font-style:normal;color:#3c3c3c">{{ $orders->order_details[0]->quote_customer->customer->user_info->address->address3 }}
                        &nbsp;{{ $orders->order_details[0]->quote_customer->customer->user_info->address->address4 }}</span>
                </div>
                <div class="clearfix"></div>
                <div class="col-xs-12" style="margin-top:10px;">
                    <div style="font-size:12pt;font-weight:normal;">
                        <em style="font-size:11px;font-weight:normal;font-style:normal;color:#3c3c3c">TEL:</em>
                        <span
                            style="font-size:11px;font-weight:normal;font-style:normal;color:#3c3c3c">{{ $orders->order_details[0]->quote_customer->customer->user_info->address->tel }}県</span>
                        <em style="font-size:11px;font-weight:normal;font-style:normal;color:#3c3c3c">FAX:</em>
                        <span
                            style="font-size:11px;font-weight:normal;font-style:normal;color:#3c3c3c">{{ $orders->order_details[0]->quote_customer->customer->user_info->address->fax }}</span>
                    </div>
                    <div style="font-size:16pt;font-weight:bold;color:#3c3c3c">
                        {{ $orders->order_details[0]->quote_customer->customer->user_info->company_name }}<span>御中</span>
                    </div>
                    <div style="font-size:12pt;font-weight:normal;color:#3c3c3c">毎度お引き立てを賜り誠に有難うございます。</div>
                    <div style="font-size:12pt;font-weight:normal;color:#3c3c3c">下記の通りご請求申し上げます。</div>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="col-md-6 align_right">
                <div class="col-xs-12" style="margin-top:-5px;">
                    <label style="font-size:12pt;font-weight:normal;font-style:normal;color:#3c3c3c">請求日</label>
                    <span style="font-size:12pt;font-weight:normal;font-style:normal;color:#3c3c3c">
                        @if ($orders->date_invoice != '')
                            {{ $orders->date_invoice }}
                        @else
                            {{ date_create()->format('Y-m-d') }}
                        @endif
                    </span>
                </div>
                <div class="clearfix"></div>
                <div class="col-xs-12" style="margin-top:2px;">
                    <label style="font-size:12pt;font-weight:normal;font-style:normal;color:#3c3c3c">請求No.</label>
                    <span style="font-size:12pt;font-weight:normal;font-style:normal;color:#3c3c3c">
                        @if ($orders->code_invoice != '')
                            {{ $orders->code_invoice }}
                        @else
                            @php
                                $numzero = 4 - strlen($orders->id);
                                $codeRank = '';
                                for ($i = 1; $i <= $numzero; $i++) {
                                    $codeRank .= '0';
                                }
                                $code_invoice = date_create()->format('Ymd') . '-' . $codeRank . $orders->id;
                            @endphp
                            {{ $code_invoice }}
                        @endif
                    </span>
                </div>
                <div class="clearfix"></div>
                <div class="col-xs-12" style="margin-top:5px;">
                    <div style="font-size:16pt;font-weight:bold;z-index:999;color:#3c3c3c;margin-right:20px;">株式会社
                        フォレスカイ</div>
                    <div>
                        <img src="{{ $image_url2 }}"
                            style="margin-top:-30px;z-index:-10;width:100px;height:100px;" />
                        <div>
                            <div
                                style="font-size:10pt;font-weight:normal;font-style:normal;z-index:999;margin-top:-60px;text-align:left;margin-left:100px;;color:#3c3c3c">
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
                        <div class="clearfix"></div>
                        <div class="col-xs-12">
                            <label style="font-size:14px;font-weight:normal;font-style:normal;color:#3c3c3c">担当:</label>
                            <span
                                style="font-size:14px;font-weight:normal;font-style:normal;color:#3c3c3c">{{ $orders->order_details[0]->quote_customer->customer->representative }}</span>
                        </div>
                    </div>
                    <div id="customer" style="margin-top: 20px">
                        <table id="meta" style="margin-top:-30px;">
                            <tr>
                                <td class="meta-head letter">合計</td>
                                @php
                                    $total_not_tax = $orders->fee_shipping + $orders->fee_daibiki;
                                    foreach ($orders->order_details as $item) {
                                        $total_not_tax += $item->sale_money;
                                    }
                                    $tax = $total_not_tax * $orders->tax_info->tax;
                                @endphp
                                <td>{{ format_number($total_not_tax, $orders->type_money) }}
                                    {{ $orders->type_money }}</td>
                            </tr>
                            <tr>
                                <td class="meta-head">消費税<span>( {{ $orders->tax_info->tax }} %)</span></td>
                                <td>{{ format_number($tax, $orders->type_money) }} {{ $orders->type_money }}</td>
                            </tr>
                            <tr>
                                <td class="meta-head">税込合計</td>
                                <td>
                                    <div class="due">{{ format_number($tax + $total_not_tax, $orders->type_money) }}
                                        {{ $orders->type_money }}</div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="col-md-12 content_nd">
                <div style="font-size:12pt;font-weight:normal;z-index:99">
                    <em style="font-size:12pt;font-weight:normal;font-style:normal;color:#3c3c3c">※ 振込先</em>
                    <span>&nbsp;&nbsp;</span>
                    <span style="font-size:12pt;font-weight:normal;font-style:normal;color:#3c3c3c;">ジャンポンネット銀行</span>
                    <span>&nbsp;</span>
                    <span style="font-size:12pt;font-weight:normal;font-style:normal;color:#3c3c3c;">すずめ支店</span>
                    <span>&nbsp;</span>
                    <span style="font-size:12pt;font-weight:normal;font-style:normal;color:#3c3c3c;">普通口座</span>
                    <span>&nbsp;</span>
                    <span style="font-size:12pt;font-weight:normal;font-style:normal;color:#3c3c3c;">８５３４８０７</span>
                </div>
                <div
                    style="font-size:12pt;font-weight:normal;font-style:normal;color:#3c3c3c;margin-left:80px; margin-top: 10px">
                    <span style="font-size:12pt;font-weight:bold;font-style:normal;color:#3c3c3c;">銀行名義</span>
                    <span>&nbsp;&nbsp;&nbsp;</span>
                    <span>（株）フォレスカイ</span>
                </div>
                <div style="font-size:12pt;font-weight:normal;z-index:99;margin-top:10px;color:#3c3c3c;">※
                    振込手数料はお客様のご負担でお願いいたします。</div>
            </div>
            <div class="clearfix"></div>
            <div class="col-md-12">
                <h1>請求内訳</h1>
                <table id="items">
                    <tr>
                        <td class="column_one" style="font-size:10pt;font-weight:normal; width: 12.5%; color:#3c3c3c;">
                        </td>
                        <td class="column_two" style="font-size:10pt;font-weight:normal; width: 12.5%; color:#3c3c3c;">
                            注文番号</td>
                        <td class="column_three"
                            style="font-size:10pt;font-weight:normal; width: 12.5%; color:#3c3c3c;">伝票No.</td>
                        <td class="column_four" style="font-size:10pt;font-weight:normal; width: 12.5%; color:#3c3c3c;">
                            型式</td>
                        <td class="column_five" style="font-size:10pt;font-weight:normal; width: 12.5%; color:#3c3c3c;">
                            数量</td>
                        <td class="column_six" style="font-size:10pt;font-weight:normal; width: 12.5%; color:#3c3c3c;">
                            単位</td>
                        <td class="column_seven"
                            style="font-size:10pt;font-weight:normal; width: 12.5%; color:#3c3c3c;">単価</td>
                        <td class="column_eight"
                            style="font-size:10pt;font-weight:normal; width: 12.5%; color:#3c3c3c;">金額</td>
                    </tr>
                    @php
                        $ship_no = rand(1000, 9000);
                    @endphp
                    @foreach ($orders->order_details as $key => $item)
                        @php
                            if ($key == 0) {
                                $class_top = '';
                            }
                            if ($key < count($orders->order_details) - 1) {
                                $class_bot = 'fix_rows';
                            } else {
                                $class_bot = null;
                            }
                        @endphp

                        <tr class="item-row {{ $class_top }}">
                            @if ($key == 0)
                                <td class="column_one" rowspan="{{ count($orders->order_details) * 2 }}"
                                    style="vertical-align: middle;border-right:1px solid;font-size:10pt;font-weight:normal;">
                                    1</td>
                            @endif
                            <td class="column_two" style="font-size:10pt;font-weight:normal;">
                                {{ $item->order_no_by_customer }}</td>
                            <td class="column_three" style="font-size:10pt;font-weight:normal;">
                                {{ $item->quote_customer->rank_quote }}</td>
                            <td class="column_four" style="text-align:center;font-size:10pt;font-weight:normal;">
                                {{ $item->katashiki }} </td>
                            <td class="column_five" style="font-size:10pt;font-weight:normal;">{{ $item->sale_qty }}
                            </td>
                            <td class="column_six" style="font-size:10pt;font-weight:normal;">{{ $item->sale_qty }}
                            </td>
                            <td class="column_seven" style="font-size:10pt;font-weight:normal;">
                                {{ format_number($item->sale_cost, $orders->type_money) }}</td>
                            <td class="column_eight" style="font-size:10pt;font-weight:normal;">
                                {{ format_number($item->sale_money, $orders->type_money) }}</td>
                        </tr>
                        <tr class="item-comment {{ $class_bot }}">
                            <td colspan="7" class="comment" style="font-size:10pt;font-weight:normal;">[備考]
                                {{ $item->refer_order }}</td>
                        </tr>
                    @endforeach
                    <tr class="item-row">
                        <td class="column_one" rowspan="2"
                            style="vertical-align: middle;border-right:1px solid;font-size:10pt;font-weight:normal;">2
                        </td>
                        <td class="column_two" style="font-size:10pt;font-weight:normal;">送料</td>
                        <td class="column_three" style="font-size:10pt;font-weight:normal;">
                            {{ $item->quote_customer->rank_quote }}</td>
                        <td class="column_four" style="text-align:center;font-size:10pt;font-weight:normal;">送料</td>
                        <td class="column_five" style="font-size:10pt;font-weight:normal;">1</td>
                        <td class="column_six" style="font-size:10pt;font-weight:normal;">式</td>
                        <td class="column_seven" style="font-size:10pt;font-weight:normal;">
                            {{ number_format($orders->fee_shipping) }}</td>
                        <td class="column_eight" style="font-size:10pt;font-weight:normal;">
                            {{ number_format($orders->fee_shipping) }}&nbsp;{{ $orders->type_money }}</td>
                    </tr>
                    <tr class="item-comment">
                        <td colspan="7" class="comment" style="font-size:10pt;font-weight:normal;">[備考]</td>
                    </tr>
                    <tr class="item-row">
                        <td class="column_one" rowspan="2"
                            style="vertical-align: middle;border-right:1px solid;font-size:10pt;font-weight:normal;">3
                        </td>
                        <td class="column_two" style="font-size:10pt;font-weight:normal;">代引き手数料</td>
                        <td class="column_three" style="font-size:10pt;font-weight:normal;">
                            {{ $item->quote_customer->rank_quote }}</td>
                        <td class="column_four" style="text-align:center;font-size:10pt;font-weight:normal;">代引き手数料</td>
                        <td class="column_five" style="font-size:10pt;font-weight:normal;">1</td>
                        <td class="column_six" style="font-size:10pt;font-weight:normal;">式</td>
                        <td class="column_seven" style="font-size:10pt;font-weight:normal;">
                            {{ number_format($orders->fee_fee_daibiki) }}</td>
                        <td class="column_eight" style="font-size:10pt;font-weight:normal;">
                            {{ number_format($orders->fee_fee_daibiki) }}&nbsp;{{ $orders->type_money }}</td>
                    </tr>
                    <tr class="item-comment">
                        <td colspan="7" class="comment" style="font-size:10pt;font-weight:normal;">[備考]</td>
                    </tr>
                </table>
            </div>
        </div>
</body>

</html>

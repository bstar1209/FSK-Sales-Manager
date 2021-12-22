@php
if ($import_info->order_header->type_money == 'JPY') {
    $type_money = '円';
} elseif ($import_info->order_header->type_money == 'USD') {
    $type_money = '$';
} elseif ($import_info->order_header->type_money == 'EUR') {
    $type_money = '€';
} else {
    $type_money = $import_info->order_header->type_money;
}
@endphp
<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
    <!--<title>Editable Invoice</title>-->
    {{-- <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->baseUrl; ?>/css/style_deliveryPDF.css"> --}}
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

        .footer_pdf {
            border-top: 5px solid;
        }

    </style>
</head>

<body>
    <div id="page-wrap" style="color:#3c3c3c">
        <div
            style="font-size:15pt;font-weight:normal;font-style:normal;background:#ccc;width:300px;text-align:center;line-height:30px;margin:0 auto">
            請求書</div>
        <div id="identity">
            <div class="col-md-12" style="margin-top:20px;z-index:100">
                <div class="col-md-6 align_left">
                    <div class="col-xs-12 ">
                        <label
                            style="font-size:11pt;font-weight:normal;font-style:normal;color:#3c3c3c;font-family:Arial">〒</label>
                        <span
                            style="font-size:11pt;font-weight:normal;font-style:normal;color:#3c3c3c;font-family:Arial"><?php echo $import_info->order_detail->send_address->zip; ?></span>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-xs-12" style="margin-top:5px;">
                        <label style="font-size:11pt;font-weight:normal;font-style:normal;color:#3c3c3c"><?php echo $import_info->order_detail->send_address->address1; ?>県<?php echo $import_info->order_detail->send_address->address2; ?>市</label>
                        <span style="font-size:11pt;font-weight:normal;font-style:normal;color:#3c3c3c"><?php echo $import_info->order_detail->send_address->address3;
                            //.' '.$import_info->order_detail->send_address->address4
                            ?></span>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-xs-12" style="margin-top:5px;">
                        <div style="font-size:12pt;font-weight:normal;">
                            <em style="font-size:11pt;font-weight:normal;font-style:normal;color:#3c3c3c">TEL:</em>
                            <span style="font-size:11pt;font-weight:normal;font-style:normal;color:#3c3c3c"><?php echo $import_info->order_detail->send_address->tel; ?></span>
                            <em style="font-size:11pt;font-weight:normal;font-style:normal;color:#3c3c3c">FAX:</em>
                            <span style="font-size:11pt;font-weight:normal;font-style:normal;color:#3c3c3c"><?php echo $import_info->order_detail->send_address->fax; ?></span>
                        </div>
                        <div style="font-size:16pt;font-weight:bold;color:#3c3c3c"><?php echo
                            $import_info->order_detail->send_address->comp_type; ?><span>御中</span></div>
                        <div
                            style="margin-top:5px;border-top:1px solid gray;padding-top: 25px;font-size:12pt;font-weight:normal;color:#3c3c3c">
                            毎度お引き立てを賜り誠に有難うございます。 <br />
                            下記の通りご請求申し上げます。
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="col-md-6 align_right">
                    <div class="col-xs-12" style="margin-top:-5px;">
                        <label style="font-size:12pt;font-weight:normal;font-style:normal;color:#3c3c3c">請求日</label>
                        <span style="font-size:12pt;font-weight:normal;font-style:normal;color:#3c3c3c"><?php echo $import_info->export_date;
                            //$infoOrder->idOrder0->date_invoice;
                            ?></span>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-xs-12" style="margin-top:2px;">
                        <label style="font-size:12pt;font-weight:normal;font-style:normal;color:#3c3c3c">請求No.</label>
                        <span style="font-size:12pt;font-weight:normal;font-style:normal;color:#3c3c3c">
                            <?php
                            $numzero = 4 - strlen($import_info->id);
                            $codeRank = '';
                            for ($i = 1; $i <= $numzero; $i++) { $codeRank .='0' ; } $code_invoice=date('Ymd',
                                strtotime($import_info->export_date)) . '-' . $codeRank . $import_info->id;
                                ?>
                        </span>
                        {{ $import_info->order_header->code_invoice }}
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
                    <div class="clearfix"></div>
                    <!--<div class="col-xs-12">
                    <label style="font-size:14px;font-weight:normal;font-style:normal;color:#3c3c3c">担当:</label>
                    <span style="font-size:14px;font-weight:normal;font-style:normal;color:#3c3c3c"><?php
/*echo Yii::app()->user->username */
?></span>
                </div>-->
                </div>
                <div id="customer">
                    <table id="meta" style="margin-top:10px;">
                        <tr>
                            <td class="meta-head letter">合計</td>
                            <?php
                            $total_not_tax = 0;
                            $total_not_tax += $import_info->order_detail->sale_money;

                            $total_not_tax += $import_info->order_header->fee_shipping +
                            $import_info->order_header->fee_daibiki;
                            $tax = $total_not_tax * $import_info->order_header->tax_info->tax;
                            ?>
                            <td>{{ format_number($total_not_tax, $import_info->order_header->type_money, true) }}</td>
                        </tr>
                        <tr>
                            <td class="meta-head">消費税<span>(<?php echo
                                    $import_info->order_header->tax_info->tax * 100; ?>%)</span></td>
                            <td>{{ format_number($tax, $import_info->order_header->type_money, true) }}</td>
                        </tr>
                        <tr>
                            <td class="meta-head">税込合計</td>
                            <td>
                                <div class="due">
                                    {{ format_number($total_not_tax + $tax, $import_info->order_header->type_money, true) }}
                                </div>
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
            <div style="font-size:12pt;font-weight:normal;font-style:normal;color:#3c3c3c;margin-left:80px;">
                <span style="font-size:12pt;font-weight:bold;font-style:normal;color:#3c3c3c;">銀行名義</span>
                <span>&nbsp;&nbsp;&nbsp;</span>
                <span>（株）フォレスカイ</span>
            </div>
            <div>
                <!-- <img src="<?php
/*echo Yii::app()->baseUrl */
?>/images/dau3.png" style="margin-top:-30px;z-index:-1;width:50px;height:50px;float:right" />-->
                <!-- <img src="/var/www/html/FSKSaleManager/images/dau3.jpg" style="margin-top:-30px;z-index:-1;width:50px;height:50px;float:right" />-->
            </div>
            <div style="font-size:12pt;font-weight:normal;z-index:99;margin-top:0px;color:#3c3c3c;">※
                振込手数料はお客様のご負担でお願いいたします。</div>
        </div>
        <div class="clearfix"></div>
        <div class="col-md-12">
            <h1>請求内訳</h1>
            <table id="items">
                <tr>
                    <th class="column_one" style="font-size:10pt;font-weight:normal;"></th>
                    <th class="column_two" style="font-size:10pt;font-weight:normal;">注文番号</th>
                    <th class="column_three" style="font-size:10pt;font-weight:normal;">伝票No.</th>
                    <th class="column_four" style="font-size:10pt;font-weight:normal;">型式</th>
                    <th class="column_five" style="font-size:10pt;font-weight:normal;">数量</th>
                    <th class="column_six" style="font-size:10pt;font-weight:normal;">単位</th>
                    <th class="column_seven" style="font-size:10pt;font-weight:normal;">単価</th>
                    <th class="column_eight" style="font-size:10pt;font-weight:normal;">金額</th>
                </tr>
                <?php
                $classBot = '';
                $classTop = 'fix_border_top';
                ?>

                <tr class="item-row">
                    <td class="column_one" rowspan="2"
                        style="vertical-align: middle;border-right:1px solid;font-size:10pt;font-weight:normal;">1</td>
                    <td class="column_two" style="font-size:10pt;font-weight:normal;"><?php echo
                        $import_info->order_detail->order_no_by_customer; ?></td>
                    <td class="column_three" style="font-size:10pt;font-weight:normal;"><?php echo
                        $import_info->quote_customer->rank_quote; ?></td>
                    <td class="column_four" style="text-align:center;font-size:10pt;font-weight:normal;"><?php echo $import_info->order_detail->katasiki; ?></td>
                    <td class="column_five" style="font-size:10pt;font-weight:normal;"><?php echo
                        $import_info->order_detail->sale_qty; ?></td>
                    <td class="column_six" style="font-size:10pt;font-weight:normal;"><?php echo
                        $import_info->quote_customer->unit_sell; ?></td>
                    <td class="column_seven" style="font-size:10pt;font-weight:normal;"><?php echo
                        format_number($import_info->order_detail->sale_cost, $import_info->order_header->type_money);
                        ?></td>
                    <td class="column_eight" style="font-size:10pt;font-weight:normal;"><?php echo
                        format_number($import_info->order_detail->sale_money, $import_info->order_header->type_money,
                        true); ?></td>
                </tr>
                <tr class="item-comment fix_rows">
                    <td colspan="7" class="comment" style="font-size:10pt;font-weight:normal;">[備考] <?php
                        echo $import_info->order_detail->quote_customer->request_vendors->fee_ship2; ?>
                    </td>
                </tr>

                <tr class="item-row">
                    <td class="column_one" rowspan="2"
                        style="vertical-align: middle;border-right:1px solid;font-size:10pt;font-weight:normal;">2</td>
                    <td class="column_two" style="font-size:10pt;font-weight:normal;">送料</td>
                    <td class="column_three" style="font-size:10pt;font-weight:normal;"><?php echo
                        $import_info->order_header->rank_quote; ?></td>
                    <td class="column_four" style="text-align:center;font-size:10pt;font-weight:normal;">送料</td>
                    <td class="column_five" style="font-size:10pt;font-weight:normal;">1</td>
                    <td class="column_six" style="font-size:10pt;font-weight:normal;">式</td>
                    <td class="column_seven" style="font-size:10pt;font-weight:normal;"><?php echo
                        number_format($import_info->order_header->fee_shipping); ?></td>
                    <td class="column_eight" style="font-size:10pt;font-weight:normal;">
                        <?php echo format_number($import_info->order_header->fee_shipping,
                        $import_info->order_detail->type_money, true); ?></td>
                </tr>
                <tr class="item-comment">
                    <td colspan="7" class="comment" style="font-size:10pt;font-weight:normal;">[備考]</td>
                </tr>
                <tr class="item-row">
                    <td class="column_one" rowspan="2"
                        style="vertical-align: middle;border-right:1px solid;font-size:10pt;font-weight:normal;">3</td>
                    <td class="column_two" style="font-size:10pt;font-weight:normal;">代引き手数料</td>
                    <td class="column_three" style="font-size:10pt;font-weight:normal;"><?php echo
                        $import_info->order_header->rank_quote; ?></td>
                    <td class="column_four" style="text-align:center;font-size:10pt;font-weight:normal;">代引き手数料</td>
                    <td class="column_five" style="font-size:10pt;font-weight:normal;">1</td>
                    <td class="column_six" style="font-size:10pt;font-weight:normal;">式</td>
                    <td class="column_seven" style="font-size:10pt;font-weight:normal;"><?php echo
                        number_format($import_info->order_header->fee_daibiki); ?></td>
                    <td class="column_eight" style="font-size:10pt;font-weight:normal;">
                        <?php echo format_number($import_info->order_header->fee_daibiki,
                        $import_info->order_header->type_money, true); ?></td>
                </tr>
                <tr class="item-comment">
                    <td colspan="7" class="comment" style="font-size:10pt;font-weight:normal;">[備考]</td>
                </tr>
            </table>
        </div>
    </div>
</body>

</html>

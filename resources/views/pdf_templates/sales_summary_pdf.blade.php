<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
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

        .text-center {
            text-align: center !important;
        }

        .mt-20 {
            margin-top: 20px !important;
        }

        .fixed-bottom {
            position: fixed;
            right: 0;
            bottom: 0;
            left: 0;
            z-index: 1030;
            text-align: center !important;
        }

    </style>
</head>

<body>
    <div id="page-wrap" style="position: relative;">
        <div class="col-md-12">
            @if (isset($flag) && $flag == 'detail')
                <h5 class="mt-20">
                    @if ($type == 'customer')明細売上@else 明細仕入 @endif
                    <hr>
                </h5>
                <table id="items">
                    <thead>
                        <tr>
                            <th class="column_two"
                                style="font-size:10pt;font-weight:normal;font-style:normal;color:#3c3c3c;">
                                @if ($type == 'customer') 出荷日 @else 入荷日 @endif
                            </th>
                            <th class="column_three"
                                style="font-size:10pt;font-weight:normal;font-style:normal;color:#3c3c3c;">
                                自社発注番号
                            </th>
                            <th class="column_four"
                                style="font-size:10pt;font-weight:normal;font-style:normal;color:#3c3c3c;">
                                型番
                            </th>
                            <th class="column_five"
                                style="font-size:10pt;font-weight:normal;font-style:normal;color:#3c3c3c;">
                                仕入数
                            </th>
                            <th class="column_six"
                                style="font-size:10pt;font-weight:normal;font-style:normal;color:#3c3c3c;">
                                仕入単価
                            </th>
                            <th class="column_four"
                                style="font-size:10pt;font-weight:normal;font-style:normal;color:#3c3c3c;">
                                仕入合計
                            </th>
                            <th class="column_five"
                                style="font-size:10pt;font-weight:normal;font-style:normal;color:#3c3c3c;">
                                顧客注文番号
                            </th>
                            <th class="column_six"
                                style="font-size:10pt;font-weight:normal;font-style:normal;color:#3c3c3c;">
                                売数
                            </th>
                            <th class="column_four"
                                style="font-size:10pt;font-weight:normal;font-style:normal;color:#3c3c3c;">
                                売単価
                            </th>
                            <th class="column_five"
                                style="font-size:10pt;font-weight:normal;font-style:normal;color:#3c3c3c;">
                                売合計
                            </th>
                            <th class="column_six"
                                style="font-size:10pt;font-weight:normal;font-style:normal;color:#3c3c3c;">
                                利益率
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (count($summary_data) != 0)
                            @foreach ($summary_data as $item)
                                <tr>
                                    <td>
                                        @if ($type == 'customer')
                                            {{ $item->ship_date }}@else {{ $item->send_date }} @endif
                                    </td>
                                    <td>{{ $item->order_header->order_no_by_customer }}</td>
                                    <td>{{ $item->katashiki }}</td>
                                    <td>{{ $item->buy_qty }}</td>
                                    <td>{{ $item->buy_cost }}</td>
                                    <td>{{ $item->buy_cost * $item->buy_qty }}</td>
                                    <td>{{ $item->order_no_by_customer }}</td>
                                    <td>{{ $item->sale_qty }}</td>
                                    <td>{{ $item->sale_cost }}</td>
                                    <td>{{ $item->sale_qty }}</td>
                                    <td>{{ $item->sale_cost * $item->sale_qty }}</td>
                                    <td>{{ $item->quote_customer->profit }}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td class="text-center" colspan="12">テーブル内のデータなし。</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            @else
                <h5 class="mt-20">
                    @if ($type == 'customer')売り上げ@else 仕入 @endif
                    <hr>
                </h5>
                <table id="items">
                    <thead>
                        <tr>
                            <th class="column_one"
                                style="font-size:10pt;font-weight:normal;font-style:normal;color:#3c3c3c;"></th>
                            <th class="column_two"
                                style="font-size:10pt;font-weight:normal;font-style:normal;color:#3c3c3c;">
                                売り上げ
                            </th>
                            <th class="column_three"
                                style="font-size:10pt;font-weight:normal;font-style:normal;color:#3c3c3c;">
                                仕入
                            </th>
                            <th class="column_four"
                                style="font-size:10pt;font-weight:normal;font-style:normal;color:#3c3c3c;">
                                利益
                            </th>
                            <th class="column_five"
                                style="font-size:10pt;font-weight:normal;font-style:normal;color:#3c3c3c;">
                                平均利益率
                            </th>
                            <th class="column_six"
                                style="font-size:10pt;font-weight:normal;font-style:normal;color:#3c3c3c;">
                                期間
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($summary_data as $item)
                            <tr>
                                <td class="text-center">
                                    @if ($type == 'customer')
                                        {{ $item['cus_name'] }}@else {{ $item['sup_name'] }} @endif
                                </td>
                                <td class="text-center">{{ $item['total_money_sale'] }}</td>
                                <td class="text-center">{{ $item['total_money_buy'] }}</td>
                                <td class="text-center">{{ $item['total_money_sale_tax'] }}</td>
                                <td class="text-center">{{ $item['total_money_sale'] }}</td>
                                <td class="text-center">{{ $item['rate_profit'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
            <h5 class="fixed-bottom">
                <hr>Foresky Co., Ltd.
            </h5>
        </div>
    </div>
</body>

</html>

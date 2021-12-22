<?php
ini_set('default_charset', 'UTF-8');
mb_internal_encoding('UTF-8');
header('Content-Type: text/html; charset=UTF-8');
date_default_timezone_set('Asia/Tokyo');
if ($order_detail->type_money == 'JPY') {
$type_money = '円';
} elseif ($order_detail->order_header->type_money == 'USD') {
$type_money = '$';
} elseif ($order_detail->order_header->type_money == 'EUR') {
$type_money = '€';
} else {
$type_money = $order_detail->order_header->type_money;
}
?>

<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
    <title>Editable Invoice</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/pdf/send_order_to_supplier.css') }}">
</head>

<body>
    <style type="text/css">

    </style>
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

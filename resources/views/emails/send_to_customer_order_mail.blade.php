<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        .result {
            float: left;
            width: 100%;
            /* text-align: right; */
        }
        .item_lable {
            float: left;
            width: 80%;
            /* text-align: right; */
        }
        .item_value {
            float: left;
            width: 20%;
            /* text-align: right; */
        }
        #list_order tr th,#list_order tr td {
            padding: 3px 5px;
        }
        .infoPayment {
            float: left;
            width: 100%;
        }
        .infoPayment span {
            float: left;
            min-width: 300px;
            text-align: left;
        }
    </style>
</head>
<body>
    {{ $details['quote_info'][0]['customer']['user_info']['company_name'] }}<br>
    {{ $details['quote_info'][0]['customer']['representative'] }} 様<br/>
    <span><i>
        いつもお世話になります。<br>
        (株)フォレスカイの吉沼です。<br>
        下記の内容に間違いがございましたら至急ご連絡ください。
    </i></span><br/>

    ---<お知らせ>--------------------------- <br>
    《お知らせ本文》<br>
    ---------------------------------------<br>

    この度はご注文いただき誠にありがとうございます.<br>
    下記の内容に間違いがございましたら至急ご連絡ください.<br>
    <br/>
    請求先情報 : <br/>
    <div class="infoPayment">
        @php
            $zip = str_split($details['address']['zip'], 3);
        @endphp
        <span>会社名 : </span><span>{{ $details['address']['comp_name'] }}</span> <br/>
        <span>名前 : </span><span>{{ $details['address']['customer_name'] }}</span> <br/>
        <span>部署名 : </span><span>{{ $details['address']['part_name'] }}</span> <br/>
        <span>郵便番号 : </span><span>{{ $zip[0] }}-{{$zip[1]}}{{$zip[2]}}</span> <br/>
        <span>都道府県 : </span><span>{{ $details['address']['address1'] }}</span> <br/>
        <span>市区町村 : </span><span>{{ $details['address']['address2'] }}</span> <br/>
        <span>番地 : </span><span>{{ $details['address']['address3'] }} &nbsp; {{ $details['address']['address4'] }}</span> <br/>
        <span>TEL : </span><span>{{ $details['address']['tel'] }}</span> <br/>
        <span>FAX番号 : </span><span>{{ $details['address']['fax'] }}</span> <br/>
    </div> <br/>
    <br/> 発注情報: <br/><br/>
    <table id="list_order">
        <thead>
            <tr>
                <th>型番</th>
                <th>メーカー</th>
                <th>DC</th>
                <th>区分</th>
                <th>発注数量</th>
                <th>発注単位</th>
                <th>単価</th>
                <th>注文番号</th>
            </tr>
        </thead>
        <tbody>
            @foreach($details['quote_info'] as $item)
                <tr>
                    <td style="text-align: center">{{ $item->katashiki }}</td>
                    <td style="text-align: center">{{ $item->maker }}</td>
                    <td style="text-align: center">{{ $item->dc }}</td>
                    <td style="text-align: center">{{ $item->request_vendors->rfq_request->kbn }}</td>
                    <td style="text-align: center">{{ $item->sell_quantity }}</td>
                    <td style="text-align: center">{{ $item->unit_sell }}</td>
                    <td style="text-align: center">{{ format_number($item->unit_price_sell, $details['quote_info'][0]->type_money_sell) }}</td>
                    <td style="text-align: center">{{ $details['code_quote'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <br> <br/>
    <div class="result">
        <span class="item_lable">商品代合計 :</span>
        <span class="item_value">
            {{ format_number($details['sub_total'], $details['quote_info'][0]->type_money_sell) }}
        </span> <br/>
        @if($details['quote_info'][0]['type_money_sell']=="JPY")
        <span class="item_lable">送料 :</span>
        <span class="item_value">
            {{ format_number($details['fee_shipping'], $details['quote_info'][0]['type_money_sell']) }}
        </span><br/>
            @if($details["fee_daibiki"] > 0 )
                <span class="item_lable">代引き手数料 :</span>
                <span class="item_value">{{ format_number($details['fee_daibiki'], $details['quote_info'][0]['type_money_sell']) }}</span><br/>
            @endif
        @endif
        <span class="item_lable">税抜合計金額 :</span>
        <span class="item_value">
            @php
            $total_with_out_tax = $details['sub_total'] + $details['fee_shipping'] +$details['fee_daibiki'];
            @endphp
            {{ format_number($total_with_out_tax, $details['quote_info'][0]['type_money_sell']) }}
        </span> <br/>
        <span class="item_lable">消費税 :</span>
        <span class="item_value">
            {{ format_number($total_with_out_tax * $details['tax'], $details['quote_info'][0]['type_money_sell']) }}
        </span> <br/>
        <span class="item_lable">税込合計金額 :</span>
        <span class="item_value">
            {{ format_number($total_with_out_tax * $details['tax'], $details['quote_info'][0]['type_money_sell']) }} {{ $details['quote_info'][0]['type_money_sell'] }}
        </span> <br/>
    </div>
    <br>
    <span>以上、よろしくお願いします。</span> <br/>
    <a href="http://www.showadenshisangyo.co.jp/joken.htm">http:&frasl;&frasl;www.showadenshisangyo.co.jp&frasl;joken.htm</a><br>
    このお見積りに関するお問い合わせ先：<br>
    Mail：<a href="sales@foresky.co.jp">hajime@foresky.co.jp</a><br>
    TEL ：04-2963-1276<br><br>
    ※お問合せに対する対応は下記営業時間内となります.<br>
    ●営業時間：AM10:30-PM5:00(土・日曜日、祝祭日定休)<br>
    <br>よろしくお願いいたします.<br>
    -----------------------------------------------------------<br>
    (株)フォレスカイ<br>
    吉沼　肇<br>
    埼玉県入間市久保稲荷4-6-4<br>
    ハイム粕谷1-103<br>
    TEL:　04-2963-1276<br>
    FAX:　04-2963-1278<br>
    hajime@foresky.co.jp<br>
</body>
</html>

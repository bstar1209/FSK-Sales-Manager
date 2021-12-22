@php
ini_set('default_charset', 'UTF-8');
mb_internal_encoding('UTF-8');
header('Content-Type: text/html; charset=UTF-8');
date_default_timezone_set('Asia/Tokyo');
@endphp
<div style="width: 100%; height: 170px">
    <div style="float:left;width:300px;height: 170px;padding: 3.5px; margin: auto">
        <div style="float:left;width:100%;border-bottom:2px solid #000;">
            <div style="float:left;margin-left:2px;border:1px solid #000;margin-bottom:2px;width:82px;font-size: 18px">
                <div style="float:left;padding:4px 10px 3px 10px;font-weight: bold;text-align: center"><b>現品票</b></div>
            </div>
        </div>
        <div style="float:left;width:100%;border-bottom:1px solid #000;">
            <div
                style="float:left;width: 100%;padding-top:4px;padding-bottom: 4px;font-weight: normal;text-align: center;">
                {{ $stock->quote_customer->customer->user_info->company_name }} 御中 </div>
        </div>
        <div style="float:left;width:100%;border-bottom:1px solid #000;">
            <div
                style="float:left;width: 100%;padding-top:4px;padding-bottom: 4px;font-weight: normal;padding-left:3px">
                注文番号 /&nbsp;&nbsp;{{ $stock->user_code }}</div>
        </div>
        <div style="float:left;width:100%;border-bottom:1px solid #000;">
            <div
                style="float:left;width: 100%;padding-top:4px;padding-bottom: 4px;font-weight: normal;padding-left:3px">
                型式 /&nbsp;&nbsp;&nbsp; {{ $stock->katashiki }}</div>
        </div>
        <div style="float:left;width:100%;border-bottom:1px solid #000;">
            <div
                style="float:left;width: 100%;padding-top:4px;padding-bottom: 4px;font-weight: normal;padding-left:3px">
                数量 /&nbsp;&nbsp;&nbsp; {{ $stock->ship_quantity }} {{ $stock->quote_customer->unit_buy }}</div>
        </div>
        <div style="float:left;width:100%;">
            <div
                style="float:left;width: 100%;padding-top:2px;padding-bottom: 0px;font-weight: bold;text-align: center">
                (株)フォレスカイ</div>
        </div>
    </div>
</div>

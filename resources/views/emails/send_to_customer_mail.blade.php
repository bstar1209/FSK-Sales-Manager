<html>
<head>
    <meta http-equiv="Content-Type"  content="text/html charset=UTF-8" />
</head>
<body>
    @php
        if($type_money == 'JPY')
            $type_money = '円';
        else if($type_money =='USD')
            $type_money = 'ドル';
        else if($type_money=='EUR')
            $type_money = 'ユーロ';
    @endphp

<?php echo $infoQuote->customer->user_info->company_name; ?> 御中<br>
{{ $info_quote->customer->user_info->company_name }} 御中<br>
{{ $info_quote->customer->representative' 様' }}<br>
<br>
■□■□■□■□■□■□■□■□■□■□■□■□■□■□■□■□■□■□■□■□■□ <br>
平成24年11月10日より電子部品販売の取り扱いは(株)フォレスカイで承ります。<br>
商品のご注文、見積依頼方法、TEL・FAX番号はこれまで通りですがメールアドレス、住所、銀行口座などは変更されてますのでご登録のほどお願いいたします。<br>
引き続きご愛顧の程よろしくお願い申し上げます。<br>
■□■□■□■□■□■□■□■□■□■□■□■□■□■□■□■□■□■□■□■□■□<br><br><br>
この度はお見積り依頼をいただきまして誠にありがとうございます。<br>
下記の通りお見積り申し上げますので内容をご確認ください。<br><br>
※消費税の引き上げについて<br>
{{ $tax_date }}出荷分より消費税が{{ $tax_value }}となりますのでご了承ください。<br>
<br><br>
<span style="text-align: center">株式会社フォレスカイ 吉沼 肇</span><br>
<span style="text-align: center">sales@foresky.co.jp</span><br><br>
==お見積り内容<br>==============================================================<br>
=&nbsp;※&nbsp;{{ $info_quote->quote_code }} {{ $info_quote->katashiki }}&nbsp;{{ $info_quote->count_predict }}
&nbsp;個&nbsp;×&nbsp;<?php echo $saleCost; ?>&nbsp;<?php echo $type_money ?> &nbsp;＝
&nbsp;<?php echo $saleMoney; ?>&nbsp;<?php echo $type_money ?> &nbsp;(納期：&nbsp;<?php echo $timeDelivery; ?>)<br>
&nbsp;&nbsp;&nbsp;&nbsp;([DC]:<?php echo $dc; ?>,&nbsp;[Rohs]:<?php echo $rohs; ?>,&nbsp;[在庫地域]:<?php echo $area; ?>)<br>
備考：<br><br><br>
-----------------------------------------------------------------------------<br>

<div style="float:left; width: 100%;text-align:right;">
    <div style="float: left;width: 80%;text-align:right; ">商品代 計：</div>
    <div style="float: left;width: 20%;text-align:right; "><?php echo $type_money.' '.$saleMoney; ?> </div>
    <div style="float: left;width: 80%;text-align:right; ">送 料：</div>
    <div style="float: left;width: 20%;text-align:right; "> <?php echo $type_money.' '.$feeShip; ?> </div><br>
    <?php
    if($feeDaibiki >0){ ?>
    <div style="float: left;width: 80%;text-align:right; ">(後払い希望の方はご連絡ください。)代引手数料：</div>
    <div style="float: left;width: 20%;text-align:right; "><?php echo $type_money.' '.$feeDaibiki; ?></div>
    <?php  }
    ?>
    <div style="float: left;width: 80%;text-align:right; ">小 計：</div>
    <div style="float: left;width: 20%;text-align:right; "><?php echo $type_money.' '.$totalSub; ?> </div>
    <div style="float: left;width: 80%;text-align:right; ">消 費 税：</div>
    <div style="float: left;width: 20%;text-align:right; "><?php echo $type_money.' '.$tax; ?> </div>
    <div style="float: left;width: 80%;text-align:right; ">総 合 計：</div>
    <div style="float: left;width: 20%;text-align:right; "><?php echo $type_money.' '.$totalMoney; ?> </div>
</div>
<br>
=============================================================================<br><br>
見積備考：<br><br>
-----------------------------------------------------------------------------<br><br><br>
-----------------------------------------------------------------------------<br><br>
もし価格や納期に問題がございましたらご連絡ください。<br>
仕入先と交渉、もしくは別ルートで調査してより好条件のお見積もりが出せるように努力いたします。<br><br><br>
*市場在庫からの調達品は値段、在庫、納期はご注文受け取り後も変更になってしまう可能性があることをご容赦ください。*<br>
**お支払いは通常、前払い（銀行振込）または代金引き換え(ヤマトコレクトサービス)となります。商品到着後一週間以内のお振り込みを御希望の方はご連絡ください。**<br>
***お取引実績・内容により、お客様の支払いサイトでのお取引も可能です。御希望の方はご連絡ください。***<br>
****ご注文の場合は本メールに返信か御社形式のご注文書をメールに添付して返信ください。****<br><br>
弊社お取引条件が適用されることをご了解ください。<br>
詳しい内容は下記リンクをご覧ください。<br><br>
<a href="http://www.showadenshisangyo.co.jp/joken.htm">http:&frasl;&frasl;www.showadenshisangyo.co.jp&frasl;joken.htm</a><br>
このお見積りに関するお問い合わせ先：<br>
Mail：<a href="sales@foresky.co.jp">sales@foresky.co.jp</a><br>
TEL ：04-2963-1276<br><br>
FAX不可
※お問合せに対する対応は下記営業時間内となります。<br>
●営業時間：AM9:00～PM6:00(土・日曜日定休)<br>
=====================================================<br>
showadenshisangyo.co.jp==
<br>
</body>
</html>

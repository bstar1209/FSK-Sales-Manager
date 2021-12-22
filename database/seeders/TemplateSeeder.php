<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Seeder;
use App\Models\TemplateInfo;

class TemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $template_names = [
            0 => "仕入先への見積依頼メール",
            1 => "仕入先への見積依頼メール（英語版",
            2 => "顧客への見積メール ",
            3 => "仕入先への発注メール ",
            4 => "仕入先への発注メール（英語版) ",
            5 => "出荷メール",
            6 => "ご注文キャンセルのお知らせ ",
            7 => "ご注文内容変更のお知らせ ",
            8 => "会員登録確認メール ",
            9 => "メールアドレスの変更確認メール",
            10 => "メールアドレスの追加確認メール",
            11 => "見積依頼確認メール",
            12 => "受注確認メール ",
            13 => "海外メーカー品調達依頼の確認メール",
            14 => "量産向け部品調達依頼メール ",
            15 => "パスワード再設定メール",
        ];
        $params = [
            "《仕入先会社名》", "《仕入先担当者》", "《お知らせ》", "《型番》", "《買数量》", "《メーカー》", "《納期》",
            "《DC》", "《Rohs対応の有無》", "《本社住所》"
        ];

        $template_content = '<div style="font-size: 18px !important; font-weight: 500">
            《仕入先会社名》 御中<br>
            《仕入先担当者》様<br>
            いつもお世話になります。<br>(株)フォレスカイの吉沼です<br>
            ---<お知らせ>---------------------------<br>
            《お知らせ》<br>
            ---------------------------------------<br>
            下記の部品のお見積りをお願いいたします。<br>
            型番：《型番》<br>
            数量：《買数量》個 <br>
            メーカー名：《メーカー》<br>
            納期: 《納期》<br>
            DC: 《DC》<br>
            Rohs対応の有無: 《Rohs対応の有無》<br>
            在庫の所在地が日本でない場合は国名を記載ください。<br>
            記載がない場合は国内在庫とします。<br>
            売切れの場合は売切れとご返事ください。<br>
            よろしくお願いいたします。<br>
            ------------------------------<br>
            《本社住所》<br>
        </div>';
        $template = new TemplateInfo;
        $template->user_id = Auth::id();
        $template->template_name = $template_names[0];
        $template->template_content = json_encode($template_content);
        $template->template_params = json_encode($params);
        $template->template_index = 0;
        $template->save();

        $params1 = [
            "《仕入先会社名》", "《型番》", "《買数量》", "《メーカー》", "《納期》", "《DC》", "《Rohs対応の有無》", "《本社住所》"
        ];

        $template_content1 = '<div style="font-size: 18px !important; font-weight: 500">Dear &nbsp;&nbsp;《仕入先会社名》<br>
            Could you send me quote for following part?<br>
            PN：《型番》<br>
            Qty：《買数量》pcs<br>
            Mfr：《メーカー》<br>
            Lead time：《納期》<br>
            DC：《DC》<br>
            Rohs compliant：《Rohs対応の有無》<br>

            Even if there is no stock or available, please replay to me.<br>
            --------------------------------<br>
            《本社住所》</div>';

        $template = new TemplateInfo;
        $template->user_id = Auth::id();
        $template->template_name = $template_names[1];
        $template->template_content = json_encode($template_content1);
        $template->template_params = json_encode($params1);
        $template->template_index = 1;
        $template->save();

        $params2 = [
            "《客先名》", "《担当》" , "《お知らせ》", "《型番》", "《売数量》", "《売単位》", "《売単価》", "《メーカー》", "《見積納期》", "《DC》", "《Rohs》", "《国》", "《見積備考》", "《計算結果》",
            "《送料》", "《代引手数料》", "《商品代 計と送料と代引手数料の合計》", "《消費税》", "《総合計》", "《支払い条件》", "《支払い条件》",
            "《本社住所》", "《円》"
        ];

        $template_content2 = '<div style="font-size: 18px !important; font-weight: 500">
            《客先名》 御中<br>
            《担当》様<br>
            いつもお世話になります。<br>
            (株)フォレスカイの吉沼です。<br>

            ---<お知らせ>---------------------------<br>
            《お知らせ》<br>
            ---------------------------------------<br>

            以下の通りお見積もりいたします。<br>

            型番：《型番》<br>
            数量：《売数量》《売単位》<br>
            単価：《売単価》円 <br>
            メーカー名：《メーカー》<br>
            納期：《見積納期》<br>
            DC：《DC》<br>
            Rohs対応の有無：《Rohs》<br>
            仕入ルート：《国》<br>
            備考：《見積備考》<br>

            商品代　計：《計算結果》《円》<br>
            　　　送料：《送料》《円》<br>
            代引手数料：《代引手数料》《円》<br>
            　　　小計：《商品代 計と送料と代引手数料の合計》《円》<br>
            　　消費税：《消費税》《円》<br>
            　　総合計：《総合計》《円》<br>

            お支払条件: 《支払い条件》<br>
            お支払条件は変更になる場合もございます。<br>

            お支払条件:《支払い条件》<br>
            後払いご希望の方はマイアカウントの登録情報変更から変更できます。<br>

            希望単価がございましたら教えて頂けますでしょうか。<br>
            仕入先に交渉いたします。<br>

            ご注文はマイアカウントの見積回答からお手続きください。<br>
            メールやFAXではご注文は通常受け付けておりません。<br>
            ご質問は担当へお問い合わせください。<br>

            値段、在庫数や納期はご注文受け取り後も変更になってしまう可能性が あることをご了承ください。<br>
            弊社規約が適用されます。<br>
            詳しい規約の内容は下記ページをご確認ください。<br>
            http://www.新しいサイトの規約ページアドレス(未定)<br>

            このお見積りに関するお問い合わせ先：<br>
            Mail：hajime@foresky.co.jp<br>
            TEL ：04-2963-1276<br>

            ※お問合せに対する対応は下記営業時間内となります。<br>
            営業時間：AM10:30-PM5:00(土・日曜日、祝祭日定休)<br>

            よろしくお願いいたします。<br>

            ------------------------------<br>
            《本社住所》
        </div>';

        $template = new TemplateInfo;
        $template->user_id = Auth::id();
        $template->template_name = $template_names[2];
        $template->template_content = json_encode($template_content2);
        $template->template_params = json_encode($params2);
        $template->template_index = 2;
        $template->save();

        $params3 = [
            "《仕入先会社名》", "《仕入先担当者》", "《お知らせ》", "《型番》", "《買数量》",
            "《メーカー》", "《納入日》", "《DC》", "《Rohs》", "《国》", "《Ship By》", "《本社住所》"
        ];

        $template_content3 = '<div style="font-size: 18px !important; font-weight: 500">
            《仕入先会社名》 御中<br>
            《仕入先担当者》様<br>
            いつもお世話になります。<br>
            (株)フォレスカイの吉沼です。<br>
            ---<お知らせ>---------------------------<br>
            《お知らせ》<br>
            ---------------------------------------<br>

            下記の部品を発注いたします。<br>
            手配のほどよろしくお願いいたします。<br>
            注文受領確認のためにお手数ですが必ずご返信いただけますようお願いいたします。<br>

            型番:《型番》<br>
            数量:《買数量》個<br>
            メーカー名:《メーカー》<br>
            納期：《納入日》<br>
            DC：《DC》<br>
            Rohs対応の有無：《Rohs》<br>
            在庫所在地：《国》<br>

            出荷方法：《Ship By》<br>

            お見積もり内容から変更が生じた場合は当日中にご連絡ください。<br>
            よろしくお願いいたします。<br>
            ------------------------------<br>
            《本社住所》
        </div>';

        $template = new TemplateInfo;
        $template->user_id = Auth::id();
        $template->template_name = $template_names[3];
        $template->template_content = json_encode($template_content3);
        $template->template_params = json_encode($params3);
        $template->template_index = 3;
        $template->save();

        $params4 = [
            "《仕入先会社名》", "《型番》", "《買数量》",
            "《メーカー》", "《納入日》", "《DC》", "《Rohs》", "《国》", "《本社住所》"
        ];

        $template_content4 = '<div style="font-size: 18px !important; font-weight: 500">
            Dear 《仕入先会社名》<br>
            Here is a PO for following parts.<br>
            Please send me a PI and your bank information.<br>
            If you accept payment by credit card or Paypal, please let me know.<br>

            If the part is sold out, please let me know today.<br>

            PN：《型番》<br>
            Qty：《買数量》pcs<br>
            Mft.：《メーカー》<br>
            Delivery：《納入日》<br>
            DC：《DC》<br>
            Rohs compliant：《Rohs》<br>
            Stock location：《国》<br>

            Thank you,<br>
            Hajime<br>
            --------------------------------<br>
            《本社住所》
        </div>';

        $template = new TemplateInfo;
        $template->user_id = Auth::id();
        $template->template_name = $template_names[4];
        $template->template_content = json_encode($template_content4);
        $template->template_params = json_encode($params4);
        $template->template_index = 4;
        $template->save();

        $params5 = [
            "《客先名》", "《担当》", "《お知らせ》", "《出荷日》",
            "《型番》", "《売数量》", "《売単位》", "《売単価》", "《メーカー》", "《計算結果》", "《送料》", "《代引手数料》", "《商品代 計と送料と代引手数料の合計》", "《消費税》", "《総合計》", "《支払い条件》", "《会社名1》", "《部署名1》", "《名前1》", "《郵便番号1》",
            "《都胴部券1》", "《市町村1》", "《番地1》", "《ビル名1》",
            "《TEL1》", "《会社名2》", "《部署名2》", "《名前2》", "《郵便番号2》","《都胴部券2》", "《市町村2》", "《番地2》", "《ビル名2》", "《TEL2》", "《本社住所》", "《円》"
        ];
        $template_content5 = '<div style="font-size: 18px !important; font-weight: 500">
            《客先名》 御中<br>
            《担当》様<br>
            いつもお世話になります。<br>
            (株)フォレスカイの吉沼です。<br>

            ---<お知らせ>---------------------------<br>
            《お知らせ》<br>
            ---------------------------------------<br>

            以下の商品が《出荷日》に出荷予定となっております。<br>

            型番：《型番》<br>
            数量：《売数量》《売単位》<br>
            単価：《売単価》《円》<br>
            メーカー名：《メーカー》<br>

            商品代　計：《計算結果》《円》<br>
            　　　送料：《送料》《円》<br>
            代引手数料：《代引手数料》《円》<br>
            　　　小計：《商品代 計と送料と代引手数料の合計》《円》<br>
            　　消費税：《消費税》《円》<br>
            　　総合計：《総合計》《円》<br>

            お支払条件: 《支払い条件》<br>
            ※代引きでのお支払いの場合は、商品お受け取り時に上記金額を配送業者(ヤマト運輸)にお支払いください。<br>

            納品先住所 <br>
            《会社名1》<br>
            《部署名1》<br>
            《名前1》様 <br>
            《郵便番号1》<br>
            《都胴部券1》　《市町村1》　《番地1》<br>
            《ビル名1》<br>
            《TEL1》<br>

            請求先住所<br>
            《会社名2》<br>
            《部署名2》<br>
            《名前2》様<br>
            《郵便番号2》<br>
            《都胴部券2》　《市町村2》　《番地2》<br>
            《ビル名2》<br>
            《TEL2》<br>

            ヤマト運輸お問い合わせ伝票番号：3573-4308-8351<br>
            -----------------------------------------------------------------------------<br>
            現在の配送状況は上記ヤマト運輸お問い合わせ伝票番号でヤマト運輸HPからご確認いただけます。<br>
            集荷のタイミングによっては伝票番号がヤマト運輸HPに反映されていない場合もございます。<br>
            その場合は時間を改めてご確認ください。<br>

            ヤマト運輸HP：http://www.kuronekoyamato.co.jp/<br>

            弊社規約が適用されます。<br>
            詳しい規約の内容は下記ページをご確認ください。<br>
            http://www.新しいサイトの規約ページアドレス(未定)<br>

            Mail：hajime@foresky.co.jp<br>
            TEL ：04-2963-1276<br>

            ※お問合せに対する対応は下記営業時間内となります。<br>
            営業時間：AM10:30-PM5:00(土・日曜日、祝祭日定休)<br>

            よろしくお願いいたします。<br>

            ------------------------------<br>
            《本社住所》
        </div>';
        $template = new TemplateInfo;
        $template->user_id = Auth::id();
        $template->template_name = $template_names[5];
        $template->template_content = json_encode($template_content5);
        $template->template_params = json_encode($params5);
        $template->template_index = 5;
        $template->save();

        $params15 = [
            "《客先名》", "《担当》", "《お知らせ》", "《メールアドレス》", "《パスワード》", "《本社住所》"
        ];
        $template_content15 = '<div style="font-size: 18px !important; font-weight: 500">
            《客先名》 御中 <br>
            《担当》様 <br>

            ***このメールは送信専用のアドレスから送信されてますので***<br>
            ***このアドレスへメールを返信することはできません。***<br>

            ---<お知らせ>---------------------------<br>
            《お知らせ》<br>
            ---------------------------------------<br>

            パスワードをリセットいたしました。新しいパスワードは下記の通りになります。<br>
            ユーザ名：《メールアドレス》<br>
            パスワード: 《パスワード》<br>

            次回はこの新しいパスワードでログインしてください。<br>

            パスワードを変更したい場合はマイアカウントのパスワード変更から
            任意のパスワードに変更することができます。<br>


            弊社ウェブサイトの使い方についてのお問い合わせは以下になります。<br>
            TEL：04-2963-1276 <br>
            Eメール：sales@foresky.co.jp <br>

            ※お問合せに対する対応は下記営業時間内となります。<br>
            営業時間：AM10:30-PM5:00(土・日曜日、祝祭日定休)<br>

            よろしくお願い致します。<br>
            ------------------------------<br>
            《本社住所》
        </div>';

        $template = new TemplateInfo;
        $template->user_id = Auth::id();
        $template->template_name = $template_names[15];
        $template->template_content = json_encode($template_content15);
        $template->template_params = json_encode($params15);
        $template->template_index = 15;
        $template->save();

        $params8 = [
            '《客先名》', '《担当》', '《お知らせ》', '《本社住所》'
        ];
        $template_content8 = '<div style="font-size: 18px !important; font-weight: 500">
            《客先名》 御中<br>
            《担当》様<br>

            ***このメールは送信専用のアドレスから送信されてますので***<br>
            ***このアドレスへメールを返信することはできません。***<br>

            //お知らせがある場合はここに表示される。<br>
            ---<お知らせ>---------------------------<br>
            《お知らせ》<br>
            ---------------------------------------<br>

            この度は(株)フォレスカイの規約に同意の上、ウェブサイトの会員登録をしていただき、誠にあり<br>
            がとうございます。<br>
            登録情報は以下通りです。<br>

            ・ユーザ名：sales@foresky.co.jp<br>
            ・パスワード：********<br>

            以下のリンクにアクセスして、ログインしてください。<br>
            http://foresky.co.jp/FSKSaleManager/index.php<br>

            会員登録時に同意していただいた規約は下記リンクをご覧ください。<br>
            http://foresky.co.jp/規約のページ<br>

            弊社ウェブサイトの使い方についてのお問い合わせは以下になります。<br>
            TEL：04-2963-1276<br>
            Eメール：sales@foresky.co.jp<br>
            ※お問合せに対する対応は下記営業時間内となります。<br>
            営業時間：AM10:30-PM5:00(土・日曜日、祝祭日定休)<br>

            よろしくお願い致します。<br>
            ------------------------------<br>
            《本社住所》
        </div>';

        $template = new TemplateInfo;
        $template->user_id = Auth::id();
        $template->template_name = $template_names[8];
        $template->template_content = json_encode($template_content8);
        $template->template_params = json_encode($params8);
        $template->template_index = 8;
        $template->save();

        $params11 = [
            '《客先名》', '《担当》', '《お知らせ》', '《受付番号》', '《型番》',
            '《希望数》', '《希望単価》', '《メーカー》', '《DC》', '《国》', '《顧客からのメッセージ》',
            '《条件1》', '《条件2》', '《条件3》'
        ];

        $template_content11 = '<div style="font-size: 18px !important; font-weight: 500">
            《客先名》 御中 <br>
            《担当》様 <br>
            いつもお世話になります。<br>

            ---<お知らせ>---------------------------<br>
            《お知らせ》<br>
            ---------------------------------------<br>

            この度、弊社にご見積依頼していただき、誠にありがとうございます。<br>
            下記の通り見積依頼を承りました。<br>

            受付番号：《受付番号》 <br>
            型番：《型番》<br>
            希望数数量：《希望数》pcs <br>
            希望単価：《希望単価》<br>
            メーカー名：《メーカー》<br>
            DC：《DC》<br>
            仕入ルート：《国》<br>
            備考：《顧客からのメッセージ》<br>
            条件1：《条件1》<br>
            条件2：《条件2》<br>
            条件3：《条件3》<br>

            早速、在庫調査を行い、見積回答させていただきますのでしばらくお待ちください
        </div>';

        $template = new TemplateInfo;
        $template->user_id = Auth::id();
        $template->template_name = $template_names[11];
        $template->template_content = json_encode($template_content11);
        $template->template_params = json_encode($params11);
        $template->template_index = 11;
        $template->save();

        $params13 = [
            '《客先》', '《担当》', '《お知らせ》', '《問い合わせメーカー名》', '《お問い合わせ内容》'
        ];

        $template_content13 = '<div style="font-size: 18px !important; font-weight: 500">
            《客先》 御中<br>
            《担当》様<br>
            いつもお世話になります。<br>
            //お知らせがある場合はここに表示される。<br>
            ---<お知らせ>---------------------------<br>
            《お知らせ》<br>
            ---------------------------------------<br>

            この度、弊社に海外メーカー品調達依頼していただき、誠にありがとうございます。<br>
            下記の通りご依頼を承りました。<br>

            メーカー名：《問い合わせメーカー名》<br>
            お問い合わせ内容:<br>
            《お問い合わせ内容》<br>

            後ほど担当より連絡させて頂きます。<br>
            TEL：04-2963-1276<br>
            Eメール：sales@foresky.co.jp<br>
            ※お問合せに対する対応は下記営業時間内となります。
        </div>';

        $template = new TemplateInfo;
        $template->user_id = Auth::id();
        $template->template_name = $template_names[13];
        $template->template_content = json_encode($template_content13);
        $template->template_params = json_encode($params13);
        $template->template_index = 13;
        $template->save();

        $params14 = [
            '《客先》', '《担当》', '《お知らせ》', '《プロジェクト名》', '《エンドユーザー名》', '《使用用途》', '《年間使用数》', '《量産開始時期》', '《お問い合わせ内容》', "《本社住所》"
        ];

        $template_content14 = '<div style="font-size: 18px !important; font-weight: 500">
            《客先》 御中<br>
            《担当》様<br>
            いつもお世話になります。<br>

            ---<お知らせ>---------------------------<br>
            《お知らせ》<br>
            ---------------------------------------<br>

            この度、弊社に量産向け部品調達依頼していただき、誠にありがとうございます。<br>
            下記の通りご依頼を承りました。<br>

            プロジェクト名：《プロジェクト名》<br>
            エンドユーザー名：《エンドユーザー名》<br>
            使用用途：《使用用途》<br>
            年間使用数：《年間使用数》<br>
            量産開始時期：《量産開始時期》<br>
            お問い合わせ内容：<br>
            《お問い合わせ内容》<br>

            後ほど担当より連絡させて頂きます。<br>

            TEL：04-2963-1276<br>
            Eメール：sales@foresky.co.jp<br>
            ※お問合せに対する対応は下記営業時間内となります。<br>
            営業時間：AM10:30-PM5:00(土・日曜日、祝祭日定休)<br>

            よろしくお願い致します。<br>
            ------------------------------<br>
            《本社住所》<br>
        </div>';

        $template = new TemplateInfo;
        $template->user_id = Auth::id();
        $template->template_name = $template_names[14];
        $template->template_content = json_encode($template_content14);
        $template->template_params = json_encode($params14);
        $template->template_index = 14;
        $template->save();
    }
}
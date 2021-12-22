<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\RequestQuoteVendor;

class RequestQuoteVendorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $maker_names = ['Toshiba', "XILINX", "NEC", 'MURATA', 'NLT (NEC)', 'TOSHIBA', 'SHARP'];
        $model_numbers = [
            'SIIDK1928', 'IKIE8382', 'QQAUSD321', 'VNJD2918', 'UJFUE0928', '23IKFKDIF', '39121NVIDI',
            'IKFI0392', 'VMKDI3981', 'IKFIW93982'
        ];
        $money_types = [
            'USD', 'EUR', 'JPY', 'UER', 'VMD', 'CNY',
        ];
        $rohs_list = ['Rohs', '鉛フリー', '有鉛品', '未確認', '不明'];
        $data = [
            "maker" => "Toshiba",
            "katashiki" => "2SC1815GR-TPE2-test",
            "katashiki_not_spl" => "2SC1815GRTPE2test",
            "quantity_buy" => "350",
            "unit_buy" => "psc",
            "type_money_buy" => "USD",
            "unit_price_buy" => "0.25",
            "dc" => "098",
            "kbn2" => "国内",
            "rohs" => "鉛フリー",
            "deadline_buy_vendor" => "7-9D",
            "fee_shipping" => "1500",
            "date_quote" => "20/3/2019",
            "code_quote" => "252",
            "comment_business" => "aaaaaa\nbbbb\nccc\ndddd\neee\n",
            "is_sendmail" => "1",
            "is_send_est" => "1",
            "is_received_quote" => "",
            "fee_ship2" => ""
        ];

        for ($i=0; $i < 5000; $i++)
        {
          $recode = new RequestQuoteVendor;
          $recode->rfq_request_id = rand(1, 500);
          $recode->rfq_request_child_id = rand(1, 500);
          $recode->supplier_id = rand(1, 9);
          $recode->maker = $maker_names[rand(0, 5)];
          $recode->katashiki = rand(0, 10).$model_numbers[rand(0, 9)];
          $recode->katashiki_not_spl = rand(0, 10).$model_numbers[rand(0, 9)];
          $recode->quantity_buy = $data['quantity_buy'];
          $recode->unit_buy = $data['unit_buy'];
          $recode->type_money_buy = $money_types[rand(0, 5)];
          $recode->unit_price_buy = $data['unit_price_buy'];
          $recode->dc = $i.$data['dc'];
          $recode->kbn2 = $i.$data['kbn2'];
          $recode->rohs = $rohs_list[rand(0, 4)];
          $recode->code_quote = $i.$data['code_quote'];
          $recode->fee_shipping = $data['fee_shipping'];
          $recode->comment_business = $i.$data['comment_business'];
          $recode->is_sendmail = rand(0, 1);
          $recode->is_send_est = 0;
          $recode->is_received_quote = 0;
          $recode->fee_ship2 = $data['fee_ship2'];
          $recode->deadline_buy_vendor = $data['deadline_buy_vendor'];
          $recode->save();
        }
    }
}

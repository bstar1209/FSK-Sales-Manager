<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\RfqRequest;

class RfqRequestSeeder extends Seeder
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
        $request_data = [
            "id" =>  "1",
            "detail_id" =>  "1",
            "supplier_id" =>  "2",
            "user_id" =>  "2",
            "rfq_date" =>  "18/3/2019",
            "maker" =>  "Toshiba",
            "katashiki" =>  "2SC1815GR",
            "katashiki_not_spl" =>  "2SC1815",
            "quantity_aspiration" =>  "200",
            "price_aspiration" =>  "",
            "kbn" =>  "10001-2",
            "condition1" =>  "",
            "condition2" =>  "",
            "condition3" =>  "",
            "comment" =>  "",
            "total" =>  "2",
            "dc" =>  "2004",
            "rohs" =>  "",
            "kbn2" => "国内"
        ];

        for ($i=0; $i < 500; $i++)
        {
          $recode = new RfqRequest;
          $recode->detail_id = $i+1;
          $recode->customer_id = rand(1, 10);
          $recode->supplier_id = rand(1, 10);
          $recode->maker = $maker_names[rand(1, 5)];
          $recode->katashiki = rand(0, 10).$model_numbers[rand(0, 9)];
          $recode->katashiki_not_spl = rand(0, 10).$model_numbers[rand(0, 9)];
          $recode->quantity_aspiration = $request_data['quantity_aspiration'] + $i;
          $recode->count_aspiration = 5 * $i;
          $recode->price_aspiration = intval($request_data['price_aspiration']);
          $recode->total = $request_data['total'];
          $recode->kbn = $i.$request_data['kbn'];
          $recode->kbn2 = $i.$request_data['kbn2'];
          $recode->dc = $i.$request_data['dc'];
          $recode->rohs = $i.$request_data['rohs'];
          $recode->is_old_data = false;
          $recode->is_solved = 1;
          $recode->save();
        }
    }
}

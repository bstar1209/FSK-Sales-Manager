<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\RfqDetail;

class RfqDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            "id" =>  "1",
            "detail_id" =>  "1",
            "supplier_id" =>  "1",
            "user_id" =>  "2",
            "rfq_date" =>  "18/3/2019",
            "maker" =>  "Toshiba",
            "katashiki" =>  "2SC1815GR-TPE2-test",
            "katashiki_not_spl" =>  "2SC1815GRTPE2test",
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


        for ($i=0; $i < 5000; $i++)
        {
          $recode = new RfqDetail;
          $recode->count_expect = $i;
          $recode->condition1 = $data['condition1'];
          $recode->condition2 = $data['condition2'];
          $recode->condition3 = $data['condition3'];
          $recode->is_delete = 1;
          $recode->is_excute = 1;
          $recode->is_estimate = 1;
          $recode->is_view = 1;
          $recode->is_solved = rand(0, 1);
          $recode->is_cancel = rand(0, 1);
          $recode->save();
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Maker;

class MakerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $maker_names = ['Toshiba', "XILINX", "NEC", 'MURATA', 'NLT (NEC)', 'TOSHIBA', 'SHARP'];

        for($i=0; $i< count($maker_names); $i++)
        {
            $new_maker = new Maker;
            $new_maker->maker_name = $maker_names[$i];
            $new_maker->save();
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Parts;

class PartSeeder extends Seeder
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

        for ($i=0; $i < 1000; $i++)
        {
            $part = new Parts;
            $part->katashiki = '000'.$i.$model_numbers[rand(0, 9)];
            $part->maker = $maker_names[rand(0, 6)];
            $part->qty = rand(0, 1000);
            $part->kubun2 = '国内';
            $part->save();
        }
    }
}

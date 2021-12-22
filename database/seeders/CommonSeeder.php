<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Common;

class CommonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $names = Common::$common_names;
        for ($i = 0; $i < count($names); $i++) 
        {
            $cond_payment = new Common;
            $cond_payment->common_name = $names[$i][0];
            $cond_payment->common_type = $names[$i][1];
            $cond_payment->save();
        }
    }
}

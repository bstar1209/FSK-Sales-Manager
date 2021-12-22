<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Rate;

class RateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rate_data = [
            ['BB', 1, 2],
            ['CNY', 19.8, 20.8],
            ['EUR', 135, 140],
            ['GBP', 135, 160],
            ['HKD', 15, 16.3],
            ['JHG', 2.2, 2],
            ['JPY', 1, 1],
            ['LAO', 1, 1],
            ['RUP', 2.3, 2.1],
            ['SGD', 81, 92],
            ['TES', 10, 20],
            ['UER', 18, 19],
            ['USD', 100, 120],
            ['VMD', 0.006, 0.007],
        ];

        for ($i=0; $i<count($rate_data); $i++)
        {
            $rate = new Rate;
            $rate->type_money = $rate_data[$i][0];
            $rate->sale_rate = $rate_data[$i][1];
            $rate->buy_rate = $rate_data[$i][2];
            $rate->is_active = rand(0, 1);
            $rate->save();
        }
    }
}

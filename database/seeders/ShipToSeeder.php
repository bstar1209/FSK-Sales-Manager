<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\ShipTo;

class ShipToSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i=0; $i < 10; $i++)
        {
            $ship_to = new ShipTo;
            $ship_to->comp_name = 'Foresky'.rand(100, 1000);
            $ship_to->staff = 'Hajime'.rand(100, 1000);
            $ship_to->address = 'Heim Japan　358-'.rand(100, 1000);
            $ship_to->fax = rand(10000, 10000000);
            $ship_to->zip = rand(10000, 10000000);
            $ship_to->representative = 'クアン'.rand(10, 100);
            $ship_to->country = 'JP';
            $ship_to->province = '三重'.rand(10, 100);
            $ship_to->city = '横浜'.rand(10, 100);
            $ship_to->save();
        }
    }
}

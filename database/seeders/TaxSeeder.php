<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Tax;
use App\Models\TaxLog;

class TaxSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tax_data = [0.06, 0.07, 0.04, 0.05];
        for($i = 0; $i < count($tax_data); $i++)
        {
            $tax = new Tax;
            $tax->tax = $tax_data[$i];
            $tax->save();
        }

        $tax_log = new TaxLog;
        $tax_log->tax = 8;
        $tax_log->save();
    }
}

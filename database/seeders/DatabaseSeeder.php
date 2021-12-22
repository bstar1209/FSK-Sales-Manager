<?php

namespace Database\Seeders;

use App\Models\TemplateInfo;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UserSeeder::class,
            CommonSeeder::class,
            CustomerSeeder::class,
            SupplierSeeder::class,
            RfqRequestSeeder::class,
            PartSeeder::class,
            RequestQuoteVendorSeeder::class,
            MakerSeeder::class,
            TaxSeeder::class,
            RateSeeder::class,
            TransportSeeder::class,
            ShipToSeeder::class,
            HeaderQuarterSeeder::class,
            TableConfigSeeder::class,
            TemplateSeeder::class
        ]);
    }
}

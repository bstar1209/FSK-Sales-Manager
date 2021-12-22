<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Transport;

class TransportSeeder extends Seeder
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
            $transport = new Transport;
            $transport->name = 'Japan J'.rand(100, 1000);
            $transport->save();
        }
    }
}

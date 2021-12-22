<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HeaderQuarter;

class HeaderQuarterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $header_quarter = new HeaderQuarter;
        $header_quarter->company_name = '(株)フォレスカイ';
        $header_quarter->address = '358-0024</br>埼玉県入間市久保稲荷4-6-4</br>
        ハイム粕谷1-103</br>';
        $header_quarter->tel = '04-2963-1276';
        $header_quarter->type = 0;
        $header_quarter->save();

        $header_quarter = new HeaderQuarter;
        $header_quarter->company_name = 'Foresky.Co., Ltd';
        $header_quarter->address = 'Heimu Kasuya 1-103</br>
        4-6-4 Kuboniari Lruma Saitama</br> 358-0024 Japan';
        $header_quarter->tel = '04-2963-1276';
        $header_quarter->type = 1;
        $header_quarter->save();
    }
}

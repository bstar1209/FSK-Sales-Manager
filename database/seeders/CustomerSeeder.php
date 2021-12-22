<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Customer;
use App\Models\UserInfo;
use App\Models\Address;
use App\Models\PaymentCondition;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $customer_names = ['陽葵', '陽菜', '結愛', '咲良', '一千花', '丹梨', '冴咲', '佑泉', '翔', '湊', '芽依'];
        $customer_names_kana = ['ひなた', 'ひなた', 'ゆあ', 'さくら', 'いちか', 'あかり', 'さえみ', 'ゆい', 'かける', 'みなと', 'めい'];
        $customer_data = [
            "compName" => "おいｊ",
            "compNameKana" => "カブシキカイシャ",
            "tel" => "12345678",
            "address" => "おいｊ",
            "fax" => "9382739273",
            "zip" => "103-0028",
            "homepages" => "http://tk2-409-45667.vs.sakura.ne.jp",
            "comp_type" => "business",
            "part_name" => "",
            "address_type" => 1,
            "cusRepresentative" => "て",
            "email" => "hocnv1@yopmail.com",
            "cusRepresentativeBusiness" => "AlylmA",
            "customerName" => "lylm sam"
        ];

        $email_list = [
            'hajime@foresky.co.jp', 
            'test@gmail.com', 
            'testuser1@gmail.com', 
            'testuser2@gmail.com',
            'testuser3@gmail.com', 
            'testuser4@gmail.com', 
            'testuser5@gmail.com'
        ];

        for ($i=1; $i < 7; $i++)
        {
            $address = new Address;
            $address->zip = $i.$customer_data['zip'];
            $address->tel = $customer_data['tel'];
            $address->fax = $customer_data['fax'];
            $address->address1 = $i.$customer_data['address'];
            $address->country = 'JP';
            $address->comp_type = $i.$customer_data['comp_type'];
            $address->part_name = $i.$customer_data['part_name'];
            $address->homepages = $i.$customer_data['homepages'];
            $address->address_type = 0;
            $address->address_index = 1;
            $address->save();

            $user_info = new UserInfo;
            $user_info->type = "customer";
            $user_info->address_id = $address->id;
            $user_info->company_name = $customer_names[$i-1];
            $user_info->company_name_kana = $customer_names_kana[$i-1];
            $user_info->rank = $i+1;
            $user_info->email1 = $email_list[$i - 1];
            $user_info->order_qty = $i;
            $user_info->order_money = $i * 100;
            $user_info->save();

            for ($j = 1; $j < 3; $j++)
            {
                $address = new Address;
                $address->user_info_id = $user_info->id;
                $address->zip = $i.$customer_data['zip'];
                $address->tel = $customer_data['tel'];
                $address->fax = $customer_data['fax'];
                $address->address1 = $i.$j.$customer_data['address'];
                $address->country = 'JP';
                $address->comp_type = $i.$j.$customer_data['comp_type'];
                $address->part_name = $i.$j.$customer_data['part_name'];
                $address->homepages = $i.$j.$customer_data['homepages'];
                $address->address_type = $j;
                $address->address_index = 1;
                $address->save();
            }

            $payment_condition = new PaymentCondition;
            $payment_condition->user_info_id = $user_info->id;
            $payment_condition->common_id = 1;
            $payment_condition->save();

            $customer = new Customer;
            $customer->user_id = $i;
            $customer->user_info_id = $user_info->id;
            $customer->name = $i.$customer_data['customerName'];
            $customer->url = $i."tk2-409-45667.vs.sakura.ne.jp";
            $customer->conditions = 1;
            $customer->representative = $i.$customer_data['cusRepresentative'];
            $customer->representative_business = $i.$customer_data['cusRepresentativeBusiness'];
            $customer->comment_business = $i."yopmail";
            $customer->is_active = 1;
            $customer->is_friend = 0;
            $customer->save();
        }
    }
}

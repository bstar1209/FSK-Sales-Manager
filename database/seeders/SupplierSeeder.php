<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Supplier;
use App\Models\UserInfo;
use App\Models\Address;
use App\Models\PaymentCondition;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $supplier_names = ['陽葵', '陽菜', '結愛', '咲良', '一千花', '丹梨', '冴咲', '佑泉', '翔', '湊', '芽依'];
        $supplier_names_kana = ['ひなた', 'ひなた', 'ゆあ', 'さくら', 'いちか', 'あかり', 'さえみ', 'ゆい', 'かける', 'みなと', 'めい'];
        $supplier_data = [
            "compName" => "おいｊ",
            "compNameKana" => "カブシキカイシャ",
            "address" => "おいｊ",
            "tel" => "12345678",
            "fax" => "ABC",
            "zip" => "ASB.zip",
            "homepages" => "tk2-409-45667.vs.sakura.ne.jp",
            "comp_type" => "business",
            "part_name" => "ABC",
            "address_type" => 1,
            "cusRepresentative" => "て",
            "email" => "hocnv1@yopmail.com",
            "cusRepresentativeBusiness" => "lylm",
            "customerName" => "lylm sam"
        ];

        for ($i=0; $i < 10; $i++)
        {
            $address = new Address;
            $address->zip = $i.$supplier_data['zip'];
            $address->tel = $supplier_data['tel'];
            $address->fax = $supplier_data['fax'];
            $address->country = 'JP';
            $address->address1 = $i.$supplier_data['address'];
            $address->comp_type = $i.$supplier_data['comp_type'];
            $address->part_name = $i.$supplier_data['part_name'];
            $address->homepages = $i.$supplier_data['homepages'];
            $address->save();

            $user_info = new UserInfo;
            $user_info->type = "supplier";
            $user_info->address_id = $address->id;
            $user_info->email1 = $i."sales@foresky.co.jp";
            $user_info->company_name = $supplier_names[$i];
            $user_info->company_name_kana = $supplier_names_kana[$i];
            $user_info->rank = $i+1;
            $user_info->order_qty = $i;
            $user_info->order_money = $i * 100;
            $user_info->order_money = $i * 100;
            $user_info->save();

            $payment_condition = new PaymentCondition;
            $payment_condition->user_info_id = $user_info->id;
            $payment_condition->common_id = 1;
            $payment_condition->save();

            $supplier = new Supplier;
            $supplier->user_info_id = $user_info->id;
            $supplier->district = "";
            $supplier->cal_po_time = 1;
            $supplier->save();
        }
    }
}

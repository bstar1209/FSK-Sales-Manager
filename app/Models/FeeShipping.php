<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeeShipping extends Model
{
    use HasFactory;

    protected $table = 'fee_shipping';
    public $timestamps = true;

    public static function get_fee_shipping($param, $money_type) {
        $param = str_replace(' ', '', $param);
        $cost_fee = 0;
        if ($money_type == "JPY")
        {
            $fee_shipping = FeeShipping::all();
            foreach($fee_shipping as $fee)
            {
                $province = explode(',',  $fee->moreInformation);
                foreach($province as $item_province)
                {
                    if($item_province == $param) {
                        $cost_fee = $fee->fee;
                        break;
                    }
                }
            }
        }
        return $cost_fee;
    }
}

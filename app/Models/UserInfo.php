<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserInfo extends Model
{
    use HasFactory;

    protected $table = 'user_info';

    public function emails()
    {
        return $this->hasMany(UserEmails::class, 'user_info_id', 'id');
    }

    public function address()
    {
        return $this->hasOne(Address::class, 'id', 'address_id')->where('address_type', '=', 0);
    }

    public function payment()
    {
        return $this->hasMany(PaymentCondition::class, 'user_info_id', 'id');
    }

    public function billing_address()
    {
        return $this->hasMany(Address::class, 'user_info_id', 'id')->where('address_type', '=', 1);
    }

    public function deliver_address()
    {
        return $this->hasMany(Address::class, 'user_info_id', 'id')->where('address_type', '=', 2);
    }

    public static function convertCollectionToArray($collections, $id_arr)
    {
        $data_arr = [];
        foreach($id_arr as $id)
        {
            array_push($data_arr, $collections[$id]);
        }

        return json_encode($data_arr);
    }
}

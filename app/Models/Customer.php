<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'customer';
    protected $dates = ['deleted_at'];

    public function rfq_requests()
    {
        return $this->hasMany(RfqRequest::class, 'customer_id', 'id');
    }

    public function user_info()
    {
        return $this->hasOne(UserInfo::class, 'id', 'user_info_id');
    }

    public function carts()
    {
        return $this->hasMany(CartLog::class, 'customer_id', 'id');
    }

    public function order_header()
    {
        return $this->hasMany(OrderHeader::class, 'customer_id', 'id');
    }
    public function salesman() 
    {
        return $this->hasOne(ChargeBusiness::class, 'id', 'representative_business');
    }
}

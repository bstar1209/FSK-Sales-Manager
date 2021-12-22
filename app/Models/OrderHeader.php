<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderHeader extends Model
{
    use HasFactory;

    protected $table = 'order_header';
    protected $appends = ['item_number', 'item_ids'];

    public $timestamps = false;

    public function order_details()
    {
        return $this->hasMany(OrderDetail::class, 'order_header_id', 'id');
    }

    public function getItemNumberAttribute()
    {
        return $this->order_details->count();
    }

    public function getItemIdsAttribute()
    {
        return $this->order_details->pluck('id')->toArray();
    }

    public function import_goods()
    {
        return $this->hasMany(ImportGoods::class, 'order_id', 'id');
    }

    public function payment_condition()
    {
        return $this->hasOne(PaymentCondition::class, 'id', 'payment_cond_id');
    }

    public function tax_info()
    {
        return $this->hasOne(Tax::class, 'id', 'tax_id');
    }
}

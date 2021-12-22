<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;

    protected $table = 'order_detail';

    public static $order_limit = 10;

    public static $ship_order_limit = 10;

    public function order_header()
    {
        return $this->belongsTo(OrderHeader::class, 'order_header_id', 'id');
    }

    public function import_goods()
    {
        return $this->hasOne(ImportGoods::class, 'order_detail_id', 'id');
    }

    public function quote_customer()
    {
        return $this->hasOne(QuoteCustomer::class, 'id', 'quote_id');
    }

    public function supplier()
    {
        return $this->hasOne(Supplier::class, 'id', 'supplier_id');
    }

    public function ship_to_info()
    {
        return $this->hasOne(ShipTo::class, 'id', 'ship_to');
    }

    public function transport()
    {
        return $this->hasOne(Transport::class, 'id', 'ship_by');
    }

    public function request_address()
    {
        return $this->hasOne(Address::class, 'id', 'request_address_id');
    }

    public function send_address()
    {
        return $this->hasOne(Address::class, 'id', 'send_address_id');
    }

    public function tax()
    {
        return $this->hasOne(Tax::class, 'id', 'tax_id');
    }
}

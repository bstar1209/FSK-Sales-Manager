<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuoteCustomer extends Model
{
    use HasFactory;

    protected $table = 'quote_customer';

    public static $history_limit = 10;
    public static $quote_limit = 10;

    public function request_vendors()
    {
        return $this->belongsTo(RequestQuoteVendor::class, 'request_vendor_id', 'id');
    }

    public function order_detail()
    {
        return $this->belongsTo(OrderDetail::class, 'id', 'quote_id');
    }

    public function customer()
    {
        return $this->hasOne(Customer::class, 'id', 'customer_id');
    }

    public function supplier()
    {
        return $this->hasOne(Supplier::class, 'id', 'supplier_id');
    }

    public function import_goods()
    {
        return $this->hasOne(ImportGoods::class, 'quote_id', 'id');
    }
}

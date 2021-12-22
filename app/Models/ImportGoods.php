<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImportGoods extends Model
{
    use HasFactory;

    protected $table = 'import_goods';
    public $timestamps = false;

    public static $shipment_limit = 20;

    public function order_header()
    {
        return $this->belongsTo(OrderHeader::class, 'order_id', 'id');
    }

    public function order_detail()
    {
        return $this->belongsTo(OrderDetail::class, 'order_detail_id', 'id');
    }

    public function quote_customer()
    {
        return $this->belongsTo(QuoteCustomer::class, 'quote_id', 'id');
    }
}

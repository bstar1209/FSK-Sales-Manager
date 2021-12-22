<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class RfqRequest extends Model
{
    use HasFactory;

    protected $table = 'rfq_request';
    protected $appends = ['quote_count'];
    public static $row_limit = 10;

    public function getQuoteCountAttribute()
    {
        if($this->quote_vendors)
            return $this->quote_vendors->count();
        else
            return 0;
    }

    public function quote_vendors()
    {
        return $this->hasMany(RequestQuoteVendor::class, 'rfq_request_id', 'id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    public function detail_info()
    {
        return $this->hasOne(RfqDetail::class, 'id', 'detail_id');
    }

    public function getCreatedAtAttribute($date)
    {
        return date_format(date_create($date),"Y-m-d");
    }

    public function getUpdatedAtAttribute($date)
    {
        return date_format(date_create($date),"Y-m-d");
    }
}

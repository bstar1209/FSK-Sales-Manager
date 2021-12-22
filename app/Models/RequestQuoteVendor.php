<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
class RequestQuoteVendor extends Model
{
    use HasFactory;

    protected $table = 'request_quote_vendor';
    public $timestamps = false;

    public static $history_limit = 9;
    public static $request_quote_vendor_limit = 9;

    public function vendor()
    {
        return $this->hasOne(Supplier::class, 'id', 'supplier_id');
    }

    public function rfq_request()
    {
        return $this->hasOne(RfqRequest::class, 'id', 'rfq_request_id');
    }

    public function quote_customer()
    {
        return $this->hasOne(QuoteCustomer::class, 'request_vendor_id', 'id');
    }

    public function rfq_detail()
    {
        return $this->hasOne(RfqDetail::class, 'id', 'rfq_request_child_id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class, 'rfq_id', 'id')->where('user_id', Auth::id());
    }
}

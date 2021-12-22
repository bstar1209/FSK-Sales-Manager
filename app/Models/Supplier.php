<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'supplier';
    protected $dates = ['deleted_at'];

    public function user_info()
    {
        return $this->hasOne(UserInfo::class, 'id', 'user_info_id');
    }

    public function request_quote()
    {
        return $this->belongsTo(RequestQuoteVendor::class, 'id', 'supplier_id');
    }

    public static function get_daily_rfq_suppliers($id)
    {
        $ids = static::where('daily_rfq', '=', 1)
            ->get()->pluck('id')->toArray();
        return $ids;
    }
}

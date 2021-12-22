<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentCondition extends Model
{
    use HasFactory;

    protected $table = 'payment_condition';
    public $timestamps = false;

    public function user() {
        return $this->belongsTo(UserInfo::class, 'id', 'user_info_id');
    }

    public function common() {
        return $this->hasOne(Common::class, 'id', 'common_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Common extends Model
{
    use HasFactory;

    protected $table = 'common';
    public $timestamps = false;

    public static $common_names = [
        ['前払い現金振り込み', 0],
        ['代引き', 0],
        ['商品到着後、一週間以内現金振り込み', 0],
        ['掛け取引未申請', 1],
        ['掛け取引申請の許可', 1],
        ['掛け取引を許可しない', 1],
        ['CC 5%', 2],
        ['前払い', 2],
        ['CC 10%', 2],
        ['CC 15%', 2],
        ['Paypal 3%', 2]
    ];

    public static $custmer_type = 0;
    public static $payment_flag = 1;
    public static $supplier_type = 2;
}

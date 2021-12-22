<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartLog extends Model
{
    use HasFactory;

    protected $table = 'cart_log';
    public $timestamps = true;

    public function part()
    {
        return $this->hasOne(Parts::class, 'id', 'product_id');
    }
}

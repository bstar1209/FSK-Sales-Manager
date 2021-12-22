<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TableConfig extends Model
{
    use HasFactory;

    protected $table = 'table_config';
    public $timestamps = true;

    public static $names = [
        0 => 'unrfq',
        1 => 'rfq_quote',
        2 => 'rfq_history',
        3 => 'quote',
        4 => 'quote_hitory',
        5 => 'order',
        6 => 'ship_order',
        7 => 'stock',
        8 => 'shipment'
    ];
}

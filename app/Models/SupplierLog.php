<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierLog extends Model
{
    use HasFactory;

    protected $table = 'supplier_log';
    public $timestamps = false;
}

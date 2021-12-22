<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HeaderQuarter extends Model
{
    use HasFactory;

    protected $table = 'headquarters';
    public $timestamps = true;

    public static $language_type = [
        'JP' => 0,
        'EN' => 1
    ];
}

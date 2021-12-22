<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChargeBusiness extends Model
{
    use HasFactory;

    protected $table = 'charge_business';
    public $timestamps = true;

    public function user()
    {
        return $this->belongsTo(User::class, 'staff_id', 'id');
    }
}

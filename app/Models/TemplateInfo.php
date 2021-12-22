<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemplateInfo extends Model
{
    use HasFactory;

    protected $table = 'template_info';
    public $timestamps = true;

    public static $template_type = [
        'Quotation request email to supplier jp' => 0,
        'Quotation request email to supplier en' => 1,
        'Quote email to customer' => 2,
        'Order mail to supplier jp' => 3,
        'Order mail to supplier en' => 4,
        'Shipping email' => 5,
        'Email address change confirmation' => 6,
        'Member registration confirmation email' => 8,
        'Quotation request confirmation email' => 11,
        'Confirmation email for overseas manufacturer product procurement request' => 13,
        'Parts procurement request email for mass production' => 14,
        'Password reset email' => 15,
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'bank_country',
        'routing_number',
        'account_number',
        'bank_currency',
        'account_holder_name',
        'bank_name',
        'bank_street',
        'bank_city',
        'bank_region',
        'swift_number',
        'iban_number',
    ];
}

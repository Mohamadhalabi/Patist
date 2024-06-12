<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $table ='orders';
    protected $fillable = [
        'application_number',
        'publication_date',
        'inventor',
        'invention_title',
        'applicant',
        'user_name',
        'user_phone',
        'user_email',
        'company',
        'relationship',
        'tax_number',
        'address',
        'post_code',
        'country',
        'state',
        'city',
        'service',
        'service_fee',
        'official_fee',
        'translation_quantity',
        'translation_fee',
        'total',
        'link',
        'token',
        'exchange_rate',
    ];

}

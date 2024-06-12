<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnnuityFee extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'official_fee_prices';

    public function item()
    {
        return $this->belongsTo(OfficialFee::class, 'official_fee_id');
    }
}

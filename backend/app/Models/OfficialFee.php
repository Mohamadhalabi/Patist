<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfficialFee extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'official_fee_items';

    public function fee()
    {
        return $this->hasOne(AnnuityFee::class)->with('item');
    }
}

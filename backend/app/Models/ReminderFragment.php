<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReminderFragment extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function reminder()
    {
        return $this->belongsTo(Reminder::class);
    }
}

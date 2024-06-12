<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reminder extends Model
{
    use HasFactory;

    protected $table = 'renewal_reminders';
    protected $guarded = [];

    protected $dateFormat = 'Y-m-d';
    protected $casts = [
        'teams' => 'array',
        'emails' => 'array',
    ];
    public function renewal()
    {
        return $this->belongsTo(Renewal::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function email_history()
    {
        return $this->hasMany(Email::class, 'renewal_id', 'id');
    }

    public function fragments()
    {
        return $this->hasMany(ReminderFragment::class, 'reminder_id', 'id');
    }
}

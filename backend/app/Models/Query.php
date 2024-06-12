<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Query extends Model
{
    use HasFactory;
    protected $fillable = ['query', 'user_id', 'token_id', 'status', 'message'];

    public function token()
    {
        return $this->belongsTo(Token::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
}

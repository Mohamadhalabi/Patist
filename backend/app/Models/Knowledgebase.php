<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Knowledgebase extends Model
{
    use HasFactory;
    protected $table ='knowledge_base';
    protected $fillable = [
        'title',
        'slug',
        'question',
        'question_link',
        'answer',
        'type',
        'project',
    ];
}

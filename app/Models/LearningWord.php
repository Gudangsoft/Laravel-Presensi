<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LearningWord extends Model
{
    protected $fillable = ['word', 'translation', 'phonetic', 'example', 'level', 'category'];
}

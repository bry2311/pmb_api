<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;
    protected $fillable = [
        'date',
        'time',
        'description',
        'forums_id',
        'years_id',
        'students_id',
    ];
}

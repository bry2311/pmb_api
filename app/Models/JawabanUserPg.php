<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JawabanUserPg extends Model
{
    use HasFactory;
    protected $fillable = [
        'answer',
        'number',
        'correctness',
        'cts_id',
        'students_id',
    ];
}

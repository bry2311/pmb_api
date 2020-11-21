<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoalPg extends Model
{
    use HasFactory;
    protected $fillable = [
        'number',
        'question',
        'A',
        'B',
        'C',
        'D',
        'E',
        'key',
        'cts_id',
    ];
}

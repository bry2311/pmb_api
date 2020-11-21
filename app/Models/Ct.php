<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ct extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'date',
        'start',
        'end',
        'duration',
        'years_id',
    ];
}

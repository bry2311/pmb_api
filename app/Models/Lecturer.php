<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lecturer extends Model
{
    use HasFactory;

    protected $fillable = [
        'nip',
        'name',
        'email',
        'status',
        'genre',
        'jabatan',
        'password',
    ];

    protected $hidden = [
        'password',
    ];
}

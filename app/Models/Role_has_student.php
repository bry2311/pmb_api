<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role_has_student extends Model
{
    use HasFactory;
    protected $fillable = [
        'years_id',
        'students_id',
        'roles_id',
    ];
}

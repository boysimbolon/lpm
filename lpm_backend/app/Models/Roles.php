<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Roles extends Model
{
    protected $table = 'tb_roles';
    protected $fillable = [
        'jenis',
    ];
}

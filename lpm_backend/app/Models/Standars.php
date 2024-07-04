<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Standars extends Model
{
    protected $table = 'tb_standars';
    protected $fillable = [
        'jenis_standar',
    ];
}

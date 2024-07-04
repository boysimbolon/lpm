<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class updateAudits extends Model
{
    use HasFactory;

    protected $table = 'tb_audits';
    protected $fillable = [
        'nilai_diri',
        'dok_pendukung',
    ];
}

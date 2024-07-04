<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipeAudits extends Model
{
    use HasFactory;

    protected $table = 'tb_tipe_audits';
    protected $fillable = [
        'tipe_audit',
        'periode',
        'semester',
        'idLPM',
        'id_fakultas'
    ];
    public function fakultas()
    {
        return $this->belongsTo(Fakultas::class, 'id_fakultas');
    }
}

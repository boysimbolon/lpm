<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerStandars extends Model
{
    protected $table = 'tb_per_standars';
    protected $fillable = [
        'id_standars',
        'id_tipe_audits',
        'pernyataan_standar',
        'poin'
    ];
    // Relasi dengan tb_standars
    public function standars()
    {
        return $this->belongsTo(Standars::class,'id_standars');
    }

    // Relasi dengan tb_tipe_audits
    public function tipeAudits()
    {
        return $this->belongsTo(TipeAudits::class,'id_tipe_audits');
    }
}

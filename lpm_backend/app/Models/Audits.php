<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Audits extends Model
{
    use HasFactory;

    protected $table = 'tb_audits';

    protected $fillable = [
        'id_per_standars',
        'id_users',
        'id_tipe_audits',
        'nilai_diri',
        'dok_pendukung',
        'progress',
        'catatan',
        'feedback'
    ];

    // Relasi dengan tb_per_standars
    public function perStandars()
    {
        return $this->belongsTo(PerStandars::class, 'id_per_standars');
    }

    // Relasi dengan tb_users
    public function users()
    {
        return $this->belongsTo(Users::class, 'id_users');
    }

    // Relasi dengan tb_tipe_audits
    public function tipeAudits()
    {
        return $this->belongsTo(TipeAudits::class, 'id_tipe_audits');
    }
}

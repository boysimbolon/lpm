<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Users extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'tb_users';
    protected $fillable = [
        'username',
        'password',
        'name',
        'id_fakultas',
        'id_roles',
    ];

    // Relasi dengan tb_fakultas
    public function fakultas()
    {
        return $this->belongsTo(Fakultas::class, 'id_fakultas');
    }

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Relasi dengan tb_roles
    public function roles()
    {
        return $this->belongsTo(Roles::class, 'id_roles');
    }
}


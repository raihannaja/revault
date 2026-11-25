<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

/*
 * PERTEMUAN 11 - User sebagai "subject" JWT
 *
 * Isi class ini sama dengan User bawaan Laravel, ditambah:
 *   - implements JWTSubject
 *   - dua method di bawah, supaya paket JWT tahu:
 *       getJWTIdentifier()   -> data apa yang disimpan di token sebagai pengenal user (claim "sub")
 *       getJWTCustomClaims() -> data tambahan di dalam payload token (di sini kosong)
 */
class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    // Pengenal user di dalam token: id user
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    // Data tambahan di payload token (kosong)
    public function getJWTCustomClaims()
    {
        return [];
    }
}

<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Passport\HasApiTokens;
class User extends Authenticatable
{
    use Notifiable;
    use HasRoles;
    use HasApiTokens;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'anggota_id', 'no_hp', 'username', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function anggota()
    {
        return $this->belongsTo('Modules\Anggota\Entities\Anggota', 'anggota_id', 'anggota_id');
    }

    // public function getNamaAttribute($value)
    // {
    //     return $this->anggota->nama;
    // }
}

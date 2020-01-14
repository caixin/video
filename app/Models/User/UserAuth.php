<?php

namespace Models\User;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class UserAuth extends Authenticatable implements JWTSubject
{
    use Notifiable;

    protected $table = 'user';

    protected $fillable = [
        'username', 'password',
    ];

    protected $hidden = [
        'token',
        'password',
        'create_ua',
        'create_ip',
        'create_ip_info',
        'login_ip',
        'login_time',
        'active_time',
        'created_by',
        'updated_at',
        'updated_by',
        'deleted_at',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    
    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}

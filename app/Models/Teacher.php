<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
//use Laravel\Passport\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Teacher extends Authenticatable implements JWTSubject
{
    use  Notifiable;
//    protected $fillable = ['name', 'phone', 'password'];
    protected $guarded = [];

    public function scopePhone($query, $param)
    {
        return $query->where('phone', $param);
    }

    // Rest omitted for brevity

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

    public function getDemonstrationVideoAttribute($value)
    {
        return is_null($value) ? defaultUserVideo() : asset($value);
    }

    public function getImageAttribute($value)
    {
        return is_null($value) ? defaultUserImage() : asset($value);
    }

    public function groups()
    {
        return $this->hasMany(Group::class);
    }

}

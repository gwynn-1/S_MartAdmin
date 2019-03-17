<?php

namespace App;

use Backpack\Base\app\Notifications\ResetPasswordNotification as ResetPasswordNotification;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;
    protected $table= "staffs";
    protected $primaryKey = "staff_id";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable =['staff_id', 'full_name', 'email', 'mobile', 'address', 'password', 'remember_token', 'created_at', 'updated_at'];


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        // TODO: Implement getJWTIdentifier() method.
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        // TODO: Implement getJWTCustomClaims() method.
        return [];
    }
    public function setPasswordAttribute($password)
    {
        if ( !empty($password) ) {
            $this->attributes['password'] = bcrypt($password);
        }
    }

    public function getStaff($id){
        $oResult = $this->select(
            "staff_id",
            "email",
            "full_name",
            'mobile',
            'address')->where("staff_id",$id);

        return $oResult->first();
    }
}

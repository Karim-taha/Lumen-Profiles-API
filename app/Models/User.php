<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
// use App\Notifications\CustomVerify Email;

class User extends Model implements AuthenticatableContract, AuthorizableContract, JWTSubject, MustVerifyEmail
{
    use Authenticatable, Authorizable, HasFactory;
    // use Notifiable;

    /* Determine if the user has verified their email address.
    *
    * @return bool
    */
    public function hasVerifiedEmail(){}

    /**
    * Mark the given user's email as verified.
    *
    * @return bool
    */
    public function markEmailAsVerified(){}

    /**
    * Send the email verification notification.
    *
    * @return void
    */
    public function sendEmailVerificationNotification(){}

    public function getEmailForVerification(){}


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name','phone', 'password', 'email', 'verified_email', 'verified_phone'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    // Relation with Article model
    public function article()
    {
        return $this->hasMany(Article::class);
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

    protected $casts = [
        'verified_email' => 'datetime',
        'verified_phone' => 'datetime',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

        /**
     * Check Hashed Password
     *
     * @param string $input
     *
     * @return bool
     */
    public function checkPassword($input)
    {
        if (Hash::check($input, $this->attributes['password'])) {
            return true;
        }
        return false;
    }

        /**
     * Create a new token.
     *
     * @param  \App\Customer   $customer
     * @param   string $type
     * @return string
     */
    public function getJwt()
    {
        $tokenNo = rand();
        $exp_time = time() + (60 * 60 * 24 * (int) env('loginExpireDays'));
        $payload = [
            'sub' => $this->id, // Subject of the token
            'iat' => time(), // Time when JWT was issued.
            'exp' => $exp_time, // Expiration time
            'no' => $tokenNo,
        ];
        $customerToken = new UserToken([
            'user_id' => $this->id,
            'token_no' => $tokenNo,
            'expire_at' => env('loginExpireDays'),
        ]);
        $customerToken->save();
        return JWT::encode($payload, base64_decode(strtr(env('JWT_SECRET'), '-_', '+/')), "HS256");
    }
}

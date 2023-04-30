<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Sanctum\NewAccessToken;
use Laravel\Sanctum\PersonalAccessToken;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, HasUuid, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
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
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Create a new personal access token for the user.
     *
     * @param string $name token name
     * @param array<int, string> $abilities token abilities
     * @return NewAccessToken Object
     */
    public function createToken(string $name, array $abilities = ['*']): NewAccessToken
    {
        /** @var PersonalAccessToken $token */
        $token = $this->tokens()->create([
            'name' => $name,
            'token' => hash('sha256', $plainTextToken = Str::random(40)),
            'abilities' => $abilities,
            'expires_at' => now()->addMinutes(config('sanctum.expiration')),
        ]);

        return new NewAccessToken($token, $token->getKey().'|'.$plainTextToken);
    }

    /**
     * User Has Many Events Relationship
     *
     * @return HasMany Eloquent Relationship
     */
    public function events(): HasMany
    {
        return $this->hasMany(Event::class, 'user_uuid');
    }

    /**
     * Get User UUID
     *
     * @return string user uuid
     */
    public function getUuid(): string
    {
        return $this->uuid;
    }
}

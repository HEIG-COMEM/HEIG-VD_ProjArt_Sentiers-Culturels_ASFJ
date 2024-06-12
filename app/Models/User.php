<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * Class User
 * 
 * This class represents a user of the application.
 * 
 * @property int $id
 * @property string $firstname
 * @property string $lastname
 * @property string $email
 * @property string $password
 * @property int $role_int
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * 
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Route[] $routes
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Rate[] $rates
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Badge[] $badges
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\RouteHistory[] $routesHistory
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'firstname',
        'lastname',
        'email',
        'password',
        'role_int',
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
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function routes()
    {
        return $this->belongsToMany(Route::class);
    }

    public function rates()
    {
        return $this->hasMany(Rate::class);
    }

    public function badges()
    {
        return $this->belongsToMany(Badge::class);
    }

    public function routesHistory()
    {
        return $this->hasMany(RouteHistory::class);
    }
}

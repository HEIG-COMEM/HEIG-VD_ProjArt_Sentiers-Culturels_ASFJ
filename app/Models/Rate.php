<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Rate
 *
 * This class represents a rate that a user can give to a route.
 *
 * @property int $id
 * @property int $rate
 * @property int $user_id
 * @property int $route_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * 
 * @property \App\Models\User $user
 * @property \App\Models\Route $route
 */
class Rate extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['rate', 'user_id', 'route_id'];

    /**
     * Get the user that owns the rate.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the route that the rate belongs to.
     */
    public function route()
    {
        return $this->belongsTo(Route::class);
    }
}

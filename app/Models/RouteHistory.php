<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class RouteHistory
 * 
 * This class represents the history of a route followed by a user.
 * 
 * @property int $id
 * @property int $route_id
 * @property int $user_id
 * @property \Illuminate\Support\Carbon $start_timestamp
 * @property \Illuminate\Support\Carbon|null $end_timestamp
 * 
 * @property \App\Models\Route $route
 * @property \App\Models\User $user
 */
class RouteHistory extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'routes_history';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'route_id',
        'user_id',
        'start_timestamp',
        'end_timestamp',
        'is_interrupted'
    ];

    /**
     * Get the route that this history belongs to.
     */
    public function route()
    {
        return $this->belongsTo(Route::class);
    }

    /**
     * Get the user that this history belongs to.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

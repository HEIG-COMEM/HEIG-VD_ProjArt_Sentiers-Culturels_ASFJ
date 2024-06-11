<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Season
 * 
 * This class represents a season in which a route can be done.
 * 
 * @property int $id
 * @property string $name
 * 
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Route[] $routes
 */
class Season extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];

    /**
     * Get the routes associated with the season.
     */
    public function routes()
    {
        return $this->belongsToMany(Route::class);
    }
}

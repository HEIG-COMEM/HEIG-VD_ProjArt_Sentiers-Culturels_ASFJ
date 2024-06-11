<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Tag
 *
 * This class represents a tag that can be associated with routes and interest points.
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * 
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Route[] $routes
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\InterestPoint[] $interestPoints
 */
class Tag extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];

    /**
     * Get the routes associated with the tag.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function routes()
    {
        return $this->belongsToMany(Route::class);
    }

    /**
     * Get the interest points associated with the tag.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function interestPoints()
    {
        return $this->belongsToMany(InterestPoint::class);
    }
}

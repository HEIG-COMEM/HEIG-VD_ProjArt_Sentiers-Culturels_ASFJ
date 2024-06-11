<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Picture
 *
 * This class represents a picture that can be associated with interest points and routes.
 *
 * @property int $id
 * @property string $title
 * @property string $description
 * @property string $path
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\InterestPoint[] $interestPoints
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Route[] $routes
 */
class Picture extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title', 'description', 'path'];

    /**
     * Get the interest points associated with the picture.
     */
    public function interestPoints()
    {
        return $this->belongsToMany(InterestPoint::class);
    }

    /**
     * Get the routes associated with the picture.
     */
    public function routes()
    {
        return $this->belongsToMany(Route::class);
    }
}

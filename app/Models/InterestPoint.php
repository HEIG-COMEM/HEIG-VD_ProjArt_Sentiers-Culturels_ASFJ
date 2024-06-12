<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * Class InterestPoint
 *
 * This class represents an interest point that can be visited by users.
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property float $lat
 * @property float $long
 * @property string $uuid
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 *
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Picture[] $pictures
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Route[] $routes
 * @property \App\Models\Badge $badge
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Tag[] $tags
 */
class InterestPoint extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'interest_points';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'description', 'lat', 'long'];

    /**
     * Boot function to generate UUID before creating a new interest point.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->uuid = Str::uuid();
        });
    }

    /**
     * Get the pictures associated with the interest point.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function pictures()
    {
        return $this->belongsToMany(Picture::class);
    }

    /**
     * Get the routes associated with the interest point.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function routes()
    {
        return $this->belongsToMany(Route::class);
    }

    /**
     * Get the badge associated with the interest point.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function badge()
    {
        return $this->hasOne(Badge::class);
    }

    /**
     * Get the tags associated with the interest point.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }
}

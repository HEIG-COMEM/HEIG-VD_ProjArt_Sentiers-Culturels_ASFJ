<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * Class Badge
 *
 * This class represents a badge that can be earned by users.
 *
 * @property int $id
 * @property string $name
 * @property string $icon_path
 * @property string|null $description
 * @property int|null $parent_id
 * @property int|null $route_id
 * @property int|null $interest_point_id
 * @property string $uuid
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $users
 * @property \App\Models\Badge|null $parent
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Badge[] $children
 * @property \App\Models\InterestPoint|null $interestPoint
 * @property \App\Models\Route|null $route
 */
class Badge extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'icon_path', // has a default value
        'description', // Nullable
        'parent_id', // Nullable
        'route_id', // Nullable
        'interest_point_id', // Nullable
    ];

    /**
     * Boot the model.
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
     * Get the users that have this badge.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * Get the parent badge.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo(Badge::class, 'parent_id');
    }

    /**
     * Get the child badges.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children()
    {
        return $this->hasMany(Badge::class, 'parent_id');
    }

    /**
     * Get the interest point associated with this badge.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function interestPoint()
    {
        return $this->belongsTo(InterestPoint::class);
    }

    /**
     * Get the route associated with this badge.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function route()
    {
        return $this->belongsTo(Route::class);
    }
}

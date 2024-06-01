<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Badge extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'icon_path', // has a default value
        'description', // Nullable
        'parent_id', // Nullable
        'route_id', // Nullable
        'interest_point_id', // Nullable
    ];

    protected static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->uuid = Str::uuid();
        });
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function parent()
    {
        return $this->belongsTo(Badge::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Badge::class, 'parent_id');
    }

    public function interestPoint()
    {
        return $this->belongsTo(InterestPoint::class);
    }

    public function route()
    {
        return $this->belongsTo(Route::class);
    }
}

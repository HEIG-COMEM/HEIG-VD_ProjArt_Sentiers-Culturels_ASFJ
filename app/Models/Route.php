<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Route extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'duration', 'length', 'path', 'difficulty_id', 'start_lat', 'start_long', 'end_lat', 'end_long'
    ];

    protected static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->uuid = Str::uuid();
        });
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function seasons()
    {
        return $this->belongsToMany(Season::class);
    }

    public function difficulty()
    {
        return $this->belongsTo(Difficulty::class);
    }

    public function pictures()
    {
        return $this->belongsToMany(Picture::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function interestPoints()
    {
        return $this->belongsToMany(InterestPoint::class);
    }

    public function rates()
    {
        return $this->hasMany(Rate::class);
    }

    public function routesHistory()
    {
        return $this->hasMany(RouteHistory::class);
    }
}

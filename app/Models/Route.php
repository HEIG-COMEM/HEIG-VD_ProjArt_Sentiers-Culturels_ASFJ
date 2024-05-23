<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Route extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'duration', 'length', 'difficulty_id', 'start_lat', 'start_long', 'end_lat', 'end_long'
    ];

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function season()
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

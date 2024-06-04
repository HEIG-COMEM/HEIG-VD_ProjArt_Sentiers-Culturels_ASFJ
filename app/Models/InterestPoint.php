<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class InterestPoint extends Model
{
    use HasFactory;

    protected $table = 'interest_points';

    protected $fillable = ['name', 'description', 'lat', 'long'];

    protected static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->uuid = Str::uuid();
        });
    }

    public function pictures()
    {
        return $this->belongsToMany(Picture::class);
    }

    public function routes()
    {
        return $this->belongsToMany(Route::class);
    }

    public function badge()
    {
        return $this->hasOne(Badge::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }
}

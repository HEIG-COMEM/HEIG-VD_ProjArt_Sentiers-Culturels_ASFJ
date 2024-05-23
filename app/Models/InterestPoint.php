<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InterestPoint extends Model
{
    use HasFactory;

    protected $table = 'interest_points';

    protected $fillable = ['name', 'description', 'latitude', 'longitude'];

    public function pictures()
    {
        return $this->belongsToMany(Picture::class);
    }

    public function routes()
    {
        return $this->belongsToMany(Route::class);
    }
}

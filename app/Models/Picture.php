<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Picture extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'path'];

    public function interestPoints()
    {
        return $this->belongsToMany(InterestPoint::class);
    }

    public function routes()
    {
        return $this->belongsToMany(Route::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Difficulty extends Model
{
    use HasFactory;

    protected $table = 'difficulties';

    protected $fillable = ['name'];

    public function routes()
    {
        return $this->hasMany(Route::class);
    }
}
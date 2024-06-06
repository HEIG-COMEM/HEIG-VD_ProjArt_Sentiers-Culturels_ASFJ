<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RouteHistory extends Model
{
    use HasFactory;

    protected $table = 'routes_history';

    protected $fillable = [
        'route_id',
        'user_id',
        'start_timestamp',
        'end_timestamp',
        'is_interrupted'
    ];

    public function route()
    {
        return $this->belongsTo(Route::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

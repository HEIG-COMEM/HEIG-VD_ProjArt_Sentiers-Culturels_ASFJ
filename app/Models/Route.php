<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;

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

    public function createRoutePath()
    {
        $interestPoints = $this->interestPoints;
        if ($interestPoints->isEmpty()) {
            return [
                'error' => 'No interest points'
            ];
        }
        $data = [
            'coordinates' => $interestPoints->map(function ($interestPoint) {
                return [$interestPoint->long, $interestPoint->lat];
            })->toArray(),
            'instructions_format' => 'html',
            'language' => 'fr'
        ];

        $path = Http::withHeaders([
            'Authorization' => env('OPENROUTESERVICE_API_KEY')
        ])->post('https://api.openrouteservice.org/v2/directions/foot-walking/geojson', $data);

        $resp = $path->json();
        $this->path = json_encode($resp);

        if (!$this->path) {
            return [
                'error' => 'Unable to create path'
            ];
        }

        if (isset($path->json()['error'])) {
            return [
                'error' => $path->json()['error']['message']
            ];
        }

        $this->duration = $path->json()['features'][0]['properties']['summary']['duration'];
        $this->length = $path->json()['features'][0]['properties']['summary']['distance'];

        $this->save();

        return $resp;
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

    public function badge()
    {
        return $this->hasOne(Badge::class);
    }
}

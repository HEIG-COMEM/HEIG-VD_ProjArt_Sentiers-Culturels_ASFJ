<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;

/**
 * Class Route
 * 
 * This class represents a route that can be followed by users.
 * 
 * @property int $id
 * @property string $uuid
 * @property string $name
 * @property string|null $description
 * @property int $duration
 * @property int $length
 * @property string $path
 * @property int $difficulty_id
 * @property float $start_lat
 * @property float $start_long
 * @property float $end_lat
 * @property float $end_long
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * 
 * @property \App\Models\Difficulty $difficulty
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Tag[] $tags
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Season[] $seasons
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Picture[] $pictures
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $users
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\InterestPoint[] $interestPoints
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Rate[] $rates
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\RouteHistory[] $routesHistory
 * @property \App\Models\Badge|null $badge
 */
class Route extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description', 'duration', 'length', 'path', 'difficulty_id', 'start_lat', 'start_long', 'end_lat', 'end_long'
    ];

    /**
     * Boot function to generate UUID before creating a new route.
     */
    protected static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->uuid = Str::uuid();
        });
    }

    /**
     * Create the route path using OpenRouteService API.
     *
     * @return array|mixed
     */
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
            'instructions_format' => 'text',
            'language' => 'fr-fr'
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

    /**
     * Get the tags associated with the route.
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    /**
     * Get the seasons associated with the route.
     */
    public function seasons()
    {
        return $this->belongsToMany(Season::class);
    }

    /**
     * Get the difficulty level associated with the route.
     */
    public function difficulty()
    {
        return $this->belongsTo(Difficulty::class);
    }

    /**
     * Get the pictures associated with the route.
     */
    public function pictures()
    {
        return $this->belongsToMany(Picture::class);
    }

    /**
     * Get the users who have favorited the route.
     */
    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * Get the interest points associated with the route.
     */
    public function interestPoints()
    {
        return $this->belongsToMany(InterestPoint::class);
    }

    /**
     * Get the rates associated with the route.
     */
    public function rates()
    {
        return $this->hasMany(Rate::class);
    }

    /**
     * Get the route history associated with the route.
     */
    public function routesHistory()
    {
        return $this->hasMany(RouteHistory::class);
    }

    /**
     * Get the badge associated with the route.
     */
    public function badge()
    {
        return $this->hasOne(Badge::class);
    }
}

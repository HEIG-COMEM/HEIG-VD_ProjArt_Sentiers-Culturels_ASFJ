<?php

namespace Database\Seeders;

use App\Models\InterestPoint;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Route;
use App\Models\Difficulty;
use App\Models\Picture;
use App\Helpers\JsonHelper;
use App\Models\Badge;
use App\Models\Tag;
use App\Models\Season;

/**
 * Class RoutesTableSeeder
 * 
 * This class is responsible for seeding the routes table in the database.
 */
class RoutesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * This method is responsible for seeding the routes table in the database.
     * It reads a JSON file containing route data, creates or updates the routes in the database,
     * and establishes relationships between routes, pictures, badges, interest points, tags, and seasons.
     * 
     * @return void
     */
    public function run(): void
    {
        try {
            $data = JsonHelper::readJson('/dataset.json');

            $routes = collect($data['sentiers'])
                ->values()
                ->all();

            foreach ($routes as $route) {
                $difficulty = Difficulty::where('name', $route['difficulty'])->first();

                $newRoute = Route::updateOrCreate([
                    'name' => $route['name']
                ], [
                    'name' => $route['name'],
                    'description' => $route['description'],
                    'duration' => 0,
                    'length' => 0,
                    'start_lat' => $route['start']['coordinate'][0],
                    'start_long' => $route['start']['coordinate'][1],
                    'end_lat' => $route['end']['coordinate'][0],
                    'end_long' => $route['end']['coordinate'][1],
                    'difficulty_id' => $difficulty->id
                ]);

                $routePicture = Picture::updateOrCreate([
                    'title' => $route['picture']['title']
                ], [
                    'title' => $route['picture']['title'],
                    'description' => $route['picture']['description'],
                    'path' => $route['picture']['path']
                ]);

                $newRoute->pictures()->syncWithoutDetaching($routePicture);

                $tags = collect($route['tags'])
                    ->map(function ($tag) {
                        return Tag::firstOrCreate(['name' => $tag]);
                    });

                $tags->each(function ($tag) use ($newRoute) {
                    $newRoute->tags()->syncWithoutDetaching($tag);
                });

                $newRoute->seasons()->syncWithoutDetaching(Season::where('name', $route['season'])->first());

                // As description and icon_path are not mandatory, we need to check if they exist
                $newBadge = Badge::updateOrCreate([
                    'name' => $route['badge']['name']
                ], [
                    'parent_id' => Badge::where('name', $route['badge']['family'])->first()->id ?? null,
                    'name' => $route['badge']['name'],
                    'description' => $route['badge']['description'] ?? null,
                    'route_id' => $newRoute->id
                ]);

                if (isset($interestPoint['badge']['icon_path'])) {
                    $newBadge->icon_path = $interestPoint['badge']['icon_path'];
                    $newBadge->save();
                }

                $count = 1;
                foreach ($route['interest_points'] as $interestPoint) {
                    $picture = Picture::updateOrCreate([
                        'title' => $interestPoint['picture']['title']
                    ], [
                        'title' => $interestPoint['picture']['title'],
                        'description' => $interestPoint['picture']['description'],
                        'path' => $interestPoint['picture']['path']
                    ]);

                    $newInterestPoint = InterestPoint::updateOrCreate([
                        'name' => $interestPoint['name']
                    ], [
                        'name' => $interestPoint['name'],
                        'description' => $interestPoint['description'],
                        'lat' => $interestPoint['coordinate'][0],
                        'long' => $interestPoint['coordinate'][1],
                    ]);

                    // As description and icon_path are not mandatory, we need to check if they exist
                    $newBadge = Badge::updateOrCreate([
                        'name' => $interestPoint['badge']['name']
                    ], [
                        'parent_id' => Badge::where('name', $interestPoint['badge']['family'])->first()->id ?? null,
                        'name' => $interestPoint['badge']['name'],
                        'description' => $interestPoint['badge']['description'] ?? null,
                        'interest_point_id' => $newInterestPoint->id
                    ]);

                    if (isset($interestPoint['badge']['icon_path'])) {
                        $newBadge->icon_path = $interestPoint['badge']['icon_path'];
                        $newBadge->save();
                    }

                    $newInterestPoint->pictures()->syncWithoutDetaching($picture);
                    $newRoute->interestPoints()->syncWithoutDetaching([$newInterestPoint->id => ['order' => $count++]]);

                    // Find tags who matches the array of tag name or create them
                    $tags = collect($interestPoint['tags'])
                        ->map(function ($tag) {
                            return Tag::firstOrCreate(['name' => $tag]);
                        });

                    $tags->each(function ($tag) use ($newInterestPoint) {
                        $newInterestPoint->tags()->syncWithoutDetaching($tag);
                    });
                }

                $newRoute->createRoutePath();
            }
        } catch (\Exception $e) {
            $this->command->error($e->getMessage());
        }
    }
}

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

class RoutesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $NB_ROUTES = 1;
        // $MIN_TOUR_POINTS = 2;
        // $interestPoints = InterestPoint::all();
        // $difficulty = Difficulty::all();

        // for ($i = 0; $i < $NB_ROUTES; $i++) {
        //     $NB_TOUR_POINTS = rand($MIN_TOUR_POINTS, count($interestPoints));
        //     $tourPoints = $interestPoints->random($NB_TOUR_POINTS);

        //     $route = new Route();
        //     $route->name = 'Route ' . $i;
        //     $route->description = 'Description de la route ' . $i;
        //     $route->duration = rand(1, 120);
        //     $route->length = rand(1, 10);

        //     $route->start_lat = rand(0, 100);
        //     $route->start_long = rand(0, 100);
        //     $route->end_lat = rand(0, 100);
        //     $route->end_long = rand(0, 100);

        //     $route->difficulty()->associate($difficulty->random());
        //     $route->save();

        //     foreach ($tourPoints as $key => $tourPoint) {
        //         $route->interestPoints()->attach($tourPoint, ['order' => ($key + 1)]);
        //     }
        // }

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

                    // dd($newInterestPoint);

                    Badge::updateOrCreate([
                        'name' => $interestPoint['badge']['name']
                    ], [
                        'parent_id' => Badge::where('name', $interestPoint['badge']['family'])->first()->id,
                        'name' => $interestPoint['badge']['name'],
                        // 'icon_path' => $interestPoint['badge']['icon_path'], // TODO : Fix this :')
                        isset($interestPoint['badge']['icon_path']) ? 'icon_path' : null => $interestPoint['badge']['icon_path'] ? $interestPoint['badge']['icon_path'] : null,
                        'interest_point_id' => $interestPoint->id
                    ]);



                    $interestPoint->pictures()->syncWithoutDetaching($picture);
                    $newRoute->interestPoints()->syncWithoutDetaching([$newInterestPoint->id => ['order' => $count++]]);
                }
            }
        } catch (\Exception $e) {
            $this->command->error($e->getMessage());
        }
    }
}

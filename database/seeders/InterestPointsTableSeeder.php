<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\InterestPoint;
use App\Models\Badge;
use App\Models\Picture;
use App\Helpers\JsonHelper;

class InterestPointsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {
            $data = JsonHelper::readJson('/dataset.json');

            $interestPoints = collect($data['interest_points'])
                ->values()
                ->all();

            foreach ($interestPoints as $interestPoint) {
                $picture = Picture::updateOrCreate([
                    'title' => $interestPoint['picture']['title']
                ], [
                    'title' => $interestPoint['picture']['title'],
                    'description' => $interestPoint['picture']['description'],
                    'path' => $interestPoint['picture']['path']
                ]);

                $interestPoint = InterestPoint::updateOrCreate([
                    'name' => $interestPoint['name']
                ], [
                    'name' => $interestPoint['name'],
                    'description' => $interestPoint['description'],
                    'lat' => $interestPoint['coordinate'][0],
                    'long' => $interestPoint['coordinate'][1],
                ]);

                Badge::updateOrCreate([
                    'name' => $interestPoint['badge']['name']
                ], [
                    'parent_id' => Badge::where('name', $interestPoint['badge']['family'])->first()->id ?? null,
                    'name' => $interestPoint['badge']['name'],
                    'icon_path' => $interestPoint['badge']['icon_path'],
                    'interest_point_id' => $interestPoint->id
                ]);

                $interestPoint->picture()->associate($picture);
                $interestPoint->save();
            }
        } catch (\Exception $e) {
            $this->command->error($e->getMessage());
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Season;
use App\Helpers\JsonHelper;

/**
 * Class SeasonsTableSeeder
 * 
 * This class is responsible for seeding the seasons table in the database.
 */
class SeasonsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * This method is responsible for seeding the seasons table in the database.
     * It reads a JSON file containing season data and creates or updates the seasons in the database.
     * 
     * @return void
     */
    public function run(): void
    {
        try {
            $data = JsonHelper::readJson('/dataset.json');
            $seasons = collect($data['seasons'])
                ->values()
                ->all();

            foreach ($seasons as $season) {
                Season::updateOrCreate([
                    'name' => $season
                ]);
            }
        } catch (\Exception $e) {
            $this->command->error($e->getMessage());
        }
    }
}

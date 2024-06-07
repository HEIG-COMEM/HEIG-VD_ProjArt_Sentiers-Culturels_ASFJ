<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Season;
use App\Helpers\JsonHelper;

class SeasonsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $SAISONS = [
        //     ['name' => 'Printemps'],
        //     ['name' => 'Été'],
        //     ['name' => 'Automne'],
        //     ['name' => 'Hiver'],
        // ];

        // foreach ($SAISONS as $saison) {
        //     Season::create($saison);
        // }

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

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Difficulty;
use App\Helpers\JsonHelper;

class DifficultiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $DIFFICULTIES = [
        //     ['name' => 'Facile', 'level' => 1],
        //     ['name' => 'Moyen', 'level' => 2],
        //     ['name' => 'Difficile', 'level' => 3],
        //     ['name' => 'Très difficile', 'level' => 4],
        // ];

        // foreach ($DIFFICULTIES as $difficulty) {
        //     Difficulty::create($difficulty);
        // }

        try {
            $data = JsonHelper::readJson('/dataset.json');

            $difficulties = collect($data['difficulties'])
                ->values()
                ->all();

            foreach ($difficulties as $difficulty) {
                Difficulty::updateOrCreate([
                    'level' => $difficulty['level']
                ], [
                    'name' => $difficulty['name'],
                    'level' => $difficulty['level']
                ]);
            }
        } catch (\Exception $e) {
            $this->command->error($e->getMessage());
        }
    }
}

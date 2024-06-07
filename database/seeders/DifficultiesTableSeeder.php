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

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Tag;
use App\Helpers\JsonHelper;

class TagsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $TAGS = [
        //     'Street-art', 'Architecture', 'Historique', 'Nature', 'Monument', 'Bord du lac', 'Forêt', 'Château', 'Patrimoine', 'Point de vue'
        // ];
        // $TAGS = array_unique($TAGS);
        // foreach ($TAGS as $tag) {
        //     Tag::create(['name' => $tag]);
        // }

        try {
            $data = JsonHelper::readJson('/dataset.json');

            $tags = collect($data['tags'])
                ->values()
                ->all();

            foreach ($tags as $tag) {
                Tag::updateOrCreate([
                    'name' => $tag
                ]);
            }
        } catch (\Exception $e) {
            $this->command->error($e->getMessage());
        }
    }
}

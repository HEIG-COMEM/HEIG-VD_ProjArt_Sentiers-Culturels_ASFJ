<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Tag;
use App\Helpers\JsonHelper;

/**
 * Class TagsTableSeeder
 * 
 * This class is responsible for seeding the tags table in the database.
 */
class TagsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * This method is responsible for seeding the tags table in the database.
     * It reads a JSON file containing tag data and creates or updates the tags in the database.
     * 
     * @return void
     */
    public function run(): void
    {
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

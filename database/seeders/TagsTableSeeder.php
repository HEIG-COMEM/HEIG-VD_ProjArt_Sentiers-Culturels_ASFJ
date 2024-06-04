<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Tag;

class TagsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $TAGS = [
            'Street-art', 'Architecture', 'Historique', 'Nature', 'Monument', 'Bord du lac', 'Forêt', 'Château', 'Patrimoine', 'Point de vue'
        ];
        $TAGS = array_unique($TAGS);
        foreach ($TAGS as $tag) {
            Tag::create(['name' => $tag]);
        }
    }
}

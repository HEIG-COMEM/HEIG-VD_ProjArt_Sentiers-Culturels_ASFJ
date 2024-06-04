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
        $TAGS = ['Sentier', 'Bord du lac', 'Montagne', 'Forêt', 'Ville', 'Campagne', 'Plage', 'Désert', 'Sentier de montagne', 'Sentier de forêt', 'Sentier de ville', 'Sentier de campagne', 'Sentier de plage', 'Sentier de désert', 'Sentier de bord de lac', 'Sentier de street art', 'Sentier d\'architecture', 'Sentier de montagne', 'Sentier de forêt', 'Sentier de ville'];
        $TAGS = array_unique($TAGS);
        foreach ($TAGS as $tag) {
            Tag::create(['name' => $tag]);
        }
    }
}

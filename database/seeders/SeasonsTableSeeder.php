<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Season;

class SeasonsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $SAISONS = [
            ['name' => 'Printemps'],
            ['name' => 'Été'],
            ['name' => 'Automne'],
            ['name' => 'Hiver'],
        ];

        foreach ($SAISONS as $saison) {
            Season::create($saison);
        }
    }
}

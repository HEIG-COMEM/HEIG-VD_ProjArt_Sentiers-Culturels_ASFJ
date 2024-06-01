<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Badge;

class BadgesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $PARENT_BADGES = [
            [
                'name' => 'Régions',
                'icon_path' => 'region.png'
            ], [
                'name' => 'Villes',
                'icon_path' => 'villes.png'
            ], [
                'name' => 'Chateaux',
                'icon_path' => 'chateaux.png'
            ], [
                'name' => 'Architecture',
                'icon_path' => 'architecture.png'
            ],
        ];

        $PARENT_BADGES_INSTANCES = [];

        foreach ($PARENT_BADGES as $badge) {
            $PARENT_BADGES_INSTANCES[] = Badge::create($badge);
        }

        $REGIONS_BADGES = [
            [
                'name' => 'Lavaux',
                'icon_path' => 'lavaux.png',
                'parent_id' => $PARENT_BADGES_INSTANCES[0]->id
            ], [
                'name' => 'Nord Vaudois',
                'icon_path' => 'nord-vaudois.png',
                'parent_id' => $PARENT_BADGES_INSTANCES[0]->id
            ], [
                'name' => 'La Côte',
                'icon_path' => 'la-cote.png',
                'parent_id' => $PARENT_BADGES_INSTANCES[0]->id
            ], [
                'name' => 'Riviera',
                'icon_path' => 'riviera.png',
                'parent_id' => $PARENT_BADGES_INSTANCES[0]->id
            ],
        ];

        $REGIONS_BADGES_INSTANCES = [];

        foreach ($REGIONS_BADGES as $badge) {
            $REGIONS_BADGES_INSTANCES[] = Badge::create($badge);
        }
    }
}

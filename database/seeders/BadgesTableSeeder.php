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
            ], [
                'name' => 'Villes',
            ], [
                'name' => 'Chateaux',
            ], [
                'name' => 'Architecture',
            ],
        ];

        $PARENT_BADGES_INSTANCES = [];

        foreach ($PARENT_BADGES as $badge) {
            $PARENT_BADGES_INSTANCES[] = Badge::create($badge);
        }

        $REGIONS_BADGES = [
            [
                'name' => 'Lavaux',
                'description' => 'Le Lavaux est une région viticole de Suisse située sur la rive nord du lac Léman, entre Lausanne et Vevey.',
                'parent_id' => $PARENT_BADGES_INSTANCES[0]->id,
                'interest_point_id' => 1,
            ], [
                'name' => 'Nord Vaudois',
                'description' => 'Le Nord Vaudois est une région du canton de Vaud en Suisse.',
                'parent_id' => $PARENT_BADGES_INSTANCES[0]->id,
                'route_id' => 1,
            ], [
                'name' => 'La Côte',
                'description' => 'La Côte est une région viticole de Suisse située sur la rive nord du lac Léman, entre Lausanne et Nyon.',
                'parent_id' => $PARENT_BADGES_INSTANCES[0]->id
            ], [
                'name' => 'Riviera',
                'description' => 'La Riviera est une région du canton de Vaud en Suisse.',
                'parent_id' => $PARENT_BADGES_INSTANCES[0]->id
            ],
        ];

        $REGIONS_BADGES_INSTANCES = [];

        foreach ($REGIONS_BADGES as $badge) {
            $REGIONS_BADGES_INSTANCES[] = Badge::create($badge);
        }
    }
}

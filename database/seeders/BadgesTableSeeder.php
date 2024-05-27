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
        $BADGES = [
            [
                'name' => 'Première Route',
                'icon_path' => 'first_route.png',
                'description' => 'Vous avez fait votre première randonnée !',
            ],
            [
                'name' => '5 Routes',
                'icon_path' => '5_routes.png',
                'description' => 'Vous avez fait 5 randonnées !',
            ],
            [
                'name' => '10 Routes',
                'icon_path' => '10_routes.png',
                'description' => 'Vous avez fait 10 randonnées !',
            ],
            [
                'name' => '20 Routes',
                'icon_path' => '20_routes.png',
                'description' => 'Vous avez fait 20 randonnées !',
            ],
            [
                'name' => '50 Routes',
                'icon_path' => '50_routes.png',
                'description' => 'Vous avez fait 50 randonnées !',
            ],
            [
                'name' => '100 Routes',
                'icon_path' => '100_routes.png',
                'description' => 'Vous avez fait 100 randonnées !',
            ],
        ];

        foreach ($BADGES as $badge) {
            Badge::create($badge);
        }
    }
}

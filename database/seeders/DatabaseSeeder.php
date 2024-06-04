<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(UsersTableSeeder::class);

        $this->call(DifficultiesTableSeeder::class);
        $this->call(SeasonsTableSeeder::class);
        $this->call(TagsTableSeeder::class);

        $this->call(InterestPointsTableSeeder::class);
        $this->call(RoutesTableSeeder::class);

        // CUSOTM ROUTES SEEDERS
        $this->call(RouteRenardSeeder::class);
        $this->call(RouteLausanneVilleNatureSeeder::class);
        // END CUSTOM ROUTES SEEDERS

        $this->call(RatesTableSeeder::class);

        $this->call(InterestPointTagSeeder::class);

        $this->call(PicturesTableSeeder::class);
        $this->call(InterestPointPictureTableSeeder::class);
        $this->call(PictureRouteTableSeeder::class);

        $this->call(RoutesHistoryTableSeeder::class);
        $this->call(RouteSeasonSeeder::class);
        $this->call(RouteTagSeed::class);
        $this->call(RouteUserSeeder::class);

        $this->call(BadgesTableSeeder::class);
        $this->call(BadgeUserTableSeeder::class);
    }
}

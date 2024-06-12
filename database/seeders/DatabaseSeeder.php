<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

/**
 * Class DatabaseSeeder
 * 
 * This class is responsible for seeding the database.
 * It calls the seeders for the tables in the database.
 */
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database by calling the seeders for the tables in the database.
     * 
     * The seeders can be called unlimited times.
     * The construction of each seeder works to make sure that if the data is aleady in the database,
     * it will not be duplicated but rather updated so that the database remains consistent.
     * 
     * @return void
     */
    public function run(): void
    {
        $this->call(UsersTableSeeder::class);

        $this->call(DifficultiesTableSeeder::class);
        $this->call(SeasonsTableSeeder::class);
        $this->call(BadgesTableSeeder::class);
        // $this->call(TagsTableSeeder::class);
        $this->call(RoutesTableSeeder::class);
    }
}

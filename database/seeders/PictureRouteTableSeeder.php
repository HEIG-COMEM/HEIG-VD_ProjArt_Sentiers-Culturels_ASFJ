<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Picture;
use App\Models\Route;

class PictureRouteTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $routes = Route::all();
        $pictures = Picture::all();

        foreach ($routes as $route) {
            $route->pictures()->attach($pictures->random()->id);
        }
    }
}

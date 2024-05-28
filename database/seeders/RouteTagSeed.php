<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Route;
use App\Models\Tag;

class RouteTagSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $routes = Route::all();
        $tags = Tag::all();

        $countTags = $tags->count();

        foreach ($routes as $route) {
            $route->tags()->attach($tags->random(rand(1, $countTags))->pluck('id'));
        }
    }
}

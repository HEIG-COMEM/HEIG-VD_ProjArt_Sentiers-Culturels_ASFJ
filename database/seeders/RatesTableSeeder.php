<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Route;
use App\Models\Rate;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RatesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $routes = Route::all();

        foreach ($users as $user) {
            foreach ($routes as $route) {
                $rate = new Rate();
                $rate->rate = rand(1, 5);
                $rate->user()->associate($user);
                $rate->route()->associate($route);
                $rate->save();
            }
        }
    }
}

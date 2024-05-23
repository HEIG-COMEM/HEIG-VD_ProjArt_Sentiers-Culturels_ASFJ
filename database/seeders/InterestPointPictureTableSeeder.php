<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Picture;
use App\Models\InterestPoint;

class InterestPointPictureTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $interestPoints = InterestPoint::all();
        $pictures = Picture::all();

        foreach ($interestPoints as $interestPoint) {
            $interestPoint->pictures()->attach($pictures->random()->id);
        }
    }
}

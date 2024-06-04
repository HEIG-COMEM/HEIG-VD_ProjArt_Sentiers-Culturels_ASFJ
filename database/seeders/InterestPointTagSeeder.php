<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\InterestPoint;
use App\Models\Tag;

class InterestPointTagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $interestPoints = InterestPoint::all();
        $tags = Tag::all();

        foreach ($interestPoints as $interestPoint) {
            $interestPoint->tags()->attach($tags->random(rand(1, 4))->pluck('id'));
        }
    }
}

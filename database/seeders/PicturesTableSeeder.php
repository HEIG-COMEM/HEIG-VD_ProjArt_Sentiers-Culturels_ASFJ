<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use App\Models\Picture;

class PicturesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $files = File::files(public_path('storage/pictures'));

        foreach ($files as $file) {
            $filename = pathinfo($file, PATHINFO_FILENAME);
            $extension = pathinfo($file, PATHINFO_EXTENSION);

            $picture = new Picture();
            $picture->title = 'Title of ' . $filename;
            $picture->description = 'Description of ' . $filename;
            $picture->path = $filename . '.' . $extension;
            $picture->save();
        }
    }
}

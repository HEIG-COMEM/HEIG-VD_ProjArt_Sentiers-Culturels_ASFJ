<?php

namespace App\Helpers;

use Illuminate\Support\Facades\File;

class JsonHelper
{
    public static function readJson($filePath)
    {
        $jsonPath = database_path($filePath);

        if (!File::exists($jsonPath)) {
            throw new \Exception("File not found: {$jsonPath}");
        }

        $jsonData = File::get($jsonPath);
        return json_decode($jsonData, true);
    }
}

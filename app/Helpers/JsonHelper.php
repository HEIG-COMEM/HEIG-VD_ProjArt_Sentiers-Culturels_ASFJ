<?php

namespace App\Helpers;

use Illuminate\Support\Facades\File;

/**
 * This class provides helper methods for working with JSON data.
 */
class JsonHelper
{
    /**
     * Reads a JSON file and returns its contents as an associative array.
     *
     * @param string $filePath The path to the JSON file.
     * @return array The contents of the JSON file as an associative array.
     * @throws \Exception If the JSON file is not found.
     */
    public static function readJson($filePath): array
    {
        $jsonPath = database_path($filePath);

        if (!File::exists($jsonPath)) {
            throw new \Exception("File not found: {$jsonPath}");
        }

        $jsonData = File::get($jsonPath);
        return json_decode($jsonData, true);
    }
}

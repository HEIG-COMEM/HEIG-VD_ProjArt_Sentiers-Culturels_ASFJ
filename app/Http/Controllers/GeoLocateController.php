<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * Class GeoLocateController
 * 
 * This class is used to calculate the distance between two points on the Earth's surface.
 */
class GeoLocateController extends Controller
{
    /**
     * Calculate the distance between two sets of coordinates.
     *
     * @param float $lat1 The latitude of the first coordinate.
     * @param float $lon1 The longitude of the first coordinate.
     * @param float $lat2 The latitude of the second coordinate.
     * @param float $lon2 The longitude of the second coordinate.
     * @param string $unit The unit of measurement for the distance (default is "K" for kilometers).
     * @return float The calculated distance between the two coordinates.
     */
    public static function distance($lat1, $lon1, $lat2, $lon2, $unit = "K"): float
    {
        if (($lat1 == $lat2) && ($lon1 == $lon2)) {
            return 0;
        } else {
            $theta = $lon1 - $lon2;
            $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
            $dist = acos($dist);
            $dist = rad2deg($dist);
            $miles = $dist * 60 * 1.1515;
            $unit = strtoupper($unit);

            if ($unit == "K") {
                return ($miles * 1.609344);
            } else if ($unit == "N") {
                return ($miles * 0.8684);
            } else {
                return $miles;
            }
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\InterestPoint;
use App\Models\Route;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RouteRenardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $INTEREST_POINTS = [
            [
                "name" => "Tour Haldimand",
                "lat" => 46.5051237,
                "long" => 6.6415143,
            ],
            [
                "name" => "Clairière du Denantou",
                "lat" => 46.5059987,
                "long" => 6.6419971,
            ],
            [
                "name" => "Parc du Denantou",
                "lat" => 46.5069033,
                "long" => 6.6400819,
            ],
            [
                "name" => "Pont du Diable",
                "lat" => 46.5093843,
                "long" => 6.6392935,
            ],
            [
                "name" => "Château-Sec",
                "lat" => 46.5129244,
                "long" => 6.6451995,
            ],
            [
                "name" => "Bonne-Espérance",
                "lat" => 46.5131903,
                "long" => 6.6482945,
            ],
            [
                "name" => "Léman-Chissiez",
                "lat" => 46.5137486,
                "long" => 6.6517374,
            ],
            [
                "name" => "Perraudettaz",
                "lat" => 46.5148926,
                "long" => 6.6543675,
            ],
            [
                "name" => "Chemin de la Vuachère",
                "lat" => 46.5186499,
                "long" => 6.6533638,
            ],
            [
                "name" => "Levant",
                "lat" => 46.5185476,
                "long" => 6.6521118,
            ],
            [
                "name" => "Belvédère de la Gottetaz",
                "lat" => 46.5224776,
                "long" => 6.6500415,
            ],
            [
                "name" => "Gottetaz Martin Luther King",
                "lat" => 46.5229053,
                "long" => 6.6510842,
            ],
            [
                "name" => "Clairière de la Gottetaz",
                "lat" => 46.5234779,
                "long" => 6.6509449,
            ],
            [
                "name" => "Passerelle de la Gottetaz",
                "lat" => 46.5235635,
                "long" => 6.6513151,
            ],
            [
                "name" => "Pont de Chailly",
                "lat" => 46.5259626,
                "long" => 6.6510362,
            ],
            [
                "name" => "Dérivation du Flon",
                "lat" => 46.5292667,
                "long" => 6.6497583,
            ],
            [
                "name" => "Ferme Aebi",
                "lat" => 46.5309118,
                "long" => 6.6492498,
            ],
            [
                "name" => "Vallon de Valmont",
                "lat" => 46.5353735,
                "long" => 6.6564703,
            ],
            [
                "name" => "Belvédère de Coteau-Fleuri",
                "lat" => 46.535617,
                "long" => 6.6594368,
            ],
            [
                "name" => "Passerelle de Valmont",
                "lat" => 46.5371353,
                "long" => 6.6596988,
            ],
            [
                "name" => "Chemin des Eterpeys",
                "lat" => 46.5373704,
                "long" => 6.6607676,
            ],
            [
                "name" => "Praz-Séchaud",
                "lat"  => 46.5402019,
                "long" => 6.6640623,
            ]
        ];

        $first_id = null;
        $last_id = null;

        foreach ($INTEREST_POINTS as $interestPoint) {
            $interestPoint['description'] = 'Description de ' . $interestPoint['name'];
            InterestPoint::create($interestPoint);

            if ($first_id === null) {
                $first_id = InterestPoint::orderBy('id', 'desc')->first()->id;
            }
            $last_id = InterestPoint::orderBy('id', 'desc')->first()->id;
        }

        $PATH = file_get_contents(public_path('storage/mock/route/renard/path.geojson'));

        // get InterestPoint between first and last
        $interestPoints = InterestPoint::whereBetween('id', [$first_id, $last_id])->get();

        // create route
        $route = Route::create([
            'name' => 'Renard',
            'description' => 'Lors de cette marche de 8 km (environ 3h), vous partirez des hauts de la ville et suivrez les pas de renards marqués au sol, qui vous mèneront jusqu’au lac. Tout au long de votre balade, vous serez amenés à emprunter des passerelles, des escaliers et des chemins forestiers.',
            'duration' => 120,
            'length' => 5000,
            'start_lat' => $interestPoints->first()->lat,
            'start_long' => $interestPoints->first()->long,
            'end_lat' => $interestPoints->last()->lat,
            'end_long' => $interestPoints->last()->long,
            'path' => $PATH,
            'difficulty_id' => 1,
        ]);

        $i = 1;
        foreach ($interestPoints as $interestPoint) {
            $route->interestPoints()->attach($interestPoint, ['order' => $i++]);
        }
    }
}

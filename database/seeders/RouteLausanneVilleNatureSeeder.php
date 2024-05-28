<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\InterestPoint;
use App\Models\Route;

class RouteLausanneVilleNatureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $INTEREST_POINTS = [
            [
                "name" => "De la lutte à l'harmonie",
                "lat" => 46.56519775991777,
                "long" => 6.690722329650185,
            ],
            [
                "name" => "Une forêt de loisirs",
                "lat" => 46.56622898418815,
                "long" => 6.685089114585368,
            ],
            [
                "name" => "Une gestion attentive",
                "lat" => 46.56388277619843,
                "long" => 6.660686658219817,
            ],
            [
                "name" => "De la forêt aux champs",
                "lat" => 46.54818374330063,
                "long" => 6.654718107900963,
            ],
            [
                "name" => "L'eau et la roue",
                "lat" => 46.54666741029686,
                "long" => 6.645791574738353,
            ],
            [
                "name" => "A court d'eau",
                "lat" => 46.54436233150523,
                "long" => 6.642494853090881,
            ],
            [
                "name" => "Villégiature lausannoise",
                "lat" => 46.53853220637824,
                "long" => 6.64283116612485,
            ],
            [
                "name" => "La ville s'étend",
                "lat" => 46.53446704409216,
                "long" => 6.643983701454687,
            ],
            [
                "name" => "Un havre de paix",
                "lat" => 46.52826280272213,
                "long" => 6.637512257805412,
            ],
            [
                "name" => "Une nature dérangeante",
                "lat" => 46.5273704771154,
                "long" => 6.63780334601887,
            ],
            [
                "name" => "Départ",
                "lat" => 46.54951470400788,
                "long" => 6.656993270684055,
            ],
        ];
        // reverse the array to have the correct order
        $INTEREST_POINTS = array_reverse($INTEREST_POINTS);

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

        $PATH = file_get_contents(public_path('storage/mock/route/lausanneVilleCampagne/path.geojson'));

        // get InterestPoint between first and last
        $interestPoints = InterestPoint::whereBetween('id', [$first_id, $last_id])->get();

        // create route
        $route = Route::create([
            'name' => 'Lausanne, Ville et Nature',
            'description' => 'Ce sentier vous invite à découvrir les relations que Lausanne entretient avec son environnement naturel, ainsi que leur évolution à travers le temps. Composé de dix postes, ce parcours chemine dans le vallon du Flon, du centre-ville au Chalet-à-Gobet, porte d’entrée de la région du Jorat.
L’histoire de la ville de Lausanne est intimement liée à la nature qui l’entoure, en particulier aux eaux du Léman, du Flon et de la Louve. Ce sont les rives du lac qui ont accueilli les ancêtres gallo-romains des Lausannois. De même, ce sont les rivières qui ont protégé des agressions extérieures leurs descendants médiévaux, retranchés dès le IVe siècle sur la colline de l’actuelle Cité.

A l’époque moderne, cette nature alliée est devenue peu à peu dérangeante. Prenant trop de place, elle engendrait des désagréments et empêchait la ville de se développer. Les forêts ont alors été défrichées et les rivières domptées et canalisées, souvent d’une main de fer.

Depuis quelques années toutefois, la relation entre les humains et la nature évolue vers une plus grande harmonie. Le concept de développement durable, qui place au même niveau les considérations sociales, économiques et écologiques, s’est petit à petit imposé. Dans cette optique, et vu la qualité des zones naturelles environnant Lausanne, la population et les autorités projettent la création d’un «Parc naturel périurbain».

Cette possibilité est offerte par une législation mise en place par la Confédération pour des territoires  ayant une nature et paysage de grande valeur, ce qui est le cas de l’environnement naturel lausannois.',
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

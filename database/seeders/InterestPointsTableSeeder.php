<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\InterestPoint;

class InterestPointsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $INTEREST_POINTS = [
            [
                'name' => 'HEIG-VD (St-Roch)',
                'description' => 'Praça da Liberdade is a square in São Paulo, Brazil. It is located in the city centre, in the district of Sé. The square is served by the São Paulo Metro.',
                'lat' => 46.781334,
                'long' => 6.647398
            ],
            [
                'name' => 'Gare de Yverdon-les-Bains',
                'description' => 'Praça da Liberdade is a square in São Paulo, Brazil. It is located in the city centre, in the district of Sé. The square is served by the São Paulo Metro.',
                'lat' => 46.781685,
                'long' => 6.640715
            ],
            [
                'name' => 'Kebab Istanbul',
                'description' => 'Praça da Liberdade is a square in São Paulo, Brazil. It is located in the city centre, in the district of Sé. The square is served by the São Paulo Metro.',
                'lat' => 46.779725,
                'long' => 6.638220
            ],
        ];

        foreach ($INTEREST_POINTS as $interestPoint) {
            InterestPoint::create($interestPoint);
        }
    }
}

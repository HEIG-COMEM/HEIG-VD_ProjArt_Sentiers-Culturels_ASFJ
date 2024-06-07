<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Badge;
use App\Helpers\JsonHelper;

class BadgesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        function createOrUpdateBadge($badge, $parent = null)
        {
            $newBadge = Badge::updateOrCreate([
                'name' => $badge['name']
            ], [
                'name' => $badge['name'],
                'description' => $badge['description'] ?? null,
            ]);

            if (isset($badge['icon_path'])) {
                $newBadge->icon_path = $badge['icon_path'];
                $newBadge->save();
            }

            if (isset($badge['children'])) {
                foreach ($badge['children'] as $child) {
                    createOrUpdateBadge($child, $newBadge);
                }
            }

            if ($parent) {
                $newBadge->parent()->associate($parent);
                $newBadge->save();
            }
        }

        try {
            $data = JsonHelper::readJson('/dataset.json');

            $badges = collect($data['badges_family'])
                ->values()
                ->all();

            foreach ($badges as $badge) {
                createOrUpdateBadge($badge);
            }
        } catch (\Exception $e) {
            $this->command->error($e->getMessage());
        }
    }
}

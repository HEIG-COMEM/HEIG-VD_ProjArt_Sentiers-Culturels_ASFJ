<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Badge;
use App\Helpers\JsonHelper;

/**
 * Class BadgesTableSeeder
 * 
 * This class is responsible for seeding the badges table in the database.
 */
class BadgesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * This method is responsible for seeding the badges table in the database.
     * It reads a JSON file containing badge data, creates or updates the badges in the database,
     * and establishes parent-child relationships between badges if specified in the JSON data.
     * 
     * @return void
     */
    public function run(): void
    {
        /**
         * Create or update a badge and its children recursively.
         *
         * @param array $badge The badge data.
         * @param Badge|null $parent The parent badge.
         * @return void
         */
        function createOrUpdateBadge($badge, $parent = null): void
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

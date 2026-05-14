<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Gender;

class GenderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $genders = [
            [
                'id' => 1,
                'code' => 'women',
                'active' => true,
                'translations' => [
                    ['locale' => 'uk', 'title' => 'Жіночі'],
                    ['locale' => 'ru', 'title' => 'Женские'],
                    ['locale' => 'en', 'title' => 'Women'],
                ],
                'order' => 1,
                'slug' => 'zhinochi',
                'emoji' => '👩',
            ],
            [
                'id' => 2,
                'code' => 'men',
                'active' => true,
                'translations' => [
                    ['locale' => 'uk', 'title' => 'Чоловічі'],
                    ['locale' => 'ru', 'title' => 'Мужские'],
                    ['locale' => 'en', 'title' => 'Men'],
                ],
                'order' => 2,
                'slug' => 'cholovichi',
                'emoji' => '🧔‍♂️',
            ],
            [
                'id' => 3,
                'code' => 'unisex',
                'active' => true,
                'translations' => [
                    ['locale' => 'uk', 'title' => 'Унісекс'],
                    ['locale' => 'ru', 'title' => 'Унисекс'],
                    ['locale' => 'en', 'title' => 'Unisex'],
                ],
                'order' => 3,
                'slug' => 'uniseks',
                'emoji' => '🧔‍♀️',
            ],
        ];

        foreach ($genders as $genderData) {
            $id = $genderData['id'];
            $translations = $genderData['translations'];
            $order = $genderData['order'] ?? 0;
            $slug = $genderData['slug'] ?? null;
            $emoji = $genderData['emoji'] ?? null;
            unset($genderData['id'], $genderData['translations'], $genderData['order'], $genderData['slug'], $genderData['emoji']);

            $gender = Gender::updateOrCreate(
                ['id' => $id],
                [
                    'code' => $genderData['code'],
                    'active' => $genderData['active'],
                    'order' => $order,
                    'slug' => $slug,
                    'emoji' => $emoji,
                ]
            );

            foreach ($translations as $translation) {
                $gender->translations()->updateOrCreate(
                    ['locale' => $translation['locale']],
                    ['title' => $translation['title']]
                );
            }
        }
    }
}

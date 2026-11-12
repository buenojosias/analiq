<?php

namespace Database\Factories;

use App\Enums\ProductionTypeEnum;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductionFactory extends Factory
{
    public function definition(): array
    {
        $city = fake()->randomElement([
            null,
            fake()->city(),
        ]);
        $title = rtrim(fake()->sentence(), '.');

        if (fake()->boolean()) {
            $title .= ': '.lcfirst(rtrim(fake()->sentence(), '.'));
        }

        if ($city) {
            $state_id = rand(1, 27);
        }

        return [
            'searched_terms' => fake()->words(rand(1, 3)),
            'repository' => fake()
                ->randomElement([
                    'Scielo',
                    'Scopus',
                    'Capes',
                    'Web of Science',
                ]),
            'type' => fake()
                ->randomElement(ProductionTypeEnum::cases())->value,
            'language' => fake()
                ->randomElement([
                    'Português',
                    'Inglês',
                    'Português',
                    'Espanhol',
                    'Português',
                ]),
            'title' => $title,
            'year' => rand(2019, 2023),
            'institution' => fake()
                ->randomElement([
                    null,
                    fake()->sentence(),
                ]),
            'program' => fake()
                ->randomElement([
                    null,
                    fake()->sentence(),
                ]),
            'periodical' => fake()
                ->randomElement([
                    null,
                    fake()->company(),
                ]),
            'city' => fake()
                ->randomElement([
                    null,
                    fake()->city(),
                ]),
            'state_id' => $state_id ?? null,
            'url' => fake()->url(),
            'doi' => fake()
                ->randomElement([
                    null,
                    fake()->uuid(),
                ]),
        ];
    }
}

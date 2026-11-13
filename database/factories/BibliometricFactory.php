<?php

namespace Database\Factories;

use App\Enums\ProductionTypeEnum;
use Illuminate\Database\Eloquent\Factories\Factory;

class BibliometricFactory extends Factory
{
    public function definition(): array
    {
        $start_year = rand(2019, 2023);

        return [
            'repositories' => fake()
                ->randomElements(
                    [
                        'Scielo',
                        'Scopus',
                        'Capes',
                        'Web of Science',
                    ],
                    rand(1, 2)
                ),
            'types' => fake()
                ->randomElements(
                    ProductionTypeEnum::class,
                    rand(1, 2)
                ),
            'terms' => [
                'a' => fake()->word(),
                'b' => fake()->word(),
            ],
            'start_year' => $start_year,
            'end_year' => rand($start_year + 1, 2024),
            'languages' => fake()
                ->randomElements(
                    [
                        'Português',
                        'Inglês',
                        'Espanhol',
                    ],
                    rand(1, 2)
                ),
        ];
    }
}

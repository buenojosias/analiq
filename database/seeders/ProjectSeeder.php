<?php

namespace Database\Seeders;

use App\Models\Bibliometric;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        User::query()->get()->each(function (User $user) {
            Project::factory(rand(1, 2))
                ->has(Bibliometric::factory())
                ->create([
                    'user_id' => $user->id,
                ]);
        });
    }
}

<?php

namespace Tests\Feature\Production;

use App\Livewire\Production\ProductionGoal;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ProductionGoalTest extends TestCase
{
    use RefreshDatabase;

    public function test_specific_goal_is_saved_and_shown(): void
    {
        $user = User::factory()->create();
        $project = $user->projects()->create([
            'theme' => 'Tema do projeto',
            'requested_at' => now()->toDateString(),
        ]);
        $production = $project->productions()->create([
            'searched_terms' => [],
            'repository' => 'Repositório',
            'type' => 'TCC',
            'language' => 'pt',
            'title' => 'Produção',
            'year' => 2026,
        ]);

        Livewire::actingAs($user)
            ->test(ProductionGoal::class, ['project' => $project, 'production' => $production->id])
            ->set('specificContent', 'Objetivo específico')
            ->call('saveSpecificGoal')
            ->assertHasNoErrors()
            ->assertSee('Objetivo específico');

        $this->assertDatabaseHas('goals', [
            'production_id' => $production->id,
            'level' => 'Específico',
            'content' => 'Objetivo específico',
        ]);
    }
}

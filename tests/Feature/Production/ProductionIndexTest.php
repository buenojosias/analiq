<?php

namespace Tests\Feature\Production;

use App\Livewire\Production\ProductionIndex;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ProductionIndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_production_title_links_to_its_project(): void
    {
        $user = User::factory()->create();
        $project = $user->projects()->create([
            'theme' => 'Tema do projeto',
            'requested_at' => now()->toDateString(),
        ]);
        $project->bibliometric()->create([
            'repositories' => ['Repositório'],
            'types' => ['TCC'],
            'terms' => [],
            'start_year' => 2026,
            'end_year' => 2026,
            'languages' => ['pt'],
        ]);
        $production = $project->productions()->create([
            'searched_terms' => [],
            'repository' => 'Repositório',
            'type' => 'TCC',
            'language' => 'pt',
            'title' => 'Produção de exemplo',
            'year' => 2026,
        ]);

        Livewire::actingAs($user)
            ->test(ProductionIndex::class, ['project' => $project])
            ->assertSee('Produção de exemplo')
            ->assertSee(route('project.bibliometrics.productions.show', [$project, $production]));
    }
}

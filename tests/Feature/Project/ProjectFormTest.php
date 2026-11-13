<?php

namespace Tests\Feature\Project;

use App\Livewire\Project\ProjectForm;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ProjectFormTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_can_create_project(): void
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(ProjectForm::class)
            ->set('theme', 'Tema do projeto')
            ->set('requested_at', now()->toDateString())
            ->call('save')
            ->assertHasNoErrors();

        $project = Project::query()->firstOrFail();

        $this->assertSame($user->id, $project->user_id);
        $this->assertSame('Tema do projeto', $project->theme);
        $this->assertSame($project->id, $user->refresh()->selected_project_id);
    }

    public function test_owner_can_update_project_without_changing_its_owner(): void
    {
        $user = User::factory()->create();
        $project = $user->projects()->create([
            'theme' => 'Tema antigo',
            'requested_at' => now()->toDateString(),
        ]);

        Livewire::actingAs($user)
            ->test(ProjectForm::class, ['project' => $project])
            ->set('theme', 'Tema atualizado')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertSame('Tema atualizado', $project->refresh()->theme);
        $this->assertSame($user->id, $project->user_id);
    }
}

<?php

namespace Tests\Feature\Bibliometric;

use App\Livewire\Bibliometric\CustomFields;
use App\Models\Bibliometric;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CustomFieldsTest extends TestCase
{
    use RefreshDatabase;

    public function test_editing_a_field_updates_the_selected_item(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $project = Project::factory()->for($user)->create([
            'theme' => 'Tema do projeto',
            'requested_at' => now()->toDateString(),
        ]);
        $bibliometric = Bibliometric::factory()->for($project)->create([
            'repositories' => ['Repositório'],
            'types' => ['TCC'],
            'terms' => [],
            'start_year' => 2026,
            'end_year' => 2026,
            'languages' => ['pt'],
        ]);
        $firstField = $bibliometric->customFields()->create(['name' => 'Primeiro', 'type' => 'text']);
        $selectedField = $bibliometric->customFields()->create(['name' => 'Segundo', 'type' => 'integer']);

        Livewire::test(CustomFields::class, ['bibliometric' => $bibliometric])
            ->call('editCustomField', $selectedField->id)
            ->assertSet('custom_field_id', $selectedField->id)
            ->assertSet('custom_field_name', 'Segundo')
            ->assertSet('custom_field_type', 'integer')
            ->set('custom_field_name', 'Atualizado')
            ->call('submit')
            ->assertHasNoErrors();

        $this->assertSame('Primeiro', $firstField->refresh()->name);
        $this->assertSame('Atualizado', $selectedField->refresh()->name);
    }

    public function test_resetting_the_form_clears_the_selected_field(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $project = Project::factory()->for($user)->create([
            'theme' => 'Tema do projeto',
            'requested_at' => now()->toDateString(),
        ]);
        $bibliometric = Bibliometric::factory()->for($project)->create([
            'repositories' => ['Repositório'],
            'types' => ['TCC'],
            'terms' => [],
            'start_year' => 2026,
            'end_year' => 2026,
            'languages' => ['pt'],
        ]);
        $field = $bibliometric->customFields()->create(['name' => 'Campo', 'type' => 'text']);

        Livewire::test(CustomFields::class, ['bibliometric' => $bibliometric])
            ->call('editCustomField', $field->id)
            ->call('resetCustomFieldForm')
            ->assertSet('custom_field_id', null)
            ->assertSet('custom_field_name', null)
            ->assertSet('custom_field_type', null);
    }
}

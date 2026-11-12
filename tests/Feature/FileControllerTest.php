<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FileControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_from_pdf(): void
    {
        $this->get(route('files', 'documento.pdf'))->assertRedirect(route('login'));
    }

    public function test_owner_can_read_pdf_and_other_user_cannot(): void
    {
        config()->set('filesystems.default', 'local');
        Storage::fake('local');

        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $project = $owner->projects()->create([
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
        $production->file()->create([
            'filename' => 'Documento',
            'path' => 'documento.pdf',
            'size' => 1.000,
            'pages' => 1,
        ]);
        Storage::put('pdfs/documento.pdf', '%PDF-1.4');

        $this->actingAs($owner)
            ->get(route('files', 'documento.pdf'))
            ->assertOk();

        $this->actingAs($otherUser)
            ->get(route('files', 'documento.pdf'))
            ->assertNotFound();
    }
}

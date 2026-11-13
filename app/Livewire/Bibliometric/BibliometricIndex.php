<?php

namespace App\Livewire\Bibliometric;

use Livewire\Component;

class BibliometricIndex extends Component
{
    public function render()
    {
        $headers = [
            ['index' => 'requested_at', 'label' => 'Data', 'sortable' => false],
            ['index' => 'theme', 'label' => 'Tema', 'sortable' => false],
            ['index' => 'action', 'label' => '', 'sortable' => false],
        ];

        $researches = Research::query()
            ->withCount('publications')
            ->get();

        return view('livewire.bibliometric.bibliometric-index', compact('researches', 'headers'))
            ->title('Bibliometria');
    }
}

// Formulário para cadastro
// Admin: mostrar todos com user
// Não admin: mostrar apenas as suas
// Quantidade de publicações

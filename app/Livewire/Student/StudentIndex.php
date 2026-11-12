<?php

namespace App\Livewire\Student;

use App\Models\Student;
use Livewire\Component;

class StudentIndex extends Component
{
    public function render()
    {
        $headers = [
            ['index' => 'name', 'label' => 'Nome', 'sortable' => false],
        ];

        if (auth()->user()->admin) {
            $headers[] = ['index' => 'user', 'label' => 'Usuário', 'sortable' => false];
        }

        $headers[] = ['index' => 'projects_count', 'label' => 'Projetos', 'sortable' => false];

        $students = Student::query()
            ->withCount('projects')
            ->when(auth()->user()->admin, fn ($q) => $q->with('user'))
            ->get();

        return view('livewire.student.student-index', compact('students', 'headers'))
            ->title('Estudantes');
    }
}

// NOT ADMIN: Listar apenas estudantes que pertencem ao usuário
// ADMIN: mostrar user
// Link para formulário de cadastro

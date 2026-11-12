<?php

namespace App\Livewire\Student;

use App\Models\Student;
use Livewire\Component;

class StudentShow extends Component
{
    public $student;

    public function mount(Student $student)
    {
        $student->load('projects');
        $this->student = $student;
    }

    public function render()
    {
        $projectHeaders = [['index' => 'theme', 'label' => 'Projeto', 'sortable' => false]];

        return view('livewire.student.student-show', compact('projectHeaders'))
            ->title('Estudante');
    }
}

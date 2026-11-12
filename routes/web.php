<?php

use App\Http\Controllers\FileController;
use App\Livewire\Bibliometric\BibliometricForm;
use App\Livewire\Bibliometric\BibliometricShow;
use App\Livewire\Citation\CitationIndex;
use App\Livewire\Content\ContentAbstract;
use App\Livewire\Content\ContentGoal;
use App\Livewire\Dashboard\DashboardIndex;
use App\Livewire\File\FileIndex;
use App\Livewire\File\FileShow;
use App\Livewire\Group\GroupIndex;
use App\Livewire\Group\GroupShow;
use App\Livewire\Internal\InternalForm;
use App\Livewire\Keyword\KeywordIndex;
use App\Livewire\Note\NoteIndex;
use App\Livewire\Production\ProductionCitation;
use App\Livewire\Production\ProductionContent;
use App\Livewire\Production\ProductionCreate;
use App\Livewire\Production\ProductionEdit;
use App\Livewire\Production\ProductionGoal;
use App\Livewire\Production\ProductionIndex;
use App\Livewire\Production\ProductionKeywords;
use App\Livewire\Production\ProductionReference;
use App\Livewire\Production\ProductionShow;
use App\Livewire\Production\ProductionTags;
use App\Livewire\Project\ProjectIndex;
use App\Livewire\Project\ProjectShow;
use App\Livewire\Record\RecordIndex;
use App\Livewire\Record\RecordPerAuthor;
use App\Livewire\Record\RecordPerCity;
use App\Livewire\Record\RecordPerDescriptor;
use App\Livewire\Record\RecordPerInstitution;
use App\Livewire\Record\RecordPerPeriodical;
use App\Livewire\Record\RecordPerProgram;
use App\Livewire\Record\RecordPerState;
use App\Livewire\Record\RecordPerYear;
use App\Livewire\Reference\ReferenceCitation;
use App\Livewire\Reference\ReferenceIndex;
use App\Livewire\Reference\ReferenceProject;
use App\Livewire\Reference\ReferenceShow;
use App\Livewire\SearchResult\SearchResultIndex;
use App\Livewire\Student\StudentForm;
use App\Livewire\Student\StudentIndex;
use App\Livewire\Student\StudentShow;
use App\Livewire\Tag\TagIndex;
use App\Livewire\WordCloud\WordCloudIndex;
use App\Livewire\WordCount\WordCountCreate;
use App\Livewire\WordCount\WordCountIndex;
use App\Livewire\WordCount\WordCountShow;
use App\Livewire\WordRanking\WordRankingCreate;
use App\Livewire\WordRanking\WordRankingIndex;
use App\Livewire\WordRanking\WordRankingShow;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');
Route::view('novo', 'new-layout')->name('new-layout');

Route::middleware(['auth'])->group(function () {

    Route::get('pdfs/{path}', [FileController::class, 'show'])->name('files');

    Route::get('dashboard', DashboardIndex::class)->name('dashboard');

    Route::get('estudantes', StudentIndex::class)->name('students.index');
    Route::get('estudantes/novo', StudentForm::class)->name('students.create');
    Route::get('estudantes/{student}', StudentShow::class)->name('students.show');
    Route::get('estudantes/{student}/editar', StudentForm::class)->name('students.edit');

    Route::get('projetos', ProjectIndex::class)->name('projects.index');
    Route::get('p/{project}', ProjectShow::class)->name('project.show');

    Route::get('p/{project}/bibiometria', BibliometricShow::class)->name('project.bibliometrics.show');

    Route::name('project.bibliometrics.')->prefix('p/{project}')->group(function () {
        Route::get('b/nova', BibliometricForm::class)->name('create');
        Route::get('b/editar', BibliometricForm::class)->name('edit');

        Route::get('producoes', ProductionIndex::class)->name('productions.index');
        Route::get('producoes/nova', ProductionCreate::class)->name('productions.create');
        Route::get('producoes/grupos', GroupIndex::class)->name('productions.groups.index');
        Route::get('producoes/grupos/{group}', GroupShow::class)->name('productions.groups.show');
        Route::get('producoes/{production}', ProductionShow::class)->name('productions.show');
        Route::get('producoes/{production}/editar', ProductionEdit::class)->name('productions.edit');
        Route::get('producoes/{production}/arquivo', FileShow::class)->name('productions.files.show');
        Route::get('producoes/{production}/keywords', ProductionKeywords::class)->name('productions.keywords');
        Route::get('producoes/{production}/tags', ProductionTags::class)->name('productions.tags');
        Route::get('producoes/{production}/objetivos', ProductionGoal::class)->name('productions.goals');
        Route::get('producoes/{production}/referencias', ProductionReference::class)->name('productions.references');
        Route::get('producoes/{production}/citacoes', ProductionCitation::class)->name('productions.citations');
        Route::get('producoes/{production}/{section}', ProductionContent::class)->name('productions.section');
        Route::get('producoes/{production}/{section}/form', InternalForm::class)->name('productions.section.form');

        Route::get('referencias', ReferenceIndex::class)->name('references.index');
        Route::get('referencias/projeto', ReferenceProject::class)->name('references.project');
        Route::get('referencias/{reference}', ReferenceShow::class)->name('references.show');
        Route::get('referencias/{reference}/citacoes', ReferenceCitation::class)->name('references.citations');

        Route::get('anotacoes', NoteIndex::class)->name('notes.index');

        Route::get('citacoes', CitationIndex::class)->name('citations.index');

        Route::get('rel/keywords', KeywordIndex::class)->name('keywords.index');
        Route::get('rel/tags', TagIndex::class)->name('tags.index');

        Route::get('rel/preliminar', SearchResultIndex::class)->name('search-results.index');

        Route::get('rel/contagem', WordCountIndex::class)->name('wordcounts.index');
        Route::get('rel/contagem/nova', WordCountCreate::class)->name('wordcounts.create');
        Route::get('rel/contagem/{wordcount}', WordCountShow::class)->name('wordcounts.show');

        Route::get('rel/resumos', ContentAbstract::class)->name('content.index');
        Route::get('rel/objetivos', ContentGoal::class)->name('content.goals');

        Route::get('rel/ranking', WordRankingIndex::class)->name('wordrankings.index');
        Route::get('rel/ranking/novo', WordRankingCreate::class)->name('wordrankings.create');
        Route::get('rel/ranking/{wordranking}', WordRankingShow::class)->name('wordrankings.show');

        Route::get('rel/nuvem', WordCloudIndex::class)->name('wordclouds.index');

        Route::get('rel/estatisticas', RecordIndex::class)->name('records.index');
        Route::get('rel/anos', RecordPerYear::class)->name('records.years');
        Route::get('rel/periodicos', RecordPerPeriodical::class)->name('records.periodicals');
        Route::get('rel/estados', RecordPerState::class)->name('records.states');
        Route::get('rel/cidades', RecordPerCity::class)->name('records.cities');
        Route::get('rel/instituicoes', RecordPerInstitution::class)->name('records.institutions');
        Route::get('rel/programas', RecordPerProgram::class)->name('records.programs');
        Route::get('rel/autores', RecordPerAuthor::class)->name('records.authors');
        Route::get('rel/descritores', RecordPerDescriptor::class)->name('records.descriptors');

        Route::get('arquivos', FileIndex::class)->name('files.index');

    });
    // Route::view('profile', 'profile')->name('profile');

    //     Route::get('pub/{publication}/conteudo', \App\Livewire\Production\ProductionContent::class)->name('researches.publications.content');
    //     Route::get('pub/{publication}/resumo', \App\Livewire\Internal\InternalForm::class)->name('researches.publications.abstract');
    //     Route::get('pub/{publication}/corpo', \App\Livewire\Internal\InternalForm::class)->name('researches.publications.body');

    // });
});

require __DIR__.'/auth.php';

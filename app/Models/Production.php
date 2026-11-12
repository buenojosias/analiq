<?php

namespace App\Models;

use App\Enums\ProductionTypeEnum;
use App\Models\Scopes\ProjectScope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;

// #[ScopedBy(ProjectScope::class)]
class Production extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'project_id',
        'searched_terms',
        'repository',
        'type',
        'language',
        'title',
        'year',
        'institution',
        'program',
        'periodical',
        'city',
        'state_id',
        'country',
        'url',
        'doi',
        'highlighted',
    ];

    protected function casts(): array
    {
        return [
            'searched_terms' => 'array',
            'type' => ProductionTypeEnum::class,
            'highlighted' => 'boolean',
        ];
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function customFields(): BelongsToMany
    {
        return $this->belongsToMany(CustomField::class)->withPivot('value')->withTimestamps();
    }

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }

    public function internals(): HasMany
    {
        return $this->hasMany(Internal::class);
    }

    public function authors(): HasMany
    {
        return $this->hasMany(Author::class);
    }

    public function keywords(): HasMany
    {
        return $this->hasMany(Keyword::class);
    }

    public function abstract(): HasOne
    {
        return $this->hasOne(Internal::class)->where(function ($query) {
            $query->where('section', 'resumo');
        });
    }

    public function goals(): HasMany
    {
        return $this->hasMany(Goal::class);
    }

    public function generalGoal(): HasOne
    {
        return $this->hasOne(Goal::class)->where(function ($query) {
            $query->where('level', 'Geral');
        });
    }

    public function specificGoals(): HasMany
    {
        return $this->hasMany(Goal::class)->where(function ($query) {
            $query->where('level', 'Específico');
        });
    }

    public function body(): HasOne
    {
        return $this->hasOne(Internal::class)->where(function ($query) {
            $query->where('section', 'body');
        });
    }

    public function file(): MorphOne
    {
        return $this->MorphOne(File::class, 'fileable');
    }

    public function wordRankings(): HasMany
    {
        return $this->hasMany(WordRanking::class);
    }

    public function references(): BelongsToMany
    {
        return $this->belongsToMany(Reference::class)->withPivot('suffix');
    }

    public function citations(): HasMany
    {
        return $this->hasMany(Citation::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function notes(): HasMany
    {
        return $this->hasMany(Note::class);
    }

    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(Group::class, 'group_production')
            ->withPivot('note');
    }
}

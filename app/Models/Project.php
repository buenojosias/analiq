<?php

namespace App\Models;

use App\Models\Scopes\UserScope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

#[ScopedBy(UserScope::class)]
class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'theme',
        'requested_at',
    ];

    protected function casts(): array
    {
        return [
            'requested_at' => 'date:Y-m-d',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function bibliometric(): HasOne
    {
        return $this->hasOne(Bibliometric::class);
    }

    public function productions(): HasMany
    {
        return $this->hasMany(Production::class);
    }

    public function files(): HasManyThrough
    {
        return $this->hasManyThrough(File::class, Production::class);
    }

    public function wordCounts(): HasMany
    {
        return $this->hasMany(WordCount::class);
    }

    public function wordRankings(): HasMany
    {
        return $this->hasMany(WordRanking::class);
    }

    public function authors(): HasManyThrough
    {
        return $this->hasManyThrough(Author::class, Production::class);
    }

    public function keywords(): HasManyThrough
    {
        return $this->hasManyThrough(Keyword::class, Production::class);
    }

    public function references(): HasMany
    {
        return $this->hasMany(Reference::class);
    }

    public function results(): HasMany
    {
        return $this->hasMany(SearchResult::class);
    }

    public function citations(): HasManyThrough
    {
        return $this->hasManyThrough(Citation::class, Production::class);
    }

    public function tags(): HasMany
    {
        return $this->hasMany(Tag::class);
    }

    public function notes(): HasMany
    {
        return $this->hasMany(Note::class);
    }

    public function groups(): HasMany
    {
        return $this->hasMany(Group::class);
    }
}

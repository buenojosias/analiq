<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bibliometric extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'project_id',
        'repositories',
        'types',
        'terms',
        'start_year',
        'end_year',
        'languages',
    ];

    protected function casts(): array
    {
        return [
            'repositories' => 'array',
            'types' => 'array',
            'terms' => 'array',
            'languages' => 'array',
        ];
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function wordAnalysisConfig(): HasOne
    {
        return $this->hasOne(WordAnalysisConfig::class);
    }

    public function customFields(): HasMany
    {
        return $this->hasMany(CustomField::class);
    }
}

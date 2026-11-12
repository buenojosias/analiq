<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Production;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FileController extends Controller
{
    public function show(string $path): StreamedResponse
    {
        $file = File::query()
            ->where('path', $path)
            ->whereHasMorph('fileable', [Production::class], fn (Builder $query) => $query->whereHas('project'))
            ->firstOrFail();

        $storagePath = 'pdfs/'.$file->path;
        abort_unless(Storage::exists($storagePath), 404);

        return Storage::response($storagePath);
    }
}

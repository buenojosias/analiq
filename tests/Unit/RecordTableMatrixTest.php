<?php

namespace Tests\Unit;

use App\Livewire\Record\TableMatrix;
use PHPUnit\Framework\TestCase;

class RecordTableMatrixTest extends TestCase
{
    public function test_it_preserves_matrix_columns_and_totals(): void
    {
        $matrix = TableMatrix::build(
            'Descritores',
            [2025, 2026],
            [
                'Pesquisa A' => [2025 => 2, 2026 => 1, 'total' => 3],
                'Pesquisa B' => [2026 => 4],
            ],
            columnTotals: [2025 => 2, 2026 => 5, 'total' => 7],
        );

        $this->assertSame(['Descritores', '2025', '2026', 'Total'], array_column($matrix['headers'], 'label'));
        $this->assertSame([
            ['label' => 'Pesquisa A', 'value_0' => 2, 'value_1' => 1, 'total' => 3],
            ['label' => 'Pesquisa B', 'value_0' => 0, 'value_1' => 4, 'total' => 4],
            ['label' => 'Total', 'value_0' => 2, 'value_1' => 5, 'total' => 7],
        ], $matrix['rows']);
    }
}

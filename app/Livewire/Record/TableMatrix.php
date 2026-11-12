<?php

namespace App\Livewire\Record;

use BackedEnum;

class TableMatrix
{
    /**
     * @return array{headers: array<int, array{index: string, label: string, sortable: bool, align?: string}>, rows: array<int, array<string, int|string>>}
     */
    public static function build(
        string $label,
        iterable $columns,
        iterable $values,
        ?iterable $rowTotals = null,
        ?iterable $columnTotals = null,
    ): array {
        $columns = collect($columns)->values();
        $headers = [['index' => 'label', 'label' => $label, 'sortable' => false]];

        foreach ($columns as $index => $column) {
            $headers[] = [
                'index' => 'value_'.$index,
                'label' => (string) ($column instanceof BackedEnum ? $column->value : $column),
                'sortable' => false,
                'align' => 'center',
            ];
        }

        $headers[] = ['index' => 'total', 'label' => 'Total', 'sortable' => false, 'align' => 'center'];
        $rows = [];

        foreach ($values as $name => $counts) {
            $row = ['label' => $name === '' || $name === null ? 'Não informado' : (string) $name];
            $sum = 0;

            foreach ($columns as $index => $column) {
                $column = $column instanceof BackedEnum ? $column->value : $column;
                $value = (int) (collect($counts)->get($column) ?? 0);
                $row['value_'.$index] = $value;
                $sum += $value;
            }

            $row['total'] = (int) (collect($rowTotals)->get($name) ?? collect($counts)->get('total') ?? $sum);
            $rows[] = $row;
        }

        if ($columnTotals !== null && $rows !== []) {
            $total = ['label' => 'Total'];
            $sum = 0;

            foreach ($columns as $index => $column) {
                $column = $column instanceof BackedEnum ? $column->value : $column;
                $value = (int) (collect($columnTotals)->get($column) ?? 0);
                $total['value_'.$index] = $value;
                $sum += $value;
            }

            $total['total'] = (int) (collect($columnTotals)->get('total') ?? $sum);
            $rows[] = $total;
        }

        return ['headers' => $headers, 'rows' => $rows];
    }
}

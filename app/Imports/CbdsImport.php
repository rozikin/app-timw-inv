<?php

namespace App\Imports;

use App\Models\Cbd;
use App\Models\CbdDetail;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Validators\Failure;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;

class CbdsImport implements ToCollection, WithStartRow, WithMapping, WithValidation, SkipsOnFailure
{
    use SkipsFailures;

    /**
     * @return int
     */
    public function startRow(): int
    {
        return 3; // Mulai dari baris ke-3
    }

    /**
     * @param array $row
     *
     * @return array
     */
    public function map($row): array
    {
        return [
            'order_no' => $row[0], // Kolom ke-1
            'supplier_raw_material_code' => $row[21], // Kolom ke-22
            'item' => $row[31], // Kolom ke-32
            'sample_code' => $row[33], // Kolom ke-34
            'color_code' => $row[36], // Kolom ke-37
            'color' => $row[37], // Kolom ke-38
            'size_code' => $row[39], // Kolom ke-40
            'size' => $row[40], // Kolom ke-41
            'qty' => $row[41], // Kolom ke-42
            'remark' => $row[42] ?? null // Kolom ke-43 (optional)
        ];
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $cbd = Cbd::updateOrCreate(
                ['order_no' => $row['order_no']],
                [
                    'supplier_raw_material_code' => $row['supplier_raw_material_code'],
                    'item' => $row['item'],
                    'sample_code' => $row['sample_code'],
                    'remark' => $row['remark']
                ]
            );

            CbdDetail::updateOrCreate(
                [
                    'order_no' => $row['order_no'],
                    'color_code' => $row['color_code'],
                    'color' => $row['color'],
                    'size_code' => $row['size_code'],
                    'size' => $row['size'],
                    'qty' => $row['qty']
                ]
            );
        }
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            '*.order_no' => 'required|string|unique:cbds,order_no',
            '*.supplier_raw_material_code' => 'required|string',
            '*.item' => 'required|string',
            '*.sample_code' => 'required|string',
            '*.color_code' => 'required|string',
            '*.color' => 'required|string',
            '*.size_code' => 'required|string',
            '*.size' => 'required|string',
            '*.qty' => 'required|integer',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'order_no.required' => 'Order number is required.',
            'order_no.unique' => 'Order number must be unique.',
            'supplier_raw_material_code.required' => 'Supplier raw material code is required.',
            'item.required' => 'Item is required.',
            'sample_code.required' => 'Sample code is required.',
            'color_code.required' => 'Color code is required.',
            'color.required' => 'Color is required.',
            'size_code.required' => 'Size code is required.',
            'size.required' => 'Size is required.',
            'qty.required' => 'Quantity is required.',
            'qty.integer' => 'Quantity must be an integer.',
        ];
    }

    public function onFailure(Failure ...$failures)
    {
        foreach ($failures as $failure) {
            \Log::error('Validation failure', [
                'row' => $failure->row(),
                'attribute' => $failure->attribute(),
                'errors' => $failure->errors(),
                'values' => $failure->values(),
            ]);
        }
    }
}

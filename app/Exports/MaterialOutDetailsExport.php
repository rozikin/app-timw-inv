<?php

namespace App\Exports;

use App\Models\MaterialOutDetail;
use App\Models\MaterialOut;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class MaterialOutDetailsExport implements FromCollection, WithHeadings, WithMapping, WithStrictNullComparison
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        return MaterialOut::query()
            ->whereBetween('created_at', [$this->startDate, $this->endDate])
            ->with(['details.item','details.materialInDetail','details.materialInDetailx']) // Load related details and item
            ->get()
            ->flatMap(function($materialOut) {
                return $materialOut->details->map(function($detail) use ($materialOut) {
                    return [
                        'material_out_id' => $materialOut->id,
                        'material_out_no' => $materialOut->material_out_no,
                        'allocation' => $materialOut->allocation,
                        'person' => $materialOut->person,
                        'remark' => $materialOut->remark,
                        'detail_id' => $detail->id,
                        'original_no' => $detail->original_no,
                        'receive_date' => $detail->materialInDetail->received_date,
                        'supplier_name' => $detail->materialInDetail->supplier_name,
                        'item_code' => $detail->item_code,
                        'po' => $detail->materialInDetail->po,
                        'color_code' => $detail->color_code,
                        'color_name' => $detail->color_name,
                        'size' => $detail->size,
                        'batch' => $detail->materialInDetail->batch,
                        'roll' => $detail->materialInDetail->roll,
                        'gross_weight' => $detail->materialInDetail->gross_weight,
                        'net_weight' => $detail->materialInDetail->net_weight,
                        'qty' => $detail->qty,
                        'basic_width' => $detail->materialInDetail->basic_width,
                        'basic_grm' => $detail->materialInDetail->basic_grm,
                        'mo' => $detail->mo,
                        'actual_weight' => $detail->materialInDetailx->actual_weight,
                        'rak' => $detail->rak,
                        'created_at' => $materialOut->created_at,
                        'updated_at' => $materialOut->updated_at,
                    ];
                });
            });
    }

    public function headings(): array
    {
        return [
            'Material OUT ID',
            'Material out No',
            'Allocation',
            'Person',
            'Remark',
            'Detail ID',
            'Original No',
            'Receive Date',
            'Supplier Name',
            'Item Code',
            'PO',
            'Color Code',
            'Color Name',
            'Size',
            'Batch',
            'Roll',
            'Gross Weight',
            'Net Weight',
            'Qty',
            'Basic Width',
            'Basic Grm',
            'MO',
            'Actual Weight',
            'Rak',
            'Created At',
            'Updated At',
        ];
    }

    public function map($row): array
    {
        return [
            $row['material_out_id'],
            $row['material_out_no'],
            $row['allocation'],
            $row['person'],
            $row['remark'],
            $row['detail_id'],
            $row['original_no'],
            $row['receive_date'],
            $row['supplier_name'],
            $row['item_code'],
            $row['po'],
            $row['color_code'],
            $row['color_name'],
            $row['size'],
            $row['batch'],
            $row['roll'],
            $row['gross_weight'],
            $row['net_weight'],
            $row['qty'],
            $row['basic_width'],
            $row['basic_grm'],
            $row['mo'],
            $row['actual_weight'],
            $row['rak'],
            $row['created_at'],
            $row['updated_at'],
        ];
    }
}

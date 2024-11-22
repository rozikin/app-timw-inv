<?php
namespace App\Exports;

use App\Models\PurchaseRequest;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PurchaseRequestExport implements FromCollection, WithHeadings, WithMapping
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    /**
     * Mengambil data berdasarkan rentang tanggal.
     */
    public function collection()
    {
        return PurchaseRequest::with('detailrequest.item.unit', 'cbd')
            ->whereBetween('created_at', [$this->startDate, $this->endDate])
            ->get();
    }

    /**
     * Header kolom di file Excel.
     */
    public function headings(): array
    {
        return [
            'No',
            'Request No',
            'CBD',
            'Type',
            'MO',
            'Style',
            'Destination',
            'Applicant',
            'Item Names',
            'Colors',
            'Sizes',
            'Units',
            'Total',
            'Remarks',
            'Statuses',
        ];
    }

    /**
     * Mapping data model ke baris Excel.
     */
    public function map($purchaseRequest): array
    {
        return [
            $purchaseRequest->id,
            $purchaseRequest->purchase_request_no,
            $purchaseRequest->cbd->order_no ?? '-',
            $purchaseRequest->tipe,
            $purchaseRequest->mo,
            $purchaseRequest->style,
            $purchaseRequest->destination,
            $purchaseRequest->applicant,
            $this->getItemNames($purchaseRequest),
            $this->getColors($purchaseRequest),
            $this->getSizes($purchaseRequest),
            $this->getUnits($purchaseRequest),
            $this->getTotals($purchaseRequest),
            $this->getRemarks($purchaseRequest),
            $this->getStatuses($purchaseRequest),
        ];
    }

    private function getItemNames($purchaseRequest)
    {
        return $purchaseRequest->detailrequest->pluck('item.item_name')->implode(', ');
    }

    private function getColors($purchaseRequest)
    {
        return $purchaseRequest->detailrequest->pluck('color')->implode(', ');
    }

    private function getSizes($purchaseRequest)
    {
        return $purchaseRequest->detailrequest->pluck('size')->implode(', ');
    }

    private function getUnits($purchaseRequest)
    {
        return $purchaseRequest->detailrequest->pluck('item.unit.unit_code')->implode(', ');
    }

    private function getTotals($purchaseRequest)
    {
        return $purchaseRequest->detailrequest->pluck('total')->sum();
    }

    private function getRemarks($purchaseRequest)
    {
        return $purchaseRequest->detailrequest->pluck('remark')->implode(', ');
    }

    private function getStatuses($purchaseRequest)
    {
        return $purchaseRequest->detailrequest->pluck('status')->implode(', ');
    }
}

<?php

namespace App\Exports;

use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class HSNExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles, WithColumnWidths, WithTitle
{
    /**
     * @return \Illuminate\Support\Collection
     */

    protected $data;
    protected $sheetTitle = 'hsn';

    public function __construct(array $data)
    {
        $this->data = $data;
        $this->sheetTitle = 'b2cs';
    }

    public function collection()
    {

        $groupedData = collect($this->data)->groupBy(function ($item) {
            return $item['HSNCode'] . '_' . $item['GSTRate'];
        })->map(function ($group) {
            $firstItem = $group->first();
            $taxableAmount = $group->sum('TaxableAmount');
            $cessAmount = $group->sum('CESS');
            $igstAmount = $group->sum('IGST');
            $totalQty = $group->sum('ItemQuantity');
            $totalCGST = $group->sum('CGST');
            $totalSGST = $group->sum('SGST');
            return [
                'HSN Code' => $firstItem['HSNCode'],
                'Description' => $firstItem['HSNDescription'] ?? '',
                'UQC' => 'NOS-NUMBER',
                'Total Quantity' => $totalQty,
                'Total Value' =>  $taxableAmount + $igstAmount + $totalCGST + $totalSGST + $cessAmount,
                'Taxable Value' => isset($taxableAmount) ? ($taxableAmount !== 0 ? $taxableAmount : '0') : '0',
                'Integrated Tax Amount' => isset($igstAmount) ? ($igstAmount !== 0 ?  $igstAmount : '0') : '0',
                'Central Tax Amount' =>  isset($totalCGST) ? ($totalCGST !== 0 ? $totalCGST : '0') : '0',
                'State/UT Tax Amount' => isset($totalSGST) ? ($totalSGST !== 0 ? $totalSGST : '0') : '0',
                'Cess Amount' => isset($cessAmount) ? ($cessAmount !== 0 ? $cessAmount : '0') : '0',
                'Rate' => $firstItem['GSTRate'] ?? '0',
            ];
        });

        $sortedData = $groupedData->sortBy('HSN Code');

        return $sortedData;
    }

    public function title(): string
    {
        return $this->sheetTitle;
    }

    public function headings(): array
    {
        $formattedData = $this->collection();

        $countHSN = $formattedData->count();
        $totalValue = $formattedData->sum('Total Value');
        $totalTax = $formattedData->sum('Taxable Value');
        $totalIGST = $formattedData->sum('Integrated Tax Amount');
        $totalCGST = $formattedData->sum('Central Tax Amount');
        $totalSGST = $formattedData->sum('State/UT Tax Amount');
        $totalCess = $formattedData->sum('Cess Amount');

        return [
            [
                'Summary For HSN(12)',
            ],
            [
                'No.of HSN',
                '',
                '',
                '',
                'Total Value',
                'Total Taxable Value',
                'Total Integrated Tax',
                'Total Central Tax',
                'Total State/UT Tax',
                'Total Cess',
                '',
            ],
            [
                $countHSN,
                '',
                '',
                '',
                $totalValue,
                $totalTax,
                $totalIGST,
                $totalCGST,
                $totalSGST,
                $totalCess,
                '',
            ],
            [
                'HSN Code',
                'Description',
                'UQC',
                'Total Quantity',
                'Total Value',
                'Taxable Value',
                'Integrated Tax Amount',
                'Central Tax Amount',
                'State/UT Tax Amount',
                'Cess Amount',
                'Rate',
            ]
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getRowDimension(4)->setRowHeight(42);

        $sheet->getStyle('A1:B1')->applyFromArray([
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color' => ['rgb' => '0070C0'],
            ],
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'ffffff'],
            ],
            'borders' => [
                'bottom' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ]);

        $sheet->getStyle('A2:K2')->applyFromArray([
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color' => ['rgb' => '0070C0'],
            ],
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'ffffff'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'outline' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ]);

        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $lastRow = $sheet->getHighestRow();
        foreach (range('A', 'K') as $column) {
            $sheet->getStyle('A5:' . $column . $lastRow)->applyFromArray([
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ]);
        }

        $sheet->getStyle('A4:K4')->applyFromArray([
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color' => ['rgb' => 'F8CBAD'],
            ],
            'font' => [
                'bold' => true,
                'color' => ['rgb' => '000000'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        $sheet->getStyle($sheet->calculateWorksheetDimension())->getFont()->setName('Times New Roman');
    }

    public function columnWidths(): array
    {
        return [
            'A' => 20,
            'B' => 14,
            'C' => 24,
            'D' => 22,
            'E' => 25,
            'F' => 18,
            'G' => 23,
            'H' => 22,
            'I' => 22,
            'J' => 22,
            'K' => 8,
        ];
    }
    public function rowHeight(): array
    {
        return [
            4 => 42,
        ];
    }
}

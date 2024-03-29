<?php

namespace App\Exports;

use App\Models\Tax;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Font;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class B2BExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles, WithColumnWidths , WithTitle
{
    /**
     * @return \Illuminate\Support\Collection
     */
    protected $data;
    protected $sheetTitle = 'b2b,sez,de';


    public function __construct(array $data)
    {
        $this->data = $data;
        $this->sheetTitle = 'b2b,sez,de';
    }

    public function collection()
    {
        $groupedData = collect($this->data)->groupBy(function ($item) {
            return $item['DocumentNumber'] . '_' . $item['GSTRate'];
        })->map(function ($group) {
            $firstItem = $group->first();
            $taxableAmount = $group->sum('TaxableAmount');
            $cessAmount = $group->sum('CESS');
            $igstAmount = $group->sum('IGST');
            return [
                'GSTIN/UIN of Recipient' => $firstItem['RecipientGSTIN'],
                'Receiver Name' => $firstItem['RecipientName'] ?? '',
                'Invoice Number' => $firstItem['DocumentNumber'] ?? '',
                'Invoice Date' => isset($firstItem['DocumentDate']) ? \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($firstItem['DocumentDate'])->format('d/m/Y') : '',
                'Invoice Value' => $firstItem['DocumentValue'] >= 0 ? $firstItem['DocumentValue'] : null,
                'Place Of Supply' => $firstItem['StateCode_Name'] ?? '',
                'Reverse Charge' => 'N',
                'Applicable % of Tax Rate' => '' ?? '',
                'Invoice Type' => $igstAmount !== 0 ? 'Intra-State supplies attracting IGST' : 'Regular B2B',
                'E-Commerce GSTIN' => '',
                //'IGST' => isset( $igstAmount) ? ( $igstAmount !== 0 ?  $igstAmount : '0') : '0',
                'Rate' =>  isset($firstItem['GSTRate']) ? ($firstItem['GSTRate'] !== 0 ? $firstItem['GSTRate'] : '0') : '0',
                'Taxable Value' => isset($taxableAmount) ? ($taxableAmount !== 0 ? $taxableAmount : '0') : '0',
                'Cess Amount' => isset($cessAmount) ? ($cessAmount !== 0 ? $cessAmount : '0') : '0',
            ];
        });



        $formattedData = $groupedData->filter(function ($item) {
            return !empty($item['GSTIN/UIN of Recipient']) && $item['Invoice Value'] > 0 && $item['Rate'] != 0;
        });

        $sortedData = $formattedData->sortBy('Invoice Date', SORT_NATURAL | SORT_FLAG_CASE);

        return $sortedData;
    }

    public function title(): string
    {
        return $this->sheetTitle;
    }
    public function headings(): array
    {

        $formattedData = $this->collection();

        $totalTax = $formattedData->sum('Taxable Value');
        $totalCess = $formattedData->sum('Cess Amount');
        $totalIgst = $formattedData->sum('IGST');

        $groupedData = collect($formattedData)->groupBy(function ($item) {
            return $item['Invoice Number'] . '_' . $item['Invoice Value'];
        });


        $uniqueInvoiceValues = $groupedData->map(function ($group) {
            return $group->first();
        });

        $totalInvoiceValue = $uniqueInvoiceValues->filter(function ($item) {
            return $item['Invoice Value'] > 0;
        })->sum('Invoice Value');

        return [
            [
                'Summary For B2B, SEZ, DE (4A, 4B, 6B, 6C)',
            ],
            [
                'No. of Recipients',
                '',
                'No. of Invoices',
                '',
                'Total Invoice Value',
                '',
                '',
                '',
                '',
                '',
                '',
                'Total Taxable Value',
                'Total Cess',
            ],
            [
                '',
                '',
                '',
                '',
                $totalInvoiceValue,
                '',
                '',
                '',
                '',
                '',
                '',
                $totalTax,
                $totalCess,
            ],
            [
                'GSTIN/UIN of Recipient',
                'Receiver Name',
                'Invoice Number',
                'Invoice date',
                'Invoice Value',
                'Place Of Supply',
                'Reverse Charge',
                'Applicable % of Tax Rate',
                'Invoice Type',
                'E-Commerce GSTIN',
                'Rate',
                'Taxable Value',
                'Cess Amount',
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

        $sheet->getStyle('A2:M2')->applyFromArray([
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

        foreach (range('A', 'M') as $column) {
            $sheet->getStyle($column . '2')->applyFromArray([
                'borders' => [
                    'left' => [
                        'borderStyle' => Border::BORDER_THIN,
                    ],
                    'right' => [
                        'borderStyle' => Border::BORDER_THIN,
                    ],

                ],
            ]);
        }

        $sheet->getStyle('A4:M4')->applyFromArray([
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
            'A' => 24,
            'C' => 15,
            'D' => 17,
            'E' => 15,
            'F' => 15,
            'G' => 15,
            'I' => 23,
            'K' => 10,
            'L' => 18,
            'M' => 12,
        ];
    }
    public function rowHeight(): array
    {
        return [
            4 => 42,
        ];
    }
}

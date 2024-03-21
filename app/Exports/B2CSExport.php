<?php

namespace App\Exports;

use App\Models\Tax;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class B2CSExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles, WithColumnWidths
{
    /**
     * @return \Illuminate\Support\Collection
     */
    protected $data;


    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function collection()
    {

        $groupedData = collect($this->data)->groupBy(function ($item) {
            return $item['DocumentNumber'] . '_' . $item['GSTRate'];
        })->map(function ($group) {
            $firstItem = $group->first();
            $taxableAmount = $group->sum('TaxableAmount');
            $cessAmount = $group->sum('CESS');
            return [
                'Type' => 'OE',
                'Place Of Supply' => $firstItem['StateCode_Name'] ?? '',
                'Applicable % of Tax Rate' => '',
                'Rate' => ($firstItem['GSTRate'] ?? '0'),
                'Taxable Value' => isset($taxableAmount) ? ($taxableAmount !== 0 ? $taxableAmount : '0') : '0',
                'Cess Amount' => isset($cessAmount) ? ($cessAmount !== 0 ? $cessAmount : '0') : '0',
                'E-Commerce GSTIN' => '',
            ];
        });

        return $groupedData;
    }

    public function headings(): array
    {
        $formattedData = $this->collection();

        $totalTax = $formattedData->sum('Taxable Value');
        $totalCess = $formattedData->sum('Cess Amount');

        return [
            [
                'Summary For B2CS(7)',
            ],
            [
                '',
                '',
                '',
                '',
                'Total Taxable Value',
                'Total Cess',
                '',
            ],
            [
                '',
                '',
                '',
                '',
                $totalTax,
                $totalCess,
                '',
            ],
            [
                'Type',
                'Place Of Supply',
                'Applicable % of Tax Rate',
                'Rate',
                'Taxable Value',
                'Cess Amount',
                'E-Commerce GSTIN',
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

        $sheet->getStyle('A2:G2')->applyFromArray([
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

        foreach (range('A', 'G') as $column) {
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

        $sheet->getStyle('A4:G4')->applyFromArray([
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
            'A' => 12,
            'C' => 23,
            'D' => 8,
            'E' => 15,
            'F' => 15,
        ];
    }
    public function rowHeight(): array
    {
        return [
            4 => 42,
        ];
    }
}

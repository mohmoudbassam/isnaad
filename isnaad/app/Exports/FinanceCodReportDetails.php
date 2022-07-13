<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;

class FinanceCodReportDetails implements WithHeadings, FromCollection, WithColumnWidths, WithEvents
{
    protected $data = '';

    /**
     * @return \Illuminate\Support\Collection
     */

    public function __construct($data)
    {

        $this->data = $data;

    }


    public function headings(): array
    {
        return [
            'Shipping Number', "Order Number",
            "Carrier",
            "Tracking Number", "Store", "Item Quantity",
            "Payment Method",
            "Cod Amount",
            "Country", "city", "Status", "Weight",
            "Shipping Date", "Delivery Date",
            'INV NO',
            "Order Created"

        ];
    }

    /**
     * @inheritDoc
     */
    public function collection()
    {
        return new Collection($this->data);
    }

    public function columnWidths(): array
    {
        return [
            'A' => 17,
            'B' => 17,
            'C' => 17,
            'D' => 17,
            'E' => 17,
            'F' => 17,
            'G' => 17,
            'H' => 17,
            'I' => 17,
            'J' => 17,
            'K' => 17,
            'L' => 17,
            'M' => 17,
            'N' => 17,
            'O' => 17,
            'P' => 17,


        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $cellRange = 'A1:P1';

                $allCellsCounts=count($this->data)+1;
                $allCellsCountsRange="A$allCellsCounts:P$allCellsCounts";

                $event->sheet->getDelegate()
                    ->getStyle($cellRange)
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->getStyle('A1:P'.$allCellsCounts)->getAlignment()->setHorizontal('center');
                $event->sheet->getStyle('A1:P'.$allCellsCounts)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                ]);
                $event->sheet->styleCells(
                    $cellRange,
                    [
                        //Set border Style
                        'borders' => [
                            'outline' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                                'color' => ['argb' => 'EB2B02'],
                            ],

                        ],

                        //Set font style
                        'font' => [
                            'name'      =>  'Calibri',
                            'size'      =>  11,
                            'bold'      =>  true,
                            'color' => ['argb' => 'EB2B02'],
                        ],

                        //Set background style


                    ]
                );
                $event->sheet->styleCells(
                    "A$allCellsCounts:P$allCellsCounts",
                    [
                        //Set border Style
                        'borders' => [
                            'outline' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                                'color' => ['argb' => 'EB2B02'],
                            ],

                        ],

                        //Set font style
                        'font' => [
                            'name'      =>  'Calibri',
                            'size'      =>  11,
                            'bold'      =>  false,
                            'color' => ['argb' => 'EB2B02'],
                        ],


                    ]

                );
                   $event->sheet->styleCells(
                       $allCellsCountsRange,
                       [
                           //Set border Style
                           'borders' => [
                               'outline' => [
                                   'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                                   'color' => ['argb' => 'EB2B02'],
                               ],

                           ],

                           //Set font style
                           'font' => [
                               'name'      =>  'Calibri',
                               'size'      =>  11,
                               'bold'      =>  false,
                               'color' => ['argb' => 'EB2B02'],
                           ],




                    ]
                );
            }

        ];

    }
}

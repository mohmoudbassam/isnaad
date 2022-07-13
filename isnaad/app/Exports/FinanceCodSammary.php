<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Sheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class FinanceCodSammary implements WithHeadings, WithTitle, WithEvents ,FromArray ,WithColumnWidths
{
    protected $data = '';


    /**
     * @param $data
     * @param string $name
     */

    public function __construct($data)
    {


        $this->data = $data;

    }


    public function headings(): array
    {
        return [
            'Store', 'Payment', 'Count Of Store', 'Sum Of Cod Amount'
        ];
    }


    public function title(): string
    {
        return 'Summary';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                $cellRange = 'A1:D1';
                $event->sheet->getDelegate()
                    ->getStyle($cellRange)
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
//                $event->sheet->styleCells('A:D', [
//                    'alignment' => [
//                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
//                    ],
//                ]);
                $event->sheet->getStyle('A1:D48')->getAlignment()->setHorizontal('center');
                // $event->sheet->getDelegate()->getStyle($cellRange)->getAlignment()->setWrapText(true);
                $event->sheet->getDelegate()->getRowDimension(1)->setRowHeight(15);
                $event->sheet->getDelegate()->mergeCells('A4:B4');
                $event->sheet->getDelegate()->mergeCells('A5:C5');
                $event->sheet->getDelegate()->mergeCells('A6:B6');
                $event->sheet->getDelegate()->mergeCells('A7:C7');
                $event->sheet->getDelegate()->mergeCells('A8:C8');



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
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => [
                                'rgb' => 'f5e942',
                            ]
                        ],

                    ]
                );

                $event->sheet->styleCells(
                    'A5:D5',
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
                            'size'      =>  12,
                            'bold'      =>  true,
                            'color' => ['argb' => 'EB2B02'],
                        ],

                        //Set background style
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => [
                                'rgb' => '4d79ff',
                            ]
                        ],

                    ]
                );
                $event->sheet->styleCells(
                    'A4:D4',
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
                            'size'      =>  12,
                            'bold'      =>  true,
                            'color' => ['argb' => 'EB2B02'],
                        ],

                        //Set background style
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => [
                                'rgb' => 'ff4d4d',
                            ]
                        ],

                    ]
                );
                $event->sheet->styleCells(
                    'A6:D6',
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
                            'size'      =>  12,
                            'bold'      =>  true,
                            'color' => ['argb' => 'EB2B02'],
                        ],

                        //Set background style
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => [
                                'rgb' => 'ff4d4d',
                            ]
                        ],

                    ]
                );
                $event->sheet->styleCells(
                    'A7:D7',
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
                            'size'      =>  12,
                            'bold'      =>  true,
                            'color' => ['argb' => 'EB2B02'],
                        ],

                        //Set background style
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => [
                                'rgb' => '4d79ff',
                            ]
                        ],

                    ]
                );
                $event->sheet->styleCells(
                    'A8:D8',
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
                            'size'      =>  13,
                            'bold'      =>  true,
                            'color' => ['argb' => 'EB2B02'],
                        ],

                        //Set background style
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => [
                                'rgb' => '29a329',
                            ]
                        ],

                    ]
                );
                $event->sheet->getStyle('A1:D8')->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                ]);
            },
        ];
    }



    public function array(): array
    {
        return $this->data;
    }

    public function columnWidths(): array
    {
        return [
            'A' => 20,
            'B' => 20,
            'C' => 20,
            'D' => 24,

        ];
    }
}

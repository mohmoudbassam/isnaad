<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;

class ErrorSeparateExport implements FromCollection, WithHeadings, WithTitle
{
    protected $data='';

    /**
     * @return \Illuminate\Support\Collection
     */

    public function __construct($data)
    {

        $this->data=$data;

    }



    public function headings(): array
    {
        return [
            'traking',
            'error'

        ];
    }

    /**
     * @inheritDoc
     */
    public function collection()
    {
        return new Collection($this->data);
    }

    public function title(): string
    {
       return 'error';
    }


}

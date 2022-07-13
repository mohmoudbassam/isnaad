<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromArray;

class interrupted implements WithHeadings ,FromCollection
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
            'shipping_number',
'date',
'store',
'carrier',
'issue',


        ];
    }

    /**
     * @inheritDoc
     */
    public function collection()
    {
        return new Collection($this->data);
    }
}

<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromArray;

class BillingExport implements WithHeadings ,FromCollection
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
            'INV',
            'Description_from_date',
            'Description_to_date',
            'Account',
            'Invoice Date',
            'Last Date',
            'Statment',
            'Isnaad Invoic',
            'Cod',
            'Balance'

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

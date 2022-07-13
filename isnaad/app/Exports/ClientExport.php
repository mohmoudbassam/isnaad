<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromArray;

class ClientExport implements WithHeadings ,FromCollection
{
    protected $data='';
    protected $listOfMonth='';

    /**
     * @return \Illuminate\Support\Collection
     */

    public function __construct($data,$listOfMonth)
    {

        $this->data=$data;
        $this->listOfMonth=$listOfMonth;

    }



    public function headings(): array
    {
        $main_header=[
            'Name',
            'Website',
            'Phone',
            'Contact Person',
            'Email',
            'Orders Number'
        ];
      return  array_merge($main_header,$this->listOfMonth);

    }

    /**
     * @inheritDoc
     */
    public function collection()
    {
        return new Collection($this->data);
    }
}

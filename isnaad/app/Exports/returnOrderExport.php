<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithValidation;


class returnOrderExport implements FromCollection ,WithHeadings
{
    protected $data='';
    /**
     * @return \Illuminate\Support\Collection
     */
    public function __construct($data)
    {
        $this->data=$data;
    }
    public function collection()
    {
        return new Collection($this->data);
    }
    public function headings(): array
    {
        return [
            'Shipping Number',"error",

        ];
    }
}

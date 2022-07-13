<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromArray;

class insaadReturn implements WithHeadings, FromCollection
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
            'shipping_number',
            'order_number',
            'carrier',
            'tracking number',
            'store',
            'total item',
            'payment',
            'cod amount',
            'city',
            'country',
            'status',
            'weight',
            'shipping_date',
            'delivary_date',
            'carrier charge',
            'shipping price',
            'diff',
            'inv',
            'order created',


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

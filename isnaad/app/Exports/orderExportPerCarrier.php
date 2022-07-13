<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class orderExportPerCarrier implements FromCollection ,WithHeadings ,WithTitle ,WithMapping
{
    protected $carrier='';
    protected $order='';
    /**
     * @return \Illuminate\Support\Collection
     */
    public function __construct($carrier,$order)
    {
        $this->carrier=$carrier;
        $this->order=$order;
    }
    public function collection()
    {
        return new Collection($this->order);
    }
    public function headings(): array
    {
         return [
            'Carrier ',"Tracking#","Shipping#","Order#",
            'City',"Weight","Store id","Cod Amount"

        ];
    }


    /**
     * @inheritDoc
     */
    public function title(): string
    {
        return $this->carrier;
    }

    /**
     * @inheritDoc
     */
    public function map($row): array
    {

        return [
             $row['carrier'],
            $row['tracking_number'],
            $row['shipping_number'],
            $row['order_number'],
            $row['city'],
            $row['weight'],
            $row['store_id'],
             $row['cod_amount'],

        ];
    }
}

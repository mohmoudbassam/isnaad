<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromArray;
class trakingExport implements  WithMultipleSheets
{
    use Exportable;
    protected $carrier;

    protected $all_orders_group_carrier='';
    /**
     * @return \Illuminate\Support\Collection
     */

    public function __construct($carrier,$all_orders_group_carrier)
    {


        $this->carrier = $carrier;
        $this->all_orders_group_carrier=$all_orders_group_carrier;


    }




    /**
     * @inheritDoc
     */
    public function sheets(): array
    {
        $sheets = [];
        foreach ($this->carrier as $carrier =>$order) {
            $sheets[] = new orderExportPerCarrier($carrier,$order);
        }
        return  $sheets;
    }

    /**
     * @inheritDoc
     */

}

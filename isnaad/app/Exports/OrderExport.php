<?php

namespace App\Exports;

use App\order;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithCustomQuerySize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Queue\SerializesModels;


class OrderExport implements FromCollection ,WithHeadings
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
               'Shipping Number',"Order Number",
            "Carrier",
            "Tracking Number" ,"Store" ,"Item Quantity",
            "Payment Method",
            "Cod Amount","Name",
            "Address","Phone",
            "City","Country","Status","Last Status","Weight",
            "Shipping Date" ,"Delivery Date","Comment",'carrier return','chargable Weight','actual Weight',
            auth()->user()->hasPermissionTo('carrier_charge_view')? 'Carrier charge':null,

            auth()->user()->hasPermissionTo('isnaarReport_shippingPrice')? 'Shipping Price':null,
            auth()->user()->hasPermissionTo('isnaarReport_diff')? 'Diff':null, "Order Created"
        ];
    }



    public function collection()
    {
        return  new Collection($this->data);
    }
}

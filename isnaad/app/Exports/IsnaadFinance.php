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


class IsnaadFinance implements FromCollection ,WithHeadings
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


        return array_filter([
            'Shipping Number',"Order Number",
            "Carrier",
            "Tracking Number" ,"Store" ,"Item Quantity",
            "Payment Method",
            "Cod Amount",
            "Country","city","Status","Weight",
            "Shipping Date" ,"Delivery Date",
            (!(auth()->user()->hasPermissionTo('carrier_charge_view')  && request('cost')))?NULL: 'chargable Weight',
            (!(auth()->user()->hasPermissionTo('carrier_charge_view')  && request('cost')))?NULL: 'actual Weight',
            (!(auth()->user()->hasPermissionTo('carrier_charge_view')  && request('cost')))?NULL: 'Carrier charge',
            (!(auth()->user()->hasPermissionTo('carrier_charge_view')  && request('cost')))?NULL:'Shipping Price',
            (!(auth()->user()->hasPermissionTo('carrier_charge_view')  && request('cost')))?NULL :'Diff',
            'INV NO',
            "Order Created"
        ]);
    }



    public function collection()
    {
        return  new Collection($this->data);
    }
}

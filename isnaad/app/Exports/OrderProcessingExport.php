<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class OrderProcessingExport implements FromCollection ,WithHeadings
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
             'Shipping Number',"Order Number",
            "Carrier ","Cod Amount",
            "Tracking Number" ,"Store" ,'Quantity',
            "Payment Method",
            "Name",
            "Address","Phone",
            "City","Country","Printed",
           "Order Created"
        ];
    }


}

<?php

namespace App\Exports;

use App\user;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;

class OrderExportInoiceReport implements FromCollection ,WithHeadings
{
    protected $data='';
    protected $needCountry='';
    /**
    * @return \Illuminate\Support\Collection
    */
    public function __construct($data,$needCountry)
    {
        $this->data=$data;
        $this->needCountry=$needCountry;
    }
    public function collection()
    {
        return new Collection($this->data);
    }
    public function headings(): array
    {
        if($this->needCountry){
         //  dd('sdfsdfsdsdsdsdsdsdsdsdsdsdsd');
            return [
                'Account',
                'Statment Name',
                'Date',
                'Total Item',
                'weight',
                'Total Qty',
                'Service Type',
                'ID_Reg',
                'Description','country', 'city' ,'cod','carrier charge','diff','extra weight price','Cost',

            ];
        }
      return [
            'Account',
            'Statment Name',
            'Date',
            'Total Item',
            'Total Sku',
            'Total Qty',
            'Service Type',
            'ID_Reg',
            'Description',
            'country',
            'city',

            'Cost'



        ];
    }



}

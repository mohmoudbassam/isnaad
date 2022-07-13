<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Facades\Excel;

class SamaryInvoice implements FromCollection , WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public $data =[];
    public function __construct($data){
        $this->data=$data;
    }
    public function collection()
    {

        $items = [];
        $i=1;
        foreach ($this->data as $key => $item) {

            $items[$key]['count'] = $i;
            $items[$key]['store'] = $item->name ?? '-';

            $items[$key]['Manger'] =$item->store_manger->name ?? ' General';
            $items[$key]['number_of_invoice'] = count($item->statment);
            $items[$key]['amount'] = $item->statment->sum(function ($statment){
                return str_replace(',','',$statment->net_blance);
            });
            $i++;
        }
        $items['i']['count'] = 'Total';
        $items['i']['store'] =  '-';
       
        $items['i']['manger'] = ' ';
        $items['i']['number_of_invoice'] = $this->data->reduce(function ($num,$store){
            return $num + count($store->statment);

        },0);
        $items['i']['amount'] = $this->data->reduce(function ($num,$store){
           return $num+ $store->statment->sum(function ($statment){
               return str_replace(',','',$statment->net_blance);
           });
        },0);
       // dd($items['i']['amount']);

        return collect($items);
    }

    public function headings(): array {
        return [
            '#',
            'store',

            'Account Manger',
            'number of invoice',
            'amount',

        ];
    }



}

<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromArray;

class HandlingPick implements WithHeadings, FromCollection ,WithMapping
{
    protected $data = '';
    protected $Confermed = '';

    /**
     * @return \Illuminate\Support\Collection
     */

    public function __construct($data,$Confermed=false)
    {

        $this->data = $data;
        $this->Confermed=$Confermed;

    }


    public function headings(): array
    {
        if($this->Confermed){
            return [
                'Account',
                'Statment Name',
                'Date',
                'Total Item',
                'weight',
                'Total Qty',
                'Service Type',
                'ID_Reg',
                'Description',
                'country',
                'city',
               
                'Cost',
               
            ];
        }else{
            return [
                'Account',
                'Statment Name',
                'Date',
                'Total Item',
                'weight',
                'Total Qty',
                'Service Type',
                'ID_Reg',
                'Description',
                'country',
                'city',
                 
                  'Cost',
                  'extra price weight',
                  'tracking_number',
                  'cod_amount',

            ];
        }

    }

    /**
     * @inheritDoc
     */
    public function collection()
    {

        return new Collection($this->data);
    }

    public function map($row): array
    {

        if($this->Confermed){
         
        return [

            $row['store']??'',
            $row['statment_name']??'',
            $row['date']??'',
            $row['total_item']??'',
            $row['weight']??'',
            $row['Total_Qty']??'',
            $row['Service_Type']??'',
             $row['ID_Reg']??'',
            
            $row['desc']??'',
            $row['country']??'',
            $row['city']??'',
            $row['Cost_Value'],

        ];
    }else{
        return [
            $row['store']??'',
            $row['statment_name']??'',
            $row['date']??'',
            $row['total_item']??'',
            $row['weight'] ?? '',
            $row['Total_Qty']??'',
            $row['Service_Type']??'',
            $row['ID_Reg']??'',
            $row['desc']??'',
            $row['country']??'',
            $row['city']??'',
            
            $row['Cost_Value']??'',
            $row['extraWeightPrice']??'',
            $row['tracking_number']??'',
            $row['cod_amount']??'',


        ];
    }


    }
}

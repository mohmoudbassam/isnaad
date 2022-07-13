<?php

namespace App\Imports;

use App\Models\storage;
use App\store;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Carbon\Carbon;
class StorageImport implements ToModel, WithStartRow
{
    /**
     * @param Collection $collection
     */
    private $store;
    private $plan;


    public function __construct($plan, $store)
    {
        $this->store =  $store;
        $this->plan = $plan;
    }

    public function model(array $row)
    {




        $cost=0;
        $desc= '';

        //  dd($row[2]);
        if ($row[2] == 'Pallet') {
            $allow_pallet =$row[3] - ($this->plan->allow_pallet ?? 0);
            $cost= $this->plan->pallet* $allow_pallet ;
            $desc.="$row[3] Pallet in use [Daily BIN Volumes]" ;
            if ($cost < 0) {
                $cost = 0;
            }
            $type = 1;
        } elseif ($row[2] == 'Shelf' && $this->plan->shelves!=0) {
            $desc.="$row[3] Shelf in use [Daily BIN Volumes]";
            $allow_self =$row[3] - ($this->plan->allow_selves ?? 0);
            $cost=$this->plan->shelves*$allow_self;

            if ($cost < 0) {
                $cost = 0;
            }
            $type = 2;
        } elseif($row[2] == 'cold' ) {
            $desc.= "$row[3] cold in use [Daily BIN Volumes]";
            $cost= $this->plan->cold * $row[3];
            $type = 3;
        }elseif(  $row[2] == 'Special' ){

            $desc.= "$row[3] special in use [Daily BIN Volumes]";
            $cost= $this->plan->special * $row[3];
            $type = 4;
        }else{

            $desc.= "$row[3] unit_price in use [Daily BIN Volumes]";
            $cost= $this->plan->unit_price * $row[4];
            if($row[2]=='Shelf'){
                $type =2;
            }
        }
        try {
            $type=$type;
        }catch (\Exception $e){
//dd($row[2],$row[1],$row[3],$row[4]);
        }
        return new storage([
            'store_id' => $this->store->account_id,
            'Date' =>Carbon::parse($row[1])->format('Y-m-d') ,
            'type' => $type,
            'sum_of_sin_volume' =>$row[3],
            'sum_of_product_qty' =>$row[4],
            'description'=>$desc,
            'cost' => $cost,

        ]);



    }

    public function startRow(): int
    {
        return 2;
    }

}

<?php

namespace App\Imports;

use App\order;
use DateTime;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class orderImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $orders = new order(
            [
                'carrier' => $row['carrier'],
                'shipping_date'=> date('Y-m-d',strtotime($row['shipping_date'])),
                'tracking_number'=> $row['tracking_number'],
            ]
        );

    }
}

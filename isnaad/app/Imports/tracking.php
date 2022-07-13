<?php

namespace App\Imports;

use App\order;
use DateTime;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class tracking implements ToModel, WithHeadingRow
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
                'tracking_num' => $row['tracking_number'],

            ]
        );

    }
}

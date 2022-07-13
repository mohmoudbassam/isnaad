<?php

namespace App\Imports;

use App\order;
use DateTime;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class importPick implements ToModel, WithHeadingRow
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
                'shipping_num' => $row['shipping_num'],
                'Status' => $row['Status'],
                'Delivery_date' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['delivery_date']),
            ]
        );

    }
}

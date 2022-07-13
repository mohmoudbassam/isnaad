<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class FinanceCodReport implements WithMultipleSheets
{
    protected $summary = '';
    protected $data = '';

    /**
     * @return \Illuminate\Support\Collection
     */

    public function __construct($data,$summary)
    {

        $this->summary = $summary;
        $this->data = $data;


    }


    public function sheets(): array
    {
        $sheets[]=new FinanceCodSammary($this->summary);
        $sheets[]=new FinanceCodReportDetails($this->data);
        return $sheets;
    }
}

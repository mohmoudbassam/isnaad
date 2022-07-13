<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class MainStorageImport implements WithMultipleSheets
{

    private $plan;
private $store;
    public function __construct($plan, $store)
    {
         $this->plan = $plan;
           $this->store =  $store;
    }

    public function sheets(): array
    {
        return [
            new StorageImport($this->plan, $this->store)
        ];
    }
}

<?php

namespace App\Models;

use App\carrier;
use App\store;
use Illuminate\Database\Eloquent\Model;

class invoice_discount extends Model
{
    protected $table = 'invoice_discount';
    protected $primaryKey = 'id';
    protected $appends=['service_type_ser'];
    protected $guarded = [];

    public function store()
    {
        return $this->belongsTo(store::class, 'store_id', 'account_id');
    }

    public static function Discription($description){
        $dis='';
        switch ($description) {
            case 0:
                $dis="Add & Discount Handling: Pick & Pack Services";
                break;
            case 2:
                $dis="Add & Discount Shipping: Carrier & Transportation";
                break;
                case 4:
                $dis="Add & Discount Replenishment : Service & Barcoding";
                break;
                case 5:
                $dis="Add & Discount Fee System price";
                break;
                case 6:
                $dis="Add & Discount Return: Handling & Transportation";
                break;
                case 7:
                $dis="Add & Discount Client Shipping: Client Return - Carrier & Transportation";
                break;
                case 8:
                $dis="Add & Discount Shipping: international  Return - Carrier & Transportation";
                break;
                case 9:
                $dis="Add & Discount Storage";
                break;
                case 10:
                $dis="Add & Discount Isnaad Transportaion";
                break;
            case 3:
                $dis="Add & Discount ";
                break;
        }
        return $dis;
    }
    public  function getServiceTypeSerAttribute(){
        $dis='';
        switch ($this->service_type) {
            case 0:
                $dis=" Handling: Pick & Pack Services";
                break;
            case 2:
                $dis=" Shipping: Carrier & Transportation";
                break;
                case 4:
                $dis="Replenishment : Service & Barcoding";
                break;
                case 5:
                $dis="Fee System price ";
                break;
                case 6:
                $dis="Return: Handling & Transportation";
                break;
                case 7:
                $dis="Shipping: Client Return - Carrier & Transportation";
                break;
                case 8:
                $dis="Shipping: international  Return - Carrier & Transportation";
                break;
                case 9:
                $dis="Storage: Shelving and warehousing";
                break;
                case 10:
                $dis="Isnaad Transportaion";
                break;
            case 3:
                $dis="Discount general";
                break;
        }
        return $dis;
    }
    public function ScopewhenStore($query, $request)
    {

        $query->when($request->store, function ($q)use($request) {

            return $q->where('store_id', $request->store);
        });
    }

}

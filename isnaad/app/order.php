<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\Models\zajil_request;
use App\Models\descountOrder;
use Illuminate\Database\Eloquent\Builder;
use App\Models\in_transit;
use App\Models\box;

class order extends Model
{
    protected $fillable = [

        'carrier',
        'ship_method',
        'tracking_number',
        'cod_amount',
        'awb_url',
        'store_id',
        'shipping_number',
        'order_number',
        'shipping_charge',
        'cod_charge',
        'processing_status',
        'order_status',
        'Last_Status',
        'processing_date',
        'shipping_date',
        'delivery_date',
        'weight',
        'description',
        'Qty_Item',
        'fname',
        'lname',
        'country',
        'city',
        'state',
        'zip_code',
        'phone',
        'address_1',
        'address_2',
        'carrier_charge',
        'shiping_date_time',
        'printed_time',
        'Comments',
        'return_date_carrier',
        'isnaad_return_date',
        'active',
        'actulWeight',
        'chargalbeWeight',
        'created_at',
        'inv_num'
    ];

    public function store()
    {
        return $this->belongsTo(store::class, 'store_id', 'account_id');
    }

    public function carriers()
    {
        return $this->belongsTo(carrier::class, 'carrier', 'name');
    }

    public function order_printed()
    {
        return $this->belongsTo(order_printed::class, 'id', 'order_id');
    }

    public function deliverd_orders()
    {
        return $this->hasOne(deliverd_orders::class, 'order_id', 'id');
    }

    public function orderZajil()
    {
        return $this->hasOne(zajil_request::class, 'order_id', 'id');
    }

    public function order_descount()
    {
        return $this->hasOne(descountOrder::class, 'order_number', 'order_number');
    }

    public function in_transit()
    {
        return $this->hasOne(in_transit::class, 'order_id', 'id');

    }

    public function scopeOnThisDay($query)
    {
        // dd(Carbon::now()->format('Y-m-d'));
        return $query->whereDate('created_at', Carbon::now()->format('Y-m-d'));
    }

    public function scopeActive($query)
    {
        // dd(Carbon::now()->format('Y-m-d'));
        return $query->where('active', 1);
    }

    //->where('city','Riyadh')->count()
    public function scopeRiyadh($query)
    {
        // dd(Carbon::now()->format('Y-m-d'));
        return $query->where('city', 'Riyadh');
    }

    public function scopeOutRiyadh($query)
    {
        // dd(Carbon::now()->format('Y-m-d'));
        return $query->where('city', '!=', 'Riyadh');
    }

    public function countries()
    {
        // dd(Carbon::now()->format('Y-m-d'));
        return $this->hasOne(country::class, 'code', 'country');
    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('testAcount', function (Builder $builder) {

            if (auth()->check()) {
                if (auth()->user()->id == 103) {
                    $builder->where('store_id', '=', 2);
                }

            }

        });
    }

    public function box()
    {
        return $this->belongsToMany(box::class, 'order_box', 'order_id', 'box_id')
            ->withPivot('id')
            ->withTimestamps();
    }

    public function order_plan()
    {
        dd($this->store);
    }
}

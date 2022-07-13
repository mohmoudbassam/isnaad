<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Models\store_cancel;
use App\Models\store_plane;
use App\Models\masterPlan;
use Illuminate\Support\Facades\DB;
class store extends Model
{

   public function orders(){
        return $this->hasMany(order::class,'store_id','account_id')->where('active','=','1');
    }
        protected $fillable=[
        'name',
        'email',
        'password',
        'contact_person',
        'shipping_charge_in_ra',
        'shipping_charge_out_ra',
        'add_cost_out_sa',
        'add_cost_in_sa',
        'cod_charge',
        'weight_in_sa',
        'weight_out_sa',
        'website','phone','status','user_id','api_key','account_id',
        'shipping_charge_international','Reciving_replanchment','return_charge_each_extra','each_2nd_units','system_fee','processing_charge','isnaad_packaging','return_charge'
          ,'return_charge_in','return_charge_out',
            'account_manger'
    ];
     public function store_cancel(){
        return $this->hasOne(store_cancel::class,'store_id','id');
    }
     public function hasPlan(){
        return $this->hasMany(store_plane::class,'store_id','account_id');
    }



    public function getOrderNumberAttribute()
    {
            return $this->orders()->count();
    }
      public function ReturnOrder(){
         return $this->hasManyThrough(
             deliverd_orders::class,order::class,
             'store_id',
             'order_id',
             'account_id',
             'id'
         )->where('order_status','Returned')->where('active','1');
    }
       public function getOrderNumberPerMonthAttribute()
    {

        return $this->orders()
            ->select(DB::raw('count(created_at) as `numberOfOrder`'), DB::raw('YEAR(created_at) year, MONTH(created_at) month'),DB::raw("DATE_FORMAT(created_at,'%Y-%m') as monthYear"))
            ->groupBy('year','month')->get();
    }

    public function getOrdersInMonth($month){
        return $this->orders()->whereMonth('created_at',$month)->count();
    }
       public function masterPlan()
    {
        return $this->hasMany(masterPlan::class, 'store_id', 'account_id');
    }
    public function statment(){
         return $this->hasMany(statment::class, 'account_id','account_id');
    }
       public function store_manger(){
         return $this->belongsTo(user::class, 'account_manger','id');
    }
    public function is_cr(){
         return $this->is_cr;
    }

    public function ScopeTotalNetBalance($q){
        return $q->select(['total_net_balance'=>statment::query()
            ->select(DB::Raw('sum((cod - total_amount + edit) - (select IFNULL(SUM(payment),0) from payments where payments.invoice_id=statment.id))'))
            ->whereColumn('stores.account_id','=','statment.account_id')
            ->where('paid','=','0') ]);
    }
    public function scopeWhenInvoiceType($q, $type)
    {

        return $q->when( ($type >=0)  , function ($q) use ($type) {

            if ($type == 0) {
                return $q;
            } else if ($type == 1) {
                return $q->having('total_net_balance', '<', "0");
            } elseif ($type) {
                return $q->having('total_net_balance', '>', "0");
            }
        });

    } public function scopeWhenActive($q, $type)
    {

        return $q->when( $type  , function ($q) use ($type) {

            if ($type == 1) {
                return $q->where('active',0);
            } else {
                return $q->where('active',1);
            }
        });

    }
    public function user(){
         return $this->belongsTo(user::class,'user_id');
    }
}

<?php

namespace App;

use App\Models\payments;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Mpdf\Tag\P;
use Illuminate\Database\Eloquent\Builder;

class statment extends Model
{
    protected $table = 'statment';
    //  protected $appends = ['net_blance', 'main_blance', 'total_payments'];
    protected $fillable = [
        'description_from_date', 'description_to_date', 'statment_date',
        'initial_date', 'last_date', 'paid', 'account_id', 'inv', 'total_amount', 'cod', 'balance', 'edit', '3_payment', '1_payment',
        '2_payment', '2_payment_date', '3_payment_date', '1_payment_date', '4_payment', '4_payment_date', 'note', 'confirmed_id'
    ];

    public function acount()
    {
        return $this->belongsTo(store::class, 'account_id', 'account_id');
    }

    public function file()
    {
        return $this->hasMany(statment_file::class, 'statment_id', 'id');
    }

    public function getbalanceAttribute($value)
    {
        return number_format($value, 2, '.', ',');
    }

    public function getcodAttribute($value)
    {
        return number_format($value + $this->edit, 2, '.', ',');
    }

    public function getTotalAmountAttribute($value)
    {
        return number_format($value, 2, '.', ',');
    }

    public function getNetBlanceAttribute()
    {
        $netBlance = number_format($this->getBlance() - $this->getTotalPaymentsAttribute(), 2);


        return $netBlance;


    }

    public function getMainBlanceAttribute()
    {
        return round($this->getBlance(), 2);
    }

    private function getBlance()
    {

        return (float)$this->getOriginal('cod') - (float)$this->getOriginal('total_amount') + (float)$this->getOriginal('edit');
    }

    public function getTotalPaymentsAttribute()
    {

        return $this->payments()->sum('payment') + $this->paymentFromsDeduct()->sum('payment');

        //  return ($this->balance- ($this['1_payment']));
    }


    public function payments()
    {
        return $this->hasMany(payments::class, 'invoice_id', 'id');
    }

    public function paymentFromsDeduct()
    {
        return $this->hasMany(payments::class, 'from_invoice', 'id');
    }

    public function paymentToDeduct()
    {
        return $this->hasMany(payments::class, 'invoice_id', 'id')->whereNotNull('from_invoice');
    }

    public function statment()
    {
        return $this->hasMany(statment::class, 'account_id', 'account_id');
    }

    public function scopeWhenDate($q, $request)
    {

        return $q->when($request->from, function ($q) use ($request) {

            $from = Carbon::parse($request->from)->format('Y-m-d');
            $to = Carbon::parse($request->to)->format('Y-m-d');
            $q->whereBetween('description_from_date', [$from, $to]);
        });

    }

    public function scopeNetBalance($q)
    {

        return $q->addSelect(['net_balance' => \App\Models\payments::query()->select(DB::Raw('(cod - total_amount + edit)  - IFNULL(sum(payment),0)'))
            ->whereColumn('invoice_id', 'statment.id')
            ->take(1)
        ]);
    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('testacount', function (Builder $builder) {
            if (auth()->user() != null) {
                if (auth()->user()->id == 103) {
                    $builder->where('account_id', '=', 2);
                }
            }
        });
    }


}

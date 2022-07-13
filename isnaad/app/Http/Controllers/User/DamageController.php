<?php

namespace App\Http\Controllers\User;


use App\carrier;
use App\Http\Controllers\Controller;
use App\Http\Requests\damage_sku_request;
use App\Http\Requests\DamageRequest;
use App\Models\damage;
use App\Models\damage_sku;
use App\Models\images;
use App\order;
use App\order_printed;
use App\user;


use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Response;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


use App\store;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Mpdf\Tag\P;

use Yajra\DataTables\DataTables;


class DamageController extends Controller
{

    public function index()
    {
        return view('m_design.Damage.Damage');
    }

    public function addDamage()
    {
        $stors = store::all();
        $carriers = carrier::all();
        return view('newDesign.damage.addDamage', ['stors' => $stors, 'carriers' => $carriers]);
    }

    public function storeDamage(DamageRequest $request)
    {

        $checkOrder = order::where([['shipping_number', $request->shipping_number], ['active', 1]])->first();
        if (!$checkOrder) {
            return redirect()->back()->withErrors(['this order not found']);
        }
        $damage = damage::create([

            'shipping_number' => $request->shipping_number,
            'paid' => $request->paid == 'paid' ? 1 : 0,
            'transaction_cost' => $request->Transaction_Cost,
            'transaction_id' => $request->Transaction_ID,
            'created_by' => auth()->user()->id,
            'account_id' => $request->store,
            'order_number' => $request->order_number,
            'carrier_id' => $request->carrier,
            'date' => $request->date,
            'traking_number' => $request->traking_number,
            'invo_num'=>$request->invo_num
        ]);
        if ($request->has('image')) {
            $this->saveDamageImage($request->image, $damage);
        }
          Session::put('sdf','name');

        return redirect()->route('damage');
    }

    private function saveDamageImage($image, $damage)
    {
        $realName = $image->getClientOriginalName();
        $filename = time() . '.' . $image->getClientOriginalExtension();

        $path = 'images/damage/';
        $image->move($path, $filename);
        images::create([
            'real_name' => $realName,
            'file_name' => $filename,
            'created_by' => auth()->user()->id
            , 'type' => 0,
            'fk' => $damage->id,

        ]);
    }

    public function get_damageis(Request  $request)
    {

        $damage = damage::query();
        if($request->has('from_date') && $request->get('from_date')!=''){

          $damage=  $damage->where('date','>',$request->get('from_date'))->orderBy('date');
        }
        if($request->has('paid') && $request->get('from_date')!='all'){
             if($request->paid=='paid'){
                 $damage->Where('paid',1);
             }elseif($request->paid =='unpaid'){
                $damage->Where('paid',0);
            }

        }

                return Datatables::of($damage->with(['store', 'carrier','image']))
            ->addColumn('actoin', function ($damage) {
//                return '
//               <a  class="action-edit" ><i class="feather icon-edit"></i></a>
//                      <span class="action-delete" ><i class="feather icon-trash"></i></span>
//                ';
                return '<div class="btn-group">
                                            <div class="dropdown">
                                                <button class="btn btn-primary dropdown-toggle mr-1 mb-1 waves-effect waves-light" type="button" id="dropdownMenuButton707" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    action
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton707" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 37px, 0px);">
                                                    <a class="dropdown-item" href="' . url('add-sku/' . $damage->id) . '"> <i class="feather icon-plus"> add sku</i></a>
                                                    <a class="dropdown-item"  onclick="showUpdateModal(' . $damage->id . ')" href="#"> <i class="feather icon-edit"> edit</i> </a>
                                                    <a class="dropdown-item" target="_blank" href="'.url('dowload-damage-invoic/'.$damage->id).'"> <i class="feather icon-file"> invice</i> </a>
                                                       <a class="dropdown-item" target="_blank" href="'.url('images/damage/'.( $damage->image!=null ?$damage->image->file_name:null)).'"> <i class="feather icon-image">show image</i> </a>
                                                    <a class="dropdown-item" href="#"> <i class="feather icon-trash">delete</i> </a>

                                                </div>
                                            </div>
                                        </div>';
            })
            ->addColumn('image', function ($damage){

                 if($damage->image){

                     return "available";
                 }else{
                     return "not available";
                 }
            })->
            rawColumns(['actoin','image'])

            ->make(true);
    }

    public function get_damage(Request $request)
    {
        $damage = damage::find($request->id)->load('image');
        $store = store::all();
        $carriers = carrier::all();
        return response()->json([
            'damage' => $damage,
            'store' => $store,
            'carriers' => $carriers,
        ]);
    }

    public function update_damage(Request $request)
    {
        $validator = Validator::make($request->all(),

            [
                'store' => 'required',
                'shipping_number' => 'required',
                'traking_number' => 'required',
                'order_number' => 'required',
                'transaction_cost' => 'required_if:paid,==,paid',
                'transaction_id' => 'required_if:paid,==,paid',
                'invo_num'=>'required'
            ]
        );
        if ($validator->fails()) {
            return response()->json(['status' => false, 'msg' => $validator->errors()->first()]);
        }
        if ($request->paid == 'paid') {
            $paid = 1;
        } else {
            $paid = 0;
            $request->transaction_id = 0;
            $request->transaction_cost = 0;
        }
        $damage = damage::find($request->id);

        $damage->update([
            'paid' => $paid,
            //  'cost'=>$request->cost,
            'transaction_cost' => $request->transaction_cost,
            'shipping_number' => $request->shipping_number,
            'transaction_id' => $request->transaction_id,
            //  'sku'=>$request->sku,
            'account_id' => $request->account_id,
            'traking_number' => $request->traking_number,
            'carrier_id' => $request->carrier_id,
            'order_number' => $request->order_number,
            'date' => $request->date,
            'invo_num' => $request->invo_num,

        ]);

        if (strpos($request->image, 'data:image/png;base64,') === 0) {
            $image = str_replace('data:image/png;base64,', '', $request->image);

            $image = str_replace(' ', '+', $image);

            $imageName = time() . '.' . 'png';
            $image = base64_decode($image);

            File::put('images/damage/' . $imageName, $image);
            $damage->image->update([
                'file_name' => $imageName,
            ]);
        }

    }


    public function add_sku_sh($id)
    {
        $damage = damage::findOrFail($id);
        if (!$damage) {
            return redirect('not-found');
        }
        return view('m_design.Damage.addSku', ['id' => $id, 'damage_sku' => $damage->skus]);
    }

    public function store_sku($id, damage_sku_request $damage_sku_request)
    {
        damage_sku::create([
            'discription' => $damage_sku_request->discription,
            'quantity' => $damage_sku_request->quantity,
            'price_unit' => $damage_sku_request->price_unit,
            'total' => $damage_sku_request->total,
            'damage_id' => $id,
        ]);
        return redirect()->back()->with('suc', 'sku added successfully');
    }

    public function downloadPdf(Request $request)
    {

        $damage = damage::findOrFail($request->id);
        $data = [
            'damage' => $damage->load('carrier'),
            'skus' => $damage->skus,
            'total' => $damage->skus->sum('total')
        ];
        return view('invo', $data);

    }
}
//<span class="action-edit"><i class="feather icon-edit"></i></span>
//<span class="action-delete"><i class="feather icon-trash"></i></span>

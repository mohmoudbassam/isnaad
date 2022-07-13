<?php

namespace App\Http\Controllers\User;


use App\carrier;
use App\Http\Controllers\Controller;
use App\Http\Requests\damage_sku_request;
use App\Http\Requests\DamageRequest;
use App\Models\cod_plan;
use App\Models\damage;
use App\Models\damage_sku;
use App\Models\images;
use App\Models\store_plane;
use App\order;
use App\order_printed;
use App\user;
use App\Models\masterPlan;
use App\Models\nstoreplan;

use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Illuminate\Http\Response;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


use App\store;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Mpdf\Tag\P;

use Yajra\DataTables\DataTables;
use function GuzzleHttp\Promise\all;


class ClientPlan extends Controller
{

    public function index(Request $request, $id = null)
    {

        $store = store::select('name', 'account_id')->whereNotIn('account_id', [2])->get();

        if ($id != '') {

            $sotre_view = store::where('account_id', '=', $id)->whereNotIn('account_id', [2])->first();

        } else {
            $sotre_view =null;
        }
        if (!$sotre_view) {
            abort(404);
        }
        return view('m_design.clientPlan.client', ['stores' => $store, 'sotre_view' => $sotre_view->load('hasPlan')]);
    }


    public function updateClientInfon(Request $request, $id)
    {

        $str = store::where('account_id', $id)->first();

        if ($str->hasPlan->count() > 0) {
            $all = [];
            foreach ($request->all() as $key => $value) {

                if (strpos($key, 'plan') !== false) {
                    $ex = explode('_plan_', $key);
                    $all[$ex[1]][$ex[0]] = $value;
                }
            }

            foreach ($all as $id => $parms) {
                $plan = store_plane::find($id);
                //  dd($plan);
                //  dd($parms);
                $plan->update($parms);

            }
        } else {

            $str = masterPlan::where('id', $id)->first();
            $str->update($request->all());
        }


        return redirect()->back()->with('success', 'store information updated sucssesfly');

    }

    public function Newindex(Request $request, $id = null)
    {

        $store = store::select('name', 'account_id')->whereNotIn('account_id', [2])->get();

        if ($id != '') {

            $sotre_view = store::where('account_id', '=', $id)->whereNotIn('account_id', [2])->first();
            if ($sotre_view->hasMultiplePlan) {
                $plans = $plans = nstoreplan::where('store_id', $id)->groupBy('fromDate')->get()->pluck('fromDate', 'id');
            } else {
                $plans = masterPlan::where('store_id', $id)->get()->pluck('from_date', 'id');;

            }


        } else {
            $sotre_view =null;
            $plans = [];

        }


        return view('m_design.clientPlan.nClient', ['stores' => $store, 'sotre_view' => optional($sotre_view)->load('hasPlan'), 'plan' => $plans]);
    }

    public function showOnePlan($id)
    {
        $plan = masterPlan::findOrFail($id);
        return view('m_design.clientPlan.showOnePlan', ['plan' => $plan]);

    }

    public function showMultiPlans($store_id, $date)
    {
        $data['store'] = store::where('account_id', $store_id)->first();
        $data['date'] = $date;

        $data['Plans'] = nstoreplan::with('cod_plan')->orderBy('from_num')->where('store_id', $store_id)->where('fromDate', $date)->get();
        if ($data['Plans']->isEmpty() && is_null($data['store'])) {
            abort(404);
        }
        return view('m_design.clientPlan.MultiPlans', $data);
    }

    public function addOnePlanForMultiple($store_id, $date)
    {
        $plans = nstoreplan::where('store_id', $store_id)->where('fromDate', $date)->get();

        return view('m_design.clientPlan.MultiPlans', ['Plans' => $plans]);
    }

    public function showAddMulitPlan($id, $copied_plan_date = null)
    {

        if ($copied_plan_date) {
            $copied_plan = nstoreplan::query()->with('cod_plan')->where('store_id', $id)->whereDate('fromDate', Carbon::parse($copied_plan_date)->toDateString())->get();

        } else {
            $copied_plan = null;
        }

        $store = store::query()->where('account_id', $id)->firstOrFail();

        return view('m_design.clientPlan.addMulitPlan', ['store' => $store, 'copied_plan' => $copied_plan]);
    }

    public function storeMultiPlan($id, Request $request)
    {
        $data = $request['plan'];
        $plans = [];

        foreach ($data as $plan) {

            $plan['fromDate'] = $request['from'];
            $plan['store_id'] = $id;
            $result = nstoreplan::create($plan);
            if (isset($plan['cod_plan']) && is_array($plan['cod_plan'])) {
                foreach ($plan['cod_plan'] as $cod_plan) {
                    cod_plan::create([
                        'from_num' => $cod_plan['from'],
                        'to_num' => $cod_plan['to'],
                        'cod' => $cod_plan['cod'],
                        'plan_id' => $result->id,

                    ]);
                }

            }


        }

        // return redirect()->back()->with('success', 'plan added successfully');

    }

    public function update_mulit_plan($store_id, $date, Request $request)
    {


        foreach ($request->plan as $id => $plan) {

            $updated_plan = tap(nstoreplan::query()->find($id))->update($plan);
            $updated_plan->cod_plan()->delete();

            if (isset( $plan['cod_plan']) && is_array($plan['cod_plan'] )) {


                foreach ($plan['cod_plan'] as $cod_plan) {
                    cod_plan::query()->create([
                        'from_num' => $cod_plan['from'],
                        'to_num' => $cod_plan['to'],
                        'cod' => $cod_plan['cod'],
                        'plan_id' => $updated_plan->id,
                    ]);

                }


            }
        }


        return redirect()->back()->with('suc', 'plan updated sucssesfly');

    }

    public function add_mini($store_id, $plan_date, Request $request)
    {

        $data = $request->except('_token');
        $data['store_id'] = $store_id;
        $data['fromDate'] = $plan_date;

        $validate = Validator::make($request->all(), [
            "from_num" => 'required|numeric',
            "to_num" => 'required|',
            "in_side_ryad" => 'required|numeric',
            "out_side_ryad" => 'required|numeric',
            "cod" => 'required|numeric',
            "each_2nd_units" => 'required',
            "processing_charge" => 'required|numeric',
            "isnaad_packaging" => 'required|numeric',
            "Reciving_replanchment" => 'required|numeric',
            "system_fee" => 'required|numeric',
            "return_charge_in" => 'required|numeric',
            "return_charge_out" => 'required|numeric',
            "return_charge_each_extra" => 'required|numeric',
        ]);
        //  dd($validate->messages()->first());
        if ($validate->fails()) {
            return response()->json([
                'message' => $validate->messages()->first()
            ]);
        }

        nstoreplan::create($data);

        return response()->json([
            'success' => true,
            'message' => 'plan added sucssesfly'
        ]);

    }

    public function copied_multiPlan(Request $request)
    {


        foreach ($request->plan as $plan) {

            $plan['store_id'] = $request->store_id;
            $plan['fromDate'] = $request->from;
            $pl = nstoreplan::query()->create($plan);

            if(isset($plan['cod_plan'])){

                foreach($plan['cod_plan'] as $cod_plan){

                    cod_plan::create([
                        'from_num' => $cod_plan['from_num'],
                        'to_num' => $cod_plan['to_num'],
                        'cod' => $cod_plan['cod'],
                        'plan_id' => $pl->id,

                    ]);
                }

            }
        }
        return redirect()->route('new-client-plan',['id'=>$request->store_id])->with('suc', 'plan created  sucssesfly');
    }


}

//<span class="action-edit"><i class="feather icon-edit"></i></span>
//<span class="action-delete"><i class="feather icon-trash"></i></span>

<?php

namespace App\Http\Controllers\InvoiceReport;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddtransportationExpenses;
use App\Imports\MainStorageImport;
use App\Imports\StorageImport;
use App\Models\invoice_discount;
use App\Models\invoice_extra_cost;
use App\Models\masterPlan;
use App\Models\nstoreplan;
use App\Models\transportation_cost;
use App\order;
use App\store;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class InvoiceBonus extends Controller
{
    
    public function transportation_form()
    {

        return response()->json([
            'success' => true,
            'page' => view('m_design.InvoceReport.transportation_form')->render()
        ]);

    }

    public function transportation_save(AddtransportationExpenses $request)
    {
        transportation_cost::create([
            'total_quantity'=>$request->quantity_item,
            'description'=>'Replenishment off on Clinet Request',
            'type'=>0,
            'date'=>Carbon::parse($request->date)->format('Y-m-d'),
            'cost'=>$request->cost,
            'store_id'=>$request->store_id
        ]);
        return response()->json(
            [
                'success'=>true,
                'message' =>'added successfully',
            ]
        );
     }

     public function discount_form($id=null)
    {

         
        $data['stores']=store::all();
        $data['record']=null;
         if($id){
             $data['record']=   invoice_discount::findOrFail($id);
     

         }
        return response()->json([
            'success' => true,
            'page' => view('m_design.InvoceReport.discount_form',$data)->render()
        ]);

    }
    public function discount_save(AddtransportationExpenses $request)
    {
        $id = isset($request['id']) ? $request['id'] : null;
        invoice_discount::updateOrCreate(['id' => $id], [
            'total_item'=>$request->total_item,
            'date'=>Carbon::parse($request->date)->format('Y-m-d'),
            'cost'=>$request->cost,
            'description'=> invoice_discount::Discription($request->serviceType),
            'service_type'=> $request->serviceType,
            'total_disccount'=> ($request->cost*$request->total_item),
            'store_id'=>$request->store_id
        ]);


        return response()->json(
            [
                'success'=>true,
                'message' =>'added successfully',
            ]
        );
    }
   public function extra_costs_form($id=null)
    {
        $data['stores']=store::all();
        $data['record']=null;
        if($id){
            $data['record']=   invoice_extra_cost::findOrFail($id);

        }

        return response()->json([
            'success' => true,
            'page' => view('m_design.InvoceReport.extra_cost_form',$data)->render()
        ]);

    }
 public function extra_costs_save(AddtransportationExpenses $request)
    {

        $id = isset($request['id']) ? $request['id'] : null;
        invoice_extra_cost::updateOrCreate(['id' => $id],[

            'date'=>Carbon::parse($request->date)->format('Y-m-d'),
            'cost'=>$request->cost,
            'description'=>$request->description,
            'store_id'=>$request->store_id
        ]);
        return response()->json(
            [
                'success'=>true,
                'message' =>'added successfully',
            ]
        );
    }

}

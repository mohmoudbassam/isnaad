<?php

namespace App\Http\Controllers\User;

use App\Exports\BillingExport;
use App\Exports\OrderExport;
use App\Http\Controllers\Controller;
use App\Models\damage_sku;
use App\statment;
use App\statment_file;
use App\store;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;


class skuController extends Controller
{
    public function get_sku(Request $request)
    {
        $skus = damage_sku::find($request->id);
        return response()->json(
            $skus
        );
    }

    public function update_sku(Request $request)
    {
        $validator = Validator::make($request->all(),

            [
                'discription'=>'required',
                'quantity'=>'required|numeric',
                'price_unit'=>'required|numeric',
                'total'=>'required|numeric',
            ]
        );
        if ($validator->fails()) {
            return response()->json(['status' => false, 'msg' => $validator->errors()->first()]);
        }
        $sku=damage_sku::find($request->id);

        $sku->update([
            'discription'=>$request->discription,
            'quantity'=>$request->quantity,
            'price_unit'=>$request->price_unit,
            'total'=>$request->total,
        ]);
    }
}

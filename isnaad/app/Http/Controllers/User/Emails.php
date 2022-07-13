<?php

namespace App\Http\Controllers\User;


use App\carrier;
use App\Http\Controllers\Controller;
use App\Models\replenishment;
use App\order;
use App\order_printed;
use App\user;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


use App\store;

class Emails extends Controller
{
    public function mangerDailayReport()
    {
        $orders_shipped = order::select('store_id', DB::raw('count(*) as total'), DB::raw('sum(Qty_Item) as Qty'))
            ->selectRaw('sum(time_to_sec(timediff(printed_time ,created_at))/3600 ) as leadtime')
            ->whereDate('shipping_date', Carbon::today())
            ->with('store')
            ->groupBy('store_id')
            ->get();
        $rep = replenishment::query();
        $reps = $rep->whereDate('date', Carbon::today())->orWhere('is_end', 0)->with('store')->get();

        $to_email = 'malkhatib@isnaad.sa';
        $subject = Carbon::today()->format('Y-M-D');
        $message = "
<html>
<head>
<title>HTML email</title>
</head>
<body>
<p>This email for day  " . Carbon::today()->format('Y-M-D') . "</p>
<table border='1'>
<tr>
<th>Client		</th>
<th>total orders</th>
<th>	total Qty</th>
<th>	lead time</th>
</tr>
<tbody>
";
        foreach ($orders_shipped as $order) {
            $str = '<tr>' . '<td>' . $order->store->name . '</td>';
            $str .= '<td>' . $order->total . '</td>';
            $str .= '<td>' . $order->Qty . '</td>';
            $str .= '<td>' . $order->leadtime . '</td>';
            $str .= '</tr>';
            $message .= $str;
        }
        $totalOrders = $orders_shipped->sum(function ($item) {
            return $item->total;
        });
        $totalQty = $orders_shipped->sum(function ($item) {
            return $item->Qty;
        });
        $Total_lead_time = $orders_shipped->sum(function ($order) {
            return $order->leadtime;
        });

        $message .= '<tr>' . '<td>Grand  total</td>';
        $message .= '<td>' . $totalOrders . '</td>';
        $message .= '<td>' . $totalQty . '</td>';
        $message .= '<td>' . number_format($Total_lead_time / $totalOrders, 2, ',', ' ') . ' H' . '</td>' . '</td></tbody>
</table>';

        $message .= "
<br><br>
<p>replenishment</p>
<table border='1'>
<tr>
<th>Client		</th>
<th>Rep ID</th>
<th>	Qty received</th>
<th>	quantity request</th>
<th>	Remaining	 </th>
<th>	date </th>
<th>	time </th>
</tr>
<tbody>
";
        $str = '';
        foreach ($reps as $rep) {
            $str = '<tr>' . '<td>' . $rep->store->name . '</td>';
            $str .= '<td>' . $rep->rep_id . '</td>';
            $str .= '<td>' . $rep->quantity_recived . '</td>';
            $str .= '<td>' . $rep->quantity_request . '</td>';
            $str .= '<td>' . $rep->remaining . '</td>';
            $str .= '<td>' . $rep->created_at . '</td>';
            $str .= '<td>' . $rep->Time . '</td>';
            $str .= '</tr>';
            $message .= $str;
        }
        $aa = "

</body>
</html>
";

        $headers = 'isnaad portal';
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n" . 'From: ' . 'isnaad app ' . "\r\n" .
            'Reply-To: ' . 'isnaad app' . "\r\n";

        $mangers = user::where('type', 'm')->get();
        // mail('asiraj@isnaad.sa', 'hello', 'hello ahmad ', $headers);
     
        foreach ($mangers as $manger) {
             
            mail($manger->email, $subject, $message, $headers);
        }
    }
}

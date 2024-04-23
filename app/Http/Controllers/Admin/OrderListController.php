<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class OrderListController extends Controller
{
    protected $sl;

    public function index(Request $request)
    {
        $header_title = 'Order List';

        if ($request->ajax()) {
            $data = Order::orderBy('created_at','DESC')->get();
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('sl',function($sl){
                        return $this->sl = $this->sl +1;
                    })
                    ->addColumn('order_date',function($v){
                        return date("d-m-Y", strtotime($v->created_at)).'<br>'.date("h:i A", strtotime($v->created_at));
                    })
                    ->addColumn('first_name',function($v){
                        return $v->first_name;
                    })
                    ->addColumn('address',function($v){
                        return $v->address;
                    })
                    ->addColumn('city',function($v){
                        return $v->city;
                    })
                    ->addColumn('state',function($v){
                        return $v->state;
                    })
                    ->addColumn('phone',function($v){
                        return $v->phone;
                    })
                    ->addColumn('email',function($v){
                        return $v->email;
                    })
                    ->addColumn('total_amount',function($v){
                        return $v->total_amount;
                    })
                    ->addColumn('payment_method',function($v){
                        return $v->payment_method;
                    })
                    ->addColumn('is_payment',function($v){
                        if($v->is_payment == 1)
                        {
                            return '<span class="badge badge-success">Paid</span>';
                        }
                        else
                        {
                            return '<span class="badge badge-danger">Unpaid</span>';
                        }
                    })
                    ->addColumn('status',function($v){
                        if($v->status == 1)
                        {
                            return 'Active';
                        }
                        else
                        {
                            return 'Inactive';
                        }
                    })
                    ->addColumn('action', function($v){
                        return '<a href="'.url('admin/order_list/details',$v->id).'" class="btn btn-primary">View</a>';

                    })
                    ->rawColumns(['sl','order_date','first_name','address','city','state','phone','email','total_amount','payment_method','is_payment','status','action'])
                    ->make(true);
        }
        
        return view('admin.order_list.index',compact('header_title'));
    }

    public function OrderDetails($order_id)
    {
        $header_title = 'Order Details';

        $order = Order::find($order_id);
        if(!empty($order))
        {
            return view('admin.order_list.order_details',compact('order','header_title'));
        }
    }
}

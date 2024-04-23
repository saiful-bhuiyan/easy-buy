<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ShippingCharge;
use Brian2694\Toastr\Toastr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;

class ShippingChargeController extends Controller
{
    protected $sl;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $header_title = 'Shipping Charges';

        if ($request->ajax()) {
            $data = ShippingCharge::orderBy('id','desc')->get();
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('sl',function($sl){
                        return $this->sl = $this->sl +1;
                    })
                    ->addColumn('name',function($v){
                        return $v->name;
                    })
                    ->addColumn('price',function($v){
                        return $v->price;
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
                    ->addColumn('user_id',function($v){
                        return $v->User->name;
                    })
                    ->addColumn('action', function($v){
                        return '<a href="'.route('shipping_charge.edit',$v->id).'" class="btn btn-primary">Edit</a>
                        <form action="'.route('shipping_charge.destroy',$v->id).'" method="POST" style="display: inline;">
                        '.csrf_field().'
                        '.method_field('DELETE').'
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>';

                    })
                    ->rawColumns(['sl','name','price','status','user_id','action'])
                    ->make(true);
        }
        
        return view('admin.shipping_charge.index',compact('header_title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $header_title = 'Create Shipping Charge';
        return view('admin.shipping_charge.create',compact('header_title'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:30',
            'price' => 'required|numeric',
            'status' => 'required',
        ];

        $validate = Validator::make($request->all(),$rules);
        if($validate->fails())
        {
            return redirect()->back()->withErrors($validate)->withInput();
        }
        else
        {
            try {
                $shipping_charge = new ShippingCharge();
                $shipping_charge->name = trim($request->name);
                $shipping_charge->price = trim($request->price);
                $shipping_charge->status = $request->status;
                $shipping_charge->user_id = Auth::user()->id;
                $shipping_charge->save();
                
                $toastr = app(Toastr::class);
                $toastr->success('Success', 'Data added successful');

                return redirect()->back();
            }catch(\Exception $e) {
                Log::error($e->getMessage());
                $toastr = app(Toastr::class);
                $toastr->error('Error', 'An error occurred');
            }
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $header_title = 'Edit Shipping Charge';
        $data = ShippingCharge::find($id);
        return view('admin.shipping_charge.edit',compact('header_title','data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $rules = [
            'name' => 'required|string|max:30',
            'type' => 'required|numeric',
            'status' => 'required',
        ];

        $validate = Validator::make($request->all(),$rules);
        if($validate->fails())
        {
            return redirect()->back()->withErrors($validate)->withInput();
        }
        else
        {
            try {
                $shipping_charge = ShippingCharge::findOrFail($id);
                $shipping_charge->name = trim($request->name);
                $shipping_charge->price = trim($request->price);
                $shipping_charge->status = $request->status;
                $shipping_charge->user_id = Auth::user()->id;
                $shipping_charge->save();
                
                $toastr = app(Toastr::class);
                $toastr->success('Success', 'Data Updated successful');

                return redirect()->back();
            }catch(\Exception $e) {
                Log::error($e->getMessage());
                $toastr = app(Toastr::class);
                $toastr->error('Error', 'An error occurred');
            }
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $shipping_charge = ShippingCharge::findOrFail($id);
            $shipping_charge->delete();
            $toastr = app(Toastr::class);
            $toastr->success('Success', 'Data Deleted successful');
        }catch(\Exception $e) {
            Log::error($e->getMessage());
            $toastr = app(Toastr::class);
            $toastr->error('Error', 'An error occurred');
        }
        return redirect()->back();
    }
}
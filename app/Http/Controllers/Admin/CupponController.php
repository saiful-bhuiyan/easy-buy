<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cuppon;
use Brian2694\Toastr\Toastr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CupponController extends Controller
{
    protected $sl;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $header_title = 'Product Cuppons';

        if ($request->ajax()) {
            $data = Cuppon::orderBy('id','desc')->get();
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('sl',function($sl){
                        return $this->sl = $this->sl +1;
                    })
                    ->addColumn('name',function($v){
                        return $v->name;
                    })
                    ->addColumn('type',function($v){
                        return $v->type;
                    })
                    ->addColumn('percent_amount',function($v){
                        return $v->percent_amount;
                    })
                    ->addColumn('expire_date',function($v){
                        return $v->expire_date;
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
                        return '<a href="'.route('cuppon.edit',$v->id).'" class="btn btn-primary">Edit</a>
                        <form action="'.route('cuppon.destroy',$v->id).'" method="POST" style="display: inline;">
                        '.csrf_field().'
                        '.method_field('DELETE').'
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>';

                    })
                    ->rawColumns(['sl','name','type','percent_amount','expire_date','status','user_id','action'])
                    ->make(true);
        }
        
        return view('admin.cuppon.index',compact('header_title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $header_title = 'Create Cuppon';
        return view('admin.cuppon.create',compact('header_title'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:30',
            'type' => 'required|string|in:Percent,Amount',
            'percent_amount' => 'string|max:10',
            'expire_date' => 'required',
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
                $cuppon = new Cuppon;
                $cuppon->name = trim($request->name);
                $cuppon->type = trim($request->type);
                $cuppon->percent_amount = trim($request->percent_amount);
                $cuppon->expire_date = trim($request->expire_date);
                $cuppon->status = $request->status;
                $cuppon->user_id = Auth::user()->id;
                $cuppon->save();
                
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
        $header_title = 'Edit Cuppon';
        $data = Cuppon::find($id);
        return view('admin.cuppon.edit',compact('header_title','data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $rules = [
            'name' => 'required|string|max:30',
            'type' => 'required|string|in:Percent,Amount',
            'percent_amount' => 'string|max:10',
            'expire_date' => 'required',
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
                $cuppon = Cuppon::findOrFail($id);
                $cuppon->name = trim($request->name);
                $cuppon->type = trim($request->type);
                $cuppon->percent_amount = trim($request->percent_amount);
                $cuppon->expire_date = $request->expire_date;
                $cuppon->status = $request->status;
                $cuppon->user_id = Auth::user()->id;
                $cuppon->save();
                
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
            $cuppon = Cuppon::findOrFail($id);
            $cuppon->delete();
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
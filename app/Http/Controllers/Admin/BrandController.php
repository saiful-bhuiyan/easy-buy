<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Brand;
use Brian2694\Toastr\Toastr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class BrandController extends Controller
{
    protected $sl;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $header_title = 'Product Brand';

        if ($request->ajax()) {
            $data = Brand::orderBy('id','desc')->get();
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('sl',function($sl){
                        return $this->sl = $this->sl +1;
                    })
                    ->addColumn('title',function($v){
                        return $v->title;
                    })
                    ->addColumn('slug',function($v){
                        return $v->slug;
                    })
                    ->addColumn('meta_title',function($v){
                        return Str::limit($v->meta_title, 15);
                    })
                    ->addColumn('meta_description',function($v){
                        return Str::limit($v->meta_description, 20);
                    })
                    ->addColumn('meta_keyword',function($v){
                        return Str::limit($v->meta_keyword, 15);
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
                        return '<a href="'.route('brand.edit',$v->id).'" class="btn btn-primary">Edit</a>
                        <form action="'.route('brand.destroy',$v->id).'" method="POST" style="display: inline;">
                        '.csrf_field().'
                        '.method_field('DELETE').'
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>';

                    })
                    ->rawColumns(['sl','title','slug','meta_title','meta_description','meta_keyword','status','user_id','action'])
                    ->make(true);
        }
        
        return view('admin.brand.index',compact('header_title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $header_title = 'Create Brand';
        return view('admin.brand.create',compact('header_title'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'title' => 'required|string|max:30',
            'meta_title' => 'string|max:50',
            'description' => 'string',
            'meta_keyword' => 'string|max:100',
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
                $brand = new Brand;
                $brand->title = trim($request->title);
                $brand->meta_title = trim($request->meta_title);
                $brand->meta_description = trim($request->meta_description);
                $brand->meta_keyword = trim($request->meta_keyword);
                $brand->status = $request->status;
                $brand->user_id = Auth::user()->id;
                $brand->save();
                
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
        $header_title = 'Edit Brand';
        $data = Brand::find($id);
        return view('admin.brand.edit',compact('header_title','data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $rules = [
            'title' => 'required|string|max:30',
            'meta_title' => 'string|max:50',
            'description' => 'string',
            'meta_keyword' => 'string|max:100',
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
                $brand = Brand::findOrFail($id);
                $brand->title = trim($request->title);
                $brand->meta_title = trim($request->meta_title);
                $brand->meta_description = trim($request->meta_description);
                $brand->meta_keyword = trim($request->meta_keyword);
                $brand->status = $request->status;
                $brand->user_id = Auth::user()->id;
                $brand->save();
                
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
            $brand = Brand::findOrFail($id);
            $brand->delete();
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

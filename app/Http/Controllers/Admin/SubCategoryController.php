<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\SubCategory;
use Brian2694\Toastr\Toastr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SubCategoryController extends Controller
{
    protected $sl;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $header_title = 'Sub Category';

        if ($request->ajax()) {
            $data = SubCategory::orderBy('id','desc')->get();
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('sl',function($sl){
                        return $this->sl = $this->sl +1;
                    })
                    ->addColumn('category_title',function($v){
                        return $v->Category->title;
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
                        return '<a href="'.route('sub_category.edit',$v->id).'" class="btn btn-primary">Edit</a>
                        <form action="'.route('sub_category.destroy',$v->id).'" method="POST" style="display: inline;">
                        '.csrf_field().'
                        '.method_field('DELETE').'
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>';

                    })
                    ->rawColumns(['sl','category_title','title','slug','meta_title','meta_description','meta_keyword','status','user_id','action'])
                    ->make(true);
        }
        
        return view('admin.sub_category.index',compact('header_title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $header_title = 'Create Sub Category';
        $category = Category::orderBy('title','ASC')->get();
        return view('admin.sub_category.create',compact('header_title','category'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'category_id' => 'required',
            'title' => 'required|string|max:30',
            'meta_title' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'meta_keyword' => 'nullable|string|max:100',
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
                $category = new SubCategory;
                $category->category_id = $request->category_id;
                $category->title = trim($request->title);
                $category->meta_title = trim($request->meta_title);
                $category->meta_description = trim($request->meta_description);
                $category->meta_keyword = trim($request->meta_keyword);
                $category->status = $request->status;
                $category->user_id = Auth::user()->id;
                $category->save();
                
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
        $header_title = 'Edit Sub Category';
        $category = Category::orderBy('title','ASC')->get();
        $data = SubCategory::find($id);
        return view('admin.sub_category.edit',compact('header_title','category','data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $rules = [
            'category_id' => 'required',
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
                $category = SubCategory::findOrFail($id);
                $category->id = $request->category_id;
                $category->title = trim($request->title);
                $category->slug = Str::slug($request->title);
                $category->meta_title = trim($request->meta_title);
                $category->meta_description = trim($request->meta_description);
                $category->meta_keyword = trim($request->meta_keyword);
                $category->status = $request->status;
                $category->user_id = Auth::user()->id;
                $category->save();
                
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
            $category = SubCategory::findOrFail($id);
            $category->delete();
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

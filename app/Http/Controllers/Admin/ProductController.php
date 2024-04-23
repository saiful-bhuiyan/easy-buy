<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Color;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\SubCategory;
use App\Models\ProductColor;
use App\Models\ProductSize;
use App\Models\ProductImage;
use Brian2694\Toastr\Toastr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    protected $sl;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $header_title = 'Product';

        if ($request->ajax()) {
            $data = Product::orderBy('id','desc')->get();
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
                        return '<a href="'.route('product.edit',$v->id).'" class="btn btn-primary">Edit</a>
                        <form action="'.route('product.destroy',$v->id).'" method="POST" style="display: inline;">
                        '.csrf_field().'
                        '.method_field('DELETE').'
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>';

                    })
                    ->rawColumns(['sl','title','slug','status','user_id','action'])
                    ->make(true);
        }
        
        return view('admin.product.index',compact('header_title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $header_title = 'Create Product';
        return view('admin.product.create',compact('header_title'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'title' => 'required|string|max:100',
        ];

        $validate = Validator::make($request->all(),$rules);
        if($validate->fails())
        {
            return redirect()->back()->withErrors($validate)->withInput();
        }
        else
        {
            try {
                $brand = new Product;
                $brand->title = trim($request->title);
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
        $header_title = 'Edit Product';
        $category = Category::orderBy('title','ASC')->where('status',1)->get();
        $brand = Brand::orderBy('title','ASC')->where('status',1)->get();
        $color = Color::orderBy('title','ASC')->where('status',1)->get();
        $selectedColors = ProductColor::where('product_id', $id)->pluck('color_id')->toArray();
        $productSize = ProductSize::where('product_id',$id)->get();
        $productImage = productImage::where('product_id',$id)->orderBy('order_by','ASC')->get();
        $data = Product::find($id);
        return view('admin.product.edit',compact('header_title','data','category','brand','color','selectedColors','productSize','productImage'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $rules = [
            'title' => 'required|string|max:100',
            'category_id' => 'required',
            'sub_category_id' => 'required',
            'price' => 'required|numeric',
            'old_price' => 'required|numeric',
            'status' => 'required',
        ];

        $validate = Validator::make($request->all(),$rules);
        if($validate->fails())
        {
            return redirect()->back()->withErrors($validate)->withInput();
        }
        else
        {
            if(!empty($request->file('image')))
            {
                foreach($request->file('image') as $image)
                {
                    if($image->isValid())
                    {
                        $ext = $image->getClientOriginalExtension();
                        $image_name = $id.'_'.Str::random(15);
                        $file_name = strtolower($image_name).'.'.$ext;
                        $image->move('upload/products/',$file_name);

                        $upload_image = new ProductImage;
                        $upload_image->product_id = $id;
                        $upload_image->image_name = $file_name;
                        $upload_image->image_extension = $ext;
                        $upload_image->save();
                    }
                   
                }
            }

            try {
                $product = Product::findOrFail($id);
                $product->title = trim($request->title);
                $product->slug = Str::slug($request->title);
                $product->sku = trim($request->sku);
                $product->category_id = trim($request->category_id);
                $product->sub_category_id = trim($request->sub_category_id);
                $product->brand_id = trim($request->brand_id);
                $product->old_price = trim($request->old_price);
                $product->price = trim($request->price);
                $product->short_description = trim($request->short_description);
                $product->description = trim($request->description);
                $product->additional_information = trim($request->additional_information);
                $product->shipping_returns = trim($request->shipping_returns);
                $product->status = $request->status;
                $product->user_id = Auth::user()->id;
                $product->save();

                $delete_old_color = ProductColor::where('product_id',$id)->delete();
                if(!empty($request->color))
                {
                    foreach($request->color as $color_id)
                    {
                        $color = new ProductColor;
                        $color->color_id = $color_id;
                        $color->product_id = $product->id;
                        $color->save();
                    }
                }
                $delete_old_size = ProductSize::where('product_id',$id)->delete();
                if(!empty($request->size))
                {
                    foreach($request->size as $size)
                    {
                        if(!empty($size['name']))
                        {
                            $newSize = new ProductSize();
                            $newSize->product_id = $product->id;
                            $newSize->name = $size['name'];
                            $newSize->price = $size['price'];
                            $newSize->save();
                        }
                    }
                }
                
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
        //
    }

    // Get Sub Category By Category Id

    public function getSubCategoryByCatId(Request $request)
    {
        $sub_category = SubCategory::where('category_id',$request->category_id)->orderBy('title','ASC')->where('status',1)->get();
        $html = '<option value="" selected disabled>Select</option>';
        if($sub_category->count() > 0)
        {
            foreach($sub_category as $v)
            {
                $html .='<option value="'.$v->id.'">'.$v->title.'</option>';
            }
        }
        else
        {
            $html = '<option value="" selected disabled>No Record Found</option>';
        }

        return $html;
    }

    public function deleteProductImage(Request $request)
    {
        $image_name = basename($request->image_name);

        $product_image = ProductImage::where('image_name', $image_name)->first();
        
        if ($product_image) {
            $file_path = public_path('upload/products/') . $product_image->image_name;
            if (file_exists($file_path)) {
                unlink($file_path);
            } else {
                return response()->json(['message' => 'Image file not found on the server'], 404);
            }

            
            $product_image->delete();

        
            return response()->json(['message' => 'Image deleted successfully'], 200);
        } else {
        
            return response()->json(['message' => 'Image not found in the database'], 404);
        }
    }

    public function productImageSortable(Request $request)
    {
        if(!empty($request->img_id))
        {
            $i = 1;
            foreach($request->img_id as $image_id)
            {
                $image = ProductImage::findOrFail($image_id);
                $image->order_by = $i;
                $image->save();
                $i++;
            }
            return response()->json(['message' => 'Image Order Updated'],200);
        }
        else
        {
            return response()->json(['message' => 'Image Order Update Failed'],404);
        }
    }
}

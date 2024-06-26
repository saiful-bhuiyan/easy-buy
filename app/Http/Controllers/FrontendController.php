<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\SubCategory;

class FrontendController extends Controller
{
    public function getCategory($slug, $sub_slug='')
    {
        $getSingleProduct = Product::where('status',1)->where('slug',$slug)->first();
        $getRelatedProduct = [];
        if(!empty($getSingleProduct))
        {
            $getRelatedProduct = Product::getRelatedProduct($getSingleProduct->SubCategory->id, $getSingleProduct->id);
            if(empty($getRelatedProduct))
            {
                $getRelatedProduct = [];
            }
        }

        $getCategory = Category::where('slug',$slug)->where('status',1)->first();
        $getSubCategory = SubCategory::where('slug',$sub_slug)->where('status',1)->first();
        $getColor = Color::where('status',1)->orderBy('id')->get();
        $getBrand = Brand::where('status',1)->orderBy('id')->get();
        if(!empty($getSingleProduct))
        {
            $meta_title = $getSingleProduct->title;
            $meta_description = $getSingleProduct->short_description;
            return view('frontend.product.details',compact('getSingleProduct','getRelatedProduct','meta_title','meta_description'));
        }
        else if(!empty($getCategory) && !empty($getSubCategory))
        {
            $getProduct = Product::where('status',1)->where('sub_category_id',$getSubCategory->id)->paginate(10);
            $page = 0;
            if(!empty($getProduct->nextPageUrl()))
            {
                $parse_url = parse_url($getProduct->nextPageUrl());
                if(!empty($parse_url['query']))
                {
                    parse_str($parse_url['query'], $get_array);
                    $page = !empty($get_array['page']) ? $get_array['page'] : 0;
                }
            }
            $meta_title = $getSubCategory->meta_title;
            $meta_description = $getSubCategory->meta_description;
            $meta_keyword = $getSubCategory->meta_keyword;
            return view('frontend.product.list',compact('getCategory','getSubCategory','meta_title','meta_description','meta_keyword','getProduct','page','getColor','getBrand'));
        }
        else if(!empty($getCategory))
        {
            $getProduct = Product::where('status',1)->where('category_id',$getCategory->id)->paginate(10);
            $page = 0;
            if(!empty($getProduct->nextPageUrl()))
            {
                $parse_url = parse_url($getProduct->nextPageUrl());
                if(!empty($parse_url['query']))
                {
                    parse_str($parse_url['query'], $get_array);
                    $page = !empty($get_array['page']) ? $get_array['page'] : 0;
                }
            }
            $meta_title = $getCategory->meta_title;
            $meta_description = $getCategory->meta_description;
            $meta_keyword = $getCategory->meta_keyword;
            return view('frontend.product.list',compact('getCategory','meta_title','meta_description','meta_keyword','getProduct','page','getColor','getBrand'));
        }
        else
        {
            abort(404);
        }
    }

    // get Filter Product By Ajax

    public function get_filter_product(Request $request)
    {
        $getProduct = Product::filterProducts($request);
        $page = 0;
        if(!empty($getProduct->nextPageUrl()))
        {
            $parse_url = parse_url($getProduct->nextPageUrl());
            if(!empty($parse_url['query']))
            {
                parse_str($parse_url['query'], $get_array);
                $page = !empty($get_array['page']) ? $get_array['page'] : 0;
            }
        }
        
        return response()->json([
            "status" => true,
            "page" => $page,
            "success" => view('frontend.product.product_list', compact('getProduct','page'))->render(),
        ],200);

        
    }

    // public function get_search_product(Request $request)
    // {
    //     $query = $request->input('query');

    //     $products = Product::where('title', 'like', '%'.$query.'%')->where('status',1)->get();
    
    //     return response()->json($products);
    // }

    public function get_search_product(Request $request)
    {
        $query = $request->input('query'); 

    
        $products = Product::where('title', 'LIKE', "%{$query}%")->get();

        $suggestions = collect([]);

            $suggestions = $products->map(function ($product) {
                $defaultImage = asset('frontend/image/no_image.jpg'); 
                $image = $product->ProductImage->sortBy('order_by')->first();
        
                return [
                    'slug' => $product->slug,
                    'title' => $product->title,
                    'price' => $product->price,
                    'old_price' => $product->old_price,
                    'image' => $image ? asset('upload/products/'.$image->image_name) : $defaultImage,
                ];
            });


        return response()->json($suggestions);
    }

    public function search(Request $request)
    {
        $getProduct = Product::where('title', 'LIKE', "%{$request->q}%")->where('status',1)->where('category_id','!=',null)->where('sub_category_id','!=',null)->paginate(10);
        $page = 0;
            if(!empty($getProduct->nextPageUrl()))
            {
                $parse_url = parse_url($getProduct->nextPageUrl());
                if(!empty($parse_url['query']))
                {
                    parse_str($parse_url['query'], $get_array);
                    $page = !empty($get_array['page']) ? $get_array['page'] : 0;
                }
            }
        $getColor = Color::where('status',1)->orderBy('id')->get();
        $getBrand = Brand::where('status',1)->orderBy('id')->get();
        return view('frontend.product.search_product',compact('getProduct','getColor','getBrand','page'));
    }

}

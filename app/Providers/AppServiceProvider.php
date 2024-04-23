<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('*',function($view){
            $view->with('Category',Category::orderBy('id','ASC')->where('status',1)->get());
            // $view->with('SubCategory',SubCategory::orderBy('id','ASC')->where('status',1)->get());
        });
    }
}

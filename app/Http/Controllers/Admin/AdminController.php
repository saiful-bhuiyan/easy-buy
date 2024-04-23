<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $header_title = 'Admin List';
        $users = User::getAdmin();
        return view('admin.admin_list.index',compact('header_title','users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $header_title = 'Create Admin';
        return view('admin.admin_list.create',compact('header_title'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email|max:255',
            'password' => 'required|string|min:8|max:255',
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
                $user = new User;
                $user->name = $request->name;
                $user->email = $request->email;
                $user->password = Hash::make($request->password);
                $user->status = $request->status;
                $user->is_admin = 1;
                $user->save();
                
                Toastr::success('Success', 'Data added successful');
                return redirect()->back();
            }catch(\Exception $e) {
                Log::error($e->getMessage());
                Toastr::error('error', 'An error occured');
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
        $header_title = 'Edit Admin';
        $data = User::find($id);
        return view('admin.admin_list.edit',compact('header_title','data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $id,
            'password' => 'required|string|min:8|max:255',
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
                $user = User::findOrFail($id);
                $user->name = $request->name;
                $user->email = $request->email;
                if(!empty($request->password))
                {
                    $user->password = Hash::make($request->password);
                }
                $user->status = $request->status;
                $user->is_admin = 1;
                $user->save();
                
                Toastr::success('Success', 'Data Updated successful');
                return redirect()->back();
            }catch(\Exception $e) {
                Log::error($e->getMessage());
                Toastr::error('error', 'An error occured');
            }
        }
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();
            Toastr::success('Success', 'Data Deleted successful');
        }catch(\Exception $e) {
            Log::error($e->getMessage());
            Toastr::error('error', 'An error occured');
        }
        return redirect()->back();
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Middleware\Admin;
use App\Models\Admin as ModelsAdmin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

class AdminController extends Controller
{
    public function dashboard() {
        $adminUser = Auth::guard('admins')->user();
        return view('admin.dashboard', ['user'=>$adminUser]);
    }
    public function admin_login() {
        $adminUser = Auth::guard('admins')->user();
        return view('admin.login', ['user'=>$adminUser]);
    }

    public function loginPost(Request $request) {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
            'captcha' => 'required|captcha',
        ]);

        $credentials = $request->only('username', 'password');

        if (Auth::guard('admins')->attempt($credentials)) {
            return redirect()->route('admin.dashboard');
        } else {
            return redirect()->route('admin_login')->withErrors(['username' => 'Đăng nhập không thành công']);
        }
    }

    public function admin_logout() {
        Auth::guard('admins')->logout();
        return Redirect('/');
    }

    public function all_accounts() {
        $adminUser = Auth::guard('admins')->user();
        $all_accounts = ModelsAdmin::orderby('updated_at', 'desc')->get();
        return view('admin.account.all_account', ['user'=>$adminUser], ['all_accounts'=>$all_accounts], compact('all_accounts'));
    }

    public function add_account()
    {
        $adminUser = Auth::guard('admins')->user();
        return view('admin.account.add_account', ['user'=>$adminUser]);
    }

    public function save_account(Request $request)
    {
        $validatedData = $request->validate([
            'username' => 'required|unique:accounts|max:255',
            'password' => 'required|max:255',
            'role' => 'required',
            'name' => 'required',
            'email' => 'required|email|unique:users',]);
        
        $user = new User();
        $user -> name = $request -> name;
        $user -> email = $request -> email;
        $user -> password = Hash::make($request->password);
        $user -> save();

        $accountId = $user -> id;
        
        $account = new ModelsAdmin();
        $account -> username = $request -> username;
        $account -> password = Hash::make($request->password);
        $account -> user_id = $accountId;
        $account -> role = $request -> role;
        $account -> save();
        return redirect::to('admin/accounts');


    }

    public function delete_account($account_id)
    {
        $adminUser = Auth::guard('admins')->user();
        $account = DB::table('accounts')->where('id', $account_id)->first();
        DB::table('accounts')->where('id', $account_id) ->delete();
        Session()->put('message', 'Xóa tài khoản thành công');
        return Redirect::to('admin/accounts');
    }

    public function edit_account($account_id)
    {
        $adminUser = Auth::guard('admins')->user();
        $account = DB::table('accounts')->where('id', $account_id)->first();
        $all_account = DB::table('accounts')->get();
        return view('admin.account.edit_account', ['user' => $adminUser], compact('account','all_account'));
    }

    public function update_account(Request $request, $account_id)
    {
        $adminUser = Auth::guard('admins')->user();
        $data = array();
        $data['username'] = $request->username;
        $data['password'] = $request->matkhau;

        $existingUser = DB::table('accounts')->where('username', $request->username)->exists();
        $request->validate([
            'username' => 'required',
        ]);
        
        if ($existingUser) {
            return back()->withInput()->withErrors(['username' => 'Username này đã tồn tại']);
        }
        else {
            DB::table('accounts')->where('id', $account_id) ->update($data);
            
            Session()->put('message', 'Sửa thành công');
            return Redirect::to('admin/accounts');
        }
    }

    public function info_admin()
    {
        $adminUser = Auth::guard('admins')->user();
        return view('admin.account.info_user', ['user'=>$adminUser]);
    }

    public function save_info_admin(Request $request)
    {
        $adminUser = Auth::guard('admins')->user();
        $data = array();
        $data['name'] = $request->name;
        $data['email'] = $request->email;
        
        $user = User::find($adminUser->user_id);
        $user->update($data);

        return view('admin.account.info_user', ['user'=>$adminUser]);
    }

}

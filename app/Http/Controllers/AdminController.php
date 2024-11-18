<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


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
        return Redirect('admin/login');
    }
}

<?php

namespace App\Http\Controllers;

use App\Exports\AdminExport;
use App\Http\Middleware\Admin;
use App\Models\Admin as ModelsAdmin;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Maatwebsite\Excel\Facades\Excel;

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

    public function all_accounts(Request $request) {
        $adminUser = Auth::guard('admins')->user();
        $filters = [];

        // Lọc theo từ khóa (nếu có)
        if ($request->has('keyword') && $request->keyword != '') {
            $filters['username'] = '%' . $request->keyword . '%';
        }

        // Lọc theo Role (nếu có)
        if ($request->has('role') && $request->role != '') {
            $filters['role'] = $request->role;
        }

        // Lọc theo Status (nếu có)
        if ($request->has('status') && $request->status != '') {
            $filters['status'] = $request->status;
        }

        // Tạo query từ bảng accounts
        $query = DB::table('accounts');

        // Lọc theo các điều kiện trong filters
        foreach ($filters as $column => $value) {
            // Nếu giá trị là kiểu 'like', dùng where('column', 'like', value)
            if (strpos($value, '%') !== false) {
                $query->where($column, 'like', $value);
            } else {
                $query->where($column, $value);
            }
        }

        // Lọc và phân trang kết quả
        $all_accounts = $query->orderBy('updated_at', 'desc')->paginate(10);

        return view('admin.account.all_account', [
            'user' => $adminUser, 
            'all_accounts' => $all_accounts,
        ]);
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
        $user -> updated_at = Carbon::now('Asia/Ho_Chi_Minh');
        $user -> created_at = Carbon::now('Asia/Ho_Chi_Minh');
        $user -> save();

        $accountId = $user -> id;
        
        $account = new ModelsAdmin();
        $account -> username = $request -> username;
        $account -> password = Hash::make($request->password);
        $account -> user_id = $accountId;
        $account -> role = $request -> role;
        $user -> updated_at = Carbon::now('Asia/Ho_Chi_Minh');
        $user -> created_at = Carbon::now('Asia/Ho_Chi_Minh');
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
        $data['status'] = $request->status;
        $data['updated_at'] = Carbon::now('Asia/Ho_Chi_Minh');
        $data['created_at'] = Carbon::now('Asia/Ho_Chi_Minh');
        
        DB::table('accounts')->where('id', $account_id)->update($data);
        
        Session()->put('message', 'Sửa thành công');
        return Redirect::to('admin/accounts');
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
        $data['updated_at'] = Carbon::now('Asia/Ho_Chi_Minh');
        $data['created_at'] = Carbon::now('Asia/Ho_Chi_Minh');
        
        $user = User::find($adminUser->user_id);
        $user->update($data);

        return view('admin.account.info_user', ['user'=>$adminUser]);
    }

    public function password_account($id)
    {
        $admin = ModelsAdmin::findOrFail($id);
        return view('admin.account.change_password', ['user' => $admin], compact('admin'));
    }

    public function changePassword(Request $request, $id)
    {
        $admin = ModelsAdmin::findOrFail($id);
        $request->validate([
            'current_password' => ['required',
            function($attr, $value, $fail) use($admin) {
                if (!Hash::check($value, $admin->password)) {
                    $fail('Mật khẩu hiện tại không đúng.');
                }
            }],
            'new_password' => 'required|min:6|different:current_password',
            'confirm_password' => 'required|same:new_password',
        ]);

        if ($request->new_password === $request->current_password) {
            return redirect()->back()->withErrors(['new_password' => 'Mật khẩu mới không thể trùng với mật khẩu cũ.']);
        }

        // Cập nhật mật khẩu mới
        $data['password'] = bcrypt($request->new_password);
        $data['updated_at'] = Carbon::now('Asia/Ho_Chi_Minh');
        if($admin->update($data))
        {
            return redirect()->route('admin.accounts')->with('success', 'Updated your password');
        }
        else {
            return redirect()->back()->with('no', 'Something error.');
        }
    }

    public function export(Request $request) 
    {
        $query = DB::table('accounts');
        if ($request->has('keyword') && !empty($request->keyword)) {
            $keyword = $request->keyword;
            $query->where('username', 'like', '%' . $keyword . '%');
        }

        if ($request->has('role') && !empty($request->role)) {
            $role = $request->role;
            $query->where('role', $role);
        }

        if ($request->has('status') && $request->status !== '') {
            $status = $request->status;
            $query->where('status', $status);
        }

        // Nếu không có tham số nào thì không lọc
        if (!$request->has('keyword') && !$request->has('role') && !$request->has('status')) {
            // Không thêm điều kiện nào, lấy tất cả bản ghi
            $filteredAccounts = $query->orderBy('updated_at', 'desc')->get();
        } else {
            // Nếu có bất kỳ tham số nào thì áp dụng điều kiện lọc
            $filteredAccounts = $query->orderBy('updated_at', 'desc')->get();
        }

        // Tạo tên file cho Excel
        $fileName = 'account_' . Carbon::now('Asia/Ho_Chi_Minh')->format('Ymd_His') . '.xlsx';

        // Trả về file Excel đã lọc
        return Excel::download(new AdminExport($filteredAccounts), $fileName);
    }

}

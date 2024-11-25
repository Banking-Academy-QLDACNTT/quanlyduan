<?php

namespace App\Http\Controllers;

use App\Exports\AdminExport;
use App\Models\Admin;
use App\Models\Employee;
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

        // Lọc theo Name
        if ($request->has('keyword') && $request->keyword != '') {
            $filters['name'] = '%' . $request->keyword . '%';
        }

        // Lọc theo Department
        if ($request->has('department') && $request->department != '') {
            $filters['employees.departmentId'] = $request->department;
        }

        $query = DB::table('accounts')->join('employees', 'employees.id', '=', 'accounts.id')
        ->join('departments', 'departments.departmentId', '=', 'employees.departmentId')
        ->select(
        'accounts.*',
        'employees.*',
        'departments.*',
        'employees.departmentId as department_id',
        'employees.id as account_id',
        DB::raw('GREATEST(accounts.updatedAt, employees.updated_at) as lastUpdated'));

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
        $all_accounts = $query->orderBy('lastUpdated', 'desc')->paginate(5);
        $departments = DB::table('departments')->select('departmentId', 'departmentName')->get();

        return view('admin.account.all_account', [
            'user' => $adminUser, 
            'all_accounts' => $all_accounts,
            'departments' => $departments
        ]);
    }

    public function add_account()
    {
        $adminUser = Auth::guard('admins')->user();
        return view('admin.account.add_account', ['user'=>$adminUser]);
    }

    public function save_account(Request $request)
    {
        $adminUser = Auth::guard('admins')->user();
        $updateBy = DB::table('employees')->where('id', $adminUser->id)->pluck('employeeId')->first();
        $validatedData = $request->validate([
            'username' => 'required|unique:accounts|max:255',
            'password' => 'required|max:255',
            'dob' => 'required',
            'name' => 'required',
            'department' => 'required|',
            'sex' => 'required',
            'phoneNumber' => 'required|max:10|string',]);
        
        $account = new Admin();
        $account -> username = $request -> username;
        $account -> password = Hash::make($request->password);
        $account -> updated_at = Carbon::now('Asia/Ho_Chi_Minh');
        $account -> created_at = Carbon::now('Asia/Ho_Chi_Minh');
        $account -> updatedAt = Carbon::now('Asia/Ho_Chi_Minh');
        $account -> updateBy = $updateBy;    
        $account -> save();

        $accountId = $account -> id;

        $user = new Employee();
        $user -> name = $request -> name;
        $user -> phoneNumber = $request -> phoneNumber;
        $user -> departmentId = $request -> department;
        $user -> dateOfBirth = $request -> dob;
        $user -> sex = $request -> sex;
        $user -> id = $accountId;
        $user -> updated_at = Carbon::now('Asia/Ho_Chi_Minh');
        $user -> created_at = Carbon::now('Asia/Ho_Chi_Minh');
        $user -> save();

        return redirect::to('admin/accounts');
    }

    public function delete_account($account_id)
    {
        $adminUser = Auth::guard('admins')->user();
        DB::table('employees')->where('id', $account_id)->delete();
        DB::table('accounts')->where('id', $account_id)->delete();
        Session()->put('message', 'Xóa tài khoản thành công');
        return Redirect::to('admin/accounts');
    }

    public function edit_account($id)
    {
        $adminUser = Auth::guard('admins')->user();
        $account = DB::table('accounts')->where('id', $id)->pluck('id')->first();
        $all_account = DB::table('accounts')->get();
        $info = DB::table('accounts')->join('employees', 'employees.id', '=', 'accounts.id')
        ->join('departments', 'departments.departmentId', '=', 'employees.departmentId')
        ->select(
        'accounts.*',
        'employees.*',
        'departments.*',
        'employees.id as account_id')->where('accounts.id', $account)->first();
        $departments = DB::table('departments')->select('departmentId', 'departmentName')->get();
        return view('admin.account.edit_account', ['user' => $adminUser, 'infos'=>$info, 'departments'=>$departments], compact('account','all_account'));
    }

    public function update_account(Request $request, $id)
    {
        $adminUser = Auth::guard('admins')->user();
        $user_id = DB::table('employees')->where('id', $id)->pluck('id')->first();
        $data = array();
        $data['name'] = $request->name;
        $data['phoneNumber'] = $request->phoneNumber;
        $data['departmentId'] = $request->department;
        $data['dateOfBirth'] = $request->dob;
        $data['sex'] = $request->input('sex');
        $data['updated_at'] = Carbon::now('Asia/Ho_Chi_Minh');

        $data_account = array();
        $data_account['username'] = $request->username;
        $data_account['updatedAt'] = Carbon::now('Asia/Ho_Chi_Minh');
        $data_account['updated_at'] = Carbon::now('Asia/Ho_Chi_Minh');


        $account = Admin::find($id);
        $account->update($data_account);
        
        $user = Employee::find($user_id);
        $user->update($data);
        
        Session()->put('message', 'Sửa thành công');
        return Redirect::to('admin/accounts');
    }

    public function info_admin()
    {
        $adminUser = Auth::guard('admins')->user();
        $info = DB::table('accounts')->join('employees', 'employees.id', '=', 'accounts.id')
        ->join('departments', 'departments.departmentId', '=', 'employees.departmentId')
        ->select(
        'accounts.*',
        'employees.*',
        'departments.*',
        'employees.id as account_id')->where('accounts.id', $adminUser->id)->first();
        $departments = DB::table('departments')->select('departmentId', 'departmentName')->get();

        return view('admin.account.info_user', ['user'=>$adminUser, 'infos'=>$info, 'departments'=>$departments]);
    }

    public function save_info_admin(Request $request)
    {
        $adminUser = Auth::guard('admins')->user();
        
        $data = array();
        $data['name'] = $request->name;
        $data['phoneNumber'] = $request->phoneNumber;
        $data['departmentId'] = $request->department;
        $data['dateOfBirth'] = $request->dob;
        $data['sex'] = $request->input('sex');
        $data['updated_at'] = Carbon::now('Asia/Ho_Chi_Minh');
        
        $user = Employee::find($adminUser->id);
        $user->update($data);

        $info = DB::table('accounts')->join('employees', 'employees.id', '=', 'accounts.id')
        ->join('departments', 'departments.departmentId', '=', 'employees.departmentId')
        ->select(
        'accounts.*',
        'employees.*',
        'departments.*',
        'employees.id as account_id')->where('accounts.id', $adminUser->id)->first();
        $departments = DB::table('departments')->select('departmentId', 'departmentName')->get();

        return view('admin.account.info_user', ['user'=>$adminUser, 'infos'=>$info, 'departments'=>$departments]);
    }

    public function password_account($id)
    {
        $admin = Admin::findOrFail($id);
        return view('admin.account.change_password', ['user' => $admin], compact('admin'));
    }

    public function changePassword(Request $request, $id)
    {
        $admin = Admin::findOrFail($id);
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
        // $query = DB::table('accounts')
        // ->join('employees', 'employees.id', '=', 'accounts.id')
        // ->join('departments', 'departments.departmentId', '=', 'employees.departmentId')
        // ->select(
        //     'accounts.username',
        //     'employees.name',
        //     'employees.dateOfBirth',
        //     'employees.phoneNumber',
        //     'departments.departmentName'
        // );
        // if ($request->has('keyword') && !empty($request->keyword)) {
        //     $keyword = $request->keyword;
        //     $query->where('employees.name', 'like', '%' . $keyword . '%');
        // }

        // if ($request->has('department') && $request->department != '') {
        //     $department = $request->department;
        //     $query->where('employees.departmentId', $department);
        // }

        // // Nếu không có tham số nào thì không lọc
        // if (!$request->has('keyword') && !$request->has('department')) {
        //     // Không thêm điều kiện nào, lấy tất cả bản ghi
        //     $filteredAccounts = $query->orderBy('accounts.updatedAt', 'desc')->get();
        // } else {
        //     // Nếu có bất kỳ tham số nào thì áp dụng điều kiện lọc
        //     $filteredAccounts = $query->orderBy('accounts.updatedAt', 'desc')->get();
        // }

        $filters = [];

        // Lọc theo Name
        if ($request->has('keyword') && $request->keyword != '') {
            $filters['name'] = '%' . $request->keyword . '%';
        }

        // Lọc theo Department
        if ($request->has('department') && $request->department != '') {
            $filters['employees.departmentId'] = $request->department;
        }

        $query = DB::table('accounts')->join('employees', 'employees.id', '=', 'accounts.id')
        ->join('departments', 'departments.departmentId', '=', 'employees.departmentId')
        ->select(
        'accounts.*',
        'employees.*',
        'departments.*',
        'employees.id as account_id');

        // Lọc theo các điều kiện trong filters
        foreach ($filters as $column => $value) {
            // Nếu giá trị là kiểu 'like', dùng where('column', 'like', value)
            if (strpos($value, '%') !== false) {
                $query->where($column, 'like', $value);
            } else {
                $query->where($column, $value);
            }
        }

        $filteredAccounts = $query->orderBy('updatedAt', 'desc')->get();

        // Tạo tên file cho Excel
        $fileName = 'account_list_' . Carbon::now('Asia/Ho_Chi_Minh')->format('Ymd_His') . '.xlsx';

        // Trả về file Excel đã lọc
        return Excel::download(new AdminExport($filteredAccounts), $fileName);
    }

}

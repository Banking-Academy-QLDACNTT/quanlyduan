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
use Illuminate\Validation\Rule;

class CustomerController extends Controller
{
    public function all_customer(Request $request)
    {
        $adminUser = Auth::guard('admins')->user(); // Lấy thông tin admin đang đăng nhập
        $this->update_ranking();
        // Khởi tạo truy vấn
        $query = DB::table('customers')
            ->join('customer_type', 'customers.customerTypeId', '=', 'customer_type.customerTypeId')
            ->join('ranking_type', 'customers.rankingTypeId', '=', 'ranking_type.rankingTypeId')
            ->select(
                'customers.*',
                'customer_type.customerTypeName',
                'ranking_type.rankingTypeName'
            );

        // Áp dụng bộ lọc theo Tên khách hàng
        if ($request->has('keyword') && $request->keyword != '') {
            $query->where('customers.customerName', 'like', '%' . $request->keyword . '%');
        }

        // Áp dụng bộ lọc theo loại khách hàng
        if ($request->has('customerTypeId') && $request->customerTypeId != '') {
            $query->where('customers.customerTypeId', $request->customerTypeId);
        }

        // Áp dụng bộ lọc theo thẻ khách hàng
        if ($request->has('rankingTypeId') && $request->rankingTypeId != '') {
            $query->where('customers.rankingTypeId', $request->rankingTypeId);
        }

        // Phân trang kết quả
        $all_customers = $query->orderBy('customers.customerId', 'desc')->paginate(10);

        // Lấy danh sách loại khách hàng và thẻ khách hàng
        $customerTypes = DB::table('customer_type')->select('customerTypeId', 'customerTypeName')->get();
        $rankingTypes = DB::table('ranking_type')->select('rankingTypeId', 'rankingTypeName')->get();

        // Trả dữ liệu về view
        return view('admin.customer.all_customer', [
            'user' => $adminUser,
            'all_customers' => $all_customers,
            'customerTypes' => $customerTypes,
            'rankingTypes' => $rankingTypes,
        ]);
    }
    

    public function view_customer($id){
        $adminUser = Auth::guard('admins')->user();
        $customer = DB::table('customers')->where('customerId', $id)->first();
        $orders = DB::table('orders')->where('customerId', $id)->paginate(10); // Nếu muốn phân trang

        return view('admin.customer.view_customer', ['user' => $adminUser], compact('customer', 'orders'));
    }

    public function add_customer() {
        $adminUser = Auth::guard('admins')->user();
        return view('admin.customer.add_customer', ['user'=>$adminUser]);
    }

    public function save_customer(Request $request) {
        $adminUser = Auth::guard('admins')->user();
        $employeeId = DB::table('employees') ->where('id',$adminId)->value('employeeId');

        $adminId = session('admin_id');
        $data = array();
        $data['customerName'] = $request->tenkhachhang;
        $data['customerTypeId'] = $request->loaikhachhang;
        $data['note'] = $request->ghichu;
        $data['phoneNumber'] = $request->sodienthoai;
        $data['email'] = $request->email;
        $data['sex'] = $request->gioitinh;
        $data['employeeId'] = $employeeId;
        $data['updateAt'] = Carbon::now('Asia/Ho_Chi_Minh');
        $data['address'] = $request->diachi;

        // Kiểm tra xem số điện thoại đã tồn tại chưa
    $existingPhone = DB::table('customers')->where('phoneNumber', $data['phoneNumber'])->exists();
    if ($existingPhone) {
        return back()->withInput()->withErrors(['sodienthoai' => 'Số điện thoại đã tồn tại.']);
    }

    // Kiểm tra xem email đã tồn tại chưa
    $existingEmail = DB::table('customers')->where('email', $data['email'])->exists();
    if ($existingEmail) {
        return back()->withInput()->withErrors(['email' => 'Email đã tồn tại.']);
    }

    // Kiểm tra xem tên khách hàng đã tồn tại chưa
    $existingCustomerName = DB::table('customers')->where('customerName', $data['customerName'])->exists();
    if ($existingCustomerName) {
        return back()->withInput()->withErrors(['tenkhachhang' => 'Tên khách hàng đã tồn tại.']);
    }

    // Kiểm tra nếu không có trùng lặp, thực hiện thêm khách hàng mới
    DB::table('customers')->insert($data);
    Session()->put('message', 'Thêm khách hàng thành công');
    return Redirect::to('admin/all-customer');
    }

    public function delete_customer($customerId) {
        $adminUser = Auth::guard('admins')->user();
        $customer = DB::table('customers')->where('customerId', $customerId)->first();
        DB::table('customers')->where('customerId', $customerId)->delete();
        Session()->put('message', 'Xóa lịch thi thành công');
        return Redirect::to('admin/all-customer');
    }

    public function edit_customer($customerId) {
        $adminUser = Auth::guard('admins')->user();
        $customer = DB::table('customers')->where('customerId', $customerId)->first();
        $customer_type = DB::table('customer_type')->get();
        return view('admin.customer.edit_customer', ['user' => $adminUser],compact('customer', 'customer_type'));
    }

    public function update_customer(Request $request, $customerId)
    {
        // Validate dữ liệu đầu vào
        $data = $request->validate([
            'tenkhachhang' => [
                'required',
                Rule::unique('customers', 'customerName')->ignore($customerId, 'customerId')
            ],
            'loaikhachhang' => 'required',
            'diachi' => 'required',
            'sodienthoai' => [
                'required',
                Rule::unique('customers', 'phoneNumber')->ignore($customerId, 'customerId')
            ],
            'email' => [
                'required',
                Rule::unique('customers', 'email')->ignore($customerId, 'customerId')
            ],
            'ghichu' => 'nullable',
            'gioitinh' => 'nullable',
        ],
        [
            'tenkhachhang.unique' => 'Tên khách hàng đã tồn tại.',
            'sodienthoai.unique' => 'Số điện thoại đã tồn tại.',
            'email.unique' => 'Email đã tồn tại.',
        ]
    );        

        // Cập nhật thông tin trong database
        $adminId = session('admin_id');
        $employeeId = DB::table('employees') ->where('id',$adminId)->value('employeeId');

        $data = array();
        $data['customerName'] = $request->tenkhachhang;
        $data['customerTypeId'] = $request->loaikhachhang;
        $data['note'] = $request->ghichu;
        $data['phoneNumber'] = $request->sodienthoai;
        $data['email'] = $request->email;
        $data['sex'] = $request->gioitinh;
        $data['updateAt'] = Carbon::now('Asia/Ho_Chi_Minh');
        $data['updateBy'] = $employeeId;
        $data['address'] = $request->diachi;

        DB::table('customers')->where('customerId', $customerId)->update($data);
        Session()->put('message', 'Sửa thông tin khách hàng thành công');
        return Redirect::to('admin/all-customer');
    }

    public function update_ranking()
    {
        // Lấy tất cả các khách hàng
        $customers = DB::table('customers')->get();

        foreach ($customers as $customer) {
            // Đếm số lượng đơn hàng hoàn thành của mỗi khách hàng
            $completedOrdersCount = DB::table('orders')
                ->where('customerId', $customer->customerId)
                ->where('orderStatusId', 3)
                ->count();

            // Tính tổng giá trị đơn hàng đã hoàn thành của mỗi khách hàng
            $totalOrderValue = DB::table('orders')
                ->where('customerId', $customer->customerId)
                ->where('orderStatusId', 3)
                ->sum('totalOrderValue');

            // Kiểm tra nếu khách hàng có hơn 5 đơn hàng hoàn thành và tổng giá trị đơn hàng > 100,000,000
            if ($completedOrdersCount > 5 && $totalOrderValue > 100000000) {
                // Cập nhật loại khách hàng thành "khách hàng thân thiết" (rankingTypeId = 2)
                DB::table('customers')
                    ->where('customerId', $customer->customerId)
                    ->update(['rankingTypeId' => 2]);
            } else {
                // Nếu không thỏa mãn, cập nhật loại khách hàng thành "khách hàng thường" (rankingTypeId = 1)
                DB::table('customers')
                    ->where('customerId', $customer->customerId)
                    ->update(['rankingTypeId' => 1]);
            }
        }
    }
}
<?php

namespace App\Http\Controllers;

use App\Exports\AdminExport;
use App\Exports\PaymentSlipExport;
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

class PaymentSlipController extends Controller
{
    public function all_paymentslip(Request $request) {
        $adminUser = Auth::guard('admins')->user(); // Lấy thông tin admin đang đăng nhập
    
        // Khởi tạo truy vấn
        $query = DB::table('payment_slips')
            ->join('payment_method', 'payment_slips.paymentMethodId', '=', 'payment_method.paymentMethodId')
            ->join('payment_status', 'payment_slips.paymentStatusId', '=', 'payment_status.paymentStatusId')
            ->select('payment_slips.*', 'payment_method.paymentMethodName', 'payment_status.paymentStatusName');
    
        // Áp dụng bộ lọc theo keyword (Tên sản phẩm)
        if ($request->has('keyword') && $request->keyword != '') {
            $query->where('payment_slips.paymentslipName', 'like', '%' . $request->keyword . '%');
        }
    
        // Áp dụng bộ lọc theo phương thức thanh toán
        if ($request->has('paymentMethod') && $request->paymentMethod != '') {
            $query->where('payment_slips.paymentMethodId', $request->paymentMethod);
        }
    
        // Áp dụng bộ lọc theo trạng thái thanh toán
        if ($request->has('paymentStatus') && $request->paymentStatus != '') {
            $query->where('payment_slips.paymentStatusId', $request->paymentStatus);
        }
    
        // Phân trang kết quả
        $all_paymentslip = $query->orderBy('payment_slips.paymentSlipId', 'desc')->paginate(5);
    
        // Lấy danh sách phương thức thanh toán và trạng thái thanh toán để hiển thị trong dropdown
        $paymentMethods = DB::table('payment_method')
            ->select('paymentMethodId', 'paymentMethodName')
            ->get();
    
        $paymentStatuses = DB::table('payment_status')
            ->select('paymentStatusId', 'paymentStatusName')
            ->get();
    
        // Trả dữ liệu về view
        return view('admin.paymentslip.all_paymentslip', [
            'user' => $adminUser,
            'all_paymentslip' => $all_paymentslip,
            'paymentMethods' => $paymentMethods,
            'paymentStatuses' => $paymentStatuses,
        ]);
    }
    
    public function add_paymentslip() {
        $adminUser = Auth::guard('admins')->user();
        return view('admin.paymentslip.add_paymentslip', ['user'=>$adminUser]);
    }
    public function save_paymentslip(Request $request) {
        $adminUser = Auth::guard('admins')->user();

        $adminId = session('admin_id');
        $employeeID = DB::table('employees') ->where('id',$adminId)->value('employeeId');
        $data = array();
        $data['orderId'] = $request->orderId;
        $data['deposit'] = $request->tiendatcoc;
        $data['depositDate'] = $request->ngaydatcoc;
        $data['paymentDate'] = $request->ngaythanhtoan;
        $data['paymentMethodId'] = $request->pttt;
        $data['note'] = $request->ghichu;
        $data['paymentStatusId'] = $request->ttp;
        $data['employeeId'] = $employeeID;
        $data['updateAt'] = Carbon::now('Asia/Ho_Chi_Minh');
        $data['updateBy'] = $employeeID;


    // Kiểm tra nếu không có trùng lặp, thực hiện thêm khách hàng mới
    DB::table('payment_slips')->insert($data);
    Session()->put('message', 'Thêm phiếu thành công');
    return Redirect::to('admin/all-paymentslip');
    if (strtotime($request->ngaydatcoc) > strtotime($request->ngaythanhtoan)) {
        return redirect()->back()->with('error', 'Ngày đặt cọc không được lớn hơn ngày thanh toán!');
    }
    $orderTotal = DB::table('orders')->where('orderId', $request->orderId)->value('totalOrderValue');

    if (!$orderTotal) {
        return redirect()->back()->with('error', 'Không tìm thấy tổng giá trị đơn hàng!');
    }
    
    if ($request->tiendatcoc > $orderTotal) {
        return redirect()->back()->with('error', 'Tiền đặt cọc không được lớn hơn tổng giá trị đơn hàng!');
    }
    

    }
    public function delete_paymentslip($paymentslipId) {
        $adminUser = Auth::guard('admins')->user();
        $paymentslip = DB::table('payment_slips')->where('paymentSlipId', $paymentslipId)->first();
        DB::table('payment_slips')->where('paymentSlipId', $paymentslipId)->delete();
        Session()->put('message', 'Xóa phiếu thành công');
        return Redirect::to('admin/all-paymentslip');
    }
     public function edit_paymentslip($paymentslipId) {
        $adminUser = Auth::guard('admins')->user();
        $paymentslip = DB::table('payment_slips')->where('paymentSlipId', $paymentslipId)->first();
        $paymentslip_type = DB::table('payment_slips')->get();
        return view('admin.paymentslip.edit_paymentslip', ['user' => $adminUser],compact('paymentslip', 'paymentslip_type'));
    }

    public function update_paymentslip(Request $request, $paymentslipId)
    {
        // Validate dữ liệu đầu vào
        $data = $request->validate([
            'orderId' => 'required|integer', // Chắc chắn là giá trị số nguyên
            'tiendatcoc' => 'required|integer', // Chỉ chấp nhận giá trị số nguyên
            'ngaydatcoc' => 'required|date',
            'ngaythanhtoan' => 'required|date',
            'pttt' => 'required',
            'ghichu' => 'nullable',
            'ttp' => 'required',
        ],
        [
            'orderId.required' => 'Mã đơn hàng là bắt buộc.',
            'tiendatcoc.required' => 'Số tiền đặt cọc là bắt buộc.',
            'tiendatcoc.integer' => 'Số tiền đặt cọc phải là số nguyên.',
            'pttt.required' => 'Phương thức thanh toán là bắt buộc.',
            'ttp.required' => 'Trạng thái thanh toán là bắt buộc.',
        ]);
    
        // Cập nhật thông tin trong database
        $adminId = session('admin_id');
        $employeeID = DB::table('employees') ->where('id',$adminId)->value('employeeId');
        $data = array();
        $data = [
            'orderId' => $request->orderId,
            'deposit' => $request->tiendatcoc,
            'depositDate' => $request->ngaydatcoc,
            'paymentDate' => $request->ngaythanhtoan,
            'paymentMethodId' => $request->pttt,
            'note' => $request->ghichu,
            'paymentStatusId' => $request->ttp,
            'employeeId' => $employeeID, // Gán lại với `employeeId` của admin
            'updateAt' => Carbon::now('Asia/Ho_Chi_Minh'),
            'updateBy' => $employeeID,
        ];
    
        DB::table('payment_slips')->where('paymentSlipId', $paymentslipId)->update($data);
        return Redirect::to('admin/all-paymentslip');
    }
    public function export_paymentslip(Request $request)
    {
        $filters = [];
    
        // Lọc theo phương thức thanh toán (paymentMethod)
        if ($request->has('paymentMethod') && $request->paymentMethod != '') {
            $filters['payment_slips.paymentMethodId'] = $request->paymentMethod;
        }
    
        // Lọc theo trạng thái phiếu (paymentStatus)
        if ($request->has('paymentStatus') && $request->paymentStatus != '') {
            $filters['payment_slips.paymentStatusId'] = $request->paymentStatus;
        }
    
        // Tạo truy vấn cơ bản
        $query = DB::table('payment_slips')
            ->join('payment_method', 'payment_slips.paymentMethodId', '=', 'payment_method.paymentMethodId')
            ->join('payment_status', 'payment_slips.paymentStatusId', '=', 'payment_status.paymentStatusId')
            ->select(
                'payment_slips.*',
                'payment_method.paymentMethodName',
                'payment_status.paymentStatusName'
            );
    
        // // Áp dụng các điều kiện lọc
        // foreach ($filters as $column => $value) {
        //     $query->where($column, $value);
        // }
       // Lọc theo các điều kiện trong filters
       foreach ($filters as $column => $value) {
        // Nếu giá trị là kiểu 'like', dùng where('column', 'like', value)
        if (strpos($value, '%') !== false) {
            $query->where($column, 'like', $value);
        } else {
            $query->where($column, $value);
        }
    }
        // Lấy dữ liệu sau khi lọc


        $filteredPaymentSlips = $query->orderBy('updateAt', 'desc')->get();
    
        // Tạo tên file Excel
        $fileName = 'payment_slips_' . Carbon::now('Asia/Ho_Chi_Minh')->format('Ymd_His') . '.xlsx';
    
        // Trả về file Excel đã lọc
        return Excel::download(new PaymentSlipExport($filteredPaymentSlips), $fileName);
    }
    
    
}
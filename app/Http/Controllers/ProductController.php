<?php

namespace App\Http\Controllers;

use App\Exports\AdminExport;
use App\Exports\ProductExport;
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

class ProductController extends Controller
{
    public function all_product(Request $request) {
        $adminUser = Auth::guard('admins')->user(); // Lấy thông tin admin đang đăng nhập
    
        // Khởi tạo truy vấn
        $query = DB::table('product')
            ->join('product_type', 'product.productTypeId', '=', 'product_type.productTypeId')
            ->select('product.*', 'product_type.productTypeName');
    
        // Áp dụng bộ lọc theo keyword (Tên sản phẩm)
        if ($request->has('keyword') && $request->keyword != '') {
            $query->where('product.productName', 'like', '%' . $request->keyword . '%');
        }
    
        // Áp dụng bộ lọc theo productType
        if ($request->has('productType') && $request->productType != '') {
            $query->where('product.productTypeId', $request->productType);
        }
    
        // Phân trang kết quả
        $all_product = $query->orderBy('product.productId', 'desc')->paginate(5);
    
        // Lấy danh sách loại sản phẩm để hiển thị trong dropdown
        $productTypes = DB::table('product_type')
            ->select('productTypeId', 'productTypeName')
            ->get();
    
        // Trả dữ liệu về view
        return view('admin.product.all_product', [
            'user' => $adminUser,
            'all_product' => $all_product,
            'productTypes' => $productTypes,
        ]);
    }
    public function add_product()
    {
        $adminUser = Auth::guard('admins')->user();
        return view('admin.product.add_product', ['user'=>$adminUser]);
    }


    public function save_product(Request $request) {
        $adminUser = Auth::guard('admins')->user();

        $adminId = session('admin_id');
        $data = array();
        $data['productName'] = $request->tensanpham;
        $data['productTypeId'] = $request->loaisanpham;
        $data['price'] = $request->giaban;
        $data['employeeId'] = $adminId;
        $data['updateAt'] = Carbon::now('Asia/Ho_Chi_Minh');
        $data['updateBy'] = $adminId;
    // Kiểm tra xem tên sản phẩm đã tồn tại chưa
    $existingProductName = DB::table('product')->where('productName', $data['productName'])->exists();
    if ($existingProductName) {
        return back()->withInput()->withErrors(['tensanpham' => 'Tên sản phẩm đã tồn tại.']);
    }
    DB::table('product')->insert($data);
    Session()->put('message', 'Thêm sản phẩm thành công');
    return Redirect::to('admin/all-product');
}
    public function delete_product($productId) {
        $adminUser = Auth::guard('admins')->user();
        $product = DB::table('product')->where('productId', $productId)->first();
        DB::table('product')->where('productId', $productId)->delete();
        Session()->put('message', 'Xóa sản phẩm thành công');
        return Redirect::to('admin/all-product');
    }
    public function edit_product($productId) {
        $adminUser = Auth::guard('admins')->user();
        $product = DB::table('product')->where('productId', $productId)->first();
        $product_type = DB::table('product_type')->get();
        return view('admin.product.edit_product', ['user' => $adminUser],compact('product', 'product_type'));
    }
    public function update_product(Request $request, $productId)
    {
        // Validate dữ liệu đầu vào
        $data = $request->validate([
            'tensanpham' => [
                'required',
                Rule::unique('product', 'productName')->ignore($productId, 'productId')
            ],
            'loaisanpham' => 'required',
            'giaban' => 'required',
        ],
        [
            'tensanpham.unique' => 'Tên sản phẩm đã tồn tại.',
        ]
    );        

        // Cập nhật thông tin trong database
        $adminId = session('admin_id');
        $data = array();
        $data['productName'] = $request->tensanpham;
        $data['productTypeId'] = $request->loaisanpham;
        $data['price'] = $request->giaban;
        $data['updateAt'] = Carbon::now('Asia/Ho_Chi_Minh');
        $data['updateBy'] = $adminId;

        DB::table('product')->where('productId', $productId)->update($data);
        Session()->put('message', 'Sửa thông tin sản phẩm thành công');
        return Redirect::to('admin/all-product');
    }
    public function export_product(Request $request)
    {
        // Khởi tạo bộ lọc từ các tham số trong request
        $filters = [];
        
        // Lọc theo tên sản phẩm (productName)
        if ($request->has('keyword') && $request->keyword != '') {
            $filters['product.productName'] = '%' . $request->keyword . '%';  // Lọc theo tên sản phẩm
        }
    
        // Lọc theo loại sản phẩm (productType)
        if ($request->has('productType') && $request->productType != '') {
            $filters['product.productTypeId'] = $request->productType;  // Lọc theo loại sản phẩm
        }
    
        // Tạo truy vấn cơ bản
        $query = DB::table('product')
            ->join('product_type', 'product.productTypeId', '=', 'product_type.productTypeId')
            ->select(
                'product.productId',
                'product.productName',
                'product.price',
                'product_type.productTypeName',
                'product.updateAt'
            );
    
        // Áp dụng các điều kiện lọc
        foreach ($filters as $column => $value) {
            // Nếu giá trị là kiểu 'like', dùng where('column', 'like', value)
            if (strpos($value, '%') !== false) {
                $query->where($column, 'like', $value);
            } else {
                $query->where($column, $value);
            }
        }
    
        // Lấy dữ liệu sau khi lọc
        $filteredProducts = $query->orderBy('product.updateAt', 'desc')->get();
    
        // Tạo tên file Excel
        $fileName = 'product_list_' . Carbon::now('Asia/Ho_Chi_Minh')->format('Ymd_His') . '.xlsx';
    
        // Trả về file Excel đã lọc
        return Excel::download(new ProductExport($filteredProducts), $fileName);
    }
     
}

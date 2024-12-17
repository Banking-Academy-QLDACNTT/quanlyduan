<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\OrderStatus;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    // Hàm lấy danh sách tất cả đơn hàng
    public function all_order() {
        // Lấy thông tin admin hiện tại
        $adminUser = Auth::guard('admins')->user();
        
        // Truy vấn danh sách đơn hàng cùng thông tin liên quan
        $all_orders = DB::table('orders')
            ->join('order_status', 'orders.orderStatusId', '=', 'order_status.orderStatusId') // Kết nối với bảng trạng thái
            ->join('employees', 'orders.employeeId', '=', 'employees.employeeId') // Kết nối với bảng nhân viên
            ->join('customers', 'orders.customerId', '=', 'customers.customerId') // Kết nối với bảng khách hàng
            ->select(
                'orders.orderId',
                'orders.orderName',
                'order_status.orderStatusName',
                'orders.orderDate',
                'customers.customerName',
                'employees.name as employeeName',
                'orders.totalOrderValue',
                'orders.note',
                'orders.updateAt',
                'orders.updateBy'
            )
            ->orderBy('orders.orderId', 'desc') // Sắp xếp theo mã đơn hàng giảm dần
            ->get();

        return view('admin.order.all_order', [
            'user' => $adminUser,
            'all_orders' => $all_orders,
        ]);
    }

    // Hàm lấy chi tiết đơn hàng
    public function order_details($id) {
        // Lấy thông tin admin hiện tại
        $adminUser = Auth::guard('admins')->user();
        
        // Lấy thông tin đơn hàng
        $order = DB::table('orders')
            ->join('order_status', 'orders.orderStatusId', '=', 'order_status.orderStatusId') // Kết nối với bảng trạng thái
            ->join('employees', 'orders.employeeId', '=', 'employees.employeeId') // Kết nối với bảng nhân viên
            ->join('customers', 'orders.customerId', '=', 'customers.customerId') // Kết nối với bảng khách hàng
            ->select(
                'orders.orderId',
                'orders.orderName',
                'order_status.orderStatusName',
                'orders.orderDate',
                'customers.customerName',
                'employees.name as employeeName',
                'orders.totalOrderValue',
                'orders.note',
                'orders.updateAt',
                'orders.updateBy'
            )
            ->where('orders.orderId', $id) // Lọc theo ID đơn hàng
            ->first();

        // Lấy chi tiết đơn hàng và thông tin sản phẩm liên quan
        $order_details = DB::table('order_details')
        ->join('product', 'order_details.productId', '=', 'product.productId')  // Kết nối với bảng product
        ->join('product_type', 'product.productTypeId', '=', 'product_type.productTypeId')  // Kết nối với bảng product_type
        ->leftJoin('size', 'order_details.sizeId', '=', 'size.sizeId')  // Kết nối với bảng size qua order_details
        ->leftJoin('color', 'order_details.colorId', '=', 'color.colorId')  // Kết nối với bảng color qua order_details
        ->leftJoin('material', 'order_details.materialId', '=', 'material.materialId')  // Kết nối với bảng material qua order_details
        ->select(
            'order_details.*',
            'product.productName', 
            'product.price', 
            'product.productTypeId', 
            'product_type.productTypeName',
            'size.sizeName', 
            'color.colorName', 
            'material.materialName'
        )
        ->where('order_details.orderId', $id)
        ->get();

    // Trả về view với thông tin đơn hàng và chi tiết
    return view('admin.order.order_details', [
        'user' => $adminUser,
        'order' => $order,
        'order_details' => $order_details,
    ]);
    }

    public function add_order(Request $request) {
        $adminUser = Auth::guard('admins')->user();

        // Lấy orderId tạm thời (MAX(orderId) + 1)
        $orderId = DB::table('orders')->max('orderId') + 1;
    
        // Lấy danh sách sản phẩm và các thuộc tính (màu sắc, kích cỡ, chất liệu) từ các bảng nối
        $products = DB::table('product')
            ->leftJoin('product_color', 'product.productId', '=', 'product_color.productId')
            ->leftJoin('product_size', 'product.productId', '=', 'product_size.productId')
            ->leftJoin('product_material', 'product.productId', '=', 'product_material.productId')
            ->leftJoin('color', 'product_color.colorId', '=', 'color.colorId')
            ->leftJoin('size', 'product_size.sizeId', '=', 'size.sizeId')
            ->leftJoin('material', 'product_material.materialId', '=', 'material.materialId')
            ->select('product.price', 'product.productId', 'product.productName', 
                     'product_color.colorId', 'color.colorName', 
                     'product_size.sizeId', 'size.sizeName', 
                     'product_material.materialId', 'material.materialName')
            ->get();
    
        // Tạo cấu trúc dữ liệu theo từng sản phẩm và các thuộc tính đi kèm
        $productAttributes = [];
    
        foreach ($products as $product) {
            // Cập nhật tên sản phẩm
            if (!isset($productAttributes[$product->productId])) {
                $productAttributes[$product->productId] = [
                    'name' => $product->productName,
                    'price' => $product->price, // Thêm giá sản phẩm vào đây
                    'colors' => [],
                    'sizes' => [],
                    'materials' => []
                ];
            }
    
            // Màu sắc: Nếu chưa có màu sắc cho sản phẩm này, thêm vào
            if ($product->colorId && !in_array($product->colorId, array_column($productAttributes[$product->productId]['colors'], 'id'))) {
                $productAttributes[$product->productId]['colors'][] = [
                    'id' => $product->colorId, 
                    'name' => $product->colorName
                ];
            }
    
            // Kích cỡ: Nếu chưa có kích cỡ cho sản phẩm này, thêm vào
            if ($product->sizeId && !in_array($product->sizeId, array_column($productAttributes[$product->productId]['sizes'], 'id'))) {
                $productAttributes[$product->productId]['sizes'][] = [
                    'id' => $product->sizeId, 
                    'name' => $product->sizeName
                ];
            }
    
            // Chất liệu: Nếu chưa có chất liệu cho sản phẩm này, thêm vào
            if ($product->materialId && !in_array($product->materialId, array_column($productAttributes[$product->productId]['materials'], 'id'))) {
                $productAttributes[$product->productId]['materials'][] = [
                    'id' => $product->materialId, 
                    'name' => $product->materialName
                ];
            }
        }
    
        // Kiểm tra nếu có sản phẩm thêm vào giỏ hàng tạm thời qua session
        if ($request->has('add_to_cart')) {
            // Kiểm tra giỏ hàng tạm thời trong session
            $cart = session()->get('cart', []);
    
            // Thêm sản phẩm vào giỏ hàng
            $productId = $request->input('productId');
            $quantity = $request->input('quantity');
            $colorId = $request->input('colorId');
            $sizeId = $request->input('sizeId');
            $materialId = $request->input('materialId');
    
            // Lấy giá sản phẩm từ cấu trúc dữ liệu productAttributes
            $price = isset($productAttributes[$productId]) ? $productAttributes[$productId]['price'] : 0;
    
            // Kiểm tra nếu sản phẩm đã có trong giỏ hàng
            if (isset($cart[$productId])) {
                $cart[$productId]['quantity'] += $quantity; // Cập nhật số lượng
            } else {
                $cart[$productId] = [
                    'productId' => $productId,
                    'quantity' => $quantity,
                    'colorId' => $colorId,
                    'sizeId' => $sizeId,
                    'materialId' => $materialId,
                    'price' => $price // Lưu giá sản phẩm vào giỏ hàng
                ];
            }
    
            // Cập nhật giỏ hàng vào session
            session()->put('cart', $cart);
        }
    
        // Lấy giỏ hàng từ session
        $cart = session()->get('cart', []);
    
        return view('admin.order.add_order', [
            'user' => $adminUser,
            'productAttributes' => $productAttributes,
            'cart' => $cart,
            'orderId' => $orderId
        ]);
    }
    
    public function save_order(Request $request)
    {
        $validatedData = $request->validate([
            'orderId' => 'required|string|max:255',
            'customerId' => 'required|integer',
            'orderDate' => 'required|date',
            'orderStatusId' => 'required|integer',
            'products' => 'required|string', // Sản phẩm dưới dạng chuỗi JSON
            'orderName' => 'required|string|max:255', // Thêm validation cho orderName
            'note' => 'nullable|string', // Thêm validation cho note
        ]);
    
        DB::beginTransaction();
    
        try {
            // Lấy thông tin người dùng quản trị viên hiện tại
            $adminUser = Auth::guard('admins')->user();
            $adminId = session('admin_id');
            $employeeID = DB::table('employees')->where('id', $adminId)->value('employeeId');
    
            // Giải mã dữ liệu sản phẩm từ chuỗi JSON
            $products = json_decode($validatedData['products'], true);
            
            // Tính tổng giá trị đơn hàng
            $totalOrderValue = 0;
            foreach ($products as $product) {
                $totalOrderValue += $product['total']; // Giả sử 'total' là giá trị tổng của mỗi sản phẩm
            }
    
            // Lưu đơn hàng vào bảng 'orders'
            $order = order::create([
                'orderId' => $validatedData['orderId'],
                'customerId' => $validatedData['customerId'],
                'orderDate' => $validatedData['orderDate'],
                'orderStatusId' => $validatedData['orderStatusId'],
                'orderName' => $validatedData['orderName'], // Sử dụng orderName từ request
                'note' => $validatedData['note'], // Sử dụng note từ request
                'totalOrderValue' => $totalOrderValue,
                'updateAt' => Carbon::now('Asia/Ho_Chi_Minh'),
                'employeeId' => $employeeID,
            ]);


            if (!is_array($products) || empty($products)) {
                throw new \Exception('Danh sách sản phẩm không hợp lệ.');
            }

            // Lưu chi tiết đơn hàng vào bảng order_details
            foreach ($products as $product) {
                OrderDetail::create([
                    'orderId' => $order->orderId,
                    'productId' => $product['productId'],
                    'quantity' => $product['quantity'],
                    'colorId' => $product['color'],
                    'sizeId' => $product['size'],
                    'materialId' => $product['material'],
                ]);
            }

            DB::commit();

            return redirect()->route('admin.order.all')->with('success', 'Đơn hàng đã được lưu thành công!');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withErrors(['error' => 'Có lỗi xảy ra khi lưu đơn hàng: ' . $e->getMessage()]);
        }
    }
    

    public function edit_order(Request $request, $id)
    {
        // Lấy thông tin admin hiện tại
        $adminUser = Auth::guard('admins')->user();
                
        // Lấy thông tin đơn hàng
        $order = DB::table('orders')
            ->join('order_status', 'orders.orderStatusId', '=', 'order_status.orderStatusId') // Kết nối với bảng trạng thái
            ->join('employees', 'orders.employeeId', '=', 'employees.employeeId') // Kết nối với bảng nhân viên
            ->join('customers', 'orders.customerId', '=', 'customers.customerId') // Kết nối với bảng khách hàng
            ->select(
                'orders.orderId',
                'orders.orderName',
                'order_status.orderStatusId',
                'order_status.orderStatusName',
                'orders.orderDate',
                'customers.customerId',
                'customers.customerName',
                'employees.name as employeeName',
                'orders.totalOrderValue',
                'orders.note',
                'orders.updateAt',
                'orders.updateBy'
            )
            ->where('orders.orderId', $id) // Lọc theo ID đơn hàng
            ->first();

        // Lấy danh sách khách hàng
        $customers = DB::table('customers')->get();

        // Lấy danh sách khách hàng
        $statuses = DB::table('order_status')->get();
    
        // Lấy chi tiết đơn hàng và thông tin sản phẩm liên quan
        $order_details = DB::table('order_details')
            ->join('product', 'order_details.productId', '=', 'product.productId')  // Kết nối với bảng product
            ->join('product_type', 'product.productTypeId', '=', 'product_type.productTypeId')  // Kết nối với bảng product_type
            ->leftJoin('size', 'order_details.sizeId', '=', 'size.sizeId')  // Kết nối với bảng size qua order_details
            ->leftJoin('color', 'order_details.colorId', '=', 'color.colorId')  // Kết nối với bảng color qua order_details
            ->leftJoin('material', 'order_details.materialId', '=', 'material.materialId')  // Kết nối với bảng material qua order_details
            ->select(
                'order_details.*',
                'product.productName', 
                'product.price', 
                'product.productTypeId', 
                'product_type.productTypeName',
                'size.sizeName', 
                'color.colorName', 
                'material.materialName'
            )
            ->where('order_details.orderId', $id)
            ->get();

        // Tính toán tổng giá cho từng sản phẩm
            foreach ($order_details as $item) {
                $item->totalPrice = $item->price * $item->quantity; // Tính tổng giá
            }
        
        // Lấy danh sách sản phẩm và các thuộc tính (màu sắc, kích cỡ, chất liệu) từ các bảng nối
        $products = DB::table('product')
        ->leftJoin('product_color', 'product.productId', '=', 'product_color.productId')
        ->leftJoin('product_size', 'product.productId', '=', 'product_size.productId')
        ->leftJoin('product_material', 'product.productId', '=', 'product_material.productId')
        ->leftJoin('color', 'product_color.colorId', '=', 'color.colorId')
        ->leftJoin('size', 'product_size.sizeId', '=', 'size.sizeId')
        ->leftJoin('material', 'product_material.materialId', '=', 'material.materialId')
        ->select('product.price', 'product.productId', 'product.productName', 
                 'product_color.colorId', 'color.colorName', 
                 'product_size.sizeId', 'size.sizeName', 
                 'product_material.materialId', 'material.materialName')
        ->get();
    
        // Tạo cấu trúc dữ liệu theo từng sản phẩm và các thuộc tính đi kèm
        $productAttributes = [];
    
        foreach ($products as $product) {
            // Cập nhật tên sản phẩm
            if (!isset($productAttributes[$product->productId])) {
                $productAttributes[$product->productId] = [
                    'name' => $product->productName,
                    'price' => $product->price, // Thêm giá sản phẩm vào đây
                    'colors' => [],
                    'sizes' => [],
                    'materials' => []
                ];
            }
    
            // Màu sắc: Nếu chưa có màu sắc cho sản phẩm này, thêm vào
            if ($product->colorId && !in_array($product->colorId, array_column($productAttributes[$product->productId]['colors'], 'id'))) {
                $productAttributes[$product->productId]['colors'][] = [
                    'id' => $product->colorId, 
                    'name' => $product->colorName
                ];
            }
    
            // Kích cỡ: Nếu chưa có kích cỡ cho sản phẩm này, thêm vào
            if ($product->sizeId && !in_array($product->sizeId, array_column($productAttributes[$product->productId]['sizes'], 'id'))) {
                $productAttributes[$product->productId]['sizes'][] = [
                    'id' => $product->sizeId, 
                    'name' => $product->sizeName
                ];
            }
    
            // Chất liệu: Nếu chưa có chất liệu cho sản phẩm này, thêm vào
            if ($product->materialId && !in_array($product->materialId, array_column($productAttributes[$product->productId]['materials'], 'id'))) {
                $productAttributes[$product->productId]['materials'][] = [
                    'id' => $product->materialId, 
                    'name' => $product->materialName
                ];
            }
        }
    
        // Kiểm tra nếu có sản phẩm thêm vào giỏ hàng tạm thời qua session
        if ($request->has('add_to_cart')) {
            // Kiểm tra giỏ hàng tạm thời trong session
            $cart = session()->get('cart', []);
    
            // Thêm sản phẩm vào giỏ hàng
            $productId = $request->input('productId');
            $quantity = $request->input('quantity');
            $colorId = $request->input('colorId');
            $sizeId = $request->input('sizeId');
            $materialId = $request->input('materialId');
    
            // Lấy giá sản phẩm từ cấu trúc dữ liệu productAttributes
            $price = isset($productAttributes[$productId]) ? $productAttributes[$productId]['price'] : 0;
    
            // Kiểm tra nếu sản phẩm đã có trong giỏ hàng
            if (isset($cart[$productId])) {
                $cart[$productId]['quantity'] += $quantity; // Cập nhật số lượng
            } else {
                $cart[$productId] = [
                    'productId' => $productId,
                    'quantity' => $quantity,
                    'colorId' => $colorId,
                    'sizeId' => $sizeId,
                    'materialId' => $materialId,
                    'price' => $price // Lưu giá sản phẩm vào giỏ hàng
                ];
            }
    
            // Cập nhật giỏ hàng vào session
            session()->put('cart', $cart);
        }
    
        // Lấy giỏ hàng từ session
        $cart = session()->get('cart', []);
    
        // Trả về view với thông tin đơn hàng và sản phẩm
        return view('admin.order.edit_order', [
            'user' => $adminUser,
            'order' => $order,
            'orderDetails' => $order_details,
            'productAttributes' => $productAttributes,
            'cart' => $cart,
            'customers' => $customers,
            'statuses' => $statuses
        ]);
    }
    

    public function update_order(Request $request, $id)
    {
        // Kiểm tra ID hợp lệ
        if (!$id) {
            return back()->withErrors(['error' => 'ID đơn hàng không hợp lệ.']);
        }
    
        // Xác thực dữ liệu đầu vào
        $validatedData = $request->validate([
            'orderId' => 'required|string|max:255',
            'orderStatusId' => 'required|integer',
            'orderName' => 'required|string|max:255',
            'note' => 'nullable|string',
            'orderTotal' => 'required|numeric',
            'products' => 'required|string', // Sản phẩm dưới dạng chuỗi JSON
        ]);
    
        // Giải mã dữ liệu sản phẩm từ chuỗi JSON
        $products = json_decode($validatedData['products'], true);
    
        // Kiểm tra nếu dữ liệu sản phẩm không hợp lệ
        if (json_last_error() !== JSON_ERROR_NONE) {
            return back()->withErrors(['error' => 'Dữ liệu sản phẩm không hợp lệ.']);
        }
    
        // Kiểm tra nếu mảng sản phẩm rỗng
        if (empty($products)) {
            return back()->withErrors(['error' => 'Danh sách sản phẩm không được để trống.']);
        }
    
        DB::beginTransaction();
    
        try {
            // Cập nhật thông tin đơn hàng
            $order = Order::where('orderId', $id)->firstOrFail();
            $order->orderStatusId = $validatedData['orderStatusId'];
            $order->orderName = $validatedData['orderName'];
            $order->note = $validatedData['note'];
            $order->totalOrderValue = $validatedData['orderTotal'];
            $order->updateAt = Carbon::now('Asia/Ho_Chi_Minh');
            $order->save();
    
            // Cập nhật chi tiết đơn hàng vào bảng 'order_details'
            foreach ($products as $product) {
                // Kiểm tra nếu các dữ liệu cần thiết tồn tại
                if (!isset($product['uniqueKey'], $product['quantity'])) {
                    return back()->withErrors(['error' => 'Dữ liệu sản phẩm không hợp lệ.']);
                }
    
                // Giải mã uniqueKey thành mảng và cắt các giá trị tương ứng
                $uniqueKey = $product['uniqueKey']; // Giả sử uniqueKey có dạng "1-1-3-2"
                $keyParts = explode('-', $uniqueKey); // Tách chuỗi thành mảng
    
                // Kiểm tra nếu mảng chứa đủ 4 phần tử
                if (count($keyParts) === 4) {
                    $productId = $keyParts[0];
                    $colorId = $keyParts[1];
                    $sizeId = $keyParts[2];
                    $materialId = $keyParts[3];
                } else {
                    // Nếu uniqueKey không hợp lệ
                    return back()->withErrors(['error' => 'uniqueKey không hợp lệ.']);
                }
    
                // Cập nhật chi tiết đơn hàng
                $orderDetail = OrderDetail::where('orderId', $id)
                                            ->where('productId', $productId)
                                            ->where('colorId', $colorId)
                                            ->where('sizeId', $sizeId)
                                            ->where('materialId', $materialId)
                                            ->first();
    
                if ($orderDetail) {
                    // Cập nhật chi tiết sản phẩm nếu đã tồn tại
                    $orderDetail->quantity = $product['quantity'];
                    $orderDetail->save();
                } else {
                    // Nếu chi tiết đơn hàng chưa tồn tại, tạo mới
                    OrderDetail::create([
                        'orderId' => $order->orderId,
                        'productId' => $productId,
                        'quantity' => $product['quantity'],
                        'colorId' => $colorId,
                        'sizeId' => $sizeId,
                        'materialId' => $materialId,
                    ]);
                }
            }
    
            DB::commit();
    
            return redirect()->route('admin.order.all')->with('success', 'Đơn hàng đã được cập nhật thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
    
            return back()->withErrors(['error' => 'Có lỗi xảy ra khi cập nhật đơn hàng: ' . $e->getMessage()]);
        }
    }
    
    
    public function delete_order($id)
    {
        DB::beginTransaction(); // Bắt đầu giao dịch
    
        try {
            // Tìm đơn hàng theo id (cột orderId trong bảng)
            $order = Order::where('orderId', $id)->firstOrFail(); // Dùng where để tìm đơn hàng theo cột orderId
    
            // Kiểm tra trạng thái hiện tại của đơn hàng
            // Giả sử rằng trạng thái 'Đã hủy' có orderStatusId = 4 (có thể thay đổi tùy theo hệ thống của bạn)
            $cancelledStatusId = 4; // ID của trạng thái 'Đã hủy'
    
            // Cập nhật trạng thái của đơn hàng sang 'Đã hủy'
            $order->orderStatusId = $cancelledStatusId;
            $order->save(); // Lưu thay đổi
    
            DB::commit(); // Commit giao dịch
    
            return redirect()->route('admin.order.all')->with('success', 'Đơn hàng đã được hủy thành công!');
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback giao dịch nếu có lỗi
    
            return back()->withErrors(['error' => 'Có lỗi xảy ra khi hủy đơn hàng: ' . $e->getMessage()]);
        }
    }
    



}



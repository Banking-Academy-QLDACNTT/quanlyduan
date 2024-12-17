@extends("layouts.admin")
@section("content")
<!-- Bootstrap DatePicker JavaScript -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<!-- Thêm CSS của iziToast -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/izitoast/dist/css/iziToast.min.css">

<!-- Thêm JS của iziToast -->
<script src="https://cdn.jsdelivr.net/npm/izitoast/dist/js/iziToast.min.js"></script>



<style>
/* General Styles */
.text-uppercase {
    text-transform: uppercase;a
    font-weight: bold;
}

.text-title-form {
    font-size: 20px;
    color: #333;
    text-align: center; /* This will center the text */
    padding: 8px;
}

.form-label {
    font-weight: bold;
    font-size: 13px;
    color: #333;
}

.form-control {
    font-size: 16px;
    border-radius: 5px;
    border: 1px solid #ddd;
    width: 100%;
    padding: 10px; /* Ensure padding matches form-select */
    height: 35.14px; /* Set height to match .form-select */
}

.form-select {
    font-size: 16px;
    padding: 10px;
    border-radius: 5px;
    height: 35.14px; /* Set height to match .form-control */
    border: 1px solid #ddd;
}

.form-group {
    margin-bottom: 20px;
}

.required::after {
    content: " *";
    color: red;
}

/* Modal */
.modal-content {
    padding: 20px;
    border-radius: 10px;
    background-color: #f7f7f7;
}

.modal-body {
    padding: 10px;
}

/* Table Styles */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

table th,
table td {
    padding: 12px;
    text-align: left;
    border: 1px solid #ddd;
}

table th {
    background-color: #f2f2f2;
    font-weight: bold;
    color: #333;
    height: 50px;  /* Chỉnh chiều cao của tất cả các tiêu đề */
}

table td {
    color: #555;
}

table tbody tr:hover {
    background-color: #f9f9f9;
}

table .action-btns {
    display: flex;
    justify-content: space-around;
}

/* Button Styles */
.btn {
    padding: 4px 10px;
    font-size: 13px;
    border-radius: 5px;
    cursor: pointer;
    justify-content: center; /* Center align the Save Order button */

}

.btn-success {
    background-color: #28a745;
    padding: 4px 10px;
    font-size: 13px;
    border: none;
    color: white;
}

.btn-success:hover {
    background-color: #218838;
}

.btn-info {
    background-color: #17a2b8;
    padding: 6px 16px;
    font-size: 15px;
    border: none;
    color: white;
}

.btn-info:hover {
    background-color: #138496;
}

/* Align buttons */
.form-group.col-lg-12 {
    display: flex;
    justify-content: center; /* Center align the Save Order button */
    margin-bottom: 20px;
    margin-top: 5px;
}

/* Style for 'Save Order' button */
.btn-info {
    padding: 10px 20px;
    font-size: 16px;
    border-radius: 5px;
    cursor: pointer;
    width: auto; /* Ensures the button does not stretch to full width */
}

/* Align 'Add Product' button to the right */
.add-product-btn {
    display: inline-block;
    padding: 5px 13px;
    font-size: 14px; /* Smaller font size */
    border-radius: 5px;
    cursor: pointer;
    margin-top: 10px;
    margin-left: auto; /* Push it to the right */
    background-color: #28a745; /* Same as the 'success' button color */
    color: white;
}

.add-product-btn:hover {
    background-color: #218838; /* Hover color */
}

/* Adjusting button container to allow proper alignment */
.form-group.col-lg-12.add-product-container {
    display: flex;
    justify-content: flex-end;
    margin-bottom: 20px;
}

/* Đặt label và ô nhập liệu trên cùng một dòng */
.form-group {
    display: flex;
    align-items: center; /* Căn chỉnh dọc để label và input cùng nằm trên một dòng */
    margin-bottom: 15px;
}

.form-label {
    width: 150px; /* Đặt chiều rộng cho label */
    margin-right: 10px; /* Khoảng cách giữa label và ô nhập liệu */
    font-size: 14px;
    font-weight: bold;
}

.form-control,
.form-select {
    flex: 1; /* Làm cho ô nhập liệu chiếm phần còn lại của dòng */
    padding: 8px;
    font-size: 12px;
    border-radius: 5px;
    border: 1px solid #ddd;
}
</style>

<div class="container py-2">
    <div class="mb-4">
        <div class="row place-userInfor">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.save.order') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="col-lg-12">
                    <h3 class="text-center mt-3" style="font-weight: 600; color:rgb(4, 70, 135); font-size: 20px; margin-bottom: 30px;">THÊM MỚI ĐƠN HÀNG</h3>
                    <div class="row">

                        <!-- Mã đơn hàng -->
                        <div class="form-group col-lg-6">
                            <label class="form-label mb-1 text-2 required">Mã đơn hàng</label>
                            <input type="text" class="form-control text-3 h-auto py-2" name="orderId" value="{{ $orderId }}" readonly>
                        </div>

                        <!-- Ngày đặt hàng -->
                        <div class="form-group col-lg-6">
                            <label class="form-label mb-1 text-2 required">Ngày đặt hàng</label>
                            <input type="date" class="form-control text-3 h-auto py-2" name="orderDate" value="{{ old('orderDate') }}" required>
                        </div>

                        <!-- Tên đơn đặt hàng -->
                        <div class="form-group col-lg-12">
                            <label class="form-label mb-1 text-2 required">Tên đơn hàng</label>
                            <input type="text" class="form-control text-3 h-auto py-2" name="orderName" value="" required>
                        </div>

                        <!-- Tên khách hàng -->
                        <div class="form-group col-lg-6">
                            <label class="form-label mb-1 text-2 required">Khách hàng</label>
                            @php
                                $customers = DB::table('customers')->get();
                            @endphp
                            <select class="form-select form-control h-auto py-2" name="customerId" required>
                                <option value="" disabled selected>Chọn khách hàng</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->customerId }}">{{ $customer->customerName }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Trạng thái đơn hàng -->
                        <div class="form-group col-lg-6">
                            <label class="form-label mb-1 text-2 required">Trạng thái đơn hàng</label>
                            @php
                                $statuses = DB::table('order_status')->get();
                            @endphp
                            <select class="form-select form-control h-auto py-2" name="orderStatusId" required disabled>
                                @foreach($statuses as $status)
                                    <option value="{{ $status->orderStatusId }}" 
                                        {{ $status->orderStatusId == 1 ? 'selected' : '' }} 
                                        {{ $status->orderStatusId != 1 ? 'disabled' : '' }}>
                                        {{ $status->orderStatusName }}
                                    </option>
                                @endforeach
                            </select>

                            <!-- Trường ẩn để truyền giá trị orderStatusId -->
                            <input type="hidden" name="orderStatusId" value="{{ $statuses->first()->orderStatusId }}">
                        </div>


                        <!-- Ghi chú -->
                        <div class="form-group col-lg-12">
                            <label class="form-label mb-1 text-2">Ghi chú</label>
                            <input type="text" class="form-control text-3 h-auto py-2" name="note" value="">
                        </div>
                        <div class="form-group col-lg-6">
                            <label for="order-total" class="form-label mb-1 text-2">Tổng giá trị đơn hàng:</label>
                            <input type="text" class="form-control" id="order-total" value="0 VND" disabled>
                        </div>
                    </div>
                </div>
                <div class="form-group col-lg-12">
                    <button type="button" class="btn btn-success add-product-btn" data-bs-toggle="modal" data-bs-target="#productModal">Thêm sản phẩm mới</button>
                </div>

                <!-- Chi tiết đơn hàng -->
                    <div id="products-list">
                        <h3 class="text-center mt-3" style="font-weight: 200; color:rgb(4, 70, 135); font-size: 20px;">CHI TIẾT ĐƠN HÀNG</h3>

                        <!-- Bảng hiển thị chi tiết đơn hàng -->
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Tên sản phẩm</th>
                                    <th>Màu sắc</th>
                                    <th>Kích cỡ</th>
                                    <th>Chất liệu</th>
                                    <th>Số lượng</th>
                                    <th>Giá</th>
                                    <th>Tổng</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody id="order-details">
                                <!-- Các dòng sản phẩm sẽ được thêm vào đây -->
                            </tbody>
                        </table>

                        <!-- Thông báo nếu chưa có sản phẩm -->
                        <div id="no-products-message" style="display: none; color: red;">Chưa có sản phẩm nào</div>

                        <!-- Các trường ẩn cho sản phẩm -->
                        <input type="hidden" name="products" id="products">
                    </div>

                    <div class="form-group col-lg-12">
                        <button type="submit" name="add_order" class="btn btn-info">Lưu đơn hàng</button>
                    </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal for adding product -->
<div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="productModalLabel">Thêm sản phẩm</h5>
                <!-- Dấu "X" để đóng modal -->
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="productForm">
                    <!-- Chọn sản phẩm -->
                    <div class="form-group">
                        <label for="modal-product" class="form-label">Sản phẩm</label>
                        <select class="form-select form-control" id="modal-product" required>
                            <option value="" disabled selected>Chọn sản phẩm</option>
                            @foreach($productAttributes as $productId => $attributes)
                                <option value="{{ $productId }}">{{ $attributes['name'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Giá sản phẩm -->
                    <div class="form-group">
                        <label for="modal-price" class="form-label">Giá sản phẩm</label>
                        <input type="text" class="form-control" id="modal-price" disabled>
                        <input type="hidden" id="modal-price-hidden">
                    </div>

                    <!-- Màu sắc -->
                    <div class="form-group">
                        <label for="modal-color" class="form-label">Màu sắc</label>
                        <select class="form-select form-control" id="modal-color" required>
                            <option value="" disabled selected>Chọn màu sắc</option>
                        </select>
                    </div>

                    <!-- Kích cỡ -->
                    <div class="form-group">
                        <label for="modal-size" class="form-label">Kích cỡ</label>
                        <select class="form-select form-control" id="modal-size" required>
                            <option value="" disabled selected>Chọn kích cỡ</option>
                        </select>
                    </div>

                    <!-- Chất liệu-->
                    <div class="form-group">
                        <label for="modal-material" class="form-label">Chất liệu</label>
                        <select class="form-select form-control" id="modal-material" required>
                            <option value="" disabled selected>Chọn chất liệu</option>
                        </select>
                    </div>

                    <!-- Số lượng -->
                    <div class="form-group">
                        <label for="modal-quantity" class="form-label">Số lượng</label>
                        <input type="number" class="form-control" id="modal-quantity" required>
                    </div>
                    <div class="d-flex justify-content-center">
                        <button type="button" class="btn btn-primary" id="save-product-btn">Lưu</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Sửa Sản Phẩm -->
<div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProductModalLabel">Sửa sản phẩm</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editProductForm">
                    <!-- Tên sản phẩm (không cho phép sửa) -->
                    <div class="form-group">
                        <label for="modal-edit-product" class="form-label">Sản phẩm</label>
                        <input type="text" class="form-control" id="modal-edit-product" readonly> <!-- Hiển thị tên sản phẩm -->
                        <input type="hidden" id="modal-edit-product-id"> <!-- Lưu ID sản phẩm -->
                    </div>

                    <!-- Giá sản phẩm -->
                    <div class="form-group">
                        <label for="modal-edit-price" class="form-label">Giá sản phẩm</label>
                        <input type="text" class="form-control" id="modal-edit-price" disabled>
                        <input type="hidden" id="modal-edit-price">
                    </div>

                    <!-- Màu sắc -->
                    <div class="form-group">
                        <label for="modal-edit-color" class="form-label">Màu sắc</label>
                        <select class="form-select form-control" id="modal-edit-color" required>
                            <option value="" disabled selected>Chọn màu sắc</option>
                            <!-- Các màu sắc sẽ được thêm vào đây từ product -->
                        </select>
                    </div>

                    <!-- Kích cỡ -->
                    <div class="form-group">
                        <label for="modal-edit-size" class="form-label">Kích cỡ</label>
                        <select class="form-select form-control" id="modal-edit-size" required>
                            <option value="" disabled selected>Chọn kích cỡ</option>
                            <!-- Các kích cỡ sẽ được thêm vào đây từ product -->
                        </select>
                    </div>

                    <!-- Chất liệu -->
                    <div class="form-group">
                        <label for="modal-edit-material" class="form-label">Chất liệu</label>
                        <select class="form-select form-control" id="modal-edit-material" required>
                            <option value="" disabled selected>Chọn chất liệu</option>
                            <!-- Các chất liệu sẽ được thêm vào đây từ product -->
                        </select>
                    </div>

                    <!-- Số lượng -->
                    <div class="form-group">
                        <label for="modal-edit-quantity" class="form-label">Số lượng</label>
                        <input type="number" class="form-control" id="modal-edit-quantity" required>
                    </div>

                    <button type="button" class="btn btn-primary" id="update-product-btn">Lưu</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Kiểm tra và hiển thị thông báo nếu không có sản phẩm
    function checkNoProductsMessage() {
        var productRows = $('#order-details tr');
        var noProductsMessage = $('#no-products-message');

        // Nếu không có sản phẩm trong bảng, hiển thị thông báo
        if (productRows.length === 0) {
            noProductsMessage.show();
        } else {
            noProductsMessage.hide();
        }
    }

    // Hàm ví dụ để tải chi tiết đơn hàng
    function loadOrderDetails(products) {
        const orderDetails = document.getElementById('order-details');
        const noProductsMessage = document.getElementById('no-products-message');
        
        // Xóa các dòng trước đó
        orderDetails.innerHTML = '';

        if (products.length === 0) {
            // Hiển thị thông báo "chưa có sản phẩm"
            noProductsMessage.style.display = 'block';
        } else {
            // Ẩn thông báo "chưa có sản phẩm"
            noProductsMessage.style.display = 'none';

            // Thêm các dòng sản phẩm
            products.forEach(product => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${product.name}</td>
                    <td>${product.color}</td>
                    <td>${product.size}</td>
                    <td>${product.material}</td>
                    <td>${product.quantity}</td>
                    <td>${product.price}</td>
                    <td>${product.total}</td>
                    <td><button class="btn btn-danger">Xóa</button></td>
                `;
                orderDetails.appendChild(row);
            });
        }
    }

    // Dữ liệu sản phẩm ví dụ
    const products = []; // Mảng rỗng có nghĩa là chưa có sản phẩm

    // Gọi hàm để tải chi tiết đơn hàng
    loadOrderDetails(products);

    // Đảm bảo rằng modal hoạt động bình thường khi được gọi
    $('#productModal').on('shown.bs.modal', function () {
        // Reset form mỗi khi modal được mở
        $('#productForm')[0].reset();
        $('#modal-color').html('<option value="" disabled selected>Chọn màu sắc</option>');
        $('#modal-size').html('<option value="" disabled selected>Chọn kích cỡ</option>');
        $('#modal-material').html('<option value="" disabled selected>Chọn chất liệu</option>');
    });

    // Cập nhật thuộc tính sản phẩm khi chọn sản phẩm trong modal
    $('#modal-product').on('change', function() {
        var productId = $(this).val();
        var productAttributes = @json($productAttributes);
        
        var colors = productAttributes[productId]?.colors || [];
        var sizes = productAttributes[productId]?.sizes || [];
        var materials = productAttributes[productId]?.materials || [];

        $('#modal-color').html('<option value="" disabled selected>Chọn màu sắc</option>');
        colors.forEach(function(color) {
            $('#modal-color').append(new Option(color.name, color.id));
        });

        $('#modal-size').html('<option value="" disabled selected>Chọn kích cỡ</option>');
        sizes.forEach(function(size) {
            $('#modal-size').append(new Option(size.name, size.id));
        });

        $('#modal-material').html('<option value="" disabled selected>Chọn chất liệu</option>');
        materials.forEach(function(material) {
            $('#modal-material').append(new Option(material.name, material.id));
        });
    });

    // Cập nhật giá khi chọn sản phẩm
    $('#modal-product').on('change', function() {
        var productId = $(this).val();
        var productAttributes = @json($productAttributes);

        // Lấy giá sản phẩm
        var price = productAttributes[productId]?.price || 0;
        $('#modal-price').val(price.toLocaleString('vi-VN', { style: 'currency', currency: 'VND' }));
        $('#modal-price-hidden').val(price);

        // Cập nhật màu sắc, kích cỡ và chất liệu
        var colors = productAttributes[productId]?.colors || [];
        var sizes = productAttributes[productId]?.sizes || [];
        var materials = productAttributes[productId]?.materials || [];
        $('#modal-color').html('<option value="" disabled selected>Chọn màu sắc</option>');
        colors.forEach(function(color) {
            $('#modal-color').append(new Option(color.name, color.id));
        });
        $('#modal-size').html('<option value="" disabled selected>Chọn kích cỡ</option>');
        sizes.forEach(function(size) {
            $('#modal-size').append(new Option(size.name, size.id));
        });
        $('#modal-material').html('<option value="" disabled selected>Chọn chất liệu</option>');
        materials.forEach(function(material) {
            $('#modal-material').append(new Option(material.name, material.id));
        });
    });

    // Lưu sản phẩm vào bảng chi tiết đơn hàng
    $('#save-product-btn').on('click', function() {
        var productId = $('#modal-product').val();
        var productName = $('#modal-product option:selected').text();
        var color = $('#modal-color').val();
        var colorName = $('#modal-color option:selected').text();
        var size = $('#modal-size').val();
        var sizeName = $('#modal-size option:selected').text();
        var material = $('#modal-material').val();
        var materialName = $('#modal-material option:selected').text();
        var quantity = parseInt($('#modal-quantity').val());
        var price = parseFloat($('#modal-price-hidden').val());
        var total = quantity * price;

        if (!productId || !color || !size || !material || !quantity) {
            alert('Vui lòng chọn đầy đủ thông tin sản phẩm!');
            return;
        }

        // Tạo định danh duy nhất
        var uniqueKey = `${productId}-${color}-${size}-${material}`;

        // Lấy mảng sản phẩm hiện tại
        var products = JSON.parse($('#products').val() || '[]');

        // Kiểm tra nếu sản phẩm đã tồn tại
        var existingProduct = products.find(product => product.uniqueKey === uniqueKey);
        if (existingProduct) {
            // Cộng dồn số lượng và cập nhật tổng giá
            existingProduct.quantity += quantity;
            existingProduct.total = existingProduct.quantity * price;

            // Cập nhật dòng trong bảng HTML
            var row = $(`tr[data-unique-key="${uniqueKey}"]`);
            row.find('td:nth-child(5)').text(existingProduct.quantity); // Cột số lượng
            row.find('td:nth-child(7)').text(existingProduct.total.toLocaleString('vi-VN', { style: 'currency', currency: 'VND' })); // Cột tổng giá
        } else {
            // Thêm sản phẩm mới
            products.push({ uniqueKey, productId, color, size, material, quantity, price, total });

            // Thêm dòng vào bảng chi tiết đơn hàng
            var productRow = `
            <tr data-unique-key="${uniqueKey}">
                <td>${productName}</td>
                <td>${colorName}</td>
                <td>${sizeName}</td>
                <td>${materialName}</td>
                <td>${quantity}</td>
                <td>${price.toLocaleString('vi-VN', { style: 'currency', currency: 'VND' })}</td>
                <td>${total.toLocaleString('vi-VN', { style: 'currency', currency: 'VND' })}</td>
                <td>
                    <button type="button" class="btn btn-warning edit-product-btn" 
                            data-unique-key="${uniqueKey}" 
                            data-product='${JSON.stringify({ productId, color, size, material, quantity, price })}'>
                        Sửa
                    </button>
                    <button type="button" class="btn btn-danger remove-product-btn" data-unique-key="${uniqueKey}">Xóa</button>
                </td>
            </tr>
        `;

            $('#order-details').append(productRow);
        }

        // Cập nhật input ẩn
        $('#products').val(JSON.stringify(products));

        // Reset modal fields after saving the product
        $('#modal-product').val('');
        $('#modal-color').val('');
        $('#modal-size').val('');
        $('#modal-material').val('');
        $('#modal-quantity').val('');
        $('#modal-price-hidden').val('');
        $('#modal-price').val(''); // Nếu bạn có element giá ở modal

        // Kiểm tra và ẩn thông báo "Chưa có sản phẩm nào" nếu có sản phẩm
        checkNoProductsMessage();

        // Cập nhật tổng đơn hàng
        updateTotalOrder();

        // Đóng modal
        $('#productModal').modal('hide');
    }); 

    // Cập nhật tổng đơn hàng
    function updateTotalOrder() {
        var products = JSON.parse($('#products').val() || '[]');
        var totalOrder = products.reduce((sum, product) => sum + product.total, 0);

        // Cập nhật giá trị tổng đơn hàng vào input
        $('#order-total').val(totalOrder.toLocaleString('vi-VN', { style: 'currency', currency: 'VND' }));
    }

    $(document).on('click', '.edit-product-btn', function() {
        var uniqueKey = $(this).data('unique-key'); // Lấy uniqueKey từ data attribute
        var products = JSON.parse($('#products').val() || '[]');
        var product = products.find(p => p.uniqueKey === uniqueKey);
        var sl = product.quantity;
        var cl = product.color;
        var sz = product.size;
        var mt = product.material;
        var id = product.productId;

    if (product) {

        // Mở modal sửa sản phẩm
        $('#editProductModal').modal('show'); 

        // Gọi hàm để tải thông tin sản phẩm vào modal
        loadProductDetails(uniqueKey, product.productId, sl, mt, sz, cl, id); // Truyền productId vào để tải thông tin
        } else {
            console.log("Không tìm thấy sản phẩm với uniqueKey " + uniqueKey);
        }
    });

    function loadProductDetails(uniqueKey, productId, sl, mt, sz, cl,id) {
        var productAttributes = @json($productAttributes); // Dữ liệu sản phẩm từ Laravel
        
        // Lọc thông tin sản phẩm dựa trên productId
        var product = productAttributes[productId]; // Truy xuất sản phẩm theo productId

        if (product) {
            // Cập nhật tên sản phẩm vào modal (không cho phép sửa)
            $('#modal-edit-product').val(product.name);  // Hiển thị tên sản phẩm, nhưng không thể chỉnh sửa
            $('#modal-edit-product-id').val(id);

            // Cập nhật giá vào modal, đảm bảo đúng định dạng tiền tệ
            $('#modal-edit-price').val(product.price.toLocaleString('vi-VN', { style: 'currency', currency: 'VND' }));
            $('#modal-edit-price').val(product.price); // Cập nhật giá vào hidden input

            // Cập nhật số lượng vào input (lấy từ product object)
            $('#modal-edit-quantity').val(sl); // Điền số lượng vào input

            // Cập nhật các lựa chọn màu sắc, kích cỡ và chất liệu
            // Màu sắc
            $('#modal-edit-color').html('<option value="" disabled selected>Chọn màu sắc</option>');
            product.colors.forEach(function(color) {
                $('#modal-edit-color').append(new Option(color.name, color.id)); // Thêm các lựa chọn màu sắc
            });

            // Kích cỡ
            $('#modal-edit-size').html('<option value="" disabled selected>Chọn kích cỡ</option>');
            product.sizes.forEach(function(size) {
                $('#modal-edit-size').append(new Option(size.name, size.id)); // Thêm các lựa chọn kích cỡ
            });

            // Chất liệu
            $('#modal-edit-material').html('<option value="" disabled selected>Chọn chất liệu</option>');
            product.materials.forEach(function(material) {
                $('#modal-edit-material').append(new Option(material.name, material.id)); // Thêm các lựa chọn chất liệu
            });

            // Cập nhật giá trị đã chọn cho các trường (màu sắc, kích cỡ, chất liệu)
            $('#modal-edit-color').val(cl); // Chọn màu sắc đã chọn
            $('#modal-edit-size').val(sz); // Chọn kích cỡ đã chọn
            $('#modal-edit-material').val(mt); // Chọn chất liệu đã chọn

            $('#update-product-btn').data('unique-key', uniqueKey); // Gán uniqueKey vào data attribute của nút
        } else {
            console.log("Không tìm thấy sản phẩm.");
        }
    }

    $('#update-product-btn').on('click', function () {
        // Lấy uniqueKey cũ từ nút
        var oldUniqueKey = $(this).data('unique-key'); 

        // Lấy thông tin mới từ modal
        var productId = $('#modal-edit-product-id').val();
        var color = $('#modal-edit-color').val();
        var size = $('#modal-edit-size').val();
        var material = $('#modal-edit-material').val();
        var quantity = parseInt($('#modal-edit-quantity').val());
        var price = parseFloat($('#modal-edit-price').val());
        var total = quantity * price;

        if (!productId || !color || !size || !material || isNaN(quantity) || isNaN(price)) {
            alert('Vui lòng nhập đầy đủ thông tin!');
            return;
        }

        // Tạo uniqueKey mới
        var newUniqueKey = `${productId}-${color}-${size}-${material}`;

        // Lấy danh sách sản phẩm từ input ẩn
        var products = JSON.parse($('#products').val() || '[]');

        // Kiểm tra nếu uniqueKey mới đã tồn tại trong danh sách
        var existingProductIndex = products.findIndex(p => p.uniqueKey === newUniqueKey);

        if (existingProductIndex !== -1 && oldUniqueKey !== newUniqueKey) {
            // Nếu uniqueKey mới trùng và khác uniqueKey cũ, cộng dồn số lượng
            products[existingProductIndex].quantity += quantity;
            products[existingProductIndex].total = products[existingProductIndex].quantity * products[existingProductIndex].price;

            // Cập nhật dòng trong bảng HTML của sản phẩm đã tồn tại
            var existingRow = $(`tr[data-unique-key="${newUniqueKey}"]`);
            existingRow.find('td:nth-child(5)').text(products[existingProductIndex].quantity); // Số lượng
            existingRow.find('td:nth-child(7)').text(
                products[existingProductIndex].total.toLocaleString('vi-VN', { style: 'currency', currency: 'VND' })
            ); // Tổng tiền

            // Xóa dòng cũ khỏi bảng HTML và danh sách
            products = products.filter(p => p.uniqueKey !== oldUniqueKey); // Xóa sản phẩm cũ khỏi danh sách
            $(`tr[data-unique-key="${oldUniqueKey}"]`).remove(); // Xóa dòng cũ trong bảng HTML
        } else {
            // Nếu uniqueKey mới không trùng hoặc trùng nhưng là chính sản phẩm cũ
            var productIndex = products.findIndex(p => p.uniqueKey === oldUniqueKey);
            if (productIndex !== -1) {
                // Cập nhật thông tin sản phẩm
                products[productIndex] = {
                    uniqueKey: newUniqueKey, // Cập nhật uniqueKey mới
                    productId,
                    color,
                    size,
                    material,
                    quantity,
                    price,
                    total
                };

                // Cập nhật dòng trong bảng HTML
                var row = $(`tr[data-unique-key="${oldUniqueKey}"]`);
                row.attr('data-unique-key', newUniqueKey); // Cập nhật `data-unique-key` trên hàng
                row.find('td:nth-child(2)').text($('#modal-edit-color option:selected').text());
                row.find('td:nth-child(3)').text($('#modal-edit-size option:selected').text());
                row.find('td:nth-child(4)').text($('#modal-edit-material option:selected').text());
                row.find('td:nth-child(5)').text(quantity); // Số lượng
                row.find('td:nth-child(6)').text(price.toLocaleString('vi-VN', { style: 'currency', currency: 'VND' }));
                row.find('td:nth-child(7)').text(total.toLocaleString('vi-VN', { style: 'currency', currency: 'VND' }));
            } else {
                alert('Không tìm thấy sản phẩm để cập nhật!');
                return;
            }
        }

        // Cập nhật lại input ẩn
        $('#products').val(JSON.stringify(products));

        // Cập nhật tổng đơn hàng
        updateTotalOrder();

        // Đóng modal
        $('#editProductModal').modal('hide');
    });
 
    $(document).on('click', '.remove-product-btn', function() {
    var uniqueKey = $(this).data('unique-key'); // Lấy uniqueKey từ data attribute
    var row = $(this).closest('tr'); // Lấy dòng sản phẩm

    // Hiển thị iziToast với thông báo xác nhận
    iziToast.question({
        title: 'Bạn có chắc chắn?',
        message: 'Sản phẩm sẽ bị xóa khỏi đơn hàng!',
        position: 'center', // Hiển thị ở giữa màn hình
        closeOnClick: true,
        buttons: [
            ['<button>Hủy</button>', function(instance, toast) {
                instance.hide({transitionOut: 'fadeOut'}, toast, 'button');
            }, true],
            ['<button>Xóa</button>', function(instance, toast) {
                // Lấy mảng sản phẩm hiện tại từ input ẩn
                var products = JSON.parse($('#products').val() || '[]');

                // Lọc bỏ sản phẩm với uniqueKey tương ứng
                products = products.filter(product => product.uniqueKey !== uniqueKey);

                // Cập nhật lại danh sách sản phẩm vào input ẩn
                $('#products').val(JSON.stringify(products));

                // Xóa dòng sản phẩm khỏi bảng
                row.remove();

                // Cập nhật tổng đơn hàng
                updateTotalOrder();

                // Kiểm tra và ẩn thông báo "Chưa có sản phẩm nào" nếu có sản phẩm
                checkNoProductsMessage();

                // Thông báo xóa thành công
                iziToast.success({
                    title: 'Đã xóa!',
                    message: 'Sản phẩm đã được xóa.',
                    position: 'center',
                });

                instance.hide({transitionOut: 'fadeOut'}, toast, 'button');
            }]
        ]
    });
});


</script>
@endsection

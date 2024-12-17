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

            <form action="{{ route('admin.update.order', $order->orderId ) }}" method="post" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="col-lg-12">
                <h3 class="text-center mt-3" style="font-weight: 600; color:rgb(4, 70, 135); font-size: 20px; margin-bottom: 30px;">SỬA ĐƠN HÀNG</h3>
                    <div class="row">

                        <!-- Mã đơn hàng -->
                        <div class="form-group col-lg-6">
                            <label class="form-label mb-1 text-2 required">Mã đơn hàng</label>
                            <input type="text" class="form-control text-3 h-auto py-2" name="orderId" value="{{ $order->orderId }}" readonly>
                        </div>

                        <!-- Ngày đặt hàng -->
                        <div class="form-group col-lg-6">
                            <label class="form-label mb-1 text-2 required">Ngày đặt hàng</label>
                            <input type="date" class="form-control text-3 h-auto py-2" name="orderDate" value="{{ old('orderDate', $order->orderDate) }}" required disabled>
                        </div>

                        <!-- Tên đơn đặt hàng -->
                        <div class="form-group col-lg-12">
                            <label class="form-label mb-1 text-2 required">Tên đơn hàng</label>
                            <input type="text" class="form-control text-3 h-auto py-2" name="orderName" value="{{ old('orderName', $order->orderName) }}" required>
                        </div>

                        <!-- Tên khách hàng -->
                        <div class="form-group col-lg-6">
                            <label class="form-label mb-1 text-2 required">Khách hàng</label>
                            <select class="form-select form-control h-auto py-2" name="customerId" required disabled>
                                <option value="" disabled selected>Chọn khách hàng</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->customerId }}" 
                                        {{ $customer->customerId == $order->customerId ? 'selected' : '' }}>
                                        {{ $customer->customerName }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Trạng thái đơn hàng -->
                        <div class="form-group col-lg-6">
                            <label class="form-label mb-1 text-2 required">Trạng thái đơn hàng</label>
                            <select class="form-select form-control h-auto py-2" name="orderStatusId" required>
                                @foreach($statuses as $status)
                                    <option value="{{ $status->orderStatusId }}" 
                                        {{ $status->orderStatusId == $order->orderStatusId ? 'selected' : '' }}>
                                        {{ $status->orderStatusName }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Ghi chú -->
                        <div class="form-group col-lg-12">
                            <label class="form-label mb-1 text-2">Ghi chú</label>
                            <input type="text" class="form-control text-3 h-auto py-2" name="note" value="{{ old('note', $order->note) }}">
                        </div>

                        <div class="form-group col-lg-6">
                            <label for="order-total" class="form-label mb-1 text-2 required">Tổng tiền</label>
                            <input type="text" class="form-control" name="orderTotal" id="order-total" value="{{ old('totalOrderValue', $order->totalOrderValue) }}" required readonly>
                        </div>
                    </div>
                </div>

                <div class="form-group col-lg-12">
                    <button type="button" class="btn btn-success add-product-btn" data-bs-toggle="modal" data-bs-target="#productModal">Thêm sản phẩm mới</button>
                </div>

                <!-- Đơn hàng -->
                 <!-- Chi tiết đơn hàng -->
                <div id="products-list">
                <h3 class="text-center mt-3" style="font-weight: 200; color:rgb(4, 70, 135); font-size: 20px;">CHI TIẾT ĐƠN HÀNG</h3>
                    <div class="form-group col-lg-12">
                        <table class="table table-striped table-bordered" id="order-items">
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
                                @foreach ($orderDetails as $item)
                                    <tr data-unique-key="{{ $item->productId }}-{{ $item->colorId }}-{{ $item->sizeId }}-{{ $item->materialId }}">
                                        <td><span>{{ $item->productName }}</span></td>
                                        <td><span>{{ $item->colorName ?? 'N/A' }}</span></td>
                                        <td><span>{{ $item->sizeName ?? 'N/A' }}</span></td>
                                        <td><span>{{ $item->materialName ?? 'N/A' }}</span></td>
                                        <td><span>{{ $item->quantity }}</span></td>
                                        <td><span>{{ number_format($item->price, 0, ',', '.') }}</span></td>
                                        <td><span>{{ number_format($item->totalPrice, 0, ',', '.') }}</span></td>
                                        <td>
                                            <button type="button" class="btn btn-warning edit-product-btn" 
                                                    data-unique-key="{{ $item->productId }}-{{ $item->colorId }}-{{ $item->sizeId }}-{{ $item->materialId }}"
                                                    data-product="{{ json_encode($item) }}">Sửa</button>
                                            <button type="button" class="btn btn-danger remove-product-btn" 
                                                    data-unique-key="{{ $item->productId }}-{{ $item->colorId }}-{{ $item->sizeId }}-{{ $item->materialId }}">Xóa</button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <!-- Hidden input để lưu thông tin các sản phẩm -->
                            <input type="hidden" name="products" id="products" value="{{ json_encode($orderDetails) }}">
                            <div id="json-output"></div>
                        </table>
                    </div>
                </div>
                <!-- Save Button -->
                <div class="form-group col-lg-12">
                    <button class="btn btn-info" type="submit">Cập nhật đơn hàng</button>
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

                    <button type="button" class="btn btn-primary" id="save-product-btn">Lưu</button>
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

    // Lấy giá trị từ input ẩn
//var jsonData = document.getElementById('products').value;

// Chuyển đổi chuỗi JSON thành đối tượng JavaScript
//var products = JSON.parse(jsonData);

// Hiển thị JSON dưới dạng chuỗi trong phần tử div
//document.getElementById('json-output').textContent = JSON.stringify(products, null, 10);

/*document.addEventListener('DOMContentLoaded', function () {
    const orderTotalInput = document.getElementById('order-total');
    
    // Hàm định dạng tiền
    function formatCurrency(amount) {
        return amount.toLocaleString('vi-VN', { style: 'currency', currency: 'VND' });
    }

    // Định dạng tổng tiền khi trang được tải
    if (orderTotalInput) {
        let value = parseFloat(orderTotalInput.value.replace(/[^0-9.-]+/g, "")); // Loại bỏ bất kỳ ký tự không phải số
        if (!isNaN(value)) {
            orderTotalInput.value = formatCurrency(value);
        }
    }
});*/

document.addEventListener('DOMContentLoaded', function () {
    const productsInput = document.getElementById('products');
    const orderDetailsTable = document.getElementById('order-details');

    // Hàm cập nhật JSON vào hidden input
    function updateProductsJSON() {
        const rows = orderDetailsTable.querySelectorAll('tr');
        const products = Array.from(rows).map(row => {
            return {
                productId: row.getAttribute('data-unique-key').split('-')[0],
                color: row.getAttribute('data-unique-key').split('-')[1],
                size: row.getAttribute('data-unique-key').split('-')[2],
                material: row.getAttribute('data-unique-key').split('-')[3],
                quantity: row.querySelector('.quantity').textContent.trim(),
                price: row.querySelector('.price').textContent.trim(),
                total: row.querySelector('.total').textContent.trim()
            };
        });
        productsInput.value = JSON.stringify(products);
    }

    // Lắng nghe các sự kiện thêm, sửa, xóa
    document.querySelectorAll('.add-product-btn, .edit-product-btn, .remove-product-btn').forEach(button => {
        button.addEventListener('click', function () {
            updateProductsJSON();
        });
    });
});



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
    var totalPrice = quantity * price;

    console.log('Quantity:', quantity);  // Kiểm tra giá trị quantity
    console.log('Price:', price);  // Kiểm tra giá trị price
    console.log('Total:', totalPrice);  // Kiểm tra giá trị tổng tiền

    if (!productId || !color || !size || !material || !quantity) {
        alert('Vui lòng chọn đầy đủ thông tin sản phẩm!');
        return;
    }

    // Tạo định danh duy nhất
    var uniqueKey = `${productId}-${color}-${size}-${material}`;

    // Lấy mảng sản phẩm hiện tại từ bảng
    var products = [];
    $('#order-details tr').each(function() {
        var row = $(this);
        var rowUniqueKey = row.data('unique-key');
        var rowData = {
            uniqueKey: rowUniqueKey,
            productName: row.find('td:nth-child(1) span').text(),
            colorName: row.find('td:nth-child(2) span').text(),
            sizeName: row.find('td:nth-child(3) span').text(),
            materialName: row.find('td:nth-child(4) span').text(),
            quantity: parseInt(row.find('td:nth-child(5) span').text()),
            price: parseFloat(row.find('td:nth-child(6) span').text().replace(/\./g, '').replace(',', '.')),
            totalPrice: parseFloat(row.find('td:nth-child(7) span').text().replace(/\./g, '').replace(',', '.'))
        };
        products.push(rowData);
    });

    console.log('Current products:', products);

    // Kiểm tra xem sản phẩm đã có chưa, nếu có thì cộng thêm số lượng và cập nhật tổng giá
    var existingProduct = products.find(product => product.uniqueKey === uniqueKey);
    if (existingProduct) {
        existingProduct.quantity += quantity;
        existingProduct.totalPrice = existingProduct.quantity * price;

        // Cập nhật dòng trong bảng HTML
        var row = $(`tr[data-unique-key="${uniqueKey}"]`);
        row.find('td:nth-child(5) span').text(existingProduct.quantity); // Cột số lượng
        row.find('td:nth-child(7) span').text(existingProduct.totalPrice.toLocaleString('vi-VN', { style: 'currency', currency: 'VND' })); // Cột tổng giá
    } else {
        // Thêm sản phẩm mới vào mảng
        products.push({ uniqueKey, productName, colorName, sizeName, materialName, quantity, price, totalPrice });

        // Thêm dòng vào bảng chi tiết đơn hàng
        var productRow = `
            <tr data-unique-key="${uniqueKey}">
                <td><span>${productName}</span></td>
                <td><span>${colorName}</span></td>
                <td><span>${sizeName}</span></td>
                <td><span>${materialName}</span></td>
                <td><span>${quantity}</span></td>
                <td><span>${price.toLocaleString('vi-VN')}</span></td>
                <td><span>${totalPrice.toLocaleString('vi-VN')}</span></td>
                <td>
                    <button type="button" class="btn btn-warning edit-product-btn" data-unique-key="${uniqueKey}" data-product='${JSON.stringify({ productId, color, size, material, quantity, price })}'>Sửa</button>
                    <button type="button" class="btn btn-danger remove-product-btn" data-unique-key="${uniqueKey}">Xóa</button>
                </td>
            </tr>
        `;
        $('#order-details').append(productRow);
    }

    // Cập nhật giá trị JSON vào input ẩn sau khi thay đổi
    $('#products').val(JSON.stringify(products));

    // Cập nhật tổng đơn hàng
    updateTotalOrder(); // Gọi lại hàm này để tính lại tổng tiền

    // Reset modal fields after saving the product
    $('#modal-product').val('');
    $('#modal-color').val('');
    $('#modal-size').val('');
    $('#modal-material').val('');
    $('#modal-quantity').val('');
    $('#modal-price-hidden').val('');
    $('#modal-price').val(''); // Nếu bạn có element giá ở modal

    // Đóng modal
    $('#productModal').modal('hide');
});

// Hàm tính tổng đơn hàng
function updateTotalOrder() {
    // Lấy dữ liệu từ input ẩn
    var productsVal = $('#products').val();
    console.log('Products value in hidden input:', productsVal);

    // Nếu không có giá trị, trả về
    if (!productsVal) {
        alert('Dữ liệu không hợp lệ');
        return;
    }

    // Chuyển dữ liệu JSON thành đối tượng JavaScript
    var products = JSON.parse(productsVal);
    console.log('Parsed products:', products);

    // Tính tổng tiền của đơn hàng
    var totalOrder = products.reduce(function(sum, product) {
        return sum + (product.totalPrice || 0);  // Đảm bảo totalPrice tồn tại
    }, 0);

    // Kiểm tra giá trị tổng đơn hàng trong console
    console.log('Total Order:', totalOrder);

    // Cập nhật giá trị tổng đơn hàng vào input
    $('#order-total').val(totalOrder);
}

$(document).on('click', '.edit-product-btn', function() {
    // Lấy uniqueKey từ data attribute của nút chỉnh sửa
    var uniqueKey = $(this).data('unique-key');
    
    // Lấy mảng sản phẩm hiện tại từ bảng
    var products = [];
    $('#order-details tr').each(function() {
        var row = $(this);
        var rowUniqueKey = row.data('unique-key');
        // Tách productId từ uniqueKey
        var rowProductId = rowUniqueKey ? rowUniqueKey.split('-')[0] : null;
        var rowColorId = rowUniqueKey ? rowUniqueKey.split('-')[1] : null;
        var rowSizeId = rowUniqueKey ? rowUniqueKey.split('-')[2] : null;
        var rowMaterialId = rowUniqueKey ? rowUniqueKey.split('-')[3] : null;
        var rowData = {
            uniqueKey: rowUniqueKey,
            productId: rowProductId, // Thêm productId từ uniqueKey
            colorId: rowColorId, // Thêm productId từ uniqueKey
            sizeId: rowSizeId, // Thêm productId từ uniqueKey
            materialId: rowMaterialId, // Thêm productId từ uniqueKey

            productName: row.find('td:nth-child(1) span').text(),
            colorName: row.find('td:nth-child(2) span').text(),
            sizeName: row.find('td:nth-child(3) span').text(),
            materialName: row.find('td:nth-child(4) span').text(),
            quantity: parseInt(row.find('td:nth-child(5) span').text()),
            price: parseFloat(row.find('td:nth-child(6) span').text().replace(/\./g, '').replace(',', '.')),
            totalPrice: parseFloat(row.find('td:nth-child(7) span').text().replace(/\./g, '').replace(',', '.'))
        };
        products.push(rowData);
    });

    // Tìm sản phẩm cần sửa trong mảng products
    var product = products.find(p => p.uniqueKey === uniqueKey);
    var sl = product.quantity;
    var cl = product.colorId;
    var sz = product.sizeId;
    var mt = product.materialId;
    var id = product.productId;

    if (product) {
        // Mở modal sửa sản phẩm
        $('#editProductModal').modal('show'); 

        console.log(product)

        // Gọi hàm để tải thông tin sản phẩm vào modal
        loadProductDetails(uniqueKey, product, sl, mt, sz, cl, id);
    } else {
        console.log("Không tìm thấy sản phẩm với uniqueKey " + uniqueKey);
        alert("Sản phẩm không tồn tại trong đơn hàng.");
    }
});

function loadProductDetails(uniqueKey, product, sl, mt, sz, cl, id) {
        var productAttributes = @json($productAttributes); // Dữ liệu sản phẩm từ Laravel

        console.log(id, uniqueKey)
        
        // Lọc thông tin sản phẩm dựa trên productId
        var product = productAttributes[product.productId]; // Truy xuất sản phẩm theo productId

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
            $('#modal-edit-color').html('<option value="" disabled selected>Chọn màu sắc</option>');
            product.colors.forEach(function(color) {
            $('#modal-edit-color').append(new Option(color.name, color.id));
            });
            $('#modal-edit-color').val(cl); // Gán màu đã chọn

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

            // Gán các giá trị đã chọn vào modal
            $('#modal-edit-size').val(sz); 
            $('#modal-edit-material').val(mt); 

            $('#update-product-btn').data('unique-key', uniqueKey); // Gán uniqueKey vào data attribute của nút
        } else {
            console.log("Không tìm thấy sản phẩm.");
        }
    }

    $('#update-product-btn').on('click', function () {
    // Lấy thông tin từ modal
    var productId = $('#modal-edit-product-id').val();
    var productName = $('#modal-edit-product option:selected').text();
    var color = $('#modal-edit-color').val();
    var colorName = $('#modal-edit-color option:selected').text();
    var size = $('#modal-edit-size').val();
    var sizeName = $('#modal-edit-size option:selected').text();
    var material = $('#modal-edit-material').val();
    var materialName = $('#modal-edit-material option:selected').text();
    var quantity = parseInt($('#modal-edit-quantity').val());
    var price = parseFloat($('#modal-edit-price').val());
    var totalPrice = quantity * price;

    // Kiểm tra dữ liệu đầu vào
    if (!productId) {
        alert('Vui lòng chọn sản phẩm!');
        return;
    }
    if (!color) {
        alert('Vui lòng chọn màu sắc!');
        return;
    }
    if (!size) {
        alert('Vui lòng chọn kích thước!');
        return;
    }
    if (!material) {
        alert('Vui lòng chọn chất liệu!');
        return;
    }
    if (isNaN(quantity) || quantity <= 0) {
        alert('Vui lòng nhập số lượng hợp lệ!');
        return;
    }
    if (isNaN(price) || price <= 0) {
        alert('Giá sản phẩm không hợp lệ!');
        return;
    }

    // Tạo uniqueKey mới
    var newUniqueKey = `${productId}-${color}-${size}-${material}`;
    var oldUniqueKey = $(this).data('unique-key'); // UniqueKey cũ

    // Lấy danh sách sản phẩm từ bảng
    var products = [];
    $('#order-details tr').each(function () {
        var row = $(this);
        var rowUniqueKey = row.data('unique-key');
        var rowData = {
            uniqueKey: rowUniqueKey,
            productName: row.find('td:nth-child(1) span').text(),
            colorName: row.find('td:nth-child(2) span').text(),
            sizeName: row.find('td:nth-child(3) span').text(),
            materialName: row.find('td:nth-child(4) span').text(),
            quantity: parseInt(row.find('td:nth-child(5) span').text()),
            price: parseFloat(row.find('td:nth-child(6) span').text().replace(/\./g, '').replace(',', '.')),
            totalPrice: parseFloat(row.find('td:nth-child(7) span').text().replace(/\./g, '').replace(',', '.'))
        };
        products.push(rowData);
    });

    // Kiểm tra sản phẩm trùng lặp
    var existingProductIndex = products.findIndex(product => product.uniqueKey === newUniqueKey);
    var oldProductIndex = products.findIndex(product => product.uniqueKey === oldUniqueKey);

    if (existingProductIndex !== -1 && oldUniqueKey !== newUniqueKey) {
        // Nếu sản phẩm trùng uniqueKey mới, cộng dồn số lượng
        products[existingProductIndex].quantity += quantity;
        products[existingProductIndex].totalPrice = products[existingProductIndex].quantity * products[existingProductIndex].price;

        // Cập nhật bảng HTML cho sản phẩm đã tồn tại
        var existingRow = $(`tr[data-unique-key="${newUniqueKey}"]`);
        existingRow.find('td:nth-child(5) span').text(products[existingProductIndex].quantity); // Cập nhật số lượng
        existingRow.find('td:nth-child(7) span').text(
            products[existingProductIndex].totalPrice.toLocaleString('vi-VN', { style: 'currency', currency: 'VND' }) // Cập nhật tổng giá
        );

        // Xóa sản phẩm cũ
        products.splice(oldProductIndex, 1);
        $(`tr[data-unique-key="${oldUniqueKey}"]`).remove();
    } else if (oldProductIndex !== -1) {
        // Nếu không trùng uniqueKey, cập nhật sản phẩm cũ
        products[oldProductIndex] = {
            uniqueKey: newUniqueKey,
            productName,
            colorName,
            sizeName,
            materialName,
            quantity,
            price,
            totalPrice
        };

        // Cập nhật bảng HTML
        var row = $(`tr[data-unique-key="${oldUniqueKey}"]`);
        row.attr('data-unique-key', newUniqueKey);
        row.find('td:nth-child(2) span').text(colorName);
        row.find('td:nth-child(3) span').text(sizeName);
        row.find('td:nth-child(4) span').text(materialName);
        row.find('td:nth-child(5) span').text(quantity);
        row.find('td:nth-child(6) span').text(price.toLocaleString('vi-VN'));
        row.find('td:nth-child(7) span').text(totalPrice.toLocaleString('vi-VN'));
    } else {
        alert('Không tìm thấy sản phẩm để cập nhật!');
        return;
    }

    // Cập nhật giá trị JSON vào input ẩn sau khi thay đổi
    $('#products').val(JSON.stringify(products));

    // Cập nhật tổng đơn hàng
    updateTotalOrder();

    // Đóng modal
    $('#editProductModal').modal('hide');
});

$(document).on('click', '.remove-product-btn', function() {
    var uniqueKey = $(this).data('unique-key'); // Get the uniqueKey from the data attribute
    var row = $(this).closest('tr'); // Get the closest product row

    // Show the iziToast confirmation message
    iziToast.question({
        title: 'Bạn có chắc chắn?',
        message: 'Sản phẩm sẽ bị xóa khỏi đơn hàng!',
        position: 'center', // Display in the center of the screen
        closeOnClick: true,
        buttons: [
            ['<button>Hủy</button>', function(instance, toast) {
                instance.hide({ transitionOut: 'fadeOut' }, toast, 'button'); // Close toast on cancel
            }, true], // true marks this button as the default one (cancel)
            ['<button>Xóa</button>', function(instance, toast) {
                //Get the current list of products from the hidden input field
                var products = JSON.parse($('#products').val() || '[]');

                // Filter out the product with the corresponding uniqueKey
                products = products.filter(product => product.uniqueKey !== uniqueKey);

                // Update the list of products in the hidden input
                $('#products').val(JSON.stringify(products));

                // Remove the product row from the table
                row.remove();

                // Update the order total
                updateTotalOrder();

                // Show success toast message
                iziToast.success({
                    title: 'Đã xóa!',
                    message: 'Sản phẩm đã được xóa.',
                    position: 'center',
                });

                instance.hide({ transitionOut: 'fadeOut' }, toast, 'button'); // Close toast after action
            }]
        ]
    });
        // Cập nhật tổng đơn hàng
        updateTotalOrder(); // Gọi lại hàm này để tính lại tổng tiền
});



</script>
@endsection

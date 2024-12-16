@extends('layouts.admin')
@section('content')
<div style="min-height:48vh">
					<h3 class="text-center mt-3">DANH SÁCH SẢN PHẨM</h3>
<div class="container">
<div class="row w3-res-tb">
<form action="" method="get" class="w-100">
    <div class="d-flex justify-content-start mb-6">
        <div class="col-sm-5">
            <div class="input-group">
                <input type="search" name="keyword" class="form-control" placeholder="Tìm theo Tên" value="{{ request()->keyword ?? '' }}">
                <select name="productType" class="form-control ml-2">
                    <option value="">Loại sản phẩm</option>
                    @foreach ($productTypes as $productType)
                        <option value="{{ $productType->productTypeId }}" {{ request()->productType == $productType->productTypeId ? 'selected' : '' }}>
                            {{ $productType->productTypeName }}
                        </option>
                    @endforeach
                </select>
                <div class="input-group-append ml-2">
                    <button type="submit" class="btn btn-primary">Lọc</button>
                    <a href="{{ route('admins.export.product', [
        'keyword' => request()->keyword,
        'productType' => request()->productType
    ]) }}"  
    class="btn btn-success col-sm-9 form-control">Xuất Excel</a>
                </div>
            </div>
        </div>
    </div>
</form>

    <table class="table table-hover table-bordered align-middle">
        <thead>
            <tr class="text-center table-primary">
                <th>
                    Mã sản phẩm
                </th>
                <th>
                    Tên sản phẩm
                </th>
                <th>
                    Loại sản phẩm
                </th>
                <th>
                    Giá bán
                </th>
            </tr>
        </thead>
        <tbody>
        @foreach ($all_product as $key => $product)
                <tr class="text-center">
                    <td>
                        {{ $product->productId }}
                    </td>
                    <td>
                        {{ $product->productName }}
                    </td>
                    <td>
                        @php
                            $product_type = DB::table('product_type')->where('productTypeId', $product->productTypeId)->first();
                            if($product_type) {
                                echo $product_type->productTypeName;
                            }
                        @endphp
                    </td>
                    <td>
                        {{ $product->price }}
                    </td>
                    <td>
                        <a href="{{ route('admin.view.product', ['id' => $product->productId]) }}" class="active styling-edit" ui-toggle-class="">
                        <i class="fa fa-eye text-primary text-active"></i></a>
                        <a href="{{ route('admin.edit.product', ['id' => $product->productId]) }}" class="active styling-edit" ui-toggle-class="">
                        <i class="fa fa-pencil-square-o text-success text-active"></i></a>
                        <a onclick="return confirm('Bạn có muốn xóa không?')" href="{{ route('admin.delete.product', ['id' => $product->productId]) }}" class="active styling-edit" ui-toggle-class="">
<i class="fa fa-times text-danger text"></i></a>
                    </td>
                </tr>
                @endforeach
        </tbody>
    </table>
    <div class="d-flex justify-content-center">
                {{ $all_product->links('pagination::bootstrap-4') }}
            </div>
</div>
</div>
@endsection
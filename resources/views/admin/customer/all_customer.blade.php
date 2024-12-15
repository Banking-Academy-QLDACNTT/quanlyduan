@extends('layouts.admin')
@section('content')
<div style="min-height:48vh">
					<h3 class="text-center mt-3">DANH SÁCH KHÁCH HÀNG</h3>
<div class="container">
<div class="row w3-res-tb">
<form action="" method="get" class="w-100">
    <div class="d-flex justify-content-start mb-6">
        <div class="col-sm-4">
            <div class="input-group">
                <input type="search" name="keyword" class="form-control" placeholder="Tìm theo Tên khách hàng" value="{{ request()->keyword ?? '' }}">
            </div>
        </div>
        <div class="col-sm-3">
            <select name="customerTypeId" class="form-control">
                <option value="">Loại khách hàng</option>
                @foreach ($customerTypes as $customerType)
                    <option value="{{ $customerType->customerTypeId }}" {{ request()->customerTypeId == $customerType->customerTypeId ? 'selected' : '' }}>
                        {{ $customerType->customerTypeName }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-sm-3">
            <select name="rankingTypeId" class="form-control">
                <option value="">Thẻ khách hàng</option>
                @foreach ($rankingTypes as $rankingType)
                    <option value="{{ $rankingType->rankingTypeId }}" {{ request()->rankingTypeId == $rankingType->rankingTypeId ? 'selected' : '' }}>
                        {{ $rankingType->rankingTypeName }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-sm-2">
            <button type="submit" class="btn btn-primary">Lọc</button>
        </div>
    </div>
</form>
    <table class="table table-hover table-bordered align-middle">
        <thead>
            <tr class="text-center table-primary">
                <th>
                    Mã Khách hàng
                </th>
                <th>
                    Tên Khách hàng
                </th>
                <th>
                    Số điện thoại
                </th>
                <th>
                    Loại khách hàng
                </th>
                <th>
                    Thẻ khách hàng
                </th>
                <th>
                    Thao tác
                </th>

            </tr>
        </thead>
        <tbody>
        @foreach ($all_customers as $key => $customer)
                <tr class="text-center">
                    <td>
                        {{ $customer->customerId }}
                    </td>
                    <td>
                        {{ $customer->customerName }}
                    </td>
                     <td>
                        {{ $customer->phoneNumber }}
                    </td>
                    <td>
                        @php
                            $customer_type = DB::table('customer_type')->where('customerTypeId', $customer->customerTypeId)->first();
                            if($customer_type) {
                                echo $customer_type->customerTypeName;
                            }
                        @endphp
                    </td>
                    <td>
                        @php
                            $ranking_type = DB::table('ranking_type')->where('rankingTypeId', $customer->rankingTypeId)->first();
                            if($ranking_type) {
                                echo $ranking_type->rankingTypeName;
                            }
                        @endphp
                    </td>
                    <td>
                        <a href="{{ route('admin.view.customer', ['id' => $customer->customerId]) }}" class="active styling-edit" ui-toggle-class="">
                        <i class="fa fa-eye text-primary text-active"></i></a>
                        <a href="{{ route('admin.edit.customer', ['id' => $customer->customerId]) }}" class="active styling-edit" ui-toggle-class="">
                        <i class="fa fa-pencil-square-o text-success text-active"></i></a>
                        <a onclick="return confirm('Bạn có muốn xóa không?')" href="{{ route('admin.delete.customer', ['id' => $customer->customerId]) }}" class="active styling-edit" ui-toggle-class="">
                        <i class="fa fa-times text-danger text"></i></a>
                    </td>
                </tr>
                @endforeach
        </tbody>
    </table>
    <div class="d-flex justify-content-center">
                {{ $all_customers->links('pagination::bootstrap-4') }}
            </div>
</div>
</div>
@endsection

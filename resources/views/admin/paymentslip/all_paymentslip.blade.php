@extends('layouts.admin')
@section('content')
<div style="min-height:48vh">
					<h3 class="text-center mt-3">DANH SÁCH PHIẾU THANH TOÁN</h3>
<div class="container">
<div class="row w3-res-tb">
<form action="" method="get" class="w-100">
    <div class="d-flex justify-content-start mb-6">
        <div class="col-sm-5">
            <div class="input-group">
                <select name="paymentMethod" class="form-control ml-2">
                    <option value="">Phương thức thanh toán</option>
                    @foreach ($paymentMethods as $method)
                        <option value="{{ $method->paymentMethodId }}" {{ request()->paymentMethod == $method->paymentMethodId ? 'selected' : '' }}>
                            {{ $method->paymentMethodName }}
                        </option>
                    @endforeach
                </select>
                <select name="paymentStatus" class="form-control ml-2">
                    <option value="">Trạng thái phiếu</option>
                    @foreach ($paymentStatuses as $status)
                        <option value="{{ $status->paymentStatusId }}" {{ request()->paymentStatus == $status->paymentStatusId ? 'selected' : '' }}>
                            {{ $status->paymentStatusName }}
                        </option>
                    @endforeach
                </select>
                <div class="input-group-append ml-2">
                    <button type="submit" class="btn btn-primary">Lọc</button>
                    <a href="{{ route('admins.export.paymentslip', ['paymentMethod' => request()->paymentMethod, 'paymentStatus' => request()->paymentStatus]) }}" 
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
                    Mã phiếu
                </th>
                <th>
                    Mã đơn hàng
                </th>
                <th>
                    Số tiền đặt cọc
                </th>
                <th>
                    Ngày đặt cọc
                </th>
                <th>
                    Thành tiền
                </th>
                <th>
                    Ngày thanh toán
                </th>
                <th>
                    Mã phương thức thanh toán
                </th>
                <th>
                    Ghi chú
                </th>
                <th>
                    Mã trạng thái phiếu
                </th>
                <th>
                    Thao tác
                </th>

            </tr>
        </thead>
        <tbody>
        @foreach ($all_paymentslip as $key => $paymentslip)
                <tr class="text-center">
                    <td>
                        {{ $paymentslip->paymentSlipId }}
                    </td>
                    <td>
                        {{ $paymentslip->orderId }}
                    </td>
                    <td>
                        {{ $paymentslip->deposit }}
                    </td>
                    <td>
                        {{ $paymentslip->depositDate }}
                    </td>
                    <td>
                        @php
                            $orderValue = DB::table('orders')->where('orderId', $paymentslip->orderId)->first();
                            if($orderValue) {
                                echo $orderValue->totalOrderValue;
                            }
                        @endphp
                    </td>
                    <td>
                        {{ $paymentslip->paymentDate }}
                    </td>
                    <td>
                        @php
                            $paymentMethodId = DB::table('payment_method')->where('paymentMethodId', $paymentslip->paymentMethodId)->first();
                            if($paymentMethodId) {
                                echo $paymentMethodId->paymentMethodName;
                            }
                        @endphp
                    </td>
                    <td>
                        {{ $paymentslip->note}}
                    </td>
                    <td>
                        @php
                            $paymentStatusId = DB::table('payment_status')->where('paymentStatusId', $paymentslip->paymentStatusId)->first();
                            if($paymentStatusId) {
                                echo $paymentStatusId->paymentStatusName;
                            }
                        @endphp
                    </td>
                
            
                    <td>
                        <a href="{{ route('admin.view.paymentslip', ['id' => $paymentslip->paymentSlipId]) }}" class="active styling-edit" ui-toggle-class="">
                        <i class="fa fa-eye text-primary text-active"></i></a>
                        <a href="{{ route('admin.edit.paymentslip', ['id' => $paymentslip->paymentSlipId]) }}" class="active styling-edit" ui-toggle-class="">
                        <i class="fa fa-pencil-square-o text-success text-active"></i></a>
                        <a onclick="return confirm('Bạn có muốn xóa không?')" href="{{ route('admin.delete.paymentslip', ['id' => $paymentslip->paymentSlipId]) }}" class="active styling-edit" ui-toggle-class="">
<i class="fa fa-times text-danger text"></i></a>
                    </td>
                </tr>
                @endforeach
        </tbody>
    </table>
    <div class="d-flex justify-content-center">
                {{ $all_paymentslip->links('pagination::bootstrap-4') }}
            </div>
</div>
</div>
@endsection
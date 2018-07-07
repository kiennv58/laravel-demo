@extends('adminlte::page')

@section('title', $title)

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/admin.css') }}">
    <style type="text/css">
        .design {
            color: #7bc1f7 !important
        }
        .produce {
            color: #1D8CE0 !important
        }
        .shipping {
            color: #e67e22 !important;
        }
        .finish {
            color: #0EBC0E !important;
        }
        .label {
            display: block;
            width: 100px;
            line-height: 15px;
        }
        .status-1 {
            background-color: #c5c5c5 !important;
        }
        .status-2 {
            background-color: #00c0ef !important;
        }
        .status-3 {
            background-color: #0047ef !important;
        }
        .status-4 {
            background-color: #f39c12 !important;
        }
        .status-5 {
            background-color: #00a65a !important;
        }
        .status-6 {
            background-color: #dd4b39 !important;
        }
    </style>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12" id="backend-blog">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">Danh sách đơn hàng</h3>
                    <span class="pull-right">
                        <a href="{{ route('orders.create') }}" class="btn btn-success"><i class="fa fa-plus"></i> Thêm mới</a>
                    </span>
                </div>

                <table class="table table-striped table-hover table-bordered">
                    <thead>
                        <tr>
                            <th width="35">#</th>
                            <th width="220">Thông tin đơn hàng</th>
                            <th>Thông tin khách hàng</th>
                            <th width="150">Giờ hẹn trả</th>
                            <th width="130">Trạng thái</th>
                            <th width="70">Chiết khấu(%)</th>
                            <th width="100" class="text-center">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($orders as $key => $order)
                            <tr>
                                <td class="text-center">{{ ++$key }}</td>
                                <td>
                                    <p>{{ $order->order_code }}</p>
                                    <p>Tạo bởi: {{ $order->staff->name }}</p>
                                    <p>Ngày tạo: {{ $order->created_at }}</p>
                                    <p>Sửa lần cuối: {{ $order->updated_at }}</p
                                </td>
                                <td>
                                    <p>{{ $order->customer->name }}</p>
                                    <p>{{ $order->customer->phone }}</p>
                                    <p>{{ $order->customer->address }}</p>
                                </td>
                                <td>{{ $order->deadline }} </td>
                                <td>
                                    <span class="label status-{{ $order->status }}">{{ $order::STATUS_LIST[$order->status] }}</span>
                                </td>
                                <td>{{ $order->kpi * 100 }}%</td>
                                <td class="text-center">
                                    @if ($order->status == 1)
                                    <a href="{{ route('orders.edit', $order->id) }}" class="btn btn-xs" data-toggle="tooltip" title="Sửa">
                                        <i class="fa fa-pencil" aria-hidden="true"></i>
                                    </a>
                                    <a href="#" data-href="{{ route('orders.destroy', $order->id) }}" class="btn btn-xs btn-destroy label-cancel" data-toggle="tooltip" title="Xóa">
                                        <i class="fa fa-times" aria-hidden="true"></i>
                                    </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Không có dữ liệu !</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="box-footer">
                    <div class="pull-right">
                        @if (count($orders))
                            {!! $orders->appends([Request::all()])->links() !!}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@section('js')
<script type="text/javascript">
</script>
@stop

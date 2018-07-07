@extends('adminlte::page')

@section('title', $title)

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/admin.css') }}">
@stop

@section('content')
    <div class="row">
        <div class="col-md-12" id="backend-blog">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">Danh sách khách hàng</h3>
                    <span class="pull-right">
                        <a href="{{ route('customers.create') }}" class="btn btn-success"><i class="fa fa-plus"></i> Thêm mới</a>
                    </span>
                </div>

                <table class="table table-striped table-hover table-bordered">
                    <thead>
                        <tr>
                            <th width="35">#</th>
                            <th width="200">Tên</th>
                            <th width="200">Só điện thoại</th>
                            <th>Địa chỉ</th>
                            <th width="100" class="text-center">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($customers as $key => $customer)
                            <tr>
                                <td class="text-center">{{ ++$key }}</td>
                                <td>{{ $customer->name }}</td>
                                <td>{{ $customer->phone }}</td>
                                <td>{{ $customer->address }}</td>
                                <td class="text-center">
                                    <a href="{{ route('customers.edit', $customer->id) }}" class="btn btn-xs" data-toggle="tooltip" title="Sửa">
                                        <i class="fa fa-pencil" aria-hidden="true"></i>
                                    </a>
                                    <a href="#" data-href="{{ route('customers.destroy', $customer->id) }}" class="btn btn-xs btn-destroy label-cancel" data-toggle="tooltip" title="Xóa">
                                        <i class="fa fa-times" aria-hidden="true"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">Không có dữ liệu !</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="box-footer">
                    <div class="pull-right">
                        @if (count($customers))
                            {!! $customers->appends([Request::all()])->links() !!}
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

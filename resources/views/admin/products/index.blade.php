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
                    <h3 class="box-title">Danh sách sản phẩm</h3>
                    <span class="pull-right">
                        <a href="{{ route('products.create') }}" class="btn btn-success"><i class="fa fa-plus"></i> Thêm mới</a>
                    </span>
                </div>

                <table class="table table-striped table-hover table-bordered">
                    <thead>
                        <tr>
                            <th width="35">#</th>
                            <th width="100">Mã sản phẩm</th>
                            <th width="200">Tên</th>
                            <th>Mô tả</th>
                            <th width="100">Giá</th>
                            <th width="130">Giá trên mỗi trang</th>
                            <th width="50">%</th>
                            <th width="100" class="text-center">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($products as $key => $product)
                            <tr>
                                <td class="text-center">{{ ++$key }}</td>
                                <td>{{ $product->getTypes()[$product->type] }}</td>
                                <td>{{ $product->name }}</td>
                                <td>{{ $product->description }}</td>
                                <td>{{ $product->default_price }}</td>
                                <td>{{ $product->price_per_page }}</td>
                                <td>{{ $product->kpi }}</td>
                                <td class="text-center">
                                    <a href="{{ route('products.edit', $product->id) }}" class="btn btn-xs" data-toggle="tooltip" title="Sửa">
                                        <i class="fa fa-pencil" aria-hidden="true"></i>
                                    </a>
                                    <a href="#" data-href="{{ route('products.destroy', $product->id) }}" class="btn btn-xs btn-destroy label-cancel" data-toggle="tooltip" title="Xóa">
                                        <i class="fa fa-times" aria-hidden="true"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">Không có dữ liệu !</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="box-footer">
                    <div class="pull-right">
                        @if (count($products))
                            {!! $products->appends([Request::all()])->links() !!}
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

@extends('adminlte::page')

@section('title', $title)

@section('css')
    <style type="text/css">
    </style>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12 admin-blog-create">
            <div class="box box-success">
                <form action="{{ route('customers.update', $customer->id) }}" method="POST" enctype="multipart/form-data">
                    <div class="box-header with-border">
                        <h3 class="box-title">Sửa thông tin khách hàng</h3>
                        <span class="pull-right">
                            <button type="submit" class="btn btn-warning"><i class="fa fa-floppy-o" aria-hidden="true"></i> Cập nhật</button>
                            <a href="{{ route('customers.index') }}" class="btn btn-default"><i class="fa fa-long-arrow-left"></i> Trờ lại</a>
                        </span>
                    </div>
                    {{ csrf_field() }}
                    {{ method_field('PUT') }}
                    <div class="box-body">
                        <div class="col-md-12">
                            <div class="form-group {{ $errors->first('name') ? 'has-error' : '' }}">
                                <label for="">Họ tên khách hàng <b style="color: red">*</b></label>
                                <input type="text" class="form-control" name="name" placeholder="Tiêu đề" value="{{ Request::old('name', $customer->name) }}">
                                <span class="help-block">{{ $errors->first('name') }}</span>
                            </div>
                            <div class="form-group {{ $errors->first('phone') ? 'has-error' : '' }}">
                                <label for="">Số điện thoại <b style="color: red">*</b></label>
                                <input type="text" class="form-control" name="phone" placeholder="Tiêu đề" value="{{ Request::old('phone', $customer->phone) }}">
                                <span class="help-block">{{ $errors->first('phone') }}</span>
                            </div>
                            <div class="form-group {{ $errors->first('address') ? 'has-error' : '' }}">
                                <label for="">Địa chỉ <b style="color: red">*</b></label>
                                <input type="text" class="form-control" name="address" placeholder="Tiêu đề" value="{{ Request::old('address', $customer->address) }}">
                                <span class="help-block">{{ $errors->first('address') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <button type="submit" class="btn btn-warning"><i class="fa fa-floppy-o" aria-hidden="true"></i> Cập nhật</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('js')
    <script type="text/javascript">
    </script>
@stop

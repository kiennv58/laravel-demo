@extends('adminlte::page')

@section('title', $title)

@section('css')
@stop

@section('content')
    <div class="row">
        <div class="col-md-12 admin-blog-create">
            <div class="box box-success">
                <form action="{{ route('customers.store') }}" method="POST" enctype="multipart/form-data">
                    <div class="box-header with-border">
                        <h3 class="box-title">Thêm mới khách hàng</h3>
                        <span class="pull-right">
                            <button type="submit" class="btn btn-success"><i class="fa fa-floppy-o" aria-hidden="true"></i> Thêm mới</button>
                            <a href="{{ route('customers.index') }}" class="btn btn-default"><i class="fa fa-long-arrow-left"></i> Trở lại</a>
                        </span>
                    </div>
                    {{ csrf_field() }}
                    <div class="box-body">
                        <div class="col-md-12">
                            <div class="form-group {{ $errors->first('name') ? 'has-error' : '' }}">
                                <label for="">Họ tên khách hàng <b style="color: red">*</b></label>
                                <input type="text" class="form-control" name="name" placeholder="Tiêu đề" value="{{ old('name') }}">
                                <span class="help-block">{{ $errors->first('name') }}</span>
                            </div>
                            <div class="form-group {{ $errors->first('phone') ? 'has-error' : '' }}">
                                <label for="">Số điện thoại <b style="color: red">*</b></label>
                                <input type="text" class="form-control" name="phone" placeholder="Tiêu đề" value="{{ old('phone') }}">
                                <span class="help-block">{{ $errors->first('phone') }}</span>
                            </div>
                            <div class="form-group {{ $errors->first('address') ? 'has-error' : '' }}">
                                <label for="">Địa chỉ <b style="color: red">*</b></label>
                                <input type="text" class="form-control" name="address" placeholder="Tiêu đề" value="{{ old('address') }}">
                                <span class="help-block">{{ $errors->first('address') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <button type="submit" class="btn btn-success"><i class="fa fa-floppy-o" aria-hidden="true"></i> Thêm mới</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('js')</script>
    <script type="text/javascript">
    </script>
@stop

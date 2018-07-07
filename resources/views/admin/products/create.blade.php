@extends('adminlte::page')

@section('title', $title)

@section('css')
@stop

@section('content')
    <div class="row">
        <div class="col-md-12 admin-blog-create">
            <div class="box box-success">
                <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                    <div class="box-header with-border">
                        <h3 class="box-title">Thêm mới sản phẩm</h3>
                        <span class="pull-right">
                            <button type="submit" class="btn btn-success"><i class="fa fa-floppy-o" aria-hidden="true"></i> Thêm mới</button>
                            <a href="{{ route('products.index') }}" class="btn btn-default"><i class="fa fa-long-arrow-left"></i> Trở lại</a>
                        </span>
                    </div>
                    {{ csrf_field() }}
                    <div class="box-body">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Loại sản phẩm</label>
                                <select class="form-control" name="type">
                                    @foreach ($types as $key => $type)
                                    <option value="{{ $key }}">{{ $type }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group {{ $errors->first('name') ? 'has-error' : '' }}">
                                <label for="">Tên sản phẩm <b style="color: red">*</b></label>
                                <input type="text" class="form-control" name="name" placeholder="Nhập tên sản phẩm" value="{{ old('name') }}">
                                <span class="help-block">{{ $errors->first('name') }}</span>
                            </div>
                            <div class="form-group {{ $errors->first('description') ? 'has-error' : '' }}">
                                <label for="">Mô tả</label>
                                <textarea class="form-control" name="description" rows="4" placeholder="Nhập mô tả">{{ old('description') }}</textarea>
                                <span class="help-block">{{ $errors->first('description') }}</span>
                            </div>
                            <div class="form-group {{ $errors->first('default_price') ? 'has-error' : '' }}">
                                <label for="">Giá <b style="color: red">*</b></label>
                                <input type="number" class="form-control" name="default_price" placeholder="Nhập giá" value="{{ old('default_price') }}">
                                <span class="help-block">{{ $errors->first('default_price') }}</span>
                            </div>
                            <div class="form-group {{ $errors->first('price_per_page') ? 'has-error' : '' }}">
                                <label for="">Giá mỗi trang<b style="color: red">*</b></label>
                                <input type="number" class="form-control" name="price_per_page" placeholder="Nhập giá mỗi trang" value="{{ old('price_per_page') }}">
                                <span class="help-block">{{ $errors->first('price_per_page') }}</span>
                            </div>
                            <div class="form-group {{ $errors->first('size') ? 'has-error' : '' }}">
                                <label for="">Kích thước<b style="color: red">*</b></label>
                                <input type="number" class="form-control" name="size" placeholder="Nhập kích thước" value="{{ old('size') }}">
                                <span class="help-block">{{ $errors->first('size') }}</span>
                            </div>
                            <div class="form-group {{ $errors->first('quantity') ? 'has-error' : '' }}">
                                <label for="">Số lượng<b style="color: red">*</b></label>
                                <input type="number" class="form-control" name="quantity" placeholder="Số lượng (hoặc số trang)" value="{{ old('quantity') }}">
                                <span class="help-block">{{ $errors->first('quantity') }}</span>
                            </div>
                            <div class="form-group {{ $errors->first('kpi') ? 'has-error' : '' }}">
                                <label for="">Chiết khấu</label>
                                <input type="text" class="form-control" name="kpi" placeholder="VD: 0.3 ~ 30%" value="{{ old('kpi') }}">
                                <span class="help-block">{{ $errors->first('kpi') }}</span>
                            </div>
                            <div class="form-group {{ $errors->first('time_avg') ? 'has-error' : '' }}">
                                <label for="">Thời gian làm ước tính</label>
                                <input type="text" class="form-control" name="time_avg" placeholder="Đơn vị phút" value="{{ old('time_avg') }}">
                                <span class="help-block">{{ $errors->first('time_avg') }}</span>
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

@extends('adminlte::page')

@section('title', $title)

@section('css')
	<link rel="stylesheet" type="text/css" href="{{ asset('css/admin.css') }}">
@stop

@section('content')
    <div class="row">
        <div class="col-md-12 admin-blog-create">
            <div class="box box-success">
            	<div class="box-header with-border">
            	    <h3 class="box-title">Đổi mật khẩu</h3>
            	    <span class="pull-right">
            	    	<a href="{{ route('home') }}" class="btn btn-sm btn-default"><i class="fa fa-long-arrow-left"></i> Trở lại</a>
            	    </span>
            	</div>
                <form action="{{ route('change-password') }}" method="POST" enctype="multipart/form-data">
                	{{ csrf_field() }}
                	<div class="box-body">
						<div class="col-md-8">
							<div class="form-group {{ $errors->first('old_password') ? 'has-error' : '' }}">
								<label for="">Mật khẩu cũ <b style="color: red">*</b></label>
								<input type="password" class="form-control" name="old_password" placeholder="" value="{{ old('old_password') }}">
								<span class="help-block">{{ $errors->first('old_password') }}</span>
							</div>
							<div class="form-group {{ $errors->first('password') ? 'has-error' : '' }}">
								<label for="">Mật khẩu mới <b style="color: red">*</b></label>
								<input type="password" class="form-control" name="password" placeholder="Ít nhất 6 ký tự" value="{{ old('password') }}">
								<span class="help-block">{{ $errors->first('password') }}</span>
							</div>
							<div class="form-group {{ $errors->first('password_confirmation') ? 'has-error' : '' }}">
								<label for="">Nhập lại mật khẩu mới <b style="color: red">*</b></label>
								<input type="password" class="form-control" name="password_confirmation" placeholder="" value="{{ old('password_confirmation') }}">
								<span class="help-block">{{ $errors->first('password_confirmation') }}</span>
							</div>
						</div>
            		</div>
            		<div class="box-footer">
            			<div class="col-md-4"></div>
            			<div class="col-md-8 row">
            				<button type="submit" class="btn btn-sm btn-success"><i class="fa fa-floppy-o" aria-hidden="true"></i> Thêm mới</button>
            			</div>
            		</div>
               	</form>
            </div>
        </div>
    </div>
@stop

@section('js')
@stop
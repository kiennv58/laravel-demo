@extends('adminlte::page')

@section('title', $title)

@section('css')
	<link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap-fileupload.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('css/admin.css') }}">
@stop

@section('content')
    <div class="row">
        <div class="col-md-12 admin-blog-create">
            <div class="box box-success">
            	<div class="box-header with-border">
            	    <h3 class="box-title">Cập nhật nhân viên</h3>
            	    <span class="pull-right">
            	    	<a href="{{ route('accounts.index') }}" class="btn btn-sm btn-default"><i class="fa fa-long-arrow-left"></i> Trờ lại</a>
            	    </span>
            	</div>
                <form action="{{ route('accounts.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                	{{ csrf_field() }}
                	{{ method_field('PUT') }}
                	<div class="box-body">
						<div class="col-md-4">
							<div class="fileupload fileupload-new" data-provides="fileupload">
							    <div class="fileupload-new thumbnail" style="width: 300px; height: 300px; margin-top: 18px;">
							        <img src="{{ $user->getImage($user->avatar) }}" alt="" />
							    </div>
							    <div class="fileupload-preview fileupload-exists thumbnail" style="width: 300px; height: 300px; margin-top: 18px; line-height: 0px"></div>
							    <div>
							        <span class="btn btn-white btn-file">
							            <span class="fileupload-new fix-new"><i class="fa fa-paper-clip"></i> Chọn ảnh</span>
							            <span class="fileupload-exists"><i class="fa fa-undo"></i> Đổi ảnh</span>
							            <input type="file" name="avatar" class="default" />
							        </span>
							    </div>
							</div>
							<span class="help-block">{{ $errors->first('avatar') }}</span>
						</div>
						<div class="col-md-8">
							<div class="form-group {{ $errors->first('username') ? 'has-error' : '' }}">
								<label for="">Tài khoản đăng nhập <b style="color: red">*</b></label>
								<input type="text" class="form-control" name="username" placeholder="Tên nhân viên" value="{{ $user->username }}">
								<span class="help-block">{{ $errors->first('username') }}</span>
							</div>
							<div class="form-group {{ $errors->first('name') ? 'has-error' : '' }}">
								<label for="">Tên nhân viên <b style="color: red">*</b></label>
								<input type="text" class="form-control" name="name" placeholder="Tên nhân viên" value="{{ $user->name }}">
								<span class="help-block">{{ $errors->first('name') }}</span>
							</div>
							<div class="form-group {{ $errors->first('phone') ? 'has-error' : '' }}">
								<label for="">Số điện thoại <b style="color: red">*</b></label>
								<input type="text" class="form-control" name="phone" placeholder="Số điện thoại" value="{{ $user->phone }}">
								<span class="help-block">{{ $errors->first('phone') }}</span>
							</div>
							<div class="form-group {{ $errors->first('email') ? 'has-error' : '' }}">
								<label for="">Email</label>
								<input type="text" class="form-control" name="email" placeholder="Email" value="{{ $user->email }}">
								<span class="help-block">{{ $errors->first('email') }}</span>
							</div>
							<div class="form-group {{ $errors->first('address') ? 'has-error' : '' }}">
								<label for="">Địa chỉ</label>
								<input type="text" class="form-control" name="address" placeholder="Địa chỉ" value="{{ $user->address }}">
								<span class="help-block">{{ $errors->first('address') }}</span>
							</div>
							<div class="form-group {{ $errors->first('basic_salary') ? 'has-error' : '' }}">
								<label for="">Lương cơ bản <b style="color: red">*</b></label>
								<input type="text" class="form-control" name="basic_salary" placeholder="Lương cơ bản" value="{{ $user->basic_salary }}">
								<span class="help-block">{{ $errors->first('basic_salary') }}</span>
							</div>
							<div class="form-group {{ $errors->first('start_date') ? 'has-error' : '' }}">
								<label for="">Ngày bắt đầu <b style="color: red">*</b></label>
								<input type="text" id="start_date" class="form-control" name="start_date" placeholder="Ngày bắt đầu làm việc" value="{{ $user->start_date }}">
								<span class="help-block">{{ $errors->first('start_date') }}</span>
							</div>
							<div class="form-group">
            					<label>
            	                  	<input type="checkbox" class="flat-red" name="active" value="1" {{ $user->active ? 'checked' : '' }}>
            	                  	Kích hoạt
            	                </label>
							</div>
							<div class="form-group {{ $errors->first('roles') ? 'has-error' : '' }}">
								<label>Chức vụ</label>
								@forelse ($roles as $role)
				                <div class="">
                					<label class="col-md-2 checkbox">
                	                  	<input type="checkbox" name="roles[]" value="{{ $role->id }}" {{ in_array($role->id, array_pluck($user->roles->all(), 'id')) ? 'checked' : '' }}>
                	                  	{{ $role->display_name }}
                	                </label>
				                </div>
				                @empty
				                <p>Không tìm thấy chức vụ nào!</p>
				                @endforelse
				                <span class="col-md-12 help-block">{{ $errors->first('roles') }}</span>
							</div>
						</div>
            		</div>
            		<div class="box-footer">
            			<div class="col-md-4"></div>
            			<div class="col-md-8 row">
            				<button type="submit" class="btn btn-sm btn-success"><i class="fa fa-floppy-o" aria-hidden="true"></i> Lưu lại</button>
            			</div>
            		</div>
               	</form>
            </div>
        </div>
    </div>
@stop

@section('js')
	<script src="{{ asset('js/bootstrap-fileupload.js') }}" ></script>
	<script src="{{ asset('js/cleave.min.js') }}" ></script>
	<script type="text/javascript">
		$('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
		  checkboxClass: 'icheckbox_flat-green'
		});
		var cleave = new Cleave('input[name=basic_salary]', {
		    numeral: true,
		    numeralThousandsGroupStyle: 'thousand'
		});
		jQuery.datetimepicker.setLocale('vi');
		$('#start_date').datetimepicker({
		    validateOnBlur: true,
		    format: 'Y-m-d H:i'
		});
	</script>
@stop
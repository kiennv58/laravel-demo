@extends('adminlte::page')

@section('title', $title)

@section('content_header')

@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-info alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4>Hướng dẫn</h4>
                Mỗi chức vụ sẽ có tên và nhóm quyền hạn khác nhau.
            </div>
        </div>
        <div class="col-md-5 col-sm-12 col-xs-12">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">Danh sách chức vụ</h3>
                </div>

                <div class="box-body">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th width="15">#</th>
                                <th width="150" class="text-center">Mã</th>
                                <th width="150" class="text-center">Tên</th>
                                <th class="text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($roles as $key => $role)
                                <tr>
                                    <td class="text-center">{{ ++$key }}</td>
                                    <td class="text-center">{{ $role->name }}</td>
                                    <td class="text-center">{{ $role->display_name }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-xs" data-toggle="tooltip" title="Sửa">
                                            <i class="fa fa-pencil" aria-hidden="true"></i>
                                        </a>
                                        <a data-href="{{ route('roles.destroy', $role->id) }}" class="btn btn-xs delete-btn" data-toggle="tooltip" title="Xóa">
                                            <i class="fa fa-times" aria-hidden="true"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">Không có dữ liệu !</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="box-footer">
                    @if (count($roles))
                        {!! $roles->appends([Request::all()])->links() !!}
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-7 col-sm-12 col-xs-12">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ isset($role_edit) ? 'Cập nhật' : 'Thêm mới' }} chức vụ</h3>
                </div>
                <div class="box-body">
                    <form action="{{ isset($role_edit) ? route('roles.update', $role_edit->id) : route('roles.store')}}" method="POST" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        @if (isset($role_edit))
                            <input name="_method" type="hidden" value="PUT">
                        @endif
                        <div class="form-group {{ $errors->first('name') ? 'has-error' : ''}}">
                            <label class="control-label" for="title">Mã chức vụ <b style="color: red">*</b></label>
                            <input type="text" class="form-control" name="name" id="name" placeholder="Viết liền không dấu. VD: nhanvienkinhdoanh" value="{{ old('title', isset($role_edit) ? $role_edit->name : '')  }}">
                            <span class="help-block">{{ $errors->first('name') }}</span>
                        </div>
                        <div class="form-group {{ $errors->first('display_name') ? 'has-error' : ''}}">
                            <label class="control-label" for="title">Tên chức vụ <b style="color: red">*</b></label>
                            <input type="text" class="form-control" name="display_name" id="display_name" placeholder="Nhập tên chức vụ" value="{{ old('title', isset($role_edit) ? $role_edit->display_name : '')  }}">
                            <span class="help-block">{{ $errors->first('display_name') }}</span>
                        </div>
                        <div class="form-group {{ $errors->first('permissions') ? 'has-error' : ''}}">
                            <label class="col-md-12 row">Quyền hạn <b style="color: red">*</b></label>
                            
                            @forelse ($permissions as $key => $perms)
                                <div class="col-md-12">
                                    <label><i class="fa fa-ellipsis-v"></i> {{ ucwords($key) }}</label>
                                </div>
                                @foreach($perms as $perm)
                                <div class="col-md-4 checkbox" style="margin-top: 10px;">
                                    <label>
                                        <input type="checkbox" name="permissions[]" id="permissions" value="{{ $perm->id }}" {{ (isset($role_edit) && in_array($perm->id, array_pluck($role_edit->perms()->get()->all(), 'id'))) ? 'checked' : '' }}>
                                        {{ $perm->display_name }}
                                    </label>
                                </div>
                                @endforeach
                            @empty
                            <p>Chưa có quyền hạn nào</p>
                            @endforelse
                            <span class="col-md-12 help-block">{{ $errors->first('permissions') }}</span>
                        </div>

                        <div class="form-group col-md-12 row" style="margin-top: 20px;">
                            <button type="submit" class="btn btn-{{ isset($role_edit) ? 'warning' : 'success' }} btn-sm">{{ isset($role_edit) ? 'Cập nhật' : 'Thêm mới' }}</button>
                            @if (isset($role_edit))
                                <a href="{{ route('roles.index') }}" class="btn btn-default btn-sm btn-cancel">Hủy</a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
@stop

@section('js')
    <script type="text/javascript">
        $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
          checkboxClass: 'icheckbox_flat-green'
        });
    </script>
@stop

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
                Vui lòng liên hệ nhân viên IT nếu cần thêm quyền hạn mới.
            </div>
        </div>
        <div class="col-md-6 col-sm-12 col-xs-12">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">Danh sách quyền hạn</h3>
                </div>

                <div class="box-body">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th width="15">#</th>
                                <th width="200" class="text-center">Mã</th>
                                <th width="200" class="text-center">Tên</th>
                                <th class="text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($permissions as $key => $permission)
                                <tr>
                                    <td class="text-center">{{ ++$key }}</td>
                                    <td class="text-center">{{ $permission->name }}</td>
                                    <td class="text-center">{{ $permission->display_name }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('permissions.edit', $permission->id) }}" class="btn btn-xs" data-toggle="tooltip" title="Sửa">
                                            <i class="fa fa-pencil" aria-hidden="true"></i>
                                        </a>
                                        <a data-href="{{ route('permissions.destroy', $permission->id) }}" class="btn btn-xs delete-btn" data-toggle="tooltip" title="Xóa">
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
                    @if (count($permissions))
                        {!! $permissions->appends([Request::all()])->links() !!}
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-6 col-sm-12 col-xs-12">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ isset($permission_edit) ? 'Cập nhật' : 'Thêm mới' }} Quyền hạn</h3>
                </div>
                <div class="box-body">
                    <form action="{{ isset($permission_edit) ? route('permissions.update', $permission_edit->id) : route('permissions.store')}}" method="POST" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        @if (isset($permission_edit))
                            <input name="_method" type="hidden" value="PUT">
                        @endif
                        <div class="form-group {{ $errors->first('name') ? 'has-error' : ''}}">
                            <label class="control-label" for="title">Mã quyền hạn <b style="color: red">*</b></label>
                            <input type="text" class="form-control" name="name" id="name" placeholder="Nhập tên quyền hạn" value="{{ old('title', isset($permission_edit) ? $permission_edit->name : '')  }}">
                            <span class="help-block">{{ $errors->first('name') }}</span>
                        </div>
                        <div class="form-group {{ $errors->first('display_name') ? 'has-error' : ''}}">
                            <label class="control-label" for="title">Tên quyền hạn <b style="color: red">*</b></label>
                            <input type="text" class="form-control" name="display_name" id="display_name" placeholder="Nhập tên quyền hạn" value="{{ old('title', isset($permission_edit) ? $permission_edit->display_name : '')  }}">
                            <span class="help-block">{{ $errors->first('display_name') }}</span>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-{{ isset($permission_edit) ? 'warning' : 'success' }} btn-sm">{{ isset($permission_edit) ? 'Cập nhật' : 'Thêm mới' }}</button>
                            @if (isset($permission_edit))
                                <a href="{{ route('permissions.index') }}" class="btn btn-default btn-sm btn-cancel">Hủy</a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
@stop

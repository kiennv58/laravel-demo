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
                    <h3 class="box-title">Danh sách nhân viên</h3>
                    <span class="pull-right">
                        <a href="{{ route('accounts.create') }}" class="btn btn-sm btn-success"><i class="fa fa-plus"></i> Thêm mới</a>
                    </span>
                </div>

                <div class="box-header with-border">
                    <form>
                        <div class="col-md-3 row">
                            <div class="form-group input-group input-group-sm">
                                <input class="form-control .input-sm" type="text" name="q" placeholder="Tìm kiếm tên, số điện thoại..." value="{{ Request::get('q') }}" style="width: 100%">
                                <div class="input-group-btn">
                                    <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2" style="padding-top: 4px; cursor: pointer;">
                            <i id="sort-btn" class="fa fa-sort" aria-hidden="true" data-toggle="tooltip" title="Sắp xếp: Ngày tạo: {{ Request::get('type_sort') == 'asc' ? 'giảm' : 'tăng' }}" ></i>
                        </div>
                    </form>
                </div>
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th width="35">#</th>
                            <th width="60"></th>
                            <th>Tên</th>
                            <th width="100">Chức vụ</th>
                            <th width="100">SĐT</th>
                            <th width="150">Email</th>
                            <th width="100">Lương cơ bản</th>
                            <th width="100" class="text-center">Trạng thái</th>
                            <th width="150" class="text-center">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $key => $staff)
                            <tr>
                                <td class="text-center">{{ ++$key }}</td>
                                <td><img class="reponsive-height" src="{{ $staff->getImage($staff->avatar) }}" width="50px"></td>
                                <td><b>{{ $staff->name }}</b></td>
                                <td>
                                    @foreach ($staff->roles as $role)
                                        <p>{{ $role->display_name }}</p>
                                    @endforeach
                                </td>
                                <td>{{ $staff->phone }}</td>
                                <td>{{ $staff->email }}</td>
                                <td>{{ number_format($staff->basic_salary, 0, '.', ',') }}đ</td>
                                <td class="text-center">
                                    <a class="primary active-btn" href="{{ route('accounts.active', $staff->id) }}" data-toggle="tooltip" title="Click để hủy/kích hoạt"><i class="fa fa-{{ $staff->active ? 'check-square' : 'square-o' }} fa-lg"></i></a>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('accounts.edit', $staff->id) }}" class="btn btn-xs" data-toggle="tooltip" title="Sửa">
                                        <i class="fa fa-pencil fa-lg" aria-hidden="true"></i>
                                    </a>
                                    <a href="#" class="btn btn-xs btn-success fs_margin_right5 btn-reset-password" data-href="{{ route('accounts.reset-password', $staff->id) }}" data-toggle="tooltip" title="Reset mật khẩu" >
                                        <i class="fa fa-undo" aria-hidden="true"></i>
                                    </a>
                                    <a href="#" data-href="{{ route('accounts.destroy', $staff->id) }}" class="text-danger btn btn-xs delete-btn" data-toggle="tooltip" title="Xóa">
                                        <i class="fa fa-times fa-lg" aria-hidden="true"></i>
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
                        @if (count($users))
                            {!! $users->appends(Request::all())->links() !!}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
<script type="text/javascript">
    $('.active-btn').click(function(e) {
        e.preventDefault();
        var $this = $(this);
        $.ajax({
            url : $this.attr('href'),
            type : 'GET',
            dataType : 'json',
            beforeSend: function() {
                $this.html('<i class="fa fa-refresh fa-spin fa-fw fa-lg"></i>');
            },
            success : function(data) {
                if(data.code === 1) {
                    if(data.active) {
                        $this.html('<i class="fa fa-check-square fa-lg"></i>');
                    } else {
                        $this.html('<i class="fa fa-square-o fa-lg"></i>');
                    }
                } else {
                    alert(data.message);
                }
            }
        })
    });

    // Confirm reset password user
    $('.btn-reset-password').click(function(e) {
        e.preventDefault();
        let href  = $(this).attr('data-href');
        let phone = $(this).attr('data-phone');
        swal({
            title: "Reset mật khẩu",
            type: "warning",
            text: 'Bạn có muốn reset mật khẩu tài khoản ' + phone + ' là 123456?',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: "Đồng ý",
            cancelButtonText: "Hủy",
            closeOnConfirm: true
        },
        function(data){
            if (data) {
                window.location.href = href;
            }
        });
    });
</script>
@stop

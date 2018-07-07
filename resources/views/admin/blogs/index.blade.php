@extends('adminlte::page')

@section('title', $title)

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ secure_asset('css/admin.css') }}">
@stop

@section('content')
    <div class="row">
        <div class="col-md-12" id="backend-blog">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">Danh sách Blog</h3>
                    <span class="pull-right">
                        <a href="{{ route('blogs.create') }}" class="btn btn-success"><i class="fa fa-plus"></i> Thêm mới</a>
                    </span>
                </div>

                <table class="table table-striped table-hover table-bordered">
                    <thead>
                        <tr>
                            <th width="35">#</th>
                            <th width="100">Danh mục</th>
                            <th width="300">Ảnh</th>
                            <th width="200">Tiêu đề</th>
                            <th>Mô tả ngắn</th>
                            <th width="100">Tags</th>
                            <th class="text-center">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($blogs as $key => $blog)
                            <tr>
                                <td class="text-center">{{ ++$key }}</td>
                                <td>
                                    {{ count($blog->category) ? $blog->category->name : 'Thiếu dữ liệu' }}
                                </td>
                                <td>
                                    <div class="blog-media">
                                        <img src="{{ $blog->getThumbnail() }}">
                                    </div>
                                </td>
                                <td>
                                    {{ $blog->title }}
                                </td>
                                <td>
                                    {{ str_limit($blog->teaser, $limit = 300, $end = '...') }}
                                </td>
                                <td>
                                    @forelse ($blog->tags as $tag)
                                        <span class="label label-success label-tag">{{ $tag->name }}</span>
                                    @empty
                                        {{-- empty expr --}}
                                    @endforelse
                                </td>
                                <td class="text-center">
                                    <a class="primary active-btn" href="{{ route('blogs.hot', $blog->id) }}" data-toggle="tooltip" title="Click để hủy hot/kích hoạt hot"><i class="fa fa-{{ $blog->active ? 'check-square' : 'square-o' }} fa-lg"></i></a>
                                </td>
                                <td class="text-center">
                                    <a class="primary active-btn" href="{{ route('blogs.active', $blog->id) }}" data-toggle="tooltip" title="Click để hủy/kích hoạt"><i class="fa fa-{{ $blog->active ? 'check-square' : 'square-o' }} fa-lg"></i></a>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('blogs.edit', $blog->id) }}" class="btn btn-xs" data-toggle="tooltip" title="Sửa">
                                        <i class="fa fa-pencil" aria-hidden="true"></i>
                                    </a>
                                    <a href="#" data-href="{{ route('blogs.destroy', $blog->id) }}" class="btn btn-xs btn-destroy label-cancel" data-toggle="tooltip" title="Xóa">
                                        <i class="fa fa-times" aria-hidden="true"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">Không có dữ liệu !</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="box-footer">
                    <div class="pull-right">
                        @if (count($blogs))
                            {!! $blogs->appends([Request::all()])->links() !!}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@section('js')
<script type="text/javascript">
    clickActive('.active-btn');

    function clickActive(id) {
        $(id).click(function(e) {
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
    }
</script>
@stop

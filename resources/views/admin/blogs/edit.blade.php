@extends('adminlte::page')

@section('title', $title)

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ secure_asset('css/bootstrap-fileupload.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ secure_asset('css/summernote.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ secure_asset('css/admin.css') }}">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css">
    <style type="text/css">
        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: #00A65A;
            border: 1px solid #00a65a;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            color: #fff;
        }
    </style>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12 admin-blog-create">
            <div class="box box-success">
                <form action="{{ route('blogs.update', $blog->id) }}" method="POST" enctype="multipart/form-data">
                    <div class="box-header with-border">
                        <h3 class="box-title">Thêm mới Blog</h3>
                        <span class="pull-right">
                            <button type="submit" class="btn btn-warning"><i class="fa fa-floppy-o" aria-hidden="true"></i> Cập nhật</button>
                            <a href="{{ route('blogs.index') }}" class="btn btn-default"><i class="fa fa-long-arrow-left"></i> Trờ lại</a>
                        </span>
                    </div>
                    {{ csrf_field() }}
                    {{ method_field('PUT') }}
                    <div class="box-body">
                        <div class="col-md-5">
                            <div class="fileupload fileupload-new" data-provides="fileupload">
                                <div class="fileupload-new thumbnail" style="width: 400px; height: 235px; margin-top: 18px;">
                                    @if ($blog)
                                        <img src="{{ $blog->getThumbnail() }}" alt="" />
                                    @else
                                        <img src="http://www.placehold.it/400x235/EFEFEF/AAAAAA&amp;text=Chọn ảnh" alt="" />
                                    @endif
                                </div>
                                <div class="fileupload-preview fileupload-exists thumbnail" style="width: 400px; height: 235px;margin-top: 18px;""></div>
                                <div>
                                    <span class="btn btn-white btn-file">
                                        <span class="fileupload-new fix-new"><i class="fa fa-paper-clip"></i> Chọn ảnh</span>
                                        <span class="fileupload-exists"><i class="fa fa-undo"></i> Đổi ảnh</span>
                                        <input type="file" name="image" class="default" />
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-7">
                            <div class="form-group {{ $errors->first('category_id') ? 'has-error' : '' }}">
                                <label for="">Chọn danh mục <b style="color: red">*</b></label>
                                <select name="category_id" class="form-control">
                                    @foreach($categories as $key => $category)
                                        <option value="{{ $key }}" {{ $blog->category_id == $key ? 'selected' : '' }}>{{ $category }}</option>
                                    @endforeach
                                </select>
                                <span class="help-block">{{ $errors->first('category_id') }}</span>
                            </div>
                            <div class="form-group {{ $errors->first('title') ? 'has-error' : '' }}">
                                <label for="">Tiêu đề <b style="color: red">*</b></label>
                                <input type="text" class="form-control" name="title" placeholder="Tiêu đề" value="{{ Request::old('title', $blog->title) }}">
                                <span class="help-block">{{ $errors->first('title') }}</span>
                            </div>
                            <div class="form-group {{ $errors->first('teaser') ? 'has-error' : '' }}">
                                <label for="">Giới thiệu ngắn <b style="color: red">*</b></label>
                                <textarea class="form-control" name="teaser" rows="4" placeholder="Giới thiệu ngắn">{{ old('teaser', $blog->teaser) }}</textarea>
                                <span class="help-block">{{ $errors->first('teaser') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="col-md-12 {{ $errors->first('content') ? 'has-error' : '' }}">
                            <label for="">Nội dung <b style="color: red">*</b></label>
                            <textarea id="summernote" name="content">{{ Request::old('content', $blog->content) }}</textarea>
                            <span class="help-block">{{ $errors->first('content') }}</span>

                            <div class="form-group">
                                <label for="">Tags</label>
                                <select multiple="true" name="tags[]" id="tags_blog" class="form-control select2" value="">
                                    @if (Request::old('tags', $tags_old))
                                       @foreach (Request::old('tags', $tags_old) as $tag)
                                           <option value="{{ $tag }}" selected>{{ isset($allTags[$tag]) ? $allTags[$tag] : $tag }}</option>
                                       @endforeach
                                    @endif
                                </select>
                            </div>

                            <div class="form-group">
                                <input type="checkbox" id="hot" class="flat-red" name="hot" value="1" {{ Request::old('hot', $blog->hot == 1) ? 'checked' : '' }}>
                                <label for="hot">Hot</label>
                            </div>
                        </div>
                        <input type="hidden" name="" id="url_upload_blog" data-url="{{ route('blogs.upload') }}">
                        <input type="hidden" name="_token" id="sad" value="{{ csrf_token() }}" content="{{ csrf_token() }}">
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
    <script src="{{ secure_asset('js/bootstrap-fileupload.js') }}" ></script>
    <script src="{{ secure_asset('js/summernote.js') }}" ></script>
    <script src="{{ secure_asset('js/summernote-vi-VN.js') }}" ></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.full.min.js" ></script>
    <script type="text/javascript">

        var data = [
            @if (count($allTags))
                @foreach ($allTags as $key => $tag_text)
                    { id: '{{ $key }}', text: '{{ $tag_text }}' },
                @endforeach
            @endif
        ];
        $("#tags_blog").select2({
            tags: true,
            tokenSeparators: [","],
            data: data
        });

        var url_upload = $('#url_upload_blog').data('url');
        jQuery(document).ready(function($) {
            console.log(url_upload);
            $('#summernote').summernote({
                minHeight: 300,
                lang: 'vi-VN',
                placeholder: 'Nhập nội dung Blog',
                callbacks: {
                    onImageUpload: function(files, editor, welEditable) {
                        sendFile(files[0],editor,welEditable);
                    }
                  }
            });
        });

        function sendFile(file,editor,welEditable) {
            data = new FormData();
            data.append("file", file);
            $.ajaxSetup({
                headers: { 'X-CSRF-Token': $('input[name="_token"]').val() }
            });
            $.ajax({
                data: data,
                type: "POST",
                url: url_upload,
                cache: false,
                contentType: false,
                processData: false,
                success: function(url) {
                    $('#summernote').summernote('insertImage', url['data'].path);
                },
                error: function(data) {
                   sweetAlert("Lỗi .. ", data.responseJSON.file[0], "error");
                }
            });
        }

        $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
          checkboxClass: 'icheckbox_flat-green'
        });
    </script>
@stop

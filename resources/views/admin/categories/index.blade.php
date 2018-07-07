@extends('adminlte::page')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ secure_asset('css/admin.css') }}">
    <style type="text/css" media="screen">
        .list-nullstyled {
            list-style: none;
        }
        .tree, .tree ul {
            margin:0;
            padding:0;
            list-style:none;
        }
        .tree ul {
            margin-left:1em;
            position:relative;
        }
        .tree ul ul {
            margin-left:.5em;
        }
        .tree ul:before {
            content:"";
            display:block;
            width:0;
            position:absolute;
            top:0;
            bottom:0;
            left:0;
            border-left:1px solid;
        }
        .tree li {
            margin:0;
            padding:0 1em;
            line-height:2em;
            color:#38B4C9;
            font-weight:700;
            position:relative;margin-bottom;
            cursor: pointer;
        }
        .tree ul li:before {
            content:"";
            display:block;
            width:10px;
            height:0;
            border-top:1px solid;
            margin-top:-1px;
            position:absolute;
            top:1em;
            left:0;
        }
        .tree ul li:last-child:before {
            background:#fff;
            height:auto;
            top:1em;
            bottom:0;
        }
/*        .tree li .bbc_tree__title {
            display: inline-block;
            width: 95%;
            float: right;
            cursor: pointer;
        }*/
        .indicator {
            margin-right:5px;
        }
        .tree li a {
            text-decoration: none;
            color:#38B4C9;
        }
        .tree li button, .tree li button:active, .tree li button:focus {
            text-decoration: none;
            /*color:#38B4C9;*/
            border:none;
            background:transparent;
            margin:0px 0px 0px 0px;
            padding:0px 0px 0px 0px;
            outline: 0;
            margin-right: 5px;
        }
        .box-body .btn-cancel {
            margin-left: 10px;
        }
    </style>
@stop

@section('title', $title)

@section('content_header')
@stop

@section('content')
    <div class="row">
        <div class="col-md-6 col-sm-12 col-xs-12">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">Danh sách chuyên mục</h3>
                </div>

                <div class="box-body">
                    <ul id="bbc-tree--categories" class="list-nullstyled">
                        @foreach($categories as $category)
                            <li>
                                <span class="bbc_tree__title">
                                    {{ $category->name }}
                                </span>
                                <button type="button" data-href='{{ route('categories.destroy', $category->id) }}'" class="btn btn-box-tool pull-right btn-detroy label-cancel" data-toggle="tooltip" title="" data-original-title="Xóa">
                                    <i class="fa fa-times"></i>
                                </button>
                                <button type="button" onclick="location.href='{{ route('categories.edit', $category->id) }}'" class="btn btn-box-tool pull-right label-accept" data-toggle="tooltip" title="" data-original-title="Sửa">
                                    <i class="fa fa-pencil"></i>
                                </button>

                                @if(count($category->childs))
                                    @include($viewPath . 'tree', ['childs' => $category->childs])
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

        </div>

        <div class="col-md-6 col-sm-12 col-xs-12">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ isset($category_edit) ? 'Cập nhật' : 'Thêm mới' }} chuyên mục</h3>
                </div>
                <div class="box-body">
                    <form action="{{ isset($category_edit) ? route('categories.update', $category_edit->id) : route('categories.store')}}" method="POST" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        @if (isset($category_edit))
                            {{ method_field('PUT') }}
                        @endif
                        <div class="form-group {{ $errors->first('name') ? 'has-error' : ''}}">
                          <label class="control-label" for="title">Tên chuyên mục  <b style="color: red">*</b></label>
                          <input type="text" class="form-control" name="name" id="name" placeholder="Nhập tên chuyên mục" value="{{ old('title', isset($category_edit) ? $category_edit->name : '')  }}">
                          <span class="help-block">{{ $errors->first('name') }}</span>
                        </div>

                        <div class="form-group {{ $errors->first('parent_id') ? 'has-error' : '' }}">
                          <label>Chuyên mục</label>
                          <select name="parent_id" class="form-control">
                            @foreach($allcategories as $key => $category1)
                                <option value="{{ $key }}" {{ isset($category_edit) ? ($key == $category_edit->parent_id ? 'selected' : '') : ''}} >{{ $category1 }}</option>
                            @endforeach
                          </select>
                          <span class="help-block">{{ $errors->first('parent_id') }}</span>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-{{ isset($category_edit) ? 'warning' : 'success' }}">{{ isset($category_edit) ? 'Cập nhật' : 'Thêm mới' }}</button>
                            @if (isset($category_edit))
                                <a href="{{ route('categories.index') }}" class="btn btn-default btn-cancel">Hủy</a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
    <script type="text/javascript" charset="utf-8">
        $.fn.extend({
            treed: function (o) {

              var openedClass = 'glyphicon-minus-sign';
              var closedClass = 'glyphicon-plus-sign';

              if (typeof o != 'undefined'){
                if (typeof o.openedClass != 'undefined'){
                    openedClass = o.openedClass;
                }
                if (typeof o.closedClass != 'undefined'){
                    closedClass = o.closedClass;
                }
              };

                //initialize each of the top levels
                var tree = $(this);
                tree.addClass("tree");
                tree.find('li').has("ul").each(function () {
                    var branch = $(this); //li with children ul
                    branch.prepend("<i class='indicator glyphicon " + closedClass + "'></i>");
                    branch.addClass('branch');
                    branch.on('click', function (e) {
                        if (this == e.target) {
                            var icon = $(this).children('i:first');
                            icon.toggleClass(openedClass + " " + closedClass);
                            $(this).children().children().toggle();
                        }
                    })
                    branch.children().children().toggle();
                });
                //fire event from the dynamically added icon
              tree.find('.branch .indicator').each(function(){
                $(this).on('click', function () {
                    $(this).closest('li').click();
                });
              });
                //fire event to open branch if the li contains an anchor instead of text
                tree.find('.branch > a').each(function () {
                    $(this).on('click', function (e) {
                        $(this).closest('li').click();
                        e.preventDefault();
                    });
                });
                //fire event to open branch if the li contains a button instead of text
                tree.find('.branch > button').each(function () {
                    $(this).on('click', function (e) {
                        $(this).closest('li').click();
                        e.preventDefault();
                    });
                });
            }
        });

        //Initialization of treeviews
        //
        $('#bbc-tree--categories').treed();
    </script>
@stop

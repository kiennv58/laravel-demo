@extends('adminlte::page')

@section('title', $title)

@section('content')
    <div class="row">
        <div class="col-md-12 admin-blog-create">
            <div class="box box-success">
                <form action="{{ route('orders.update', $order->id) }}" method="POST" enctype="multipart/form-data">
                    <div class="box-header with-border">
                        <h3 class="box-title">Chỉnh sửa đơn hàng</h3>
                        <span class="pull-right">
                            <button type="submit" class="btn btn-success"><i class="fa fa-floppy-o" aria-hidden="true"></i> Lưu lại</button>
                            <a href="{{ route('orders.index') }}" class="btn btn-default"><i class="fa fa-long-arrow-left"></i> Trở lại</a>
                        </span>
                    </div>
                    {{ csrf_field() }}
                    {{ method_field('PUT') }}
                    <div class="box-body">
                        <div class="col-md-6">
                            <div id="customer-info">
                                <div id="new-customer">
                                    <div class="form-group">
                                        <label for="">Tên khách hàng</label>
                                        <input type="text" class="form-control" name="name" placeholder="Nhập tên khách hàng" value="{{ $order->customer->name }}" disabled>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Số điện thoại</label>
                                        <input type="text" class="form-control" name="phone" placeholder="Nhập số điện thoại" value="{{ $order->customer->phone }}" disabled>
                                    </div>
                                    <div class="form-group {{ $errors->first('address') ? 'has-error' : '' }}">
                                        <label for="">Địa chỉ</label>
                                        <input type="text" class="form-control" name="address" placeholder="Nhập địa chỉ" value="{{ $order->customer->address }}" disabled>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div id="customer-info">
                                <div id="old-product">
                                    <div class="form-group {{ $errors->first('product_id') ? 'has-error' : '' }}">
                                        <label for="name">Tên sản phẩm</label>
                                        <select name="product_id" id="product_name" class="form-control select2" value="">
                                            <option></option>
                                            @foreach ($products as $product)
                                            <option value="{{ $product->id }}" title="{{ $product->type }}" {{ $order->order_details->first()->product_id == $product->id ? 'selected' : '' }}>{{ $product->name }}</option>
                                            @endforeach
                                        </select>
                                        <span class="help-block">{{ $errors->first('product_id') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12" id="product_type_2" style="display: none;">
                            <label class="col-md-12 row">Gia công</label>
                            <div class="col-md-3 row">
                                <div class="form-group {{ $errors->first('tape') ? 'has-error' : '' }}">
                                    <select name="tape" class="form-control">
                                        <option value="0" {{ $order->order_details->first()->tape == 0 ? 'selected' : '' }}>Chọn loại băng dính</option>
                                        <option value="1" {{ $order->order_details->first()->tape == 1 ? 'selected' : '' }}>Băng dính xi</option>
                                        <option value="2" {{ $order->order_details->first()->tape == 2 ? 'selected' : '' }}>Băng dính trơn</option>
                                    </select>
                                    <span class="help-block">{{ $errors->first('tape') }}</span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group {{ $errors->first('can') ? 'has-error' : '' }}">
                                    <select name="can" class="form-control">
                                        <option value="0" {{ $order->order_details->first()->can == 0 ? 'selected' : '' }}>Không cán</option>
                                        <option value="1" {{ $order->order_details->first()->can == 1 ? 'selected' : '' }}>Có cán</option>
                                    </select>
                                    <span class="help-block">{{ $errors->first('can') }}</span>
                                </div>
                            </div>
                            <div class="col-md-3 row">
                                <div class="form-group {{ $errors->first('spine') ? 'has-error' : '' }}">
                                    <select name="spine" class="form-control">
                                        <option value="0" selected>Chọn loại gáy sách</option>
                                        @foreach ($spines as $index => $spine)
                                        <option value="{{ $index }}" {{ $order->order_details->first()->spine == $index ? 'selected' : '' }}>{{ $spine }}</option>
                                        @endforeach
                                    </select>
                                    <span class="help-block">{{ $errors->first('spine') }}</span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group {{ $errors->first('glue') ? 'has-error' : '' }}">
                                    <select name="glue" class="form-control">
                                        <option value="0" {{ $order->order_details->first()->glue == 0 ? 'selected' : '' }}>Không dùng keo nhiệt</option>
                                        <option value="1" {{ $order->order_details->first()->glue == 1 ? 'selected' : '' }}>Dùng keo nhiệt</option>
                                    </select>
                                    <span class="help-block">{{ $errors->first('glue') }}</span>
                                </div>
                            </div>
                            <div class="col-md-3 row">
                                <div class="form-group {{ $errors->first('odd_page') ? 'has-error' : '' }}">
                                    <select name="odd_page" class="form-control">
                                        <option value="0" {{ $order->order_details->first()->odd_page == 0 ? 'selected' : '' }}>In 2 mặt</option>
                                        <option value="1" {{ $order->order_details->first()->odd_page == 1 ? 'selected' : '' }}>In 1 mặt</option>
                                    </select>
                                    <span class="help-block">{{ $errors->first('odd_page') }}</span>
                                </div>
                            </div>
                            <div class="col-md-12 row">
                                <label for="">Bìa</label>
                            </div>
                            <div class="col-md-3 row">
                                <div class="form-group">
                                    <select name="cover_color" class="form-control">
                                        <option value="0" selected>In đen trắng</option>
                                        @foreach ($cover_colors as $index => $cover_color)
                                        <option value="{{ $index }}" {{ $order->order_details->first()->cover_color == $index ? 'selected' : '' }}>{{ $cover_color }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <select name="plastic" class="form-control">
                                        <option value="0">Không bóng kính</option>
                                        @foreach ($plastics as $index => $plastic)
                                        <option value="{{ $index }}" {{ $order->order_details->first()->plastic == $index ? 'selected' : '' }}>{{ $plastic }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 row">
                                <div class="form-group">
                                    <select name="cover_type" class="form-control">
                                        <option value="0">Không bìa ngoại</option>
                                        @foreach ($cover_types as $index => $cover_type)
                                        <option value="{{ $index }}" {{ $order->order_details->first()->cover_type == $index ? 'selected' : '' }}>{{ $cover_type }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <select name="can" class="form-control">
                                        <option value="0" {{ $order->order_details->first()->can == 0 ? 'selected' : '' }}>Không cán</option>
                                        <option value="1" {{ $order->order_details->first()->can == 1 ? 'selected' : '' }}>Có cán</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group {{ $errors->first('quantity') ? 'has-error' : '' }}">
                                <label for="">Số lượng (hoặc số trang)</label>
                                <input type="text" class="form-control" name="quantity" placeholder="Nhập số trang hoặc số lượng sản phẩm" value="{{ $order->order_details->first()->quantity }}">
                                <span class="help-block">{{ $errors->first('quantity') }}</span>
                            </div>
                            <div class="form-group col-md-4 row {{ $errors->first('size') ? 'has-error' : '' }}">
                                <label for="size">Kích thước</label>
                                <input type="text" class="form-control" name="size" placeholder="Nhập kích thước" value="{{ $order->order_details->first()->size }}" list="list_size">
                                <datalist id="list_size">
                                    <option value="A0"></option>
                                    <option value="A1"></option>
                                    <option value="A2"></option>
                                    <option value="A3"></option>
                                    <option value="A4"></option>
                                    <option value="A5"></option>
                                </datalist>
                                <span class="help-block">{{ $errors->first('size') }}</span>
                            </div>
                            <div class="form-group col-md-4" style="padding-bottom: 10px;">
                                <label for="color">In màu</label>
                                <select name="color" class="form-control">
                                    <option value="0" {{ $order->order_details->first()->color == 0 ? 'selected' : '' }}>In đen trắng</option>
                                    <option value="1" {{ $order->order_details->first()->color == 1 ? 'selected' : '' }}>In màu</option>
                                </select>
                            </div>
                            <div class="form-group col-md-4 row  {{ $errors->first('kpi') ? 'has-error' : '' }}">
                                <label for="">Chiết khấu đơn hàng</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="kpi" placeholder="VD: 30 (tương đương 30%)" value="{{ $order->kpi * 100 }}">
                                    <span class="input-group-addon">%</span>
                                </div>
                                <span class="help-block">{{ $errors->first('kpi') }}</span>
                            </div>
                            <div class="form-group col-md-4 row {{ $errors->first('price') ? 'has-error' : '' }}">
                                <label for="">Giá</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="price" placeholder="Nhập giá tiền" value="{{ $order->order_details->first()->price }}">
                                    <span class="input-group-addon">đ</span>
                                </div>
                                <span class="help-block">{{ $errors->first('price') }}</span>
                            </div>
                            <div class="form-group col-md-4 {{ $errors->first('deadline') ? 'has-error' : '' }}">
                                <label for="">Thời gian hẹn trả khách</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="deadline" name="deadline" placeholder="Chọn thời gian" value="{{ $order->deadline }}">
                                    <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                                </div>
                                <span class="help-block">{{ $errors->first('deadline') }}</span>
                            </div>
                            <div class="form-group col-md-4 row order-status">
                                <label>Trạng thái</label>
                                <select class="form-control" name="status">
                                    @foreach ($order::STATUS_LIST as $index => $status)
                                        <option value="{{ $index }}" class="{{ strtolower($status) }}" {{ $order->status == $index ? 'selected' : '' }}><b>{{ $status }}</b></option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <button type="submit" class="btn btn-success"><i class="fa fa-floppy-o" aria-hidden="true"></i> Lưu lại</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('js')</script>
    <script src="{{ asset('js/cleave.min.js') }}" ></script>
    <script type="text/javascript">
        $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
          checkboxClass: 'icheckbox_flat-green'
        });
        jQuery.datetimepicker.setLocale('vi');
        $('#deadline').datetimepicker({
            validateOnBlur: true,
            format: 'Y-m-d H:i'
        });
        var cleave = new Cleave('input[name=price]', {
            numeral: true,
            numeralThousandsGroupStyle: 'thousand'
        });

        $("#product_name").select2({
            placeholder: 'Chọn sản phẩm',
            language: 'vn'
        });
        $("#product_name").on('select2:select', function (e) {
            let data = e.params.data;
            if (data.title == 2) {
                $("#product_type_2").slideDown();
            } else {
                $("#product_type_2").slideUp();
            }
            
        })
    </script>
@stop

@section('css')
<style type="text/css">
    #customer-info {
        border: 1px dashed #ddd;
        margin-bottom: 20px;
        padding: 10px;
        overflow: hidden;
    }
    .order-status .design {
        color: #7bc1f7 !important
    }
    .order-status .produce {
        color: #1D8CE0 !important
    }
    .order-status .shipping {
        color: #e67e22 !important;
    }
    .order-status .finish {
        color: #0EBC0E !important;
    }
</style>
@stop

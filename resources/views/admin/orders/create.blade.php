@extends('adminlte::page')

@section('title', $title)

@section('content')
    <div class="row">
        <div class="col-md-12 admin-blog-create">
            <div class="box-header with-border">
                <h3 class="box-title">Thêm mới đơn hàng</h3>
                <span class="pull-right">
                    <button type="submit" class="btn btn-success"><i class="fa fa-floppy-o" aria-hidden="true"></i> Thêm mới</button>
                    <a href="{{ route('orders.index') }}" class="btn btn-default"><i class="fa fa-long-arrow-left"></i> Trở lại</a>
                </span>
            </div>
            
            <form action="{{ route('orders.store') }}" method="POST" enctype="multipart/form-data">
                {{ csrf_field() }}
                
                <div class="col-md-4">
                    <div class="box box-solid">
                        <div class="box-header  with-border">
                            <p class="box-title">In sách keo nhiệt</p>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                            </div>
                        </div>
                        <div class="box-body">
                            
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="box box-solid">
                        <div class="box-header  with-border">
                            <p class="box-title">In sách keo nhiệt</p>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                            </div>
                        </div>
                        <div class="box-body">
                            
                        </div>
                    </div>
                </div>
                
                <input type="hidden" id="count-list-product-more" name="count-list-product-more" value="{{ Request::old('count-list-product-more', 1) }}">
                
                <div id="list-product">
                    <div class="col-md-12">
                        <div class="box box-solid">
                            <div class="box-header with-border">
                                <h4>Sản phẩm</h4>
                                <div class="box-tools pull-right">
                                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="box-body">
                                <div class="col-md-12">
                                    <div id="old-product">
                                        <div class="form-group {{ $errors->first('product_id') ? 'has-error' : '' }}">
                                            <label for="name">Tên sản phẩm</label>
                                            <select name="product_id" id="product_name" class="form-control select2" value="">
                                                <option></option>
                                                @foreach ($products as $product)
                                                <option value="{{ $product->id }}" title="{{ $product->type }}">{{ $product->name }}</option>
                                                @endforeach
                                            </select>
                                            <span class="help-block">{{ $errors->first('product_id') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div id="product_type_2" style="display: none;">
                                    <label class="col-md-12">Gia công</label>
                                    <div class="col-md-3">
                                        <div class="form-group {{ $errors->first('tape') ? 'has-error' : '' }}">
                                            <select name="tape" class="form-control">
                                                <option value="0" selected>Chọn loại băng dính</option>
                                                <option value="1">Băng dính xi</option>
                                                <option value="2">Băng dính trơn</option>
                                            </select>
                                            <span class="help-block">{{ $errors->first('tape') }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group {{ $errors->first('can') ? 'has-error' : '' }}">
                                            <select name="can" class="form-control">
                                                <option value="0" selected>Không cán</option>
                                                <option value="1">Có cán</option>
                                            </select>
                                            <span class="help-block">{{ $errors->first('can') }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group {{ $errors->first('spine') ? 'has-error' : '' }}">
                                            <select name="spine" class="form-control">
                                                <option value="0" selected>Chọn loại gáy sách</option>
                                                @foreach ($spines as $index => $spine)
                                                <option value="{{ $index }}">{{ $spine }}</option>
                                                @endforeach
                                            </select>
                                            <span class="help-block">{{ $errors->first('spine') }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group {{ $errors->first('glue') ? 'has-error' : '' }}">
                                            <select name="glue" class="form-control">
                                                <option value="0" selected>Không dùng keo nhiệt</option>
                                                <option value="1">Dùng keo nhiệt</option>
                                            </select>
                                            <span class="help-block">{{ $errors->first('glue') }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group {{ $errors->first('add_page') ? 'has-error' : '' }}">
                                            <select name="add_page" class="form-control">
                                                <option value="0" selected>In 2 mặt</option>
                                                <option value="1">In 1 mặt</option>
                                            </select>
                                            <span class="help-block">{{ $errors->first('add_page') }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <label for="">Bìa</label>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <select name="cover_color" class="form-control">
                                                <option value="0" selected>In đen trắng</option>
                                                @foreach ($cover_colors as $index => $cover_color)
                                                <option value="{{ $index }}">{{ $cover_color }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <select name="plastic" class="form-control">
                                                <option value="0">Không bóng kính</option>
                                                @foreach ($plastics as $index => $plastic)
                                                <option value="{{ $index }}">{{ $plastic }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <select name="cover_type" class="form-control">
                                                <option value="0">Không bìa ngoại</option>
                                                @foreach ($cover_types as $index => $cover_type)
                                                <option value="{{ $index }}">{{ $cover_type }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <select name="can" class="form-control">
                                                <option value="0">Không cán</option>
                                                <option value="1">Có cán</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <div class="form-group col-md-3 {{ $errors->first('quantity') ? 'has-error' : '' }}">
                                        <label for="">Số lượng (hoặc số trang)</label>
                                        <input type="text" class="form-control" name="quantity" placeholder="Nhập số trang hoặc số lượng sản phẩm" value="{{ old('quantity') }}">
                                        <span class="help-block">{{ $errors->first('quantity') }}</span>
                                    </div>
                                    <div class="form-group col-md-3 {{ $errors->first('size') ? 'has-error' : '' }}">
                                        <label for="size">Kích thước</label>
                                        <input type="text" class="form-control" name="size" placeholder="Nhập kích thước" value="{{ old('size') }}" list="list_size">
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
                                    <div class="form-group col-md-3" style="padding-bottom: 10px;">
                                        <label for="color">In màu</label>
                                        <select name="color" class="form-control">
                                            <option value="0" selected>In đen trắng</option>
                                            <option value="1">In màu</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3 {{ $errors->first('price') ? 'has-error' : '' }}">
                                        <label for="">Giá</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="price" placeholder="Nhập giá tiền" value="{{ old('price') }}">
                                            <span class="input-group-addon">đ</span>
                                        </div>
                                        <span class="help-block">{{ $errors->first('price') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="box box-solid" id="product-more">
                        <div class="box-header">
                            <h3 class="box-title">Thêm sản phẩm</h3>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-default btn-sm btn-add"><i class="fa fa-plus-circle"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="box box-solid">
                        <div class="box-header with-border">
                            <h4>Thông tin đơn hàng</h4>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="box-body">
                            <div>
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox"  name="new_customer" value="1"> <b>Khách hàng mới</b>
                                    </label>
                                </div>
                                <div id="old-customer">
                                    <div class="form-group {{ $errors->first('name') ? 'has-error' : '' }}">
                                        <label for="name">Tên khách hàng</label>
                                        <select name="name" id="customer_name" class="form-control select2" value="">
                                        </select>
                                        <input type="hidden" id="user_id" value="" name="customer_id">
                                        <span class="help-block">{{ $errors->first('name') }}</span>
                                    </div>
                                    <div class="customer-info-search" style="display: none;">
                                        <p><span id="phone-search"></span></p>
                                        <p><span id="address-search"></span></p>
                                    </div>
                                </div>
                                <div id="new-customer" style="display: none;">
                                    <div class="form-group {{ $errors->first('phone') ? 'has-error' : '' }}">
                                        <label for="">Tên khách hàng</label>
                                        <input type="text" class="form-control" name="name" placeholder="Nhập tên khách hàng" value="{{ old('name') }}">
                                        <span class="help-block">{{ $errors->first('name') }}</span>
                                    </div>
                                    <div class="form-group {{ $errors->first('phone') ? 'has-error' : '' }}">
                                        <label for="">Số điện thoại</label>
                                        <input type="text" class="form-control" name="phone" placeholder="Nhập số điện thoại" value="{{ old('phone') }}">
                                        <span class="help-block">{{ $errors->first('phone') }}</span>
                                    </div>
                                    <div class="form-group {{ $errors->first('address') ? 'has-error' : '' }}">
                                        <label for="">Địa chỉ</label>
                                        <input type="text" class="form-control" name="address" placeholder="Nhập địa chỉ" value="{{ old('address') }}">
                                        <span class="help-block">{{ $errors->first('address') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="order-info">
                                <div class="form-group col-md-4 row {{ $errors->first('kpi') ? 'has-error' : '' }}">
                                    <label for="">Chiết khấu đơn hàng</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="kpi" placeholder="VD: 30 (tương đương 30%)" value="{{ old('kpi') }}">
                                        <span class="input-group-addon">%</span>
                                    </div>
                                    <span class="help-block">{{ $errors->first('kpi') }}</span>
                                </div>
                                <div class="form-group col-md-4 {{ $errors->first('deadline') ? 'has-error' : '' }}">
                                    <label for="">Thời gian hẹn trả khách</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="deadline" name="deadline" placeholder="Chọn thời gian" value="{{ old('deadline') }}">
                                        <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                                    </div>
                                    <span class="help-block">{{ $errors->first('deadline') }}</span>
                                </div>
                                <div class="form-group col-md-4 order-status">
                                    <label>Trạng thái</label>
                                    <select class="form-control" name="status">
                                        @foreach ($status_list as $index => $status)
                                            <option value="{{ $index }}" class="{{ strtolower($status) }}"><b>{{ $status }}</b></option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-12">
                    <button type="submit" class="btn btn-success"><i class="fa fa-floppy-o" aria-hidden="true"></i> Thêm mới</button>
                </div>
            </form>
        </div>
    </div>
@stop

@section('js')
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
        $('input[name="new_customer"').click(function(){
            $('.customer-info-search').slideUp();
            $('#new-customer').slideToggle(500);
            $('#old-customer').slideToggle(500)
        });

        $("#customer_name").select2({
          ajax: {
            url: '{{ route('customers.search') }}',
            dataType: 'json',
            delay: 250,
            data: function (params) {
              return {
                q: params.term, // search term
                page: params.page
              };
            },
            processResults: function (data, params) {
              params.page = params.page || 1;

              return {
                results: data.data,
                pagination: {
                  more: (params.page * 30) < data.total
                }
              };
            },
            cache: true
          },
          placeholder: 'Nhập tên khách hàng...',
          escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
          minimumInputLength: 1,
          templateResult: formatCustomer,
          templateSelection: formatCusSelection
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

        function formatCustomer (customer) {
          var markup = "<div class='select2-result-repository__meta'>" +
              "<div class='select2-result-repository__title'><b>" + customer.name + "</b></div>";
          if (customer.phone) {
            markup += "<div class='select2-result-repository__description'>" + customer.phone + "</div>";
          }
          if (customer.address) {
            markup += "<div class='select2-result-repository__description'>" + customer.address + "</div>";
          }

          return markup;
        }

        function formatCusSelection (customer) {
          $('#user_id').val(customer.id);
          $('#phone-search').text(customer.phone);
          $('#address-search').text(customer.address);
          $('.customer-info-search').slideDown();
          return customer.name;
        }
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
    .box-header h4 {
        margin-top: 0px;
        margin-bottom: 0px;
        font-size: 16px;
    }
    #product-more .box-header {
        border: 1px solid #a9ddff;
        cursor: pointer;
    }
    #product-more .box-title {
        color: #2980b9;
    }
    #product-more .btn-add {
        color: #2980b9;
    }
    #product-more .btn-add i {
        font-size: 22px;
    }
</style>
@stop

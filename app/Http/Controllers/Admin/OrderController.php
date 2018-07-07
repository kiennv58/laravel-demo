<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\AdminController;
use App\Repositories\Orders\OrderRepository;
use App\Repositories\OrderDetails\OrderDetailRepository;
use App\Repositories\Products\ProductRepository;
use App\Repositories\Customers\CustomerRepository;
use App\Helpers\OrderConstant;
use App\Repositories\Orders\Order;
use DB;
use Auth;
use App\Helpers\PushNotify;

class OrderController extends AdminController
{
    private $viewPath = 'admin.orders.';
    private $title    = 'Đơn hàng';

    protected $customer;

    protected $rules = [
        'status'	 => 'required',
        'kpi'		 => 'required|numeric',
        'product_id' => 'required',
        'quantity' => 'required',
        'price' => 'required',
        'size' => 'required',
        'deadline' => 'required'
    ];

    protected $messages = [
        'status.required' => 'Chưa chọn trạng thái',
        'kpi.required'	  => 'Chiết khấu không được để trống',
        'kpi.numeric'     => 'Chiết khấu chỉ chứa chữ số',
        'product_id.required'    => 'Chưa chọn sản phẩm',
        'quantity.required'    => 'Chưa nhập số lượng',
        'price.required'    => 'Chưa nhập giá',
        'size.required'    => 'Chưa nhập số lượng',
        'deadline.required'    => 'Chưa nhập thời gian'
    ];

    function __construct(OrderRepository $order, ProductRepository $product, CustomerRepository $customer, OrderDetailRepository $order_detail, Order $model, PushNotify $notify)
    {
        $this->order = $order;
        $this->product = $product;
        $this->customer = $customer;
        $this->order_detail = $order_detail;
        $this->model = $model;
        $this->notify = $notify;
        $this->checkPermission('order');
    }

    public function index(Request $request)
    {
        return view($this->viewPath . 'index')->with([
            'title' => ' Danh sách ' . $this->title,
            'orders' => $this->order->getByParam([], 10)
        ]);
    }

    public function create()
    {
        $spines = OrderConstant::SPINE;
        $cover_types = OrderConstant::COVER_TYPE;
        $cover_colors = OrderConstant::COVER_COLOR;
        $plastics = OrderConstant::PLASTIC;
        $odd_pages = OrderConstant::ODD_PAGE;
        $status_list = array_except($this->model::STATUS_LIST, [6]);
        $products = $this->product->getByParam([], 100);

        return view($this->viewPath . 'create')->with([
            'title'         => 'Tạo mới ' . $this->title,
            'spines'        => $spines,
            'cover_types'   => $cover_types,
            'plastics'      => $plastics,
            'odd_pages'     => $odd_pages,
            'products'      => $products,
            'cover_colors'  => $cover_colors,
            'status_list'   => $status_list
        ]);
    }

    public function store(Request $request)
    {
        try {
            $this->validate($request, $this->rules, $this->messages);
            $params = $request->all();
            $params['staff_id'] = Auth::user()->id;
            $params['kpi'] = $params['kpi'] / 100;
            $params['price'] = str_replace('.', '', $params['price']);
            $customer;
            DB::beginTransaction();
            if (array_key_exists('new_customer', $params) && $params['new_customer']) {
                $customer = $this->customer->store($params);
                $params['customer_id'] = $customer->id;
            } else {
                $customer = $this->customer->getById($params['customer_id']);
            }
            if ($order = $this->order->store($params)) {
                $params['order_id'] = $order->id;
                $this->order_detail->store($params);
                DB::commit();
                $this->notify->push([
                    'title' => 'Đơn mới tạo',
                    'body'  => 'Bởi ' . Auth::user()->name,
                    'link'  => route('orders.show', $order->id)
                ]);
                notify()->flash('Thêm mới ' . $this->title . ' thành công', 'success', [
                    'timer' => 1500]);
                return redirect()->route('orders.index');
            } else {
                DB::rollBack();
                notify()->flash('Thêm mới ' . $this->title . ' thất bại', 'error', [
                    'timer' => 1500]);
                return redirect()->back();
            }
        } catch(\Illuminate\Validation\ValidationException $exception) {
            DB::rollBack();
            notify()->flash('Đã có lỗi xảy ra!', 'warning', [
                'timer' => 1500]);
            return redirect()->back()->withErrors($exception->validator->errors())->withInput();
        }
    }

    public function edit($id)
    {
        $order = $this->order->getById($id);
        if (is_null($order)) {
            notify()->flash('Không tìm thấy trang yêu cầu', 'warning', [
                'timer' => 1500]);
            return redirect()->back();
        }
        $spines = OrderConstant::SPINE;
        $cover_types = OrderConstant::COVER_TYPE;
        $cover_colors = OrderConstant::COVER_COLOR;
        $plastics = OrderConstant::PLASTIC;
        $odd_pages = OrderConstant::ODD_PAGE;
        $products = $this->product->getByParam([], 100);

        return view($this->viewPath . 'edit')->with([
            'title'         => 'Cập nhật ' . $this->title,
            'order'         => $order,
            'spines'        => $spines,
            'cover_types'   => $cover_types,
            'plastics'      => $plastics,
            'odd_pages'     => $odd_pages,
            'products'      => $products,
            'cover_colors'  => $cover_colors
        ]);
    }

    public function update($id, Request $request)
    {
        try {
            $this->validate($request, $this->rules, $this->messages);
            $order = $this->order->getById($id);
            $params = $request->all();
            $params['kpi'] = $params['kpi'] / 100;
            $params['price'] = str_replace('.', '', $params['price']);

            if (is_null($order)) {
                notify()->flash('Không tìm thấy trang yêu cầu', 'warning', [
                    'timer' => 1500]);
                return redirect()->back();
            }

            DB::beginTransaction();
            if ($this->order->update($order, $params)) {
                $this->order_detail->update($order->order_details->first(), $params);
                DB::commit();
                $this->notify->push([
                    'title' => 'Cập nhật đơn',
                    'body'  => 'Bởi ' . Auth::user()->name,
                    'link'  => route('orders.show', $order->id)
                ]);
                notify()->flash('Cập nhật ' . $this->title . ' thành công', 'success', [
                    'timer' => 1500]);
                return redirect()->route('orders.index');
            } else {
                DB::rollBack();
                notify()->flash('Cập nhật ' . $this->title . ' thất bại', 'error', [
                    'timer' => 1500]);
                return redirect()->back();
            }
        } catch(\Illuminate\Validation\ValidationException $exception) {
            DB::rollBack();
            notify()->flash('Vui lòng kiểm tra lại các thông tin cần nhập', 'warning', [
                'timer' => 1500]);
            return redirect()->back()->withErrors($exception->validator->errors())->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $order = $this->order->getById($id);

            if (is_null($order)) {
                notify()->flash('Không tìm thấy trang yêu cầu', 'warning', [
                    'timer' => 1500]);
                return redirect()->back();
            }
            DB::beginTransaction();
            if ($order->order_details()->delete() && $this->order->delete($order)) {
                $this->notify->push([
                    'title' => 'Xóa đơn',
                    'body'  => 'Bởi ' . Auth::user()->name,
                    'link'  => route('orders.index')
                ]);
                DB::commit();
                notify()->flash('Xóa ' . $this->title . ' thành công', 'success', [
                    'timer' => 1500]);
                return response()->json([
                    'code'    => 1,
                    'message' => 'Xóa thành công'
                ]);
            } else {
                DB::rollBack();
                notify()->flash('Xóa ' . $this->title . ' thất bại', 'error', [
                    'timer' => 1500]);
                return response()->json([
                    'code'    => 0,
                    'message' => 'Xóa thất bại'
                ]);
            }
        } catch(\Illuminate\Validation\ValidationException $exception) {
            DB::rollBack();
            notify()->flash('Có lỗi trong quá trình xóa dữ liệu', 'warning', [
                'timer' => 1500]);
            return redirect()->back()->withErrors($exception->validator->errors())->withInput();
        }
    }
}

<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\AdminController;
use App\Repositories\Customers\CustomerRepository;

class CustomerController extends AdminController
{
    private $viewPath = 'admin.customers.';
    private $title    = 'Khách hàng';

    protected $customer;

    protected $rules = [
        'name'       => 'required',
        'phone'      => 'required',
        'address' 	 => 'required'
    ];

    protected $messages = [
        'name.required'       => 'Tên không được để trống!',
        'phone.required'      => 'Số điện thoại không được để trống!',
        'address.required'	 => 'Địa chỉ không được để trống!'
    ];

    function __construct(CustomerRepository $customer)
    {
        $this->customer = $customer;
        $this->checkPermission('customer');
    }

    public function index(Request $request)
    {
        return view($this->viewPath . 'index')->with([
            'title' => 'Danh sách ' . $this->title,
            'customers' => $this->customer->getByParam([], 10)
        ]);
    }

    public function search(Request $request)
    {
        return response()->json($this->customer->getByParam($request->all(), 10));
    }

    public function create()
    {
        return view($this->viewPath . 'create')->with([
            'title' => 'Tạo mới khách hàng ' . $this->title
        ]);
    }

    public function store(Request $request)
    {
        try {
            $this->validate($request, $this->rules, $this->messages);
            if ($this->customer->store($request->all())) {
                notify()->flash('Thêm mới ' . $this->title . ' thành công', 'success', [
                    'timer' => 1500]);
                return redirect()->route('customers.index');
            }else {
                notify()->flash('Thêm mới ' . $this->title . ' thất bại', 'error', [
                    'timer' => 1500]);
                return redirect()->back();
            }
        } catch(\Illuminate\Validation\ValidationException $exception) {
            notify()->flash('Vui lòng kiểm tra lại các thông tin cần nhập', 'warning', [
                'timer' => 1500]);
            return redirect()->back()->withErrors($exception->validator->errors())->withInput();
        }
    }

    public function edit($id)
    {
        $customer = $this->customer->getById($id);

        if (is_null($customer)) {
            notify()->flash('Không tìm thấy trang yêu cầu', 'warning', [
                'timer' => 1500]);
            return redirect()->back();
        }

        return view($this->viewPath . 'edit')->with([
            'title'      => 'Cập nhật ' . $this->title,
            'customer'       => $customer
        ]);
    }

    public function update($id, Request $request)
    {
        try {
            $this->validate($request, $this->rules, $this->messages);

            $customer = $this->customer->getById($id);

            if (is_null($customer)) {
                notify()->flash('Không tìm thấy trang yêu cầu', 'warning', [
                    'timer' => 1500]);
                return redirect()->back();
            }

            if ($this->customer->update($customer, $request->all())) {
                notify()->flash('Cập nhật ' . $this->title . ' thành công', 'success', [
                    'timer' => 1500]);
                return redirect()->route('customers.index');
            }else {
                notify()->flash('Cập nhật ' . $this->title . ' thất bại', 'error', [
                    'timer' => 1500]);
                return redirect()->back();
            }
        } catch(\Illuminate\Validation\ValidationException $exception) {
            notify()->flash('Vui lòng kiểm tra lại các thông tin cần nhập', 'warning', [
                'timer' => 1500]);
            return redirect()->back()->withErrors($exception->validator->errors())->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $customer = $this->customer->getById($id);

            if (is_null($customer)) {
                notify()->flash('Không tìm thấy trang yêu cầu', 'warning', [
                    'timer' => 1500]);
                return redirect()->back();
            }

            if ($this->customer->delete($customer)) {
                notify()->flash('Xóa ' . $this->title . ' thành công', 'success', [
                    'timer' => 1500]);
                return response()->json([
                    'code'    => 1,
                    'message' => 'Xóa thành công'
                ]);
            }else {
                notify()->flash('Xóa ' . $this->title . ' thất bại', 'error', [
                    'timer' => 1500]);
                return response()->json([
                    'code'    => 0,
                    'message' => 'Xóa thất bại'
                ]);
            }
        } catch(\Illuminate\Validation\ValidationException $exception) {
            notify()->flash('Có lỗi trong quá trình xóa dữ liệu', 'warning', [
                'timer' => 1500]);
            return redirect()->back()->withErrors($exception->validator->errors())->withInput();
        }
    }
}

<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\AdminController;
use App\Repositories\Products\ProductRepository;
use App\Repositories\Products\Product;

class ProductController extends AdminController
{
    private $viewPath = 'admin.products.';
    private $title    = 'Sản phẩm';

    protected $product;

    protected $rules = [
        'name'       => 'required',
        'default_price'      => 'required|numeric',
        'price_per_page' 	 => 'required|numeric'
    ];

    protected $messages = [
        'name.required'       		=> 'Tên không được để trống!',
        'default_price.required'	=> 'Giá không được để trống!',
        'default_price.numeric'		=> 'Giá phải ở dạng số',
        'price_per_page.required'	=> 'Giá mỗi trang không được để trống!',
        'price_per_page.numeric'	=> 'Giá mỗi trang phải ở dạng số'
    ];

    function __construct(ProductRepository $product, Product $model)
    {
        $this->product = $product;
        $this->model   = $model;
    }

    public function index(Request $request)
    {
        return view($this->viewPath . 'index')->with([
            'title' => 'Danh sách ' . $this->title,
            'products' => $this->product->getByParam([], 10)
        ]);
    }

    public function create()
    {
        $types = $this->model->getTypes();
        return view($this->viewPath . 'create')->with([
            'title' => 'Tạo mới khách hàng ' . $this->title,
            'types'    => $types
        ]);
    }

    public function store(Request $request)
    {
        try {
            $this->validate($request, $this->rules, $this->messages);
            if ($this->product->store($request->all())) {
                notify()->flash('Thêm mới ' . $this->title . ' thành công', 'success', [
                    'timer' => 1500]);
                return redirect()->route('products.index');
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
        $product = $this->product->getById($id);
        $types = $this->model->getTypes();
        if (is_null($product)) {
            notify()->flash('Không tìm thấy trang yêu cầu', 'warning', [
                'timer' => 1500]);
            return redirect()->back();
        }

        return view($this->viewPath . 'edit')->with([
            'title'     => 'Cập nhật ' . $this->title,
            'product'   => $product,
            'types'     => $types
        ]);
    }

    public function update($id, Request $request)
    {
        try {
            $this->validate($request, $this->rules, $this->messages);

            $product = $this->product->getById($id);

            if (is_null($product)) {
                notify()->flash('Không tìm thấy trang yêu cầu', 'warning', [
                    'timer' => 1500]);
                return redirect()->back();
            }

            if ($this->product->update($product, $request->all())) {
                notify()->flash('Cập nhật ' . $this->title . ' thành công', 'success', [
                    'timer' => 1500]);
                return redirect()->route('products.index');
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
            $product = $this->product->getById($id);

            if (is_null($product)) {
                notify()->flash('Không tìm thấy trang yêu cầu', 'warning', [
                    'timer' => 1500]);
                return redirect()->back();
            }

            if ($this->product->delete($product)) {
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

    public function search(Request $request)
    {
        return response()->json($this->product->getByParam($request->all(), 10));
    }
}

<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Categories\CategoryRepository;

class CategoryController extends Controller
{
    protected $category;

    protected $viewPath = 'admin.categories.';
    protected $title    = 'Danh mục';

    protected $rules = [
        'name' => 'required'
    ];

    protected $messages = [
        'name.required' => 'Tên chuyên mục không được để trống!'
    ];

    public function __construct(CategoryRepository $category)
    {
        $this->category = $category;
    }

    public function index(Request $request)
    {
        return view($this->viewPath . 'index')->with([
            'title'         => $this->title,
            'viewPath'      => $this->viewPath,
            'categories'    => $this->category->getAllCategories(),
            'allcategories' => $this->category->recursiveCategories()
        ]);
    }

    public function store(Request $request)
    {
        try {
            $this->validate($request, $this->rules, $this->messages);
                if ($this->category->store($request->all())) {
                    notify()->flash('Thêm mới ' . $this->title . ' thành công', 'success', ['timer' => 1500]);
                    return redirect()->route('categories.index');
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
        $category = $this->category->getById($id);

        return view($this->viewPath . 'index')->with([
            'title'         => $this->title,
            'viewPath'      => $this->viewPath,
            'categories'    => $this->category->getAllCategories(),
            'allcategories' => $this->category->recursiveCategories(),
            'category_edit' => $category
        ]);
    }

    public function update($id, Request $request)
    {

        $category = $this->category->getById($id);

        $child = $this->category->recursiveCategories($category->id);
        array_shift($child);

        if(count($child) && $category->parent_id <> $request['parent_id']) {
            notify()->flash('Danh mục hiện tại đang là danh mục cha nên không thể cập nhật được phần danh mục', 'error', [
                'timer' => 1500]);
            return redirect()->back();
        }
        if ($category->id == $request['parent_id']) {
            notify()->flash('Danh mục không hợp lệ', 'error', [
                'timer' => 1500]);
            return redirect()->back();
        }
        try {
            $this->validate($request, $this->rules, $this->messages);
                if ($this->category->update($category, $request->all())) {
                    notify()->flash('Cập nhật ' . $this->title . ' thành công', 'success', [
                        'timer' => 1500]);
                    return redirect()->route('categories.index');
                }else {
                    notify()->flash('Cập nhât ' . $this->title . ' thất bại', 'error', [
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
        $category = $this->category->getById($id);

        $id_delete = array_keys($this->category->recursiveCategories($category->id));
        array_shift($id_delete);
        array_push($id_delete, $category->id);

        try {
            if ($this->category->deleteRecursiveCategories($id_delete)) {
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

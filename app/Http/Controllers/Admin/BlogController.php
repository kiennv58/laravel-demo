<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\AdminController;
use App\Repositories\Blogs\BlogRepository;
use App\Repositories\Categories\CategoryRepository;
use App\Repositories\Tags\TagRepository;

class BlogController extends AdminController
{
    private $viewPath = 'admin.blogs.';
    private $title    = 'Blog';

    protected $blog;
    protected $category;
    protected $tag;

    protected $rules = [
        'title'       => 'required',
        'teaser'      => 'required',
        'category_id' => 'required',
        'content'     => 'required'
    ];

    protected $messages = [
        'title.required'       => 'Tiêu đề không được để trống!',
        'teaser.required'      => 'Mô tả ngắn không được để trống!',
        'category_id.required' => 'Danh mục không được để trống!',
        'content.required'     => 'Nội dung không được để trống!'
    ];

    function __construct(BlogRepository $blog, CategoryRepository $category, TagRepository $tag)
    {
        $this->blog     = $blog;
        $this->category = $category;
        $this->tag      = $tag;
    }

    public function index(Request $request)
    {
        return view($this->viewPath . 'index')->with([
            'title' => 'Danh sách ' . $this->title,
            'blogs' => $this->blog->getQueryScope([], [], 10)
        ]);
    }

    public function create()
    {
        $tags = $this->tag->getQueryScope([], [], false);
        $tags = array_pluck($tags, 'name', 'name');

        return view($this->viewPath . 'create')->with([
            'title'      => 'Thêm mới ' . $this->title,
            'categories' => $this->category->recursiveCategories(0, '', true),
            'allTags'  => $tags
        ]);
    }

    public function store(Request $request)
    {
        if (!isset($request['category_id'])) {
            $request['category_id'] = null;
        }
        if (isset($request['image'])) {
            $this->rules['image'] = 'image|mimes:jpeg,png,jpg,gif,svg|max:2048';
        }
        try {
            $this->validate($request, $this->rules, $this->messages);
            if ($this->blog->store($request->all())) {
                notify()->flash('Thêm mới ' . $this->title . ' thành công', 'success', [
                    'timer' => 1500]);
                return redirect()->route('blogs.index');
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

    public function upload(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ], ['file.max' => 'Dung lượng ảnh không quá 2MB']);

        return $this->blog->upload($request['file'], false, false, false);
    }

    public function edit($id)
    {
        $blog = $this->blog->getById($id);

        if (is_null($blog)) {
            notify()->flash('Không tìm thấy trang yêu cầu', 'warning', [
                'timer' => 1500]);
            return redirect()->back();
        }

        $tags = $this->tag->getQueryScope([], [], false);
        $tags = array_pluck($tags, 'name', 'name');

        $tags_old = $blog->tags;
        $tags_old = array_pluck($tags_old, 'name', 'name');

        return view($this->viewPath . 'edit')->with([
            'title'      => 'Cập nhật ' . $this->title,
            'categories' => $this->category->recursiveCategories(0, '', true),
            'blog'       => $blog,
            'allTags'    => $tags,
            'tags_old'   => $tags_old
        ]);
    }

    public function update($id, Request $request)
    {
        try {
            $this->validate($request, $this->rules, $this->messages);

            $blog = $this->blog->getById($id);

            if (is_null($blog)) {
                notify()->flash('Không tìm thấy trang yêu cầu', 'warning', [
                    'timer' => 1500]);
                return redirect()->back();
            }

            if ($this->blog->update($blog, $request->all())) {
                notify()->flash('Cập nhật ' . $this->title . ' thành công', 'success', [
                    'timer' => 1500]);
                return redirect()->route('blogs.index');
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
            $blog = $this->blog->getById($id);

            if (is_null($blog)) {
                notify()->flash('Không tìm thấy trang yêu cầu', 'warning', [
                    'timer' => 1500]);
                return redirect()->back();
            }

            if ($this->blog->delete($blog)) {
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

    public function hot($id)
    {
        if($this->blog->setHot($id)) {
            $blog = $this->blog->getById($id);
            return [
                'code'   => 1,
                'messages' => 'Cập nhật thành công',
                'active' => $blog->hot
            ];
        }
        return [
            'code'   => 0,
            'messages' => 'Có lỗi xảy ra!'
        ];
    }

        public function active($id)
    {
        if($this->blog->setActive($id)) {
            $blog = $this->blog->getById($id);
            return [
                'code'   => 1,
                'messages' => 'Cập nhật thành công',
                'active' => $blog->active
            ];
        }
        return [
            'code'   => 0,
            'messages' => 'Có lỗi xảy ra!'
        ];
    }
}

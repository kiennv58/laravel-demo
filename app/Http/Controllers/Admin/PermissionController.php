<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Permissions\PermissionRepository;

class PermissionController extends Controller
{
    private $viewPath = 'admin.permissions.';
    private $title    = 'Quyền hạn';

    protected $permission;

    protected $rules = [
		'name' => 'required',
		'display_name' => 'required'
    ];

    protected $messages = [
		'name.required' => 'Tên quyền hạn không được để trống!',
		'display_name.required' => 'Tên quyền hạn không được để trống!'
    ];

    function __construct(PermissionRepository $permission)
    {
		$this->permission = $permission;
    }

    public function index(Request $request)
    {
    	return view($this->viewPath . 'index')->with([
			'title' => 'Danh sách ' . $this->title,
			'permissions'  => $this->permission->getByParam([], 10)
    	]);
    }

    public function store(Request $request)
    {
    	// dd($request->all());
        try {
            $this->validate($request, $this->rules, $this->messages);
                if ($this->permission->store($request->all())) {
                    notify()->flash('Thêm mới ' . $this->title . ' thành công', 'success', [
                        'timer' => 1500]);
                    return redirect()->route('permissions.index');
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
        $permission = $this->permission->getById($id);

        if (is_null($permission)) {
            notify()->flash('Không tìm thấy trang yêu cầu', 'warning', [
                'timer' => 1500]);
            return redirect()->back();
        }

        return view($this->viewPath . 'index')->with([
            'title'    => 'Cập nhật ' . $this->title,
            'permissions'     => $this->permission->getByParam([], 10),
            'permission_edit' => $permission
        ]);
    }

    public function update(Request $request, $id)
    {
        $permission = $this->permission->getById($id);

        if (is_null($permission)) {
            notify()->flash('Không tìm thấy trang yêu cầu', 'warning', [
                'timer' => 1500]);
            return redirect()->back();
        }

        try {
            $this->validate($request, $this->rules, $this->messages);
                if ($this->permission->update($permission, $request->all())) {
                    notify()->flash('Cập nhật ' . $this->title . ' thành công', 'success', [
                        'timer' => 1500]);
                    return redirect()->route('permissions.index');
                } else {
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
        $permission = $this->permission->getById($id);

        if (is_null($permission)) {
            notify()->flash('Không tìm thấy trang yêu cầu', 'warning', [
                'timer' => 1500]);
            return redirect()->route('permissions.index');
        }

        try {
            if ($this->permission->delete($permission)) {
                notify()->flash('Xóa ' . $this->title . ' thành công', 'success', [
                    'timer' => 1500]);
                return [
                    'code'   => 1,
                    'messages' => 'Xóa tài khoản thành công'
                ];
            } else {
                notify()->flash('Xóa ' . $this->title . ' thất bại', 'error', [
                    'timer' => 1500]);
                return [
                    'code'   => 0
                ];
            }
        } catch(\Illuminate\Validation\ValidationException $exception) {
            notify()->flash('Có lỗi trong quá trình xóa dữ liệu', 'warning', [
                'timer' => 1500]);
            return redirect()->back()->withErrors($exception->validator->errors())->withInput();
        }
    }
}

<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Roles\RoleRepository;
use App\Repositories\Permissions\PermissionRepository;
use DB;

class RoleController extends Controller
{
    private $viewPath = 'admin.roles.';
    private $title    = 'Chức vụ';

    protected $role;

    protected $rules = [
		'name' 			=> 'required',
		'display_name'  => 'required'
    ];

    protected $messages = [
		'name.required' 		=> 'Tên chức vụ không được để trống!',
		'display_name.required' => 'Tên chức vụ không được để trống!'
    ];

    function __construct(RoleRepository $role, PermissionRepository $permission)
    {
		$this->role 	  = $role;
		$this->permission = $permission;
    }

    public function index(Request $request)
    {
        $params['except'] = 'superadmin';
    	return view($this->viewPath . 'index')->with([
			'title' 	   => 'Danh sách ' . $this->title,
			'roles'  	   => $this->role->getByParam($params, 10),
			'permissions'  => $this->permission->getAllWithSort()
    	]);
    }

    public function store(Request $request)
    {
    	DB::beginTransaction();
		try {
			$this->validate($request, $this->rules, $this->messages);
			$role = $this->role->store($request->all());
			DB::commit();
			notify()->flash('Thêm mới ' . $this->title . ' thành công', 'success', [
			    'timer' => 1500]);
			return redirect()->route('roles.index');
		}
		catch(Exception $e) {
			DB::rollback();
    	    notify()->flash('Có lỗi xảy ra', 'warning', [
    	        'timer' => 1500]);
    	    return redirect()->back()->withErrors($exception->validator->errors())->withInput();
    	}
    }

    public function edit($id)
    {
        $role = $this->role->getById($id);

        if (is_null($role) || ($role->name == 'superadmin')) {
            notify()->flash('Không tìm thấy trang yêu cầu', 'warning', [
                'timer' => 1500]);
            return redirect()->back();
        }

        return view($this->viewPath . 'index')->with([
            'title'    		=> 'Cập nhật ' . $this->title,
            'roles'     	=> $this->role->getByParam([], 10),
            'permissions'  => $this->permission->getAllWithSort(),
            'role_edit'		=> $role
        ]);
    }

    public function update($id, Request $request)
    {
        $role = $this->role->getById($id);

        if (is_null($role) || ($role->name == 'superadmin')) {
            notify()->flash('Không tìm thấy trang yêu cầu', 'warning', [
                'timer' => 1500]);
            return redirect()->back();
        }
        DB::beginTransaction();
        try {
            $this->validate($request, $this->rules, $this->messages);
            if ($this->role->update($role, $request->all())) {
            	DB::commit();
                notify()->flash('Cập nhật ' . $this->title . ' thành công', 'success', [
                    'timer' => 1500]);
                return redirect()->route('roles.index');
            }else {
                notify()->flash('Cập nhât ' . $this->title . ' thất bại', 'error', [
                    'timer' => 1500]);
                return redirect()->back();
            }
        } catch(\Illuminate\Validation\ValidationException $exception) {
        	DB::rollback();
            notify()->flash('Vui lòng kiểm tra lại các thông tin cần nhập', 'warning', [
                'timer' => 1500]);
            return redirect()->back()->withErrors($exception->validator->errors())->withInput();
        }
    }

    public function destroy($id)
    {
        $role = $this->role->getById($id);

        if (is_null($role) || ($role->name == 'superadmin')) {
            notify()->flash('Không tìm thấy trang yêu cầu', 'warning', [
                'timer' => 1500]);
            return redirect()->back();
        }

        try {
            if ($this->role->delete($role)) {
                notify()->flash('Xóa ' . $this->title . ' thành công', 'success', [
                    'timer' => 1500]);
                return [
                    'code'   => 1,
                    'messages' => 'Xóa tài khoản thành công'
                ];
            }else {
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

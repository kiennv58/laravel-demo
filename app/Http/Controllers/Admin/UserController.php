<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Users\UserRepository;
use App\Repositories\Roles\RoleRepository;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\EditUserRequest;
use DB;
use Auth;

class UserController extends Controller
{
    protected $title = 'Danh sách tài khoản';
    protected $user;
    protected $role;

    public function __construct(UserRepository $user, RoleRepository $role)
    {
        $this->user = $user;
        $this->role = $role;
    }

    public function index(Request $request)
    {
        $type_sort = array_get($request->all(), 'type_sort', 'desc');
        $type_sort = $type_sort == 'asc' ? 1 : -1;
        $sorting   = 'created_at:' . $type_sort;
        $sorting   = explode(',', $sorting);

        if (is_null($request->active)) {
            array_except($request->all(), ['active']);
        }

        return view('admin.users.index')->with([
            'title'   => $this->title,
            'users'   => $this->user->getByParam($request->all(), 10, $sorting)
        ]);
    }

    public function show(Request $request, $id)
    {
        if ($user = $this->user->getById($id)) {
            return view('admin.users.show')->with([
                'title' => 'Chi tiết thông tin ' . $this->title,
                'user'  => $user
            ]);
        }
        notify()->flash('Không tìm thấy tài khoản này!', 'error', [
            'timer' => 1500]);
        return redirect()->back();
    }

    public function create(Request $request)
    {
        return view('admin.users.create')->with([
            'title'             => $this->title,
            'roles' => $this->role->getByParam([], 0)
        ]);
    }

    public function store(CreateUserRequest $request)
    {
        $params = $request->all();
        $params['basic_salary'] = str_replace('.', '', $params['basic_salary']);

        DB::beginTransaction();
        if ($user = $this->user->store($params)) {
            if (array_key_exists('roles', $params)) {
                $user->roles()->sync($params['roles']);
            }
            DB::commit();
            notify()->flash('Thêm mới tài khoản thành công', 'success', [
                'timer' => 1500]);
            return redirect()->route('accounts.index');
        } else {
            DB::rollBack();
            notify()->flash('Thêm mới tài khoản thất bại', 'error', [
                'timer' => 1500]);
            return redirect()->back();
        }
    }

    public function edit(Request $request, $id)
    {
        if($user = $this->user->getById($id)) {
            return view('admin.users.edit')->with([
                'title' => 'Cập nhật ' . $this->title,
                'roles' => $this->role->getByParam([], 0),
                'user'  => $user
            ]);
        } else {
            notify()->flash('Không tìm thấy tài khoản này!', 'error', [
                'timer' => 1500]);
            return redirect()->back();
        }
    }

    public function update(EditUserRequest $request, $id)
    {
        if (!isset($request['active'])) {
            $request['active'] = 0;
        }

        try {
            $user = $this->user->getById($id);
            $params = $request->all();
            $params['basic_salary'] = str_replace('.', '', $params['basic_salary']);

            DB::beginTransaction();
            if ($this->user->update($user, $params)) {
                if (array_key_exists('roles', $params)) {
                    $user->roles()->sync($params['roles']);
                }
                DB::commit();
                notify()->flash('Cập nhật tài khoản thành công', 'success', [
                    'timer' => 1500]);
                return redirect()->route('accounts.index');
            } else {
                DB::rollBack();
                notify()->flash('Cập nhật tài khoản thất bại', 'error', [
                    'timer' => 1500]);
                return redirect()->back();
            }
        } catch(\Illuminate\Validation\ValidationException $exception) {
            notify()->flash('Vui lòng kiểm tra lại các thông tin cần nhập', 'warning', [
                'timer' => 1500]);
            return redirect()->back()->withErrors($exception->validator->errors())->withInput();
        }
    }

    public function active($id)
    {
        if($this->user->setActive($id)) {
            $user = $this->user->getById($id);
            return [
                'code'   => 1,
                'messages' => 'Cập nhật thành công',
                'active' => $user->active
            ];
        }
        return [
            'code'   => 0,
            'messages' => 'Có lỗi xảy ra!'
        ];
    }

    public function destroy($id)
    {
        $user = $this->user->getById($id);
        if ($user->isSuperAdmin()) {
            abort(403);
        }
        try {
            $this->user->delete($user);
            notify()->flash('Xóa tài khoản thành công', 'success', ['timer' => 1500]);
            return [
                'code' => 1
            ];
        } catch(\Illuminate\Validation\ValidationException $exception) {
            notify()->flash('Có lỗi trong quá trình xóa dữ liệu', 'warning', [
                'timer' => 1500]);
            return redirect()->back()->withErrors($exception->validator->errors())->withInput();
        }
    }

    public function resetPasswordDefault($id)
    {
        $user = $this->user->getById($id);

        try {
            if ($this->user->resetPassWordDefault($user)) {
                notify()->flash('Reset mật khẩu thành công', 'success', [
                    'timer' => 1500]);
                return redirect()->back();
            } else {
                notify()->flash('Có lỗi trong quá trình reset lại mật khẩu về mặc định', 'warning', [
                'timer' => 1500]);
                return redirect()->back();
            }
        }
        catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}

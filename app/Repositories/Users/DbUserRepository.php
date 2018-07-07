<?php
namespace App\Repositories\Users;

use App\Filters\FilterFactory;
use App\Helpers\Sorting;
use App\Repositories\BaseRepository;
use App\User;

class DbUserRepository extends BaseRepository implements UserRepository
{
    public function __construct(User $user)
    {
        $this->model = $user;
    }

    /**
     * Lấy tất cả bản ghi có phân trang
     *
     * @param  integer $size Số bản ghi mặc định 25
     * @param  array $sorting Sắp xếp
     * @return Illuminate\Pagination\Paginator
     */
    public function getByQuery($params, $size = 25, $sorting = [])
    {
        $query       = array_get($params, 'q', null);
        $status      = array_get($params, 'status', null);

        $model = $this->model;

        if (!empty($sorting)) {
            $model = $model->orderBy($sorting[0], $sorting[1] > 0 ? 'ASC' : 'DESC');
        }

        if (!getCurrentUser()->isSuperAdmin()) {
            $model = $model->where('id', '>', 1);
        }

        if (!is_null($status)) {
            $model = $model->where('active', $status);
        }

        if ($query != '') {
            $model = $model->where(function($q) use ($query) {
                return $q->where('name', 'like', "%{$query}%")
                            ->orWhere('phone', 'like', "%{$query}%")
                            ->orWhere('email', 'like', "%{$query}%");
            });
        }

        return $size < 0 ? $model->get() : $model->paginate($size);
    }

    public function store($attributes)
    {
        $attributes['password'] = bcrypt(array_get($attributes, 'password', '123456'));
        if (isset($attributes['avatar'])) {
            $resultUpload = $this->upload($attributes['avatar'], $this->model->imgWidth, $this->model->imgHeight, true);
            if ($resultUpload['code']) {
                $attributes['avatar'] = $resultUpload['data']['name'];
            } else {
                $attributes['avatar'] = '';
            }
        } else {
            $attributes['avatar'] = '';
        }

        $model = $this->model->create($attributes);
        return $model;
    }

    // public function update($model, $attributes)
    // {
    //     if ($model->isSuperAdmin()) {
    //         return $model;
    //     }

    //     if (isset($attributes['avatar'])) {
    //         $resultUpload = $this->upload($attributes['avatar'], $this->model->imgWidth, $this->model->imgHeight, true);
    //         if ($resultUpload['code']) {
    //             $attributes['avatar'] = $resultUpload['data']['name'];
    //             // Thực hiện remove ảnh cũ đi
    //             $this->removeImage($model->avatar);
    //         }
    //     }

    //     $model->fill($attributes)->save();

    //     return $this->getById($model->id, 'withoutScope');
    // }

    /**
     * Cập nhật thông tin 1 bản ghi theo ID
     *
     * @param  integer $id ID bản ghi
     * @return bool
     */
    public function update($record, $data)
    {
        if ($password = array_get($data, 'password', null)) {
            $data['password'] = bcrypt($data['password']);
        }

        $record->fill($data)->save();

        if ($roles = array_get($data, 'roles', null)) {
            $record->roles()->sync($roles);
        }

        return $this->getById($record->id);
    }

    /**
     * Reest lại mật khẩu cho tài khoản shipper về 123456
     * @param  [type] $model [description]
     * @return [type]        [description]
     */
    public function resetPassWordDefault($model)
    {
        if ($model->isSuperAdmin()) {
            return $model;
        }

        $model->password = bcrypt('123456');

        $model->save();

        return $this->getById($model->id, 'withoutScope');
    }

    public function setActive($id)
    {
        $user = $this->model->find($id);
        if ($user->isSuperAdmin()) {
            return true;
        }

        $user->active = !$user->active;
        return $user->save();
    }

    public function delete($model)
    {
        if ($model->isSuperAdmin()) {
            return true;
        }

        // Remove Avatar
        $this->removeImage($model->avatar);
        // Delete
        return $model->delete();
    }

}

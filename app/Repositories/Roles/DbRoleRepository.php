<?php

namespace App\Repositories\Roles;
use App\Repositories\BaseRepository;
use App\Role;

class DbRoleRepository extends BaseRepository implements RoleRepository
{
    public function __construct(Role $role)
    {
        $this->model = $role;
    }

    /**
     * Lưu thông tin 1 bản ghi mới
     *
     * @param  array $data
     * @return Eloquent
     */
    public function store($data)
    {
        $model = $this->model->create($data);

        $this->syncInfo($model, $data);

        return $this->getById($model->id);
    }

    /**
     * Cập nhật thông tin 1 bản ghi theo ID
     *
     * @param  integer $id ID bản ghi
     * @return bool
     */
    public function update($id, $data)
    {
        $model = $this->getById($id);
        $model->fill($data)->save();

        $this->syncInfo($model, $data);

        return $this->getById($id);
    }

    /**
     * convert sang customer
     * @param  Role $model
     * @return json
     */
    public function syncInfo($model, $data)
    {
        $userIds = array_get($data, 'users', []);
        $permIds = array_get($data, 'permissions', []);

        $model->users()->sync($userIds);
        $model->perms()->sync($permIds);

        return $model;
    }

}

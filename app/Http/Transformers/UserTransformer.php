<?php

namespace App\Http\Transformers;

use App\User;
use League\Fractal\TransformerAbstract;
use Carbon\Carbon;

class UserTransformer extends TransformerAbstract
{
    protected $availableIncludes = [
        'roles'
    ];

    public function transform(User $user = null)
    {
        if (is_null($user)) {
            return [];
        }

        $data = [
            'id'             => $user->id,
            'name'           => $user->name,
            'username'       => $user->username,
            'email'          => $user->email,
            'phone'          => $user->phone,
            'address'        => $user->address,
            'start_date'     => $user->start_date,
            'basic_salary'   => $user->basic_salary,
            'avatar'         => $user->avatar,
            'avatar_path'    => $user->getImage(),
            'active'         => $user->isActive(),
            'status_txt'     => $user->active ? 'Đang hoạt động' : 'Đang bị khóa',
            'created_at'     => $user->created_at ? $user->created_at->format('d-m-Y H:i:s') : null,
            'updated_at'     => $user->updated_at ? $user->updated_at->format('d-m-Y H:i:s') : null
        ];

        return $data;
    }

    public function includeRoles(User $user = null)
    {
        if (is_null($user)) {
            return $this->null();
        }

        return $this->collection($user->roles, new RoleTransformer());
    }
}

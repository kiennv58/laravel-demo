<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Api\RestfulHandler;
use App\Http\Controllers\Api\TransformerTrait;
use App\Http\Transformers\UserTransformer;
use App\Repositories\Users\UserRepository;
use App\Repositories\Roles\RoleRepository;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\EditUserRequest;
use DB;
use Auth;

class UserController extends ApiController
{
    use TransformerTrait, RestfulHandler;

    protected $user;
    protected $role;

    public function __construct(UserRepository $user, RoleRepository $role, UserTransformer $transformer)
    {
        $this->user = $user;
        $this->role = $role;
        $this->setTransformer($transformer);
    }

    public function getResource()
    {
        return $this->user;
        $this->checkPermission('user');
    }

    public function index(Request $request)
    {
        $pageSize = $request->get('limit', 25);
        $sort = explode(':', $request->get('sort', 'id:1'));

        $models = $this->getResource()->getByQuery($request->all(), $pageSize, $sort);
        return $this->successResponse($models);
    }

    public function show(Request $request, $id)
    {
        if ($user = $this->user->getById($id)) {
            return $this->successResponse($user);
        }
        return $this->notFoundResponse();
    }

    public function store(CreateUserRequest $request)
    {
        try {
            $params = $request->all();
            $params['basic_salary'] = str_replace('.', '', $params['basic_salary']);

            DB::beginTransaction();
            $user = $this->getResource()->store($request->all());
            if (array_key_exists('roles', $params)) {
                $user->roles()->sync($params['roles']);
            }
            DB::commit();
            return $this->successResponse($user);
        } catch (\Illuminate\Validation\ValidationException $validationException) {
            DB::rollBack();
            return $this->errorResponse([
                'errors' => $validationException->validator->errors(),
                'exception' => $validationException->getMessage()
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
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

            $user = $this->user->update($user, $params);
            if (array_key_exists('roles', $params)) {
                $user->roles()->sync($params['roles']);
            }
            DB::commit();
            return $this->successResponse($user);
        } catch (\Illuminate\Validation\ValidationException $validationException) {
            DB::rollBack();
            return $this->errorResponse([
                'errors' => $validationException->validator->errors(),
                'exception' => $validationException->getMessage()
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function active($id)
    {
        try {
            DB::beginTransaction();
            $this->getResource()->setActive($id);
            $user = $this->getResource()->getById($id);

            DB::commit();
            return $this->successResponse($user);
        } catch (\Illuminate\Validation\ValidationException $validationException) {
            DB::rollBack();
            return $this->errorResponse([
                'errors' => $validationException->validator->errors(),
                'exception' => $validationException->getMessage()
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function resetPasswordDefault($id)
    {
        $user = $this->user->getById($id);
        try {
            $this->user->resetPassWordDefault($user);
            $user = $this->user->getById($id);
            return $this->successResponse($user);
        } catch (\Exception $e) {
            throw $e;
        }
    }
}

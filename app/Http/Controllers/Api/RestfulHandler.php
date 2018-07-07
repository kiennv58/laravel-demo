<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

trait RestfulHandler {

    use ResponseHandler;

    public function index(Request $request) {
        $pageSize = $request->get('limit', 25);
        $sort = $request->get('sort', '');

        $collections = $this->getResource()->getByParam($request->all(), $pageSize, explode(',', $sort));
        return $this->successResponse($collections);
    }

    public function show($id) {
        if ($model = $this->getResource()->getById($id)) {
            return $this->successResponse($model);
        }
        return $this->notFoundResponse();
    }

    public function store(Request $request) {
        DB::beginTransaction();

        try {
            $this->validate($request, $this->validationRules, $this->validationMessages);

            $model = $this->getResource()->store($request->all());

            DB::commit();
            return $this->successResponse($model);
        }
        catch (\Illuminate\Validation\ValidationException $validationException) {
            DB::rollback();
            return $this->errorResponse([
                'errors' => $validationException->validator->errors(),
                'exception' => $validationException->getMessage()
            ]);
        }
        catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function update(Request $request, $id) {
        if (!$model = $this->getResource()->getById($id)) {
            return $this->notFoundResponse();
        }

        DB::beginTransaction();

        try {
            $this->validate($request, $this->validationRules, $this->validationMessages);

            $model = $this->getResource()->update($model, $request->all());

            DB::commit();
            return $this->successResponse($model);
        }
        catch (\Illuminate\Validation\ValidationException $validationException) {
            DB::rollback();
            return $this->errorResponse([
                'errors' => $validationException->validator->errors(),
                'exception' => $validationException->getMessage()
            ]);
        }
        catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function destroy($id) {
        if (!$model = $this->getResource()->getById($id)) {
            return $this->notFoundResponse();
        }

        DB::beginTransaction();

        try {
            $this->getResource()->delete($model);

            DB::commit();
            return $this->deleteResponse();
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function forceDestroy($id) {
        if (!$model = $this->getResource()->getByIdWithTrash($id)) {
            return $this->notFoundResponse();
        }

        DB::beginTransaction();

        try {
            $this->getResource()->forceDestroy($model);

            DB::commit();
            return $this->deleteResponse();
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function restore($id) {
        if (!$model = $this->getResource()->getByIdwithTrash($id)) {
            return $this->notFoundResponse();
        }

        DB::beginTransaction();

        try {
            $this->getResource()->restore($model);

            DB::commit();
            return $this->successResponse($model);
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
}

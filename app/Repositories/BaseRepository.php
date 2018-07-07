<?php
namespace App\Repositories;

use App\Filters\FilterFactory;
use App\Helpers\Sorting;
use Image;

abstract class BaseRepository {
    protected $model;

    /**
     * Lấy thông tin 1 bản ghi xác định bởi ID
     *
     * @param  integer $id ID bản ghi
     * @return Eloquent
     */
    public function getById($id, $withoutScope = null)
    {
        $model = $withoutScope == 'withoutScope' ? $this->model->withoutGlobalScope('user') : $this->model;
        return $model->find($id);
    }

    /**
     * Lấy thông tin 1 bản ghi xác định bởi ID
     *
     * @param  integer $id ID bản ghi
     * @return Eloquent
     */
    public function getByIdWithTrash($id)
    {
        return $this->model->withTrashed()->find($id);
    }

    /**
     * tim kiem thong tin tra ve danh sach ban ghi
     * @param  array        $params     mang tham so can tim kiem
     * @param  integer      $size       so ban ghi tren 1 trang
     * @param  array        $sorting    mang key:value can sap xep
     * @return Collection               danh sach ban ghi
     */
    public function getByParam($params, $size = 25, $sorting = [], $getSql = false, $withoutScope = null)
    {
        // dd($sorting);
        $query = FilterFactory::apply($params, $this->model);
        $query = Sorting::apply($sorting, $query);
        // dd($query->toSql());

        if ($getSql) {
            return $query;
        }

        if ($withoutScope == 'withoutScope') {
            $query->withoutGlobalScope('user');
        }

        switch ($size) {
            case -1:
            case 0:
                return $query->get();
                break;

            case 1:
                return $query->first();
                break;

            default:
                return $query->paginate($size);
                break;
        }
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
        return $this->getById($model->id);
    }

    /**
     * Cập nhật thông tin 1 bản ghi theo ID
     *
     * @param  integer $id ID bản ghi
     * @return bool
     */
    public function update($model, $data)
    {
        $model->fill($data)->save();
        return $this->getById($model->id, 'withoutScope');
    }

    /**
     * Xóa 1 bản ghi. Nếu model xác định 1 SoftDeletes
     * thì method này chỉ đưa bản ghi vào trash. Dùng method destroy
     * để xóa hoàn toàn bản ghi.
     *
     * @param  integer $id ID bản ghi
     * @return bool|null
     */
    public function delete($model)
    {
        return $model->delete();
    }

    /**
     * Xóa han 1 bản ghi.
     *
     * @param  integer $id ID bản ghi
     * @return bool|null
     */
    public function forceDestroy($model)
    {
        return $model->forceDelete();
    }

    /**
     * phuc hoi lai ban ghi da bij xoa
     *
     * @param  integer $id ID bản ghi
     * @return bool|null
     */
    public function restore($model)
    {
        return $model->restore();
    }

    /**
     * upload image
     * @param  mixed $file
     * @return array
     */
    public function upload($file, $width, $height, $resize = true)
    {
        $image_name = date('Y_m_d_H_i_s') ."_". md5($file->getClientOriginalName() . substr( md5(rand()), 0, 7));
        $width = $width ? $width : $this->model->imgWidth;
        $height = $height ? $height : $this->model->imgHeight;

        try {
            if ($resize) {

                $image = Image::make($file->getRealPath())->orientate();
                if ($image->width() > $width) {
                    $image->resize($width, null, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                }
                $upload = $image->crop($width, $height)->save($this->getUploadImagePath($image_name));
            } else {
                $upload = Image::make($file->getRealPath())->orientate()->save($this->getUploadImagePath($image_name));
            }
            return $this->uploadSuccess($image_name);
        } catch (Exception $e) {
            return $this->uploadFail($e);
        }
    }

    /**
     * get image path
     * @param  String $img
     * @return String
     */
    protected function getImagePath($img) {
        return asset($this->model->imgPath . '/' . $img);
    }

    /**
     * get path upload image
     * @param string $img
     * @return string
     */
    protected function getUploadImagePath($img){
        return storage_path($this->model->uploadPath . '/' . $img);
    }

    /**
     * upload success response
     * @param  mixed $data
     * @return array
     */
    protected function uploadSuccess($name) {
        return [
            'code'    => 1,
            'message' => 'success',
            'data'    => [
                'name' => $name,
                'path' => $this->getImagePath($name)
            ]
        ];
    }

     /**
     * upload fail response
     * @param  mixed $data
     * @return array
     */
    protected function uploadFail($data) {
        return [
            'code'    => 0,
            'message' => 'fail',
            'data'    => $data
        ];
    }

    public function removeImage($image)
    {
        @unlink($this->getUploadImagePath($image));
    }
}

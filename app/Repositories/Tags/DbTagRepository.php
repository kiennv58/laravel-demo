<?php

namespace App\Repositories\Tags;
use App\Repositories\BaseRepository;

class DbTagRepository extends BaseRepository implements TagRepository
{
    public function __construct(Tag $tag)
    {
        $this->model = $tag;
    }

    public function getQueryScope($params, $sorting = [], $paginate = false)
    {
        $model = $this->model->orderBy('id', 'desc');

        if (!empty($sorting)) {
            $model = $model->orderBy($sorting[0], $sorting[1] > 0 ? 'ASC' : 'DESC');
        }

        return $paginate ? $model->paginate($paginate) : $model->get();
    }

    public function getTagBySlug($value)
    {
        return $this->model
                    ->where('slug', $value)
                    ->first();
    }

}

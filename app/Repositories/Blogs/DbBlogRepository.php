<?php

namespace App\Repositories\Blogs;
use App\Repositories\BaseRepository;
use Auth;
use App\Repositories\Tags\TagRepository;

class DbBlogRepository extends BaseRepository implements BlogRepository
{
    public function __construct(Blog $blog, TagRepository $tag)
    {
        $this->model = $blog;
        $this->tag   = $tag;
    }

    public function getQueryScope($params, $sorting = [], $paginate = false)
    {
        $query      = array_get($params, 'keyword', '');

        $model = $this->model->orderBy('id', 'desc');

        if (!empty($sorting)) {
            $model = $model->orderBy($sorting[0], $sorting[1] > 0 ? 'ASC' : 'DESC');
        }

        if (!empty($query)) {
            $query = '%' . $query . '%';
            $model = $model->where(function ($q) use ($query) {
                $q->orWhere('title','LIKE', $query)
                  ->orWhere('teaser','LIKE', $query);
            });
        }

        return $paginate ? $model->paginate($paginate) : $model->get();
    }

    public function store($attributes)
    {
        // Image
        if (isset($attributes['image'])) {
            $resultUpload = $this->upload($attributes['image'], $this->model->imgWidth, $this->model->imgHeight, true);
            if ($resultUpload['code']) {
                $attributes['image'] = $resultUpload['data']['name'];
            } else {
                $attributes['image'] = '';
            }
        }else {
            $attributes['image'] = '';
        }
        $attributes['active'] = Blog::ACTIVE;
        if (!isset($attributes['hot'])) {
            $attributes['hot'] = 0;
        }
        $attributes['user_id'] = Auth::user()->id;

        $model = $this->model->create($attributes);

        // Tag
        if (isset($attributes['tags'])) {
            $tags = [];
            foreach ($attributes['tags'] as $key => $value) {
                $tag = $this->getOrCreateTag($value);
                array_push($tags, $tag->id);
            }

            $model->tags()->sync($tags);
        }

        return $model;
    }

    public function update($model, $attributes)
    {
        $attributes['hot'] = isset($attributes['hot']) ? Blog::ACTIVE : Blog::UNACTIVE;

        if (isset($attributes['image'])) {
            $resultUpload = $this->upload($attributes['image'], $this->model->imgWidth, $this->model->imgHeight, true);
            if ($resultUpload['code']) {
                $attributes['image'] = $resultUpload['data']['name'];
                // Thực hiện remove ảnh cũ đi
                $this->removeImage($model->image);
            }
        }

        // Remove File
        preg_match_all ('/storage\/images\/blogs\/(.*?)"/', $model->content, $pat_array_old);
        preg_match_all ('/storage\/images\/blogs\/(.*?)"/', $attributes['content'], $pat_array_new);

        $img_delete = array_diff($pat_array_old[1], $pat_array_new[1]);

        foreach ($img_delete as $key => $value) {
            $this->removeImage($value);
        }

        // Tag
        if (isset($attributes['tags'])) {
            $tags = [];
            foreach ($attributes['tags'] as $key => $value) {
                $tag = $this->getOrCreateTag($value);
                array_push($tags, $tag->id);
            }
            $model->tags()->sync($tags);
        } else {
            $model->tags()->sync([]);
        }

        return $model->fill($attributes)->save();
    }

    public function delete($model)
    {
        // Remove Image
        $this->removeImage($model->image);

        // Remove File
        preg_match_all ('/images\/blogs\/(.*?)"/', $model->content, $pat_array);

        foreach ($pat_array[1] as $key => $value) {
            $this->removeImage($value);
        }

        // Remove Tag
        $model->tags()->sync([]);

        // Delete
        return $model->delete();
    }

    public function syncTag($model, $data)
    {
        $tags = array_get($data, 'tags', []);
        $model->tags()->sync($tags);
    }

    public function getOrCreateTag($value)
    {
        if (empty($value))
            return null;

        $tag = $this->tag->getTagBySlug(str_slug($value));

        if (is_null($tag)) {
            $data['name'] = $value;
            $tag          = $this->tag->store($data);
        }

        return $tag;
    }

    /**
     * Get all items of model
     * @param array|bool $filter Mảng điều kiện filter, có thể add nhiều mảng, ví dụ:
     *                           [['name', 'LIKE', '%Alvin%'], ['age', '>', 30]]
     * @param array|bool $sorter Mảng điều kiện sort, ví dụ:
     *                           ['created_at', 'desc']
     * @param number|bool $paginate Phân trang.
     * @return Illuminate\Support\Collection Model collections
     */
    public function getAll($filter = false, $sorter = false, $paginate = false)
    {
        if ($filter === false && $sorter === false && $paginate === false)
        {
            return $this->model->all();
        }

        $query = $this->model->where(function($q) use ($filter) {
            if (!empty($filter)) {
                foreach ($filter as $f) {
                    list($col, $ope, $val) = $f;
                    $q->where($col, $ope, $val);
                }
            }
        });

        if ($sorter) {
            list($col, $dir) = $sorter;
            $query->orderBy($col, $dir);
        }
        return $paginate ? $query->paginate($paginate) : $query->get();
    }

    /**
     * [getAllByTagWithPaginate]
     * @param  [type]  $tagSlug
     * @param  integer $pageSize
     * @return [type]
     */
    public function getAllByTagWithPaginate($tagSlug, $hot = false , $paginate)
    {
        $tag = $this->tag->getTagBySlug($tagSlug);
        $listBlog = [];
        foreach ($tag->blogs as $blog) {
            $listBlog[] = $blog->id;
        }
        $blogs = $this->model->whereIn('id', $listBlog)
                        ->where('blogs.active', Blog::ACTIVE)
                        ->orderBy('blogs.created_at', 'DESC');
        if ($hot) {
             $blogs = $blogs->where('blogs.hot', Blog::HOT);
        }

        return $paginate ? $blogs->paginate($paginate) : $query->get();

    }

    public function setHot($id)
    {
        $blog = $this->model->find($id);
        $blog->hot = !$blog->hot;
        return $blog->save();
    }


    public function setActive($id)
    {
        $blog = $this->model->find($id);
        $blog->active = !$blog->active;
        return $blog->save();
    }
}

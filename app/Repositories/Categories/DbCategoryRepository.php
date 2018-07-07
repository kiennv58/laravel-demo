<?php

namespace App\Repositories\Categories;
use App\Repositories\BaseRepository;

class DbCategoryRepository extends BaseRepository implements CategoryRepository
{
    private $categories = [];

    public function __construct(Category $category)
    {
        $this->model = $category;
    }

    public function getAllCategories($parent_id = 0, $flag = false)
    {
        $categories = $this->model->where('active', Category::ACTIVE)/*->orderBy('created_at', 'DESC')*/;
        return ! $flag ? $categories->where('parent_id', $parent_id)->get() : $categories->select('id', 'name')->get();
    }

    /**
     * Hàm đệ quy lấy ra toàn bộ danh sách danh mục theo kiểu danh mục cha - danh mục con
     * @param  integer $parent [description]
     * @param  string  $level  [description]
     * @return [type]          [description]
     */
    public function recursiveCategories($parent = 0, $level = '', $flag = false)
    {
        $this->categories[0] = "Chuyên mục cha";
        $level .= "|-- ";
        $children = $this->model->where(['parent_id' => $parent, 'active' => Category::ACTIVE])->get();

        if ($children->count() > 0) {
            foreach ($children as $item) {
                if ($item->parent_id == 0) {
                    $level = '';
                }
                $this->categories[$item->id] = $level.$item->name;
                $this->recursiveCategories($item->id,$level);
            }
        }

        if ($flag) {
            unset($this->categories[0]);
            return $this->categories;
        }

        return $this->categories;
    }

    public function deleteRecursiveCategories($array)
    {
        foreach ($array as $key => $value) {
            $cate = $this->model->find($value);
            if(!$cate->delete()) {
                return false;
            }
        }
        return true;
    }

}

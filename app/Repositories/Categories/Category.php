<?php

namespace App\Repositories\Categories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;
    // use SoftDeletes, NodeTrait;

    /**
     * Define status for the model
     */
    const DEACTIVE = 0;
    const ACTIVE   = 1;

    public $table = 'categories';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $fillable = ['name', 'slug', 'parent_id'];

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = str_slug($this->attributes['name']);
    }

    public function getDeletedAt()
    {
        return $this->deleted_at ? $this->deleted_at->format('d-m-Y') : null;
    }

    public function parent() {
        return $this->belongsTo('Fship\Repositories\Categories\Category', 'parent_id', 'id');
    }

    // public function blogs()
    // {
    //     return $this->hasMany('Fship\Repositories\Blogs\Blog', 'category_id', 'id')->withTrashed();
    // }

    public function childs()
    {
        return $this->hasMany(Category::class, 'parent_id', 'id')->where('active', '=', 1);
    }

    // public function getUrl() {
    //     return route('blog.category', [$this->id, $this->slug]);
    // }
}

<?php

namespace App\Repositories\Blogs;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Blog extends Model
{
    use SoftDeletes;

    const ACTIVE   = 1;
    const INACTIVE = 0;
    const HOT      = 1;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $fillable = ['category_id', 'title', 'teaser', 'content', 'image', 'user_id', 'hot', 'active'];

    /**
     * Full path and physical path of image
     */
    public $imgPath = 'storage/images/blogs';
    public $uploadPath = 'app/public/images/blogs';

    public $imgWidth  = 800;
    public $imgHeight = 300;

    public $imageDefault = '/images/default.jpg';

    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = $value;
        $this->attributes['slug'] = str_slug($this->attributes['title']);
    }

    public function getDeletedAt()
    {
        return $this->deleted_at ? $this->deleted_at->format('d-m-Y') : null;
    }

    public function getImage()
    {
        if ($this->image == '') return $this->image;
        return secure_asset($this->imgPath . '/' . $this->image);
    }

    public function getThumbnail()
    {
        if ($this->image == '') return $this->imageDefault;
        return secure_asset($this->imgPath . '/' . $this->image);
    }

    public function user()
    {
        return $this->belongsTo('Fship\User', 'user_id')->withTrashed();
    }

    public function category()
    {
        return $this->belongsTo('Fship\Repositories\Categories\Category', 'category_id')->withTrashed();
    }

    public function tags()
    {
        return $this->belongsToMany('Fship\Repositories\Tags\Tag', 'blog_tag', 'blog_id', 'tag_id');
    }

    /**
     * [getUrl description]
     * @return [type] [description]
     */
    public function getUrl() {
        $route = route('blogs.show', [$this->id, $this->slug]);
        return $route;
    }

    /**
     * Get tag with link
     * @return [type] [description]
     */
    public function getTags() {
        $tag_list = [];
        foreach($this->tags as $tag) {
            $tag_list[] = '<a href="'. route('blogs.tag', [$tag->slug]) .'" class="tag-content">#'.$tag->name.'</a>';
        }
        return implode('', $tag_list);
    }
}

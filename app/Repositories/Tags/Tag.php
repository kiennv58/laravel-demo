<?php

namespace App\Repositories\Tags;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $fillable = ['name'];

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = str_slug($this->attributes['name']);
    }

    public function blogs()
    {
        return $this->belongsToMany('Fship\Repositories\Blogs\Blog', 'blog_tag', 'tag_id', 'blog_id');
    }
}

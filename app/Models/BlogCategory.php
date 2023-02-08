<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Kyslik\ColumnSortable\Sortable;

class BlogCategory extends Model
{
    use HasFactory, Sluggable, Sortable;
    protected $fillable = ["title", "slug", "status", "created_at", "updated_at"];

    protected $table="blog_categories";

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title',
                'unique' => true,
                'separator' => '-',
                'onUpdate' => true,
            ]
        ];
    }

    public function getCustomSlugAttribute()
    {
        // dd($this->slug);
        if (empty($this->slug)) {
            return strtoupper(trim($this->category_name));
        } else {
            return strtoupper(trim($this->slug));
        }
    }

    public function blogs(){
        return $this->hasMany(\App\Models\Blog::class,'blog_categories_id','id');
    }

    public function ActiveBlogs()
    {
        return $this->hasMany(\App\Models\Blog::class, 'blog_categories_id', 'id')->where('status',1);
    }

    
}

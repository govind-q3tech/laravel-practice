<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Cviebrock\EloquentSluggable\Sluggable;
use Kyslik\ColumnSortable\Sortable;

class Blog extends Model
{
    use HasFactory, Sluggable, Sortable;

    protected $fillable = ["blog_categories_id", "title", "description", "slug","images","status","meta_title","meta_keyword","sub_title"];
    public $sortable = ["title",  'status', 'created_at'];
   
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

    public function blog_category()
    {
        return $this->belongsTo(\App\Models\BlogCategory::class,'blog_categories_id','id');
    }

    public function comment(){
        return $this->hasMany(\App\Models\Comment::class,'blog_id','id');
    }

    //     public function category()
    // {
    //   return $this->belongsTo(\App\Models\BlogCategory::class,'blog_categories_id','id');
    // }

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id', 'id');
    }
    
    public function scopeStatus($query, $status = 1)
    {
        if (!empty($keyword)) {
            $query->where(function ($query) use ($status) {
                $query->where('status', $status);
            });
        }
        return $query;
    }
}

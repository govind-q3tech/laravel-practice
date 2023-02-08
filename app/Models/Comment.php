<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Kyslik\ColumnSortable\Sortable;
use App\Models\BusinessCategory;
use App\Models\Location;

class Comment extends Model
{
    use HasFactory, Sluggable, Sortable;
    protected $fillable = ["title", "sub_title", "slug", "short_description", "description", "banner", "meta_title", "meta_keyword", "meta_description", "status", 'position'];

    public $sortable = ["title", "sub_title", "slug", 'status', 'created_at', 'updated_at'];


    public function sluggable()
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
            return strtoupper(trim($this->title));
        } else {
            return strtoupper(trim($this->slug));
        }
    }

    public function blog()
    {
        return $this->belongsTo(\App\Models\Blog::class, 'blog_id', 'id');
    }
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id', 'id');
    }

 


    public function getParentsNames()
    {
        if ($this->parent) {
            return $this->parent->getParentsNames() . " > " . $this->message;
        } else {
            return $this->message;
        }
    }

    public function parent()
{
    return $this->belongsTo(self::class, 'parent_id');
}

public function children()
{
    return $this->hasMany(self::class, 'parent_id', 'blog_id');
}
}

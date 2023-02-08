<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Kyslik\ColumnSortable\Sortable;
use App\Models\BusinessCategory;
use App\Models\Location;

class Page extends Model
{
    use HasFactory, Sluggable, Sortable;
    protected $fillable = ["title", "sub_title", "slug", "short_description", "description", "banner", "meta_title", "meta_keyword", "meta_description", "status", 'position'];

    public $sortable = ["title", "sub_title", "slug", 'status', 'created_at', 'updated_at'];


    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }


    public function business_category()
    {
        return $this->belongsTo(BusinessCategory::class, 'business_category_id');
    }

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
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

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function getCustomSlugAttribute()
    {
        // dd($this->slug);
        if (empty($this->slug)) {
            return strtoupper(trim($this->title));
        } else {
            return strtoupper(trim($this->slug));
        }
    }


    public function scopeFilter($query, $keyword)
    {
        if (!empty($keyword)) {
            $query->where(function ($query) use ($keyword) {
                $query->where('title', 'LIKE', '%' . $keyword . '%');
                $query->orWhere('slug', 'LIKE', '%' . $keyword . '%');
            });
        }
        return $query;
    }
    public static $positions = ['left' => 'Left Column', 'right' => 'Right Column'];
}

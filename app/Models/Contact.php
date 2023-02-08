<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Contact extends Model
{
    use HasFactory, Sortable;
    public $sortable = ['name','email','subject','created_at', 'updated_at'];
    protected $fillable = ['name','email','subject','message', "is_read", "listing_id"];

    public function listing()
    {
        return $this->belongsTo(\App\Models\Listing::class, 'listing_id');
    }  
}

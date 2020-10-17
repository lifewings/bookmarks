<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bookmark extends Model
{
    protected $table = 'bookmarks';

    protected $fillable = [
        'id',
        'created_at',
        'favicon',
        'page_url',
        'page_title',
        'meta_description',
        'meta_keywords',
    ];

    protected $dates = [
        'created_at'
    ];

    protected $dateFormat = 'Y-m-d H:i:s';

    public $timestamps = false;
}
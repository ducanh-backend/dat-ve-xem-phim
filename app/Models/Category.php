<?php

namespace App\Models;

class Category extends BaseModel {
    protected $table = 'categories';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'slug',
        'description'
    ];
}

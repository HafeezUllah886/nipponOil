<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $primaryKey = 'categoryID';
    protected $table = 'categories';
    protected $guarded = [];

    public function products()
    {
        return $this->hasMany(\App\Models\Product::class, 'categoryID', 'categoryID');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class productModel extends Model
{
    protected $table = 'tblproduct';

     public function categories()
    {
        return $this->belongsToMany(
            categoryModel::class,
            'tblproductcategory', // your pivot table
            'productId',
            'categoryId'
        );
    }
}

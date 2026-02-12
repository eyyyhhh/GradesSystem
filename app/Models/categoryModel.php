<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class categoryModel extends Model
{
      protected $table = 'tblcategory';

       public function products()
    {
        return $this->belongsToMany(
            productModel::class,
            'tblproductcategory',
            'categoryId',
            'productId'
        );
    }
}

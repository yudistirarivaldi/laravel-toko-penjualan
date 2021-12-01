<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductGallery extends Model
{
    

    protected $fillable = [
     'products_id','photo'
    ];

    protected $hidden = [

    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'products_id', 'id');
    }

}

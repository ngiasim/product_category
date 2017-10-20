<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product_image extends Model
{ 
    use SoftDeletes;
    protected $table = 'product_image';
    protected $primaryKey = "product_image_id";

    protected $fillable = ['fk_product','sort_order','image_path'];

    protected function addImage($request){
        $this->fill([
                'fk_product'        => $request['fk_product'],
                'sort_order'        => $request['sort_order'],
                'image_path'        => $request['image_path'],
                'is_default'        => $request['is_default']
        ]);
        $this->save();
    }

    protected function removeImages($product_image_id){
        $image = $this->find($product_image_id);
        $image->delete();
    }

}

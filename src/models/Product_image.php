<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product_image extends Model
{ 
    use SoftDeletes;
    protected $table = 'product_image';
    protected $primaryKey = "product_image_id";

    protected $fillable = ['fk_product','sort_order','image_path','is_default','is_model_view','is_product_view'];

    protected function getProductImagesById($fk_product){
        return $this->where(['fk_product'=>$fk_product])->orderBy('sort_order','asc')->get();
    }

    protected function imageHasDefault($fk_product){
        return $this->where(['fk_product'=>$fk_product,'is_default'=>1])->count();
    }
    

    protected function addImage($request){
        $this->fill([
                'fk_product'        => $request['fk_product'],
                'sort_order'        => $request['sort_order'],
                'image_path'        => $request['image_path'],
                'is_default'        => $request['is_default']
        ]);
        $this->save();
    }

    protected function makeDefault($product_image_id){
        $image = $this->find($product_image_id);
        $product_id = $image->fk_product;

        $last_default = $this->where(['fk_product'=>$product_id,'is_default'=>1])->first();
        $last_default->fill([
                'is_default'        => 0
        ]);
        $last_default->save();


        $image->fill([
                'is_default'        => 1
        ]);
        $image->save();
    }


    protected function updateImageType($request){
        
        $image_type = $this->find($request['image_id']);
        if($request['image_type'] == 'make_model_view'){
            $image_type->fill([
                'is_model_view'        => 1,
                'is_product_view'      => 0
                
            ]);
        }else{
            $image_type->fill([
                'is_model_view'        => 0,
                'is_product_view'      => 1
                
            ]);

        }
        $check = $image_type->save();
        if($check){ return 'success'; }else{ return 'failure'; }
    }


    protected function updateImageSortOrder($request){
        
        $sort_order = $this->find($request['image_id']);
            $sort_order->fill([
                'sort_order'        => $request['sort_order']   
            ]);
        $check = $sort_order->save();
        if($check){ return 'success'; }else{ return 'failure'; }
    }

    
    

    protected function removeImages($product_image_id){
        $image = $this->find($product_image_id);
        $image->delete();
    }

}

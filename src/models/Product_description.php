<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product_description extends Model
{
	use SoftDeletes;
	protected $table = 'product_description';
    protected $primaryKey = "product_description_id";
    

    protected $fillable = ['fk_language','fk_product','products_name','products_description'];

    public function products()
    {
        return $this->belongsTO('App\Models\Product', 'fk_product', 'product_description_id');
    }

    public function language()
    {
        return $this->belongsTO('App\Language', 'fk_language', 'language_id');
    }

    protected function addProductsDescription($request,$fk_product){
        foreach($request->products_name as $key=>$val){

        $products_name             =  (empty($val)?'':$val);
        $products_description      =  (empty($request['products_description'][$key])?'':$request['products_description'][$key]);
     

            $obj = new Product_description;
            $obj->fill([
                'fk_language'            => $key,
                'fk_product'             => $fk_product,
                'products_name'          => $products_name,
                'products_description'   => $products_description 
            ]);
            $obj->save();
        }
            
    }

    protected function addProductsDescriptions($request,$fk_product){
        for($i=1;$i<=2;$i++){
            $obj = new Product_description;
            $obj->fill([
                    'fk_language'           => $i,
                    'fk_product'            => $fk_product,
                    'products_name'         => $request['products_name'],
                    'products_description'  => $request['products_description']
                ]);
            $obj->save();
        }     
    }


    protected function updateProductsDescription($request,$fk_product){
        foreach($request->products_name as $key=>$val){

            $products_name             =  (empty($val)?'':$val);
            $description      =  (empty($request['products_description'][$key])?'':$request['products_description'][$key]);

            $products_description = $this->where(['fk_product'=>$fk_product,'fk_language'=>$key])->first();
            $products_description->fk_language          = $key;
            $products_description->products_name        = $products_name;
            $products_description->products_description = $description;
            $products_description->save();
        }
    }

    protected function updateProductsDescriptions($request,$fk_product){
        for($i=1;$i<=2;$i++){
            $obj = new Product_description;
            $obj = $obj->where(['fk_product'=>$fk_product,'fk_language'=>$i])->first();
            $obj->fill([
                    'fk_language'           => $i,
                    'fk_product'            => $fk_product,
                    'products_name'         => $request['products_name'],
                    'products_description'  => $request['products_description']
                ]);
            $obj->save();
        } 
    }
    
    protected function getAllProductNames(){
            return $this->pluck('products_name','fk_product')->prepend('No Parent',0);
    }

    protected function deleteProductsDescription($fk_product){
        $products_description = $this->find(['fk_product'=>$fk_product])->first();
        $products_description->delete();
    }
}

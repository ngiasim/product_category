<?php

namespace App;

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
        return $this->belongsTO('App\Product', 'fk_product', 'product_description_id');
    }

    protected function rules($except_product_id=""){
        $arr =  array(                 
        );
        /*$arr =  array(        
            'products_name'            => 'required|max:60|unique:products_description,products_name,'.$except_product_id,
            'products_description'     => 'required|max:2000'           
        );
*/
        return $arr;
    }

    protected function addProductsDescription($request,$fk_product){
        foreach($request->products_name as $key=>$val){
            $obj = new Product_description;
            $obj->fill([
                'fk_language'           => $key,
                'fk_product'            => $fk_product,
                'products_name'          => $val,
                'products_description'   => $request['products_description'][$key]
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
            $products_description = $this->where(['fk_product'=>$fk_product,'fk_language'=>$key])->first();
            $products_description->fk_language          = $key;
            $products_description->products_name        = $val;
            $products_description->products_description = $request['products_description'][$key];
            $products_description->save();
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

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
	use SoftDeletes;
    protected $table = 'product';
    protected $primaryKey = "product_id";

    protected $fillable = ['fk_brand','fk_product_status','products_sku','meta_keywords','meta_description','base_price'];


    public function productsDescription()
    {
        return $this->hasOne('App\Models\Product_description', 'fk_product', 'product_id');
    }

    public function productsDescriptions()
    {
        return $this->hasMany('App\Models\Product_description', 'fk_product', 'product_id');
    }

    public function ProductAttribute()
    {
       return $this->hasMany('App\Models\ProductAttribute', 'fk_product', 'product_id');
    }

		public function inventory()
    {
       return $this->hasMany('App\Models\InventoryItem', 'fk_product', 'product_id');
    }

		public function mapProductInventoryItem()
    {
       return $this->hasMany('App\Models\MapProductInventoryItem', 'fk_product', 'product_id');
    }

    protected function rules($except_id=""){
        $arr =  array(
            'meta_keywords'              => 'required|max:200' ,
            'meta_description'           => 'required|max:2000',
            'fk_product_status'          => 'required|integer',
            'products_sku'               => 'required|max:200',
            'base_price'                 => 'required|integer'
        );

        return $arr;
    }

    protected function addProducts($request){
    	/*$this->fill([
                'id_brands'            => $request->id_brands,
                'products_sku'         => $request->products_sku,
                'meta_keywords'        => $request->meta_keywords,
                'meta_description'     => $request->meta_description
            ]);*/

            $this->fill([
                'fk_brand'             => 1,
                'meta_keywords'        => $request['meta_keywords'],
                'meta_description'     => $request['meta_description'],
                'fk_product_status'    => $request['fk_product_status'],
                'products_sku'         => $request['products_sku'],
                'base_price'           => $request['base_price']
            ]);

            $this->save();
            return $this->product_id;
    }

    protected function updateProducts($request,$id){
        $products = $this->find($id);
        $products->meta_keywords        = $request['meta_keywords'];
        $products->meta_description     = $request['meta_description'];
        $products->fk_product_status    = $request['fk_product_status'];
        $products->products_sku         = $request['products_sku'];
        $products->base_price           = $request['base_price'];

        $products->save();
    }

    protected function updateProductSeo($request){
        $products = $this->find($request['product_id']);
        $products->meta_title           = $request['meta_title'];
        $products->meta_keywords        = $request['meta_keywords'];
        $products->meta_description     = $request['meta_description'];

        $products->save();
    }

    protected function deleteProducts($id){
        $products = $this->find($id);
        $products->delete();
    }


}

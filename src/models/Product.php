<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
	use SoftDeletes;
    protected $table = 'product';
    protected $primaryKey = "product_id";

    protected $fillable = ['fk_brand','fk_product_status','products_sku','meta_keywords','meta_description','base_price','percent_off','qty_unlimited','is_global'];


    public function productsDescription()
    {
        return $this->hasOne('App\Models\Product_description', 'fk_product', 'product_id');
    }
    public function productsDescriptions()
    {
        return $this->hasMany('App\Models\Product_description', 'fk_product', 'product_id');
    }

    public function regionalPrices()
    {
        return $this->hasMany('App\Models\Product_regional_price', 'fk_product', 'product_id');
    }
    public function productsStatus()
    {
        return $this->belongsTo('App\Models\Product_status', 'fk_product_status', 'product_status_id');
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

    protected function getMetaDataById($id)
    {
       return $this->with(['productsDescription','productsStatus'])->find($id)->toArray();
    }

    protected function getCreatedAtById($id)
    {
       return $this->where(['product_id'=>$id])->pluck('created_at')->first();
    }
    

    protected function addProducts($request){
    	
        $meta_keywords         =  (empty($request['meta_keywords'])?'':$request['meta_keywords']);
        $meta_description      =  (empty($request['meta_description'])?'':$request['meta_description']);
        $base_price            =  (empty($request['base_price'])?0:$request['base_price']);
        $percent_off           =  (empty($request['percent_off'])?0:$request['percent_off']);
        $qty_unlimited         =  (empty($request['qty_unlimited'])?0:1);
        $is_global             =  (empty($request['is_global'])?0:1);

            $this->fill([
                'fk_brand'             => 1,
                'meta_keywords'        => $meta_keywords,
                'meta_description'     => $meta_description,
                'fk_product_status'    => $request['fk_product_status'],
                'products_sku'         => $request['products_sku'],
                'base_price'           => $base_price,
                'percent_off'          => $percent_off,
                'qty_unlimited'        => $qty_unlimited,
                'is_global'            =>  $is_global
            ]);

            $this->save();
            return $this->product_id;
    }

    protected function updateProducts($request,$id){

        $meta_keywords         =  (empty($request['meta_keywords'])?'':$request['meta_keywords']);
        $meta_description      =  (empty($request['meta_description'])?'':$request['meta_description']);
        $base_price            =  (empty($request['base_price'])?0:$request['base_price']);
        $percent_off           =  (empty($request['percent_off'])?0:$request['percent_off']);
        $qty_unlimited         =  (empty($request['qty_unlimited'])?0:1);
        $is_global             =  (empty($request['is_global'])?0:1);

        $products = $this->find($id);
        $products->meta_keywords        = $meta_keywords;
        $products->meta_description     = $meta_description;
        $products->fk_product_status    = $request['fk_product_status'];
        $products->products_sku         = $request['products_sku'];
        $products->base_price           = $base_price;
        $products->percent_off           = $percent_off;
        $products->qty_unlimited        = $qty_unlimited;
        $products->is_global            = $is_global;

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

	public function getProductOptionByProductId($product_id)
	{
			$product_option = Product::where('product_id', '=', $product_id)
			->with(array('ProductAttribute' => function($query) {
						$query->with(array('productOption' => function($query2) {
							$query2->with('productOptionValue');
					 }));
				}))
				->get();
				return $product_option;
	}
}

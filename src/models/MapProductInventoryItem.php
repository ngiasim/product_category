<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MapProductInventoryItem extends Model
{
	use SoftDeletes;
    protected $table = 'map_product_inventory_item';
    protected $primaryKey = "map_product_inventory_item_id";

    //protected $fillable = ['fk_brand','fk_product_status','products_sku','meta_keywords','meta_description','base_price'];


    // public function inventoryItem()
    // {
    //      return $this->belongsTO('App\Models\InventoryItem', 'fk_inventory_item', 'inventory_item');
    // }

		public function product()
		{
			return $this->belongsTO('App\Models\Product', 'fk_product', 'product_id');
      //return $this->hasOne('App\Models\ProductOption', 'fk_product_option', 'product_option_id');
		}

		public function inventory()
		{
			return $this->belongsTO('App\Models\InventoryItem', 'fk_inventory_item', 'inventory_id');
		}

    // public function ProductAttribute()
    // {
    //    return $this->hasMany('App\Models\ProductAttribute', 'fk_product', 'product_id');
    // }

    // protected function rules($except_id=""){
    //     $arr =  array(
    //         'meta_keywords'              => 'required|max:200' ,
    //         'meta_description'           => 'required|max:2000',
    //         'fk_product_status'          => 'required|integer',
    //         'products_sku'               => 'required|max:200'
    //     );
    //
    //     return $arr;
    // }

    protected function addProducts($request){
    	/*$this->fill([
                'id_brands'            => $request->id_brands,
                'products_sku'         => $request->products_sku,
                'meta_keywords'        => $request->meta_keywords,
                'meta_description'     => $request->meta_description
            ]);*/

            // $this->fill([
            //     'fk_brand'             => 1,
            //     'meta_keywords'        => $request['meta_keywords'],
            //     'meta_description'     => $request['meta_description'],
            //     'fk_product_status'    => $request['fk_product_status'],
            //     'products_sku'         => $request['products_sku'],
            //     'base_price'           => $request['base_price']
            // ]);
						//
            // $this->save();
            // return $this->product_id;
    }

    protected function updateProducts($request,$id){
        // $products = $this->find($id);
        // $products->meta_keywords        = $request['meta_keywords'];
        // $products->meta_description     = $request['meta_description'];
        // $products->fk_product_status    = $request['fk_product_status'];
        // $products->products_sku         = $request['products_sku'];
        // $products->base_price         = $request['base_price'];
				//
        // $products->save();
    }

    protected function deleteMapping($product_id,$inventory_id){
        $map = $this->where("fk_product","=",$product_id)->where("fk_inventory_item","=",$inventory_id);
        $map->delete();
    }


}

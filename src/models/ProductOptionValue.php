<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductOptionValue extends Model
{ //check
	  use SoftDeletes;
    protected $table = 'product_option_value';
    protected $primaryKey = "product_option_value_id";

		// public function ProductAttribute()
		// {
		// 		 return $this->hasMany('App\ProductAttribute','fk_product_option','product_option_id');
		// }

    //protected $fillable = ['fk_brand','fk_product_status','products_sku','meta_keywords','meta_description'];
		public function productOption()
	  {
	       return $this->belongsTo('App\ProductOption','fk_product_option','product_option_id');
	  }
		//
		// public function productoption()
		// 	{
		// 	 return $this->hasMany('App\ProductOption', 'fk_product_option', 'product_option_id');
		// 	}

		/*public function getOrdersByFilters($filter) {

			$data = $this->orderBy('order_id','DESC');

		  if(count($filter))
		  {
			   if(!empty($filter['customer_id']))
			   {
					 $data = $data->where('fk_customer',  '=', $filter['customer_id']);
			   }

		  }
				return $data->get();

			  // return $this->all();
		}*/

    // public function productsDescription()
    // {
    //     return $this->hasOne('App\Product_description', 'fk_product', 'product_id');
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

    // protected function addProducts($request){
    // 	/*$this->fill([
    //             'id_brands'            => $request->id_brands,
    //             'products_sku'         => $request->products_sku,
    //             'meta_keywords'        => $request->meta_keywords,
    //             'meta_description'     => $request->meta_description
    //         ]);*/
		//
    //         $this->fill([
    //             'fk_brand'            => 1,
    //             'meta_keywords'        => $request->meta_keywords,
    //             'meta_description'     => $request->meta_description,
    //             'fk_product_status'    => $request->fk_product_status,
    //             'products_sku'         => $request->products_sku
    //         ]);
		//
    //         $this->save();
    //         return $this->product_id;
    // }

    // protected function updateProducts($request,$id){
    //     $products = $this->find($id);
    //     $products->meta_keywords        = $request->meta_keywords;
    //     $products->meta_description     = $request->meta_description;
    //     $products->fk_product_status    = $request->fk_product_status;
    //     $products->products_sku         = $request->products_sku;
    //     $products->save();
    // }
		//
    // protected function deleteProducts($id){
    //     $products = $this->find($id);
    //     $products->delete();
    // }


}

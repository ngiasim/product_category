<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product_regional_price extends Model
{
    use SoftDeletes;
    protected $table = 'product_regional_price';
    protected $primaryKey = "product_regional_price_id";

    protected $fillable = ['fk_region','fk_product','price','currency_code'];

    public function region()
    {
        return $this->belongsTo('App\Models\Region', 'fk_region', 'region_id');
    }

    protected function addPriceRules($except_id=""){
        $arr =  array(
            'price'           => 'required|not_in:0|regex:/^\d*(\.\d{1,2})?$/'
        );
        return $arr;
    }

    protected function updatePriceRules($except_id=""){
        $arr =  array(
            'price'           => 'required|not_in:0|regex:/^\d*(\.\d{1,2})?$/'   
        );
        return $arr;
    }

    /*
    protected function getAllRegionsWithCountries()
    {
        return $this->with(array('country'))->get();
    }

    protected function getSingleRegionById($id)
    {
        return $this->with(array('country' => function($query) {
              $query->orderBy('name','asc');
             }))->find($id);
    }

    public function country()
    {
        return $this->hasMany('App\Models\Country', 'fk_region', 'region_id');
    }*/


    protected function addPrice($request,$fk_product){

        foreach($request->price as $fk_region=>$price){

            $already_exists = $this->where(['fk_region'=>$fk_region,'fk_product'=>$fk_product])->first();
            if(empty($already_exists)){
                $obj = new Product_regional_price;
                $obj->fill([
                    'fk_region'      => $fk_region,
                    'fk_product'     => $fk_product,
                    'price'          => $price
                ]);
                $obj->save();
            }else{
                $this->updatePrice($already_exists->product_regional_price_id,$price);
            }
        }
    }

    protected function updatePrice($id,$price){

        $obj = $this->find($id);
        $obj->price  = $price;
        $obj->save();
    }

    /*
    protected function getAllRegions(){
        return $this->orderBy('name','asc')->pluck('name','region_id')->toArray();
    }

    protected function deleteRegion($id){
        $region = $this->find($id);
        $region->delete();
    }*/


    

    

}

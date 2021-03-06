<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category_description extends Model
{
	use SoftDeletes;
	protected $table = 'category_description';
    protected $primaryKey = "category_description_id";

    protected $fillable = ['fk_language','fk_category','category_name','category_description'];

    public function categories()
    {
        return $this->belongsTO('App\Models\Category', 'fk_category', 'id');
    }

    // public function language()
    // {
    //     return $this->hasOne('App\Language', 'fk_language', 'language_id');
    // }

    public function language()
    {
        return $this->belongsTO('App\Language', 'fk_language', 'language_id');
    }

    protected function getDescriptionsByProductId($id){
        return $this->where(['fk_category'=>$id])->get();
    }

    protected function rules($except_category_id=""){
        $arr =  array(        
            'category_name.*'              => 'required|max:60',
            'category_description.*'     => 'required|max:2000'           
        );

       /* if($except_category_id!=""){ 
            $arr['category_name'] = 'required|max:60|unique:categories_description,category_name,'.$except_category_id; 
        }*/
        return $arr;
    }

    protected function addCategoriesDescription($request,$fk_category){
        foreach($request['category_name'] as $key=>$val){
            $obj = new Category_description;
            $obj->fill([
                'fk_language'            => $key,
                'fk_category'            => $fk_category,
                'category_name'          => $val,
                'category_description'   => $request['category_description'][$key]
            ]);
            $obj->save();
        }
    }

    protected function updateCategoriesDescription($request,$fk_category){
        foreach($request->category_name as $key=>$val){
            $categories_description = $this->where(['fk_category'=>$fk_category,'fk_language'=>$key])->first();
            $categories_description->fk_language            = $key;
            $categories_description->category_name          = $val;
            $categories_description->category_description   = $request['category_description'][$key];
            $categories_description->save();
        }
    }
    
    protected function getAllCategoryNames(){
            return $this->pluck('category_name','fk_category')->prepend('No Parent',0);
    }

    protected function deleteCategoriesDescription($fk_category){
        $categories_description = $this->where(['fk_category'=>$fk_category]);
        //foreach()
        $categories_description->delete();
    }
}

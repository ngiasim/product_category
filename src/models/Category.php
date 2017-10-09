<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
	use SoftDeletes;
    protected $table = 'category';
    protected $primaryKey = "category_id";


    protected $fillable = ['id_parent','category_link','sort_order','meta_keywords','meta_description'];

    public function children()
    {
       return $this->hasMany('App\Category', 'id_parent');
    }

    // recursive, loads all descendants
    public function childrenRecursive()
    {
       return $this->children()->with(['childrenRecursive','categoriesDescription']);
       // which is equivalent to:
       // return $this->hasMany('Survey', 'parent')->with('childrenRecursive);
    }

    // parent
    public function parent()
    {
       return $this->belongsTo('App\Category','id_parent');
    }

    // all ascendants
    public function parentRecursive()
    {
       return $this->parent()->with(['parentRecursive','categoriesDescription']);
    }


    public function categoriesDescription()
    {
        return $this->hasOne('App\Category_description', 'fk_category', 'category_id');
    }

    protected function rules($except_id=""){
        $arr =  array(
            'id_parent'                  => 'required|integer',            
            'sort_order'                 => 'required|integer',
            'meta_keywords'              => 'required|max:200' ,
            'meta_description'           => 'required|max:2000', 
            'category_link'              => 'required|max:200'       
        );
        /*if($except_id!=""){ $arr['sort_order'] = 'required|integer|unique:categories,sort_order,'.$except_id; }*/
        return $arr;
    }

    protected function addCategories($request){
    	$this->fill([
                'id_parent'            => $request->id_parent,
                'category_link'        => $request->category_link,
                'sort_order'           => $request->sort_order,
                'meta_keywords'        => $request->meta_keywords,
                'meta_description'     => $request->meta_description
            ]);

            $this->save();
            return $this->category_id;
    }

    protected function updateCategories($request,$id){
        $categories = $this->find($id);
        $categories->id_parent          = $request->id_parent;
        $categories->category_link    = $request->category_link;
        $categories->sort_order         = $request->sort_order;
        $categories->meta_keywords      = $request->meta_keywords;
        $categories->meta_description   = $request->meta_description;
        $categories->save();
    }

    protected function deleteCategory($id){
        $categories = $this->find($id);
        $categories->delete();
    }

    
}

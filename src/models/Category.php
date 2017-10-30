<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Map_product_category;

class Category extends Model
{
	use SoftDeletes;
    protected $table = 'category';
    protected $primaryKey = "category_id";


    protected $fillable = ['id_parent','category_link','sort_order','meta_keywords','meta_description'];

    public function children()
    {
       return $this->hasMany('App\Models\Category', 'id_parent');
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
       return $this->belongsTo('App\Models\Category','id_parent');
    }

    // all ascendants
    public function parentRecursive()
    {
       return $this->parent()->with(['parentRecursive','categoriesDescription']);
    }


    public function categoriesDescription()
    {
        return $this->hasOne('App\Models\Category_description', 'fk_category', 'category_id');
    }

    public function categoriesDescriptions()
    {
        return $this->hasMany('App\Models\Category_description', 'fk_category', 'category_id');
    }

    protected function getSingleCategoryById($id)
    {
        return $this->with(array('categoriesDescriptions' => function($query) {
               $query->with(array('language'));
           }))->find($id);
    }

    


    protected $globalRecursive = array();
    protected $globalIteration = 0;
    // Return Global Array of Database Fetched Categories Tree
    protected function getCategoriesTree()
    {
        $childrenRecursive = $this->with(['childrenRecursive','categoriesDescription'])->where('id_parent', 0)->get()->toArray();
        $this->getCategoriesRecursive($childrenRecursive,0); 
        return $this->globalRecursive;
    }

    // Recursive Function To Get Categories Tree In Global Array
    private function getCategoriesRecursive($cat,$indent=0)
    {      
        foreach($cat as $row){
            $span = '<span class="glyphicon glyphicon-triangle-right"></span>';
            if($row['id_parent'] == 0){ $span = ''; }

            $this->globalRecursive[$this->globalIteration]['category_id'] = $row['category_id'];
            $this->globalRecursive[$this->globalIteration]['category_name'] = str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',$indent).$span.' '.$row['categories_description']['category_name'];
            $this->globalRecursive[$this->globalIteration]['sort_order'] = $row['sort_order'];
            $this->globalRecursive[$this->globalIteration]['products'] = Map_product_category::where(['fk_category' => $row['category_id']])->count();
            
            $this->globalIteration++;
            if (!empty($row['children_recursive'])){
                $this->getCategoriesRecursive($row['children_recursive'],$indent+1);
            }
                
        }    
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
                'id_parent'            => $request['id_parent'],
                'category_link'        => $request['category_link'],
                'sort_order'           => $request['sort_order'],
                'meta_keywords'        => $request['meta_keywords'],
                'meta_description'     => $request['meta_description']
            ]);

            $this->save();
            return $this->category_id;
    }

    protected function updateCategories($request,$id){
        $categories = $this->find($id);
        $categories->id_parent          = $request->id_parent;
        $categories->category_link      = $request->category_link;
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

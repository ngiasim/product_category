<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Map_product_category extends Model
{
	use SoftDeletes;
	protected $table = 'map_product_category';
	protected $primaryKey = "map_product_category_id";

    protected $fillable = ['fk_category','fk_product','sort_order'];

    protected function getAllMapped($fk_product){
    	return $this->select('map_product_category_id','fk_category', 'fk_product')->where(['fk_product'=>$fk_product])->get();
    }

    protected function addProductCategory($request){
            
        $alreadyExists = $this->where(['fk_category'=>$request['category_id'],'fk_product'=>$request['product_id']])->first();
        if(empty($alreadyExists)){
            $this->fill([
                'fk_category'           => $request['category_id'],
                'fk_product'            => $request['product_id'],
                'sort_order'            => 1
            ]);
            $this->save();
            return array('category_id'=>$request['category_id'],'tag_id'=>$this->map_product_category_id);
        }
        return '';

    }

    protected function deleteTags($id){
        $tags = $this->find($id);
        $tags->delete();
    }
    
    
}



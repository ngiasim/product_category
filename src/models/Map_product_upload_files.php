<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Map_product_upload_files extends Model
{
	use SoftDeletes;
	protected $table = 'map_product_upload_files';
	protected $primaryKey = "map_product_upload_files_id";

    protected $fillable = ['fk_product','fk_upload_files','sort_order'];


    protected function addProductFileId($request){  
            $this->fill([
                'fk_upload_files'       => $request['fk_upload_files'],
                'fk_product'            => $request['fk_product'],
                'sort_order'            => 1
            ]);
            $this->save();
    }

}



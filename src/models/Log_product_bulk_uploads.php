<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Log_product_bulk_uploads extends Model
{
	use SoftDeletes;
	protected $table = 'log_product_bulk_uploads';
	protected $primaryKey = "log_product_bulk_uploads_id";

    protected $fillable = ['fk_product','fk_bulk_uploads'];


    protected function addProductFileId($request){  
            $this->fill([
                'fk_bulk_uploads'       => $request['fk_bulk_uploads'],
                'fk_product'            => $request['fk_product']
            ]);
            $this->save();
    }

}



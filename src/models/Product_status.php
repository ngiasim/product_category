<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product_status extends Model
{
	use SoftDeletes;
	protected $table = 'product_status';
    	protected $fillable = ['status_code','status_name'];

    	protected function getAllStatuses() {
            	return $this->pluck('status_name','product_status_id');
    	}
}

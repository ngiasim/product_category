<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Language extends Model
{
	use SoftDeletes;
	protected $table = 'language';
	protected $primaryKey = "language_id";

    protected $fillable = ['name','code'];

    /*protected function getAllLanguages(){
            return $this->pluck('name','id');
    }*/

    protected function getAllLanguages(){
    	return $this->select('language_id', 'name', 'code', 'direction')->get();
    }


    
}

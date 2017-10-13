<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bulk_uploads extends Model
{
	use SoftDeletes;
	protected $table = 'bulk_uploads';
	protected $primaryKey = "bulk_uploads_id";

    protected $fillable = ['file_name','file_path','rows_count','summary','status'];

    protected function getUploadedFileById($bulk_uploads_id){
    	return $this->where(['bulk_uploads_id'=>$bulk_uploads_id])->get();
    }

    protected function addUploadedFile($request){
        
            $this->fill([
                'file_name'            => $request['file_name'],
                'file_path'            => $request['file_path']
            ]);
            $this->save();
            return $this->bulk_uploads_id;
    }

    protected function updateUploadedFile($bulk_uploads_id,$rows_count=0,$status=0,$summary=''){
           
        $file = $this->find($bulk_uploads_id);

        if($rows_count!=0){
            $file->rows_count       = $rows_count;
        }
        if($status!=0){
            $file->status       = $status;
        }
        if($summary!=''){
            $file->summary       = $summary;
        }
        $file->save();

    }

    
}



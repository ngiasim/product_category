<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Upload_files extends Model
{
	use SoftDeletes;
	protected $table = 'upload_files';
	protected $primaryKey = "upload_files_id";

    protected $fillable = ['file_name','file_path','rows_count','products_added','categories_added','status'];

    protected function getUploadedFileById($upload_files_id){
    	return $this->where(['upload_files_id'=>$upload_files_id])->get();
    }

    protected function addUploadedFile($request){
        
            $this->fill([
                'file_name'            => $request['file_name'],
                'file_path'            => $request['file_path']
            ]);
            $this->save();
            return $this->upload_files_id;
    }

    protected function updateRowCount($request,$upload_files_id){
            
            $file = $this->find($upload_files_id);
            $file->fill([
                'rows_count'            => $request['rows_count']
            ]);
            $file->save();
    }

    protected function updateStatus($request,$upload_files_id){
            
            $file = $this->find($upload_files_id);
            $file->fill([
                'status'            => $request['status']
            ]);
            $file->save();
    }

    protected function updateProductsCount($request,$upload_files_id){
            
            $file = $this->find($upload_files_id);
            $file->fill([
                'products_added'        => $request['products_added']
            ]);
            $file->save();
    }

    protected function updateCategoriesCount($request,$upload_files_id){
            
            $file = $this->find($upload_files_id);
            $file->fill([
                'categories_added'      => $request['categories_added']
            ]);
            $file->save();
    }

    
}



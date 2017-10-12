<?php

namespace App\Jobs;

use App\Upload_files;
use App\Product;
use App\Product_description;
use App\Category;
use App\Category_description;
use App\Map_product_category;
use App\Map_product_upload_files;
use DB;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UploadProductsCsvJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $upload_files_id;
    protected $path;
    protected $cat_count=1;

    public function __construct($upload_files_id,$path)
    {
        $this->upload_files_id  = $upload_files_id;
        $this->path             = $path;
    }


    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        ini_set('max_execution_time', 45000); 
            $fileD = fopen($this->path,"r");
            $column=fgetcsv($fileD);
            while(!feof($fileD)){
             $rowData[]=fgetcsv($fileD);
            }
            
            $response = array();
            $rows_count = count($rowData);

            $record = array('rows_count'=>$rows_count);
            Upload_files::updateRowCount($record,$this->upload_files_id); 

            $record = array('status'=>1);
            Upload_files::updateStatus($record,$this->upload_files_id); 

            $product_count=1;
            try {
                foreach ($rowData as $key => $value) {
                    if($value[0] != ''){

                        
                            $product['products_sku'] = $value[0];
                            $product['meta_keywords'] = $value[1];
                            $product['meta_description'] = $value[2];
                            $product['fk_product_status'] = 1;
                            $product['base_price'] = $value[8];

                            $product_desc['products_name'] = $value[3];
                            $product_desc['products_description'] = $value[4];

                            $lookup = array('English'=>$value[7]);
                            $category_id = $this->categoriesLookup($lookup);


                        $alreadyExists = Product::where(['products_sku'=>$value[0]])->first();

                        if(empty($alreadyExists)){ 
                            $fk_product = Product::addProducts($product); 
                            Product_description::addProductsDescriptions($product_desc,$fk_product); 
                           // $response[] = array('sku'=>$product['products_sku'],'name'=>$product_desc['products_name'],'status'=>'Product Added','color'=>'#60d45e');

                            $product_category_map['category_id'] = $category_id;
                            $product_category_map['product_id'] = $fk_product;
                            Map_product_category::addProductCategory($product_category_map); 

                            $record = array('fk_upload_files'=>$this->upload_files_id,'fk_product'=>$fk_product);
                            Map_product_upload_files::addProductFileId($record); 

                        }else{
                            Product::updateProducts($product,$alreadyExists['product_id']); 
                            //$response[] = array('sku'=>$product['products_sku'],'name'=>$product_desc['products_name'],'status'=>'Product Updated','color'=>'#6487d6');
                            
                            $record = array('fk_upload_files'=>$this->upload_files_id,'fk_product'=>$alreadyExists['product_id']);
                            Map_product_upload_files::addProductFileId($record); 

                        }

                        $record = array('products_added'=>$product_count);
                        Upload_files::updateProductsCount($record,$this->upload_files_id); 

                        $product_count++;
                        
                    }
                }
            }
            catch(Exception $e) {
                $record = array('status'=>2);
                Upload_files::updateStatus($record,$this->upload_files_id); 
            }
        $record = array('status'=>3);
        Upload_files::updateStatus($record,$this->upload_files_id); 
    }

    public function failed(Exception $exception)
    {
        $record = array('status'=>2);
        Upload_files::updateStatus($record,$this->upload_files_id); 
    }

    public function categoriesLookup($lookup)
    {
        $arr = explode("|",$lookup['English']);
        $total = count($arr);
        $id_parent = 0;
        $last_created = false;
        $cat = array();
        for($i=0;$i<$total;$i++){
            $cat_name = $arr[$i];
            if(!$last_created){
               
               //$cat = Category_description::select('fk_category')->where(['category_name'=>$cat_name])->first();
               //$cat = Category::select('category_id')->with('categoriesDescription')->where(['id_parent'=>$id_parent,'category_description.category_name'=>$cat_name])->first();

                $cat = DB::table('category')
                ->join('category_description', 'category.category_id', '=', 'category_description.fk_category')
                ->select('category.category_id')
                ->where(['category.id_parent'=>$id_parent,'category_description.category_name'=>ucwords($cat_name)])

                ->first();


                //->where(['category.id_parent'=>$id_parent,'category_description.category_name'=>$cat_name])

                //strtolower()
                // Converting Std Class object to array 
                $cat = json_decode(json_encode($cat), true);

            }
            if(empty($cat)){
                $request = array();
                $request['id_parent'] = $id_parent;
                $request['category_link'] = $cat_name;
                $request['sort_order'] = 0;
                $request['meta_keywords'] = $cat_name;
                $request['meta_description'] = $cat_name;
                $category_id = Category::addCategories($request); 

                $request['category_name'] = [1=>ucwords($cat_name),2=>$cat_name];
                $request['category_description'] = [1=>$cat_name,2=>$cat_name];
                Category_description::addCategoriesDescription($request,$category_id); 

                $record = array('categories_added'=>$this->cat_count);
                Upload_files::updateCategoriesCount($record,$this->upload_files_id); 
                $this->cat_count++;

                $last_created = true;
                $id_parent = $category_id;
            }else{
                $id_parent = $cat['category_id'];
            }
        }
        return $id_parent;
    }
}

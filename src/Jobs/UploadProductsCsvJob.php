<?php

namespace App\Jobs;

use App\Bulk_uploads;
use App\Models\Product;
use App\Models\Product_description;
use App\Models\Category;
use App\Models\Category_description;
use App\Models\Map_product_category;
use App\Models\Log_product_bulk_uploads;
use DB;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UploadProductsCsvJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    protected $bulk_uploads_id;
    protected $path;
    protected $count_categories_added=0;

    public function __construct($bulk_uploads_id,$path)
    {
        $this->bulk_uploads_id  = $bulk_uploads_id;
        $this->path             = $path;
    }


    public function handle()
    {
        ini_set('max_execution_time', 45000); 
            $fileD = fopen($this->path,"r");
            $column=fgetcsv($fileD);
            while(!feof($fileD)){
             $rowData[]=fgetcsv($fileD);
            }
            
            $response = array();
            $rows_count = count($rowData)-1; 
            $summary = json_encode(array('products_added'=>0,'products_updated'=>0,'categories_added'=>0));

            Bulk_uploads::updateUploadedFile($this->bulk_uploads_id,$rows_count,1,$summary); 

            $count_products_added   = 0;
            $count_products_updated = 0;
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

                            $record = array('fk_bulk_uploads'=>$this->bulk_uploads_id,'fk_product'=>$fk_product);
                            Log_product_bulk_uploads::addProductFileId($record); 

                            $count_products_added++;
                        }else{
                            Product::updateProducts($product,$alreadyExists['product_id']); 
                            Product_description::updateProductsDescriptions($product_desc,$alreadyExists['product_id']); 
                            //$response[] = array('sku'=>$product['products_sku'],'name'=>$product_desc['products_name'],'status'=>'Product Updated','color'=>'#6487d6');
                            
                            $record = array('fk_bulk_uploads'=>$this->bulk_uploads_id,'fk_product'=>$alreadyExists['product_id']);
                            Log_product_bulk_uploads::addProductFileId($record); 

                            $count_products_updated++;
                        }


                    $summary = json_encode(array('products_added'=>$count_products_added,'products_updated'=>$count_products_updated,'categories_added'=>$this->count_categories_added));
                    Bulk_uploads::updateUploadedFile($this->bulk_uploads_id,0,0,$summary);  
    
                    }
                }
            }
            catch(Exception $e) {
                Bulk_uploads::updateUploadedFile($this->bulk_uploads_id,0,2); 
            }

        Bulk_uploads::updateUploadedFile($this->bulk_uploads_id,0,3); 
    }

    public function failed(Exception $exception)
    {
        Bulk_uploads::updateUploadedFile($this->bulk_uploads_id,0,2); 
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
               
                $cat = DB::table('category')
                ->join('category_description', 'category.category_id', '=', 'category_description.fk_category')
                ->select('category.category_id')
                ->where(['category.id_parent'=>$id_parent,'category_description.category_name'=>ucwords($cat_name)])

                ->first();

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
 
                $this->count_categories_added++;

                $last_created = true;
                $id_parent = $category_id;
            }else{
                $id_parent = $cat['category_id'];
            }
        }
        return $id_parent;
    }
}

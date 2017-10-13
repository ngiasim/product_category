<?php

namespace Ngiasim\Categories;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Product;
use App\Product_description;
use App\Language;
use App\Product_status;
use App\Category;
use App\Category_description;
use App\Map_product_category;
use App\Bulk_uploads;
use Carbon\Carbon;

use App\Jobs\UploadProductsCsvJob;

use DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;

class ProductController extends Controller
{
    protected $globalRecursive = array();
    protected $globalIteration = 0;

    public function index()
    {
        $products =  Product::with('productsDescription')->take(100)->get();
        return view('products::index',compact('products'));
    }

    public function selectAll()
    {
       $products =  Product::get();
       $results = ["sEcho" => 1,
            "iTotalRecords" => count($products),
            "iTotalDisplayRecords" => count($products),
            "aaData" => $products ];
       echo json_encode($results);
    }
    
    public function create()
    {   
        $categories = $this->getCategoriesTree();
        $languages = Language::getAllLanguages();
        $statuses = Product_status::getAllStatuses();
        return view('products::create',compact('languages','statuses','categories'));
    }

    public function store(Request $request)
    {
        // Validating Inputs
        $validator = Validator::make(Input::all(),array_merge(Product::rules(),Product_description::rules()));

        // If Validation Fails
        if ($validator->fails()) {
            $messages = $validator->messages();
            return Redirect::to('products/create')->withErrors($validator)->withInput();
        }
        // On Validation Success
        else{
            // Inserting Data in Products and Product_description Table
            $product_id = Product::addProducts($request); 
            Product_description::addProductsDescription($request,$product_id);
            
            return redirect('/products');
        }
    }
   
    public function show($id)  
    {
        //
    }

    
    public function edit($id)
    {
        $edit_products = Product::find($id);
        $edit_products_description = Product_description::where(['fk_product'=>$id])->get();
        $languages = Language::getAllLanguages();
        $statuses = Product_status::getAllStatuses();
        $categories = $this->getCategoriesTree();
        $get_mapped_category_ids = Map_product_category::where(['fk_product'=>$id])->pluck('fk_category')->toArray();
        $get_mapped_ids = Map_product_category::where(['fk_product'=>$id])->orderBy('fk_category','asc')->pluck('map_product_category_id')->toArray();
        $get_mapped_categories =$this->getParentCategories($get_mapped_category_ids);
        return view('products::edit',compact('languages','statuses','edit_products','edit_products_description','id','categories','get_mapped_categories','get_mapped_ids'));
    }

    public function update(Request $request, $product_id)
    {
        // Validating Inputs
        $validator = Validator::make(Input::all(),array_merge(Product::rules($product_id),Product_description::rules($product_id)));

        if ($validator->fails()) {
            $messages = $validator->messages();
            return Redirect::to('products/'.$product_id.'/edit')->withErrors($validator)->withInput();
        }else{

            // Updating Data in Categories and Categories_description Table
            Product::updateProducts($request,$product_id); 
            Product_description::updateProductsDescription($request,$product_id); 
            
            return redirect('/products');
        }
    }



    private function getParentCategories($cat_ids=array())
    {      
        $childrenRecursive = Category::with(['parentRecursive','categoriesDescription'])->whereIn('category_id', $cat_ids)->get()->toArray();
        $cat_names = array();
        foreach($childrenRecursive as $key=>$row){
            
            $cat_names[$key][] =  $row['categories_description']['category_name'];
            while ($row = $row['parent_recursive']) {
                    $cat_names[$key][] = $row['categories_description']['category_name'];
                
            }
        }
        return $cat_names;
    }

    public function addTags(Request $request)
    {    
        $ids = Map_product_category::addProductCategory($request); 
        $array = array('status'=>'success','message'=>'Record added successfully.');
        if($ids == ''){ $array['status'] = 'failure'; $array['message'] = 'Record already exists!'; }
        else{
            $get_mapped_category_name =$this->getParentCategories(array($ids['category_id']));
            foreach($get_mapped_category_name as $row){
                $category_name = implode(' > ',array_reverse($row));
                $array['category'] = $category_name;
            }
            $array['tag_id'] = $ids['tag_id'];
        }
        return response()->json($array);

    }
    
    public function removeTags($tag_id)
    {
        Map_product_category::deleteTags($tag_id);
        return redirect()->back();
    }

    public function uploadCSV()
    {
        $response = '';
        return view('products::bulkupload',compact('response'));
    }

    public function storeCSV(Request $request){
            
            $response = '';

            //$get_contents = file_get_contents($request->upload_csv->getPathName());
            $file_name = time().'.'.$request->upload_csv->getClientOriginalExtension();
            $request->upload_csv->move(public_path('files/products/'), $file_name);

            $file_path = public_path('files/products/');
            $record['file_name'] = $file_name;
            $record['file_path'] = $file_path;

            $bulk_uploads_id = Bulk_uploads::addUploadedFile($record);
            $path = public_path('files/products/').$file_name;


            UploadProductsCsvJob::dispatch($bulk_uploads_id,$path);
            //exec('cd ../ && php artisan queue:work --queue=default --timeout=1800 --tries=1');

            $response = $bulk_uploads_id;
            return view('products::bulkupload',compact('response'));    
    }
    
    public function uploadStatus($bulk_uploads_id){
            
            $response = Bulk_uploads::getUploadedFileById($bulk_uploads_id);
            return view('products::uploadstatus',compact('response'));    
    }

    public function ajaxUploadStatus(Request $request){
            
        $response = Bulk_uploads::getUploadedFileById($request['bulk_uploads_id']);
        $arr = array();
        foreach($response as $row){
            $arr['rows_count']          = $row['rows_count'];
            $summary = json_decode($row['summary']);
            $arr['products_added']      = $summary->products_added;
            $arr['products_updated']    = $summary->products_updated;
            $arr['categories_added']    = $summary->categories_added;
            
            $arr['percent'] = round((($summary->products_added+$summary->products_updated) / $row['rows_count'] ) * 100,2).' %';

            if($row['status'] ==0){
                $arr['status'] = 'Uploading has not started yet.';
            }
            elseif($row['status'] ==1){
                $arr['status'] = 'Uploading started.';
            }
            elseif($row['status'] ==2){
                $arr['status'] = 'Uploading Error.';
            }
            else{
                $arr['status'] = 'Successfully Uploaded';
            }
        }

        return response()->json($arr);  
    }
    
    public function triggerQueue(Request $request){   
       
       exec('cd ../ && php artisan queue:work --tries=1');

    }
    

    public function destroy($product_id)
    {
        Product::deleteProducts($product_id);
        Product_description::deleteProductsDescription($product_id);
        return redirect('/products');
    }

    // Return Global Array of Database Fetched Categories Tree
    private function getCategoriesTree()
    {
        $childrenRecursive = Category::with(['childrenRecursive','categoriesDescription'])->where('id_parent', 0)->get()->toArray();
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
            $this->globalRecursive[$this->globalIteration]['category_name'] = str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',$indent).$span.' '.$row['categories_description']['category_name'];
            $this->globalRecursive[$this->globalIteration]['category_description'] = $row['categories_description']['category_description'];
            
            $this->globalIteration++;
            if (!empty($row['children_recursive'])){
                $this->getCategoriesRecursive($row['children_recursive'],$indent+1);
            }
                
        }    
    }
}

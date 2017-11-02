<?php

namespace Ngiasim\Categories;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Product_description;
use App\Language;
use App\Models\Product_status;
use App\Models\Category;
use App\Models\Category_description;
use App\Models\Map_product_category;
use App\Models\Product_image;
use App\Models\Region;
use App\Models\Product_regional_price;

use App\Bulk_uploads;

use DataTables;
use Image;
use File;
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
        //$products =  Product::with('productsDescription')->take(100)->get();
        $products =  Product::with('productsDescription')->simplePaginate(100);
        $page_title = "Product";
        return view('products::index',compact('products','page_title'));
    }

    public function getProducts()
    {
        //return \DataTables::of(Product::get())->make(true);
        $data = Product::select('product_id','products_sku','base_price')->with('productsDescription');
        $response = $this->makeDatatable($data);
        return  $response;
    }

    function makeDatatable ($data)
    {

        return \DataTables::of($data)
        ->addColumn('id', function ($product) {

            $return = '';
            $return .= '<a title="View Product" target="_blank" class="actionLink" href="/products/'.$product->product_id.'">'.$product->product_id.'</a> ';
            return $return;

        })
        ->addColumn('products_name', function ($product) {

            $return = $product['productsDescription']['products_name'];
            return $return;

        })
        ->addColumn('quantity', function ($product) {

            $total = $this->getInventoryCountByProductId($product->product_id);
            return $total;

        })
        ->addColumn('products_sku', function ($product) {

            $return = $product->products_sku;
            return $return;

        })
        ->addColumn('price', function ($product) {

            $return = $product->base_price.' -AED';
            return $return;

        })
        ->addColumn('action', function ($product) {

            $return = '';
            //$return .= '<a title="Show Inventories" class="actionLink" href="/inventory/'.$product->product_id.'"><i class="fa fa-sitemap" aria-hidden="true"></i></a> ';

            $return .= '<span class="table-action-icons"><a title="Edit" href="'.route('products.edit',$product->product_id).'"><i class="glyphicon glyphicon-edit"></i></a></span>';

             $return .= '<span class="table-action-icons"><a  onclick="deleteProduct('.$product->product_id.');return false;" href="#"><i class="glyphicon glyphicon-trash"></i></a></span>';

            return $return;

        })
        ->rawColumns(['id','action']) ->make(true);

    }

    public function seo($id)
    {  
        $meta_data = Product::getMetaDataById($id);
        $edit_products = Product::find($id);
        $page_title = $id." - SEO";
        return view('products::seo',compact('meta_data','edit_products','id','page_title'));

    }

    public function updateSeo(Request $request)
    {
        // Validating Inputs
        $rules = [
            'meta_title'        => 'required|max:250',
            'meta_keywords'     => 'required|max:250',
            'meta_description'  => 'required|max:2000',
        ];

        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->messages();
            return Redirect::back()->withErrors($validator)->withInput();
        }else{
            Product::updateProductSeo($request);

            if(isset($request['ss'])) {
                return back()->with('success','Product SEO updated successfully.');
            } else {
                return redirect()->route('products.index')
                    ->with('success','Product SEO updated successfully.');
            }
        }
    }

    public function attributes($id)
    {
        $page_title = $id." - Attributes";
        return view('products::attributes',compact('id','page_title'));
    }

    public function logs($id)
    {
        $meta_data = Product::getMetaDataById($id);
        $page_title = $id." - Logs";
        return view('products::logs',compact('meta_data','id','page_title'));
    }

    public function create()
    {
        $regions = Region::orderBy('name','asc')->get();
        $languages = Language::getAllLanguages();
        $statuses = Product_status::getAllStatuses();
        $page_title = "Add Product";
        return view('products::create',compact('regions','languages','statuses','page_title'));
    }

    public function store(Request $request)
    { 
        // Validating Inputs
        $messages = [
            'products_name.*.required' => 'The Products Name field is required.',
            'products_description.*.required' => 'The Products Description field is required.',
            'products_name.*.max' => 'The Products Name may not be greater than 60 characters.',
            'products_description.*.max' => 'The Products Description may not be greater than 2000 characters.',
        ];


        // Status Change Code Starts here
        $selected_status_code = 'dr';
        $selected_status = Product_status::select('status_code')->where('product_status_id',$request['fk_product_status'])->first();
        if(!empty($selected_status)){
            $selected_status_code = $selected_status->status_code;
        }
        
        if($selected_status_code == 'dr'){
            $rules = $this->changeStatusToDraft();
        }

        if($selected_status_code == 're'){
            $rules = $this->changeStatusToReview();
        }

        if($selected_status_code == 'pu'){
            return Redirect::to('products/create')->with('error','Product can not be published, No inventry found for this product.')->withInput();
            
        }

        if($selected_status_code == 'ou'){
            return Redirect::to('products/create')->with('error','Can not set Product status to "Out of Stock" at this point.')->withInput();
        }

        $is_global_checked =  (empty($request['is_global'])?0:1);
        if($is_global_checked==0){
            $regional_price_rules = array(        
                'price.*'            => 'required|regex:/^\d*(\.\d{1,2})?$/',        
            );
            $rules= array_merge($rules,$regional_price_rules);

            $regional_price_messages = array(
                'price.*.required' => 'The Price field is required.',
                'price.*.regex' => 'The Price field is not valid.',
            );
            $messages= array_merge($messages,$regional_price_messages);
        }

        $validator = Validator::make(
            Input::all(),
            $rules,
            $messages
        );

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
            if($is_global_checked==0){
                Product_regional_price::addPrice($request,$product_id);
            }
            
            if(isset($request['ss'])) {
            return redirect()->route('products.edit',$product_id)
                ->with('success','Product created successfully');
            } else if(isset($request['san'])){
                return redirect()->route('products.create')
                    ->with('success','Product created successfully');
            } else {
                return redirect()->route('products.index')
                    ->with('success','Product created successfully');
            }

        }
    }

    

    public function changeStatusToDraft()
    {
        $product_rules =  array(
            'fk_product_status'          => 'integer',
            'products_sku'               => 'max:200',
            'base_price'                 => 'nullable|regex:/^\d*(\.\d{1,2})?$/',
            'percent_off'                => 'nullable|max:100'
        );

        $product_description_rules =  array(        
            'products_name.1'            => 'required|max:60',
            'products_name.*'            => 'max:60',
            'products_description.*'     => 'max:2000'           
        );

        return array_merge($product_rules,$product_description_rules);
    }

    public function changeStatusToReview()
    {
        $product_rules =  array(
            'fk_product_status'          => 'required|integer',
            'products_sku'               => 'required|max:200',
            'base_price'                 => 'required|not_in:0|regex:/^\d*(\.\d{1,2})?$/',
            'percent_off'                => 'nullable|max:100'
        );

        $product_description_rules =  array(        
            'products_name.*'            => 'required|max:60',
            'products_description.*'     => 'required|max:2000'           
        );

        return array_merge($product_rules,$product_description_rules);
    }

    public function changeStatusToPublish($product_id)
    {
        $product_rules =  array(
            'fk_product_status'          => 'required|integer',
            'products_sku'               => 'required|max:200',
            'base_price'                 => 'required|not_in:0|regex:/^\d*(\.\d{1,2})?$/',
            'percent_off'                => 'nullable|max:100'
        );

        $product_description_rules =  array(        
            'products_name.*'            => 'required|max:60',
            'products_description.*'     => 'required|max:2000'           
        );

        $total = $this->getInventoryCountByProductId($product_id);
        if($total>0){
            return array(
                'rules'=>array_merge($product_rules,$product_description_rules),
                'status'=>'success',
                'message'=>'This product have positive inventory.'
            );
        }else{
            return array(
                'rules'=>array_merge($product_rules,$product_description_rules),
                'status'=>'failure',
                'message'=>'Product can not be published, No inventry found for this product.'
            );

        }
    }

    public function changeStatusToOutOfStock($product_id)
    {
        $product_rules =  array(
            'fk_product_status'          => 'required|integer',
            'products_sku'               => 'required|max:200',
            'base_price'                 => 'required|not_in:0|regex:/^\d*(\.\d{1,2})?$/',
            'percent_off'                => 'nullable|max:100'
        );

        $product_description_rules =  array(        
            'products_name.*'            => 'required|max:60',
            'products_description.*'     => 'required|max:2000'           
        );

        $total = $this->getInventoryCountByProductId($product_id);
        if($total>0){
            return array(
                'rules'=>array_merge($product_rules,$product_description_rules),
                'status'=>'failure',
                'message'=>'Positive inventory found, can not change product status to "Out Of Stock"'
            );
        }else{
            return array(
                'rules'=>array_merge($product_rules,$product_description_rules),
                'status'=>'success',
                'message'=>'No inventry found for this product.'
            );

        }
    }

    public function getInventoryCountByProductId($product_id){
        $inventoryObj = Product::where('product_id', '=', $product_id)
       ->with(array('mapProductInventoryItem' => function($query) {
              $query->with(array('inventory'));
             }))
       ->get()->toArray();
       
       $total = 0;
       foreach($inventoryObj[0]['map_product_inventory_item'] as $key => $row){
            
           $total+=($row['inventory']['qty_onhand']-$row['inventory']['qty_reserved']-$row['inventory']['qty_admin_reserved'])+$row['inventory']['qty_preorder'];

        }
       return $total;
    }

    public function show($id)
    {
        $page_title = $id." - Product";
        $product = Product::with(array('productsDescriptions' => function($query) {
               $query->with(array('language'));
           }))->find($id);

        //dd($product);
        return view('products::show',compact('product','page_title'));
    }


    public function edit($id)
    {
        $edit_products = Product::find($id);
        if(!empty($edit_products)){
            $edit_products_description = Product_description::where(['fk_product'=>$id])->get();
            $languages = Language::getAllLanguages();
            $regions = Region::orderBy('name','asc')->get();
            $inserted_regions = Product_regional_price::where(['fk_product'=>$id])->get();
            $statuses = Product_status::getAllStatuses();
            $page_title = $id." - Product";
            $meta_data = Product::getMetaDataById($id);
            return view('products::edit',compact('regions','inserted_regions','meta_data','languages','statuses','edit_products','edit_products_description','id','page_title'));
        }else{
            return redirect()->route('products.index')
                    ->with('error','Product Id does not exist.');
        }
    }

    public function update(Request $request, $product_id)
    {
        // Validating Inputs
        $messages = [
            'products_name.*.required' => 'The Products Name field is required.',
            'products_description.*.required' => 'The Products Description field is required.',
            'products_name.*.max' => 'The Products Name may not be greater than 60 characters.',
            'products_description.*.max' => 'The Products Description may not be greater than 2000 characters.',
        ];

        // Status Change Code Starts here
        $selected_status_code = 'dr';
        $selected_status = Product_status::select('status_code')->where('product_status_id',$request['fk_product_status'])->first();
        if(!empty($selected_status)){
            $selected_status_code = $selected_status->status_code;
        }

        if($selected_status_code == 'dr'){
            $rules = $this->changeStatusToDraft();
        }

        if($selected_status_code == 're'){
            $rules = $this->changeStatusToReview();
        }

        if($selected_status_code == 'pu'){
            $response = $this->changeStatusToPublish($product_id);
            if($response['status'] == 'failure'){
                return Redirect::back()->with('error',$response['message'])->withInput();
            }
            $rules = $response['rules'];
            
        }

        if($selected_status_code == 'ou'){
            $response = $this->changeStatusToOutOfStock($product_id);
            if($response['status'] == 'failure'){
                return Redirect::back()->with('error',$response['message'])->withInput();
            }
            $rules = $response['rules'];
        }

        $is_global_checked =  (empty($request['is_global'])?0:1);
        if($is_global_checked==0){
            $regional_price_rules = array(        
                'price.*'            => 'required|regex:/^\d*(\.\d{1,2})?$/',        
            );
            $rules= array_merge($rules,$regional_price_rules);

            $regional_price_messages = array(
                'price.*.required' => 'The Price field is required.',
                'price.*.regex' => 'The Price field is not valid.',
            );
            $messages= array_merge($messages,$regional_price_messages);
        }

        $validator = Validator::make(
            Input::all(),
            $rules,
            $messages
        );
        // Status Change Code Ends here

        if ($validator->fails()) {
            $messages = $validator->messages();
            //$id = Redirect::to('products/'.$product_id.'/edit')->withErrors($validator)->withInput();
            //dd($id);
            return Redirect::to('products/'.$product_id.'/edit')->withErrors($validator)->withInput();
        }else{

            // Updating Data in Categories and Categories_description Table
            Product::updateProducts($request,$product_id);
            Product_description::updateProductsDescription($request,$product_id);
            if($is_global_checked==0){
                Product_regional_price::addPrice($request,$product_id);
            }
            
            if(isset($request['ss'])) {
                return redirect()->route('products.edit',$product_id)
                ->with('success','Product updated successfully');
            } else {
                return redirect()->route('products.index')
                    ->with('success','Product updated successfully');
            }
            
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


    public function categorization($id)
    {
        $meta_data = Product::getMetaDataById($id);

        $categories = Category::getCategoriesTree();
        $get_mapped_category_ids = Map_product_category::where(['fk_product'=>$id])->pluck('fk_category')->toArray();
        $get_mapped_ids = Map_product_category::where(['fk_product'=>$id])->orderBy('fk_category','asc')->pluck('map_product_category_id')->toArray();
        $get_mapped_categories =$this->getParentCategories($get_mapped_category_ids);
        $page_title = $id." - Categorization";
        return view('products::categorization',compact('meta_data','id','categories','get_mapped_categories','get_mapped_ids','page_title'));
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


    public function destroy($product_id)
    {
        Product::deleteProducts($product_id);
        Product_description::deleteProductsDescription($product_id);
        session()->flash('success', 'Product deleted successfully.');
        return response()->json(['status'=>'success']);
    }

    
}

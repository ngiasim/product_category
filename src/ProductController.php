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
            $return .= '<a title="Show Inventories" class="actionLink" href="/inventory/'.$product->product_id.'"><i class="fa fa-sitemap" aria-hidden="true"></i></a> ';

            $return .= '<a title="Edit" class="actionLink" href="'.route('products.edit',$product->product_id).'"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a> ';

            $return .= '<a href="javascript:void(0)" data-type="user" data-toggle="modal" data-target="#myModal" data-uri="products/destroy/"'.$product->product_id.'"  class="actionLink confirm-delete" ><i class="glyphicon glyphicon-trash"></i> </a>';

            return $return;

        })
        ->rawColumns(['id','action']) ->make(true);

    }

    public function seo($id)
    {
        $edit_products = Product::find($id);
        $page_title = $id." - SEO";
        return view('products::seo',compact('edit_products','id','page_title'));
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
            return back()->with('success','Product SEO updated successfully.');
        }
    }

    public function attributes($id)
    {
        $page_title = $id." - Attributes";
        return view('products::attributes',compact('id','page_title'));
    }

    public function logs($id)
    {
        $page_title = $id." - Logs";
        return view('products::logs',compact('id','page_title'));
    }

    public function create()
    {
        $categories = $this->getCategoriesTree();
        $languages = Language::getAllLanguages();
        $statuses = Product_status::getAllStatuses();
        $page_title = "Add Product";
        return view('products::create',compact('languages','statuses','categories','page_title'));
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
        $edit_products_description = Product_description::where(['fk_product'=>$id])->get();
        $languages = Language::getAllLanguages();
        $statuses = Product_status::getAllStatuses();
        $categories = $this->getCategoriesTree();
        $get_mapped_category_ids = Map_product_category::where(['fk_product'=>$id])->pluck('fk_category')->toArray();
        $get_mapped_ids = Map_product_category::where(['fk_product'=>$id])->orderBy('fk_category','asc')->pluck('map_product_category_id')->toArray();
        $get_mapped_categories =$this->getParentCategories($get_mapped_category_ids);
        $page_title = $id." - Product";
        return view('products::edit',compact('languages','statuses','edit_products','edit_products_description','id','categories','get_mapped_categories','get_mapped_ids','page_title'));
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

    public function uploadImages($id)
    {   
        $path = $this->getImageDirectoryByProductId($id);
        $get_images = Product_image::where(['fk_product'=>$id])->get();
        $page_title = $id." - Image";
        return view('products::uploadimage',compact('id','get_images','path','page_title'));
    }

    public function storeImages(Request $request)
    {   
        //dd($request->all());
       
        $rules = [
            'uploaded_image.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'sort_order.*'     => 'required|integer',
        ];

        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->messages();
            return Redirect::back()->withErrors($validator)->withInput();
        }
        else{

            $content_path = $this->getImageDirectoryByProductId($request->product_id);
            $path =  base_path('public/'.$content_path);
            $count = Product_image::where(['fk_product'=>$request->product_id])->count();
            $is_default=0;
            if($count==0){ $is_default=1; }



            foreach($request->uploaded_image as $key => $image){
                $imageName = time().'-'.$key.'.'.$image->getClientOriginalExtension();
                $image->move($path, $imageName);

                $zoom = $path.'/zoom';
                $img = Image::make($path.'/'.$imageName);
                $img->resize(1000, 1000, function ($constraint) {
                    $constraint->aspectRatio('1:1');
                })->save($zoom.'/'.$imageName);

                $item = $path.'/item';
                $img = Image::make($path.'/'.$imageName);
                $img->resize(680, 680, function ($constraint) {
                    $constraint->aspectRatio('1:1');
                })->save($item.'/'.$imageName);

                $list = $path.'/list';
                $img = Image::make($path.'/'.$imageName);
                $img->resize(220, 220, function ($constraint) {
                    $constraint->aspectRatio('1:1');
                })->save($list.'/'.$imageName);

                File::delete($path.'/'.$imageName);

                $record = [
                    'fk_product'=>$request->product_id,
                    'sort_order'=>$request['sort_order'][$key],
                    'image_path'=>$imageName,
                    'is_default'=>$is_default
                ];
                Product_image::addImage($record);
                $is_default = 0;

            }
            return back()->with('success','You have successfully upload image.');
        }
    }

    public function getImageDirectoryByProductId($product_id)
    {    
        $created_at = Product::where(['product_id'=>$product_id])->pluck('created_at')->first();
        $path =  'content/'.date('Y/m/', strtotime($created_at)).$product_id;
        File::exists(base_path('public/'.$path)) or File::makeDirectory(base_path('public/'.$path), $mode = 0755, $recursive = true, $force = false);
        // Directory for Zoom
        $zoom = $path.'/zoom';
        File::exists($zoom) or File::makeDirectory($zoom);

        // Directory for item
        $item = $path.'/item';
        File::exists($item) or File::makeDirectory($item);

        // Directory for list
        $list = $path.'/list';
        File::exists($list) or File::makeDirectory($list);
        return $path;
    }

    public function removeImages($product_image_id)
    {
        Product_image::removeImages($product_image_id);
        return redirect()->back();
    }

    public function categorization($id)
    {    
        $categories = $this->getCategoriesTree();
        $get_mapped_category_ids = Map_product_category::where(['fk_product'=>$id])->pluck('fk_category')->toArray();
        $get_mapped_ids = Map_product_category::where(['fk_product'=>$id])->orderBy('fk_category','asc')->pluck('map_product_category_id')->toArray();
        $get_mapped_categories =$this->getParentCategories($get_mapped_category_ids);
        $page_title = $id." - Categorization";
        return view('products::categorization',compact('id','categories','get_mapped_categories','get_mapped_ids','page_title'));
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

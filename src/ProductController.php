<?php

namespace Ngiasim\Categories;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Product_description;
use App\Models\Language;
use App\Models\Product_status;
use App\Models\Category;
use App\Models\Category_description;
use App\Models\Map_product_category;
use App\Bulk_uploads;

use DataTables;

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
        return view('products::index',compact('products'));
    }

    public function getProducts()
    {
        //return \DataTables::of(Product::get())->make(true);
        $data = Product::select('product_id','products_sku','base_price')->with('productsDescription')->take(5000)->get();
        $response = $this->makeDatatable($data);
        return  $response;
    }

    function makeDatatable ($data)
    {

        return \DataTables::of($data)
        ->addColumn('id', function ($product) {

            $return = $product->product_id;
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
        ->rawColumns(['action']) ->make(true);

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

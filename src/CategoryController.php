<?php
namespace Ngiasim\Categories;
use App\Http\Controllers\Controller;


use Illuminate\Http\Request;
use App\Category;
use App\Category_description;
use App\Language;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;

class CategoryController extends Controller
{
    
    protected $globalRecursive = array();
    protected $globalIteration = 0;

    // Categories Listing
    public function index()
    {
       // Categories array with spaces in category_name
       $categories = $this->getCategoriesTree();
       return view('categories::index',compact('categories'));
    }


    public function create()
    {
        $categories = $this->getCategoriesTree();
        $languages = Language::getAllLanguages();
        return view('categories::create',compact('languages','categories'));
    }

    public function store(Request $request)
    {
        
        // Validating Inputs
        $validator = Validator::make(Input::all(),array_merge(Category::rules(),Category_description::rules()));

        // If Validation Fails
        if ($validator->fails()) {
            $messages = $validator->messages();
            return Redirect::to('categories/create')->withErrors($validator)->withInput();
        }
        // On Validation Success
        else{
            // Inserting Data in Category and Category_description Table
            $category_id = Category::addCategories($request); 
            Category_description::addCategoriesDescription($request,$category_id); 
            return redirect('/categories');
        }
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        // Get records from category and category_description tables
        $edit_categories = Category::find($id);
        $edit_categories_description = Category_description::where(['fk_category'=>$id])->get();
        
        // Categories array with spaces in category_name
        $categories = $this->getCategoriesTree();
        $languages = Language::getAllLanguages();

        return view('categories::edit',compact('categories','languages','edit_categories','edit_categories_description','id'));
    }

    public function update(Request $request, $id_categories)
    {
        // Validating Inputs
        $validator = Validator::make(Input::all(),array_merge(Category::rules($id_categories),Category_description::rules($id_categories)));

        if ($validator->fails()) {
            $messages = $validator->messages();
            return Redirect::to('categories/'.$id_categories.'/edit')->withErrors($validator)->withInput();
        }else{

            // Updating Data in Categories and Categories_description Table
            Category::updateCategories($request,$id_categories); 
            Category_description::updateCategoriesDescription($request,$id_categories); 
            
            return redirect('/categories');
        }
    }

    public function destroy($id_categories)
    {
        // Soft Deleting from database
        Category::deleteCategory($id_categories);
        Category_description::deleteCategoriesDescription($id_categories);
        return redirect('/categories');
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

<?php
namespace Ngiasim\Categories;
use App\Http\Controllers\Controller;


use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Category_description;
use App\Language;
use App\Models\Map_product_category;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;


class CategoryController extends Controller
{

    // Categories Listing
    public function index()
    {
       // Categories array with spaces in category_name
       $categories = Category::getCategoriesTree();
       $page_title = "Categories";
       return view('categories::index',compact('categories','page_title'));
    }


    public function create()
    {
        $categories = Category::getCategoriesTree();
        $languages = Language::getAllLanguages();
        $page_title = "Add Category";
        return view('categories::create',compact('languages','categories','page_title'));
    }

    public function store(Request $request)
    {
        // Validating Inputs
        $messages = [
            'category_name.*.required' => 'The Category Name field is required.',
            'category_description.*.required' => 'The Category Description field is required.',
            'category_name.*.max' => 'The Category Name may not be greater than 60 characters.',
            'category_description.*.max' => 'The Category Description may not be greater than 2000 characters.',
        ];
        $validator = Validator::make(Input::all(),array_merge(Category::rules(),Category_description::rules()),$messages);

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
        $page_title = $id." - Category";
        $category = Category::getSingleCategoryById($id);
        
        //dd($category);
        return view('categories::show',compact('category','page_title'));
    }

    public function edit($id)
    {
        // Get records from category and category_description tables
        $edit_categories = Category::find($id);
        $edit_categories_description = Category_description::getDescriptionsByProductId($id);
        
        // Categories array with spaces in category_name
        $categories = Category::getCategoriesTree();
        $languages = Language::getAllLanguages();
        $page_title = "Edit Category";
        return view('categories::edit',compact('categories','languages','edit_categories','edit_categories_description','id','page_title'));
    }

    public function update(Request $request, $id_categories)
    {
        // Validating Inputs
        $messages = [
            'category_name.*.required' => 'The Category Name field is required.',
            'category_description.*.required' => 'The Category Description field is required.',
            'category_name.*.max' => 'The Category Name may not be greater than 60 characters.',
            'category_description.*.max' => 'The Category Description may not be greater than 2000 characters.',
        ];
        $validator = Validator::make(Input::all(),array_merge(Category::rules($id_categories),Category_description::rules($id_categories)),$messages);

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

}

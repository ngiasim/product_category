<?php
namespace Ngiasim\Categories;
use App\Http\Controllers\Controller;


use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Category_description;
use App\Models\Language;
use App\Models\Map_product_category;
use App\Models\InventoryItem;
use App\Models\InventoryItemDetail;
use App\Models\Product;
use App\Models\MapProductInventoryItem;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;


class InventoryController extends Controller
{



    // Categories Listing
    public function index()
    {
       // Categories array with spaces in category_name
       $inventoryObj = Product::with(array('mapProductInventoryItem' => function($query) {
              $query->with(array('inventory' => function($query2) {
                      $query2->with(array('inventoryItemDetail' => function($query3) {
                             $query3->with('productOption');
                             $query3->with('productOptionValue');
                       }));
             }));
        }))
       ->get();
       //dd($inventoryObj);
       return view('inventory::index',compact('inventoryObj'));
    }

    public function show($id)
    {
       $inventoryObj = Product::where('product_id', '=', $id)
       ->with(array('mapProductInventoryItem' => function($query) {
              $query->with(array('inventory' => function($query2) {
                      $query2->with(array('inventoryItemDetail' => function($query3) {
                             $query3->with('productOption');
                             $query3->with('productOptionValue');
                       }));
             }));
        }))
       ->get();
       //dd($inventoryObj);
       return view('inventory::index',compact('inventoryObj'));

    }

    public function destroy($id)
    {
        $obj = input::all();
        $product_id = $obj["product_id"];
        // Soft Deleting from database
        InventoryItem::deleteInventory($id);
        InventoryItemDetail::deleteInventoryItemDetail($id);
        MapProductInventoryItem::deleteMapping($product_id,$id);
        return redirect('/inventory');
    }

}

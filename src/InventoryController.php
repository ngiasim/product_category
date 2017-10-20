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

    public function create()
    {
         //dd(\Session::all());
         $id = 2;
         $product_option = $inventoryObj = Product::where('product_id', '=', $id)
         ->with(array('ProductAttribute' => function($query) {
               $query->with(array('productOption' => function($query2) {
                 $query2->with('productOptionValue');
              }));
           }))
           ->get();

           $displayAttribite;
           foreach ($product_option as $product ) {
             foreach ($product->ProductAttribute as $pa){
            $displayAttribite[$product->product_id][] =  $pa->productOption->productOptionValue;
           }
         }

         $final;
         foreach ($displayAttribite as $key1 => $val1){
           foreach ($val1 as $key2 => $val2){
             foreach ($val2 as $key3 => $val3){
                $inner[$key3] =  $val3->display_name;
              }
              $inner1[$key2]=$inner;
           }
           //$final[$key1]=$inner1;
         }
        //  echo "<pr>";
        //  print_r(
        //      $this->combinations($inner1)
        //  );
        //  dd();
         $display_inventories = $this->combinations($inner1);
         //dd($display_inventories);
         return view('inventory::create',compact('display_inventories'));
     }

     public function combinations($arrays, $i = 0) {
         if (!isset($arrays[$i])) {
             return array();
         }
         if ($i == count($arrays) - 1) {
             return $arrays[$i];
         }

         // get combinations from subsequent arrays
         $tmp = $this->combinations($arrays, $i + 1);

         $result = array();

         // concat each array from tmp with each element from $arrays[$i]
         foreach ($arrays[$i] as $v) {
             foreach ($tmp as $t) {
                 $result[] = is_array($t) ?
                     array_merge(array($v), $t) :
                     array($v, $t);
             }
         }

         return $result;
     }

}

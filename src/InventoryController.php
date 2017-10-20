<?php
namespace Ngiasim\Categories;
use App\Http\Controllers\Controller;


use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Category_description;
use App\Language;
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
       $product_id = $id;
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
       return view('inventory::index',compact('inventoryObj','product_id'));

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
         $obj = input::all();
         $product_id = $obj["product_id"];
         $product_option = $inventoryObj = Product::where('product_id', '=', $product_id)
         ->with(array('ProductAttribute' => function($query) {
               $query->with(array('productOption' => function($query2) {
                 $query2->with('productOptionValue');
              }));
           }))
           ->get();
           $idsAttribite;
           foreach ($product_option as $product ) {
               foreach ($product->ProductAttribute as $pa){
                  $idsAttribite[$product->product_id][] =  $pa->productOption->productOptionValue;
                }
           }

           foreach ($idsAttribite as $key1 => $val1){
             foreach ($val1 as $key2 => $val2){
               foreach ($val2 as $key3 => $val3){
                  $inner_ids[$key3] =  $val3->product_option_value_id;
                }
                $inner1_ids[$key2]=$inner_ids;
             }
           }

           $ids_inventories = $this->combinations($inner1_ids);
           //dd($ids_inventories);

           $displayAttribite;
           foreach ($product_option as $product ) {
               foreach ($product->ProductAttribute as $pa){
                  $displayAttribite[$product->product_id][] =  $pa->productOption->productOptionValue;
                }
           }

           foreach ($displayAttribite as $key1 => $val1){
             foreach ($val1 as $key2 => $val2){
               foreach ($val2 as $key3 => $val3){
                  $inner[$key3] =  $val3->display_name;
                }
                $inner1[$key2]=$inner;
             }
           }

         $display_inventories = $this->combinations($inner1);
         //dd($display_inventories);
         return view('inventory::create',compact('display_inventories','ids_inventories','product_id'));
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

     public function store()
     {

          $obj = input::all();
          $attributes = "";
          $underscore = "";
          $ids_atrr;
          foreach ($obj as $key => $b)
          {
            if (strstr($key , "atm")){
                $attributes.= $underscore.$obj[$key];
                $underscore = "_";
              }
              if (strstr($key , "atrr")){
                  $ids_atrr[] = $obj[$key];
                }
          }

          $productObj = Product::find($obj["product_id"]);

          $invantoryObj = new InventoryItem();
          $invantoryObj->inventory_code = $productObj->products_sku."_".$attributes;
          $invantoryObj->qty_onhand = $obj["qty"];
          $invantoryObj->qty_total = $obj["qty"];
          $invantoryObj->barcode = "323232";
          $invantoryObj->save();

          //$invantoryObj->created_by = Auth::user()->user_id;

          foreach ($ids_atrr as $key => $value) {
            $invantoryItemObj = new InventoryItemDetail();
            $invantoryItemObj->fk_inventory_item = $invantoryObj->inventory_id;
            $invantoryItemObj->fk_product_option = "1";
            $invantoryItemObj->fk_product_option_values = $value;
            $invantoryItemObj->save();
          }

          $mapinvantoryObj = new MapProductInventoryItem();
          $mapinvantoryObj->fk_product = $obj["product_id"];
          $mapinvantoryObj->fk_inventory_item = $invantoryObj->inventory_id;
          $mapinvantoryObj->save();




          return redirect('/products');
      }

}

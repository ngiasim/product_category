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
       $meta_data = Product::getMetaDataById($id);
       return view('inventory::index',compact('inventoryObj','meta_data'));
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
       $id = $product_id;
       $meta_data = Product::getMetaDataById($id);
       return view('inventory::index',compact('inventoryObj','product_id','id','meta_data'));

    }

    public function destroy($id)
    {
        $obj = input::all();

        $product_id = $obj["product_id"];
        $variable = InventoryItemDetail::where("fk_inventory_item","=",$id)->get();
        foreach ($variable as $key => $value) {
          InventoryItemDetail::deleteInventoryItemDetail($value->inventory_item_detail_id);
        }
        InventoryItem::deleteInventory($id);
        MapProductInventoryItem::deleteMapping($product_id,$id);
        return redirect('/products/inventory/'.$obj["product_id"]);
    }

    public function create()
    {
         //dd(\Session::all());
         $objInvItemDetail = new InventoryItemDetail();
         $objProduct = new Product();

         $obj = input::all();
         $product_id = $obj["product_id"];

         $selected = $objInvItemDetail->getInventoriesByProductId($product_id);
         $selected_attr = [];
         $selected_qty = [];
        ////***************** Generate Sytem Existing atrribute array **********///
         foreach ($selected as $sel ) {
              $selected_attr[$sel->fk_inventory_item][]=$sel->fk_product_option_values;
              $selected_qty[$sel->fk_inventory_item] = $sel->qty_onhand;
         }
         //dd($selected_qty);
         ////***************** End **********///

        $product_option = $objProduct->getProductOptionByProductId($product_id);

        $option_names = $this->getProductOptionNames($product_option);
        $ids_inventories_one = $this->getattributesarraywithoption($product_option);
        $ids_inventories = $this->getattributesarraywithids($product_option);
        $display_inventories = $this->getattributesarraywithname($product_option);

         /**************** Compile Two Arrays With Identicat restriction**********/
         $qt_inv = [];
         $new_qt_inv = [];
         foreach ($ids_inventories as $key => $value) {
              foreach ($selected_attr as $keyatrr => $valueatrr) {
                if($value === array_intersect($value, $valueatrr) && $valueatrr === array_intersect($valueatrr, $value)) {
                    //unset($display_inventories[$key]);
                    $qt_inv[$key]=$selected_qty[$keyatrr];
                    $new_qt_inv[$key] = $keyatrr;
                    //continue;
                  } else {
                    //$qt_inv[$key]=0;
                    // continue;
                  }
              }
         }
         /*********************End*************************/
         //dd($new_qt_inv);
         $id = $product_id;
         $ids_inventories = $ids_inventories_one;
         $inventoryAddView = \View::make('inventory::create', compact('new_qt_inv','display_inventories','ids_inventories','product_id','id','option_names','qt_inv'))->render();
         $data = array(
             "inventoryAddView" => $inventoryAddView
         );
         return $this->generateSuccessResponse($data);
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

     function getattributesarraywithoption($product_option)
     {
       $idsAttribited;
       foreach ($product_option as $product ) {
           foreach ($product->ProductAttribute as $pa){
              $idsAttribited[$product->product_id][] =  $pa->productOption->productOptionValue;
            }
       }

       foreach ($idsAttribited as $key1 => $val1){
         foreach ($val1 as $key2 => $val2){
           foreach ($val2 as $key3 => $val3){
              $inner_ids_one[$key3] =  $val3->product_option_value_id."_".$val3->fk_product_option;
            }
            $inner0_ids[$key2]=$inner_ids_one;
         }
       }

       $ids_inventories_one = $this->combinations($inner0_ids);
       return $ids_inventories_one;
     }

     public function getattributesarraywithids($product_option)
     {
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
       return $ids_inventories;
     }

     public function getattributesarraywithname($product_option)
     {
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
                unset($inner);
             }
           }

         $display_inventories = $this->combinations($inner1);
         return $display_inventories;
     }

     public function getProductOptionNames($product_option)
     {
         $displayOptionNames;
         foreach ($product_option as $product ) {
             foreach ($product->ProductAttribute as $pa){
                $displayOptionNames[$product->product_id][] =  $pa->productOption;
              }
         }
         //dd($displayOptionNames);
         foreach ($displayOptionNames as $key1 => $val1){
                foreach ($val1 as $pa){
                  $inner_name[] = $pa->display_name;
            }
         }

       return $inner_name;

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
          if($obj["method"]=="update")
          {
            //dd($obj["inv"]);

            $invantoryObj = InventoryItem::find($obj["inv"]);
            $qty_diff = $obj["qty"] - $invantoryObj->qty_onhand;
            $invantoryObj->qty_onhand = $obj["qty"];
            $invantoryObj->qty_total = $invantoryObj->qty_total+$qty_diff;
            $invantoryObj->save();

          }else if ($obj["method"]=="add")
          {

          $invantoryObj = new InventoryItem();
          $invantoryObj->inventory_code = $productObj->products_sku."_".$attributes;
          $invantoryObj->qty_onhand = $obj["qty"];
          $invantoryObj->qty_total = $obj["qty"];
          $invantoryObj->barcode = "323232";
          $invantoryObj->save();

          //$invantoryObj->created_by = Auth::user()->user_id;

          foreach ($ids_atrr as $key => $value) {
            $invantoryItemObj = new InventoryItemDetail();
            $sep = explode("_",$value);
            $invantoryItemObj->fk_inventory_item = $invantoryObj->inventory_id;
            $invantoryItemObj->fk_product_option = $sep[1];
            $invantoryItemObj->fk_product_option_values = $sep[0];
            $invantoryItemObj->save();
          }

          $mapinvantoryObj = new MapProductInventoryItem();
          $mapinvantoryObj->fk_product = $obj["product_id"];
          $mapinvantoryObj->fk_inventory_item = $invantoryObj->inventory_id;
          $mapinvantoryObj->save();

        }

          return redirect('/products/inventory/'.$obj["product_id"]);
      }

}

<?php
namespace Ngiasim\Categories;
use App\Http\Controllers\Controller;


use Illuminate\Http\Request;
use App\Models\InventoryItem;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use App\system_config;
use App\Models\GiftCard;


class GiftCardController extends Controller
{
    public function index()
    {
    	$data = [];
    	//load GC product and its values
    	$productId = system_config::getGCProductId();
    	$product = new Product();
    	$gcValues = $product->getGCValues($productId);
    	return view('giftcard::view',compact('gcValues','productId'));
    }
    
    public function addGiftCard(Request $request)
    {
		#todo:validation
		#todo :make customer id and order id dynamic (part of order completion)
    	$input = $request->all();
    	$inventoryDetail = InventoryItem::find($input['fk_inventory']);
    	$input['fk_cutomer'] = 3;
    	$input['fk_order'] = 0;
    	$input['amount'] = $inventoryDetail->inventory_price;
    	$giftcard = new GiftCard();
    	$giftcard->addGiftCard($input);
    	return redirect()->back()->with('success', 'Gift card added successfully!');
    	
    }
}
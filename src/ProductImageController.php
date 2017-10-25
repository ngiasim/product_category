<?php

namespace Ngiasim\Categories;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Product_description;
use App\Models\Product_image;

use Image;
use File;

use DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;

class ProductImageController extends Controller
{
    
    public function uploadImages($id)
    {
        $meta_data = Product::getMetaDataById($id);

        $path = $this->getImageDirectoryByProductId($id);
        $get_images = Product_image::getProductImagesById($id);
        $page_title = $id." - Image";
        return view('products::uploadimage',compact('meta_data','id','get_images','path','page_title'));
    }

    public function storeImages(Request $request)
    {

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
            $count = Product_image::imageHasDefault($request->product_id);
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
        $created_at = Product::getCreatedAtById($product_id);
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

    
    public function makeDefaultImage($product_image_id)
    {
        Product_image::makeDefault($product_image_id);
        return redirect()->back();
    }

    public function updateImageType(Request $request)
    {
        $response = Product_image::updateImageType($request);
        if($response == 'success'){
            return response()->json(['status'=>'success']);
        }else{
            return response()->json(['status'=>'failure']);
        }
    }

    public function updateImageSortOrder(Request $request)
    {
        $response = Product_image::updateImageSortOrder($request);
        if($response == 'success'){
            return response()->json(['status'=>'success']);
        }else{
            return response()->json(['status'=>'failure']);
        }
    }
    

    public function removeImages($product_image_id)
    {
        Product_image::removeImages($product_image_id);
        return redirect()->back();
    }

}

<!-- index.blade.php -->
@extends('layouts.cockpit_master')
@section('content')


  <div class="main-center-area">
     <div class="row">
          <div class="page-header admin-header">
              <h3 id="page-title">Product Details</h3>      
          </div>
     </div>


    <div class="row">
       
      <div class="col-md-12">
           
      
            <div>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th colspan="2"><h3><a>Product Info</a></h3></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="col-md-4"><b>Product Id</b></td>
                            <td class="col-md-8">{{$product->product_id}}</td>
                        </tr>
                        @foreach($product->productsDescriptions as $desc)
                        <tr>
                            <td class="col-md-4"><b>Product Name - ({{$desc->language->name}})</b></td>
                            <td class="col-md-8" style="direction:{{$desc->language->direction}}"> {{$desc->products_name}}</td>
                        </tr>
                        <tr>
                            <td class="col-md-4"><b>Product Description - ({{$desc->language->name}})</b></td>
                            <td class="col-md-8" style="direction:{{$desc->language->direction}}"> {!!$desc->products_description!!}</td>
                        </tr>
                        @endforeach
                        <tr>
                            <td class="col-md-4"><b>Product SKU</b></td>
                            <td class="col-md-8">{{$product->products_sku}}</td>
                        </tr>

                        <tr>
                            <td class="col-md-4"><b>Product Views</b></td>
                            <td class="col-md-8">{{$product->products_viewed}}</td>
                        </tr>

                        <tr>
                            <td class="col-md-4"><b>Base Price</b></td>
                             <td class="text-left text-danger"><h4><strong><i class="fa fa-dollar"></i> {{$product->base_price}}/-</strong></h4></td>
                        </tr>

                        <tr>
                            <td class="col-md-4"><b>Is Global</b></td>
                            <td class="col-md-8">{{($product->is_global==1?'Yes':'No')}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            @if($product->is_global==0)
            <div>
                <table class="table table-bordered">
                    <colgroup>
                      <col width="20%" >
                      <col width="80%">
                    </colgroup>
                    <thead>
                        <tr>
                            <th colspan="2"><h3><a>Regional Price List</a><h3></th>
                        </tr>
                        <tr>
                            <th><h4>Region</h4></th>
                            <th><h4> Price</h4></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($product->regionalPrices as $region)
                            <tr>
                                <td>{{$region->region->name}} : </td>
                                <td class="text-left text-danger"><strong><i class="fa fa-dollar"></i> {{$region->price}}/-</strong></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>  
            @endif



            <div>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th colspan="2"><h3><a>SEO Tags</a><h3></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="col-md-4"><b>Meta Tag Title</b></td>
                            <td class="col-md-8">{{$product->meta_title}}</td>
                        </tr>
                        
                        <tr>
                            <td class="col-md-4"><b>Meta Tag Keywords</b></td>
                            <td class="col-md-8">{{$product->meta_keywords}}</td>
                        </tr>

                        <tr>
                            <td class="col-md-4"><b>Meta Tag Description</b></td>
                            <td class="col-md-8">{{$product->meta_description}}</td>
                        </tr>
                        
                       
                    </tbody>
                </table>
            </div>
      
      
        </div>


    </div>
  </div>
@include('common.delete');
@endsection
@section('script')

@endsection

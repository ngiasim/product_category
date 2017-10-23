<!-- index.blade.php -->
@extends('layouts.cockpit_master')
@section('content')


  <div class="main-center-area">
     <div class="row">
          <div class="page-header admin-header">
              <h3 id="page-title">Category Details</h3>      
          </div>
     </div>


    <div class="row">
       
      <div class="col-md-12">
           
      
            <div>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th colspan="2"><h3><a>Category Info</a></h3></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="col-md-4"><b>Category Id</b></td>
                            <td class="col-md-8">{{$category->category_id}}</td>
                        </tr>
                        @foreach($category->categoriesDescriptions as $desc)
                        <tr>
                            <td class="col-md-4"><b>Category Name - ({{$desc->language->name}})</b></td>
                            <td class="col-md-8" style="direction:{{$desc->language->direction}}"> {{$desc->category_name}}</td>
                        </tr>
                        <tr>
                            <td class="col-md-4"><b>Category Description - ({{$desc->language->name}})</b></td>
                            <td class="col-md-8" style="direction:{{$desc->language->direction}}"> {!!$desc->category_description!!}</td>
                        </tr>
                        @endforeach
                        <tr>
                            <td class="col-md-4"><b>Sort Order</b></td>
                            <td class="col-md-8">{{$category->sort_order}}</td>
                        </tr>
                       
                    </tbody>
                </table>
            </div>

            <div>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th colspan="2"><h3><a>SEO Tags</a><h3></th>
                        </tr>
                    </thead>
                    <tbody>
                        
                        <tr>
                            <td class="col-md-4"><b>Meta Tag Keywords</b></td>
                            <td class="col-md-8">{{$category->meta_keywords}}</td>
                        </tr>

                        <tr>
                            <td class="col-md-4"><b>Meta Tag Description</b></td>
                            <td class="col-md-8">{{$category->meta_description}}</td>
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

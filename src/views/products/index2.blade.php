<!-- index.blade.php -->
@extends('layouts.cockpit_master')
@section('content')

    <!-- Datatables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css">
    <script src="//cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>

  <div class="container">
     <div class="row">
          <div class="page-header admin-header">
              <h3 id="page-title">All Products</h3>      
          </div>
     </div>
     <div class="row">
          <div class="col-md-12 no-padding-right">
               <a href="products/uploadcsv" class="btn btn-primary pull-right"><i class="glyphicon glyphicon-plus"></i> Upload CSV</a>
               <a href="products/create" class="btn btn-primary pull-right margin-right-10"><i class="glyphicon glyphicon-plus"></i> Add New</a>
          </div>
     </div>
     <div class="row">
          <div class="col-md-12 admin-table-view">
               <table class="table table-bordered" id="table_data">
        <thead>
          <tr>
            <th>ID</th>
            <th>Product Name</th>
            <th>Keywords</th>
            <th>Action</th>
          </tr>
        </thead>
        
        <tbody>
          @foreach($products as $row)
          <tr>
            <td>{{$row['product_id']}}</td>
            <td>{{$row['productsDescription']['products_name']}}</td>
            <td>{{$row['meta_keywords']}}</td>
            <td>
                                   <span class="table-action-icons">
                                        <a href="{{url('products/'.$row['product_id'].'/edit')}}">
                                             <i class="glyphicon glyphicon-edit"></i>
                                        </a>
                                   </span>
                                   <span class="table-action-icons">

              {!! Form::open(['method'=>'DELETE','url' => 'products/'.$row['product_id']]) !!}
                    <button type="submit" class="glyphicon glyphicon-trash"></button>
              {!! Form::close() !!}
                                   </span>
            </td>
          </tr>
          @endforeach
        </tbody>
       
      </table>
    </div>
  </div>
{{ $products->links() }}
  </div>


<script>
  $(document).ready(function() {
    $.noConflict();
    $('#table_datasss').dataTable({
      "pageLength": 10,
    });
  });
 </script>

@endsection

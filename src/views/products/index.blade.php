<!-- index.blade.php -->
@extends('layouts.cockpit_master')
@section('content')


    <!-- Datatables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css">
    <script src="//cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>

    
    
      <div class="x_panel">
        <div class="x_content">
      <h1 id="page-title">All Products </h1>


  <div class="container">

    <div class='row'>
      <a href="products/create" class="btn btn-primary">+ Add New</a>
      <a href="products/uploadcsv" class="btn btn-primary">+ Upload CSV</a>
      <br>
      <br>
        <table class="table table-bordered " id='table_data'>
        
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
            <a style="float:left;margin-right: 10px;" href="{{url('products/'.$row['product_id'].'/edit')}}"><i class="glyphicon glyphicon-pencil"></i></a>

              {!! Form::open(['method'=>'DELETE','url' => 'products/'.$row['product_id']]) !!}
                    <button type="submit" class="glyphicon glyphicon-trash"></button>
              {!! Form::close() !!}

            </td>
          </tr>
          @endforeach
        </tbody>
       
      </table>
    </div>
  </div>


  </div>
</div>


<script>

  $(document).ready(function() {
    $.noConflict();
    $('#table_data').dataTable({
      "pageLength": 10,
    });
  });

 </script>

@endsection

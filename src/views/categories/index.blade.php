<!-- index.blade.php -->
@extends('layouts.cockpit_master')
@section('content')


    <!-- Datatables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css">
    <script src="//cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>

  <div class="x_panel">
        <div class="x_content">
      <h1 id="page-title">All Categories </h1>

  <div class="container">

    <div class='row'>
    <a href="categories/create" class="btn btn-primary">+ Add New</a>
    <br>
    <br>
        <table class="table table-bordered" id='table_data'>
        
        <thead>
          <tr>
            <th>ID</th>
            <th>Category Name</th>
            <th>Category Description</th>
            <th>Products Count</th>
            <th style="width:150px;">Action</th>
          </tr>
        </thead>
       
        <tbody>

        @foreach($categories as $row)
          <tr>
                <td><?=$row['category_id']?></td>
                <td><?=$row['category_name']?></td>
                <td><?=$row['category_description']?></td>
                <td><?=$row['products']?></td>
                <td><a style="float:left;margin-right: 10px;" href="{{url('categories/'.$row['category_id'].'/edit')}}"><i class="glyphicon glyphicon-pencil"></i></a>
                  {!! Form::open(['method'=>'DELETE','url' => 'categories/'.$row['category_id']]) !!}
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
    $('#table_datass').dataTable({
      "pageLength": 10,
       "bSort": false
    });
  });
 </script>

@endsection

<!-- index.blade.php -->
@extends('layouts.cockpit_master')
@section('content')

    <!-- Datatables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css">
    <script src="//cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>

  <div class="main-center-area">
     <div class="row">
          <div class="page-header admin-header">
              <h3 id="page-title">All Categories</h3>      
          </div>
     </div>
    <div class='row'>
          <div class="col-md-12 no-padding-right">
               <a href="categories/create" class="btn btn-primary pull-right"><i class="glyphicon glyphicon-plus"></i> Add New</a>
          </div>
     </div>
     <div class="row">
          <div class="col-md-12 admin-table-view">
               <table class="table table-bordered" id="table_data">
        <thead>
          <tr>
            <th>ID</th>
            <th>Category Name</th>
            <th>Category Description</th>
            <th>Products Count</th>
                              <th>Action</th>
          </tr>
        </thead>
        <tbody>

        @foreach($categories as $row)
          <tr>
                <td><?=$row['category_id']?></td>
                <td><?=$row['category_name']?></td>
                <td><?=$row['category_description']?></td>
                <td><?=$row['products']?></td>
                              <td>
                                   <span class="table-action-icons">
                                        <a href="{{url('categories/'.$row['category_id'].'/edit')}}">
                                             <i class="glyphicon glyphicon-edit"></i>
                                        </a>
                                   </span>
                                   <span class="table-action-icons">
                  {!! Form::open(['method'=>'DELETE','url' => 'categories/'.$row['category_id']]) !!}
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

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
              <h3 id="page-title">All Inventories</h3>
          </div>
     </div>
    <div class='row'>
          <div class="col-md-12 no-padding-right">
               <a href="create" class="btn btn-primary pull-right"><i class="glyphicon glyphicon-plus"></i> Add New Invetory</a>
          </div>
     </div>
     <div class="row">
          <div class="col-md-12 admin-table-view">
               <table class="table table-bordered" id="table_data">
        <thead>
          <tr>
            <th>ID</th>
            <th>Inventory Code</th>
            <th>QTY On Hand</th>
            <th>QTY Reserved</th>
            <th>QTY Pre Order</th>
            <th>QTY Total</th>
            <th>QTY Admin Reserved</th>
            <th>QTY Available</th>
            <th>Created At</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          @foreach($inventoryObj as $r)
            @foreach($r->mapProductInventoryItem as $row)
            <tr>
                  <td>{{$row->inventory->inventory_id}}</td>
                  <td>{{$row->inventory->inventory_code}}</td>
                  <td>{{$row->inventory->qty_onhand}}</td>
                  <td>{{$row->inventory->qty_reserved}}</td>
                  <td>{{$row->inventory->qty_preorder}}</td>
                  <td>{{$row->inventory->qty_total}}</td>
                  <td>{{$row->inventory->qty_admin_reserved}}</td>
                  <td>{{($row->inventory->qty_onhand-$row->qty_reserved-$row->qty_admin_reserved)+$row->qty_preorder}}</td>
                  <td>{{\Carbon\Carbon::parse($row->inventory->created_at)->toDayDateTimeString() }}</td>
                  <td>
                       <!-- <span class="table-action-icons">
                            <a href="{{url('categories/'.$row['category_id'].'/edit')}}">
                                 <i class="glyphicon glyphicon-edit"></i>
                            </a>
                       </span> -->
                       <span class="table-action-icons">
                          {!! Form::open(['method'=>'DELETE','url' => 'inventory/'.$row->inventory->inventory_id]) !!}
                            <input id="product_id" name="product_id" value="{{$r->product_id}}" type="hidden">
                            <button type="submit" class="glyphicon glyphicon-trash"></button>
                          {!! Form::close() !!}
                        </span>
                  </td>
            </tr>
            @endforeach
           @endforeach


        </tbody>
      </table>
    </div>
  </div>
  </div>


<script>
  $(document).ready(function() {
    $.noConflict();
    $('#table_data').dataTable({
      "pageLength": 10,
       "bSort": false
    });
  });
 </script>

@endsection

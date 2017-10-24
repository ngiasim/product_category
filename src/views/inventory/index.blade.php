<!-- index.blade.php -->
@extends('layouts.cockpit_master')
@section('content')

    <!-- Datatables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css">
    <script src="//cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>
    <div class="product-header">
          <h3 class="pull-left">Update Products</h3>
          @include('products::common-product-header')
    </div>
          <div style="clear:both"></div>



    <div class="container">
       <div class="row">
            <div class="page-header admin-header">
                <h3 id="page-title">All Inventories</h3>
            </div>
       </div>
      <div class='row'>
            <div class="col-md-12 no-padding-right">
                 <button id="open-add-inv-section" onclick="addInventoryView('{{$id}}');" class="btn btn-primary pull-right"><i class="glyphicon glyphicon-plus"></i> Add Invetory</button>
            </div>
       </div>
       <div class='row'>
         <div style="clear:both">&nbsp;</div>
       </div>

       <div id="add-new-inv" class='row'>
       </div>
       <div id="inventorylistview" class="form-group row">
           @include('inventory::inventorylisting')
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

  function addInventoryView(id){

   $.ajax({
         url: "/products/inventory/create?product_id="+id,
         dataType: 'JSON',
         type:'GET',
         data:{"_token": "{{ csrf_token() }}"},
         success: function (res) {
           if (res.success)
           {
             console.log(res.inventoryAddView);
             $("#add-new-inv").html(res.inventoryAddView);
             $("#open-add-inv-section").html("<i class='glyphicon glyphicon-minus'></i> Close");
             $("#open-add-inv-section").attr("onclick","closeInventoryView('"+id+"');");


           }
         //location.href = "/phoneorder";
         }
       });
  }

  function closeInventoryView(id){

    $("#add-new-inv").html("");
    $("#open-add-inv-section").html("<i class='glyphicon glyphicon-plus'></i> Add Invetory");
    $("#open-add-inv-section").attr("onclick","addInventoryView('"+id+"');");

  }

 </script>

@endsection

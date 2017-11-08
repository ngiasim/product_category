<!-- index.blade.php -->
@extends('layouts.cockpit_master')
@section('content')


  <div class="main-center-area">
     <div class="row">
          <div class="page-header admin-header">
              <h3 id="page-title">All Products</h3>      
          </div>
     </div>
     <div class="row">

                @if(session()->has('success'))
                  <div class="alert alert-success">
                      <strong>Success - </strong> {{ session()->get('success') }}
                  </div>
                @endif

                @if(session()->has('error'))
                  <div class="alert alert-danger">
                      <strong>Alert - </strong> {{ session()->get('error') }}
                  </div>
                @endif


          <div class="col-md-12 no-padding-right">
               <a href="{{ url('bulkupload') }}" class="btn btn-primary pull-right"><i class="glyphicon glyphicon-plus"></i> Upload CSV</a>
               <a href="{{ url('products/create') }}" class="btn btn-primary pull-right margin-right-10"><i class="glyphicon glyphicon-plus"></i> Add New</a>
          </div>
     </div>
     <div class="row">
          <div class="col-md-12 admin-table-view">
     


      <table class="table table-bordered table-striped" id="product_table" >
        <colgroup>
          <col width="5%" >
          <col width="40%">
          <col width="20%" >
          <col width="20%">
          <col width="15%">
        </colgroup>
        <thead>
          <tr>
            <th>ID</th>
            <th>Product Name</th>
            <th>Price</th> 
            <th>Quantity</th> 
            <th>SKU</th> 
            <th>Action</th> 
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>


    </div>
  </div>
  </div>
@include('common.delete');
@endsection
@section('script')

<script type="text/javascript">
$(document).ready(function() {
   var oTable =  $('#product_table').DataTable({
        processing: true,
        serverSide: true,
        pageLength: 50,
          ajax: {
            url: '{{ URL::to('products/getproducts') }}',
        },
        columns: [
            {data: 'id', name: 'id'},
            {data: 'products_name', name: 'products_name'},
            {data: 'price', name: 'price'},
            {data: 'quantity', name: 'quantity'},
            {data: 'products_sku', name: 'products_sku'},
            {data: 'action', name: 'action'},

        ]
    });
});



function deleteProduct(product_id) {
  
  var delete_record = confirm("Sure to delete ? ");
  if (delete_record == true) {
    $.ajax({
      url : '/products/'+product_id,
      type: 'DELETE', 
      data: {"_token": "{{ csrf_token() }}"},
      success: function(response) {
          if(response.status == "success"){
            location.reload();
          }else{
            alert("Something went wrong! Try again later.");
          }
      }
    });
  }
}

</script>
@endsection

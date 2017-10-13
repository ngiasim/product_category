<!-- create.blade.php -->
@extends('layouts.cockpit_master')
@section('content')


<div class="container">




 @if ($response!='')
   <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Products Bulk Upload Status</div>

                <div class="panel-body">

                      <table class="table table-bordered" id="tbl_categories_tagged">
                        <thead>
                          <tr>
                            <th>Total Rows</th>
                            <th>Products Added</th>
                            <th>Products Updated</th>
                            <th>Categories Added</th>
                            <th>Percentage</th>
                            <th>Status</th>

                          </tr>
                        </thead>
                       
                        <tbody>
                          <tr>
                            <td id="rows_count">0</td>
                            <td id="products_added">0</td>
                            <td id="products_updated">0</td>
                            <td id="categories_added">0</td>
                            <td id="percent">0 %</td>
                            <td id="status">Loading...</td>
                          </tr>
                        </tbody>
                      </table>

                    
                </div>
            </div>
        </div>
    </div>
  @endif



</div>
<script>
 

function updateStatus(){
    $.ajax({
            type: "POST",
            url : {!! json_encode(url('/products/uploadstatus')) !!},
            data: {"_token": "{{ csrf_token() }}",bulk_uploads_id:{{$response[0]['bulk_uploads_id']}}},
            success: function( row ) {
              
              $("#rows_count").html(row.rows_count);
              $("#products_added").html(row.products_added);
              $("#products_updated").html(row.products_updated);
              $("#categories_added").html(row.categories_added);
              $("#status").html(row.status);
              $("#percent").html(row.percent);
               
            }
    });
}
function runArtisan(){
    $.ajax({
            type: "POST",
            url : {!! json_encode(url('/products/triggerqueue')) !!},
            data: {"_token": "{{ csrf_token() }}"},
            success: function( row ) {
              
               
            }
    });
}
updateStatus();
runArtisan();
setInterval(function(){
    updateStatus() // this will run after every 5 seconds
}, 3000);

</script>



@endsection
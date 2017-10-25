<!-- edit.blade.php -->

@extends('layouts.cockpit_master')
@section('content')

<div class="product-header">
      <h3 class="pull-left">Update Products - Images</h3>
      @include('products::common-product-header')
</div>
      <div style="clear:both"></div>
        

  <div class="main-center-area">
    

        <div class="row">
              <label class="control-label col-md-3 col-md-offset-1">
                Upload Image
              </label>
              <label class="control-label col-md-3 col-md-offset-1">
                Sort Order
              </label>
              <label class="control-label col-md-2 col-md-offset-1">
                Add more
              </label>
        </div>

     {!! Form::open(['url' => 'products/storeimages','id'=>'form_upload_image','files' => true]) !!}
              {{ Form::hidden('product_id', $id) }}
               

      <div class="row table table-bordered">
        <div class="control-group" id="fields">
          
          <div class="controls">
          
              <div class="entry input-group col-md-10 col-md-offset-1" style="margin-top: 20px; margin-bottom: 20px;">
                
                {!! Form::file('uploaded_image[]', array('required','class' => 'btn btn-primary  col-md-4')) !!}
                
                {{ Form::text('sort_order[]',0, array('required', 
                                  'class'=>'btn btn-primary col-md-4 col-md-offset-1' )) 
                          }}

                <span class="input-group-btn col-md-2 col-md-offset-1">
                  <button class="btn btn-success btn-add" type="button">
                        <span class="glyphicon glyphicon-plus"></span>
                  </button>
                </span>

              </div>
           

          </div>
          
        </div>
      </div>


        <div class="form-group row" style="margin-top:30px;">
          <div class="col-md-3 text-center">
            <input type="submit" value="Upload Images" class="btn btn-primary margin-right-10">
          </div>
        </div>
      {!! Form::close() !!}



      <div class="row">
          <div class="col-md-12 admin-table-view">
                <div class="panel-body">
                      <table class="table table-bordered" id="tbl_categories_tagged">
                      <colgroup>
                        <col width="5%" >
                        <col width="25%">
                        <col width="10%" >
                        <col width="35%">
                        <col width="20%">
                      </colgroup>
                        <thead>
                          <tr>
                            <th>Id</th>
                            <th>Image</th>
                            <th>Sort Order</th>
                            <th>Image Type</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                       
                        <tbody>
                          @foreach($get_images as $key=>$row)
                          <tr>
                            <td>{{$key+1}}</td>
                            <td><img src="{{asset($path.'/list/'.$row['image_path'])}}"></td>
                            <td>
                              <span id="sort-{{$row['product_image_id']}}"></span>
                              <input value="{{$row['sort_order']}}" onmouseup="updateSortOrder({{$row['product_image_id']}},'sort-'+{{$row['product_image_id']}})" onkeyup="updateSortOrder({{$row['product_image_id']}},'sort-'+{{$row['product_image_id']}})" type="number" min="0" max="10" class="form-control input-xs">
                            </td>
                            <td>
                               <span id="radio-{{$row['product_image_id']}}"></span>
                                <div class="radio">
                                  <label><input onclick="changeImageType('make_model_view',{{$row['product_image_id']}},'radio-'+{{$row['product_image_id']}})" {{($row['is_model_view']==1?'checked':'')}} type="radio" name="optradio-{{$row['product_image_id']}}">Main On-Figure</label>
                                </div>
                                <div class="radio">
                                  <label><input onclick="changeImageType('make_product_view',{{$row['product_image_id']}},'radio-'+{{$row['product_image_id']}})" {{($row['is_product_view']==1?'checked':'')}} type="radio" name="optradio-{{$row['product_image_id']}}">Main Still</label>
                                </div>
                            </td>
                            <td>
                             @if ($row['is_default']==0)
                              <h4><a class="label label-default" href="{{url('products/makedefaultimage/'.$row['product_image_id'])}}">Make Default</a> 
                             @else
                              <h4><span class="label label-success">Default</span>
                             @endif
                              &nbsp; &nbsp; 
                              <a href="{{url('products/removeimages/'.$row['product_image_id'])}}">Delete</a></h4></td>
                           </tr>
                          @endforeach
                        </tbody>
                      </table>
            </div>            
        </div>
    </div>



  </div>

@endsection
@section('script')

<script type="text/javascript">
//$.noConflict();
$(function()
{
    $(document).on('click', '.btn-add', function(e)
    {
        e.preventDefault();

        var controlForm = $('.controls:first'),
            currentEntry = $(this).parents('.entry:first'),
            newEntry = $(currentEntry.clone()).appendTo(controlForm);

        newEntry.find('input:file').val('');
        newEntry.find('input:text').val('0');

        controlForm.find('.entry:not(:last) .btn-add')
            .removeClass('btn-add').addClass('btn-remove')
            .removeClass('btn-success').addClass('btn-danger')
            .html('<span class="glyphicon glyphicon-minus"></span>');
    }).on('click', '.btn-remove', function(e)
    {
      $(this).parents('.entry:first').remove();

    e.preventDefault();
    return false;
  });
});


function changeImageType(selected_type,image_id,span_id) {
          
    $.ajax({
            type: "POST",
            url : {!! json_encode(url('/products/updateImageType')) !!},
            data: {"_token": "{{ csrf_token() }}",image_type: selected_type,image_id:image_id},
            success: function( msg ) {
              $("#"+span_id).html('');
              if(msg.status == 'success'){
                $("#"+span_id).html('<p style="color:green">Image type updated Successfully.</p>');
              }else{
                  $("#"+span_id).html('<p style="color:red">Something went wrong! Please try again.</p>');
              }

              setTimeout(function() {
                  $("#"+span_id).html('');
              }, 2000);

            }
        });
}

function updateSortOrder(image_id,span_id) {
        var sort_order = event.target.value;
        if(sort_order == ''){ $("#"+span_id).html('<p style="color:red">Sort Order can not be empty.</p>'); return false; }
        if(sort_order < 0){ $("#"+span_id).html('<p style="color:red">Sort Order can not be less than 0</p>'); return false; }
        if(sort_order > 10){ $("#"+span_id).html('<p style="color:red">Sort Order can not be greater than 10.</p>'); return false; }
        $.ajax({
            type: "POST",
            url : {!! json_encode(url('/products/updateImageSortOrder')) !!},
            data: {"_token": "{{ csrf_token() }}",sort_order: sort_order,image_id:image_id},
            success: function( msg ) {
              $("#"+span_id).html('');
              if(msg.status == 'success'){
                $("#"+span_id).html('<p style="color:green">Sort Order updated Successfully.</p>');
              }else{
                  $("#"+span_id).html('<p style="color:red">Something went wrong! Please try again.</p>');
              }

              setTimeout(function() {
                  $("#"+span_id).html('');
              }, 2000);

            }
        });
              
}

</script>
@endsection
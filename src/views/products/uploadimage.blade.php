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
                        <thead>
                          <tr>
                            <th>Id</th>
                            <th>Image</th>
                            <th>Sort Order</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                       
                        <tbody>
                          @foreach($get_images as $key=>$row)
                          <tr>
                            <td>{{$key+1}}</td>
                            <td><img src="{{asset($path.'/list/'.$row['image_path'])}}"></td>
                            <td>{{$row['sort_order']}}</td>
                            <td><a href="{{url('products/removeimages/'.$row['product_image_id'])}}">Delete</a></td>
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

</script>
@endsection
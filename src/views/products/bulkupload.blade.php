<!-- create.blade.php -->
@extends('layouts.cockpit_master')
@section('content')

<style>
.error{
  color:#ff7272;
  }
  input[type="text"] {
    color: black !important;
  }
</style>


<!-- <script src="{{url('ckeditor/ckeditor.js')}}"></script>
 -->
<script src="{{url('js/arabic.js')}}"></script>
<script src="https://cdn.ckeditor.com/4.7.3/basic/ckeditor.js"></script>
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Products Bulk Upload</div>

                <div class="panel-body">
                    
                    {!! Form::open(['url' => 'products/storecsv','id'=>'form_add_product','files' => true]) !!}

                      <div class="form-group row">
                        {{ Form::label('Upload Products CSV: ', null, ['class' => 'col-sm-3 col-form-label col-form-label-lg']) }}
                          <div class="col-sm-5">
                             
                              {{Form::file('upload_csv',array('required', 
                                  'class'=>'form-control form-control-lg'))}}
                          </div>
                        </div>
                        

                        <div class="form-group row">
                          <div class="col-md-4 col-md-offset-4 text-center">
                            <input type="submit" value="Upload" class="btn btn-primary">
                          </div>
                        </div>
                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>




  @if ($response!='')
   <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Products Upload Status</div>

                <div class="panel-body">

                      <table class="table table-bordered" id="tbl_categories_tagged">
                        <thead>
                          <tr>
                            <th>SKU</th>
                            <th>Product Name</th>
                            <th>Status</th>
                          </tr>
                        </thead>
                       
                        <tbody>
                          @foreach($response as $row)
                          <tr style="color:white;background-color:{{$row['color']}}">
                            <td>{{$row['sku']}}</td>
                            <td>{{$row['name']}}</td>
                            <td>{{$row['status']}}</td>
                           </tr>
                          @endforeach
                        </tbody>
                      </table>

                    
                </div>
            </div>
        </div>
    </div>
  @endif




</div>


<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.min.js"></script>
<script>
 

  $("#form_add_product").validate({
      rules: {
        products_name: {
          required: true,
          minlength: 2,
          maxlength: 60,
        }
      },
      messages: {
        products_name: "Please select a Product Status",
      }
    });


</script>



@endsection
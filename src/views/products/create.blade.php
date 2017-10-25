<!-- create.blade.php -->

@extends('layouts.cockpit_master')
@section('content')

<!-- <script src="{{url('ckeditor/ckeditor.js')}}"></script>
 -->
<script src="{{url('js/arabic.js')}}"></script>
<script src="https://cdn.ckeditor.com/4.7.3/basic/ckeditor.js"></script>

<div class="main-center-area">
     
    <div class="row">
          <div class="page-header admin-header">
              <h3 id="page-title">Add Products</h3>      
          </div>
     </div>

     <div class="row">
          <!-- -->
            <div class="panel panel-default">
                <div class="panel-body">

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


                    {!! Form::open(['url' => 'products','id'=>'form_add_product']) !!}

                      <div class="form-group row">
                         {{ Form::label('Product Name: ', null, ['class' => 'col-xs-12 col-md-2 col-form-label col-form-label-lg']) }}
                        @foreach($languages as $row)
                          <div class="col-xs-12 col-md-5">
                              {{ Form::text('products_name['.$row["language_id"].']',null, array( 
                                  'class'=>'form-control form-control-lg '.$row['direction'],'placeholder'=>$row['name'] )) 
                              }}
                              @if ($errors->has('products_name.'.$row["language_id"])) <p class="help-block error">{{ $errors->first('products_name.'.$row["language_id"]) }}</p> @endif
                        
                          </div>
                        @endforeach
                        </div>
                        

                        <div class="form-group row">
                         {{ Form::label('Product Description: ', null, ['class' => 'col-xs-12 col-md-2 col-form-label col-form-label-lg']) }}
                        @foreach($languages as $row)
                         <div class="col-xs-12 col-md-5">
                            {{ Form::textarea('products_description['.$row["language_id"].']', null, ['class' => 'description '.$row['direction'] ]) }} 
                            @if ($errors->has('products_description.'.$row["language_id"])) <p class="help-block error">{{ $errors->first('products_description.'.$row["language_id"]) }}</p> @endif
                          </div>
                        @endforeach
                        </div>


                        <div class="form-group row">
                         {{ Form::label('Product Status:', null, ['class' => 'col-xs-12 col-md-2 col-form-label col-form-label-lg']) }}
                         <div class="col-xs-12 col-md-10">
                                {{ Form::select('fk_product_status', $statuses,0, ['class' => 'form-control']
                                ) }}
                            </div>
                            @if ($errors->has('fk_product_status')) <p class="help-block error">{{ $errors->first('fk_product_status') }}</p> @endif
                        </div>


                        <div class="form-group row">
                         {{ Form::label('Product SKU:', null, ['class' => 'col-xs-12 col-md-2 col-form-label col-form-label-lg']) }}
                         <div class="col-xs-12 col-md-10">
                              {{ Form::text('products_sku', null, array(
                                  'class'=>'form-control form-control-lg' )) 
                              }}
                              @if ($errors->has('products_sku')) <p class="help-block error">{{ $errors->first('products_sku') }}</p> @endif
                          </div>
                        </div>

                        <div class="form-group row">
                         {{ Form::label('Product Price:', null, ['class' => 'col-xs-12 col-md-2 col-form-label col-form-label-lg']) }}
                         <div class="col-xs-12 col-md-10">
                       
                              {{ Form::number('base_price', null, array('step'=>'any', 
                                  'class'=>'form-control form-control-lg' )) 
                              }}
                              @if ($errors->has('base_price')) <p class="help-block error">{{ $errors->first('base_price') }}</p> @endif
                          </div>
                        </div>


                        <div class="form-group row">
                         {{ Form::label('Has Unlimited Quantity:', null, ['class' => 'col-xs-12 col-md-2 col-form-label col-form-label-lg']) }}
                         <div class="col-xs-6 col-md-1">
                       
                              {{ Form::checkbox('qty_unlimited', 1, null, ['class' => 'form-control']) }}

                          </div>
                        </div>
                        

                        

                        <div class="form-group row">
                         <div class="col-md-offset-2 col-md-10 text-center">
                              <input type="submit" value="Save & Stay" class="btn btn-primary margin-right-10">
                              <input type="submit" value="Save & Add New" class="btn btn-primary margin-right-10">
                              <input type="submit" value="Save" class="btn btn-primary margin-right-10">
                            <input type="submit" value="Back To Listing" class="btn btn-primary">
                          </div>
                        </div>
                    {!! Form::close() !!}

                </div>
            </div>
     </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.min.js"></script>
<script>
  
  CKEDITOR.replaceClass = 'description';
  
  $(document).ready(function() {
    $.noConflict();
    $(".rtl").arabisk();
 
  });
  

  $("#form_add_product").validate({
      rules: {
        fk_product_status: {
          required: true
        },
        products_sku: {
          maxlength: 50
        }
      },
      messages: {
        fk_product_status: "Please select a Product Status",
      }
    });


</script>



@endsection

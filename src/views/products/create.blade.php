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
                    {!! Form::open(['url' => 'products','id'=>'form_add_product']) !!}

                      <div class="form-group row">
                         {{ Form::label('Product Name: ', null, ['class' => 'col-xs-12 col-md-2 col-form-label col-form-label-lg']) }}
                        @foreach($languages as $row)
                         <div class="col-xs-12 col-md-5">
                              {{ Form::text('products_name['.$row["language_id"].']',null, array('required', 
                                  'class'=>'form-control form-control-lg '.$row['direction'],'placeholder'=>$row['name'] )) 
                              }}
                          </div>
                        @endforeach
                        </div>
                        

                        <div class="form-group row">
                         {{ Form::label('Product Description: ', null, ['class' => 'col-xs-12 col-md-2 col-form-label col-form-label-lg']) }}
                        @foreach($languages as $row)
                         <div class="col-xs-12 col-md-5">
                            {{ Form::textarea('products_description['.$row["language_id"].']', null, ['class' => 'description '.$row['direction'] ]) }} 
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
                              {{ Form::text('products_sku', null, array('required', 
                                  'class'=>'form-control form-control-lg' )) 
                              }}
                              @if ($errors->has('products_sku')) <p class="help-block error">{{ $errors->first('products_sku') }}</p> @endif
                          </div>
                        </div>

                        <div class="form-group row">
                         {{ Form::label('Product Price:', null, ['class' => 'col-xs-12 col-md-2 col-form-label col-form-label-lg']) }}
                         <div class="col-xs-12 col-md-10">
                              {{ Form::text('base_price', null, array('required', 
                                  'class'=>'form-control form-control-lg' )) 
                              }}
                              @if ($errors->has('base_price')) <p class="help-block error">{{ $errors->first('base_price') }}</p> @endif
                          </div>
                        </div>
                        

                        <div class="form-group row">
                         {{ Form::label('Meta Keywords:', null, ['class' => 'col-xs-12 col-md-2 col-form-label col-form-label-lg']) }}
                         <div class="col-xs-12 col-md-10">
                              {{ Form::text('meta_keywords', null, array('required', 
                                  'class'=>'form-control form-control-lg' )) 
                              }}

                              @if ($errors->has('meta_keywords')) <p class="help-block error">{{ $errors->first('meta_keywords') }}</p> @endif
                          </div>
                        </div>

                        <div class="form-group row">
                         {{ Form::label('Meta Description:', null, ['class' => 'col-xs-12 col-md-2 col-form-label col-form-label-lg']) }}
                          <div class="col-sm-10">
                              {{ Form::text('meta_description', null, array('required', 
                                  'class'=>'form-control form-control-lg' )) 
                              }}

                              @if ($errors->has('meta_description')) <p class="help-block error">{{ $errors->first('meta_description') }}</p> @endif
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
          <!-- -->
     </div>
</div>


<div class="container">
     <div class="row">
          <div class="col-md-10 col-md-offset-1">
               fahim
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
        products_name: {
          required: true,
          minlength: 2,
          maxlength: 60,
        },
        products_description: {
          required: true,
          maxlength: 2000,
        },
        fk_product_status: {
          required: true
        },
        products_sku: {
          required: true,
          maxlength: 50
        },
        base_price: {
          required: true,
          number: true
        },
        meta_keywords: {
          required: true,
          maxlength: 200
        },
        meta_description: {
          required: true,
          maxlength: 2000
        }
      },
      messages: {
        fk_product_status: "Please select a Product Status",
      }
    });


</script>



@endsection

@extends('layouts.cockpit_master')
@section('content')

<div class="product-header">
      <h3 class="pull-left">Update Products - SEO</h3>
      @include('products::common-product-header')
</div>
      <div style="clear:both"></div>


<div class="main-center-area"> 
     <div class="row">
          <div class="col-md-12 admin-table-view">
           
            <div class="panel panel-default">
                <div class="panel-body">

          @if(session()->has('success'))
				    <div class="alert alert-success">
				        {{ session()->get('success') }}
				    </div>
				  @endif


                    {!! Form::open(['method'=>'post','url' => "products/updateseo",'id'=>'form_update_product']) !!}
                      
                   	{{ Form::hidden('product_id', $id) }}
                   	
                   		<div class="form-group row">
                              {{ Form::label('Meta Tag Title:', null, ['class' => 'col-xs-12 col-md-2 col-form-label col-form-label-lg']) }}
                              <div class="col-xs-12 col-md-10">
                              {{ Form::text('meta_title', $edit_products->meta_title, array('required', 
                                  'class'=>'form-control form-control-lg' ,'placeholder'=>'Meta Tag Title')) 
                              }}

                              @if ($errors->has('meta_title')) <p class="help-block error">{{ $errors->first('meta_title') }}</p> @endif
                          </div>
                        </div>


                        <div class="form-group row">
                              {{ Form::label('Meta Tag Keywords:', null, ['class' => 'col-xs-12 col-md-2 col-form-label col-form-label-lg']) }}
                              <div class="col-xs-12 col-md-10">
                              {{ Form::textarea('meta_keywords', $edit_products->meta_keywords, array('required', 'size' => '30x5','class'=>'form-control form-control-lg','placeholder'=>'Meta Tag Keywords')) }}


                              @if ($errors->has('meta_keywords')) <p class="help-block error">{{ $errors->first('meta_keywords') }}</p> @endif
                          </div>
                        </div>

                        <div class="form-group row">
                              {{ Form::label('Meta Tag Description:', null, ['class' => 'col-xs-12 col-md-2 col-form-label col-form-label-lg']) }}
                              <div class="col-xs-12 col-md-10">

                              {{ Form::textarea('meta_description', $edit_products->meta_description, array('required', 'size' => '30x5','class'=>'form-control form-control-lg','placeholder'=>'Meta Tag Description')) }}


                              @if ($errors->has('meta_description')) <p class="help-block error">{{ $errors->first('meta_description') }}</p> @endif
                          </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-offset-2 col-md-10 text-center">
                                {!! Form::submit('Update & Close', array("class"=>"btn btn-primary margin-right-10","name"=>"tool-close")) !!}
 								{!! Form::submit('Update & Stay', array("class"=>"btn btn-primary","name"=>"tool-stay")) !!}
                            </div>
                        </div>
                    {!! Form::close() !!}
                </div>
            </div>
               <!-- -->
        </div>
    </div>
</div>

@endsection
@section('script')

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.min.js"></script>
<script>


  $("#form_update_products").validate({
      rules: {
        meta_title: {
          required: true,
          maxlength: 250
        },
        meta_keywords: {
          required: true,
          maxlength: 250
        },
        meta_description: {
          required: true,
          maxlength: 2000
        }
      },
      messages: {
        id_languages: "Please select a Language",
      }
    });


</script>

@endsection


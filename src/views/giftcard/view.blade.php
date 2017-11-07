@extends('store.layouts.store_master')
@section('content')
<div class="main">
     <div class="row">
          <div class="col-md-12 ">
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


                    {!! Form::open(['url' => 'giftcard/add','id'=>'','class'=>'form-horizontal']) !!}
   						<div class="form-group ">
                              {{ Form::label('SELECT GIFT CARD AMOUNT: ', null, ['class' => 'control-label col-md-2']) }}

                              <div class="col-md-5">
                              {{ Form::select('fk_inventory',$gcValues,null, array('required', 
                                  'class'=>'form-control ' )) 
                              }}
  
                          </div>
        
                        </div>
                        <input type="hidden" name="product_id" id="product_id" value="{{$productId}}"> 
                      <div class="form-group ">
                              {{ Form::label('RECIPIENT NAME: ', null, ['class' => 'control-label col-md-2']) }}

                              <div class="col-md-5">
                              {{ Form::text('recipient_name',null, array('required', 
                                  'class'=>'form-control ','placeholder'=>'Recipient Name' )) 
                              }}
  
                          </div>
        
                        </div>
                          <div class="form-group ">
                              {{ Form::label('RECIPIENT EMAIL: ', null, ['class' => 'control-label col-md-2']) }}

                              <div class="col-md-5">
                              {{ Form::email('recipient_email',null, array('required', 
                                  'class'=>'form-control  ','placeholder'=>'Recipient Email' )) 
                              }}
  
                          </div>
        
                        </div>
                        
     					<div class="form-group ">
                              {{ Form::label('ADD A PERSONAL MESSAGE:(Optional) ', null, ['class' => 'control-label col-md-2']) }}
	                          <div class="col-xs-12 col-md-5">
	                              {{ Form::textarea('message',null, array( 
	                                  'class'=>'form-control','placeholder'=>'Message type here...' )) 
	                              }}
	                          </div>
        
                        </div>

                        <div class="form-group row">
                              <div class="col-md-offset-2 col-md-10 text-center">
		                        <button type="submit" class="btn btn-primary pull-right" >Add to Bag</button>
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

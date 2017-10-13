<!-- create.blade.php -->
@extends('layouts.cockpit_master')
@section('content')

<style>
.error{
  color:#ff7272 !important;
  }
</style>
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Products Bulk Upload</div>

                <div class="panel-body">
                    
                    {!! Form::open(['url' => 'products/storecsv','id'=>'form_upload','files' => true]) !!}

                      <div class="form-group row">
                        {{ Form::label('Upload Products CSV: ', null, ['class' => 'col-sm-3 col-form-label col-form-label-lg']) }}
                          <div class="col-sm-5">
                             
                              {{Form::file('upload_csv',array('required', 
                                  'class'=>'form-control form-control-lg','id'=>'upload_csv'))}}
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
                <div class="panel-heading">Kindly follow the link to check upload status.</div>

                <div class="panel-body">

                      <a target="_blank" href="{{url('products/uploadstatus/'.$response)}}">{{url('products/uploadstatus/'.$response)}}</a>

                    
                </div>
            </div>
        </div>
    </div>
  @endif




</div>
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.0/jquery.validate.min.js"></script>
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.0/additional-methods.js"></script>

<script>
 

  $("#form_upload").validate({
      rules: {
        upload_csv: {
                required: true,
                extension: "csv"
        }
      },
      messages: {
      }
    });


</script>



@endsection

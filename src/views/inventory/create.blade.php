<!-- create.blade.php -->

@extends('layouts.cockpit_master')
@section('content')
<script src="{{url('js/arabic.js')}}"></script>
<script src="https://cdn.ckeditor.com/4.7.3/basic/ckeditor.js"></script>

<div class="container">
    <div class="row">
          <div class="page-header admin-header">
              <h3 id="page-title">Add Inventory</h3>
          </div>
     </div>
     <div class="row">
          <div class="col-md-12 admin-table-view">
               <!-- -->
            <div class="panel panel-default">
                <div class="panel-body">
                    {!! Form::open(['url' => 'inventory','id'=>'form_add_inventory']) !!}

                      <div class="form-group row">
                              {{ Form::label('Inventories: ', null, ['class' => 'col-xs-12 col-md-2 col-form-label col-form-label-lg']) }}
                        </div>

                        <div class="form-group row">
                          @foreach($display_inventories as $row)
                            <div class="col-xs-2 col-md-2">
                            {{  Form::checkbox('inventory', 'value') }}
                            </div>
                              @foreach($row as $col)
                              <div class="col-xs-4 col-md-4">
                                  {{ Form::text($col,$col, array('required','disabled',
                                      'class'=>'form-control form-control-lg '))
                                  }}
                              </div>
                              @endforeach
                              <div class="col-xs-2 col-md-2">
                                {{ Form::text('qty','0', array('required',
                                    'class'=>'form-control form-control-lg '))
                                }}
                              </div>
                          @endforeach
                        </div>




                        <div class="form-group row">
                              <div class="col-md-offset-2 col-md-10 text-center">

                                   {!! Form::submit('Save & Close', ['id' => 'tool_sbmitclick', 'class' => 'btn btn-primary margin-right-10' ]) !!}

                                   {!! Form::submit('Save & Stay', array("class"=>"btn btn-primary margin-right-10","name"=>"tool-save-stay")) !!}

                                   {!! Form::submit('Save & Add New', array("class"=>"btn btn-primary margin-right-10","name"=>"tool-save-addnew")) !!}

    <a href="/categories" class="btn btn-primary">Back To Listing</a>

                          </div>
                        </div>
                    {!! Form::close() !!}
                </div>
            </div>
               <!-- -->
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

  $("#form_add_category").validate({
      rules: {
        id_parent: {
          required: true,
          number: true
        },
        category_name: {
          required: true,
          minlength: 2,
          maxlength: 60,
        },
        category_link: {
          required: true,
          maxlength: 200
        },
        category_description: {
          required: true,
          maxlength: 2000,
        },
        meta_keywords: {
          required: true,
          maxlength: 200
        },
        meta_description: {
          required: true,
          maxlength: 2000
        },
        sort_order: {
          required: true,
          number: true
        }
      },
      messages: {
        id_parent: "Please select Parent Category",
      }
    });


</script>

@endsection

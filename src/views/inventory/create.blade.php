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

                      <div class="form-group row">
                              {{ Form::label('Inventories: ', null, ['class' => 'col-xs-12 col-md-2 col-form-label col-form-label-lg']) }}
                        </div>


                          @foreach($ids_inventories as $key1 => $row1)
                          @foreach($row1 as $col1)

                             <!-- <input type="hidden" value="{{$col1}}" id="atrr-{{$key1}}" name="atrr-{{$key1}}"> -->

                          @endforeach
                          @endforeach



                          @foreach($display_inventories as $key => $row)
                          {!! Form::open(['url' => 'inventory','id'=>'form_add_inventory']) !!}

                          <div class="form-group row">
                            <div class="col-xs-2 col-md-2">

                            </div>
                              @foreach($row as $key2 => $col)
                              <div class="col-xs-2 col-md-2">
                                {{ Form::text('atm-'.$loop->iteration,$col, array('required',
                                    'id'=>'atm','class'=>'form-control form-control-lg '))
                                }}
                                <input type="hidden" value="{{$ids_inventories[$key][$key2]}}" id="atrr-{{$loop->iteration}}" name="atrr-{{$loop->iteration}}">

                              </div>
                              @endforeach
                              <input type="hidden" value="{{$product_id}}" id="product_id" name="product_id">

                              <div class="col-xs-2 col-md-2">
                                <!-- <input type="hidden" value="{{$key}}" id="" name=""> -->
                                {{ Form::text('qty','0', array('required',
                                    'class'=>'form-control form-control-lg '))
                                }}
                              </div>
                              <div class="col-xs-2 col-md-2">
                                    <!-- <button type="button" class="btn btn-success" >
                                        Add </span>
                                    </button> -->
                                    {!! Form::submit('Add', ['id' => 'tool_sbmitclick', 'class' => 'btn btn-primary margin-right-10' ]) !!}

                              </div>
                              </div>
                              {!! Form::close() !!}
                          @endforeach



                        <div class="form-group row">
                              <div class="col-md-offset-2 col-md-10 text-center">

                                   

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

  // CKEDITOR.replaceClass = 'description';
  //
  // $(document).ready(function() {
  //   $.noConflict();
  //   $(".rtl").arabisk();
  // });

  // $("#form_add_category").validate({
  //     rules: {
  //       id_parent: {
  //         required: true,
  //         number: true
  //       },
  //       category_name: {
  //         required: true,
  //         minlength: 2,
  //         maxlength: 60,
  //       },
  //       category_link: {
  //         required: true,
  //         maxlength: 200
  //       },
  //       category_description: {
  //         required: true,
  //         maxlength: 2000,
  //       },
  //       meta_keywords: {
  //         required: true,
  //         maxlength: 200
  //       },
  //       meta_description: {
  //         required: true,
  //         maxlength: 2000
  //       },
  //       sort_order: {
  //         required: true,
  //         number: true
  //       }
  //     },
  //     messages: {
  //       id_parent: "Please select Parent Category",
  //     }
  //   });

  function addInventory(prodId){
    alert(prodId);
  }


</script>

@endsection

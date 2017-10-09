<!-- edit.blade.php -->

@extends('layouts.cockpit_master')
@section('content')
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
.error{
  color:#ff7272;
  }
  input[type="text"] {
    color: black !important;
  }
</style>
<script src="{{url('js/arabic.js')}}"></script>
<script src="https://cdn.ckeditor.com/4.7.3/basic/ckeditor.js"></script>
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Update Categories</div>

                <div class="panel-body">
                    
                    {!! Form::open(['method'=>'patch','url' => "categories/$id",'id'=>'form_update_category']) !!}
                    
                        
                        <div class="form-group row">
                        {{ Form::label('Category Name: ', null, ['class' => 'col-sm-2  col-form-label col-form-label-lg']) }}
                        <?php $i=0; ?>
                        @foreach($languages as $row)
                          <div class="col-sm-5">
                              {{ Form::text('category_name['.$row["language_id"].']',$edit_categories_description[$i]['category_name'], array('required', 
                                  'class'=>'form-control form-control-lg '.$row['direction'],'placeholder'=>$row['name'])) 
                              }}

                          </div>
                        <?php $i++; ?>
                        @endforeach
                        </div>



                        <div class="form-group row">
                        {{ Form::label('Category Description: ', null, ['class' => 'col-sm-2  col-form-label col-form-label-lg']) }}
                        <?php $i=0; ?>
                        @foreach($languages as $row)
                          <div class="col-sm-5">
                              {{ Form::textarea('category_description['.$row["language_id"].']', $edit_categories_description[$i]['category_description'], ['class' => 'description '.$row['direction'] ]) }} 
                          </div>
                        <?php $i++; ?>
                        @endforeach
                        </div>


                        <div class="form-group row">

                         {{ Form::label('Parent Category:', null, ['class' => 'col-sm-2  col-form-label col-form-label-lg']) }}
                            <div class="col-sm-10">
                              <select class="form-control" name="id_parent">
                                <option value="0">No Parent</option>
                                @foreach($categories as $row)
                                  <option <?=(($row['category_id']==$edit_categories->id_parent)?'selected':'')?> value="<?=$row['category_id']?>"><?=$row['category_name']?></option>
                                @endforeach
                              </select>
                            </div>

                              @if ($errors->has('id_parent')) <p class="help-block error">{{ $errors->first('id_parent') }}</p> @endif
                        </div>
                        


                        <div class="form-group row">
                         {{ Form::label('Category Link:', null, ['class' => 'col-sm-2  col-form-label col-form-label-lg']) }}
                        
                          <div class="col-sm-10">
                              {{ Form::text('category_link',$edit_categories->category_link, array('required', 
                                  'class'=>'form-control form-control-lg' )) 
                              }}

                              @if ($errors->has('category_link')) <p class="help-block error">{{ $errors->first('category_link') }}</p> @endif
                          </div>
                        </div>

                        <div class="form-group row">
                        {{ Form::label('Meta Keywords:', null, ['class' => 'col-sm-2  col-form-label col-form-label-lg']) }}
                          <div class="col-sm-10">
                              {{ Form::text('meta_keywords', $edit_categories->meta_keywords, array('required', 
                                  'class'=>'form-control form-control-lg' )) 
                              }}

                              @if ($errors->has('meta_keywords')) <p class="help-block error">{{ $errors->first('meta_keywords') }}</p> @endif
                          </div>
                        </div>

                        <div class="form-group row">
                        {{ Form::label('Meta Description:', null, ['class' => 'col-sm-2  col-form-label col-form-label-lg']) }}
                          <div class="col-sm-10">
                              {{ Form::text('meta_description', $edit_categories->meta_description, array('required', 
                                  'class'=>'form-control form-control-lg' )) 
                              }}

                              @if ($errors->has('meta_description')) <p class="help-block error">{{ $errors->first('meta_description') }}</p> @endif
                          </div>
                        </div>

                        <div class="form-group row">
                        {{ Form::label('Sort Order:', null, ['class' => 'col-sm-2  col-form-label col-form-label-lg']) }}
                          <div class="col-sm-10">
                              {{ Form::text('sort_order', $edit_categories->sort_order, array('required', 
                                  'class'=>'form-control form-control-lg' )) 
                              }}

                              @if ($errors->has('sort_order')) <p class="help-block error">{{ $errors->first('sort_order') }}</p> @endif
                          </div>
                        </div>

                        <div class="form-group row">
                          <div class="col-md-4 col-md-offset-3 text-center">

 {!! Form::submit('Update & Close', array("class"=>"btn btn-primary","name"=>"tool-close")) !!}
 {!! Form::submit('Update & Stay', array("class"=>"btn btn-primary","name"=>"tool-stay")) !!}
                          </div>
                        </div>
                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
</div>


<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.min.js"></script>
<script>
  
  CKEDITOR.replaceClass = 'description';
  
  $(document).ready(function() {
    //$.noConflict();
    $(".rtl").arabisk();
  });
  
  $("#form_update_category").validate({
      rules: {
        fk_language: {
          required: true,
          number: true
        },
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
        categories_description: {
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
        fk_language: "Please select a Language",
        id_parent: "Please select Parent Category",
      }
    });


</script>

@endsection


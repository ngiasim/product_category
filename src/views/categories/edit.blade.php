<!-- edit.blade.php -->
@extends('layouts.cockpit_master')
@section('content')

<script src="{{url('js/arabic.js')}}"></script>
<script src="https://cdn.ckeditor.com/4.7.3/basic/ckeditor.js"></script>

<div class="main-center-area">
    <div class="row">
          <div class="page-header admin-header">
              <h3 id="page-title">Update Categories</h3>      
          </div>
     </div>
     <div class="row">
          <div class="col-md-12 admin-table-view">

               <!-- -->
            <div class="panel panel-default">
                <div class="panel-body">
                    
                    {!! Form::open(['method'=>'patch','url' => "categories/$id",'id'=>'form_update_category']) !!}
                    
                        
                        <div class="form-group row">
                              {{ Form::label('Category Name: ', null, ['class' => 'col-xs-12 col-md-2 col-form-label col-form-label-lg']) }}
                        <?php $i=0; ?>
                        @foreach($languages as $row)
                              <div class="col-xs-12 col-md-5">
                              {{ Form::text('category_name['.$row["language_id"].']',$edit_categories_description[$i]['category_name'], array('required', 
                                  'class'=>'form-control form-control-lg '.$row['direction'],'placeholder'=>$row['name'])) 
                              }}
                              @if ($errors->has('category_name.'.$row["language_id"])) <p class="help-block error">{{ $errors->first('category_name.'.$row["language_id"]) }}</p> @endif
                        
                          </div>
                        <?php $i++; ?>
                        @endforeach
                        </div>


                        <div class="form-group row">
                              {{ Form::label('Category Description: ', null, ['class' => 'col-xs-12 col-md-2 col-form-label col-form-label-lg']) }}
                        <?php $i=0; ?>
                        @foreach($languages as $row)
                              <div class="col-xs-12 col-md-5">
                              {{ Form::textarea('category_description['.$row["language_id"].']', $edit_categories_description[$i]['category_description'], ['class' => 'description '.$row['direction'] ]) }} 
                              @if ($errors->has('category_description.'.$row["language_id"])) <p class="help-block error">{{ $errors->first('category_description.'.$row["language_id"]) }}</p> @endif
                        
                          </div>
                        <?php $i++; ?>
                        @endforeach
                        </div>


                        <div class="form-group row">
                              {{ Form::label('Parent Category:', null, ['class' => 'col-xs-12 col-md-2 col-form-label col-form-label-lg']) }}
                              <div class="col-xs-12 col-md-10">
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
                              {{ Form::label('Category Link:', null, ['class' => 'col-xs-12 col-md-2 col-form-label col-form-label-lg']) }}
                        
                              <div class="col-xs-12 col-md-10">
                              {{ Form::text('category_link',$edit_categories->category_link, array('required', 
                                  'class'=>'form-control form-control-lg' )) 
                              }}

                              @if ($errors->has('category_link')) <p class="help-block error">{{ $errors->first('category_link') }}</p> @endif
                          </div>
                        </div>


                        <div class="form-group row">
                              {{ Form::label('Meta Keywords:', null, ['class' => 'col-xs-12 col-md-2 col-form-label col-form-label-lg']) }}
                              <div class="col-xs-12 col-md-10">
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
                              <div class="col-md-offset-2 col-md-10 text-center">
                                   {!! Form::submit('Update & Close', array("class"=>"btn btn-primary margin-right-10","name"=>"tool-close")) !!}
 {!! Form::submit('Update & Stay', array("class"=>"btn btn-primary","name"=>"tool-stay")) !!}
                          </div>
                        </div>
                    {!! Form::close() !!}

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


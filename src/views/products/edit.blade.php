<!-- edit.blade.php -->

@extends('layouts.cockpit_master')
@section('content')
<!-- 
<script src="{{url('ckeditor/ckeditor.js')}}"></script> -->
<script src="{{url('js/arabic.js')}}"></script>
<script src="https://cdn.ckeditor.com/4.7.3/basic/ckeditor.js"></script>

<div class="container">
    <div class="row">
          <div class="page-header admin-header">
              <h3 id="page-title">Update Products</h3>      
          </div>
     </div>
     <div class="row">
          <div class="col-md-12 admin-table-view">
               <!-- -->
            <div class="panel panel-default">
                <div class="panel-body">
                    {!! Form::open(['method'=>'patch','url' => "products/$id",'id'=>'form_update_product']) !!}
                      
                        <div class="form-group row">
                              {{ Form::label('Product Name: ', null, ['class' => 'col-xs-12 col-md-2 col-form-label col-form-label-lg']) }}
                        <?php $i=0; ?>
                        @foreach($languages as $row)
                              <div class="col-xs-12 col-md-5">
                              {{ Form::text('products_name['.$row["language_id"].']',$edit_products_description[$i]['products_name'], array('required', 
                                  'class'=>'form-control form-control-lg '.$row['direction'],'placeholder'=>$row['name'])) 
                              }}
                          </div>
                        <?php $i++; ?>
                        @endforeach
                        </div>


                        <div class="form-group row">
                              {{ Form::label('Product Description: ', null, ['class' => 'col-xs-12 col-md-2 col-form-label col-form-label-lg']) }}
                        <?php $i=0; ?>
                        @foreach($languages as $row)
                              <div class="col-xs-12 col-md-5">
                            {{ Form::textarea('products_description['.$row["language_id"].']', $edit_products_description[$i]['products_description'], ['class' => 'description '.$row['direction']]) }} 
                          </div>
                        <?php $i++; ?>
                        @endforeach
                        </div>


                        <div class="form-group row">
                              {{ Form::label('Product Status:', null, ['class' => 'col-xs-12 col-md-2 col-form-label col-form-label-lg']) }}
                              <div class="col-xs-12 col-md-10">
                                {{ Form::select('fk_product_status', $statuses,$edit_products->fk_product_status, ['class' => 'form-control']
                                ) }}
                            </div>

                            @if ($errors->has('fk_product_status')) <p class="help-block error">{{ $errors->first('fk_product_status') }}</p> @endif
                        </div>


                        <div class="form-group row">
                              {{ Form::label('Product SKU:', null, ['class' => 'col-xs-12 col-md-2 col-form-label col-form-label-lg']) }}
                              <div class="col-xs-12 col-md-10">
                              {{ Form::text('products_sku', $edit_products->products_sku, array('required', 
                                  'class'=>'form-control form-control-lg' )) 
                              }}

                              @if ($errors->has('products_sku')) <p class="help-block error">{{ $errors->first('products_sku') }}</p> @endif
                          </div>
                        </div>


                        <div class="form-group row">
                              {{ Form::label('Product Price:', null, ['class' => 'col-xs-12 col-md-2 col-form-label col-form-label-lg']) }}
                              <div class="col-xs-12 col-md-10">
                              {{ Form::text('base_price', $edit_products->base_price, array('required', 
                                  'class'=>'form-control form-control-lg' )) 
                              }}
                              @if ($errors->has('base_price')) <p class="help-block error">{{ $errors->first('base_price') }}</p> @endif
                          </div>
                        </div>
                        

                        <div class="form-group row">
                              {{ Form::label('Meta Keywords:', null, ['class' => 'col-xs-12 col-md-2 col-form-label col-form-label-lg']) }}
                              <div class="col-xs-12 col-md-10">
                              {{ Form::text('meta_keywords', $edit_products->meta_keywords, array('required', 
                                  'class'=>'form-control form-control-lg' )) 
                              }}

                              @if ($errors->has('meta_keywords')) <p class="help-block error">{{ $errors->first('meta_keywords') }}</p> @endif
                          </div>
                        </div>

                        <div class="form-group row">
                              {{ Form::label('Meta Description:', null, ['class' => 'col-xs-12 col-md-2 col-form-label col-form-label-lg']) }}
                              <div class="col-xs-12 col-md-10">
                              {{ Form::text('meta_description', $edit_products->meta_description, array('required', 
                                  'class'=>'form-control form-control-lg' )) 
                              }}

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
    <div class="row">
          <div class="page-header admin-header">
              <h3 id="page-title">Categories Tagging</h3>      
        </div>
    </div>
    <div class="row">
          <div class="col-md-12 admin-table-view">
               <!-- -->
                <div class="panel-body">
                      <div class="form-group row">
                        {{ Form::label('Categories:', null, ['class' => 'col-sm-2 col-form-label col-form-label-lg']) }}
                          <div class="col-sm-8">
                              <select name="categories" id="category_id" class="form-control">
                                @foreach($categories as $row)
                                  <option value="<?=$row['category_id']?>"><?=$row['category_name']?></option>
                                @endforeach
                              </select>
                          </div>

                          <div class="col-sm-2">
                              <button id="add_tags" class="btn btn-primary">Add</button>
                          </div>
                      </div>

                      <table class="table table-bordered" id="tbl_categories_tagged">
                        <thead>
                          <tr>
                            <th>Id</th>
                            <th>Tagged In Category</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                       
                        <tbody>
                          @foreach($get_mapped_categories as $key=>$row)
                          <tr>
                            <td>{{$key+1}}</td>
                            <td>{{implode(' > ',array_reverse($row))}}</td>
                            <td><a href="{{url('products/removeTags/'.$get_mapped_ids[$key])}}">Delete</a></td>
                           </tr>
                          @endforeach
                        </tbody>
                      </table>
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

    $('#add_tags').on('click', function (e) {
        var category_id = $('#category_id').val();
        $.ajax({
            type: "POST",
            url : {!! json_encode(url('/products/addTags')) !!},
            data: {"_token": "{{ csrf_token() }}",category_id: category_id,product_id:{{$id}}},
            success: function( msg ) {
              if(msg.status == 'success'){
               var sno = $('#tbl_categories_tagged > tbody tr:last td:first').text();
                if(sno == ''){ sno = 0; }
                $("#tbl_categories_tagged > tbody:last").append("<tr><td>"+(parseInt(sno)+1)+"</td><td>"+msg.category+"</td></tr>");
                var remove_tag_url = {!! json_encode(url('/products/removeTags')) !!};
                $("#tbl_categories_tagged > tbody tr:last").append("<td><a href='"+remove_tag_url+"/"+msg.tag_id+"'>Delete</a></td>");
              }else{
                alert(msg.message)
              }
            }
        });
    });

  });


  $("#form_update_product").validate({
      rules: {
        id_languages: {
          required: true,
          number: true
        },
        products_name: {
          required: true,
          minlength: 2,
          maxlength: 60,
        },
        products_description: {
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
        }
      },
      messages: {
        id_languages: "Please select a Language",
      }
    });


</script>

@endsection


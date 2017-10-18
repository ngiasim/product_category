<!-- edit.blade.php -->

@extends('layouts.cockpit_master')
@section('content')

<div class="product-header">
      <h3 class="pull-left">Update Products - Categorization</h3>
      @include('products::common-product-header')
</div>
      <div style="clear:both"></div>
        

<div class="container">
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
                            <th>Product Tagged In Category</th>
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
        </div>
    </div>
  </div>

@endsection
@section('script')

<script type="text/javascript">
$(document).ready(function() {

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

</script>
@endsection
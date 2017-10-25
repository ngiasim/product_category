<div class="text-right quick-access open">
    <a href="#" class="arrowholder">
      <span class="tigger" title="Quick access">
        <i class="fa fa-arrow-circle-left" aria-hidden="true"></i>
      </span>
    </a>

    <div class="link-holder">
      <a class="btn  btn-primary btn-xs" href="{{url('products/'.$id.'/edit')}}">Product Info</a>
      <a class="btn  btn-primary btn-xs" href="{{url('products/categorization/'.$id)}}">Category</a>
      <a class="btn  btn-primary btn-xs" href="{{url('products/seo/'.$id)}}">Seo</a>
      <a class="btn  btn-primary btn-xs" href="{{url('products/inventory/'.$id)}}">Attributes</a>
      <a class="btn  btn-primary btn-xs" href="{{url('products/images/'.$id)}}">Images</a>
      <a class="btn  btn-primary btn-xs" href="{{url('products/logs/'.$id)}}">Logs</a>
    </div>
</div>

  <div>
    <br>
    <br>
      <p style="float:left">Name : <b>{{$meta_data['products_description']['products_name']}}</b></p>
      <p style="float:left; margin-left:50px;">SKU : <b>{{$meta_data['products_sku']}}</b></p>
      <p style="float:left; margin-left:50px;">Status : <b>{{$meta_data['products_status']['status_name']}}</b></p>
      <p style="float:left; margin-left:50px;">Price : <b>{{$meta_data['base_price']}}</b></p>   
  </div>


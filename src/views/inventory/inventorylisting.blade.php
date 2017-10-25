<div class="row">
        <div class="col-md-12 admin-table-view">
             <table class="table table-bordered" id="table_data">
      <thead>
        <tr>
          <th>ID</th>
          <th>Inventory Code</th>
          <th>QTY On Hand</th>
          <th>QTY Reserved</th>
          <th>QTY Pre Order</th>
          <th>QTY Total</th>
          <th>QTY Admin Reserved</th>
          <th>QTY Available</th>
          <th>Price</th>
          <th>Price Impact</th>
          <th>Created At</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        @foreach($inventoryObj as $r)
          @foreach($r->mapProductInventoryItem as $row)
          <tr>
                <td>{{$row->inventory->inventory_id}}</td>
                <td>{{$row->inventory->inventory_code}}</td>
                <td>{{$row->inventory->qty_onhand}}</td>
                <td>{{$row->inventory->qty_reserved}}</td>
                <td>{{$row->inventory->qty_preorder}}</td>
                <td>{{$row->inventory->qty_total}}</td>
                <td>{{$row->inventory->qty_admin_reserved}}</td>
                <td>{{($row->inventory->qty_onhand-$row->inventory->qty_reserved-$row->inventory->qty_admin_reserved)+$row->inventory->qty_preorder}}</td>
                <td>{{$row->inventory->inventory_price}}</td>
                <td><center><b> {{$row->inventory->inventory_price_prefix}} </b></center></td>
                <td>{{\Carbon\Carbon::parse($row->inventory->created_at)->toDayDateTimeString() }}</td>
                <td>
                     <!-- <span class="table-action-icons">
                          <a href="{{url('categories/'.$row['category_id'].'/edit')}}">
                               <i class="glyphicon glyphicon-edit"></i>
                          </a>
                     </span> -->
                     <span class="table-action-icons">
                        {!! Form::open(['method'=>'DELETE','url' => 'products/inventory/delete/'.$row->inventory->inventory_id]) !!}
                          <input id="product_id" name="product_id" value="{{$r->product_id}}" type="hidden">
                          <button type="submit" class="glyphicon glyphicon-trash"></button>
                        {!! Form::close() !!}
                      </span>
                </td>
          </tr>
          @endforeach
         @endforeach


      </tbody>
    </table>
  </div>
</div>

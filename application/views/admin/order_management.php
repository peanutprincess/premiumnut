

<div class="jumbotron jumbotron-fluid text-center">
       <h1 class="display-4">Order Management (Admin Only)</h1>
      
</div>

<div class="container">
      
    <div class="row text-center guides-nav">
        <div class="col-md-12">
            <a href="https://order.premiumnut.com/pages/export_order_list_quickbooks" class="btn btn-primary pull-right marginbottom10">Export all to .xlsx (Quick Books)</a>
        </div>
        <div class="col-md-12">
            <table class="table order_table datatable" id="order_management_table" width="100%"> 
                <thead>
                    <tr>
                        <th>ID.</th>
                        <th>Order ID</th>
                        <th>No. of Products</th>
                        <th>Quantity of Products</th>
                        <th>Total Cost</th>
                        <th>User</th>
                        <th>Ordered on</th> 
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($orders_list as $key=>$order){
                        $key++;
                        echo '<tr>';
                            echo '<td>'.$order['id'].'</td>';
                            echo '<td>'.$order['order_id'].'</td>';
                            echo '<td>'.$order['total_products'].'</td>';
                            echo '<td>'.$order['order_total_qty'].'</td>';
                            echo '<td>'.$order['order_total_price'].'</td>';
                            echo '<td>'.$order['user_id'].'</td>';
                            echo '<td>'.gmdate("d-m-Y\ H:i\ ", $order['datetime']).'</td>';
                            echo '<td> 
                            <a href="#show_order" title="Edit Order" class="edit show_order" order-id="'.$order['id'].'"><i class="fa fa-info-circle" aria-hidden="true"></i></a>
                            /
                            <a href="#delete_order" title="Delete Order" class="delete delete_order" order-id="'.$order['id'].'"><i class="fa fa-trash" aria-hidden="true"></i></a>
                            /
                            <a href="https://order.premiumnut.com/pages/export_order_list_quickbooks?order_id='.$order['id'].'" title="Export this Order" class="export export_order"><i class="fas fa-file-export"></i></a>

                            </td>';
                        echo '</tr>';
                    } ?>
                </tbody>
                <tfoot></tfoot>
            </table>

        </div>
    </div>
</div>


<!-- Show Modal -->
<div class="modal fade" id="show_order_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="exampleModalLabel">Viewing Order # <span id="order_data_id"></span></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


<!-- Delete Modal -->
<div class="modal fade" id="delete_order_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">

  <?php echo form_open('admin/delete_order', array('id' => 'delete_order_form', 'class' => 'delete_order_controller')); ?>

    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="exampleModalLabel">Delete Order # <span id="order_data_id"></span></h4>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to delete this order ?</p>
        <input type="hidden" name="delete_order_id" id="delete_order_id" value="">
      </div>
      <div class="modal-footer">
        <input type="submit" value="Delete Order" id="Delete_order_submit" class="btn btn-primary">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
      </div>
    </div>

    <?php echo form_close(); ?>

  </div>
</div>

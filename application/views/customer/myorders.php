

<div class="jumbotron jumbotron-fluid text-center">
       <h1 class="display-4">My Orders</h1>
        <p>List of all the orders submitted by you.</p>
</div>

<div class="container">
      
    <div class="row text-center guides-nav">
        <div class="col-md-12">
            <table class="table order_table datatable" id="order_management_table" width="100%"> 
                <thead>
                    <tr>
                        <th>Sr.</th>
                        <th>Order ID</th>
                        <th>No. of Products</th>
                        <th>Total Cost</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($user_order_list as $key=>$order){
                        $key++;

                        $status = '';

                        if($order['status'] == 0){
                            $status = 'Received';
                        }
                        else if($order['status'] == 1){
                            $status = 'Processing';
                        }
                        else if($order['status'] == -1){
                            $status = 'Cancelled/Rejected';
                        }
                        else{
                          $status = 'Unknown';
                        }

                        echo '<tr>';
                            echo '<td>'.$key.'</td>';
                            echo '<td>'.$order['order_id'].'</td>';
                            echo '<td>'.$order['total_products'].'</td>';
                            echo '<td>'.$order['order_total_price'].'</td>';
                            echo '<td>'.gmdate("d-m-Y\ H:i\ ", $order['datetime']).'</td>';
                            echo '<td>'.$status.'</td>';
                            echo '<td> 
                            <a href="#show_order" class="show_order" title="Show Details" order-id="'.$order['id'].'"><i class="fa fa-info-circle" aria-hidden="true"></i></a>
                            /
                            <a href="#reorder" class="reorder" title="Reorder"  order-id="'.$order['id'].'"><i class="fa fa-refresh" aria-hidden="true"></i></a>
                            </td>';
                        echo '</tr>';
                    } ?>
                </tbody>
                <tfoot></tfoot>
            </table>

        </div>
    </div>
</div>


<!-- Modal -->
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

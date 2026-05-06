

<div class="jumbotron jumbotron-fluid text-center">
       <h1 class="display-4">Price Level Settings (Admin Only)</h1>
      
</div>

<div class="container">
      
    <div class="row text-center product_management_row">
    
        <div class="col-md-12">
          <a href="#" id="add_price_level" class="btn btn-primary pull-right">Add a Price Level</a>
        </div>
        <div class="col-md-12">
            <table class="table price_level_datatable" width="100%"> 
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Sort Order</th>
                        <th>Price Level Title</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($price_level as $key=>$product){
                        $key++;
                        echo '<tr>';
                          echo '<td>'.$product['id'].'</td>';
                            echo '<td>'.$product['sort_order'].'</td>';
                            echo '<td>'.$product['name'].'</td>';

                            echo '<td class="actions_tab"> 
                            <a href="#edit_price_level" title="Edit" class="edit edit_price_level" price-level-id="'.$product['id'].'"><i class="fa fa-pencil" aria-hidden="true"></i>
                            </a>
                            /
                            <a href="#delete_price_level" title="Delete" class="delete delete_price_level" price-level-id="'.$product['id'].'"><i class="fa fa-trash" aria-hidden="true"></i>
                            </a>
                            </td>';
                        echo '</tr>';
                    } ?>
                </tbody>
                <tfoot></tfoot>
            </table>

        </div>
    </div>
</div>

</div>



<!-- Add Modal -->
<div class="modal fade" id="add_price_level_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
    <?php echo form_open('admin/add_price_level', array('id' => 'add_price_level_form', 'class' => 'update_profile_controller')); ?>

      <div class="modal-header">
        <h4 class="modal-title" id="exampleModalLabel">Add new price level </h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body row">
        
            <ul class="profile_ul col-md-12" style="padding:0px 15px !important;">
                <li>
                    <label>Price Level Title</label>
                    <input type="text" name="price_level_name" value="" class="form-control" placeholder="">
                </li>

                <li>
                    <label>Sort Order</label>
                    <input type="number"name="sort_order" value="" class="form-control" >
                </li>

            </ul>
      </div>
      <div class="modal-footer">
        <input type="submit" value="Add Price Level" id="add_price_level_submit" class="btn btn-primary">

        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
      </div>

      <?php echo form_close(); ?>

    </div>
  </div>
</div>


<!-- Edit Modal -->
<div class="modal fade" id="edit_price_level_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">

  <?php echo form_open('admin/update_price_level', array('id' => 'update_price_level_form', 'class' => 'update_price_level_controller')); ?>

    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="exampleModalLabel">Edit Price Level # <span id="price_level_data_id"></span></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        
      </div>
      <div class="modal-footer">
        <input type="submit" value="Update Price Level" id="update_price_level_submit" class="btn btn-primary">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>

    <?php echo form_close(); ?>

  </div>
</div>



<!-- Delete Modal -->
<div class="modal fade" id="delete_price_level_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">

  <?php echo form_open('admin/delete_price_level', array('id' => 'delete_price_level_form', 'class' => 'delete_product_controller')); ?>

    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="exampleModalLabel">Delete Price Level # <span id="price_level_data_id"></span></h4>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to delete this Price Level ?</p>
        <input type="hidden" name="delete_price_level_id" id="delete_price_level_id" value="">
      </div>
      <div class="modal-footer">
        <input type="submit" value="Delete Price Level" id="Delete_price_level_submit" class="btn btn-primary">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
      </div>
    </div>

    <?php echo form_close(); ?>

  </div>
</div>




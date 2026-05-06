

<div class="jumbotron jumbotron-fluid text-center">
       <h1 class="display-4">Product Management (Admin Only)</h1>
      
</div>

<div class="container">
      
    <div class="row text-center product_management_row">
    
        <div class="col-md-12">
          <a href="#" id="add_product" class="btn btn-primary pull-right">Add a Product</a>
        </div>
        <div class="col-md-12">
            <table class="table product_table datatable" id="product_management_table" width="100%"> 
                <thead>
                    <tr>
                        <th> ID</th>
                        <th>Master ID</th>
                        <th>Description</th>
                        <th>U/M</th>
                        <th>Price L1</th>
                        <th>Price L2</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($products_list as $key=>$product){
                        $key++;
                        echo '<tr>';
                          echo '<td>'.$product['id'].'</td>';
                            echo '<td>'.$product['master_id'].'</td>';
                            echo '<td>'.$product['description'].'</td>';
                            echo '<td>'.$product['u-m'].'</td>';
                            echo '<td>'.number_format((float)$product['level1price'], 2, '.', '').'</td>';
                            echo '<td>'.number_format((float)$product['level2price'], 2, '.', '').'</td>';

                            echo '<td class="actions_tab"> 
                            <a href="#edit_product" title="Edit" class="edit edit_product" product-id="'.$product['id'].'"><i class="fa fa-pencil" aria-hidden="true"></i>
                            </a>
                            /
                            <a href="#delete_product" title="Delete" class="delete delete_product" product-id="'.$product['id'].'"><i class="fa fa-trash" aria-hidden="true"></i>
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

<!-- Modal -->
<div class="modal fade" id="add_product_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
    <?php echo form_open('admin/add_product', array('id' => 'add_product_form', 'class' => 'update_profile_controller')); ?>

      <div class="modal-header">
        <h4 class="modal-title" id="exampleModalLabel">Add new product </h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body row">
        
            <ul class="profile_ul col-md-12" style="padding:0px 15px !important;">
                <li>
                    <label> Name</label>
                    <input type="text" name="product_name" value="" class="form-control" placeholder="CANDY:SOUR BELTS:C2350:1289">
                </li>

                <li>
                    <label> Master ID</label>
                    <input type="number" name="master_id" value="" class="form-control" required>
                    <span class="master_id_check_result"></span>
                </li>
 
                <li> 
                    <label>Description</label>
                    <input type="text" name="description" value="" class="form-control"  placeholder="1289 - SOUR BELTS (1 LB BAG)">
                </li>

                
                <li>
                    <label>U/M</label>
                    <select name="u_m" class="form-control" required >
                        <option value="">Select</option>
                        <?php 
                        foreach($umlist as $um){
                          echo '<option value="'.$um['id'].'">'.$um['name'].'</option>';
                        }
                        ?>
                    </select>
                </li>


                <li>
                    <label>Product Type</label>
                    <select name="product_type" class="form-control" required>
                        <option value="">Select</option>
                        <?php 
                        foreach($product_type as $ptype){
                          echo '<option value="'.$ptype['id'].'">'.$ptype['name'].'</option>';
                        }
                        ?>
                    </select>
                </li>

                <li>
                    <label>Weight Type</label>
                    <select name="weight_type" class="form-control" required>
                        <option value="">Select</option>
                        <?php 
                        foreach($weight_type as $weight){
                          echo '<option value="'.$weight['id'].'">'.$weight['name'].'</option>';
                        }
                        ?>
                    </select>
                </li>

                <?php foreach($price_level as $price_level_item){ ?>

                  <li>
                      <label>Price (Level <?php echo $price_level_item['name']; ?>)</label>
                      <input type="number" step=0.01 name="price<?php echo $price_level_item['id']; ?>" value="" class="form-control" >
                  </li>

                <?php } ?>


            </ul>
      </div>
      <div class="modal-footer">
        <input type="submit" value="Add Product" id="add_product_submit" class="btn btn-primary">

        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
      </div>

      <?php echo form_close(); ?>

    </div>
  </div>
</div>


<!-- Edit Modal -->
<div class="modal fade" id="edit_product_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">

  <?php echo form_open('admin/update_product', array('id' => 'update_product_form', 'class' => 'update_product_controller')); ?>

    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="exampleModalLabel">Edit Product # <span id="product_data_id"></span></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        
      </div>
      <div class="modal-footer">
        <input type="submit" value="Update Product" id="update_product_submit" class="btn btn-primary">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>

    <?php echo form_close(); ?>

  </div>
</div>



<!-- Delete Modal -->
<div class="modal fade" id="delete_product_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">

  <?php echo form_open('admin/delete_product', array('id' => 'delete_product_form', 'class' => 'delete_product_controller')); ?>

    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="exampleModalLabel">Delete Product # <span id="product_data_id"></span></h4>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to delete this product ?</p>
        <input type="hidden" name="delete_product_id" id="delete_product_id" value="">
      </div>
      <div class="modal-footer">
        <input type="submit" value="Delete Product" id="Delete_product_submit" class="btn btn-primary">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
      </div>
    </div>

    <?php echo form_close(); ?>

  </div>
</div>




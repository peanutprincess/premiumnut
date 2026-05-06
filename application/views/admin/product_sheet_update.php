

<div class="jumbotron jumbotron-fluid text-center">
       <h1 class="display-4">Product Sheet DB Update (Admin Only)</h1>
      
</div>

<div class="container">
      
    <div class="row text-center">
        <div class="col-md-12">
      
        <?php echo form_open_multipart('pages/product_sheet_update', array('id' => 'update_bulk_price', 'class' => 'update_price_controller')); ?>

        <div>
            <label>Upload excel(.xlsx) file to check:</label><br>
            <input type="file" name="file" id="file" accept=".xls,.xlsx">
        </div>
        
        <div>
            <input type="submit" class="btn btn-primary" name="import" id="product_sheet_update_submit" value="Check File">
        </div>


        <?php echo form_close(); ?>

        <div id="product_sheet_update_list"></div>

        </div>
    </div>
</div>



</div>

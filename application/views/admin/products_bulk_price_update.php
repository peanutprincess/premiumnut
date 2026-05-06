

<div class="jumbotron jumbotron-fluid text-center">
       <h1 class="display-4">Product Bulk Price Update(Admin Only)</h1>
      
</div>

<div class="container">
      
    <div class="row text-center">
        <div class="col-md-12">
      
        <?php echo form_open_multipart('pages/update_bulk_price', array('id' => 'update_bulk_price', 'class' => 'update_price_controller')); ?>

        <div>
            <label>Upload excel(.xlsx) file to check for price changes in database:</label><br>
            <input type="file" name="file" id="file" accept=".xls,.xlsx">
        </div>
        
        <div>
            <input type="submit" class="btn btn-primary" name="import" id="bulk_price_check_submit" value="Check File">
        </div>


        <?php echo form_close(); ?>

        <div id="bulk_price_check_list"></div>

        <small style="text-align:left;display: block;">            
            Note: System will only display products with differences in either prices.<br>
            Note: tested on file (All Price Lists 10_29_21.xlsx) with MasterID as 3rd column, Price 1 as 12th column and Price 2 as as 10th column in excel sheet.
        </small>

        </div>
    </div>
</div>



</div>

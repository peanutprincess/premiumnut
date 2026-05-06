
<div class="jumbotron jumbotron-fluid text-center">
       <h1 class="display-4">ORDER FORM</h1>
       <p><a target="_blank" href="<?php echo $this->config->base_url(); ?>/assets/images/how_to_order_guide.png">Click here for INSTRUCTIONS on How to Order Guide.</a></p>
</div>

<div class="container order_form">
 
<?php echo form_open('pages/order_submission', array('id' => 'order_submission', 'class' => 'order_form_controller loading')); ?>
            <div class="col-md-12"> 
                <p>
                    All Prices are approximate. Prices subject to change without notice. Availability may vary.
                    <br>
                     Please check your invoice upon recieving your order to see finalized prices.
                    </p>
            </div>
            <div class="loadersmall"></div>
            <div class="row text-center "> 


                <div class="col-md-12"> 

                    <ul class="product_listing">
                        <li class="form_head">
                            <p>&nbsp;</p>
                            <p>&nbsp;</p>
                            <p>Unit Cost</p>
                            <p>Line Cost</p>
                        </li>

                        <?php
                        $count = 0;
                        $gtotal = 0;
                        //var_dump($_SESSION['cart_data']);
                        if(isset($_SESSION['cart_data'])){
                            foreach($_SESSION['cart_data'] as $cart_item){
                                $count++;
                                $total = $cart_item['price'] * $cart_item['qty'];
                                $gtotal = $gtotal + $total;

                                echo '
                                <li item-id="'.$count.'">';

                                if($count != 1){
                                    echo '
                                    <a class="remove_item" title="Remove Item"><i class="fa fa-times" aria-hidden="true"></i></a>
                                    ';
                                }

                                echo
                                '   <input type="text" class="form-control itemname" onchange="product_data(jQuery(this).attr(&apos;name&apos;))" list="product_items" name="itemname-'.$count.'" placeholder="Select an item" value="'.$cart_item['description'].'">
                                    <input type="number" class="form-control itemqty" onkeyup="qty_calc(jQuery(this).attr(&apos;name&apos;))" name="itemqty-'.$count.'" min="1" max="100" placeholder="Select Quantity" value='.$cart_item['qty'].'>
                                    <input type="number" step="0.01" class="itemunit readonly" value="'.$cart_item['price'].'" name="itemunitcost-'.$count.'" readonly="" >
                                    <input type="number" step="0.01" class="itemline readonly" value="'.$total.'" name="itemlinecost-'.$count.'" readonly="" >
                                    <input type="hidden" name="product-id-'.$count.'" value="'.$cart_item['id'].'">
                                </li>
                                ';
                            }
                        }
                        else{
                            ?>
                                <li item-id="1">
                                    <input type="text" class="form-control itemname" onchange="product_data(jQuery(this).attr('name'))" list="product_items"
                                    name="itemname-1" placeholder="Select an item">
                                    <input type="number" class="form-control itemqty" onkeyup="qty_calc(jQuery(this).attr('name'))" name="itemqty-1" min="1" max="100" placeholder="Select Quantity" disabled="disabled">
                                    <input type="number" step="0.01" class="itemunit readonly" value="0.00" name="itemunitcost-1" readonly>
                                    <input type="number" step="0.01" class="itemline readonly" value="0.00" name="itemlinecost-1" readonly>
                                </li>
                            <?php
                        }
                        ?>
                    </ul>
                    
                </div> 
            </div>

            <div class="row form_footer"> 
                <div class="col-md-12">
                    <ul class="form_foot">
                        <li>
                            <p>&nbsp;</p>
                            <p>&nbsp;</p>
                            <p>Total</p>
                            <p id="total_cost">
                                <?php
                                if(isset($_SESSION['cart_data'])){
                                    echo $gtotal;
                                }
                                else{
                                    echo '0.00';
                                }
                                ?>
                                
                            </p>
                        </li>
                    </ul>
                </div>
                <div class="col-md-6 text-left"> 
                    <button type="button" id="add_item" class="btn btn-primary">
                    + Add Item</button>
                    <p>Add more items to order form</p>
                </div> 
                <div class="col-md-6 text-right"> 
                    <input type="text" name="po_number" placeholder="PO Number (optional)" class="form-control">
                    <textarea name="order_notes" rows="5" placeholder="Notes (optional)" class="form-control"></textarea>
                    <button type="submit"  class="btn btn-primary">
                        Submit
                    </button>
                </div> 
            </div>
            <input type="hidden" name="user_level" value="<?php echo  $level; ?>">
        <?php echo form_close(); ?>
</div>

<datalist id="product_items">

</datalist>
<div id="cart_menu">
    <div class="cart_container">
        <div class="cart_header">
            <button id="close_cart_menu" class="btn btn-primary"><i class="fa fa-times" aria-hidden="true"></i></button>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>Item Name</th>
                    <th>Price</th>
                    <th>Qty</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php
                	//var_dump($_SESSION);

                    if(isset($_SESSION['cart_data'])){
                        $gtotal = 0;

                        foreach($_SESSION['cart_data'] as $cart_item){
                            $total = $cart_item['price'] * $cart_item['qty'];
                            $gtotal = $gtotal + $total;

                            echo '
                            <tr data-master-id="'.$cart_item['masterid'].'">
                                <td>'.$cart_item['description'].'</td>
                                <td>'.$cart_item['qty'].'</td>
                                <td>'.$cart_item['price'].'</td>
                                <td>'.$total.'</td>
                            </tr>
                            ';
                        }
                    }
                ?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3">Grand Total</th>
                    <?php
                    if(isset($_SESSION['cart_data'])){
                        echo '<th data-total>$'. $gtotal .'</th>';
                    }
                    else{
                        echo '<th data-total>$0</th>';
                    }
                    ?>
                </tr>
            </tfoot>
        </table>   

        <div class="cart_footer">
            <form action="<?php echo base_url(); ?>order">
                <input type="submit" class="btn btn-primary" value="Checkout" />
            </form>
            <button class="btn btn-primary" id="clear_cart">Empty Cart</button>
        </div>
        
 
    </div>
</div>



<div class="jumbotron jumbotron-fluid text-center printable">
    <h1 class="display-4">Product Guide (<?php echo $weight_name; ?>)</h1>
</div>
<?php 
$level = 0;
$level = $this->session->userdata('level');
if($level == 0){
    $level = 1;
}
?>

<div class="container">
    <div class="row">
        <div class="col-md-12 ">
            <button id="print_me" class="btn btn-primary pull-right marginbottom10">Print this Guide</button>
        </div>
    </div>
</div>

<div class="container printable">
      
    <div class="row text-center guides-nav product_management_row ">
        <div class="col-md-12">
            <form id="bulk_add_to_cart_form" method="post">
                <table class="table product_table" id="product_management_table" width="100%"> 
                    <thead>
                        <tr>
                            <th>Master ID</th>
                            <th>Description</th>
                            <th>U/M</th>
                            <th>Price</th>
                            <th> 
                                <input  
                                type="submit" 
                                class="btn btn-primary"
                                id="bulk_add_to_cart" 
                                name="addbulk" 
                                value="Bulk add to cart">
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($products_list as $key=>$product){
                                $key++;
                                $error = 0;
                                
                                if($level <= 1){
                                    $error = 0;
                                }
                                else if($level == 2){
                                    $error = 0;
                                }
                                else if(isset($product['price_level_price']) && $level > 2){
                                    $error = 0;
                                }
                                else{
                                    $error = 1;
                                }

                                if($error == 0){
                                    echo '<tr>';
                                        echo '<td>'.$product['master_id'].'</td>';
                                        echo '<td>'.$product['description'].'</td>';
                                        echo '<td>'.$product['u-m'].'</td>';
                                        echo '<td>';
                                        if($level <= 1){
                                            echo $product['level1price'];
                                        }
                                        else if($level == 2){
                                            echo $product['level2price'];
                                        }
                                        else if(isset($product['price_level_price']) && $level > 2){
                                            echo $product['price_level_price'];
                                        }
                                        else{
                                            echo '0.00';
                                        }
                                        echo '</td>';

                                        $item_qty = '';

                                        if(isset($_SESSION['cart_data'])){
                                            foreach($_SESSION['cart_data'] as $cart_item){
                                                if($cart_item['masterid'] == $product['master_id']){
                                                    $item_qty = $cart_item['qty'];
                                                }
                                            }
                                        }

                                        echo '<td>
                                        <input type="number" placeholder="Enter Quantity" name="'.$product['master_id'].'" value='.$item_qty.'>
                                        </td>';

                                    echo '</tr>';
                                }

                            
                            
                        } ?>
                    </tbody>
                    <tfoot></tfoot>
                </table>
            </form>
        </div>
    </div>
</div>

<div class="container" style="display:none;">
    <hr />
    <h2>excel sheet importer (beta)</h2>
    <input type="file" id="fileUpload" />
    <input type="button" id="importerupload" value="Upload" />
    <hr />
    <div id="dvExcel"></div>

</div>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.13.5/xlsx.full.min.js"></script>

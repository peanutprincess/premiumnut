

/* ---------------------------------------------------------------------------- order form script */
jQuery('#add_item').click(function(){

    var total_items = jQuery( ".product_listing li" ).length;
    var new_item_id = jQuery( ".product_listing li" ).last().attr('item-id');
    new_item_id++;
    //alert("total_items length" + total_items);

    var item_html = '<li ' +
    ' item-id="'+ new_item_id +'">'+
    '<a class="remove_item" title="Remove Item"><i class="fa fa-times" aria-hidden="true"></i></a>'+
    '<input type="text" class="form-control itemname" onchange="product_data(jQuery(this).attr(\'name\'))" list="product_items" name="itemname-'+new_item_id+'" placeholder="Select an item"> ' +
    '<input type="number" class="form-control itemqty" onkeyup="qty_calc(jQuery(this).attr(\'name\'))" name="itemqty-'+new_item_id+'" min="1"  max="100" placeholder="Select Quantity" disabled="disabled"> ' +
    '<input type="number" step="0.01" class="itemunit readonly" value="0.00" name="itemunitcost-'+new_item_id+'" readonly>'+
    '<input type="number" step="0.01" class="itemline readonly" value="0.00" name="itemlinecost-'+new_item_id+'" readonly>'+
    '</li>';

    $( ".product_listing" ).append( item_html);

    jQuery('.remove_item').click(function(){
        var get_item_id = jQuery(this).parent().attr('item-id');
        //console.log("get_item_id: " + get_item_id);
        jQuery( ".product_listing li" ).remove( "[item-id="+ get_item_id +"]" );
        calc_total();
    });

});

jQuery('.remove_item').click(function(){
    var get_item_id = jQuery(this).parent().attr('item-id');
    jQuery( ".product_listing li" ).remove( "[item-id="+ get_item_id +"]" );
    calc_total();
    console.log("get_item_id: " + get_item_id);
});



/* ---------------------------------------------------------------------------- global functions - start */

function product_data(value) {
    var getvalue = jQuery( '[name='+value+']' ).val();
    var gethtmlelement = jQuery( '[name='+value+']' ).parent('li');
    var get_item_id = jQuery( '#product_items option[value="'+getvalue+'"]' ).attr('product-id');
    var getliitemid = jQuery( '[name='+value+']' ).parent('li').attr('item-id');

    if(getvalue == ''){
        gethtmlelement.children('.itemqty').prop( "disabled", true );
        gethtmlelement.children('.itemqty').val('0');
        gethtmlelement.children('.itemunit').val('0.00');
        gethtmlelement.children('.itemline').val('0.00');
        calc_total();
    }
    else{
        //console.log('itemno: '+ value);
        //console.log('getvalue: '+ getvalue);
        //console.log('get_product_id: '+ get_item_id);
        $.ajax({
            url: 'pages/get_product_info',
            type: "GET",
            dataType: "json",
            data: get_item_id, 
            success: function(data){
                //console.log('success ajax! data: ' , data);
                //console.log('data.id ' + data.id);
                var item_id_html = '<input type="hidden" name="product-id-'+getliitemid+'" value="'+data.id+'">';
                
                price = Number(data.price);

                gethtmlelement.append(item_id_html);
                gethtmlelement.children('.itemunit').val(price.toFixed(2));
                gethtmlelement.children('.itemqty').val('1');
                gethtmlelement.children('.itemqty').prop( "disabled", false );
                gethtmlelement.children('.itemline').val(price.toFixed(2));
                calc_total();
                //console.log(this);
            },
            error: function(xhr)
            {
                console.log('failed ajax!');
            }
        });
    }

}

function qty_calc(name) {
    var getvalue = jQuery( '[name='+name+']' ).val();
    var gethtmlelement = jQuery( '[name='+name+']' ).parent('li');
    var get_price_element = gethtmlelement.children('.itemunit').val();

    //console.log('name: '+ name);
    //console.log('getvalue: '+ getvalue);
    //console.log('gethtmlelement: '+ gethtmlelement);
    //console.log('get_price_element: '+ get_price_element);
    
    totalprice = Number(getvalue * get_price_element);
    //console.log('totalprice: '+ totalprice);
    gethtmlelement.children('.itemline').val(totalprice.toFixed(2));
    calc_total();
}

function calc_total() {
    getlinetotal = 0;
    jQuery( ".product_listing li[item-id]" ).each(function(index) {
        getline = parseFloat(jQuery(this).children('.itemline').val());
        //console.log('getlinetotal: ' + getlinetotal);
        //console.log('getline: ' + getline);
        getlinetotal = Number(getlinetotal + getline);
        jQuery( "#total_cost" ).html(getlinetotal.toFixed(2));
        //console.log('calc_total ------------------------------------------------ ' + index);
    });
}

function get_product_info_by_order_array(product_data_array){

    //console.log('product_data_array:' + product_data_array);

    var product_data_json = JSON.parse(product_data_array);
    //console.log(product_data_array);
    let all_product_ID_in_order = '';
    let all_product_product_qty_in_order = '';
    let all_product_product_total_in_order = '';


    $.each( product_data_json, function( key, product ) {
        all_product_ID_in_order = all_product_ID_in_order.concat(product.product_id+',');
        all_product_product_qty_in_order = all_product_product_qty_in_order.concat(product.product_qty+',');
        all_product_product_total_in_order = all_product_product_total_in_order.concat(product.product_total+',');
    });

    //console.log('all_product_ID_in_order: ' + all_product_ID_in_order);

    $.ajax({
        url: '../pages/get_product_info_order_bulk',
        type: "GET",
        dataType: "json",
        async: true,
        data: {ids: all_product_ID_in_order, qtys: all_product_product_qty_in_order, totals: all_product_product_total_in_order}, 
        success: function(product_data){
            //console.log( "product.product_id data: " , product_data );
            $.each( product_data, function( key, product_data ) {
                if(product_data.id != null){
                    //console.log( "product.id: " , product_data.id );
                    var this_order_level =  jQuery('#order_level').html();
                    var price;
                    this_order_level == 1 ? price = product_data.level1price : price = product_data.level2price
                    var tbody_product_row_html = ''+
                        '<tr>'+
                            '<td>'+product_data.id+'</td>'+
                            '<td>'+product_data.description+'</td>'+
                            '<td>'+product_data.product_qty+'</td>'+
                            '<td>$'+price+'</td>'+
                            '<td>$'+product_data.product_total+'</td>'+
                        '</tr>'+
                    '';
                    jQuery('#tbody_product_row').append(tbody_product_row_html);
                }
            });
        },
        error: function(xhr)
        {
            console.log('failed');
        }
    })


}


function calculate_total_cart(){
    get_item_total = 0;
    jQuery( "#cart_menu table tbody tr[data-master-id] td:nth-child(4)" ).each(function( index ) {
        //console.log('this: ' + jQuery(this));
        value = parseFloat(jQuery(this).text());
        get_item_total = get_item_total + value;
    });
    jQuery('#cart_menu table tfoot th[data-total]').html('$' + get_item_total.toFixed(2));
    //console.log('get_item_total: ' + get_item_total);
}

/* ---------------------------------------------------------------------------- global functions - end */

jQuery(document).ready(function() {
    //console.log('len: '+jQuery('.product_listing').length);
    if(jQuery('.product_listing').length > 0) {
        $.ajax({
            url: 'pages/products_list',
            type: "GET",
            dataType: "json",
            cache: false,
            success: function(data){
                //alert('success ajax!');
                //console.log(data);
                data.forEach(element => {
                    //console.log('element.description: ' + element.description );
                    var datalist = '<option value="'+element.description+'" product-id='+element.id+'>';
                    $( "#product_items" ).append( datalist);
                });
                jQuery('form.order_form_controller').removeClass('loading');

            },
            error: function(xhr)
            {
                alert('failed ajax!');
            }
        });
    }

    if(jQuery('#order_management_table').length > 0) {
        
        jQuery('.show_order').click(function(){

            var get_order_id = jQuery(this).attr('order-id');

            $.ajax({
                url: '../pages/get_order_info',
                type: "GET",
                dataType: "json",
                data: get_order_id, 
                success: function(data){
 
                    jQuery('#show_order_modal #order_data_id').html(data.order_id);
                    //console.log(data);
                    get_product_info_by_order_array(data.order_data);
                    order_level = data.order_level;

                    var order_details_html = ''+
                    '<div class="row">'+
                        '<div class="col-md-12">'+
                            '<ul class="order_details_ul">'+
                                '<li>'+
                                    '<label>No of Products:</label>'+
                                    '<p>'+data.total_products+'</p>'+
                                '</li>'+
                                '<li>'+
                                    '<label>Quantity of Products	:</label>'+
                                    '<p>'+data.order_total_qty+'</p>'+
                                '</li>'+

                                '<li>'+
                                    '<label>Order By:</label>'+
                                    '<p>'+data.user_id+' ('+data.company+')</p>'+
                                '</li>'+
                                '<li>'+
                                    '<label>Ordered on:</label>'+
                                    '<p>'+data.datetime+'</p>'+
                                '</li>'+
                                '<li>'+
                                    '<label>PO Number:</label>'+
                                    '<p>'+data.po_number+'</p>'+
                                '</li>'+
                                '<li>'+
                                    '<label>Order Notes:</label>'+
                                    '<p>'+data.notes+'</p>'+
                                '</li>'+
                                '<li>'+
                                '</li>'+
                                '<li>'+
                                    '<button id="print_me" class="btn btn-primary">Print</button>'+
                                '</li>'+
                            '</ul>'+
                        '</div>'+
                        '<div class="col-md-12 product_list">'+
                            '<h4>Products List:</h4>'+
                            '<table class="table" width="100%">'+
                                '<thead>'+
                                    '<tr>'+
                                        '<th>Product ID</th>'+
                                        '<th>Name</th>'+
                                        '<th>Quantity</th>'+
                                        '<th>Price '+order_level+'</th>'+
                                        '<th>Total</th>'+
                                    '</tr>'+
                                '</thead>'+

                                '<tbody id="tbody_product_row">'+
                                '</tbody>'+

                                '<tfoot>'+
                                    '<tr>'+
                                        '<th></th>'+
                                        '<th></th>'+
                                        '<th>'+data.order_total_qty+'</th>'+
                                        '<th></th>'+
                                        '<th>$'+data.order_total_price+'</th>'+
                                    '</tr>'+
                                '</tfoot>'+

                            '</table>'+
                        '</div>'+
                    '</div>';

                    jQuery('#show_order_modal .modal-body').html(order_details_html);

                    $('#show_order_modal').modal('show'); 

                    jQuery('#print_me').click(function(){
                        window.print();
                    });
                    
                    //console.log(data);
                },
                error: function(xhr)
                {
                    console.log('failed ajax!');
                }
            });
        });


        jQuery('.delete_order').click(function(){
            var get_order_id = jQuery(this).attr('order-id');
            jQuery('#delete_order_modal').modal('show');
            jQuery('#delete_order_modal #order_data_id').html(get_order_id);
            jQuery('#delete_order_modal input#delete_order_id').attr('value' , get_order_id);
        });

    }


    if(jQuery('#update_profile').length > 0) {
        
        jQuery('#update_profile_submit').click(function(event){
            event.preventDefault();
            var form_parent = jQuery('#update_profile');

            var password  = form_parent.find('input[name=password]');
            var cpassword  = form_parent.find('input[name=confirm_password]');
            
            //console.log('password: ' + password.val());
            //console.log('cpassword: ' + cpassword.val());

            if(password.val() !== cpassword.val()){
                error = 1;
                alert('Passwords do not match!');
                password.addClass('error');
                cpassword.addClass('error');
            }
            else{
                error = 0;
                password.removeClass('error');
                cpassword.removeClass('error');
            }

            if(error == 0){
                form_parent.addClass('loading');
                var ajaxurl = form_parent.attr('action');
                var field_data = form_parent.serializeArray();
                //console.log('serializeArray: ' , field_data);
                
                $.ajax({
                    url: ajaxurl,
                    type: "GET",
                    dataType: "json",
                    data: field_data,
                    cache: false,
                    success: function(data){
                        alert('Profile Updated');
                        form_parent.removeClass('loading');
                    },
                    error: function(xhr)
                    {
                        alert('Update Failed!');
                        form_parent.removeClass('loading');
                    }
                });
                
            }


        });


    }
    
    if(jQuery('.add_user').length > 0) {

        jQuery('.add_user').click(function(){
            $('#add_user_modal').modal('show'); 
        });

        jQuery('form#add_user_form').submit(function(event){
            event.preventDefault();
            var error = 0;
            var form_parent = jQuery('#add_user_form');
            var password  = form_parent.find('input[name=password]');
            var cpassword  = form_parent.find('input[name=confirm_password]');
            
            //console.log('password: ' + password.val());
            //console.log('cpassword: ' + cpassword.val());

            if(password.val() !== cpassword.val()){
                error = 1;
                alert('Passwords do not match!');
                password.addClass('error');
                cpassword.addClass('error');
            }
            else{
                error = 0;
                password.removeClass('error');
                cpassword.removeClass('error');
            }
            if(error == 0){
                this.submit();
            }
    
        });

        jQuery('form#update_user_form').submit(function(event){
            event.preventDefault();
            var error = 0;
            var form_parent = jQuery('#update_user_form');
            var password  = form_parent.find('input[name=password]');
            var cpassword  = form_parent.find('input[name=confirm_password]');
            
            //console.log('password: ' + password.val());
            //console.log('cpassword: ' + cpassword.val());

            if(password.val() !== cpassword.val()){
                error = 1;
                alert('Passwords do not match!');
                password.addClass('error');
                cpassword.addClass('error');
            }
            else{
                error = 0;
                password.removeClass('error');
                cpassword.removeClass('error');
            }
            if(error == 0){
                this.submit();
            }
    
        });



        if (window.location.href.indexOf('#userstab') > 0 ) {
            $('#v-pills-users-tab').click();
        }
    }

    if(jQuery('#add_product').length > 0) {

        jQuery('#add_product').click(function(){
            $('#add_product_modal').modal('show'); 
        });

        jQuery('form#add_product_form').submit(function(event){
            event.preventDefault();
            var error = 0;
            var form_parent = jQuery('#add_product_form');
            var product_name  = form_parent.find('input[name=product_name]');
            var master_id  = form_parent.find('input[name=master_id]');
            var description  = form_parent.find('input[name=description]');
            var u_m  = form_parent.find('input[name=u_m]');
            var product_type  = form_parent.find('input[name=product_type]');
            var weight_type  = form_parent.find('input[name=weight_type]');
            var price1  = form_parent.find('input[name=price1]');
            var price2  = form_parent.find('input[name=price2]');

            $.ajax({
                url: '../pages/check_product_by_master_id',
                type: "GET",
                dataType: "json",
                async: false,
                data: master_id,
                cache: false,
                success: function(data,form_parent){
                    master_id_input_span = jQuery('#add_product_form').find('.master_id_check_result')
                    master_id_input = jQuery('#add_product_form').find('input[name=master_id]')

                    if(data.length === 0){
                        //console.log('master_id_available');
                        master_id_input_span.removeClass('error');
                        master_id_input.removeClass('error');
                        master_id_input_span.addClass('success');
                        master_id_input.addClass('success');
                        master_id_input_span.html('master id available');
                        error = 0;
                    }
                    else{
                        //console.log('data.id: ' , data[0].id);
                        //console.log('master_id_already_exist');
                        master_id_input_span.removeClass('success');
                        master_id_input.removeClass('success');
                        master_id_input_span.addClass('error');
                        master_id_input.addClass('error');
                        master_id_input_span.html('master id already exist, please use another.');
                        error = 1;
                    }
                },
                error: function(xhr){
                    console.log('failed ajax!');
                }
            });


            if(error == 0){
                console.log('this.submit()');
                this.submit();
            }
    
        });

        if (window.location.href.indexOf('#userstab') > 0 ) {
            jQuery('#v-pills-users-tab').click();
        }

        jQuery('.edit_product').click(function(){

            var get_product_id = jQuery(this).attr('product-id');
            jQuery('#edit_product_modal').modal('show');

            $.ajax({
                url: '../pages/get_product_edit_info',
                type: "GET",
                dataType: "JSON",
                data: get_product_id,
                success: function(data){
 
                    jQuery('#edit_product_modal #product_data_id').html(data.master_id);

                    var product_details_html = ''+
                        '<ul class="profile_ul col-md-12" style="padding:0px 15px !important;">'+
                            '<li>'+
                                '<label>Name</label>'+
                                '<input type="text" name="product_name" value="'+data.name+'" class="form-control">'+
                            '</li>'+
                            '<li>'+
                                '<label>Master ID</label>'+
                                '<input type="text" name="master_id" value="'+data.master_id+'" class="form-control" readonly>'+
                            '</li>'+
                            '<li>'+
                                '<label>Description</label>'+
                                '<input type="text" name="description" value="'+data.description+'" class="form-control" required>'+
                            '</li>'+
                            '<li class="um_type_list_item"></li>'+
                            '<li class="product_type_list_item"></li>'+
                            '<li class="weight_type_list_item"></li>'+
                            '<li>'+
                                '<label>Price (Level 1)</label>'+
                                '<input type="text" name="price1" value="' + data.price1 + '" class="form-control" required>'+
                            '</li>'+
                            '<li>'+
                                '<label>Price (Level 2)</label>'+
                                '<input type="text" name="price2" value="' + data.price2 + '" class="form-control" required>'+
                            '</li>';

                            var array_1 = data.price_level;
                            //console.log(data.price_level);
                            
                            array_1.forEach(function(product_price_level, i) {
                            if(product_price_level['id'] != '1' && product_price_level['id'] != '2'){
                                product_details_html = product_details_html.concat(''+
                                '<li data=' + product_price_level['id'] + '>'+
                                    '<label>Price (Level '+ product_price_level['name'] +')</label>'+
                                    '<input type="text" name="price'+ product_price_level['name'] +'" value="' + product_price_level['price'] + '" class="form-control" required>'+
                                '</li>');
                            }
                            
                        });

                        product_details_html = product_details_html.concat('</ul>');
                  
                    jQuery('#edit_product_modal .modal-body').html(product_details_html);

                    var get_um_type = jQuery('#add_product_modal select[name=u_m]').prop('outerHTML');
                    var um_type_html = '<label>U/M Type</label>' + get_um_type;
                    //console.log(data.um_id);
                    jQuery('.um_type_list_item').append(um_type_html);
                    jQuery('.um_type_list_item select option[value="'+data.um_id+'"]').prop('selected', true)

                    var get_product_type = jQuery('#add_product_modal select[name=product_type]').prop('outerHTML');
                    var product_type_html = '<label>Product Type</label>' + get_product_type;
                    //console.log(product_type_html);
                    jQuery('.product_type_list_item').append(product_type_html);
                    jQuery('.product_type_list_item select option[value="'+data.product_type+'"]').prop('selected', true)

                    var get_weight_type = jQuery('#add_product_modal select[name=weight_type]').prop('outerHTML');
                    var weight_type_html = '<label>Weight Type</label>' + get_weight_type;
                    //console.log(weight_type_html);
                    jQuery('.weight_type_list_item').append(weight_type_html);
                    jQuery('.weight_type_list_item select option[value="'+data.weight_type+'"]').prop('selected', true)


                    jQuery('#edit_product_modal').modal('show'); 

                    //console.log(data);
                },
                error: function(xhr)
                {
                    console.log('failed ajax!');
                }
            });
        });

        jQuery('.delete_product').click(function(){
            var get_product_id = jQuery(this).attr('product-id');
            jQuery('#delete_product_modal').modal('show');
            jQuery('#delete_product_modal #product_data_id').html(get_product_id);
            jQuery('#delete_product_modal input#delete_product_id').attr('value' , get_product_id);
        });
    }


    if(jQuery('.edit_user').length > 0) {

        jQuery('.edit_user').click(function(){

            var get_user_id = jQuery(this).attr('user-id');
            jQuery('#update_user_modal').modal('show');

            $.ajax({
                url: '../admin/get_user_edit_info',
                type: "GET",
                dataType: "JSON",
                data: get_user_id,
                success: function(data){

                    jQuery('#update_user_modal #user_first_name').html(data.first_name +' '+ data.last_name);

                    var user_details_html = ''+
                        '<ul class="profile_ul col-md-12" style="padding:0px 15px !important;">'+
                            '<li>'+
                                '<label>First Name</label>'+
                                '<input type="text" name="first_name" value="'+data.first_name+'" class="form-control">'+
                                '<input type="hidden" name="user_id" value="'+data.id+'">'+
                            '</li>'+
                            '<li>'+
                                '<label>last Name</label>'+
                                '<input type="text" name="last_name" value="'+data.last_name+'" class="form-control">'+
                            '</li>'+
                            '<li>'+
                                '<label>Email</label>'+
                                '<input type="text" name="email" value="'+data.email+'" class="form-control" required readonly>'+
                            '</li>'+
                            '<li>'+
                                '<label>Phone</label>'+
                                '<input type="text" name="phone" value="' + data.phone + '" class="form-control" required>'+
                            '</li>'+
                            '<li>'+
                                '<label>Password</label>'+
                                '<input type="password" name="password" value="" class="form-control">'+
                                '<small>Type new password to change old password or leave it blank</small>'+
                            '</li>'+
                            '<li>'+
                                '<label>Confirm Password</label>'+
                                '<input type="password" name="confirm_password" value="" class="form-control">'+
                                '<small>Confirm new password or leave it blank</small>'+
                            '</li>'+
                            '<li>'+
                                '<label>Company</label>'+
                                '<input type="text" name="company" value="' + data.company + '" class="form-control" required>'+
                            '</li>'+
                            '<li>'+
                                '<label>Address</label>'+
                                '<input type="text" name="address" value="' + data.address + '" class="form-control" required>'+
                            '</li>'+
                            '<li>'+
                                '<label>Postcode</label>'+
                                '<input type="text" name="postcode" value="' + data.postcode + '" class="form-control" required>'+
                            '</li>'+
                            '<li>'+
                                '<label>City</label>'+
                                '<input type="text" name="city" value="' + data.city + '" class="form-control" required>'+
                            '</li>'+
                            '<li>'+
                                '<label>Country</label>'+
                                '<input type="text" name="country" value="' + data.country + '" class="form-control" required>'+
                            '</li>'+

                            '<li class="edit_level_list"></li>'+
                        '</ul>';


                
                    jQuery('#update_user_modal .modal-body').html(user_details_html);

                    var get_level_list = jQuery('#add_user_modal .level_list').prop('innerHTML');
                    console.log(data.level);
                    jQuery('.edit_level_list').append(get_level_list);
                    jQuery('.edit_level_list select option[value="'+data.level+'"]').prop('selected', true)

                    jQuery('#edit_product_modal').modal('show'); 

                    //console.log(data);
                },
                error: function(xhr)
                {
                    console.log('failed ajax!');
                }
            });
        });

        jQuery('.delete_user').click(function(){
            var get_user_id = jQuery(this).attr('user-id');
            jQuery('#delete_user_modal').modal('show');
            jQuery('#delete_user_modal #user_data_id').html(get_user_id);
            jQuery('#delete_user_modal input#delete_user_id').attr('value' , get_user_id);
        });

    }


    if(jQuery('#update_bulk_price').length > 0) {
        //console.log('update_bulk_price found');

        jQuery('#bulk_price_check_submit').click(function(event){
            event.preventDefault();
            var checkparent = jQuery('#bulk_price_check_list');
            var form_parent = jQuery('#update_bulk_price');
            jQuery(form_parent).addClass('loading');

            //console.log('bulk_price_check_list checking');

            var ajaxurl = form_parent.attr('action');
            var field_data = new FormData();
            var files = jQuery('#file')[0].files;
            field_data.append('file',files[0]);

            $.ajax({
                url: ajaxurl,
                type: "POST",
                dataType: "json",
                data: field_data,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data){
                    //console.log(data.htmldata);
                    jQuery('#bulk_price_check_list').html(data.htmldata);
                    jQuery(form_parent).removeClass('loading');

                    form_parent.append(''+
                    '<div id="saveconfirm_div">'+
                    '<input type="checkbox" name="saveconfirm" value="1" id="saveconfirm" required> Confirm Save'+
                    '</div>'+
                    '<div id="bulk_price_save_submit_div">'+
                    '<button class="btn btn-primary" id="bulk_price_save_submit">Save prices to database</button>'+
                    '</div>');

                    bulk_price_save_submit();

                },
                error: function(data, xhr){
                    //console.log(data);
                    jQuery('#bulk_price_check_list').html(data.responseText);
                    jQuery(form_parent).removeClass('loading');
                }
            });
        });
    }

    if(jQuery('#product_sheet_update_submit').length > 0) {
        //console.log('update_bulk_price found');

        jQuery('#product_sheet_update_submit').click(function(event){
            event.preventDefault();
            var checkparent = jQuery('#product_sheet_update_list');
            var form_parent = jQuery('#update_bulk_price');
            jQuery(form_parent).addClass('loading');

            //console.log('bulk_price_check_list checking');

            var ajaxurl = form_parent.attr('action');
            var field_data = new FormData();
            var files = jQuery('#file')[0].files;
            field_data.append('file',files[0]);

            $.ajax({
                url: ajaxurl,
                type: "POST",
                dataType: "json",
                data: field_data,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data){
                    //console.log(data.htmldata);
                    jQuery('#product_sheet_update_list').html(data.htmldata);
                    jQuery(form_parent).removeClass('loading');

                    bulk_price_save_submit();

                },
                error: function(data, xhr){
                    //console.log(data);
                    jQuery('#product_sheet_update_list').html(data.responseText);
                    jQuery(form_parent).removeClass('loading');
                }
            });
        });
    }

    function bulk_price_save_submit(){

        jQuery('#bulk_price_save_submit').click(function(event){
            event.preventDefault();
            var form_parent = jQuery('#update_bulk_price');
            var saveconfirm_div = jQuery('#saveconfirm_div');
            var bulk_price_save_submit_div = jQuery('#bulk_price_save_submit_div');

            var ajaxurl = form_parent.attr('action');

            var field_data = new FormData();
            var files = jQuery('#file')[0].files;
            field_data.append('file',files[0]);
            field_data.append('saveconfirm', jQuery('#saveconfirm').val());

            //console.log('#saveconfirm: '+ jQuery('#saveconfirm').val());
            //console.log('field_data: ', field_data);

            if(jQuery('#saveconfirm').is(":checked")){
                console.log('saveconfirm is checked');
                jQuery(form_parent).addClass('loading');
    
                 $.ajax({
                    url: ajaxurl,
                    type: "POST",
                    dataType: "json",
                    data: field_data,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(data){
                        console.log('success save to database');
                        jQuery('#bulk_price_check_list').html('<p>Data saved to database successfully</p>');
                        jQuery(saveconfirm_div).css('display','none');
                        jQuery(bulk_price_save_submit_div).css('display','none');        
                        jQuery(form_parent).removeClass('loading');
                    },
                    error: function(data, xhr){
                        //console.log(data);
                        jQuery('#bulk_price_check_list').html(data.responseText);
                        jQuery(form_parent).removeClass('loading');
                    }
                });
            }
            else{
                alert('check save confirm to save to database!');
            }

        });
    }

    if(jQuery('#product_management_table').length > 0) {

        jQuery('#bulk_add_to_cart').click(function(event){
            event.preventDefault();

            var form_parent = jQuery('#bulk_add_to_cart_form');
            var field_data = form_parent.serializeArray();
            var cart_data = new Array();

            count = 0;
            //console.log('serializeArray: ' , field_data);

            field_data.forEach(element => {
                //console.log('element.description: ' + element.description );
                if(element.value){
                    var masterid = element.name;
                    var qty = element.value;
                    count = count + 1;
                    //console.log('masterid: ' + masterid + ' qty: '+ qty);
                    new_cart_data = {
                        'item' : count,
                        'masterid' : masterid,
                        'qty' : qty,
                    };
                    cart_data.push(new_cart_data);
                }
            });

            //console.log('cart_data empty: ' + cart_data.length);

            if(cart_data.length != 0){
                $.ajax({
                    url: 'pages/bulk_add_to_cart',
                    type: "POST",
                    data: {'data': cart_data},
                    success: function(data){
                        //alert('success ajax!');
                        //console.log('data: ' , data);
                        JSON.parse(data).forEach(element => {
                            let total = (element.qty * element.price).toFixed(2);
                            this_item = jQuery('#cart_menu table tbody tr[data-master-id='+ element.masterid +']');
                            
                            if(jQuery(this_item).length > 0 && element.qty == 0 || element.qty == ''){
                                //console.log('remove product from cart ');
                                jQuery(this_item).remove();
                            }
                            else if(this_item.length > 0 ){
                                //console.log('update existing product in cart ');
                                jQuery(this_item).children('td:nth-child(2)').html(element.qty);
                                jQuery(this_item).children('td:nth-child(3)').html(element.price);
                                jQuery(this_item).children('td:nth-child(4)').html(total);
                            }
                            else{
                                jQuery('#cart_menu table tbody').append(''+
                                '<tr data-master-id='+ element.masterid +'>'+
                                    '<td>'+ element.description +'</td>'+
                                    '<td>'+ element.qty +'</td>'+
                                    '<td>'+ element.price +'</td>'+
                                    '<td>'+ total +'</td>'+
                                '</tr>'+
                                '');
                            }
                            
                        });
                        calculate_total_cart();
                        jQuery('#cart_menu').addClass('display');
    
                        //jQuery('form.order_form_controller').removeClass('loading');
                    },
                    error: function(reponse, xhr){
                        console.log('reponse: ' , reponse);
                        //alert('failed ajax!');
                    }
                });
            }


        });
    }
});



/* ---------------------------------------------------------------------------- general ready functions 2 */

$(document).ready(function() {
    $('.datatable').DataTable({
        "order": [[ 0, "desc" ]]
    });

    $('.price_level_datatable').DataTable({
        "order": [[ 1, "asc" ]]
    });
    

    $('#print_me').click(function(){
        window.print();
    });

    
    $(window).keydown(function(event){
        if(event.keyCode == 13) {
            event.preventDefault();
            $('#add_item').click();
            return false;
        }
    });

} );


/* ---------------------------------------------------------------------------- importer upload javascript */

$("#importerupload000").on("click", function () {
    //Reference the FileUpload element.
    var fileUpload = $("#fileUpload")[0];

    //Validate whether File is valid Excel file.
    var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.xls|.xlsx)$/;
    if (regex.test(fileUpload.value.toLowerCase())) {
        if (typeof (FileReader) != "undefined") {
            var reader = new FileReader();

            //For Browsers other than IE.
            if (reader.readAsBinaryString) {
                reader.onload = function (e) {
                    ProcessExcel(e.target.result);
                };
                reader.readAsBinaryString(fileUpload.files[0]);
            } else {
                //For IE Browser.
                reader.onload = function (e) {
                    var data = "";
                    var bytes = new Uint8Array(e.target.result);
                    for (var i = 0; i < bytes.byteLength; i++) {
                        data += String.fromCharCode(bytes[i]);
                    }
                    ProcessExcel(data);
                };
                reader.readAsArrayBuffer(fileUpload.files[0]);
            }
        } else {
            alert("This browser does not support HTML5.");
        }
    } else {
        alert("Please upload a valid Excel file.");
    }
});
function ProcessExcel(data) {
    //Read the Excel File data.
    var workbook = XLSX.read(data, {
        type: 'binary'
    });

    //Fetch the name of First Sheet.
    var firstSheet = workbook.SheetNames[0];

    //Read all rows from First Sheet into an JSON array.
    var excelRows = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[firstSheet]);

    //Create a HTML Table element.
    var table = $("<table />");
    table[0].border = "1";

    //Add the header row.
    var row = $(table[0].insertRow(-1));

   //Add the header cells.
    var headerCell = $("<th />");
    headerCell.html("Id");
    row.append(headerCell);

    var headerCell = $("<th />");
    headerCell.html("Name");
    row.append(headerCell);

    var headerCell = $("<th />");
    headerCell.html("weight type");
    row.append(headerCell);

    var headerCell = $("<th />");
    headerCell.html("product type");
    row.append(headerCell);

    var headerCell = $("<th />");
    headerCell.html("DB Status");
    row.append(headerCell);

    var headerCell = $("<th />");
    headerCell.html("Update Status");
    row.append(headerCell);

    console.log(excelRows);

    //Add the data rows from Excel file.
    for (var i = 0; i < excelRows.length; i++) {
        //Add the data row.
        var row = $(table[0].insertRow(-1));
        //Add the data cells.
        item_master_id = excelRows[i].master_id;
        weight_type = excelRows[i].weight_type;
        product_type = excelRows[i].product_type;

        productdata = {
            'item_master_id' : item_master_id,
            'weight_type' : weight_type,
            'product_type' : product_type,
        };
        //console.log('productdata: ' , productdata);
        if(item_master_id){
            var cell = $("<td />");
            var dvExcel = $("#dvExcel");

            datares = '';
            cell.html(item_master_id);
            row.append(cell);
    
            cell = $("<td />");
            cell.html(excelRows[i].name);
            row.append(cell);

            cell = $("<td />");
            cell.html(excelRows[i].weight_type);
            row.append(cell);

            cell = $("<td />");
            cell.html(excelRows[i].product_type);
            row.append(cell);

            cell = $("<td id='dbstatus-"+item_master_id+"' />");
            cell.html('');
            row.append(cell);

            cell = $("<td id='updatestatus-"+item_master_id+"' />");
            cell.html('');
            row.append(cell);

            $.ajax({
                url: 'pages/get_product_by_master_id',
                type: "GET",
                dataType: "json",
                async: true,
                data: productdata,
                cache: false,
                success: function(data){
                    if(data.length == 2){
                        console.log('duplicate-' , data);
                    }
                    else if(data.length == 0){
                        console.log('missing------------' + item_master_id);
                    }

                    jQuery('#dbstatus-' + data[0].master_id ).html(1);
                    jQuery('#updatestatus-' + data[0].master_id ).html(1);

                },
                error: function(xhr){
                    console.log('get_product_by_master_id Failed!');
                    jQuery('#dbstatus-' + data[0].master_id ).html(0);
                    jQuery('#updatestatus-' + data[0].master_id ).html(0);

                }
            });
        }
       
    }
    

    var dvExcel = $("#dvExcel");
    dvExcel.html("");
    dvExcel.append(table);
};

/* ---------------------------------------------------------------------------- Cart javascript */

jQuery('#open_cart_menu').click(function(event){
    event.preventDefault();
    jQuery('#cart_menu').addClass('display');
});

jQuery('#close_cart_menu').click(function(){
    event.preventDefault();
    jQuery('#cart_menu').removeClass('display');
});

jQuery('#clear_cart').click(function(){
    event.preventDefault();
    $.ajax({
        url: 'pages/empty_cart',
        type: "GET",
        success: function(data){
            jQuery('#cart_menu table tbody').html('');
            calculate_total_cart();
            jQuery('#cart_menu').addClass('display');
            location.reload();  
        },
        error: function(reponse, xhr){
            console.log('reponse: ' , reponse);
        }
    });

});
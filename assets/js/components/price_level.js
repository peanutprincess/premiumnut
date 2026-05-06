if(jQuery('#add_price_level').length > 0) {

    jQuery('#add_price_level').click(function(){
        $('#add_price_level_modal').modal('show'); 
    });

    jQuery('.edit_price_level').click(function(){

        var get_price_level_id = jQuery(this).attr('price-level-id');
        jQuery('#edit_price_level_modal').modal('show');

        $.ajax({
            url: base_url + 'admin/get_price_level_edit_info',
            type: "GET",
            dataType: "JSON",
            data: get_price_level_id,
            success: function(data){

                jQuery('#edit_price_level_modal #price_level_data_id').html(data.id);

                var price_level_details_html = ''+
                    '<ul class="profile_ul col-md-12" style="padding:0px 15px !important;">'+
                   
                        '<li>'+
                            '<label>Sort Order</label>'+
                            '<input type="text" name="sort_order" value="'+data.sort_order+'" class="form-control" >'+
                        '</li>'+
                        '<li>'+
                            '<label>Name</label>'+
                            '<input type="text" name="price_level_name" value="'+data.name+'" class="form-control">'+
                            '<input type="hidden" name="id" value="'+data.id+'" class="form-control" >'+
                        '</li>'+
                    '</ul>';

                jQuery('#edit_price_level_modal .modal-body').html(price_level_details_html);

                jQuery('#edit_price_level_modal').modal('show'); 

                //console.log(data);
            },
            error: function(xhr)
            {
                console.log('failed ajax!');
            }
        });
    });

    jQuery('.delete_price_level').click(function(){
        var get_price_level_id = jQuery(this).attr('price-level-id');
        jQuery('#delete_price_level_modal').modal('show');
        jQuery('#delete_price_level_modal #price_level_data_id').html(get_price_level_id);
        jQuery('#delete_price_level_modal input#delete_price_level_id').attr('value' , get_price_level_id);
    });
}
var wcmca_selected_value = {};
var wcmca_address_note_value = {};
var wcmca_address_preview_value = {};
jQuery(document).ready(function()
{
	jQuery(document).on('change', '.wcmca_product_address_select_menu', wcmca_reload_product_address);
	jQuery(document).on('update_checkout', wmca_checkout_updated);
	jQuery(document).on('updated_checkout', wmca_restore_selected);
	jQuery(document).on('keyup', '.wcmca_product_field_note', wcmca_save_note_value);
	wmca_checkout_updated(null);
	wcmca_init_select_menus();
	//wcmca_move_product_shipping_boxes_after_variation();
});
function wcmca_save_note_value(event)
{
	wcmca_address_note_value[jQuery(event.currentTarget).attr('id')] = jQuery(event.currentTarget).val();
}
function wmca_checkout_updated(event)
{
	
	setTimeout(wcmca_init_add_new_addresses_button, 2500);
}
function wcmca_init_select_menus()
{
	//Initial state: selected element must be the first of the list (select box)
	jQuery('.wcmca_product_address_select_menu').each(function(index,elem)
	{
		jQuery(elem).val(jQuery(jQuery(elem).attr('id')+" option:first").val());
	});
	wcmca_update_product_handling_fee_counter(0);
}
function wmca_restore_selected()
{
	jQuery('.wcmca_product_address_select_menu').each(function(index,elem)
	{
		if(wcmca_selected_value[jQuery(elem).attr('id')])
			jQuery(elem).val(wcmca_selected_value[jQuery(elem).attr('id')]);
	});
	
	jQuery('.wcmca_product_address').each(function(index,elem)
	{
		if(wcmca_address_preview_value[jQuery(elem).attr('id')])
			jQuery(elem).html(wcmca_address_preview_value[jQuery(elem).attr('id')]);
	});
	
	jQuery('.wcmca_product_field_note').each(function(index,elem)
	{
		if(wcmca_address_note_value[jQuery(elem).attr('id')])
			jQuery(elem).val(wcmca_address_note_value[jQuery(elem).attr('id')]);
	});
} 
function wcmca_move_product_shipping_boxes_after_variation()
{
	//console.log("move");
	jQuery('.wcmca_product_shipping_box').each(function(index,elem)
	{
		var result = jQuery(elem).parent()/* .find('.variation') */;
		//console.log(jQuery(result).last());
		var last_index =  jQuery(elem).parent().children().size() - 1;
		//console.log(last_index);
		if(result.length != 0 && jQuery(elem).index() != last_index)
		{
			//jQuery(elem).insertAfter(result);
			jQuery(elem).appendTo(result);
		}
	});
}

function wcmca_reload_product_address(event)
{
	var val = jQuery(event.currentTarget).val();
	var ids = val.split("-||-"); //[0] = cart_item_key; [1] = address_id; [2] = address_type;
	var type = ids[2]; 
	var random = Math.floor((Math.random() * 1000000) + 999);
	var formData = new FormData();

	//used to restore selected values once the form is resetted
	wcmca_selected_value[jQuery(event.currentTarget).attr('id')] = val;
	
	if(ids[2] == "same_as_billing")
	{
		//UI	
		wcmca_update_product_handling_fee_counter(-1);
		wcmca_address_preview_value['wcmca_product_address_'+ids[0]] = wcmca.default_address_message;
		jQuery('#wcmca_product_address_'+ids[0]).html(wcmca.default_address_message);
		return false;
	}
	
	formData.append('action', 'wcmca_load_product_address');
	formData.append('cart_item_key', ids[0]);
	formData.append('address_id', ids[1]);
	formData.append('type', type);
	
	//UI
	//jQuery('#wcmca_product_address_loader_'+ids[0]).css('display', 'block');
	jQuery('#wcmca_product_address_'+ids[0]).html(wcmca.product_address_loading);
	
	jQuery.ajax({
		url: wcmca_ajax_url+"?nocache="+random,
		type: 'POST',
		data: formData,
		async: true,
		success: function (data) 
		{
			//UI	
			jQuery('#wcmca_product_address_'+ids[0]).html(data);
			wcmca_address_preview_value['wcmca_product_address_'+ids[0]] = data;
			wcmca_update_product_handling_fee_counter(1);
			//jQuery('#wcmca_product_address_loader_'+ids[0]).css('display', 'none');
						
		},
		error: function (data) 
		{
			//console.log(data);
			//alert("Error: "+data);
		},
		cache: false,
		contentType: false,
		processData: false
	});
}
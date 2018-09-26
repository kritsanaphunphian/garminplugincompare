<?php 
class WCMCA_Order
{
	public function __construct()
	{
	}
	public static function get_id($order)
	{
		if(version_compare( WC_VERSION, '2.7', '<' ))
			return $order->id;
		
		return $order->get_id();
	}
	public function get_vat_meta_field($order_id)
	{
		global $wcev_order_model;
		$billing_vat_number = /* isset($wcev_order_model) ? $wcev_order_model->get_vat_number($order_id) : */ get_post_meta($order_id, 'billing_vat_number',true);
	
		
		$billing_vat_number = $billing_vat_number ? $billing_vat_number : "";
		return $billing_vat_number;
	}
	public function save_vat_field($order_id, $value)
	{
		update_post_meta($order_id,'billing_vat_number', $value);
	}
	public function get_formatted_item_shipping_address($item, $is_html = true)
	{
		global $wcmca_address_model,$wcmca_html_helper ;
		
		$type = 'shipping';
		$value = "";
		$address = array();
		$notes_field = "";
		if(is_object($item) && get_class($item) == 'WC_Order_Item_Meta')
			$meta_data = $item->meta;	
		else 
			$meta_data = version_compare( WC_VERSION, '2.7', '<' ) ? $item["item_meta"]  : $item->get_meta_data();
		
		if(!isset($meta_data))
			return "";
		
		foreach($meta_data as $key => $single_meta)
		{
			if(!is_object($single_meta))
			{
				$type = $key == '_wcmca_type' ? $single_meta[0] : $type;
				if(strpos($key, '_wcmca_shipping_') !== false || strpos($key, '_wcmca_billing_') !== false)
					$address[str_replace('_wcmca_', "", $key)] = $single_meta[0];
				//Note field 
				if($key == '_wcmca_notes')
					$notes_field = $single_meta[0];
			}
			else
			{
				$type = $single_meta->key == '_wcmca_type' ? $single_meta->value : $type;
				if(strpos($single_meta->key, '_wcmca_shipping_') !== false || strpos($single_meta->key, '_wcmca_billing_') !== false)
					$address[str_replace('_wcmca_', "", $single_meta->key)] = $single_meta->value;
				//Note field 
				if($single_meta->key == '_wcmca_notes')
					$notes_field =  $single_meta->value;
			}
		}
		//Note field 
		$address['notes'] = $notes_field;
		
		if(!isset($address[$type.'_country']))
			return "";
		$address_fields = $wcmca_address_model->get_woocommerce_address_fields_by_type($type, $address[$type.'_country']);
		
		return $wcmca_html_helper->get_formatted_order_item_shipping_address($address, $address_fields, $type, $is_html);
	}
}
?>
<?php 
class WCMCA_Html
{
	var $allowed_field_type = array('text','datepicker', 'number', 'multiselect', 'select','checkbox','radio', 'phone', 'tel', 'email','state', 'country');
	public function __construct()
	{ 
		add_action('admin_menu', array(&$this,'init_admin_pages'));
		add_action('wp_ajax_wcmca_get_addresses_html_popup_by_user_id', array(&$this, 'ajax_get_addresses_html_popup_by_user_id'));
		add_action('wp_ajax_wcmca_reload_address_selectors_data', array(&$this, 'ajax_reload_address_selectors_data'));
	}
	function init_admin_pages()
	{
		/* add_options_page( 
			__('Edit page','woocommerce-multiple-customer-addresses'),
			__('Edit page','woocommerce-multiple-customer-addresses'),
			'manage_woocommerce',
			'woocommerce-multiple-customer-addresses-edit-user',
			array(&$this, 'render_admin_user_addresses_edit_page')
		); */
		//add_pages_page('Edit addresses', 'WooCommerce Multiple Customer Adresses', 'manage_woocommerce', 'woocommerce-multiple-customer-addresses-edit-user', array(&$this, 'render_admin_user_addresses_edit_page'));
		//add_management_page('Edit addresses', 'WooCommerce Multiple Customer Adresses', 'manage_woocommerce', 'woocommerce-multiple-customer-addresses-edit-user', array(&$this, 'render_admin_user_addresses_edit_page'));
		
		//Parent slug is null, in this way the page is not showed in admin menu
		add_submenu_page(null, 'Edit addresses', 'WooCommerce Multiple Customer Adresses', 'manage_woocommerce', 'woocommerce-multiple-customer-addresses-edit-user', array(&$this, 'render_admin_user_addresses_edit_page'));
	}
	function curPageURL() 
	{
		 $pageURL = 'http';
		 if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
		 $pageURL .= "://";
		 if ($_SERVER["SERVER_PORT"] != "80") {
		  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
		 } else {
		  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
		 }
		 return $pageURL;
	}
	public function common_js()
	{
		?>
		<script>
				var wcmca_ajax_url = '<?php echo admin_url('admin-ajax.php'); ?>';
				var wcmca_is_wcbcf_active = false <?php //if($wcmca_is_wcbcf_active) echo 'true'; else echo 'false'; ?>;
				var wcmca_current_url = '<?php echo $this->curPageURL(); ?>';
				var wcmca_confirm_delete_message = '<?php _e('Address will be deleted, Are you sure?','woocommerce-multiple-customer-addresses'); ?>';
				var wcmca_confirm_duplicate_message = '<?php _e('Address will be duplicated, are you sure?','woocommerce-multiple-customer-addresses'); ?>';
				var wcmca_state_string = '<?php _e('State','woocommerce-multiple-customer-addresses'); ?>';
				var wcmca_postcode_string = '<?php _e('Postcode / ZIP','woocommerce-multiple-customer-addresses'); ?>';
				var wcmca_city_string = '<?php _e('City','woocommerce-multiple-customer-addresses'); ?>';
			</script>
			<?php 
	}
	public function render_custom_css($page)
	{
		global $wcmca_option_model;
		$css = $wcmca_option_model->get_custom_css_rules();
		if(!isset($css[$page]))
			return;
		?>
		<style type="text/css">
		<?php echo $css[$page]; ?>
		</style>
		<?php 
	}
	//add admin user edit addresses page link button
	public function add_multiple_address_link_to_user_admin_profile_page($user)
	{
		if(!current_user_can('manage_woocommerce'))
			return;
		?>
		<h2><?php _e('Additional addresses','woocommerce-multiple-customer-addresses'); ?></h2>
		<table class="form-table">
			<tbody>
				<tr>
					<th><?php _e('Addresses list','woocommerce-multiple-customer-addresses'); ?></th>
					<td>
						<a class="button button-primary wcmca_primary" target="_blank" href="<?php echo get_admin_url(); ?>admin.php?page=woocommerce-multiple-customer-addresses-edit-user&user_id=<?php echo $user->ID; ?>"><?php _e('View & Edit','woocommerce-multiple-customer-addresses'); ?></a>
					</td>
				</tr>
			</tbody>			
		</table>
		<?php 
	}
	// ------------------ ORDER PAGE ---------------------- //
	//Admin order edit page -> container rendering
	public function render_admin_order_page_additional_addresses_loading_tools()
	{
		global  $wcmca_option_model;
		$which_addresses_to_hide = $wcmca_option_model->which_addresse_type_are_disabled();
		$this->addresses_list_common_scripts();
		wp_dequeue_script('wcmca-additional-addresses');
		wp_dequeue_script('wcmca-additional-addresses-ui');
		
		
		wp_enqueue_script('wcmca-admin-order-edit-ui', WCMCA_PLUGIN_PATH.'/js/admin-order-edit-ui.js?'.time(), array('jquery'));
		wp_enqueue_script('wcmca-admin-order-edit', WCMCA_PLUGIN_PATH.'/js/admin-order-edit.js?'.time(), array('jquery'));
		wp_enqueue_script('jquery-ui-tooltip');
		
		wp_enqueue_style('wcmca-backend-edit-user-addresses', WCMCA_PLUGIN_PATH.'/css/backend-edit-user-addresses.css');
		wp_enqueue_style('wcmca-backend-edit-order-addresses', WCMCA_PLUGIN_PATH.'/css/backend-order-edit.css');
		?>
		<script>
		var wcmca_load_additional_addresses_text_button = "<?php _e( 'Click to load addresses list', 'woocommerce-multiple-customer-addresses' ); ?>";
		var wcmca_loader_html = '<img class="wcmca_preloader_image" src="<?php echo WCMCA_PLUGIN_PATH.'/img/loader.gif' ?>" ></img>';
		var wcmca_hide_billing_addresses_selection = <?php if($which_addresses_to_hide['billing']) echo "true"; else echo "false"; ?>;
		var wcmca_hide_shipping_addresses_selection = <?php if($which_addresses_to_hide['shipping']) echo "true"; else echo "false"; ?>;
		</script>
		<div id="wcmca_additional_addresses_container" class="mfp-hide"></div>
		<?php 
	}
	//Admin order page -> ajax call to retrieve data  
	function ajax_get_addresses_html_popup_by_user_id()
	{
		$user_id = isset($_POST['user_id']) && is_numeric($_POST['user_id']) && $_POST['user_id'] > 0 ? $_POST['user_id'] : $user_id;
		$type = isset($_POST['type']) ? $_POST['type'] : null;
		
		?>
		<a href= "#"  id="wcmca_close_button" class="mfp-close">X</a>
		<?php 
			if(isset($user_id) && isset($type))
				$this->render_addresses_list($user_id, $type, false);
			else 
				echo "<h3>".__('Please select a regisered user.','woocommerce-multiple-customer-addresses')."</h3>";
		 wp_die();
	}
	// ------------------ END ORDER PAGE ---------------------- //
	
	
	//Admin user edit addresses page 
	public function render_admin_user_addresses_edit_page($user_id = null)
	{
		$this->addresses_list_common_scripts();
		wp_enqueue_style('wcmca-backend-edit-user-addresses', WCMCA_PLUGIN_PATH.'/css/backend-edit-user-addresses.css');		
		
		$user_id = isset($_GET['user_id']) && is_numeric($_GET['user_id']) && $_GET['user_id'] > 0 ? $_GET['user_id'] : $user_id;
		
		?>
			<div class="wrap white-box">
			<?php if(isset($user_id)) 
					$this->render_addresses_list($user_id, null, false); ?>
			</div>
		<?php 
	}
	function addresses_list_common_scripts($is_checkout = 'no')
	{
		wp_enqueue_style('wcmca-magnific-popup', WCMCA_PLUGIN_PATH.'/css/vendor/magnific-popup.css'); 
		wp_enqueue_style('wcmca-additional-addresses',WCMCA_PLUGIN_PATH.'/css/frontend-my-account-addresses-list.css');
		wp_enqueue_style('wcmca-frontend-common',WCMCA_PLUGIN_PATH.'/css/frontend-common.css');
			
		if(!is_admin())		
			wp_enqueue_script('wcmca-custom-select2',WCMCA_PLUGIN_PATH.'/js/select2-manager.js', array('jquery', 'select2')); 
		wp_enqueue_script('wcmca-additional-addresses-ui',WCMCA_PLUGIN_PATH.'/js/frontend-address-form-ui.js?'.time(), array('jquery'));  
		wp_register_script('wcmca-additional-addresses',WCMCA_PLUGIN_PATH.'/js/frontend-address-form.js?'.time(), array('jquery')); 
		$additional_js_options = array(
			'is_checkout_page' => $is_checkout,
			'user_id' => get_current_user_id()
		);
		
		wp_localize_script( 'wcmca-additional-addresses', 'wcmca_address_form', $additional_js_options );
		// -- START [CUSTOM CODE BY GARMINBYGIS]
		wp_localize_script(
			'wcmca-additional-addresses-ui',
			'objectL10n',
			array(
				'validation_state'        => __( 'You must select a province.', 'garminbygis' ),
				'validation_internalname' => __( 'Identifier / Name field cannot be blank.', 'garminbygis' ),
				'validation_firstname'    => __( 'First name field cannot be blank.', 'garminbygis' ),
				'validation_lastname'     => __( 'Last name field cannot be blank.', 'garminbygis' ),
				'validation_address1'     => __( 'Street address field cannot be blank.', 'garminbygis' ),
				'validation_address2'     => __( 'Sub District field cannot be blank.', 'garminbygis' ),
				'validation_city'         => __( 'District / City field cannot be blank.', 'garminbygis' ),
				'validation_postcode'     => __( 'Postcode field cannot be blank.', 'garminbygis' ),
				'validation_phone'        => __( 'Phone number field cannot be blank.', 'garminbygis' ),
				'validation_email'        => __( 'Email address field cannot be blank.', 'garminbygis' )
			)
		);
		// -- END [CUSTOM CODE BY GARMINBYGIS]
		wp_enqueue_script( 'wcmca-additional-addresses' );
		
		wp_enqueue_script('wcmca-magnific-popup', WCMCA_PLUGIN_PATH.'/js/vendor/jquery.magnific-popup.js', array('jquery'));
	}
	//Woocommerce My account page (used also for admin order and user profile pages)
	public function render_addresses_list($user_id = null, $type_to_show_in_order_edit_page = null, $include_scripts = true)
	{
		global $wcmca_address_model, $wcmca_customer_model, $wcmca_option_model;
		$is_vat_identification_number_enabled = $wcmca_option_model->is_vat_identification_number_enabled();
		$default_addresses_style = $wcmca_option_model->get_style_options();
		$which_addresses_to_hide = $wcmca_option_model->which_addresse_type_are_disabled();
		$user_id = !isset($user_id) ? get_current_user_id() : $user_id;
		$addresses_by_type = $wcmca_customer_model->get_addresses_by_type($user_id);
		
		$field_managment_options = $wcmca_option_model->get_addresseses_managment_settings();
		$user_can_add_new_billing_addresses = $field_managment_options['max_number_of_billing_addresses'] == 0 ||  count($addresses_by_type['billing']) <= $field_managment_options['max_number_of_billing_addresses'];
		$user_can_add_new_shipping_addresses = $field_managment_options['max_number_of_shipping_addresses'] == 0 ||  count($addresses_by_type['shipping']) <= $field_managment_options['max_number_of_shipping_addresses'];
		
		
		/* if(isset($_GET['wcmca_delete']))
			$wcmca_address_model->delete_address($_GET['wcmca_delete']); */
		
		if($include_scripts)
		{
			$this->addresses_list_common_scripts();	
		}
		?>
		<div id="wcmca_custom_addresses">
			<div class="u-columns woocommerce-Addresses col2-set addresses">
			<?php if(!$which_addresses_to_hide['billing'] && !isset($type_to_show_in_order_edit_page) && !$field_managment_options['disable_user_billing_addresses_editing_capabilities']  ): ?>
				<div class="u-column1 col-1 woocommerce-Address">
					<?php if($user_can_add_new_billing_addresses): ?>
						<a href="#wcmca_address_form_container_billing" class="button wcmca_add_new_address_button" id="wcmca_add_new_address_button_billing"><?php _e('Add new billing address','woocommerce-multiple-customer-addresses'); ?></a>
						<div class="wcmca_loader_container">
							<img class="wcmca_saving_loader_image" src="<?php echo WCMCA_PLUGIN_PATH.'/img/loader.gif' ?>" >
						</div>
					<?php else: 
						echo "<i>".__('Billing addresses limit reached! To add a new one, delete one of the existings!','woocommerce-multiple-customer-addresses')."</i>";
					endif; ?>
				</div>
			<?php endif;
				 if(!$which_addresses_to_hide['shipping'] && !isset($type_to_show_in_order_edit_page) && !$field_managment_options['disable_user_shipping_addresses_editing_capabilities'] ): ?>
				<div class="u-column2 col-2 woocommerce-Address">
					<?php if($user_can_add_new_shipping_addresses): ?>
						<a href="#wcmca_address_form_container_shipping" class="button wcmca_add_new_address_button" id="wcmca_add_new_address_button_shipping"><?php _e('Add new shipping address','woocommerce-multiple-customer-addresses'); ?></a>
						<div class="wcmca_loader_container">
							<img class="wcmca_saving_loader_image" src="<?php echo WCMCA_PLUGIN_PATH.'/img/loader.gif' ?>" >
						</div>
					<?php else:
						echo "<i>".__('Shipping addresses limit reached! To add a new one, delete one of the existings!','woocommerce-multiple-customer-addresses')."</i>";
					endif; ?>
				</div>
			<?php endif; ?>
			</div>
			<?php 
				
				//wcmca_var_dump($addresses_by_type);
				$col_counter = 0;
				foreach($addresses_by_type as $type => $addresses)
				  if(!empty($addresses) && !$which_addresses_to_hide[$type] && (!isset($type_to_show_in_order_edit_page) || $type_to_show_in_order_edit_page == $type))
				  { 
			  
					include(WCMCA_PLUGIN_ABS_PATH.'/templates/my_account.php');
				  }	//end if !empty($addresses)			
				?>
			
			<?php $this->common_js(); ?>
			
			<form id="wcmca_address_form_container_billing" class="mfp-hide">
				<?php $this->render_address_form('billing', $user_id ); ?>
			</form>
			<form id="wcmca_address_form_container_shipping" class="mfp-hide">
				<?php $this->render_address_form('shipping', $user_id ); ?>
			</form>
		</div>
		<?php
	}
	
	//New/Edit address popup HTML
	public function render_address_form($type = 'billing', $user_id=null)
	{
		global $wcmca_address_model, $wcmca_option_model;
		$is_vat_identification_number_enabled = $wcmca_option_model->is_vat_identification_number_enabled();
		$is_identifier_field_disabled = $wcmca_option_model->is_identifier_field_disabled();
		$countries = $wcmca_address_model->get_countries($type);	
		//WCBCF (Brazialian extra fields) support
		$wcbcf_settings = get_option( 'wcbcf_settings' );
		$wcmca_is_wcbcf_active = wcmca_is_wcbcf_active();
		$required_fields = $wcmca_option_model->get_required_fields();
		?>
		
		<div id="wcmca_form_popup_container_<?php echo $type; ?>">
			<a href= "#"  id="wcmca_close_address_form_button_<?php echo $type; ?>" class="mfp-close">X</a>
			<div class="woocommerce">
				<div  id="wcmca_address_form_<?php echo $type; ?>">
					<div id="wcmca_address_form_fieldset_<?php echo $type; ?>">
					<!-- Error messages -->
					<div class="wcmca_error" id="wcmca_required_field_error"><?php _e('Please make sure to have filled all the required fields.','woocommerce-multiple-customer-addresses'); ?></div>
					<div class="wcmca_error" id="wcmca_email_field_error"><?php _e('The entered email has not a valid format.','woocommerce-multiple-customer-addresses'); ?></div>
					<div class="wcmca_error" id="wcmca_postcode_field_error"><?php _e('The entered postcode has not a valid format.','woocommerce-multiple-customer-addresses'); ?></div>
					<div class="wcmca_error" id="wcmca_phone_field_error"><?php _e('The entered phone  has not a valid format.','woocommerce-multiple-customer-addresses'); ?></div>
					<!-- End error messages -->
					<input id="wcmca_address_id_<?php echo $type; ?>" name="wcmca_address_id" type="hidden" value="-1"></input>
					<?php if(isset($user_id)): ?>
						<input type="hidden" name="wcmca_user_id" value="<?php echo $user_id;?>"></input>
					<?php endif; ?>
						<?php 
						$address_fields = $wcmca_address_model->get_woocommerce_address_fields_by_type($type);
						
						//Field name
						if(!$is_identifier_field_disabled && get_current_user_id() > 0) //in case of product shipping address for guest
							woocommerce_form_field('wcmca_address_internal_name', array(
									'type'       => 'text',
									'id' 		 => 'wcmca_address_internal_name',
									'class'      => array( 'form-row-wide' ),
									'required'   => true,
									'input_class' => array('not_empty' ,'wcmca_input_field'),
									'label'      => __('Identifier / Name (Examples: "Office address," "Mary Jones," "MJ 2145," etc.)','woocommerce-multiple-customer-addresses'),
									'label_class' => array( 'wcmca_form_label' ),
									'custom_attributes'    => array('required' => 'required'),
									//'placeholder'    => __('First Name','woocommerce-multiple-customer-addresses')
									)
									);
								
						//Is default checkbox
						$default_address_label = $type == "shipping" ? __('Make this address the default shipping address','woocommerce-multiple-customer-addresses'): __('Make this address the default billing address','woocommerce-multiple-customer-addresses');
						if(get_current_user_id() > 0) //in case of product shipping address for guest
							woocommerce_form_field('wcmca_'.$type.'_is_default_address', array(
									'type'       => 'checkbox',
									'class'      => array( 'form-row-wide' ),
									'required'   => false,
									'label'      => $default_address_label,
									'label_class' => array( 'wcmca_default_checkobx_label' )
									)
									);
								
						$was_prev_field_first_row = false;
						foreach($address_fields as $field_name => $address_field)
						{
							if($field_name == 'billing_state' || $field_name == 'shipping_state' 
								|| (isset($address_field['type']) && !in_array($address_field['type'],$this->allowed_field_type)) || 
								(isset($address_field['enabled']) && !$address_field['enabled']))
								{
									//wcmca_var_dump($address_field['type']);
									continue;
								}
							else if($field_name == 'billing_country' || $field_name == 'shipping_country')
							{
								$was_prev_field_first_row = $was_prev_field_last_row = false;
								?>
								<div class="wcmca_divider"></div>
								<?php 
								woocommerce_form_field('wcmca_'.$type.'_country', array(
									'type'       => 'select',
									'class'      => array( 'form-row-first' ),
									'input_class' => array('wcmca-country-select2', 'not_empty'),
									'required'   => true,
									'label'      => __('Select a country','woocommerce-multiple-customer-addresses'),
									'label_class' => array( 'wcmca_form_label' ),
									//placeholder'    => __('Select a country','woocommerce-multiple-customer-addresses'),
									'options'    => $countries,
									'custom_attributes'  => array('required' => 'required')
									)
								);
								?> 
									<div id="wcmca_country_field_container_<?php echo $type; ?>"></div>
									<img class="wcmca_preloader_image" src="<?php echo WCMCA_PLUGIN_PATH.'/img/loader.gif' ?>" ></img>
									<div class="wcmca_divider"></div>
								<?php 
							}
							else
							{
								
								$is_required = isset($address_field['required']) ? $address_field['required'] : false;
								$custom_attributes = isset($address_field['custom_attributes']) ? $address_field['custom_attributes'] : array();
								$class_to_assign = $address_field['class'];
								
								//row class managment
								if(wcmca_array_element_contains_substring('form-row-first', $address_field['class']) && $was_prev_field_first_row)
								{
									$class_to_assign = array( 'form-row-last' );
									$was_prev_field_last_row = true;
								}
								else
									$was_prev_field_last_row = wcmca_array_element_contains_substring('form-row-last', $address_field['class']) ? true : false;
								
								if(wcmca_array_element_contains_substring('form-row-last', $address_field['class']) && (!$was_prev_field_first_row /* || $was_prev_field_last_row */))
								{
									$class_to_assign = array( 'form-row-wide' );
									$was_prev_field_first_row = false;
								}
								else
									$was_prev_field_first_row = wcmca_array_element_contains_substring('form-row-first', $address_field['class']) ? true : false;
								
								
								
								//requirement managment and class managment
								if($is_required)
									$custom_attributes['required'] = 'required';
								$input_class = isset($address_field['required']) && $address_field['required'] ? array('not_empty' ,'wcmca_input_field') : array('wcmca_input_field');
								$label_class = array( 'wcmca_form_label' );
								
								//field options managment
								$field_options = isset($address_field['options']) ? $address_field['options'] : array(); 
								
								//Support for Checkout Field Editor Pro Advanced
								if(isset($address_field['options_object']))
								{
									$field_options = array();
									foreach($address_field['options_object'] as $object_option)
										$field_options[$object_option['key']] = $object_option['text'];
								}
								//extra field type managment
								if(isset($address_field['type']) && $address_field['type'] == "multiselect")
								{	
									$address_field['type'] = 'select';
									$custom_attributes['multiple'] = 'multiple';
								}
								elseif(isset($address_field['type']) && ($address_field['type'] == "radio" || $address_field['type'] == "checkbox"))
								{
									$custom_attributes['data-default'] = isset($woocommerce_address_field['default'])  ? $woocommerce_address_field['default'] :  0;
									$label_class = array( 'wcmca_form_inline_input_label' );
									$input_class = isset($address_field['required']) && $address_field['required'] ? array('not_empty' ,'wcmca_inline_input_field') : array('wcmca_inline_input_field');
								}
								
								//Forcing/Unforcing required
								if( (($field_name == 'billing_first_name' || $field_name == 'billing_last_name') && $required_fields['billing_first_and_last_name_disable_required']) || 
								    (($field_name == 'shipping_first_name' || $field_name == 'shipping_last_name') && $required_fields['shipping_first_and_last_name_disable_required']) )
									{
										$is_required = false;
										$input_class =  array('wcmca_input_field');
										if(isset($custom_attributes['required']))
											unset($custom_attributes['required']);
									}
								if( ($field_name == 'billing_company'  && $required_fields['billing_company_name_enable_required']) || 
								    ($field_name == 'shipping_company'  && $required_fields['shipping_company_name_enable_required'])  )
									{
										$is_required = true;
										$input_class = array('not_empty' ,'wcmca_input_field');
										$custom_attributes['required'] = 'required';
									}
								 @woocommerce_form_field('wcmca_'.$field_name, array(
										'type'       => isset($address_field['type']) ? $address_field['type'] : 'text',
										'autocomplete' => isset($address_field['autocomplete']) ? $address_field['autocomplete'] : false,
										'class'      => $class_to_assign,//array( 'form-row-first' ),
										'required'   => $is_required,
										'input_class' => $input_class,
										'label'      => isset($address_field['label']) ? $address_field['label'] : "",										
										'description'    => isset($address_field['description']) ? $address_field['description'] : '',
										'label_class' => $label_class,
										'placeholder'    => isset($address_field['placeholder']) ? $address_field['placeholder'] : '',
										'maxlength'    => isset($address_field['maxlength']) ? $address_field['maxlength'] : false,
										'validate'    => isset($address_field['validate']) ? $address_field['validate'] : array(),
										'custom_attributes'    => $custom_attributes,
										'options'    => $field_options,
										),
										$address_field['type'] == 'checkbox' && $address_field['default'] ? true : null /* $address_field['checked'] */
									);
							}
						}
						do_action('wcmca_after_render_address_form', $type); 
						?>
						
						<p class="wcmca_save_address_button_container">
							<button class="button" class="wcmca_save_address_button" id="wcmca_save_address_button_<?php echo $type; ?>"><?php _e('Save','woocommerce-multiple-customer-addresses'); ?></button>
							<img class="wcmca_preloader_image" id="wcmca_validation_loader_<?php echo $type; ?>" src="<?php echo WCMCA_PLUGIN_PATH.'/img/loader.gif' ?>" ></img>
						</p>
					</div>
				 </div>
			</div>
		</div>
		<!--<div id="wcmca_form_background_overlay" ></div>-->
		<?php		
	}
	//Checkout page
	function render_address_form_popup()
	{
		 $this->common_js();
		 if(get_current_user_id() == 0)
			$this->addresses_list_common_scripts('yes');
		 
		 ?>
		<div id="wcmca_custom_addresses" display="height:1px">
		</div>
		<div id="wcmca_address_form_container_billing" class="mfp-hide">
				<?php $this->render_address_form('billing'); ?>
		</div>
		<div id="wcmca_address_form_container_shipping" class="mfp-hide">
			<?php $this->render_address_form('shipping'); ?>
		</div>
		<?php
	}
	
	//Checkout page -> dropdown menus
	function render_address_select_menu($type = 'billing')
	{
		global $wcmca_customer_model, $wcmca_option_model;
		
		wp_enqueue_style('wcmca-magnific-popup', WCMCA_PLUGIN_PATH.'/css/vendor/magnific-popup.css');
		wp_enqueue_style('wcmca-additional-addresses', WCMCA_PLUGIN_PATH.'/css/frontend-checkout.css');
		wp_enqueue_style('wcmca-frontend-common',WCMCA_PLUGIN_PATH.'/css/frontend-common.css');
		
		wp_enqueue_script('wcmca-custom-select2',WCMCA_PLUGIN_PATH.'/js/select2-manager.js', array('jquery', 'select2')); 
		wp_enqueue_script('wcmca-magnific-popup', WCMCA_PLUGIN_PATH.'/js/vendor/jquery.magnific-popup.js', array('jquery'));		
		wp_enqueue_script('wcmca-additional-addresses-ui', WCMCA_PLUGIN_PATH.'/js/frontend-checkout-ui.js?'.time(), array('jquery'));
		wp_enqueue_script('wcmca-additional-addresses', WCMCA_PLUGIN_PATH.'/js/frontend-checkout.js?'.time(), array('jquery'));
		
		wp_enqueue_script('wcmca-address-form-ui',WCMCA_PLUGIN_PATH.'/js/frontend-address-form-ui.js?'.time(), array('jquery'));  
		wp_register_script('wcmca-address-form',WCMCA_PLUGIN_PATH.'/js/frontend-address-form.js?'.time(), array('jquery')); 
		$additional_js_options = array(
			'is_checkout_page' => 'yes',
			'user_id' => get_current_user_id()
		);
		wp_localize_script( 'wcmca-address-form', 'wcmca_address_form', $additional_js_options );
		// -- START [CUSTOM CODE BY GARMINBYGIS]
		wp_localize_script(
			'wcmca-additional-addresses-ui',
			'objectL10n',
			array(
				'validation_state'        => __( 'You must select a province.', 'garminbygis' ),
				'validation_internalname' => __( 'Identifier / Name field cannot be blank.', 'garminbygis' ),
				'validation_firstname'    => __( 'First name field cannot be blank.', 'garminbygis' ),
				'validation_lastname'     => __( 'Last name field cannot be blank.', 'garminbygis' ),
				'validation_address1'     => __( 'Street address field cannot be blank.', 'garminbygis' ),
				'validation_address2'     => __( 'Sub District field cannot be blank.', 'garminbygis' ),
				'validation_city'         => __( 'District / City field cannot be blank.', 'garminbygis' ),
				'validation_postcode'     => __( 'Postcode field cannot be blank.', 'garminbygis' ),
				'validation_phone'        => __( 'Phone number field cannot be blank.', 'garminbygis' ),
				'validation_email'        => __( 'Email address field cannot be blank.', 'garminbygis' )
			)
		);
		// -- END [CUSTOM CODE BY GARMINBYGIS]
		wp_enqueue_script( 'wcmca-address-form' );
		
		$addresses = $wcmca_customer_model->get_addresses(get_current_user_id());
		$addresses_by_type = $wcmca_customer_model->get_addresses_by_type(get_current_user_id());
		$which_addresses_to_hide = $wcmca_option_model->which_addresse_type_are_disabled();
		
		$field_managment_options = $wcmca_option_model->get_addresseses_managment_settings();
		$user_can_add_new_addresses = !$field_managment_options['disable_user_'.$type.'_addresses_editing_capabilities'] && ($field_managment_options['max_number_of_'.$type.'_addresses'] == 0 ||  count($addresses_by_type[$type]) <= $field_managment_options['max_number_of_'.$type.'_addresses']);
	
		if($which_addresses_to_hide[$type])
			return;
		?>
		
		<p class="form-row form-row">
			<label><?php _e('Select an address','woocommerce-multiple-customer-addresses'); ?></label>
			<select class="wcmca_address_select_menu" data-type="<?php echo $type; ?>" id="wcmca_address_select_menu_<?php echo $type; ?>">
				<?php $this->create_options_for_main_address_selector($type, $addresses); ?>
			</select>
			<!-- #wcmca_custom_addresses -->
			<?php if($user_can_add_new_addresses): ?>
			<a href="#wcmca_address_form_container_<?php echo $type; ?>" id="wcmca_add_new_address_button_<?php echo $type; ?>" data-associated-selector="wcmca_address_select_menu_<?php echo $type; ?>" class ="button wcmca_add_new_address_button"><?php _e('Add new address','woocommerce-multiple-customer-addresses'); ?></a>
			<?php endif; ?>
		</p>
		<p>
			<img class="wcmca_loader_image" id="wcmca_loader_image_<?php echo $type; ?>" src="<?php echo WCMCA_PLUGIN_PATH.'/img/loader.gif' ?>" ></img>
		</p>
		<?php 
	}
	public function render_add_product_address_for_guest_user($item_cart_id)
	{
		global  $wcmca_option_model;
		$shipping_per_product_related_options = $wcmca_option_model->shipping_per_product_related_options();
		$default_address_message = __( 'Item will be shipped the billing address (or to the shipping address if you selected to ship to a different address from the billing).', 'woocommerce-multiple-customer-addresses' );
		$type = 'billing'; 
		
		$this->addresses_list_common_scripts('yes');
		wp_register_script('wcmca-product-address-guest', WCMCA_PLUGIN_PATH.'/js/frontend-checkout-product-address-guest.js', array('jquery'));
		$js_options = array(
			'product_address_loading' => __( 'Loading...', 'woocommerce-multiple-customer-addresses' ),
			'default_address_message' => $default_address_message
		);
		wp_localize_script( 'wcmca-product-address-guest', 'wcmca_guest', $js_options );
		wp_enqueue_script( 'wcmca-product-address-guest' );
		wp_enqueue_script('wcmca-product-fee', WCMCA_PLUGIN_PATH.'/js/frontend-checkout-fee.js', array('jquery'));
		
		wp_enqueue_style('wcmca-product-address', WCMCA_PLUGIN_PATH.'/css/frontend-checkout-product-address.css');
		wp_enqueue_style('wcmca-additional-product-addresses', WCMCA_PLUGIN_PATH.'/css/frontend-checkout.css');
		?>
		<a href="#wcmca_address_form_container_billing" class="button wcmca_add_new_product_address_guest_button wcmca_add_new_address_button" data-cart-item-id="<?php echo $item_cart_id; ?>" ><?php _e('Set address','woocommerce-multiple-customer-addresses'); ?></a>
		<a href="#" class="button wcmca_remove_address_button" id="wcmca_remove_address_button_<?php echo $item_cart_id; ?>"  data-cart-item-id="<?php echo $item_cart_id; ?>" ><?php _e('Remove address','woocommerce-multiple-customer-addresses'); ?></a>
		<input type="hidden" name="wcmca_product_address_for_guest_user[<?php echo $item_cart_id; ?>]" id="product_address_for_guest_<?php echo $item_cart_id; ?>" value="same_as_billing"></input>
		
		<img class="wcmca_product_address_loader" id="wcmca_product_address_loader_<?php echo $item_cart_id; ?>" src="<?php echo WCMCA_PLUGIN_PATH.'/img/horizontal-15.gif' ?>"></img>
		<!-- Address preview container -->
		<div class="wcmca_product_address"  data-unique-id="<?php echo $item_cart_id; ?>" id="wcmca_product_address_<?php echo $item_cart_id; ?>"><?php echo $default_address_message; //$this->render_product_address_preview($pre_selected_address_id, $user_id); ?></div>
		<?php if($shipping_per_product_related_options['display_notes_field']): 
					$is_note_field_required = $shipping_per_product_related_options['is_notes_field_required'] ? 'required="required"' : "";
				?>
					<label class="wcmca_product_field_label <?php if($shipping_per_product_related_options['is_notes_field_required']) echo ' wcmca_product_required_field_label '; ?>"><?php _e('Note','woocommerce-multiple-customer-addresses'); ?></label>
					<textarea class="wcmca_product_field_note" id="wcmca_product_fields_note_<?php echo $item_cart_id; ?>" name="wcmca_product_fields[<?php echo $item_cart_id; ?>][notes]" <?php echo $is_note_field_required; ?>></textarea>
		<?php endif; 
	}
	public function render_address_select_menu_for_product($item_cart_id)
	{
		global  $wcmca_customer_model, $wcmca_option_model;
		$addresse_type_disabled = $wcmca_option_model->which_addresse_type_are_disabled();
		$shipping_per_product_related_options = $wcmca_option_model->shipping_per_product_related_options();
		if($addresse_type_disabled['billing'] && $addresse_type_disabled['shipping'])
			return;
		
		$type = 'shipping'; //no longer used
		$user_id = get_current_user_id();
		//$addresses = $wcmca_customer_model->get_addresses($user_id);
		$addresses = $wcmca_customer_model->get_addresses_by_type($user_id);
		$addresses_by_type =  $wcmca_customer_model->get_addresses_by_type($user_id);
		$field_managment_options = $wcmca_option_model->get_addresseses_managment_settings();
		$user_can_add_new_billing_addresses = $field_managment_options['max_number_of_billing_addresses'] == 0 ||  count($addresses_by_type['billing']) <= $field_managment_options['max_number_of_billing_addresses'];
		$user_can_add_new_shipping_addresses = $field_managment_options['max_number_of_shipping_addresses'] == 0 ||  count($addresses_by_type['shipping']) <= $field_managment_options['max_number_of_shipping_addresses'];
		
		$pre_selected_address_id = "";
		$default_address_message = __( 'Item will be shipped the billing address (or to the shipping address if you selected to ship to a different address from the billing).', 'woocommerce-multiple-customer-addresses' );
		wp_register_script('wcmca-product-address', WCMCA_PLUGIN_PATH.'/js/frontend-checkout-product-address.js', array('jquery'));
		$js_options = array(
			'product_address_loading' => __( 'Loading...', 'woocommerce-multiple-customer-addresses' ),
			'default_address_message' => $default_address_message
		);
		wp_localize_script( 'wcmca-product-address', 'wcmca', $js_options );
		wp_enqueue_script( 'wcmca-product-address' );
		wp_enqueue_script('wcmca-product-fee', WCMCA_PLUGIN_PATH.'/js/frontend-checkout-fee.js', array('jquery'));
		
		wp_enqueue_style('wcmca-product-address', WCMCA_PLUGIN_PATH.'/css/frontend-checkout-product-address.css');
		
		?>
			<div class="wcmca_product_shipping_box">
				<span class="wcmca_product_shipping_title"><?php _e('Shipping address','woocommerce-multiple-customer-addresses'); ?></span>			
				<select class="wcmca_product_address_select_menu " data-type="<?php echo $type; ?>" data-unique-id="<?php echo $item_cart_id; ?>" id="wcmca_product_address_select_menu_<?php echo $type."_".$item_cart_id; ?>" name="wcmca_product_address[<?php echo $item_cart_id; ?>]">
					<?php echo $this->create_options_for_item_address_selector($type, $item_cart_id, $addresses, $addresse_type_disabled); ?>
				</select>
				
				<?php if($shipping_per_product_related_options['display_add_billing_address_button'] || $shipping_per_product_related_options['display_add_shipping_address_button'] ): ?>
				<div class="wcmca_add_new_address_buttons_container">
					<?php if($shipping_per_product_related_options['display_add_billing_address_button'] && !$field_managment_options['disable_user_billing_addresses_editing_capabilities'] && $user_can_add_new_billing_addresses): ?>
						<a href="#wcmca_address_form_container_billing" class="button wcmca_add_new_address_button" data-associated-selector="wcmca_product_address_select_menu_<?php echo $type."_".$item_cart_id; ?>" ><?php _e('Add new billing address','woocommerce-multiple-customer-addresses'); ?></a>
					<?php endif; 
					if($shipping_per_product_related_options['display_add_shipping_address_button'] && !$field_managment_options['disable_user_shipping_addresses_editing_capabilities'] && $user_can_add_new_shipping_addresses):?>
						<a href="#wcmca_address_form_container_shipping" class="button wcmca_add_new_address_button" data-associated-selector="wcmca_product_address_select_menu_<?php echo $type."_".$item_cart_id; ?>" ><?php _e('Add new shipping address','woocommerce-multiple-customer-addresses'); ?></a>
					<?php endif; ?>
				</div>
				<?php endif; ?>
				
				<img class="wcmca_product_address_loader" id="wcmca_product_address_loader_<?php echo $item_cart_id; ?>" src="<?php echo WCMCA_PLUGIN_PATH.'/img/horizontal-15.gif' ?>"></img>
				<!-- Address preview container -->
				<div class="wcmca_product_address"  id="wcmca_product_address_<?php echo $item_cart_id; ?>"><?php echo $default_address_message; //$this->render_product_address_preview($pre_selected_address_id, $user_id); ?></div>
				<?php if($shipping_per_product_related_options['display_notes_field']): 
					$is_note_field_required = $shipping_per_product_related_options['is_notes_field_required'] ? 'required="required"' : "";
				?>
					<label class="wcmca_product_field_label <?php if($shipping_per_product_related_options['is_notes_field_required']) echo ' wcmca_product_required_field_label '; ?>"><?php _e('Note','woocommerce-multiple-customer-addresses'); ?></label>
					<textarea class="wcmca_product_field_note" id="wcmca_product_fields_note_<?php echo $item_cart_id; ?>" name="wcmca_product_fields[<?php echo $item_cart_id; ?>][notes]" <?php echo $is_note_field_required; ?>></textarea>
				<?php endif; ?>
			</div>
		<?php 
	}
	//Used also to repopupate main billing/shipping addresses selector via AJAX
	public function create_options_for_main_address_selector($type, $addresses)
	{
		if(empty($addresses)): ?>
					<option value="" selected disabled><?php _e('There are no additional addresses','woocommerce-multiple-customer-addresses'); ?></option>
			<?php else: ?>
					<?php if($type == 'shipping'): ?>
						<option value="last_used_<?php echo $type; ?>"><?php _e('Shipping address used for the previous order','woocommerce-multiple-customer-addresses'); ?></option>
					<?php else: ?>
						<option value="last_used_<?php echo $type; ?>"><?php _e('Billing address used for the previous order','woocommerce-multiple-customer-addresses'); ?></option>
					<?php endif; ?>
			<?php endif;
				
				foreach( $addresses as $address_id => $address)
					if(isset($address['address_internal_name']) && $address['type'] == $type)
					{
						$is_dafault = isset($address[$type."_is_default_address"]) && $address[$type."_is_default_address"] ? " (".__('Default','woocommerce-multiple-customer-addresses').")" : "";
						$is_dafault_class = $is_dafault != "" ? " class='wcmca_default_droppdown_option' " : "";
						$selected = isset($address[$address['type']."_is_default_address"]) ? 'selected="selected"': "";
						echo '<option value="'.$address_id.'" '.$selected.' '.$is_dafault_class .'>'.$address['address_internal_name'].$is_dafault.'</option>';
					}
			
	}
	//Used also to repopupate product addresses selector via AJAX
	public function create_options_for_item_address_selector($type, $item_cart_id, $addresses, $addresse_type_disabled)
	{
													   //checkout_data-||-same_as_billing ?>
		<option value="<?php echo $item_cart_id."-||-";?>checkout_data-||-last_used_shipping"><?php _e('Use the current billing address','woocommerce-multiple-customer-addresses'); ?></option>
				
				<?php $pre_selected_address_id = "last_used_".$type ?>
						<?php if($type == 'shipping'):  ?>
							<!-- <option value="<?php echo $item_cart_id."-||-";?>last_used_<?php echo $type; ?>"><?php _e('Shipping address used for the previous order','woocommerce-multiple-customer-addresses'); ?></option> -->
						<?php else: ?>
							<!-- <option value="<?php echo $item_cart_id."-||-";?>last_used_<?php echo $type; ?>"><?php _e('Billing address used for the previous order','woocommerce-multiple-customer-addresses'); ?></option>-->
						<?php endif; ?>
				<?php 
					if(!empty($addresses['billing']) && !$addresse_type_disabled['billing'])
					{
						echo '<optgroup label="'.__('Billing addresses','woocommerce-multiple-customer-addresses').'">';
						foreach( $addresses['billing'] as $address_id => $address)
						{
							if(isset($address['address_internal_name']) /* && $address['type'] == $type */)
							{
								$is_dafault = /* isset($address[$type."_is_default_address"]) && $address[$type."_is_default_address"] ? " (".__('Default','woocommerce-multiple-customer-addresses').")" : */ "";
								$is_dafault_class = $is_dafault != "" ? " class='wcmca_default_droppdown_option' " : "";
								$selected = /* isset($address[$address['type']."_is_default_address"]) ? 'selected="selected"': */ "";
								//$pre_selected_address_id = isset($address[$address['type']."_is_default_address"]) ? $address_id : $pre_selected_address_id;
								echo '<option  value="'.$item_cart_id."-||-".$address_id.'-||-billing" '.$selected.' '.$is_dafault_class .'>'.$address['address_internal_name'].$is_dafault.'</option>';
							}
						}
						echo '</optgroup>';
					}
					
					if(!empty($addresses['shipping']) && !$addresse_type_disabled['shipping'])
					{
						echo '<optgroup label="'.__('Shipping addresses','woocommerce-multiple-customer-addresses').'">';
						foreach( $addresses['shipping'] as $address_id => $address)
						{
							if(isset($address['address_internal_name']) )
							{
								$is_dafault = "";
								$is_dafault_class = $is_dafault != "" ? " class='wcmca_default_droppdown_option' " : "";
								$selected = "";
								//$pre_selected_address_id = isset($address[$address['type']."_is_default_address"]) ? $address_id : $pre_selected_address_id;
								echo '<option data-address-type="shipping" value="'.$item_cart_id."-||-".$address_id.'-||-shipping" '.$selected.' '.$is_dafault_class .'>'.$address['address_internal_name'].$is_dafault.'</option>';
							}
						}
						echo '</optgroup>';
					}
	}
	public function ajax_reload_address_selectors_data()
	{
		global $wcmca_customer_model, $wcmca_option_model, $woocommerce;
		
		$types = array('billing', 'shipping');
		$selectors_data = array('cart_items' => array());
		$user_id = get_current_user_id();
		$addresse_type_disabled = $wcmca_option_model->which_addresse_type_are_disabled();
		
		if($user_id == 0)
		{
			echo "no";
			wp_die();
		}
		
		$addresses = $wcmca_customer_model->get_addresses_by_type($user_id);
		$main_addresses = $wcmca_customer_model->get_addresses(get_current_user_id());
		
		//main billing/shipping selectors
		foreach($types as $type)
		{
			ob_start();
			$this->create_options_for_main_address_selector($type,$main_addresses);
			$selectors_data[$type] = ob_get_contents(); 
			$selectors_data[$type] = str_replace(array("\t", "\n"), "", $selectors_data[$type]);
			ob_end_clean();
		}
		
		$items = $woocommerce->cart->get_cart();	
		if(!$addresse_type_disabled['billing'] || !$addresse_type_disabled['shipping'])
			foreach($items as $item_key => $item)
			{
				ob_start();
				$this->create_options_for_item_address_selector('billing',  $item_key, $addresses, $addresse_type_disabled);
				$selectors_data['cart_items'][$item_key] = ob_get_contents(); 
				$selectors_data['cart_items'][$item_key] = str_replace(array("\t", "\n"), "", $selectors_data['cart_items'][$item_key]);
				ob_end_clean();
			}
		
		//wcmca_var_dump($selectors_data);
		echo json_encode($selectors_data);
		wp_die();
	}
	public function render_product_address_preview($address_id, $user_id, $type = 'shipping')
	{
		global $wcmca_customer_model, $wcmca_address_model;
		if($address_id == "")
			return;
		$address = $wcmca_customer_model->get_address_by_id($user_id, $address_id);
		if(empty($address))
			return;
		$address_fields = isset($address[$type.'_country']) ? $wcmca_address_model->get_woocommerce_address_fields_by_type($type, $address[$type.'_country']) : array();
	
		include(WCMCA_PLUGIN_ABS_PATH.'/templates/checkout_product_address.php');
	}
	public function render_product_address_preview_for_guest_user($address)
	{
		global $wcmca_address_model;
		$type = 'billing';
		$address_fields = isset($address[$type.'_country']) ? $wcmca_address_model->get_woocommerce_address_fields_by_type($type, $address[$type.'_country']) : array();
		
		include(WCMCA_PLUGIN_ABS_PATH.'/templates/checkout_product_address.php');
	}
	public function get_formatted_order_item_shipping_address($address, $address_fields, $type = 'shipping', $is_html = true)
	{
		global $wcmca_address_model;
		$result = "";
		ob_start();
		include(WCMCA_PLUGIN_ABS_PATH.'/templates/order_table_item.php');
		$result .= !$is_html ? strip_tags(ob_get_clean()) : ob_get_clean();
		$result = trim(preg_replace('/\s+/', ' ', $result));
		return $result;
	}
}
?>
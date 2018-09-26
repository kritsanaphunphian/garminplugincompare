<?php 
if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array(
	'key' => 'group_5757d093217f9',
	'title' => 'WooCommerce Customer Multiple Addresses - General Options & Style',
	'fields' => array(
		array(
			'key' => 'field_5757d1431248e',
			'label' => 'VAT idetification field',
			'name' => 'wcmca_vat_idetification_field',
			'type' => 'true_false',
			'instructions' => 'You can optionally show an extra VAT identification field on billing addrese where your EU clients can enter their VAT Number.',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '100',
				'class' => '',
				'id' => '',
			),
			'message' => 'Enable',
			'default_value' => 0,
			'ui' => 0,
			'ui_on_text' => '',
			'ui_off_text' => '',
		),
		array(
			'key' => 'field_58048bcfa8c03',
			'label' => 'VAT idetification field - enable required',
			'name' => 'wcmca_vat_identification_enable_required',
			'type' => 'select',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => array(
				array(
					array(
						'field' => 'field_5757d1431248e',
						'operator' => '==',
						'value' => '1',
					),
				),
			),
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'multiple' => 0,
			'allow_null' => 0,
			'choices' => array(
				'no' => 'No',
				'yes' => 'Yes',
			),
			'default_value' => array(
			),
			'ui' => 0,
			'ajax' => 0,
			'placeholder' => '',
			'return_format' => 'value',
		),
		array(
			'key' => 'field_57a843beb8547',
			'label' => 'Disable billing multiple addresses',
			'name' => 'wcmca_disable_billing_multiple_addresses',
			'type' => 'true_false',
			'instructions' => 'Multiple addresses selection will be disabled for billing address',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '100',
				'class' => '',
				'id' => '',
			),
			'message' => '',
			'default_value' => 0,
			'ui' => 1,
			'ui_on_text' => '',
			'ui_off_text' => '',
		),
		array(
			'key' => 'field_59e4cb410bd5c',
			'label' => 'Max number of billing addresses',
			'name' => 'wcmca_max_number_of_billing_addresses',
			'type' => 'number',
			'instructions' => 'This is the max number of billing addresses the user can create. Leave 0 for no limits.',
			'required' => 1,
			'conditional_logic' => array(
				array(
					array(
						'field' => 'field_57a843beb8547',
						'operator' => '!=',
						'value' => '1',
					),
				),
			),
			'wrapper' => array(
				'width' => '50',
				'class' => '',
				'id' => '',
			),
			'default_value' => 0,
			'placeholder' => 'Default value: 0',
			'prepend' => '',
			'append' => '',
			'min' => 0,
			'max' => '',
			'step' => 1,
		),
		array(
			'key' => 'field_59e4cc1c0bd5d',
			'label' => 'Disable user add/edit/delete billing addresses capabilities',
			'name' => 'wcmca_disable_user_billing_addresses_editing_capabilities',
			'type' => 'true_false',
			'instructions' => 'Enabling this option, the user will not be able to insert, delete or edit the existing shipping addresses. Only the Admin will be able to do that.',
			'required' => 0,
			'conditional_logic' => array(
				array(
					array(
						'field' => 'field_57a843beb8547',
						'operator' => '!=',
						'value' => '1',
					),
				),
			),
			'wrapper' => array(
				'width' => '50',
				'class' => '',
				'id' => '',
			),
			'message' => '',
			'default_value' => 0,
			'ui' => 1,
			'ui_on_text' => '',
			'ui_off_text' => '',
		),
		array(
			'key' => 'field_57a843edb8548',
			'label' => 'Disable shipping multiple addresses',
			'name' => 'wcmca_disable_shipping_multiple_addresses',
			'type' => 'true_false',
			'instructions' => 'Multiple addresses selection will be disabled for shipping address',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'message' => '',
			'default_value' => 0,
			'ui' => 1,
			'ui_on_text' => '',
			'ui_off_text' => '',
		),
		array(
			'key' => 'field_59e4ca980bd5b',
			'label' => 'Max number of shipping addresses',
			'name' => 'wcmca_max_number_of_shipping_addresses',
			'type' => 'number',
			'instructions' => 'This is the max number of shipping addresses the user can create. Leave 0 for no limits.',
			'required' => 1,
			'conditional_logic' => array(
				array(
					array(
						'field' => 'field_57a843edb8548',
						'operator' => '!=',
						'value' => '1',
					),
				),
			),
			'wrapper' => array(
				'width' => '50',
				'class' => '',
				'id' => '',
			),
			'default_value' => 0,
			'placeholder' => 'Default value: 0',
			'prepend' => '',
			'append' => '',
			'min' => 0,
			'max' => '',
			'step' => 1,
		),
		array(
			'key' => 'field_59e4cc9f0bd5e',
			'label' => 'Disable user add/edit/delete shipping addresses capabilities',
			'name' => 'wcmca_disable_user_shipping_addresses_editing_capabilities',
			'type' => 'true_false',
			'instructions' => 'Enabling this option, the user will not be able to insert, delete or edit the existing shipping addresses. Only the Admin will be able to do that.',
			'required' => 0,
			'conditional_logic' => array(
				array(
					array(
						'field' => 'field_57a843edb8548',
						'operator' => '!=',
						'value' => '1',
					),
				),
			),
			'wrapper' => array(
				'width' => '50',
				'class' => '',
				'id' => '',
			),
			'message' => '',
			'default_value' => 0,
			'ui' => 1,
			'ui_on_text' => '',
			'ui_off_text' => '',
		),
		array(
			'key' => 'field_57e2d9502bac1',
			'label' => 'My account page - Display fields labels',
			'name' => 'wcmca_my_account_page_display_fields_labels',
			'type' => 'select',
			'instructions' => 'By default on My Account page are listed additional addresses displaying fields with labels and content. You can optionally disable the label display for fields.',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '50',
				'class' => '',
				'id' => '',
			),
			'multiple' => 0,
			'allow_null' => 0,
			'choices' => array(
				'no' => 'No',
				'yes' => 'Yes',
			),
			'default_value' => array(
			),
			'ui' => 0,
			'ajax' => 0,
			'placeholder' => '',
			'return_format' => 'value',
		),
		array(
			'key' => 'field_57fc8a9629c14',
			'label' => 'Disable Identifier / Name field',
			'name' => 'wcmca_disable_identifier_field',
			'type' => 'select',
			'instructions' => 'By default the user has to specify, during the field creation, an "Address identifier" that lately is used to recall the address during the checkout process. Disabling this field the address will be identified using user address data. Ex.: "John Smith - 25012 Main Street - New York, NY, US".',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '50',
				'class' => '',
				'id' => '',
			),
			'multiple' => 0,
			'allow_null' => 0,
			'choices' => array(
				'no' => 'No',
				'yes' => 'Yes',
			),
			'default_value' => array(
			),
			'ui' => 0,
			'ajax' => 0,
			'placeholder' => '',
			'return_format' => 'value',
		),
		array(
			'key' => 'field_57e443341073e',
			'label' => 'Billing first and last name - disable required',
			'name' => 'wcmca_billing_first_and_last_name_disable_required',
			'type' => 'select',
			'instructions' => 'By default billing first and last name are required field. You can disable fields to be required (useful for business users)',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '50',
				'class' => '',
				'id' => '',
			),
			'multiple' => 0,
			'allow_null' => 0,
			'choices' => array(
				'no' => 'No',
				'yes' => 'Yes',
			),
			'default_value' => array(
			),
			'ui' => 0,
			'ajax' => 0,
			'placeholder' => '',
			'return_format' => 'value',
		),
		array(
			'key' => 'field_57e443ae1073f',
			'label' => 'Shipping first and last name - disable required',
			'name' => 'wcmca_shipping_first_and_last_name_disable_required',
			'type' => 'select',
			'instructions' => 'By default shipping first and last name are required field. You can disable fields to be required (useful for business users)',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '50',
				'class' => '',
				'id' => '',
			),
			'multiple' => 0,
			'allow_null' => 0,
			'choices' => array(
				'no' => 'No',
				'yes' => 'Yes',
			),
			'default_value' => array(
			),
			'ui' => 0,
			'ajax' => 0,
			'placeholder' => '',
			'return_format' => 'value',
		),
		array(
			'key' => 'field_57e44500ebbbe',
			'label' => 'Billing company name - enabled required',
			'name' => 'wcmca_billing_company_name_enable_required',
			'type' => 'select',
			'instructions' => 'By default billing company name is not a required field. You can disable the field to be required (useful for business users)',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '50',
				'class' => '',
				'id' => '',
			),
			'multiple' => 0,
			'allow_null' => 0,
			'choices' => array(
				'no' => 'No',
				'yes' => 'Yes',
			),
			'default_value' => array(
			),
			'ui' => 0,
			'ajax' => 0,
			'placeholder' => '',
			'return_format' => 'value',
		),
		array(
			'key' => 'field_57e44575ebbbf',
			'label' => 'Shipping company name - enabled required',
			'name' => 'wcmca_shipping_company_name_enable_required',
			'type' => 'select',
			'instructions' => 'By default billing company name is not a required field. You can disable the field to be required (useful for business users)',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '50',
				'class' => '',
				'id' => '',
			),
			'multiple' => 0,
			'allow_null' => 0,
			'choices' => array(
				'no' => 'No',
				'yes' => 'Yes',
			),
			'default_value' => array(
			),
			'ui' => 0,
			'ajax' => 0,
			'placeholder' => '',
			'return_format' => 'value',
		),
		array(
			'key' => 'field_58e3cef50bdd8',
			'label' => 'Add shipping email field to Shipping addresses',
			'name' => 'wcmca_add_shipping_email_field_to_shipping_addresses',
			'type' => 'true_false',
			'instructions' => 'This option adds an email field to the shipping addresses.',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '50',
				'class' => '',
				'id' => '',
			),
			'message' => '',
			'default_value' => 0,
			'ui' => 1,
			'ui_on_text' => '',
			'ui_off_text' => '',
		),
		array(
			'key' => 'field_58e3cf430bdda',
			'label' => 'Is shipping email field required',
			'name' => 'wcmca_is_shipping_email_required',
			'type' => 'true_false',
			'instructions' => 'Make the shipping email required field.',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '50',
				'class' => '',
				'id' => '',
			),
			'message' => '',
			'default_value' => 0,
			'ui' => 1,
			'ui_on_text' => '',
			'ui_off_text' => '',
		),
		array(
			'key' => 'field_58e3cf640bddb',
			'label' => 'Add shipping phone number field to Shipping addresses',
			'name' => 'wcmca_add_shipping_phone_field_to_shipping_addresses',
			'type' => 'true_false',
			'instructions' => 'This option adds a phone field to shipping addresses.',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '50',
				'class' => '',
				'id' => '',
			),
			'message' => '',
			'default_value' => 0,
			'ui' => 1,
			'ui_on_text' => '',
			'ui_off_text' => '',
		),
		array(
			'key' => 'field_58e3cf780bddc',
			'label' => 'Is shipping phone number field required',
			'name' => 'wcmca_is_shipping_phone_required',
			'type' => 'true_false',
			'instructions' => 'Make the shipping phone number required field.',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '50',
				'class' => '',
				'id' => '',
			),
			'message' => '',
			'default_value' => 0,
			'ui' => 1,
			'ui_on_text' => '',
			'ui_off_text' => '',
		),
		array(
			'key' => 'field_582374da35d42',
			'label' => 'My account page - Addresses title tag',
			'name' => 'wcmca_my_account_page_addresses_title_tag',
			'type' => 'select',
			'instructions' => 'Select wich html tag has to be used to render the addresses title. Keep in mind that you can customize title title style using the CSS class <strong>wcmca_address_title</strong>',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'multiple' => 0,
			'allow_null' => 0,
			'choices' => array(
				'h3' => 'h3',
				'h1' => 'h1',
				'h2' => 'h2',
				'h4' => 'h4',
				'h5' => 'h5',
				'h6' => 'h6',
			),
			'default_value' => array(
			),
			'ui' => 0,
			'ajax' => 0,
			'placeholder' => '',
			'return_format' => 'value',
		),
		array(
			'key' => 'field_57f3630e15d0c',
			'label' => 'Default address badge backgroud color',
			'name' => 'wcmca_default_badge_backgroud_color',
			'type' => 'color_picker',
			'instructions' => '',
			'required' => 1,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '50',
				'class' => '',
				'id' => '',
			),
			'default_value' => '#000000',
		),
		array(
			'key' => 'field_57f3637b15d0d',
			'label' => 'Default address badge text color',
			'name' => 'wcmca_default_badge_text_color',
			'type' => 'color_picker',
			'instructions' => '',
			'required' => 1,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '50',
				'class' => '',
				'id' => '',
			),
			'default_value' => '#FFFFFF',
		),
		array(
			'key' => 'field_57e26475087f6',
			'label' => 'Custom CSS rules - My account page',
			'name' => 'wcmca_custom_css_rules_my_account_page',
			'type' => 'textarea',
			'instructions' => 'Define here your custom CSS',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'new_lines' => '',
			'maxlength' => '',
			'placeholder' => '',
			'rows' => '',
		),
		array(
			'key' => 'field_57e26509087f7',
			'label' => 'Custom CSS rules - Checkout page',
			'name' => 'wcmca_custom_css_rules_checkout_page',
			'type' => 'textarea',
			'instructions' => 'Define here your custom CSS',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'new_lines' => '',
			'maxlength' => '',
			'placeholder' => '',
			'rows' => '',
		),
	),
	'location' => array(
		array(
			array(
				'param' => 'options_page',
				'operator' => '==',
				'value' => 'acf-options-multiple-customer-addresses-options',
			),
		),
	),
	'menu_order' => 0,
	'position' => 'normal',
	'style' => 'default',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => '',
	'active' => 1,
	'description' => '',
));

acf_add_local_field_group(array(
	'key' => 'group_58e3ce32ad990',
	'title' => 'WooCommerce Customer Multiple Addresses - Shipping per product options',
	'fields' => array(
		array(
			'key' => 'field_58d0072f70a9f',
			'label' => 'Shipping per product',
			'name' => 'wcmca_shipping_per_product',
			'type' => 'true_false',
			'instructions' => 'If enabled, on Checkout page the customer will be able to associate a shipping address for each item.',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '50',
				'class' => '',
				'id' => '',
			),
			'message' => '',
			'default_value' => 0,
			'ui' => 1,
			'ui_on_text' => '',
			'ui_off_text' => '',
		),
		array(
			'key' => 'field_58dcb80ef844e',
			'label' => 'Add same products distinctly to cart',
			'name' => 'wcmca_add_product_distinctly_to_cart',
			'type' => 'true_false',
			'instructions' => 'Same products will be added to cart as distinct items. Quantity will be adjustable only using the Cart page.',
			'required' => 0,
			'conditional_logic' => array(
				array(
					array(
						'field' => 'field_58d0072f70a9f',
						'operator' => '==',
						'value' => '1',
					),
				),
			),
			'wrapper' => array(
				'width' => '50',
				'class' => '',
				'id' => '',
			),
			'message' => '',
			'default_value' => 0,
			'ui' => 1,
			'ui_on_text' => '',
			'ui_off_text' => '',
		),
		array(
			'key' => 'field_591956f87947d',
			'label' => 'Disable shop page reloading if a product is added to cart',
			'name' => 'wcmca_disable_shop_page_reloading_if_a_product_is_added_to_cart',
			'type' => 'true_false',
			'instructions' => 'By default shop page is reloaded if a product is added to cart. This is to allow user to be able to re-add a product to cart. To disable this behaviour use the following switch.',
			'required' => 0,
			'conditional_logic' => array(
				array(
					array(
						'field' => 'field_58dcb80ef844e',
						'operator' => '==',
						'value' => '1',
					),
					array(
						'field' => 'field_58d0072f70a9f',
						'operator' => '==',
						'value' => '1',
					),
				),
			),
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'message' => '',
			'default_value' => 0,
			'ui' => 1,
			'ui_on_text' => '',
			'ui_off_text' => '',
		),
		array(
			'key' => 'field_58e3cfc40bddd',
			'label' => 'Display Notes field',
			'name' => 'wcmca_display_notes_field',
			'type' => 'true_false',
			'instructions' => 'A note field will be showed for each cart item on checkout page.',
			'required' => 0,
			'conditional_logic' => array(
				array(
					array(
						'field' => 'field_58d0072f70a9f',
						'operator' => '==',
						'value' => '1',
					),
				),
			),
			'wrapper' => array(
				'width' => '100',
				'class' => '',
				'id' => '',
			),
			'message' => '',
			'default_value' => 0,
			'ui' => 1,
			'ui_on_text' => '',
			'ui_off_text' => '',
		),
		array(
			'key' => 'field_58e3d21d0bddf',
			'label' => 'Display "Add billing address" button',
			'name' => 'wcmca_display_add_billing_address_button',
			'type' => 'true_false',
			'instructions' => 'This option adds the "Add billing address" button for each cart item on checkout page.',
			'required' => 0,
			'conditional_logic' => array(
				array(
					array(
						'field' => 'field_58d0072f70a9f',
						'operator' => '==',
						'value' => '1',
					),
				),
			),
			'wrapper' => array(
				'width' => '50',
				'class' => '',
				'id' => '',
			),
			'message' => '',
			'default_value' => 0,
			'ui' => 1,
			'ui_on_text' => '',
			'ui_off_text' => '',
		),
		array(
			'key' => 'field_58e3d2b80bde0',
			'label' => 'Display "Add shipping address" button',
			'name' => 'wcmca_display_add_shipping_address_button',
			'type' => 'true_false',
			'instructions' => 'This option adds the "Add shipping address" button for each cart item on checkout page.',
			'required' => 0,
			'conditional_logic' => array(
				array(
					array(
						'field' => 'field_58d0072f70a9f',
						'operator' => '==',
						'value' => '1',
					),
				),
			),
			'wrapper' => array(
				'width' => '50',
				'class' => '',
				'id' => '',
			),
			'message' => '',
			'default_value' => 0,
			'ui' => 1,
			'ui_on_text' => '',
			'ui_off_text' => '',
		),
		array(
			'key' => 'field_593f9c436412b',
			'label' => 'Handling fee for products shipped to addresses different from checkout shipping address?',
			'name' => 'wcmca_handling_product_shipping_fee',
			'type' => 'true_false',
			'instructions' => 'You can add a <strong>fixed</strong> handling fee for each product shipped to an address different to the checkout shipping address. <strong>NOTE:</strong> Handling fee <strong>DO NOT APPLY</strong> additional shipping cost based on the selected address.',
			'required' => 0,
			'conditional_logic' => array(
				array(
					array(
						'field' => 'field_58d0072f70a9f',
						'operator' => '==',
						'value' => '1',
					),
				),
			),
			'wrapper' => array(
				'width' => '50',
				'class' => '',
				'id' => '',
			),
			'message' => '',
			'default_value' => 0,
			'ui' => 1,
			'ui_on_text' => '',
			'ui_off_text' => '',
		),
		array(
			'key' => 'field_593fa30a64131',
			'label' => 'Is fee taxable?',
			'name' => 'wcmca_fee_taxable',
			'type' => 'true_false',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => array(
				array(
					array(
						'field' => 'field_593f9c436412b',
						'operator' => '==',
						'value' => '1',
					),
					array(
						'field' => 'field_58d0072f70a9f',
						'operator' => '==',
						'value' => '1',
					),
				),
			),
			'wrapper' => array(
				'width' => '50',
				'class' => '',
				'id' => '',
			),
			'message' => '',
			'default_value' => 0,
			'ui' => 1,
			'ui_on_text' => '',
			'ui_off_text' => '',
		),
		array(
			'key' => 'field_593f9d336412c',
			'label' => 'Handling fees',
			'name' => 'wcmca_fee_ranges',
			'type' => 'repeater',
			'instructions' => '<p>You can add fee values to cart according to the number of products that have to be shipped to different addresses. </p>
<p><strong>Example:</strong> if you have from 2 to 4 product to ship to a different address, you can assign a fee of 4$ for each of them. If you have from 5 to 8 products, you can assig a fee of 3$, and so on.</p>',
			'required' => 1,
			'conditional_logic' => array(
				array(
					array(
						'field' => 'field_593f9c436412b',
						'operator' => '==',
						'value' => '1',
					),
					array(
						'field' => 'field_58d0072f70a9f',
						'operator' => '==',
						'value' => '1',
					),
				),
			),
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'collapsed' => '',
			'min' => 0,
			'max' => 0,
			'layout' => 'table',
			'button_label' => 'Add range',
			'sub_fields' => array(
				array(
					'key' => 'field_593f9f8b6412d',
					'label' => 'Min',
					'name' => 'wcmca_min',
					'type' => 'number',
					'instructions' => 'Leave emtpy for or 0 for no limits',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '30',
						'class' => '',
						'id' => '',
					),
					'default_value' => 0,
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'min' => 0,
					'max' => '',
					'step' => 1,
				),
				array(
					'key' => 'field_593f9fcf6412f',
					'label' => 'Max',
					'name' => 'wcmca_max',
					'type' => 'number',
					'instructions' => 'Leave emtpy for or 0 for no limits',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '30',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'min' => 0,
					'max' => '',
					'step' => 1,
				),
				array(
					'key' => 'field_593f9fe464130',
					'label' => 'Fee value',
					'name' => 'wcmca_fee',
					'type' => 'number',
					'instructions' => '',
					'required' => 1,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '30',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'min' => 1,
					'max' => '',
					'step' => '0.001',
				),
			),
		),
	),
	'location' => array(
		array(
			array(
				'param' => 'options_page',
				'operator' => '==',
				'value' => 'acf-options-multiple-customer-addresses-options',
			),
		),
	),
	'menu_order' => 0,
	'position' => 'normal',
	'style' => 'default',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => '',
	'active' => 1,
	'description' => '',
));

endif;
?>
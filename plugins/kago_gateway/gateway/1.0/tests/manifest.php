<?php
$manifest = array(
			
	'id' => (int)1,
	// Transaction mode: 1=live 0=test.
	'mode' => 1,
	// Order id.
	'order_id' => '123432345',
	// Order token.
	'order_token' => '',
	// Order description.
	'description' => '',
	// Session id of order session.
	'session_id' => '',
	// Customer's IP address.
	'customer_ip' => '',
	// Customer's user id.
	'customer_id' => 62,
	// Order currency code.
	'currency' => 'USD',
	// Order subtotal.
	'unit_of_measure' => 'LB',
	// Order subtotal.
	'subtotal' => 0.0,
	// Order tax total.
	'tax_total' => 0.0,
	// Order total.
	'total' => 0.0,
	// Number of store credits applied to the order.
	'store_credits' => 0,
	// Store credit total applied to the order.
	'store_credit' => 0.0,
	// List of applied promotional codes.
	'promotional_codes' => array(),
	// Single promotional code applied to the order.
	'promo_code' => '',
	// Total amount of discount from promotional code(s).
	'promo_discount' => 0.0,
	
	'continue_shopping_url' => JURI::root() . '?option=com_kago&view=checkout&step=completed&order_id=123456',
	'edit_cart_url' => JURI::root() . '?option=com_kago&view=cart',
	'merchant_calculations_url' => JURI::root() . '?option=com_kago&view=ipn&gateway=gcheckout',
	'shipments' 	=> array(		
		
		array(
			'id' => (int)1,
			// Shipping method.
			'carrier' => '',
			// Shipping method.
			'method' => '',
			// Order handling total.
			'handling_total' => 0.0,
			// Order duty total.
			'duty_total' => 0.0,
			// Order duty total.
			'tax_total' => 0.0,
			// Order shipping total.
			'shipping_total' => 0.0,
			//addresses
			'addresses' => array(
				'ship_from' => array(
					'city' => 'New York', 
					'region' => 'NY', 
					'country_code' => 'US', 
					'postal_code' => '10022'
				),
				'billing' =>  array(
					// Billing first name.
					'first_name' => '',
					// Billing middle name.
					'middle_name' => '',
					// Billing last name.
					'last_name' => '',
					// Billing company.
					'company' => '',
					// Billing address.
					'address' => '',
					// Billing address - second line.
					'address2' => '',
					// Billing city.
					'city' => '',
					// Billing region/state.
					'region' => '',
					// Billing postal/zip code.
					'postal_code' => '',
					// Billing country.
					'country' => '',
					// Billing email address.
					'email' => '',
					// Billing phone number.
					'phone' => '',
					// Billing secondary phone number.
					'phone2' => ''
				),

				// Prime the shipping section of the invoice.
				'shipping' =>  array(
					// Shipping first name.
					'first_name' => '',
					// Shipping middle name.
					'middle_name' => '',
					// Shipping last name.
					'last_name' => '',
					// Shipping company.
					'company' => '',
					// Shipping address.
					'address' => '',
					// Shipping address - second line.
					'address2' => '',
					// Shipping city.
					'city' => '',
					// Shipping region/state.
					'region' => '',
					// Shipping postal/zip code.
					'postal_code' => '',
					// Shipping country.
					'country' => '',
					// Shipping email address.
					'email' => '',
					// Shipping phone number.
					'phone' => '',
					// Shipping secondary phone number.
					'phone2' => ''
				),
			),

			'items' => array(
				
				array(
					'id' => 127,
					'name' => 'Magic Book',
					'description' => 'A book about magic, DUH!',
					'price' => 10.99,
					'tax' => true,
					'quantity' => 2,
					'weight' => 0.5,
					'height' => 10,
					'width' => 20,
					
					
					'tangible' => true,
					'subscr' => null,
					
				),
				array(
					'id' => 128,
					'name' => 'Magazine Subscription',
					'description' => 'Subscribe to our magazine, Introductory offer: $5 2week trial then $49.99 per month',
					'price' => 5.00,
					'tax' => true,
					'quantity' => 2,
					'weight' => 0.5,
					'height' => 10,
					'width' => 20,
					'tangible' => true,
					'subscription' => array(
						/*
						'DAILY' => 'DAILY', 
	'WEEKLY' => 'WEEKLY', 
	'SEMI_MONTHLY' => 'SEMI_MONTHLY', 
	'MONTHLY' => 'MONTHLY', 
	'EVERY_TWO_MONTHS' => 'EVERY_TWO_MONTHS', 
	'QUARTERLY' => 'QUARTERLY', 
	'YEARLY' => 'YEARLY'
						*/
						'billing_period' => 'MONTHLY',
						'initial_price' => 5.00,
						'start_date' => '10/12/2011',
						'end_date' => '',
						'shipping' => array(
							'carrier' => '',
							// Shipping method.
							'method' => '',
							// Order handling total.
							'handling_total' => 0.0,
							// Order duty total.
							'duty_total' => 0.0,
							'shipping_total' => 0.0,
						),
						// Order duty total.
						'tax_total' => 0.0,
						'price_total' => 49.99
					)
				)		 
			)			 
		)
	)	
);
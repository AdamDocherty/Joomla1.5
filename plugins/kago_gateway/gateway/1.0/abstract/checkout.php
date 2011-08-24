<?php
/**
 * @package     Kcommerce
 * @subpackage  Abstract
 *
 * @author		Adam Stephen Docherty
 * @copyright   Copyright (C) 2011 'corePHP', LLC. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

/**
 * Payment Gateway Abstract.
 *
 * @package     Kcommerce
 * @subpackage  Abstract
 * @since       1.0
 */
abstract class KGatewayAbstractCheckout extends KGateway implements KGatewayInterfaceCheckout
{				
	public 
	
	/**
	 * @public array Multidimensional Array representation of the Order.
	 * @since  1.0
	 */
	$manifest,
	
	/**
	 * @public string XML representation of the Order.
	 * @since  1.0
	 */
	$manifest_xml,
	
	/** DEPREICIATED DO NOT USE
	 * @public object QueryPath dom representation of the Order XML.
	 * @since  1.0
	 */
	$manifest_dom;
	
	public function set_manifest( $manifest )
	{
		$this->load_resource( 'XmlDomConstruct', '/3rdparty/XmlDomConstruct.php' );
		
		$dom = new XmlDomConstruct( '1.0', 'utf-8' );
		
		$dom->preserveWhiteSpace = false;
		$dom->formatOutput = true;
		$dom->fromMixed( array( 'manifest' => $manifest ) );
		
		$this->manifest = $manifest;
		$this->manifest_xml = $dom->saveXML();
		//$this->manifest_dom = qp( $this->manifest_xml );
		
		return $this;
	}
	
	public function l( $define )
	{		
		return $define;
	}
	
	public function verify_manifest()
	{
		//$this->verify_manifest() or $this->trigger( '_kc_on_error', array( 000001, $this->l( 'MANIFEST_ERROR' ) ) );

		return true;
	}
	
	public function format_xml( $xml ){ 
		
		$dom = new DOMDocument( '1.0' );
		
		$dom->preserveWhiteSpace = false;
		$dom->formatOutput = true;
		$dom->loadXML( $xml );

		return $dom->saveXML();
	}
	
	public function post( $path = false, $postfields=array(), $header=false )
	{
		$uri = $this->uri;
		
		if( $this->testmode ) $uri = $this->test_uri;

		if( $path ) $uri = $uri . $path;
		
		$c = curl_init( $uri );

		curl_setopt( $c, CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt( $c, CURLOPT_POST, true);
		curl_setopt( $c, CURLOPT_RETURNTRANSFER, true );
		
		if( !empty( $header ) ) 
			curl_setopt( $c, CURLOPT_HTTPHEADER, $header );
		
		if( $postfields ) 
			curl_setopt( $c, CURLOPT_POSTFIELDS, $postfields );
		
		$this->last_response = curl_exec( $c );
	
		return $this;	
	}
}

function bp($mixed=false){die('<pre>'.print_r($mixed,true).'</pre>');}
function p($mixed=false){echo'<pre>'.print_r($mixed,true).'</pre>';}


/*

		
		$manifest = array(
			
			'id' => (int)1,
			// Transaction mode: 1=live 0=test.
			'mode' => 1,
			// Order id.
			'order_id' => '',
			// Order token.
			'order_token' => '',
			// Order description.
			'description' => '',
			// Session id of order session.
			'session_id' => '',
			// Customer's IP address.
			'customer_ip' => '',
			// Customer's user id.
			'customer_id' => 0,
			// Order currency code.
			'currency' => 'USD',
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
							'desc' => 'A book about magic, DUH!',
							'price' => 10.99,
							'tax' => true,
							'qty' => 2,
							'attributes' => array(
								
								'weight' => array(
									'title' => 'Weight',
									'value' => 1.5,
								),
								
								'height' => array(
									'title' => 'height',
									'value' => 3,
								),
								'height' => array(
									'title' => 'height',
									'value' => 3,
								),
								'height' => 10,
								'width' => 20,
							),
							
							
							'tangible' => true,
							'subscr' => null,
							
						),
						array(
							'id' => 127,
							'name' => 'Magazine Subscription',
							'desc' => 'Subscribe to our magazine, Introductory offer: $5 2week trial then $49.99 per month',
							'price' => 5.00,
							'tax' => true,
							'qty' => 2,
							'weight' => 0.5,
							'height' => 10,
							'width' => 20,
							'tangible' => true,
							'subscr' => array(
								/*
								H = hourly
								D = daily	
								W = weekly
								BM = bimonthly
								M = monthly
								Y = yearly
								/
								'billing_period' => 'M',
								'initial_price' => '$5.00',
								'start_date' => '',
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
						),				 
					)			 
				),
				
				array(
					'id' => (int)2,
					'items' => array(
						'id' => 12345,
						'items'
						 
					)			 
				)
			)
			
		);	
		
		//Load plugin that houses KCommerce, the kc_initialise
		//event can be found here
		JPluginHelper::importPlugin( 'kago_gateway', 'kcommerce' );
		
		//trigger the initialise event, we use array_pop
		//because the trigger method returns an array
		//and since we are only expecting one response
		//all's cool
		$KGateway = array_pop( 
			//message the initialise event also passing the selected pgate
			JDispatcher::getInstance()->trigger( 'kc_initialise', array( 'gcheckout' ) ) 
		)
		//note: object chaining ahead!
		//here we add an object to implement the KCommerce Observer layer
		->add_observer( Kago::get_instance( 'gateway_observer' ) )
		//now we set the checkout manifest
		->set_manifest( $manifest )
		//and finally we checkout!
		->checkout();
*/
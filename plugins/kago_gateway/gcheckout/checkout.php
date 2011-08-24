<?php
/**
 * @package     Joomla
 * @subpackage  Plugin
 *
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

class KGcheckoutCheckout extends KGatewayAbstractCheckout
{
	public 	
	$test_uri = 'https://sandbox.google.com/checkout/',	
	$uri = 'https://checkout.google.com/',	
	$merchant_id = '1234567890',	
	$merchant_key = 'HsYXFoZfHAqyLcCRYeH8qQ',
	$testmode = true,
	
	$request,
	$response,
	$redirect_url,
	$element;
	
	public function checkout()
	{	
		$this->trigger( 'on_error', array(  '111', 'oops' ) );
		
		$this->get_redirect_url();
		
		echo $this->redirect_url;
		
		return $this;
		//header('Location: '. $this->redirect_url );
	}
	
	public function get_redirect_url()
	{
		$this->parse_manifest();		
	 
		$this->post( 
			'api/checkout/v2/merchantCheckout/Merchant/' . $this->merchant_id,
			$this->format_xml( $this->request->asXML() ),
			array(
				'Authorization: Basic ' . base64_encode( $this->merchant_id . ':' . $this->merchant_key ),
				'Content-Type: application/xml; charset=UTF-8',
				'Accept: application/xml; charset=UTF-8'
			)
		);
		
		$this->load_resource( 'qp', '/3rdparty/QueryPath/QueryPath.php' );
		
		$response = qp( $this->last_response );
		
		if( $response->find('error-message')->text() )
			$this->trigger( 'kc_error', 
				array( 0, $this->l( $response->find('error-message')->text() ) ) );
			
		$this->redirect_url = $response->top()->find('redirect-url')->text();
		
		return $this;
	}
	
	public function parse_manifest()
	{	
		$this->manifest = (object)$this->manifest;		
		$this->shipment = (object)$this->manifest->shipments[0];
		
		$this->set_checkout_xml();
		$this->set_shopping_cart();
		$this->set_items();
		$this->set_merchant_private_data();
		$this->set_checkout_flow_support();
		
		return $this;
	}
	
	private function set_checkout_xml()
	{
		$this->request = new SimpleXMLElement( '<?xml version="1.0" encoding="UTF-8"?>
		<checkout-shopping-cart xmlns="http://checkout.google.com/schema/2">
		</checkout-shopping-cart>' );
	}
	
	private function set_shopping_cart()
	{	
		$this->element->shopping_cart = $this->request->addChild( 'shopping-cart' );		
	}
	
	private function set_items()
	{	
		$this->element->items = $this->element->shopping_cart->addChild( 'items' );		
		
		foreach( $this->shipment->items as $item ){
			$this->add_item( $item );
		}
	}
	
	private function set_item_subscription( SimpleXMLElement $item_element, stdClass $item )
	{		
		$subscr = (object)$item->subscription;		
		$subscr_element = $item_element->addChild( 'subscription' );
		
		$subscr_element->addAttribute( 'type', 'google' );
		$subscr_element->addAttribute( 'period', $this->format_billing_period( $subscr->billing_period ) );
		$subscr_element->addAttribute( 'start-date', $this->format_date( $subscr->start_date ) );
		
		$payments = $subscr_element->addChild('payments');
		$subscription_payment = $payments->addChild('subscription-payment');
		$maximum_charge = $subscription_payment->addChild( 'maximum-charge', $this->format_currency( $item->price ) );
		$maximum_charge->addAttribute('currency', 'USD');
		
		$recurrent_item = $subscr_element->addChild('recurrent-item');				
		$merchant_private_item_data = $recurrent_item->addChild('merchant-private-item-data');
		
		$merchant_private_item_data->addChild( 'item_id', $item->id );

		if( !empty( $this->manifest->order_id ) ) 
			$merchant_private_item_data->addChild( 'order_id', $this->manifest->order_id );
		
		if( !empty( $this->manifest->customer_id ) ) 
			$merchant_private_item_data->addChild( 'customer_id', $this->manifest->customer_id );
				
		$recurrent_item->addChild( 'item-name', htmlspecialchars( $item->name ) );
		$recurrent_item->addChild( 'item-description', htmlspecialchars( $item->description ) );
		$recurrent_item->addChild( 'quantity', 1);	
		$recurrent_item->addChild( 'unit-price', '0' )
									->addAttribute( 'currency', 'USD');
									
		if( $subscr->initial_price ){		
			$add_item = $item;
			
			$add_item->id = 'ip-' . $item->id;
			$add_item->name = $this->l( 'INITIAL_PAYMENT' ) . ': ' . htmlspecialchars( $item->name );
			$add_item->price = $subscr->initial_price;
			
			unset( $add_item->subscription );
			
			$this->add_item( $add_item );
		}
		
	}
	
	private function set_merchant_private_data()
	{	
		$this->element->merchant_private_data = $this->element->shopping_cart->addChild( 'merchant-private-data' );	
		
		$merchant_private_data = $this->element->merchant_private_data;
		
		if( !empty( $this->manifest->order_id ) ) 
			$merchant_private_data->addChild( 'order_id', $this->manifest->order_id );
		
		if( !empty( $this->manifest->customer_id ) ) 
			$merchant_private_data->addChild( 'customer_id', $this->manifest->customer_id );
	}
	
	private function set_checkout_flow_support()
	{		
		$this->element->checkout_flow_support = $this->request->addChild( 'checkout-flow-support' );
			
			$this->element->merchant_checkout_flow_support = $this->element->checkout_flow_support->addChild( 'merchant-checkout-flow-support' );
				
				$mcfs = $this->element->merchant_checkout_flow_support;
				
				$this->element->continue_shopping_url = 
					$mcfs->addChild( 'continue-shopping-url', htmlspecialchars( $this->manifest->continue_shopping_url ) );
				
				$this->element->edit_cart_url = 
					$mcfs->addChild( 'edit-cart-url', htmlspecialchars( $this->manifest->edit_cart_url ) );
				
				$this->element->merchant_calculations = $mcfs->addChild( 'merchant-calculations' );
				
				$this->element->merchant_calculations
					->addChild( 'merchant-calculations-url', htmlspecialchars( $this->manifest->merchant_calculations_url ) );
				$this->element->merchant_calculations
					->addChild( 'accept-gift-certificates', 'false' );
				$this->element->merchant_calculations
					->addChild( 'accept-merchant-coupons', 'false' );
					
				$this->set_tax_tables();
				$this->set_shipping_methods();			
	}
	
	private function set_tax_tables()
	{
		$this->element->tax_tables = $this->element->merchant_checkout_flow_support->addChild( 'tax-tables' );
		$this->element->tax_tables->addAttribute( 'merchant-calculated', true );
		
		$this->element->default_tax_rules	
			= $this->element->tax_tables		
					->addChild( 'default-tax-table' )
						->addChild( 'tax-rules' );
		
		$this->add_default_tax_rule( 'MI', '0.1', 'false', 'us-state-area' );
	}
	
	function set_shipping_methods()
	{		
		$this->element->shipping_methods 
			= $this->element->merchant_checkout_flow_support->addChild( 'shipping-methods' );
		
		$this->element->carrier_calculated_shipping
			= $this->element->shipping_methods->addChild( 'carrier-calculated-shipping' );
		
		//The <shipping-packages> tag encapsulates information about all of the packages that will 
		//be shipped to the buyer. At this time, merchants may only specify one package per order.	
		$this->element->shipping_packages
			= $this->element->carrier_calculated_shipping->addChild( 'shipping-packages' );
		
		$ship_from = (object)$this->shipment->addresses['ship_from'];
		
		//only one per order
		$this->add_shipping_package( 
			$ship_from->city, $ship_from->region, $ship_from->country_code, $ship_from->postal_code 
		);
				
		$this->element->carrier_calculated_shipping_options
			= $this->element->carrier_calculated_shipping->addChild( 'carrier-calculated-shipping-options' );
			
		$this->add_carrier_calculated_shipping_option(
			10, 'USD', 'FedEx', 'REGULAR_PICKUP', 'Priority Overnight'
		);
	}	
	
	private function add_shipping_package( $city, $region, $country_code, $postal_code )
	{
		$package = $this->element->shipping_packages->addChild( 'shipping-package' );
		
		$ship_from = $package->addChild( 'ship-from' );
		$ship_from->addAttribute( 'id', 'Shipping Package' );
		
		$ship_from->addChild( 'city', $city );
		$ship_from->addChild( 'region', $region );
		$ship_from->addChild( 'country-code', $country_code );
		$ship_from->addChild( 'postal-code', $postal_code );
		
		return $this;
	}
	
	function add_carrier_calculated_shipping_option( $price, $currency, $shipping_company, $carrier_pickup, $shipping_type )
	{		
		$option 
			= $this->element->carrier_calculated_shipping_options->addChild( 'carrier-calculated-shipping-option' );
			
		$option->addChild( 'price', $price )->addAttribute( 'currency', $currency );
		$option->addChild( 'shipping-company', $shipping_company );
		$option->addChild( 'carrier-pickup', $carrier_pickup );
		$option->addChild( 'shipping-type', $shipping_type );
	}
	
	public function add_default_tax_rule( $state, $rate, $shipping_taxed=false, $tax_area='us-state-area' )
	{
		$rule = $this->element->default_tax_rules->addChild( 'default-tax-rule' );
		
		$rule
		->addChild( 'tax-area' )
			->addChild( $tax_area )
				->addChild( 'state', $state );
				
		$rule->addChild( 'shipping-taxed', $shipping_taxed );
		$rule->addChild( 'rate', $rate );
				
		return $this;
	}
	
	public function add_item( $item )
	{	
		$item = (object)$item;
			
		$item_element = $this->element->items->addchild( 'item' );
		
		$item_element->addChild( 'merchant-item-id', $item->id );
		$item_element->addChild( 'item-name', $item->name );
		$item_element->addChild( 'item-description', $item->description );
		
		$item_weight = $item_element->addChild( 'item-weight' );
		
		$item_weight->addAttribute( 'unit', $this->manifest->unit_of_measure );
		$item_weight->addAttribute( 'value', $item->weight );
		
		if( isset( $item->subscription ) ){
			$item_element->addChild( 'unit-price', 0 )
						->addAttribute( 'currency', $this->manifest->currency );
		} else {
			$item_element->addChild( 'unit-price', $this->format_currency( $item->price ) )
						->addAttribute( 'currency', $this->manifest->currency );
		}
		
		$item_element->addChild( 'quantity', $item->quantity );
		
		$merchant_private_item_data = $item_element->addChild('merchant-private-item-data');
	
		$merchant_private_item_data->addChild( 'item_id', $item->id );

		if( !empty( $this->manifest->order_id ) ) 
			$merchant_private_item_data->addChild( 'order_id', $this->manifest->order_id );
		
		if( !empty( $this->manifest->customer_id ) ) 
			$merchant_private_item_data->addChild( 'customer_id', $this->manifest->customer_id );
	
		if( isset( $item->subscription ) ) $this->set_item_subscription( $item_element, $item );
		
		return $this;
	}
	
	/*public function remove_item( $item_id ){
		
		$this->load_resource( 'qp', '/library/QueryPath/QueryPath.php' );
		
		$dom = qp( $this->request->asXML() );
		
		$dom->remove( "item[id={$item_id}]");
		
		$this->request = new SimpleXMLElement( $dom->top()->xml() );
		
		return $this;
	}*/
	
	private function format_billing_period( $period )
	{	
		$billing_period = (object)array(
			'DAILY' => 'DAILY', 
			'WEEKLY' => 'WEEKLY', 
			'SEMI_MONTHLY' => 'SEMI_MONTHLY', 
			'MONTHLY' => 'MONTHLY', 
			'EVERY_TWO_MONTHS' => 'EVERY_TWO_MONTHS', 
			'QUARTERLY' => 'QUARTERLY', 
			'YEARLY' => 'YEARLY'
		);		
		
		isset( $billing_period->$period ) or $this->trigger( 'kc_error', array( 0, $this->l( 'BILLING_PERIOD_ERROR' ) ) );
		
		return $billing_period->$period;
	}
	
	private function format_date( $date )
	{	
		return date( 'c', strtotime( $date ) );	
	}
	
	private function format_currency( $amount )
	{	
		return $amount;	
	}
	
}
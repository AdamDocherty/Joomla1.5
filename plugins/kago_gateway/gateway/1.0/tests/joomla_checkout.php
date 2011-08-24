<?php
include( dirname(__FILE__) .'/joomla_initialise.php' );

include( dirname(__FILE__) .'/manifest.php' );

//Load plugin that houses KCommerce, the kc_initialise
//event can be found here
JPluginHelper::importPlugin( 'kago_gateway', 'gateway' );

//trigger the initialise event, we use array_pop
//because the trigger method returns an array
//and since we are only expecting one response
//all's cool
$KGateway = array_pop( 
	//message the initialise event also passing the selected pgate
	JDispatcher::getInstance()->trigger( 'kg_initialise', array( 'checkout', 'gcheckout' ) ) 
)
//note: object chaining ahead!
//here we add an object to implement the KCommerce Observer layer
->add_observer( 'Observer', dirname(__FILE__) .'/observer.php' )
//now we set the checkout manifest
->set_manifest( $manifest )
//and finally we checkout!
->checkout()
/*->add_item( array(
	'id' => 1299,
	'name' => 'Magic Book Replacement',
	'description' => 'A book about magic, DUH!',
	'price' => 10.99,
	'tax' => true,
	'quantity' => 2,
	'weight' => 0.5,
	'height' => 10,
	'width' => 20,
	'tangible' => true,
	'subscr' => null,	
))*/
;

echo $KGateway->format_xml( $KGateway->request->asXML() ); die();
 
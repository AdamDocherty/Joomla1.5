<?php
/**
 * @package     Joomla
 * @subpackage  Plugin
 *
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined( '_JEXEC' ) or die( 'Restricted access' );

class plgKago_gatewayGcheckout extends JPlugin
{				
	public function kg_initialise_gateway( JParameter $params, $type, $gateway )
	{	
		
		//make sure this is the correct gateway requested
		if( !stristr( get_class( $this ), $gateway ) ) return false;
		
		//set the class we will be using
		$gateway_class = 'K' . ucfirst( $gateway ) . ucfirst( $type );
		
		//check if gateway class is present, if not we load it
		if( !class_exists( $gateway_class ) ) 
			require_once( dirname(__FILE__)  . DS . $gateway . DS . $type . '.php'  );	
		
		//instantiate and return our gateway object
		return new $gateway_class( array(
			'merchant_id' 	=> $this->params->get( 'merchant_id', '205534975901825' ),
			'merchant_key' 	=> $this->params->get( 'merchant_key', '-8HRoGqhlpYOjzlGNQuxlg' ),
			//'uri' 			=> $this->params->get( 'uri', false ),
			//'test_uri' 		=> $this->params->get( 'test_uri', false ),
			'testmode' 		=> $this->params->get( 'testmode', true )						
		));
	}
}
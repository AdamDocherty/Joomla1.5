<?php
/**
 * @package     Joomla
 * @subpackage  Plugin
 *
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined( '_JEXEC' ) or die( 'Restricted access' );
					
class plgKago_gatewayGateway extends JPlugin
{				
	function kg_initialise( $type, $gateway )
	{						

		//note that major versions are stored in seperate folders
		$this->params->set( 'api_path', 
			dirname(__FILE__) . DS . 'gateway' . DS . $this->params->get( 'api_version', '1.0' ) . DS
		);
		
		//check if KGateway is present, if not we load it
		if( !class_exists( 'KGateway' ) ) 
			require_once( $this->params->get( 'api_path' ) . 'KGateway.php' );
		
		//load interface
		if( !class_exists( 'KGatewayInterface' . ucfirst( $type ) ) ) 
			require_once( $this->params->get( 'api_path' ) . 'interface' . DS . $type . '.php'  );
			
		//load abstract
		if( !class_exists( 'KGatewayAbstract' . ucfirst( $type ) ) ) 
			require_once( $this->params->get( 'api_path' ) . 'abstract' . DS . $type . '.php'  );
		
		//load our selected gateway, this plugin will have the 
		//kc_initilise_gateway event that we trigger a little later
		JPluginHelper::importPlugin( 'kago_gateway', $gateway );
		
		//initialise the gateway - we are only expecting one response so we
		//can use array_pop to quickly extract our gateway object 
		return array_pop( 
			JDispatcher::getInstance()->trigger( 'kg_initialise_gateway', array( $this->params, $type, $gateway ) )
		);
	}
}
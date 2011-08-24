<?php
/**
 * @package     KGateway
 * @subpackage  KGateway
 *
 * @author		Adam Stephen Docherty
 * @copyright   Copyright (C) 2011 'corePHP', LLC. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

/**
 * Payment Gateway.
 *
 * @package     KGateway
 * @subpackage  KGateway
 * @since       1.0
 */
 
abstract class KGateway
{				
	public 
	
	/**
	 * @public string API Version.
	 * @since  1.0
	 */
	$api_version = '1.0',
	
	/**
	 * @public array Observers that are to be messaged via the trigger method.
	 * @since  1.0
	 */
	$observers = array();	
	
	function __construct( $options )
	{	
		$this->set_options( $options );		
		$this->load_resource( 'KGatewayInterfaceObserver', '/interface/observer.php' );
		$this->load_resource( 'KGatewayAbstractObserver', '/abstract/observer.php' );		
	}
	
	public function set_options( $options=array() )
	{
		foreach( $options as $option=>$value ) 
			$this->$option = $value;
			
		return $this;
	}
	
	public function add_observer( $classname, $path )
	{
		if( !class_exists( $classname ) )
			require_once( $path );
		
		array_push( $this->observers, new $classname );		
		
		return $this;
	}
	
	public function trigger( $event, $arguments=array() )
	{
		//$this->trigger( '_kc_on_get_manifest', array( 100, 'oops' ) );
		
		$response = array();
		
		if( !empty( $this->observers ) ){
			foreach( $this->observers as $observer ){
				if( method_exists( $observer, $event ) ){
					$return[] = call_user_func_array( array( $observer, $event ), $arguments );
				} 
			}
		} 
		
		return $this;
	}
	
	public function load_resource( $classname, $path )
	{		
		if( !class_exists( $classname ) && !function_exists( $classname ) )
			require_once( dirname(__FILE__) . $path );
			
		return $this;		
	}
}
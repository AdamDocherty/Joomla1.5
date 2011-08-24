<?php
class Observer extends KGatewayAbstractObserver
{
	public function on_error(  $code, $message )
	{	
		throw new Exception(  $code . ': ' . $message );
	}
}
<?php

abstract class KGatewayAbstractObserver implements KGatewayInterfaceObserver
{
	public function on_error(  $code, $message )
	{	
		throw new Exception(  $code . '1: ' . $message );
	}
}
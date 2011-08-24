<?php
/**
 * @package     KGateway
 * @subpackage  Interface
 *
 * @author		Adam Stephen Docherty
 * @copyright   Copyright (C) 2011 'corePHP', LLC. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

/**
 * Payment Gateway.
 *
 * @package     KGateway
 * @subpackage  Interface
 * @since       1.0
 */
interface KGatewayInterfaceObserver
{
	/**
	 * Method to throw an error with code and message.
	 *
	 * @param    string    Error Code.
	 * @param    string    Error Message.
	 * @return  NULL
	 *
	 * @since   1.0
	 * @throws  Exception
	 */
	function on_error(  $code, $message );
}
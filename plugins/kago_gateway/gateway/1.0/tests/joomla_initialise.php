<?php
// Set flag that this is a parent file
define( '_JEXEC', 1 );

define('JPATH_BASE', str_replace( 'plugins/kago_gateway/gateway/1.0/tests', '', dirname(__FILE__) ) );

define( 'DS', DIRECTORY_SEPARATOR );

require_once ( JPATH_BASE .DS.'includes'.DS.'defines.php' );
require_once ( JPATH_BASE .DS.'includes'.DS.'framework.php' );
jimport('joomla.plugin.helper');

/**
 * CREATE THE APPLICATION
 *
 * NOTE :
 */
$mainframe = JFactory::getApplication('site');

/**
 * INITIALISE THE APPLICATION
 *
 * NOTE :
 */
// set the language
$mainframe->initialise();
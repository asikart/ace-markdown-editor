<?php
/**
 * @package		Asikart.Plugin
 * @subpackage	system.plg_akmarkdown
 * @copyright	Copyright (C) 2012 Asikart.com, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

$app = JFactory::getApplication() ;
$ww_exists = true ;

// Include WindWalker from libraries or plugin self.
// ===============================================================
if(!defined('AKPATH_ROOT')) {
	$inner_ww_path 	= JPATH_PLUGINS."/system/akmarkdown/windwalker" ;
	$lib_ww_path	= JPATH_LIBRARIES . '/windwalker' ;
	
	if(file_exists($lib_ww_path.'/init.php')) {
		// From libraries
		$ww_path = $lib_ww_path ;
	}else{
		// From Component folder
		$ww_path = $inner_ww_path ;
	}


	// Init WindWalker
	// ===============================================================
	if(!file_exists($ww_path.'/init.php')) {
		$message = 'Please install WindWalker Framework to enable Asikart Markdown Content Plugin.' ;
        $app->enqueueMessage($message, 'warning');
        $ww_exists = false ;
	}else{
        include_once $ww_path.'/init.php' ;
    }
}
else{
	include_once AKPATH_ROOT.'/init.php' ;
}

define( 'AKMARKDOWN_ENABLED', $ww_exists );
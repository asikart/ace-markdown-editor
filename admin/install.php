<?php
/**
 * @package		Asikart.Plugin
 * @subpackage	system.plg_akmarkdown
 * @copyright	Copyright (C) 2012 Asikart.com, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;
 
/**
 * Script file of Akmarkdown Module
 */
class plgSystemAkmarkdownInstallerScript
{
	/**
	 * method to install the component
	 *
	 * @return void
	 */
	function install($parent) 
	{
		
		
	}
 
	/**
	 * method to uninstall the component
	 *
	 * @return void
	 */
	function uninstall($parent) 
	{
		
	}
 
	/**
	 * method to update the component
	 *
	 * @return void
	 */
	function update($parent) 
	{
		
	}
 
	/**
	 * method to run before an install/update/uninstall method
	 *
	 * @return void
	 */
	function preflight($type, $parent) 
	{
		
	}
 
	/**
	 * method to run after an install/update/uninstall method
	 *
	 * @return void
	 */
	function postflight($type, $parent) 
	{
		$db = JFactory::getDbo();
		
		
		// Get install manifest
		// ========================================================================
		$p_installer 	= $parent->getParent() ;
		$installer 		= new JInstaller();
		$manifest 		= $p_installer->manifest ;
		$path 			= $p_installer->getPath('source');
		$result			= array() ;
		
		$css =
<<<CSS
	<style type="text/css">
		#ak-install-img {
			
		}
		
		#ak-install-msg {
			
		}
	</style>
CSS;

		$basePath = JPATH_ADMINISTRATOR ;
        $extension = 'plg_system_akmarkdown' ;
        $lang = JFactory::getLanguage();
        $lang->load(strtolower($extension), $basePath, null, false, false)
            || $lang->load(strtolower($extension), JPATH_PLUGINS . '/system/akmarkdown', null, false, false)
            || $lang->load(strtolower($extension), $basePath, $lang->getDefault(), false, false)
            || $lang->load(strtolower($extension), JPATH_PLUGINS . '/system/akmarkdown', $lang->getDefault(), false, false);
        
        
		echo $css ;
		include_once $path.'/windwalker/admin/installscript.php' ;
	}
	
}
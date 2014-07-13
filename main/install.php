<?php
/**
 * @package        Asikart.Plugin
 * @subpackage     system.plg_akmarkdown
 * @copyright      Copyright (C) 2012 Asikart.com, Inc. All rights reserved.
 * @license        GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

/**
 * Script file of Akmarkdown Plugin
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
		if (JVERSION < 3.2)
		{
			$parent->getParent()->abort('You need Joomla 3.2 of higher.');

			return;
		}
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
		if (JVERSION < 3.2)
		{
			$parent->getParent()->abort('You need Joomla 3.2 of higher.');

			return;
		}
	}

	/**
	 * method to run before an install/update/uninstall method
	 *
	 * @return void
	 */
	function preflight($type, $parent)
	{
		if (JVERSION < 3.2)
		{
			$parent->getParent()->abort('You need Joomla 3.2 of higher.');

			return;
		}

		$basePath  = JPATH_ADMINISTRATOR;
		$extension = 'plg_system_akmarkdown';
		$lang      = JFactory::getLanguage();
		$lang->load(strtolower($extension), $basePath, null, false, false)
		|| $lang->load(strtolower($extension), JPATH_PLUGINS . '/system/akmarkdown', null, false, false)
		|| $lang->load(strtolower($extension), $basePath, $lang->getDefault(), false, false)
		|| $lang->load(strtolower($extension), JPATH_PLUGINS . '/system/akmarkdown', $lang->getDefault(), false, false);
	}

	/**
	 * method to run after an install/update/uninstall method
	 *
	 * @return void
	 */
	function postflight($type, $parent)
	{
		if (JVERSION < 3.2)
		{
			$parent->getParent()->abort('You need Joomla 3.2 of higher.');

			return;
		}

		$db = JFactory::getDbo();

		// Load Lnguage
		$basePath  = JPATH_ADMINISTRATOR;
		$extension = 'plg_system_akmarkdown.sys';
		$lang      = JFactory::getLanguage();
		$lang->load(strtolower($extension), $basePath, null, false, false)
		|| $lang->load(strtolower($extension), JPATH_PLUGINS . '/system/akmarkdown', null, false, false)
		|| $lang->load(strtolower($extension), $basePath, $lang->getDefault(), false, false)
		|| $lang->load(strtolower($extension), JPATH_PLUGINS . '/system/akmarkdown', $lang->getDefault(), false, false);

		// Get install manifest
		// ========================================================================
		$p_installer = $parent->getParent();
		$installer   = new JInstaller();
		$manifest    = $p_installer->manifest;
		$path        = $p_installer->getPath('source');
		$result      = array();

		$css =
			<<<CSS
				<style type="text/css">
		#ak-install-img {
			
		}
		
		#ak-install-msg {
			
		}
	</style>
CSS;

		echo $css;

		$img = JURI::root() . 'plugins/system/akmarkdown/images/akmarkdown-logo.png';
		$a   = 'index.php?option=com_plugins&view=plugins&filter_search=ace%20x%20markdown';
		echo JHtml::link($a, JHtml::image($img, 'LOGO'));
		echo '<br /><br />';
		echo JText::sprintf('PLG_SYSTEM_AKMARKDOWN_XML_DESCRIPTION', $a);
		echo '<br /><br />';

		$installScript = __DIR__ . '/src/Installer/installscript.php';

		if (!is_file($installScript))
		{
			$installScript = JPATH_LIBRARIES . '/windwalker/src/System/installscript.php';
		}

		include $installScript;
	}

}
<?php
/**
 * Part of Windwalker project.
 *
 * @copyright  Copyright (C) 2011 - 2014 SMS Taiwan, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

// No direct access
defined('_JEXEC') or die;

$db = JFactory::getDbo();

// Show Installed table
// ========================================================================
$grid = new \JGrid;

$option['class'] = 'adminlist table table-striped table-bordered';
$option['style'] = 'width: 750px;';

$grid->setTableOptions($option);
$grid->setColumns(array('num', 'type', 'name', 'version', 'state', 'info'));

$grid->addRow(array(), 1);
$grid->setRowCell('num',     '#', array());
$grid->setRowCell('type',    JText::_('COM_INSTALLER_HEADING_TYPE'), array());
$grid->setRowCell('name',    JText::_('COM_INSTALLER_HEADING_NAME'), array());
$grid->setRowCell('version', JText::_('JVERSION'), array());
$grid->setRowCell('state',   JText::_('JSTATUS'), array());
$grid->setRowCell('info',    JText::_('COM_INSTALLER_MSG_DATABASE_INFO'), array());

// Set cells
$i = 0;

$tick  = '<i class="icon-publish"></i>';
$cross = '<i class="icon-unpublish"></i>';

$td_class = array('style' => 'text-align:center;');

// Set Extension install success info
// ========================================================================
include __DIR__ . '/installscript/' . $manifest['type'] . '.php';

// Install plugins
// ========================================================================
include __DIR__ . '/installscript/plugins.php';

// Render install information
// ========================================================================
echo $grid;

<?php
/**
 * @package     Joomla.Platform
 * @subpackage  Form
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
 
defined('JPATH_PLATFORM') or die;
 
/**
 * Form Field class for the Joomla Platform.
 * Supports an HTML select list of categories
 *
 * @package     Joomla.Platform
 * @subpackage  Form
 * @since       11.1
 */
class JFormFieldGotosystem extends JFormField
{
    /**
     * The form field type.
     *
     * @var    string 
     * @since  11.1
     */
    public $type = 'Gotosystem';
    
    /**
     * Method to get the field input markup.
     *
     * @return  string  The field input markup.
     *
     * @since   11.1
     */
    protected function getInput()
    {
        $db = JFactory::getDbo();
        $q = $db->getQuery(true) ;
        
        $q->select("extension_id")
            ->from("#__extensions")
            ->where("name = 'plg_system_akmarkdown'")
            ;
        
        $db->setQuery($q);
        $id = $db->loadResult();
        
        $html = '';
        
        if($id) {
            $link = "index.php?option=com_plugins&task=plugin.edit&extension_id=".$id ;
            $html = JHtml::link($link, JText::_('PLG_EDITORS_AKMARKDOWN_GOTO_SYSTEM'), array('target'=> '_blank')) ;
        }
        
        return $html;
    }
}
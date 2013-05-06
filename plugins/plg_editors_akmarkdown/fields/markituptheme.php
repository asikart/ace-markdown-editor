<?php
/**
 * @package     Joomla.Platform
 * @subpackage  Form
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
 
defined('JPATH_PLATFORM') or die;
 
JFormHelper::loadFieldClass('list');
 
/**
 * Form Field class for the Joomla Platform.
 * Supports an HTML select list of categories
 *
 * @package     Joomla.Platform
 * @subpackage  Form
 * @since       11.1
 */
class JFormFieldMarkituptheme extends JFormFieldList
{
    /**
     * The form field type.
     *
     * @var    string 
     * @since  11.1
     */
    public $type = 'Markituptheme';
 
    /**
     * Method to get the field options for category
     * Use the extension attribute in a form to specify the.specific extension for
     * which categories should be displayed.
     * Use the show_root attribute to specify whether to show the global category root in the list.
     *
     * @return  array    The field option objects.
     *
     * @since   11.1
     */
    protected function getOptions()
    {
        // Initialise variables.
        $options = array();
        $name = (string) $this->element['name'];
 
        $files = JFolder::folders(dirname(__FILE__).'/../assets/markitup/skins');
        
        foreach( $files as $file ):
            $options[] = JHtml::_(
                'select.option', $file,
                $file, 'value', 'text'
            );
        endforeach;
 
        // Merge any additional options in the XML definition.
        $options = array_merge(parent::getOptions(), $options);
 
        return $options;
    }
    
    /**
     * Method to get the field input markup.
     *
     * @return  string  The field input markup.
     *
     * @since   11.1
     */
    public function getInput()
    {
        $a = '  <a href="http://markitup.jaysalvat.com/examples/" target="_blank">'.JText::_('JHELP').'</a>' ;
        return parent::getInput(). $a ;
    }
}
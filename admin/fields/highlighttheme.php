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
class JFormFieldHighlighttheme extends JFormFieldList
{
    /**
     * The form field type.
     *
     * @var    string 
     * @since  11.1
     */
    public $type = 'Highlighttheme';
 
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
        // INCLUDE WINDWALKER FRAMEWORK
        include_once dirname(__FILE__).'/../lib/init.php' ;
        
        jimport('joomla.filesystem.folder');
        
        // Initialise variables.
        $options = array();
        $name = (string) $this->element['name'];
 
        $files = JFolder::files(AKPATH_ROOT.'/assets/js/highlight/styles');
        
        foreach( $files as $file ):
            if( strpos($file, '.css') !== false ) {
                $file = str_replace( '.css', '', $file ) ;
                $options[] = JHtml::_(
                    'select.option', $file,
                    $file, 'value', 'text'
                );
            }
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
        if(JVERSION < 3){
            AKHelper::_('include.addCSS', 'buttons/delicious-buttons/delicious-buttons.css', 'ww');
        }
        
        $a = '  <a style="float: left; margin-left: 10px;" class="akmarkdown-help-button btn btn-small delicious light green-pastel" href="http://softwaremaniacs.org/media/soft/highlight/test.html" target="_blank">'.JText::_('JHELP').'</a>' ;
        return '<div class="akmarkdown-help-wrap pull-left fltlft">'.parent::getInput(). '</div>'. $a ;
    }
    
}
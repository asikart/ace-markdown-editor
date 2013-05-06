<?php
/**
 * @package		Asikart.Plugin
 * @subpackage	editors.plg_akmarkdown
 * @copyright	Copyright (C) 2012 Asikart.com, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');

/**
 * Akmarkdown Editors Plugin
 *
 * @package		Joomla.Plugin
 * @subpackage	Editors.akmarkdown
 * @since		1.5
 */
class plgEditorAkmarkdown extends JPlugin
{
	
	public static $_self ;
	
	/**
	 * Constructor
	 *
	 * @access      public
	 * @param       object  $subject The object to observe
	 * @param       array   $config  An array that holds the plugin configuration
	 * @since       1.6
	 */
    public function __construct(&$subject, $config)
    {
		parent::__construct( $subject, $config );
		$this->loadLanguage();
		$this->app = JFactory::getApplication();
        
		self::$_self = $this ;
    }
	
	
	
	/*
	 * function getInstance
	 */
	
	public static function getInstance()
	{
		return self::$_self ;
	}
	
	
	
	// system Events
	// ======================================================================================
	
	/**
	 * Method to handle the onInitEditor event.
	 *  - Initialises the Editor
	 *
	 * @return  string	JavaScript Initialization string
	 * @since 1.5
	 */
	public function onInit()
	{
        $app    = JFactory::getApplication() ;
        $doc    = JFactory::getDocument();
        $user   = JFactory::getUser() ;
        $params = $this->params ;
        
        // Include JS
        // ===============================================================
        if( JVERSION < 3 ) {
            $doc->addScript( JURI::root(true).'/plugins/editors/akmarkdown/assets/jquery.js' ) ;
            $doc->addScript( JURI::root(true).'/plugins/editors/akmarkdown/assets/jquery.noconflict.js' ) ;
        }
        
        $doc->addScript( JURI::root(true).'/plugins/editors/akmarkdown/assets/markitup/jquery.markitup.js' ) ;
        $doc->addScript( JURI::root(true).'/plugins/editors/akmarkdown/assets/markitup/sets/markdown/set.js' ) ;
        $doc->addScript( JURI::root(true).'/plugins/editors/akmarkdown/assets/ace/ace.js' ) ;
        $doc->addScript( JURI::root(true).'/plugins/editors/akmarkdown/assets/akmarkdown.js' ) ;
        
        
        // Include CSS
        // ===============================================================
        //$doc->addStylesheet( JURI::root(true).'/plugins/editors/akmarkdown/assets/images/style.css' ) ;
        $doc->addStylesheet( JURI::root(true).'/plugins/editors/akmarkdown/assets/markitup/skins/'.$params->get('MarkItUp_Theme', 'simple').'/style.css' ) ;
        $doc->addStylesheet( JURI::root(true).'/plugins/editors/akmarkdown/assets/markitup/sets/markdown/style.css' ) ;
        
        
        
        $return =
<<<RT
<style type="text/css">
.akmarkdown-wrap { height: 450px; }
</style>

<script type="text/javascript">

var AKMarkdownOption = {
    aceTheme : '{$params->get('AceEditor_Theme', 'twilight')}'
};

var AKMarkdown  = new AKMarkdownClass(AKMarkdownOption) ;

// on Joomla! Save
window.addEvent('domready', function(){
    AKMarkdown.overrideSaveAction();
});
</script>
RT;
        
        return $return ;
	}

	/**
	 * Copy editor content to form field.
	 *
	 * @return  void
	 */
	public function onSave($id)
	{
		return "document.getElementById(editor).value = AKMarkdown.ace['{$id}'].getValue();\n";
	}

	/**
	 * Get the editor content.
	 *
	 * @param   string	$id		The id of the editor field.
	 *
	 * @return  string
	 */
	public function onGetContent($id)
	{
		return "AKMarkdown.ace[editor].getValue();\n";
	}

	/**
	 * Set the editor content.
	 *
	 * @param   string	$id		The id of the editor field.
	 * @param   string	$html	The content to set.
	 *
	 * @return  string
	 */
	public function onSetContent($id, $html)
	{
		return "document.getElementById(editor).value = $html;\n";
	}

	/**
	 * @param   string	$id
	 *
	 * @return  string
	 */
	public function onGetInsertMethod($id)
	{
		static $done = false;

		// Do this only once.
		if (!$done)
		{
			$doc = JFactory::getDocument();
			$js =
<<<JS
            function jInsertEditorText(text, editor)
			{
				AKMarkdown.ace[editor].insert(text) ;
                AKMarkdown.ace[editor].focus();
			}
JS;
			$doc->addScriptDeclaration($js);
		}

		return true;
	}

	/**
	 * Display the editor area.
	 *
	 * @param   string	$name		The control name.
	 * @param   string	$html		The contents of the text area.
	 * @param   string	$width		The width of the text area (px or %).
	 * @param   string	$height		The height of the text area (px or %).
	 * @param   integer  $col		The number of columns for the textarea.
	 * @param   integer  $row		The number of rows for the textarea.
	 * @param   boolean	$buttons	True and the editor buttons will be displayed.
	 * @param   string	$id			An optional ID for the textarea (note: since 1.6). If not supplied the name is used.
	 * @param   string	$asset
	 * @param   object	$author
	 * @param   array  $params		Associative array of editor parameters.
	 *
	 * @return  string
	 */
	public function onDisplay($name, $content, $width, $height, $col, $row, $buttons = true, $id = null, $asset = null, $author = null, $params = array())
	{
        $doc = JFactory::getDocument();
        
		if (empty($id))
		{
			$id = $name;
		}
        
        
        $script =
<<<SC

// Init AKMarkdown Editor "{$id}"
jQuery(document).ready(function($){
    AKMarkdown.createEditor('{$id}', '{$name}') ;
});

SC;
        
        $doc->addScriptDeclaration($script);
        
        
		// Only add "px" to width and height if they are not given as a percentage
		if (is_numeric($width))
		{
			$width .= 'px';
		}

		if (is_numeric($height))
		{
			$height .= 'px';
		}
        
		$buttons = $this->_displayButtons($id, $buttons, $asset, $author);
		$editor  = "<div id=\"{$id}-wrap\" class=\"akmarkdown-wrap {$id}\">".$content."</div>" . $buttons;

		return $editor;
	}

	public function _displayButtons($name, $buttons, $asset, $author)
	{
		// Load modal popup behavior
		JHtml::_('behavior.modal', 'a.modal-button');

		$args['name'] = $name;
		$args['event'] = 'onGetInsertMethod';

		$return = '';
		$results[] = $this->update($args);

		foreach ($results as $result)
		{
			if (is_string($result) && trim($result))
			{
				$return .= $result;
			}
		}

		if (is_array($buttons) || (is_bool($buttons) && $buttons))
		{
			$results = $this->_subject->getButtons($name, $buttons, $asset, $author);

			// This will allow plugins to attach buttons or change the behavior on the fly using AJAX
			$return .= "\n<div id=\"editor-xtd-buttons\" class=\"btn-toolbar pull-left\">\n";
			$return .= "\n<div class=\"btn-toolbar\">\n";

			foreach ($results as $button)
			{
				// Results should be an object
				if ($button->get('name'))
				{
					$modal		= ($button->get('modal')) ? 'class="modal-button btn"' : null;
					$href		= ($button->get('link')) ? 'class="btn" href="'.JURI::base().$button->get('link').'"' : null;
					$onclick	= ($button->get('onclick')) ? 'onclick="'.$button->get('onclick').'"' : null;
					$title      = ($button->get('title')) ? $button->get('title') : $button->get('text');
					$return .= "<a ".$modal." title=\"".$title."\" ".$href." ".$onclick." rel=\"".$button->get('options')."\"><i class=\"icon-".$button->get('name')."\"></i> ".$button->get('text')."</a>\n";
				}
			}

			$return .= "</div>\n";
			$return .= "</div>\n";
			$return .= "<div class=\"clearfix\"></div>\n";
		}

		return $return;
	}
	
	
	
	// AKFramework Functions
	// ====================================================================================
	
	
	/**
	 * function call
	 * 
	 * A proxy to call class and functions
	 * Example: $this->call('folder1.folder2.function', $args) ; OR $this->call('folder1.folder2.Class::function', $args)
	 * 
	 * @param	string	$uri	The class or function file path.
	 * 
	 */
	
	public function call( $uri ) {
		// Split paths
		$path = explode( '.' , $uri );
		$func = array_pop($path);
		$func = explode( '::', $func );
		
		// set class name of function name.
		if(isset($func[1])){
			$class_name = $func[0] ;
			$func_name = $func[1] ;
			$file_name = $class_name ;
		}else{
			$func_name = $func[0] ;
			$file_name = $func_name ;
		}
		
		$func_path 		= implode('/', $path).'/'.$file_name;
		$include_path = JPATH_ROOT.'/'.$this->params->get('include_path', 'easyset');
		
		// include file.
		if( !function_exists ( $func_name )  && !class_exists($class_name) ) :			
			$file = trim($include_path, '/').'/'.$func_path.'.php' ;
			
			if( !file_exists($file) ) {
				$file = dirname(__FILE__).'/lib/'.$func_path.'.php' ;
			}
			
			if( file_exists($file) ) {
				include_once( $file ) ;
			}
		endif;
		
		// Handle args
		$args = func_get_args();
        array_shift( $args );
        
		// Call Function
		if(isset($class_name) && method_exists( $class_name, $func_name )){
			return call_user_func_array( array( $class_name, $func_name ) , $args );
		}elseif(function_exists ( $func_name )){
			return call_user_func_array( $func_name , $args );
		}
		
	}
	
	
	
	public function includeEvent($func) {
		$include_path = JPATH_ROOT.'/'.$this->params->get('include_path', 'easyset');
		$event = trim($include_path, '/').'/'.'events/'.$func.'.php' ;
		if(file_exists( $event )) return $event ;
	}
	
	
	
	public function resultBool($result = array()) {
		foreach( $result as $result ):
			if(!$result) return false ;
		endforeach;
		
		return true ;
	}
}

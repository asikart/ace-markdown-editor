<?php
/**
 * @package		Asikart.Plugin
 * @subpackage	system.plg_akmarkdown
 * @copyright	Copyright (C) 2012 Asikart.com, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;


jimport('joomla.plugin.plugin');

/**
 * Akmarkdown System Plugin
 *
 * @package		Joomla.Plugin
 * @subpackage	System.akmarkdown
 * @since		1.5
 */
class plgSystemAkmarkdown extends JPlugin
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
        // INCLUDE WINDWALKER FRAMEWORK
        include_once dirname(__FILE__).'/lib/init.php' ;
        
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
	
	/*
	 * function onAfterInitialise
	 */
	
	public function onAfterInitialise()
	{
		$akmarkdown = JRequest::getVar('akmarkdown') ;
        
        if( $akmarkdown ) {
            
            $post   = JRequest::get('post') ;
            $text   = $_POST['data'] ;
            
            //$text   = str_replace('<?', '#?', $text) ;
            
            $text   = $this->render($text);
            
            //SEF
            $base	= JURI::base(true).'/';
    
            $regex  = '#href="index.php\?([^"]*)#m';
            $text   = preg_replace_callback($regex, array('plgSystemAkmarkdown', 'route'), $text);
    
            $protocols	= '[a-zA-Z0-9]+:'; //To check for all unknown protocals (a protocol must contain at least one alpahnumeric fillowed by :
            $regex		= '#(src|href|poster)="(?!/|'.$protocols.'|\#|\')([^"]*)"#m';
            $text		= preg_replace($regex, "$1=\"$base\$2\"", $text);
            
            // Replace some text
            $text       = str_replace('<a', '<a target="_blank"', $text) ;
            
            echo $text ;
            
            jexit();
        }
	}
    
    /**
	 * Replaces the matched tags
	 *
	 * @param	array	An array of matches (see preg_match_all)
	 * @return	string
	 */
	protected static function route(&$matches)
	{
		$original	= $matches[0];
		$url		= $matches[1];
		$url		= str_replace('&amp;', '&', $url);
		$route		= JRoute::_('index.php?'.$url);

		return 'href="'.$route;
	}
    
	
	
	// Content Events
	// ======================================================================================
	
	
	/**
	 * Akmarkdown prepare content method
	 *
	 * Method is called by the view
	 *
	 * @param	string	The context of the content being passed to the plugin.
	 * @param	object	The content object.  Note $article->text is also available
	 * @param	object	The content params
	 * @param	int		The 'page' number
	 * @since	1.6
	 */
	public function onContentPrepare($context, &$article, &$params, $page=0)
	{
		$app = JFactory::getApplication();
		
		$article->text = $this->render( $article->text );
        
		if( $path = $this->includeEvent(__FUNCTION__) ) @include $this->includeEvent(__FUNCTION__);
	}
	
    
	/**
     * function render
     * @param $text
     */
    public function render($text)
    {
        if( AKMARKDOWN_ENABLED ){
            $extra  = $this->params->get('Markdown_Extra', 1);
            $theme  = $this->params->get('Highlight_Theme', 'default');
            $text   = AKHelper::_('html.markdown', $text, $extra, array('highlight' => $theme));
        }else{
            $text = nl2br($text);
        }
        
        return $text ;
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

<?php
/**
 * @package        Asikart.Plugin
 * @subpackage     system.plg_akmarkdown
 * @copyright      Copyright (C) 2012 Asikart.com, Inc. All rights reserved.
 * @license        GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');

/**
 * Akmarkdown System Plugin
 *
 * @package        Joomla.Plugin
 * @subpackage     System.akmarkdown
 * @since          3.0
 */
class PlgSystemAkmarkdown extends JPlugin
{
	/**
	 * Property self.
	 *
	 * @var  plgSystemAkmarkdown
	 */
	public static $self;

	/**
	 * Constructor
	 *
	 * @param   object  &$subject  The object to observe
	 * @param   array   $config    An array that holds the plugin configuration
	 *
	 * @since   1.6
	 */
	public function __construct(&$subject, $config)
	{
		parent::__construct($subject, $config);

		$this->loadLanguage();
		$this->app = JFactory::getApplication();

		self::$self = $this;
	}

	/**
	 * getInstance
	 *
	 * @return  plgSystemAkmarkdown
	 */
	public static function getInstance()
	{
		return self::$self;
	}

	// System Events
	// ======================================================================================

	/**
	 * onAfterInitialise
	 *
	 * @return  void
	 */
	public function onAfterInitialise()
	{
		$input = JFactory::getApplication()->input;

		$akmarkdown = $input->getVar('akmarkdown');

		if ($akmarkdown)
		{
			$text = $input->post->getRaw('data');

			$text = $this->render($text);

			// SEF
			$base = JURI::base(true) . '/';

			$regex = '#href="index.php\?([^"]*)#m';
			$text  = preg_replace_callback($regex, array('plgSystemAkmarkdown', 'route'), $text);

			// To check for all unknown protocals (a protocol must contain at least one alpahnumeric fillowed by :
			$protocols = '[a-zA-Z0-9]+:';
			$regex     = '#(src|href|poster)="(?!/|' . $protocols . '|\#|\')([^"]*)"#m';
			$text      = preg_replace($regex, "$1=\"$base\$2\"", $text);

			// Replace some text
			$text = str_replace('<a', '<a target="_blank"', $text);

			echo $text;

			jexit();
		}
	}

	/**
	 * Replaces the matched tags
	 *
	 * @param   array  &$matches  An array of matches (see preg_match_all)
	 *
	 * @return  string
	 */
	protected static function route(&$matches)
	{
		$original = $matches[0];
		$url      = $matches[1];
		$url      = str_replace('&amp;', '&', $url);
		$route    = JRoute::_('index.php?' . $url);

		return 'href="' . $route;
	}

	// Content Events
	// ======================================================================================

	/**
	 * Akmarkdown prepare content method
	 *
	 * Method is called by the view
	 *
	 * @param   string   $context   The context of the content being passed to the plugin.
	 * @param   object   &$article  The content object.  Note $article->text is also available
	 * @param   object   &$params   The content params
	 * @param   int      $page      The 'page' number
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */
	public function onContentPrepare($context, &$article, &$params, $page = 0)
	{
		$article->text = '<div class="akmarkdown-content" >' . $this->render($article->text) . '</div>';

		if ($path = $this->includeEvent(__FUNCTION__))
		{
			@include $this->includeEvent(__FUNCTION__);
		}
	}

	/**
	 * Akmarkdown after display content method
	 *
	 * Method is called by the view and the results are imploded and displayed in a placeholder
	 *
	 * @param   string   $context   The context of the content being passed to the plugin.
	 * @param   object   &$article  The content object.  Note $article->text is also available
	 * @param   object   &$params   The content params
	 * @param   int      $page      The 'page' number
	 *
	 * @return   string
	 *
	 * @since    1.6
	 */
	public function onContentAfterDisplay($context, &$article, &$params, $page = 0)
	{
		$prettify = $this->params->get('Article_Prettify', 2);

		if (!$prettify)
		{
			return;
		}

		$return   = true;
		$nav_list = ($context == 'com_content.article') ? true : false;

		if ($prettify == 1 && $context == 'com_content.article')
		{
			$return = false;
		}

		if ($prettify == 2 &&
			(
				$context == 'com_content.category'
				|| $context == 'com_content.article'
				|| $context == 'com_content.featured'
			)
		)
		{
			$return = false;
		}

		if ($prettify >= 3)
		{
			$return = false;
		}

		if ($return)
		{
			return;
		}

		// Set JS
		static $loaded;

		if (!$loaded)
		{
			$doc = JFactory::getDocument();

			// Set Params
			$option['Article_ForceNewWindow']     = $this->params->get('Article_ForceNewWindow', false);
			$option['Article_NavList']            = $nav_list ? $this->params->get('Article_NavList', false) : false;
			$option['Article_NavList_Class']      = $this->params->get('Article_NavList_Class', 'akmarkdown-nav-box well well-small');
			$option['Article_ForceImageAlign']    = $this->params->get('Article_ForceImageAlign', 'center');
			$option['Article_ForceImageMaxWidth'] = $this->params->get('Article_ForceImageMaxWidth', 0);
			$option['Article_ImageClass']         = $this->params->get('Article_ImageClass', 'akmarkdown-img img-polaroid');
			$option['Article_TableClass']         = $this->params->get('Article_TableClass', 'akmarkdown-table table-bordered table-striped table-hover center');

			// Set Language
			JText::script('PLG_SYSTEM_AKMARKDOWN_NAV_LIST_BACK_TO_TOP');

			$option = $this->getJSObject($option);

			JHtml::_('behavior.framework', true);
			$doc->addStylesheet(JURI::root(true) . '/plugins/system/akmarkdown/assets/css/content.css');
			$doc->addScript(JURI::root(true) . '/plugins/system/akmarkdown/assets/js/content.js');
			$doc->addScriptDeclaration('var AKMarkdownOption = ' . $option . '; AKMarkdownPretiffy( AKMarkdownOption ); ');

			$loaded = true;
		}
	}

	// Other Functions
	// ====================================================================================

	/**
	 * render
	 *
	 * @param   string $text
	 *
	 * @return  mixed|string
	 */
	public function render($text)
	{
		include_once __DIR__ . "/lib/autoload.php";

		$extra = $this->params->get('Markdown_Extra', 1);

		$option['highlight']        = $this->params->get('Highlight_Theme', 'default');
		$option['highlight_enable'] = $this->params->get('Highlight_Enabled', 1);

		// Render markdown
		$text = str_replace("\t", '    ', $text);

		if ($extra)
		{
			$text = \Michelf\MarkdownExtra::defaultTransform($text);
		}
		else
		{
			$text = \Michelf\Markdown::defaultTransform($text);
		}

		$text = preg_replace('/((http|ftp|https):\/\/[\w-]+(\.[\w-]+)+([\w.,@?^=%&amp;:\/~+#-]*[\w@?^=%&amp;\/~+#-])?)/', '<a href="\1">\1</a>', $text);

		if (JArrayHelper::getValue($option, 'highlight_enable', 1))
		{
			AKHelper::_('html.highlight', JArrayHelper::getValue($option, 'highlight', 'default'));
		}

		return $text;
	}

	/**
	 * getJSObject
	 *
	 * @param array $array
	 *
	 * @return  string
	 */
	public function getJSObject($array = array())
	{
		// Initialise variables.
		$object = "{";
		$comma  = ",";

		// Iterate over array to build objects
		foreach ((array) $array as $k => $v)
		{
			if (is_null($v))
			{
				continue;
			}

			if (is_bool($v))
			{
				{
					$object .= ' ' . $k . ': ';
					$object .= ($v) ? 'true' : 'false';
					$object .= $comma;
				}
			}
			elseif (!is_array($v) && !is_object($v))
			{
				$object .= ' ' . $k . ': ';
				$object .= (is_numeric($v) || strpos($v, '\\') === 0) ? (is_numeric($v)) ? $v : substr($v, 1) : "'" . $v . "'";
				$object .= $comma;
			}
			else
			{
				$object .= ' ' . $k . ': ' . $this->getJSObject($v) . $comma;
			}
		}

		if (substr($object, -1) == ',')
		{
			$object = substr($object, 0, -1);
		}

		$object .= '}';

		return $object;
	}

	// AKFramework Functions
	// ====================================================================================

	/**
	 * call
	 *
	 * @param   string $uri
	 *
	 * @return  mixed
	 */
	public function call($uri)
	{
		// Split paths
		$path = explode('.', $uri);
		$func = array_pop($path);
		$func = explode('::', $func);

		// set class name of function name.
		if (isset($func[1]))
		{
			$class_name = $func[0];
			$func_name  = $func[1];
			$file_name  = $class_name;
		}
		else
		{
			$func_name = $func[0];
			$file_name = $func_name;
		}

		$func_path    = implode('/', $path) . '/' . $file_name;
		$include_path = JPATH_ROOT . '/' . $this->params->get('include_path', 'easyset');

		// include file.
		if (!function_exists($func_name) && !class_exists($class_name)) :
			$file = trim($include_path, '/') . '/' . $func_path . '.php';

			if (!file_exists($file))
			{
				$file = dirname(__FILE__) . '/lib/' . $func_path . '.php';
			}

			if (file_exists($file))
			{
				include_once($file);
			}
		endif;

		// Handle args
		$args = func_get_args();
		array_shift($args);

		// Call Function
		if (isset($class_name) && method_exists($class_name, $func_name))
		{
			return call_user_func_array(array($class_name, $func_name), $args);
		}
		elseif (function_exists($func_name))
		{
			return call_user_func_array($func_name, $args);
		}

	}

	public function includeEvent($func)
	{
		$include_path = JPATH_ROOT . '/' . $this->params->get('include_path', 'easyset');
		$event        = trim($include_path, '/') . '/' . 'events/' . $func . '.php';
		if (file_exists($event))
		{
			return $event;
		}
	}

	public function resultBool($result = array())
	{
		foreach ($result as $result):
			if (!$result)
			{
				return false;
			}
		endforeach;

		return true;
	}
}

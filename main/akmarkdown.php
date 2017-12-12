<?php
/**
 * @package        Asikart.Plugin
 * @subpackage     system.plg_akmarkdown
 * @copyright      Copyright (C) 2012 Asikart.com, Inc. All rights reserved.
 * @license        GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
use Akmarkdown\Web\Response;
use Joomla\Utilities\ArrayHelper;

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
	 * Property hash.
	 *
	 * @var  string
	 */
	protected $hash = '';

	/**
	 * Property version.
	 *
	 * @var string
	 */
	protected $version = null;

	/**
	 * Property headBottom.
	 *
	 * @var string
	 */
	protected $headBottom = null;

	/**
	 * Property highlightJSLoaded.
	 *
	 * @var  bool
	 */
	protected $highlightJSLoaded = false;

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
		$this->hash = JDEBUG ? md5(uniqid()) : $this->getVersion();

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
	}

	/**
	 * This event raised from com_ajax.
	 *
	 * @return  mixed|string
	 *
	 * @throws Exception
	 */
	public function onAjaxAkmarkdownPreview()
	{
		$input = JFactory::getApplication()->input;

		$text = $input->post->get('data', '', 'raw');

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

		$text .= <<<STYLE
<style>
img { max-width: 550px; }
</style>
STYLE;

		return $text;
	}

	/**
	 * This event raised from com_ajax.
	 *
	 * @return  void
	 *
	 * @throws  Exception
	 */
	public function onAjaxAkmarkdownUpload()
	{
		include_once __DIR__ . '/lib/autoload.php';

		$input = JFactory::getApplication()->input->files;

		try
		{
			$result = Akmarkdown\Uploader\ImageUploader::upload($input);
		}
		catch (\Exception $e)
		{
			$result = array('error', $e->getMessage());
		}

		$response = new Response;
		$response->setBody(json_encode($result));
		$response->setMimeType('text/json');

		$response->respond();

		exit();
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
			$doc->addStyleSheetVersion(JURI::root(true) . '/plugins/system/akmarkdown/assets/css/content.css', $this->hash);
			$doc->addScriptVersion(JURI::root(true) . '/plugins/system/akmarkdown/assets/js/content.js', $this->hash);
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

		// Pre-process adding whitespace before </p> to avoid wrong URL
		$text = str_replace('</p', ' </p', $text);

		$autolink = new \Asika\Autolink\Autolink;
		$text = $autolink->convert($text, array('target' => '_blank'));

		if (ArrayHelper::getValue($option, 'highlight_enable', 1) && !$this->highlightJSLoaded)
		{
			$this->loadHighlightJs(ArrayHelper::getValue($option, 'highlight', 'default'));

			$this->highlightJSLoaded = true;
		}

		return $text;
	}

	/**
	 * Highlight Markdown <pre><code class="lang">.
	 *
	 * Use highlight.js: http://softwaremaniacs.org/soft/highlight/en/
	 *
	 * @param   string  $theme  Code style name.
	 *
	 * @return  void
	 */
	protected function loadHighlightJs($theme = 'default')
	{
		$css = 'assets/js/highlight/styles/' . $theme . '.css';

		jimport('joomla.filesystem.file');

		if (!JFile::exists(__DIR__ . '/' . $css))
		{
			$css = 'assets/js/highlight/styles/default.css';
		}

		$doc = JFactory::getDocument();

		$this->addStylesheetInHeadBottom(JUri::root(true) . '/plugins/system/akmarkdown/' . $css, $this->hash);
		$doc->addScriptVersion(JUri::root(true) . '/plugins/system/akmarkdown/assets/js/highlight/highlight.pack.js', $this->hash);

		$doc->addScriptDeclaration("    ;hljs.initHighlightingOnLoad();\n");
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

	/**
	 * addStylesheetInHeadBottom
	 *
	 * @param string $url
	 * @param string $hash
	 *
	 * @return  void
	 */
	protected function addStylesheetInHeadBottom($url, $hash)
	{
		$this->headBottom = $url . '?' . $hash;
	}

	/**
	 * onAfterRender
	 *
	 * @return  void
	 */
	public function onAfterRender()
	{
		if (! $this->headBottom)
		{
			return;
		}

		$body = $this->app->getBody();

		$body = explode('</head>', $body);

		$body[0] .= '  <link rel="stylesheet" href="' . $this->headBottom . '" type="text/css" />' . "\n";

		$body = implode('</head>', $body);

		$this->app->setBody($body);
	}

	// AKFramework Functions
	// ====================================================================================

	/**
	 * getVersion
	 *
	 * @return  string
	 */
	protected function getVersion()
	{
		if ($this->version)
		{
			return $this->version;
		}

		$xml = __DIR__ . '/akmarkdown.xml';

		$xml = simplexml_load_file($xml);

		return (string) $this->version = $xml->version;
	}
}

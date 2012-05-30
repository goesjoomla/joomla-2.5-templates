<?php
/**
 * GoesJoomla template framework.
 *
 * Declares base template processing and rendering class.
 *
 * @copyright	Copyright (C) 2012 Manh-Cuong Nguyen. All rights reserved.
 * @author		Manh-Cuong Nguyen <cuongnm@goesjoomla.com>
 * @license		Visit http://www.goesjoomla.com/licenses.html for details.
 * @link		http://www.goesjoomla.com/templates.html
 */

// No direct access.
defined('_JEXEC') or die;

class GJTemplate
{
	/**
	 * Path to template base directory.
	 *
	 * @var string
	 */
	static private $path;

	/**
	 * Template layout for rendering.
	 *
	 * @var string
	 */
	static private $layout;

	/**
	 * Layout theme for rendering.
	 *
	 * @var string
	 */
	static private $theme;

	/**
	 * Structural and content blocks visibility status.
	 *
	 * @var object
	 */
	static private $status;

	/**
	 * Entry method for setting template properties.
	 *
	 * @param	string	$property
	 * @param	string	$value
	 * @return	void
	 */
	static public function set($property, $value)
	{
		// Generates method name to handle set action.
		$method = 'set'.ucfirst($property);

		// Sets property value.
		if (method_exists(__CLASS__, $method))
		{
			return self::$method($value);
		}
		else
		{
			self::${$property} = $value;
		}
	}

	/**
	 * Detects structural and content blocks visibility status.
	 *
	 * @param	array	$positions	array('blk1' => array('pos1', 'pos2', ...), ...)
	 * @return	boolean
	 */
	static public function setStatus($positions)
	{
		$status = new stdClass();

		// Gets required properties.
		$doc	= JFactory::getDocument();
		$params	= self::get('params');

		foreach ($positions AS $block => $innerPositions)
		{
			// Camelizes block name.
			$block = 'has'.ucfirst(preg_replace('/\-([a-z])/e', 'strtoupper(\'$1\')', $block)).'Block';

			// Presets block visibility status.
			$status->$block = 0;

			foreach ($innerPositions AS $position)
			{
				// Camelizes position name.
				$name = 'has'.ucfirst(preg_replace('/\-([a-z])/e', 'strtoupper(\'$1\')', $position)).'Pos';

				if ( ! isset($status->$name))
				{
					if ($params->get('gj_demo_full_layout'))
					{
						// Checks if this position is disabled by user.
						$status->$name = (int) ( ! isset($_COOKIE["gj-{$position}-position"]) OR $_COOKIE["gj-{$position}-position"] != 'disabled');

						if ( ! $status->$name)
						{
							JFactory::getApplication()->enqueueMessage(JText::sprintf('TPL_GJ_POSITION_DISABLED', $position, "\$GJ.cookie('gj-{$position}-position', '', -1); window.location.reload();"));
						}
					}
					else
					{
						// Uses Joomla API to detect positions visibility.
						$status->$name = (int) $doc->countModules($position);

						// Checks if this position has static content injection.
						if ( ! $status->$name AND $params->get("gj_{$position}") AND $params->get("gj_{$position}_inject") == 'if-empty')
						{
							$status->$name = 1;
						}
					}
				}

				// Updates block visibility status.
				$status->$block += $status->$name;
			}
		}

		self::$status = $status;
	}

	/**
	 * Entry method for getting template properties.
	 *
	 * @param	string	$property
	 * @param	string	$default
	 * @return	mixed
	 */
	static public function get($property, $default = NULL)
	{
		// Generates method name to handle get action.
		$method = 'get'.ucfirst($property);

		// Gets property value.
		if (method_exists(__CLASS__, $method))
		{
			return isset($default) ? self::$method($default) : self::$method();
		}
		elseif (isset(self::${$property}))
		{
			return self::${$property};
		}
		else
		{
			return $default;
		}
	}

	/**
	 * Retrieves template parameters.
	 *
	 * @param	none
	 * @return	mixed
	 */
	static public function getParams()
	{
		// Gets Joomla document object.
		$doc =& JFactory::getDocument();

		return $doc->params;
	}

	/**
	 * Retrieves template layout.
	 *
	 * @param	string	$default	Default layout
	 * @return	mixed
	 */
	static public function getLayout($default = 'desktop')
	{
		if ( ! isset(self::$layout))
		{
			// TODO: Detect client device type: desktop, tablet, smartphone or feature phone?
			$layout = $default;

			self::set('layout', $layout);
		}

		return self::$layout;
	}

	/**
	 * Retrieves template layout's theme.
	 *
	 * @param	string	$default	Default theme
	 * @return	mixed
	 */
	static public function getTheme($default = 'default')
	{
		if ( ! isset(self::$theme))
		{
			$params	= self::get('params');
			$layout	= self::get('layout');
			$theme	= $params->get("gj_{$layout}_theme", $default);

			self::set('theme', $theme);
		}

		return self::$theme;
	}

	/**
	 * Retrieves structural and content blocks visibility status.
	 *
	 * @param	array	$positions	array('blk1' => array('pos1', 'pos2', ...), ...)
	 * @return	void
	 */
	static public function getStatus($positions = array())
	{
		if ( ! isset(self::$status))
		{
			if (is_array($positions) AND count($positions))
			{
				self::set('status', $positions);
			}
			else
			{
				throw new Exception(JText::_('TPL_GJ_CONTAINERS_NOT_DECLARED'));
			}
		}

		return self::$status;
	}

	/**
	 * Entry method for injecting static content and position inclusion tag.
	 *
	 * @param	string	$position	Position name
	 * @param	string	$chrome		Module chrome
	 * @param	string	$attrib     Attributes for position inclusion tag, e.g. 'a1="v1" a2="2"'
	 * @return	void
	 */
	static public function inject($position, $chrome = 'GJModule', $attrib = NULL)
	{
		// Generates method name to handle inject action.
		$method = 'inject'.ucfirst($position);

		// Generates position inclusion tag.
		$include = '<jdoc:include type="modules" name="'.$position.'" style="'.$chrome.'" '.$attrib.' />';

		if (method_exists(__CLASS__, $method))
		{
			$html = self::$method($include);
		}
		else
		{
			$html = self::injectComponent($position, $include);
		}

		// Gets template parameters.
		$params = self::get('params');

		if ($params->get('gj_demo_full_layout'))
		{
			// Gets required properties.
			$doc	= JFactory::getDocument();
			$path	= self::get('path');

			if (
				$html == $include
				AND
				! $doc->countModules($position)
				AND
				! file_exists($path.DS.'images'.DS.'preview'.DS.$position.'.gif')
			) {
				// Generates sample module.
				$module = new stdClass;
				$module->title		= JText::_('TPL_GJ_SAMPLE_MODULE_TITLE');
				$module->content	= JText::_('TPL_GJ_SAMPLE_MODULE_CONTENT');
				$module->showtitle	= TRUE;

				$attribs = array();
				if ($attrib) {
					parse_str(
						preg_replace(
							array(
								'/([^\s^=]+)\s*=\s*["\']([^"^\']+)["\']/e',
								'/\s+/'
							),
							array(
								'\'$1=\'.urlencode(trim(\'$2\'))',
								'&'
							),
							$attrib
						),
						$attribs
					);
				}
				$attribs['style'] = $chrome;

				jimport('joomla.application.module.helper');
				$html = JModuleHelper::renderModule($module, $attribs);
			}

			// Generates HTML code for position preview.
			$html .= '<div class="gj-overlay"></div>';

			if (file_exists($path.DS.'images'.DS.'preview'.DS.$position.'.gif'))
			{
				$size = getimagesize(JPATH_BASE.DS.'templates'.DS.$doc->template.DS.'images'.DS.'preview'.DS.$position.'.gif');
				$html = $html.'<img class="gj-position-name" src="'.$doc->baseurl.'/templates/'.$doc->template.'/images/preview/'.$position.'.gif" '.$size[3].' />';
			}
			else
			{
				$html .= '<span class="gj-position-name">'.$position.'</span>';
			}

			$html	= '<div class="gj-position-preview-ground"><div id="gj-'.$position.'-pos-preview" class="clearfix gj-position-preview">'
					. $html
					. '<a class="close-button" title="'.JText::_('TPL_GJ_DISABLE_POSITION').'" onclick="$GJ.cookie(\'gj-'.$position.'-position\', \'disabled\'); window.location.reload();">'.JText::_('TPL_GJ_CLOSE').'</a>'
					. '</div></div>';
		}

		echo $html;
	}

	/**
	 * Generates then injects logo.
	 *
	 * @param	string	$include	Position inclusion tag
	 * @return	void
	 */
	static public function injectLogo($include)
	{
		// Gets required properties.
		$doc	= JFactory::getDocument();
		$status	= (int) $doc->countModules('logo');
		$params	= self::get('params');
		$logo	= $params->get('gj_logo');

		if ($logo)
		{
			$logo	= substr($logo, 0, 1) == '/' ? $logo : $doc->baseurl.'/'.$logo;
			$title	= $params->get('gj_logo_title');
			$slogan	= $params->get('gj_logo_slogan');
			$method	= $params->get('gj_logo_inject');

			// Generates HTML code for logo injection.
			$inject	= '<h1 class="gj-logo-heading hidden">'.JText::sprintf('TPL_GJ_LOGO_HEADING', $title, $slogan).'</h1>'
					. '<a href="'.$doc->baseurl.'" title="'.$slogan.'">'
					. '<img src="'.$logo.'" alt="'.$slogan.'"></a>';

			// Injects generated HTML code.
			return self::finalizeInject($include, $inject, $method, $status);
		}

		return $include;
	}

	/**
	 * Generates then injects personalization panel.
	 *
	 * @param	string	$include	Position inclusion tag
	 * @return	void
	 */
	static public function injectPersonalize($include)
	{
		// Gets required properties.
		$doc	= JFactory::getDocument();
		$status	= (int) $doc->countModules('personalize');
		$params	= self::get('params');
		$method	= $params->get('gj_personalize_inject');

		// Generates HTML code for personalization panel injection.
		$inject = JText::_('TPL_GJ_PERSONALIZE');

		// Injects generated HTML code.
		return self::finalizeInject($include, $inject, $method, $status);
	}

	/**
	 * Injects component output.
	 *
	 * @param	string	$position	Position name
	 * @param	string	$include	Position inclusion tag
	 * @return	void
	 */
	static public function injectComponent($position, $include) {
		// Gets required properties.
		$doc	= JFactory::getDocument();
		$status	= (int) $doc->countModules('logo');
		$params	= self::get('params');
		$layout	= self::get('layout');

		if ($params->get('gj_'.$layout.'_component_mapping') == $position)
		{
			$inject = '<div id="gj-component-output-ground"><div id="gj-component-output"><jdoc:include type="component" /></div></div>';
			$method = $params->get('gj_'.$layout.'_component_inject');

			// Injects component inclusion tag.
			return self::finalizeInject($include, $inject, $method, $status);
		}

		return $include;
	}

	/**
	 * Finalizes injection code for a position.
	 *
	 * @param	string	$include	Position inclusion tag
	 * @param	string	$inject		Extra code for injection
	 * @param	string	$method		Inclusion method
	 * @param	integer	$status		Counted number of modules
	 * @return	string
	 */
	static public function finalizeInject($include, $inject, $method, $status)
	{
		if ($method == 'if-empty' AND ! $status)
		{
			return $inject."\n";
		}
		elseif ($method == 'always-prepend' OR ($method == 'prepend-if-not-empty' AND $status))
		{
			return $inject."\n".$include;
		}
		elseif ($method == 'always-append' OR ($method == 'append-if-not-empty' AND $status))
		{
			return $include."\n".$inject."\n";
		}

		return $include;
	}

	/**
	 * Renders template then outputs.
	 *
	 * @param	boolean	$gzip	If set to TRUE, compress all .css and .js files
	 * @param	integer	$cache	Minutes for caching the compressed file in client's browser
	 * @return	void
	 */
	static public function render($gzip = TRUE, $cache = 60)
	{
		// Gets required properties.
		$doc	= JFactory::getDocument();
		$params	= self::get('params');
		$path	= self::get('path');
		$layout	= self::get('layout');
		$theme	= self::get('theme');

		// Initializes either the requested or default theme of the requested layout.
		if (is_readable($path.DS.'layouts'.DS.$layout.DS.$theme.'.php'))
		{
			require $path.DS.'layouts'.DS.$layout.DS.$theme.'.php';
		}
		elseif (is_readable($path.DS.'layouts'.DS.$layout.DS.'default.php'))
		{
			require $path.DS.'layouts'.DS.$layout.DS.'default.php';
		}

		// Renders either the requested or default theme of the requested layout.
		if (is_readable($path.DS.'layouts'.DS.$layout.DS.$theme.'.phtml'))
		{
			require $path.DS.'layouts'.DS.$layout.DS.$theme.'.phtml';
		}
		elseif (is_readable($path.DS.'layouts'.DS.$layout.DS.'default.phtml'))
		{
			require $path.DS.'layouts'.DS.$layout.DS.'default.phtml';
		}
		else
		{
			throw new Exception(JText::sprintf('TPL_GJ_THEME_NOT_FOUND', $theme, $layout));
		}
	}
}
?>
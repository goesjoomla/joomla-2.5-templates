<?php
/**
 * GoesJoomla template framework.
 *
 * Initializes and renders Joomla 2.5 template.
 *
 * @copyright	Copyright (C) 2012 Manh-Cuong Nguyen. All rights reserved.
 * @author		Manh-Cuong Nguyen <cuongnm@goesjoomla.com>
 * @license		Visit http://www.goesjoomla.com/licenses.html for details.
 * @link		http://www.goesjoomla.com/templates.html
 */

/**
 * Steps for creating new Joomla 2.5 template based on GoesJoomla template framework:
 *
 * TODO: 1. Edits templateDetails.xml file to replace all GJ_Framework occurrences with new template name then changes the string TPL_GJ_FRAMEWORK_XML_DESCRIPTION accordingly.
 * TODO: 2. Edits defines.php file to replace all GJ_Framework occurrences with new template name.
 * TODO: 3. Edits language/en-GB/en-GB.tpl_GJ_Framework.sys.ini to replace all GJ_FRAMEWORK occurrences with uppercased new template name.
 * TODO: 4. Renames all language files according to new template name.
 * TODO: 5. Edits layouts/desktop/default.css to represent new template's default desktop layout design.
 */

// No direct access.
defined('_JEXEC') or die;

// Declares template parameters.
require dirname(__FILE__).DS.'defines.php';

// Adds common stylesheets to document head.
JHtml::_('stylesheet', 'templates/'.$this->template.'/css/reset.css');
JHtml::_('stylesheet', 'templates/'.$this->template.'/css/typography.css');

// Adds common Javascript file to document head.
JHtml::_('script', 'templates/'.$this->template.'/js/gj.js');

// TODO: Adds common inline style declaration to document head.

// TODO: Adds common inline script declaration to document head.

// Initializes base template class then renders.
require dirname(__FILE__).DS.'includes'.DS.'template.php';

GJTemplate::set('path', dirname(__FILE__));
GJTemplate::render();
?>
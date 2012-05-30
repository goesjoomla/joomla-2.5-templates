<?php
/**
 * GoesJoomla template framework.
 *
 * Defines default value for all template parameters.
 *
 * @copyright	Copyright (C) 2012 Manh-Cuong Nguyen. All rights reserved.
 * @author		Manh-Cuong Nguyen <cuongnm@goesjoomla.com>
 * @license		Visit http://www.goesjoomla.com/licenses.html for details.
 * @link		http://www.goesjoomla.com/templates.html
 */

// No direct access.
defined('_JEXEC') or die;

// Declares development parameters.
$this->params->def('gj_demo_full_layout',	1);

// Declares parameters for logo generation.
$this->params->def('gj_logo',			'templates/GJ_Framework/images/logo.png');
$this->params->def('gj_logo_title',		$this->title);
$this->params->def('gj_logo_slogan',	JText::_('TPL_GJ_ENTER_YOUR_SLOGAN_HERE'));
$this->params->def('gj_logo_inject',	'if-empty');

// Declares typography parameters.
$this->params->def('gj_typography_text_style',	'business');
$this->params->def('gj_typography_text_size',	'small');
$this->params->def('gj_typography_line_height',	'1.5');

// Declares desktop layout specific parameters.
$this->params->def('gj_desktop_theme',				'default');

$this->params->def('gj_desktop_width',				'narrow');
$this->params->def('gj_desktop_width_narrow',		'960px');
$this->params->def('gj_desktop_width_wide',			'1150px');
$this->params->def('gj_desktop_width_fluid',		'90%');

$this->params->def('gj_desktop_promo_left_width',	'23%');
$this->params->def('gj_desktop_promo_right_width',	'23%');
$this->params->def('gj_desktop_left_col_width',		'23%');
$this->params->def('gj_desktop_right_col_width',	'23%');
$this->params->def('gj_desktop_inner_left_width',	'23%');
$this->params->def('gj_desktop_inner_right_width',	'23%');

$this->params->def('gj_desktop_component_mapping',	'main-body');
$this->params->def('gj_desktop_component_inject',	'if-empty');

// Declares parameters for personalization settings injection.
$this->params->def('gj_personalize',					1);
$this->params->def('gj_personalize_font_resizer',		1);
$this->params->def('gj_personalize_width_switcher',		1);
$this->params->def('gj_personalize_theme_switcher',		1);
$this->params->def('gj_personalize_layout_switcher',	1);
$this->params->def('gj_personalize_inject',				'if-empty');
?>
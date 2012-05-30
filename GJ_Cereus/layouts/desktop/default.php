<?php
/**
 * GoesJoomla template framework.
 *
 * Initializes default theme for desktop layout.
 *
 * @copyright	Copyright (C) 2012 Manh-Cuong Nguyen. All rights reserved.
 * @author		Manh-Cuong Nguyen <cuongnm@goesjoomla.com>
 * @license		Visit http://www.goesjoomla.com/licenses.html for details.
 * @link		http://www.goesjoomla.com/templates.html
 */

// No direct access.
defined('_JEXEC') or die;

// Adds theme specific stylesheets to document head.
JHtml::_('stylesheet', 'templates/'.$doc->template.'/css/plugins/layout.css');
JHtml::_('stylesheet', 'templates/'.$doc->template.'/css/plugins/menu.css');

JHtml::_('stylesheet', 'templates/'.$doc->template.'/layouts/desktop/default.css');
JHtml::_('stylesheet', 'templates/'.$doc->template.'/layouts/desktop/default-menu.css');
JHtml::_('stylesheet', 'templates/'.$doc->template.'/layouts/desktop/default-module.css');

if ($params->get('gj_demo_full_layout'))
{
	JHtml::_('stylesheet', 'templates/'.$doc->template.'/layouts/desktop/default-preview.css');
}

// Adds theme specific Javascript file to document head.
JHtml::_('behavior.framework', TRUE);
JHtml::_('script', 'templates/'.$doc->template.'/layouts/desktop/default.js');

// Adds theme specific inline style declaration to document head.
$doc->addStyleDeclaration(
'	body {
		font-size: '.$params->get('gj_typography_text_size').';
		line-height: '.(float) $params->get('gj_typography_line_height').'em;
	}
	h1 {
		margin-bottom: '.round((float) $params->get('gj_typography_line_height') / 2.25, 3).'em;
	}
	h2 {
		margin-bottom: '.round((float) $params->get('gj_typography_line_height') / 2, 3).'em;
	}
	h3 {
		margin-bottom: '.round((float) $params->get('gj_typography_line_height') / 1.75, 3).'em;
	}
	h4 {
		margin-bottom: '.round((float) $params->get('gj_typography_line_height') / 1.5, 3).'em;
	}
	h5, h6 {
		margin-bottom: '.round((float) $params->get('gj_typography_line_height') / 1.25, 3).'em;
	}
	.gj-module-ground {
		margin: '.round((float) $params->get('gj_typography_line_height') / 2, 3).'em 0.5em;
	}
	#gj-content-ground {
		margin-bottom: '.round((float) $params->get('gj_typography_line_height') / 2, 3).'em;
	}

	body.gj-narrow-width .gj-container {
		width: '.(int) $params->get('gj_desktop_width_narrow').'px;
	}
	body.gj-wide-width .gj-container {
		width: '.(int) $params->get('gj_desktop_width_wide').'px;
	}
	body.gj-fluid-width .gj-container {
		width: '.(float) $params->get('gj_desktop_width_fluid').'%;
	}

	.gj-promo-left-column {
		width: '.(float) $params->get('gj_desktop_promo_left_width').'%;
	}
	.gj-promo-right-column {
		width: '.(float) $params->get('gj_desktop_promo_right_width').'%;
	}
	.gj-promo-center-column {
		width: '.(100 - (float) $params->get('gj_desktop_promo_left_width') - (float) $params->get('gj_desktop_promo_right_width')).'%;
	}
	.gj-wide-promo-left-column {
		width: '.(100 - (float) $params->get('gj_desktop_promo_right_width')).'%;
	}
	.gj-wide-promo-right-column {
		width: '.(100 - (float) $params->get('gj_desktop_promo_left_width')).'%;
	}

	.gj-left-column {
		width: '.(float) $params->get('gj_desktop_left_col_width').'%;
	}
	.gj-right-column {
		width: '.(float) $params->get('gj_desktop_right_col_width').'%;
	}
	.gj-center-column {
		width: '.(100 - (float) $params->get('gj_desktop_left_col_width') - (float) $params->get('gj_desktop_right_col_width')).'%;
	}
	.gj-wide-left-column {
		width: '.(100 - (float) $params->get('gj_desktop_right_col_width')).'%;
	}
	.gj-wide-right-column {
		width: '.(100 - (float) $params->get('gj_desktop_left_col_width')).'%;
	}

	#gj-left-pos.gj-left-column {
		left: -'.(100 - (float) $params->get('gj_desktop_left_col_width') - (float) $params->get('gj_desktop_right_col_width')).'%;
	}
	#gj-menu-left-pos.gj-center-column,
	#gj-center-ground.gj-center-column {
		left: '.(float) $params->get('gj_desktop_left_col_width').'%;
	}
	#gj-center-ground.gj-wide-left-column {
		left: auto;
	}
	#gj-center-ground.gj-wide-right-column + #gj-left-pos.gj-left-column {
		left: auto;
	}

	.gj-inner-left-column {
		width: '.(float) $params->get('gj_desktop_inner_left_width').'%;
	}
	.gj-inner-right-column {
		width: '.(float) $params->get('gj_desktop_inner_right_width').'%;
	}
	.gj-inner-center-column {
		width: '.(100 - (float) $params->get('gj_desktop_inner_left_width') - (float) $params->get('gj_desktop_inner_right_width')).'%;
	}
	.gj-wide-inner-left-column {
		width: '.(100 - (float) $params->get('gj_desktop_inner_right_width')).'%;
	}
	.gj-wide-inner-right-column {
		width: '.(100 - (float) $params->get('gj_desktop_inner_left_width')).'%;
	}

	#gj-inner-left-pos.gj-inner-left-column {
		left: -'.(100 - (float) $params->get('gj_desktop_inner_left_width') - (float) $params->get('gj_desktop_inner_right_width')).'%;
	}
	#gj-body-ground.gj-inner-center-column {
		left: '.(float) $params->get('gj_desktop_inner_left_width').'%;
	}
	#gj-body-ground.gj-wide-inner-left-column {
		left: auto;
	}
	#gj-body-ground.gj-wide-inner-right-column + #gj-inner-left-pos.gj-inner-left-column {
		left: auto;
	}'
);

// TODO: Adds theme specific inline script declaration to document head.

// Declares then detects positions and blocks visibility.
$positions = array(
	'header'			=> array('logo', 'personalize', 'menu-left', 'menu-right'),
	'menu'				=> array('menu-left', 'menu-right'),
	'content-top'		=> array('promo-left', 'promo', 'promo-right', 'content-top'),
	'promo'				=> array('promo-left', 'promo', 'promo-right'),
	'content'			=> array('left', 'inner-left', 'breadcrumbs', 'user-top', 'user1', 'user2', 'main-body-top', 'main-body', 'main-body-bottom', 'user3', 'user4', 'user-bottom', 'banner', 'inner-right', 'right'),
	'center'			=> array('inner-left', 'breadcrumbs', 'user-top', 'user1', 'user2', 'main-body-top', 'main-body', 'main-body-bottom', 'user3', 'user4', 'user-bottom', 'banner', 'inner-right'),
	'body'				=> array('breadcrumbs', 'user-top', 'user1', 'user2', 'main-body-top', 'main-body', 'main-body-bottom', 'user3', 'user4', 'user-bottom', 'banner'),
	'content-bottom'	=> array('content-bottom', 'user5', 'user6', 'user7'),
	'additional'		=> array('user5', 'user6', 'user7'),
	'fixed'				=> array('fixed-left-top', 'fixed-left-middle', 'fixed-left-bottom', 'fixed-right-top', 'fixed-right-middle', 'fixed-right-bottom'),
	'footer'			=> array('footer-left', 'footer-right'),
	'debug'				=> array('debug')
);

$status = GJTemplate::get('status', $positions);
?>
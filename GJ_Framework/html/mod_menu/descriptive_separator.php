<?php
/**
 * GoesJoomla template framework.
 *
 * Descriptive menu alternative layout for Joomla 2.5 menu rendering.
 *
 * @copyright	Copyright (C) 2012 Manh-Cuong Nguyen. All rights reserved.
 * @author		Manh-Cuong Nguyen <cuongnm@goesjoomla.com>
 * @license		Visit http://www.goesjoomla.com/licenses.html for details.
 * @link		http://www.goesjoomla.com/templates.html
 */

// No direct access.
defined('_JEXEC') or die;

// NOTE: It is important to remove spaces between elements.
if (@$item->isRichText)
{
	$linktype = '<span><span class="item-title">'.$item->title.'</span><span class="item-description">'.$item->anchor_title.'</span></span>';
}
else
{
	$linktype = '<span>'.$item->title.'</span>';
}

if ($item->menu_image)
{
	$linktype = '<img src="'.$item->menu_image.'" alt="'.$item->title.'" />'.($item->params->get('menu_text', 1) ? $linktype : '');
}

?><span class="separator"><?php echo $linktype; ?></span>

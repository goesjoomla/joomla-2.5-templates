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
$class = $item->anchor_css   ? ' class="'.$item->anchor_css.'"'   : '';
$title = $item->anchor_title ? ' title="'.$item->anchor_title.'"' : '';

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

jimport('joomla.filter.output');
$flink = JFilterOutput::ampReplace(htmlspecialchars($item->flink));

switch ($item->browserNav) {
	default:
	case 0:
?><a<?php echo $class.$title; ?> href="<?php echo $flink; ?>"><?php echo $linktype; ?></a><?php
	break;

	case 1:
		// _blank
?><a<?php echo $class.$title; ?> href="<?php echo $flink; ?>" target="_blank"><?php echo $linktype; ?></a><?php
	break;

	case 2:
		// window.open
		$options = 'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,'.$params->get('window_open');
?><a<?php echo $class.$title; ?> href="<?php echo $flink; ?>" onclick="window.open(this.href,'targetWindow','<?php echo $options;?>');return false;"><?php echo $linktype; ?></a><?php
	break;
}
?>
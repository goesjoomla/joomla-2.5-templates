<?php
/**
 * GoesJoomla template framework.
 *
 * Suckerfish menu alternative layout for Joomla 2.5 menu rendering.
 *
 * @copyright	Copyright (C) 2012 Manh-Cuong Nguyen. All rights reserved.
 * @author		Manh-Cuong Nguyen <cuongnm@goesjoomla.com>
 * @license		Visit http://www.goesjoomla.com/licenses.html for details.
 * @link		http://www.goesjoomla.com/templates.html
 */

// No direct access.
defined('_JEXEC') or die;

// NOTE: It is important to remove spaces between elements.
?>
<ul<?php echo $params->get('tag_id') != NULL ? ' id="'.$params->get('tag_id').'"' : ''; ?> class="menu sf-menu<?php if ($class_sfx) echo substr($class_sfx, 0, 1) == ' ' ? $class_sfx : ' sf-menu'.$class_sfx; ?>"><?php
foreach ($list as $i => &$item)
{
	// Generates class attribute.
	$class = 'item-'.$item->id.' '.strtolower(preg_replace('/[^A-Za-z0-9]+/', '-', html_entity_decode($item->title)));

	if ($item->id == $active_id)
	{
		$class .= ' current';
	}

	if (in_array($item->id, $path))
	{
		$class .= ' active';
	}
	elseif ($item->type == 'alias')
	{
		$aliasToId = $item->params->get('aliasoptions');
		if (count($path) > 0 && $aliasToId == $path[count($path)-1])
		{
			$class .= ' active';
		}
		elseif (in_array($aliasToId, $path))
		{
			$class .= ' alias-parent-active';
		}
	}

	if ($item->deeper)
	{
		$class .= ' deeper';
	}

	if ($item->parent)
	{
		$class .= ' parent';
	}

	if ( ! empty($class))
	{
		$class = ' class="'.trim($class).'"';
	}

	echo '<li'.$class.'>';

	// Render the menu item.
	switch ($item->type)
	{
		case 'component':
		case 'separator':
		case 'url':
			require JModuleHelper::getLayoutPath('mod_menu', 'descriptive_'.$item->type);
		break;

		default:
			require JModuleHelper::getLayoutPath('mod_menu', 'descriptive_url');
		break;
	}

	// The next item is deeper.
	if ($item->deeper)
	{
		echo '<ul>';
	}
	// The next item is shallower.
	elseif ($item->shallower)
	{
		echo '</li>';
		echo str_repeat('</ul></li>', $item->level_diff);
	}
	// The next item is on the same level.
	else
	{
		echo '</li>';
	}
}
?></ul>
<script type="text/javascript">new $GJ.template.initializeMenu();</script>

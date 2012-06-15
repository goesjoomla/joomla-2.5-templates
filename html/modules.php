<?php
/**
 * GoesJoomla template framework.
 *
 * Declares module chrome rendering function.
 *
 * @copyright	Copyright (C) 2012 Manh-Cuong Nguyen. All rights reserved.
 * @author		Manh-Cuong Nguyen <cuongnm@goesjoomla.com>
 * @license		Visit http://www.goesjoomla.com/licenses.html for details.
 * @link		http://www.goesjoomla.com/templates.html
 */

// No direct access.
defined('_JEXEC') or die;

function modChrome_GJModule($module, &$params, &$attribs) {
	// Initialize module counter.
	$doc =& JFactory::getDocument();
	$attribs['counted'] = isset($attribs['counted']) ? $attribs['counted']   : $doc->countModules($attribs['name']);
	$attribs['current'] = isset($attribs['current']) ? $attribs['current']++ : 1;

	// Initialize module rendering attributes.
	$hideIfEmpty = isset($attribs['hide-if-empty']) ? (bool) $attribs['hide-if-empty'] : TRUE;
	$hideHeading = isset($attribs['hide-heading'])  ? (bool) $attribs['hide-heading']  : FALSE;
	$isHorizMods = isset($attribs['is-horizontal']) ? (bool) $attribs['is-horizontal'] : FALSE;

	// Initialize module class suffix.
	$classSuffix = htmlspecialchars($params->get('moduleclass_sfx'));
	if (empty($classSuffix) AND isset($attribs['class-suffix']))
	{
		$classSuffix = $attribs['class-suffix'];
	}
	if (substr($classSuffix, 0, 1) == '+') {
		$classSuffix = ' '.substr($classSuffix, 1);
	}
	( ! $module->showtitle OR $hideHeading)    AND $classSuffix .= ' no-heading';
	$attribs['current'] == 1                   AND $classSuffix .= ' first';
	$attribs['current'] >= $attribs['counted'] AND $classSuffix .= ' last';

	// Render module now.
	if ( ! $hideIfEmpty OR ! empty($module->content)) :
		if ($isHorizMods) : ?>
<div class="column"><?php endif; ?><div class="gj-module-ground<?php if ( ! empty($classSuffix)) echo substr($classSuffix, 0, 1) == ' ' ? $classSuffix : ' gj-module-ground'.$classSuffix; ?>">
	<div class="gj-module<?php if ( ! empty($classSuffix)) echo substr($classSuffix, 0, 1) == ' ' ? $classSuffix : ' gj-module'.$classSuffix; ?>">
<?php
		if ( ! $hideHeading AND $module->showtitle) : ?>
		<div class="gj-module-heading<?php if ( ! empty($classSuffix)) echo substr($classSuffix, 0, 1) == ' ' ? $classSuffix : ' gj-module-heading'.$classSuffix; ?>">
<?php
			$headingLevel = isset($attribs['heading-level']) ? (int) $attribs['heading-level'] : 3;
			echo "<h{$headingLevel} class=\"gj-module-title\">{$module->title}</h{$headingLevel}>\n";
?>
		</div>
<?php
		endif; ?>
		<div class="clearfix gj-module-content<?php if ( ! empty($classSuffix)) echo substr($classSuffix, 0, 1) == ' ' ? $classSuffix : ' gj-module-content'.$classSuffix; ?>">
<?php
			echo trim($module->content)."\n"; ?>
		</div>
	</div>
<?php
		if ($isHorizMods) : ?>
</div><?php endif; ?></div>
<?php
	endif;
}
?>
/**
 * GoesJoomla template framework.
 *
 * Declares script for desktop layout's default theme.
 *
 * @copyright	Copyright (C) 2012 Manh-Cuong Nguyen. All rights reserved.
 * @author		Manh-Cuong Nguyen <cuongnm@goesjoomla.com>
 * @license		Visit http://www.goesjoomla.com/licenses.html for details.
 * @link		http://www.goesjoomla.com/templates.html
 */

$GJ.template.initializeMenu = $GJ.c.extend({
	init: function(options) {
		this.options = $GJ.set.options(options, {
			centralizeSub: true,
			transitionFx: true,
			hideDelay: 500,
			classes: {
				dropDown: 'sf-menu',
				fitParent: 'gj-button-menu'
			}
		});

		this.menus = {};
		var lists = document.getElementsByTagName('ul');
		$GJ.a.each(lists, $GJ.f.bind(this, function(list) {
			for (var c in this.options.classes) {
				if (list.className.indexOf(this.options.classes[c]) > -1) {
					this.menus[c] = this.menus[c] || [];
					this.menus[c].push(list);
				}
			}
		}));
		for (var c in this.options.classes) { this.menus[c] && this[c](c); }
	},

	dropDown: function(c) {
		var toggle = $GJ.f.bind(this, function(root, item, state, handler, event) {
			event = event || window.event;

			// Get item's hover state
			var hovered = $GJ.el.hasClass(item, 'mouseover');

			if (state) {
				if (hovered && (this.options.hideDelay || this.options.transitionFx)) {
					// Check if sub-menu is queued for hiding
					root.hideQueue = root.hideQueue || {};
					if (root.hideQueue.items) {
						var i = $GJ.a.contain(root.hideQueue.items, item);
						if (i > -1) {
							clearTimeout(root.hideQueue.timer[i]);
							delete root.hideQueue.timer[i];
							delete root.hideQueue.items[i];
						}
					}
				}

				if (!hovered) {
					$GJ.el.addClass(item, 'mouseover');
					item.lastChild.nodeName != 'UL' || handler(root, item);
				}
			} else if (!state && hovered && $GJ.ev.isMouseOut(item, event)) {
				if (item.lastChild.nodeName == 'UL') {
					if (this.options.hideDelay || this.options.transitionFx) {
						// Calculate hide ordering
						root.shown = root.shown || [];
						root.hideOrder = (root.shown.length - 1) - $GJ.a.contain(root.shown, item);
						this.options.hideDelay && root.hideOrder++;
					}

					if (root.hideOrder) {
						root.hideQueue = root.hideQueue || {};

						root.hideQueue.timer = root.hideQueue.timer || [];
						if (root.hideQueue.timer[root.hideOrder]) {
							clearTimeout(root.hideQueue.timer[root.hideOrder]);
						}
						root.hideQueue.timer[root.hideOrder] = setTimeout(
							$GJ.f.pass(handler, [root, item, root.hideOrder]),
							(this.options.transitionFx ? root.hideOrder : 1)
							*
							(this.options.hideDelay ? this.options.hideDelay : 500)
						);

						root.hideQueue.items = root.hideQueue.items || [];
						root.hideQueue.items[root.hideOrder] = item;
					} else {
						handler(root, item);
					}
				} else {
					$GJ.el.removeClass(item, 'mouseover');
				}
			}
		}),
		show = $GJ.f.bind(this, function(root, item) {
			// Centralize second level sub-menu
			if (this.options.centralizeSub && !item.gjCentralized && item.parentNode == root) {
				var cur, alter;
				cur = isNaN(cur = parseFloat($GJ.el.getStyle(item.lastChild, 'margin-left'))) ? 0 : cur;
				alter = (item.lastChild.offsetWidth - item.offsetWidth) / 2;

				$GJ.el.setStyle(item.lastChild, 'margin-left', (cur - alter) + 'px');
				item.gjCentralized = true;
			}

			// Create transition effect
			if (this.options.transitionFx) {
				// Cancel hiding finalization timer
				if (item.onHideCompleteTimer) {
					clearTimeout(item.onHideCompleteTimer);
					delete item.onHideCompleteTimer;
				}

				// It's time to show sub-menu
				$GJ.el.setStyle(item.lastChild, {left: 'auto', opacity: 1, overflow: 'hidden'});
				$GJ.el.setStyle(
					item.lastChild,
					item.parentNode == root
					? {height: item.gjSubMenuDimension.h}
					: {width: item.gjSubMenuDimension.w}
				);

				// Schedule showing finalization
				item.onShowCompleteTimer = setTimeout(
					$GJ.f.pass(function(item) {
						$GJ.el.setStyle(item.lastChild, 'overflow', 'visible');
					}, [item]),
					500
				);
			}

			root.shown = root.shown || [];
			$GJ.a.contain(root.shown, item) > -1 || root.shown.push(item);
		}),
		hide = $GJ.f.bind(this, function(root, item, hideOrder) {
			$GJ.el.removeClass(item, 'mouseover');

			// Create transition effect
			if (this.options.transitionFx) {
				// Cancel showing finalization timer
				if (item.onShowCompleteTimer) {
					clearTimeout(item.onShowCompleteTimer);
					delete item.onShowCompleteTimer;
				}

				// It's time to hide sub-menu
				$GJ.el.setStyle(item.lastChild, {left: 'auto', opacity: 0, overflow: 'hidden'});
				$GJ.el.setStyle(
					item.lastChild,
					item.parentNode == root ? {height: 0} : {width: 0}
				);

				// Schedule hiding finalization
				item.onHideCompleteTimer = setTimeout(
					$GJ.f.pass(function(root, item) {
						$GJ.el.setStyle(item.lastChild, 'left', '-999em');

						// Update list of showing sub-menu
						root.shown = root.shown || [];
						var i = $GJ.a.contain(root.shown, item);
						i < 0 || root.shown.splice(i, 1);
					}, [root, item]),
					500
				);
			}

			// Update hiding queue if necessary
			if (
				hideOrder
				&&
				(root.hideQueue.timer[hideOrder] || root.hideQueue.items[hideOrder])
			) {
				delete root.hideQueue.timer[hideOrder];
				delete root.hideQueue.items[hideOrder];
			}
		});

		$GJ.a.each(this.menus[c], $GJ.f.bind(this, function(menu) {
			var items = menu.getElementsByTagName('li');
			$GJ.a.each(items, $GJ.f.bind(this, function(menu, item) {
				if (this.options.transitionFx && item.lastChild.nodeName == 'UL') {
					// Transition effect initialization
					item.gjSubMenuDimension = {
						w: $GJ.el.getStyle(item.lastChild, 'width'),
						h: $GJ.el.getStyle(item.lastChild, 'height')
					};
	
					$GJ.el.addClass(item.lastChild, 'transition-fx dimension');
					$GJ.el.setStyle(item.lastChild, 'opacity', 0);
					$GJ.el.setStyle(
						item.lastChild,
						item.parentNode == menu ? {height: 0} : {width: 0}
					);
				}

				$GJ.ev.add(item, 'mouseover', $GJ.f.pass(toggle, [menu, item, true, show]));
				$GJ.ev.add(item, 'mouseout', $GJ.f.pass(toggle, [menu, item, false, hide]));
			}, menu));
		}));
	},

	fitParent: function(c) {
		$GJ.a.each(this.menus[c], $GJ.f.bind(this, function(menu) {
			var w = this.calculateWidth(menu);
			$GJ.el.setStyle(
				menu,
				'width',
				(w <= menu.parentNode.offsetWidth ? w : menu.parentNode.offsetWidth) + 'px'
			);
			if (w > menu.parentNode.offsetWidth) {
				var alter = (w - menu.parentNode.offsetWidth) / (menu.childNodes.length - 1);
				$GJ.a.each(menu.childNodes, $GJ.f.pass(function(max, alter, item, i) {
					if (i == max - 1) return;
					var cur;
					cur = isNaN(cur = parseFloat($GJ.el.getStyle(item, 'margin-right'))) ? 0 : cur;
					$GJ.el.setStyle(item, 'margin-right', (cur - alter) + 'px');
				}, [menu.childNodes.length, alter]));
				$GJ.el.setStyle(menu.lastChild, 'margin-right', 0);
			}
		}));
	},

	calculateWidth: function(menu) {
		if (!menu.gjCalculatedWidth) {
			var w = 0;
			$GJ.a.each(menu.childNodes, function(item) {
				var gutter = 0;
				for (var p in {'margin-left':'', 'margin-right':''}) {
					gutter += isNaN(p = parseFloat($GJ.el.getStyle(item, p))) ? 0 : p;
				}
				w += item.offsetWidth + gutter;
			});
			menu.gjCalculatedWidth = w;
		}
		return menu.gjCalculatedWidth;
	}
});

$GJ.template.initializeFixedPosition = $GJ.c.extend({
	init: function(where) {
		this.element = document.getElementById('gj-fixed-' + where + '-middle-pos');
		$GJ.ev.add(window, 'resize', $GJ.f.bind(this, this.position));
		this.position();
	},

	position: function() {
		var size = $GJ.get.view.size(), msg = document.getElementById('gj-system-message');
		msg && (size.h += msg.offsetHeight);
		$GJ.el.setStyle(this.element, 'top', ((size.h - this.element.offsetHeight) / 2) + 'px');
	}
});

$GJ.template.equalizeColumnHeight = $GJ.c.extend({
	init: function(container, columns) {
		container = document.getElementById(container);
		if (container) {
			this.columns = [];
			$GJ.a.each(columns, $GJ.f.bind(this, function(column) {
				this.columns.push(document.getElementById(column));
			}));

			container.getElementsByTagName('img').length
			? $GJ.ev.add(window, 'load', $GJ.f.bind(this, this.equalize))
			: this.equalize();
		}
	},

	equalize: function() {
		var maxHeight = 0;
		$GJ.a.each(this.columns, function(column) {
			maxHeight = (column && column.offsetHeight > maxHeight) ? column.offsetHeight : maxHeight;
		});

		$GJ.a.each(this.columns, function(column) {
			if (column && column.offsetHeight < maxHeight) {
				var divs = column.getElementsByTagName('div');
				for (var i = divs.length - 1; i >= 0; i--) {
					if (divs[i].className.match(/\bgj-module-content\b/)) {
						var gutter = 0, curHeight = parseFloat($GJ.el.getStyle(divs[i], 'height'));
						for (var p in {
							'margin-top':'', 'margin-bottom':'',
							'border-top-width':'', 'border-bottom-width':'',
							'padding-top':'', 'padding-bottom':''
						}) {
							gutter += isNaN(p = parseFloat($GJ.el.getStyle(divs[i], p))) ? 0 : p;
						}
						$GJ.el.setStyle(
							divs[i],
							'height',
							(divs[i].offsetHeight + (maxHeight - column.offsetHeight) - gutter) + 'px'
						);
						break;
					}
				}
			}
		});
	}
});

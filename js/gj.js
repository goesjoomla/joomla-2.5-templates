/**
 * GoesJoomla template framework.
 *
 * Declares core Javascript functions.
 *
 * @copyright	Copyright (C) 2012 Manh-Cuong Nguyen. All rights reserved.
 * @author		Manh-Cuong Nguyen <cuongnm@goesjoomla.com>
 * @license		Visit http://www.goesjoomla.com/licenses.html for details.
 * @link		http://www.goesjoomla.com/templates.html
 */

$GJ = {
	a: {
		merge: function(a1, a2) {
			var a = [];
			a1 = a1 || [];
			for (var i = 0; i < a1.length; i++) { a.push(a1[i]); }
			a2 = a2 || [];
			for (var i = 0; i < a2.length; i++) { a.push(a2[i]); }
			return a;
		},

		contain: function(a, v) {
			for (var i = 0; i < a.length; i++) { if (a[i] == v) return i; }
			return -1;
		},

		each: function(a, fn) {
			for (var i = 0; i < a.length; i++) { fn(a[i], i); }
		}
	},

	f: {
		bind: function(obj, fn) {
			var args = arguments.length > 2 ? Array.slice(arguments, 2) : [];
			return function() { fn.apply(obj, $GJ.a.merge(args, arguments)); };
		},

		pass: function(fn, args, obj) {
			return function() { fn.apply(obj, $GJ.a.merge(args, arguments)); };
		}
	},

	el: {
		hasClass: function(e, $class) {
			return e.className.match(new RegExp('\\b' + $class + '\\b'));
		},

		addClass: function(e, $class) {
			$GJ.el.hasClass(e, $class) || (e.className += (e.className == '' ? '' : ' ') + $class);
		},

		removeClass: function(e, $class) {
			e.className = e.className.replace(new RegExp('\\s*\\b' + $class + '\\b'), '');
		},

		toggleClass: function(e, c1, c2) {
			c2 = c2 || '';
			var m1 = new RegExp('\\b' + c1 + '\\b'), m2 = c2 != '' ? new RegExp('\\b' + c2 + '\\b') : '';
			if (e.className.match(m1)) {
				e.className = e.className.replace(m1, c2);
			} else if (c2 == '' || e.className.match(m2)) {
				c2 == '' ? $GJ.el.addClass(e, c1) : (e.className = e.className.replace(m2, c1));
			}
		},

		getStyle: function(e, p) {
			p = p.toLowerCase();
			var val = '', ccp = $GJ.get.camelCase(p), f;

			if (window.getComputedStyle) {
				// Non-IE
				val = document.defaultView.getComputedStyle(e, null).getPropertyValue(p);
			} else if (e.currentStyle) {
				if ('filters' in e && p == 'opacity') {
					val	= (f = e.style.filter || e.currentStyle.filter) && f.indexOf('opacity=') > -1
						? parseFloat(f.match(/opacity=([^)]*)/)[1]) / 100 : 1;
				} else {
					ccp = ccp.replace(/^float$/, 'styleFloat');
					val = e.currentStyle[ccp];
				}

				if (val == 'auto' && /^(width|height)$/.test(p) && e.currentStyle.display != 'none') {
					val = e['offset' + p.charAt(0).toUpperCase() + p.substr(1)] + 'px';
				}
			}

			return val;
		},

		setStyle: function(e, p, val) {
			var css = e.style, def = function(o) { return typeof o != 'undefined'; }, direct = { display: true };

			if ('filters' in e && (typeof p == 'string' ? /opacity/i.test(p) : def(p.opacity))) {
				css.zoom = 1;
				css.filter	= (css.filter || '').replace(/alpha\([^)]*\)/, '')
							+ 'alpha(opacity=' + (def(p.opacity) ? p.opacity : val) * 100 + ')';
			}

			if (def(css.cssText)) {
				var styleToSet = css.cssText;
				if (typeof p == 'object') {
					for (var i in p) {
						if (typeof i == 'string') {
							direct[i] && (css[i] = p[i]);
							styleToSet += ';' + i + ':' + p[i];
						}
					}
				} else {
					direct[p] && (css[p] = val);
					styleToSet += ';' + p + ':' + val;
				}
				css.cssText = styleToSet;
			}
		}
	},

	ev: {
		add: function(e, t, h) {
			if (e.addEventListener) {
				e.addEventListener(t, h, false);
			} else if (e.attachEvent) {
				e.attachEvent('on' + t, h);
			} else {
				var type = 'on' + t;
				e.$_events = e.$_events || {};
				e.$_events[type] = e.$_events[type] || [];

				if (!e.$_events[type].length) {
					var curHandler = e[type] ? e[type] : null;
					e[type] = $GJ.f.bind(e, $GJ.ev.trigger, type, curHandler);
				}

				e.$_events[type].push(h);
			}
		},
	
		remove: function(e, t, h) {
			if (e.removeEventListener) {
				e.removeEventListener(t, h, false);
			} else if (e.detachEvent) {
				e.detachEvent('on' + t, h);
			} else {
				var type = 'on' + t;
				if (e.$_events && e.$_events[type]) {
					var handlers = [];
					$GJ.a.each(e.$_events[type], function(handler) {
						handler.toString() == h.toString() || handlers.push(handler);
					});
					e.$_events[type] = handlers;
				}
			}
		},

		trigger: function(type, previousHandler, event) {
			event = event || ((this.ownerDocument || this.document || this).parentWindow || window).event;
			previousHandler && previousHandler.call(this, event);
			$GJ.a.each(this.$_events[type], $GJ.f.bind(this, function(handler) {
				handler && handler.call(this, event);
			}));
		},

		isMouseOut: function(src, event) {
			event = event || ((this.ownerDocument || this.document || this).parentWindow || window).event;
			var tg = event.relatedTarget || event.toElement;
			while (tg && tg != src && tg.nodeName != 'BODY') { tg = tg.parentNode; }
			return tg == src ? false : true;
		}
	},

	get: {
		camelCase: function(str) {
			return str.replace(/\-(\w)/g, function (match, char) { return char.toUpperCase(); });
		},

		view: {
			size: function() {
				var w = 0, h = 0;
				if (typeof window.getSize == 'function') {
					// Mootools
					var winSize = window.getSize();
					w = winSize.x;
					h = winSize.y;
				} else if (typeof window.innerWidth == 'number') {
					// Non-IE
					w = window.innerWidth;
					h = window.innerHeight;
				} else if (
					document.documentElement
					&&
					(document.documentElement.clientWidth || document.documentElement.clientHeight)
				) {
					// IE 6+ in 'standards compliant mode'
					w = document.documentElement.clientWidth;
					h = document.documentElement.clientHeight;
				} else if (document.body && (document.body.clientWidth || document.body.clientHeight)) {
					// IE 4 compatible
					w = document.body.clientWidth;
					h = document.body.clientHeight;
				}
				return {w: w, h: h};
			},

			scroll: function() {
				var t = 0, l = 0;
				if (typeof window.getScroll == 'function') {
					// Mootools
					var winScroll = window.getScroll();
					t = winScroll.y;
					l = winScroll.x;
				} else if (typeof window.pageYOffset == 'number') {
					// Netscape compliant
					t = window.pageYOffset;
					l = window.pageXOffset;
				} else if (document.body && (document.body.scrollLeft || document.body.scrollTop)) {
					// DOM compliant
					t = document.body.scrollTop;
					l = document.body.scrollLeft;
				} else if (
					document.documentElement
					&&
					(document.documentElement.scrollLeft || document.documentElement.scrollTop)
				) {
					// IE6 standards compliant mode
					t = document.documentElement.scrollTop;
					l = document.documentElement.scrollLeft;
				}
				return {t: t, l: l};
			}
		}
	},

	set: {
		options: function(options, $default) {
			options = options || {};
			for (var i in $default) {
				options[i]	= typeof $default[i] == 'object'
							? $GJ.set.options(options[i], $default[i])
							: (typeof options[i] == 'undefined' ? $default[i] : options[i]);
			}
			return options;
		}
	},

	cookie: function(name, value, days) {
		if (arguments.length == 1) {
			// Read cookie
			name = name + '=';
			var ca = document.cookie.split(';');
			for (var i = 0; i < ca.length; i++) {
				var c = ca[i];
				while (c.charAt(0) == ' ') { c = c.substring(1, c.length); }
				if (c.indexOf(name) == 0) {
					// Return cookie data
					return c.substring(name.length, c.length);
				}
			}
			return null;
		} else {
			// Write cookie
			var expires = '';
			if (typeof days != 'undefined') {
				var date = new Date();
				date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
				expires = '; expires=' + date.toGMTString();
			}
			document.cookie = name + '=' + value + expires + '; path=/';
		}
	}
};

/* Simple JavaScript Inheritance
 * By John Resig http://ejohn.org/
 * MIT Licensed.
 */
(function(){
	var initializing = false, fnTest = /xyz/.test(function(){xyz;}) ? /\b_super\b/ : /.*/;

	// The base class implementation (does nothing)
	$GJ.c = function(){};

	// Create a new class that inherits from this class
	$GJ.c.extend = function(proto) {
		var _super = this.prototype;

		// Instantiate a base class (but only create the instance, don't run the init constructor)
		initializing = true;
		var prototype = new this();
		initializing = false;

		// Copy the properties over onto the new prototype
		for (var name in proto) {
			// Check if we're overwriting an existing function
			if (typeof proto[name] == "function" && typeof _super[name] == "function" && fnTest.test(proto[name])) {
				prototype[name] = (function(name, fn){
					return function() {
						var tmp = this._super;

						// Add a new ._super() method that is the same method but on the super-class
						this._super = _super[name];

						// The method only need to be bound temporarily,
						// so we remove it when we're done executing
						var ret = fn.apply(this, arguments);			 
						this._super = tmp;

						return ret;
					};
				})(name, proto[name]);
			} else {
				prototype[name] = proto[name];
			}
		}

		// The dummy class constructor
		function $class() {
			// All construction is actually done in the init method
			!initializing && this.init && this.init.apply(this, arguments);
		}

		// Populate our constructed prototype object
		$class.prototype = prototype;

		// Enforce the constructor to be what we expect
		$class.prototype.constructor = $class;

		// And make this class extendable
		$class.extend = arguments.callee;

		return $class;
	};
})();

// Declares common classes for template initialization
$GJ.template = {
	goToTop: $GJ.c.extend({
		init: function(element) {
			this.element = typeof element == 'string' ? document.getElementById(element) : element;
			$GJ.ev.add(document, 'scroll', $GJ.f.bind(this, this.scroll));
			$GJ.ev.add(window, 'resize', $GJ.f.bind(this, this.position));
			$GJ.ev.add(this.element, 'click', $GJ.f.bind(this, this.click));
			this.position();
		},

		scroll: function() {
			var size = $GJ.get.view.size(), scroll = $GJ.get.view.scroll();
			scroll.t > (size.h / 2)	? $GJ.el.addClass(this.element, 'visible')
									: $GJ.el.removeClass(this.element, 'visible');
		},

		position: function() {
			var size = $GJ.get.view.size();
			$GJ.el.setStyle(this.element, 'left', ((size.w - this.element.offsetWidth) / 2) + 'px');
			this.scroll();
		},

		click: function() {
			if (typeof Fx.Scroll != 'undefined') {
				this.element.href = 'javascript:void(0)';
				new Fx.Scroll(window).toTop();
			}
		}
	}),

	systemMessage: $GJ.c.extend({
		init: function() {
			this.mInner = document.getElementById('gj-system-message');
			this.mOuter = this.mInner.parentNode;
			this.message = document.getElementById('gj-system-message-pos');
			this.maxHeight = this.message.offsetHeight;

			this.bContainer = document.getElementById('gj-system-message-button-ground');
			this.button = this.bContainer.getElementsByTagName('a').item(0);

			this.fixedPos = {
				lt: document.getElementById('gj-fixed-left-top-pos'),
				rt: document.getElementById('gj-fixed-right-top-pos'),
				lm: document.getElementById('gj-fixed-left-middle-pos'),
				rm: document.getElementById('gj-fixed-right-middle-pos')
			};
			for (var i in this.fixedPos) {
				this.fixedPos[i] && $GJ.el.addClass(this.fixedPos[i], 'transition-fx position');
			}

			$GJ.ev.add(window, 'load', $GJ.f.bind(this, this.position));
			$GJ.ev.add(window, 'resize', $GJ.f.bind(this, this.position));
			$GJ.ev.add(this.button, 'click', $GJ.f.bind(this, this.toggle));
		},

		position: function() {
			var size = $GJ.get.view.size();
			this.fixedPos.rt && (size.w -= this.fixedPos.rt.offsetWidth);

			if (this.mInner.offsetLeft + this.mInner.offsetWidth + this.bContainer.offsetWidth < size.w) {
				var r	= ((size.w - (this.mInner.offsetLeft + this.mInner.offsetWidth))
						+ this.bContainer.offsetWidth) / 2;
				$GJ.el.setStyle(this.bContainer, 'right', '-' + r + 'px');
			} else if (parseInt($GJ.el.getStyle(this.bContainer, 'right')) != 0) {
				$GJ.el.setStyle(this.bContainer, 'right', 0);
			}
		},

		toggle: function() {
			if ($GJ.el.hasClass(this.mOuter, 'open') || $GJ.el.hasClass(this.mOuter, 'close')) {
				$GJ.el.toggleClass(this.mOuter, 'open', 'close');
			} else {
				$GJ.el.addClass(this.mOuter, this.maxHeight > this.mOuter.offsetHeight ? 'open' : 'close');
			}

			if ($GJ.el.hasClass(this.mOuter, 'open')) {
				$GJ.el.setStyle(this.mOuter, 'height', this.maxHeight + 'px');

				this.fixedPos.lt && $GJ.el.setStyle(this.fixedPos.lt, 'top', this.maxHeight + 'px');
				this.fixedPos.rt && $GJ.el.setStyle(this.fixedPos.rt, 'top', this.maxHeight + 'px');

				if (this.fixedPos.lm || this.fixedPos.rm) {
					var size = $GJ.get.view.size();
					if (this.fixedPos.lm) {
						$GJ.el.setStyle(
							this.fixedPos.lm,
							'top',
							(((size.h + this.maxHeight) - this.fixedPos.lm.offsetHeight) / 2) + 'px'
						);
					}
					if (this.fixedPos.rm) {
						$GJ.el.setStyle(
							this.fixedPos.rm,
							'top',
							(((size.h + this.maxHeight) - this.fixedPos.rm.offsetHeight) / 2) + 'px'
						);
					}
				}
			} else {
				$GJ.el.setStyle(this.mOuter, 'height', 0);

				this.fixedPos.lt && $GJ.el.setStyle(this.fixedPos.lt, 'top', 0);
				this.fixedPos.rt && $GJ.el.setStyle(this.fixedPos.rt, 'top', 0);

				if (this.fixedPos.lm || this.fixedPos.rm) {
					var size = $GJ.get.view.size();
					if (this.fixedPos.lm) {
						$GJ.el.setStyle(
							this.fixedPos.lm,
							'top',
							((size.h - this.fixedPos.lm.offsetHeight) / 2) + 'px'
						);
					}
					if (this.fixedPos.rm) {
						$GJ.el.setStyle(
							this.fixedPos.rm,
							'top',
							((size.h - this.fixedPos.rm.offsetHeight) / 2) + 'px'
						);
					}
				}
			}
		}
	})
};

/**
 * WHA Tour Poster — front-end behaviour.
 *
 * Ported from the inline script of the original static design
 * (Poster variant): mobile menu, season tabs, card rails with dots and
 * mouse dragging, reveal on scroll.
 *
 * Added on top of the reference: keyboard and screen reader support
 * (Escape, focus trap, roving tabindex on the tabs, aria-expanded) and
 * respect for prefers-reduced-motion.
 *
 * Vanilla JS only, no jQuery.
 */
( function () {
	'use strict';

	var FOCUSABLE = 'a[href], area[href], button:not([disabled]), input:not([disabled]):not([type="hidden"]), select:not([disabled]), textarea:not([disabled]), [tabindex]:not([tabindex="-1"])';

	var reducedMotion = window.matchMedia && window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;

	function focusable( container ) {
		return Array.prototype.filter.call(
			container.querySelectorAll( FOCUSABLE ),
			function ( el ) {
				return el.offsetWidth > 0 || el.offsetHeight > 0 || el === document.activeElement;
			}
		);
	}

	/* ---------------------------------------------------------------
	 * Mobile menu
	 * ------------------------------------------------------------- */
	var mnav = document.getElementById( 'mnav' );

	if ( mnav ) {
		var openers = Array.prototype.slice.call( document.querySelectorAll( '[data-menu-open]' ) );
		var closers = Array.prototype.slice.call( document.querySelectorAll( '[data-menu-close]' ) );
		var lastOpener = null;

		function openMenu( opener ) {
			lastOpener = opener || openers[ 0 ] || null;
			mnav.classList.add( 'is-open' );
			mnav.setAttribute( 'aria-hidden', 'false' );
			openers.forEach( function ( b ) {
				b.setAttribute( 'aria-expanded', 'true' );
			} );

			var items = focusable( mnav );
			if ( items.length ) {
				items[ 0 ].focus();
			}
		}

		function closeMenu( returnFocus ) {
			if ( ! mnav.classList.contains( 'is-open' ) ) {
				return;
			}
			mnav.classList.remove( 'is-open' );
			mnav.setAttribute( 'aria-hidden', 'true' );
			openers.forEach( function ( b ) {
				b.setAttribute( 'aria-expanded', 'false' );
			} );

			if ( false !== returnFocus && lastOpener ) {
				lastOpener.focus();
			}
		}

		openers.forEach( function ( b ) {
			b.addEventListener( 'click', function () {
				openMenu( b );
			} );
		} );

		closers.forEach( function ( b ) {
			b.addEventListener( 'click', function () {
				// Links inside the drawer navigate away, so do not steal the focus back.
				closeMenu( 'A' !== b.tagName );
			} );
		} );

		// Any link inside the drawer closes it: in-page anchors would otherwise
		// stay hidden behind the open drawer.
		mnav.addEventListener( 'click', function ( e ) {
			if ( e.target.closest && e.target.closest( 'a[href]' ) ) {
				closeMenu( false );
			}
		} );

		// Escape closes the drawer and returns the focus to the burger.
		document.addEventListener( 'keydown', function ( e ) {
			if ( 'Escape' === e.key && mnav.classList.contains( 'is-open' ) ) {
				e.preventDefault();
				closeMenu( true );
			}
		} );

		// Simple focus trap while the drawer is open.
		mnav.addEventListener( 'keydown', function ( e ) {
			if ( 'Tab' !== e.key || ! mnav.classList.contains( 'is-open' ) ) {
				return;
			}

			var items = focusable( mnav );
			if ( ! items.length ) {
				return;
			}

			var first = items[ 0 ];
			var last = items[ items.length - 1 ];

			if ( e.shiftKey && document.activeElement === first ) {
				e.preventDefault();
				last.focus();
			} else if ( ! e.shiftKey && document.activeElement === last ) {
				e.preventDefault();
				first.focus();
			}
		} );
	}

	/* ---------------------------------------------------------------
	 * Season tabs — the current month picks the default season
	 * (12–2 winter, 3–5 spring, 6–8 summer, 9–11 autumn).
	 * ------------------------------------------------------------- */
	var tabs = Array.prototype.slice.call( document.querySelectorAll( '[role="tab"]' ) );

	if ( tabs.length ) {
		function panelOf( tab ) {
			var id = tab.getAttribute( 'aria-controls' );
			return id ? document.getElementById( id ) : null;
		}

		function selectTab( t, moveFocus ) {
			tabs.forEach( function ( x ) {
				var panel = panelOf( x );
				x.setAttribute( 'aria-selected', 'false' );
				x.setAttribute( 'tabindex', '-1' );
				x.classList.remove( 'is-active' );
				if ( panel ) {
					panel.hidden = true;
				}
			} );

			t.setAttribute( 'aria-selected', 'true' );
			t.setAttribute( 'tabindex', '0' );
			t.classList.add( 'is-active' );

			var current = panelOf( t );
			if ( current ) {
				current.hidden = false;
			}

			if ( moveFocus ) {
				t.focus();
			}
		}

		tabs.forEach( function ( t, i ) {
			t.setAttribute( 'tabindex', 'true' === t.getAttribute( 'aria-selected' ) ? '0' : '-1' );

			t.addEventListener( 'click', function () {
				selectTab( t, false );
			} );

			t.addEventListener( 'keydown', function ( e ) {
				var next = null;

				switch ( e.key ) {
					case 'ArrowLeft':
						next = tabs[ ( i - 1 + tabs.length ) % tabs.length ];
						break;
					case 'ArrowRight':
						next = tabs[ ( i + 1 ) % tabs.length ];
						break;
					case 'Home':
						next = tabs[ 0 ];
						break;
					case 'End':
						next = tabs[ tabs.length - 1 ];
						break;
					default:
						return;
				}

				e.preventDefault();
				selectTab( next, true );
			} );
		} );

		var m = new Date().getMonth() + 1;
		var season = m >= 12 || m <= 2 ? 'winter' : m <= 5 ? 'spring' : m <= 8 ? 'summer' : 'autumn';
		var currentTab = document.getElementById( 'tab-' + season );
		if ( currentTab ) {
			selectTab( currentTab, false );
		}
	}

	/* ---------------------------------------------------------------
	 * Card rails: dots indicator, counter and mouse dragging.
	 * ------------------------------------------------------------- */
	Array.prototype.forEach.call( document.querySelectorAll( '.rail' ), function ( rail ) {
		var items = rail.children;
		var n = items.length;

		if ( ! n ) {
			return;
		}

		var dots = document.createElement( 'div' );
		dots.className = 'rail-dots';
		dots.setAttribute( 'aria-hidden', 'true' );

		for ( var i = 0; i < n; i++ ) {
			dots.appendChild( document.createElement( 'i' ) );
		}

		var counter = document.createElement( 'span' );
		dots.appendChild( counter );
		rail.insertAdjacentElement( 'afterend', dots );

		function update() {
			// `columnGap` is "normal" on some engines, which parses to NaN.
			var gap = parseFloat( getComputedStyle( rail ).columnGap );

			if ( isNaN( gap ) ) {
				gap = 24;
			}

			var w = items[ 0 ].getBoundingClientRect().width + gap;
			var idx = Math.min( n - 1, Math.max( 0, Math.round( rail.scrollLeft / w ) ) );

			Array.prototype.forEach.call( dots.querySelectorAll( 'i' ), function ( d, j ) {
				d.classList.toggle( 'is-on', j === idx );
			} );

			counter.textContent = ( idx + 1 ) + ' / ' + n;
		}

		rail.addEventListener( 'scroll', update, { passive: true } );
		update();

		// Mouse dragging on the desktop; touch devices keep the native swipe.
		var down = false;
		var startX = 0;
		var startL = 0;
		var moved = false;

		rail.addEventListener( 'mousedown', function ( e ) {
			if ( 0 !== e.button ) {
				return;
			}
			down = true;
			moved = false;
			startX = e.pageX;
			startL = rail.scrollLeft;
		} );

		window.addEventListener( 'mousemove', function ( e ) {
			if ( ! down ) {
				return;
			}

			var dx = e.pageX - startX;

			if ( ! moved && Math.abs( dx ) < 4 ) {
				return;
			}

			if ( ! moved ) {
				moved = true;
				rail.classList.add( 'is-dragging' );
			}

			rail.scrollLeft = startL - dx;
			e.preventDefault();
		} );

		window.addEventListener( 'mouseup', function () {
			if ( ! down ) {
				return;
			}

			down = false;

			if ( moved ) {
				rail.classList.remove( 'is-dragging' );
				var w = items[ 0 ].getBoundingClientRect().width + 24;
				rail.scrollTo( {
					left: Math.round( rail.scrollLeft / w ) * w,
					behavior: reducedMotion ? 'auto' : 'smooth'
				} );
			}
		} );

		// Swallow the click that ends a drag so the card link is not followed.
		rail.addEventListener( 'click', function ( e ) {
			if ( moved ) {
				e.preventDefault();
				e.stopPropagation();
			}
		}, true );
	} );

	/* ---------------------------------------------------------------
	 * Reveal on load / scroll.
	 * ------------------------------------------------------------- */
	var targets = Array.prototype.slice.call( document.querySelectorAll( '[data-reveal]' ) );

	if ( reducedMotion || ! ( 'IntersectionObserver' in window ) ) {
		targets.forEach( function ( el ) {
			el.classList.add( 'is-in' );
		} );
	} else {
		var io = new IntersectionObserver( function ( entries ) {
			entries.forEach( function ( entry ) {
				if ( ! entry.isIntersecting ) {
					return;
				}

				var delay = +entry.target.getAttribute( 'data-delay' ) || 0;

				setTimeout( function () {
					entry.target.classList.add( 'is-in' );
				}, delay );

				io.unobserve( entry.target );
			} );
		}, { threshold: 0.12 } );

		targets.forEach( function ( el ) {
			io.observe( el );
		} );
	}
}() );
